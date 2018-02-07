<?php

class PerchImage
{
    private $mode = false;
    
    // Image quality for JPEGs (0 to 99)
    private $jpeg_quality = 90;

    // Compression rate for PNGs (0 to 9)
    private $png_compression = 9;

    // Pixel density
    private $density = 1;

    // Sharpening
    private $sharpening = 4;

    // Progressive JPEGs
    private $progressive_jpeg = true;
    
    private $box_constrain = true;
    
    public function __construct()
    {
        if (!defined('PERCH_IMAGE_LIB')) define('PERCH_IMAGE_LIB', 'GD');

        if (strtolower(PERCH_IMAGE_LIB)=='imagick' && extension_loaded('imagick') && class_exists('Imagick')) {
            $this->mode = 'imagick';
        }

        if (strtolower(PERCH_IMAGE_LIB)=='gd' && extension_loaded('gd')) {
            $this->mode = 'gd';
        }   

        if (!defined('PERCH_PROGRESSIVE_JPEG')) define('PERCH_PROGRESSIVE_JPEG', true);

        $this->progressive_jpeg = PERCH_PROGRESSIVE_JPEG;
    }

    public function reset_defaults()
    {
        // Compression quality for JPEGs
        $this->jpeg_quality = 90;
        $this->png_compression  = 9;

        // Pixel density
        $this->density = 1;

        // Sharpening
        $this->sharpening = 4;
    }
    
    public function resize_image($image_path, $target_w=false, $target_h=false, $crop=false, $suffix=false)
    {
        $Perch = Perch::fetch();

        $bail = false;

        if ($this->mode === false) return false; 
        
        if ($crop) {
            PerchUtil::debug('Resizing and cropping image... ('.$this->mode.', '.sprintf('w%d h%d @%dx %s', $target_w, $target_h, $this->density, $suffix).')');
        }else{
            PerchUtil::debug('Resizing image... ('.$this->mode.', '.sprintf('w%d h%d @%dx %s', $target_w, $target_h, $this->density, $suffix).')');
        }        
        
        $info = getimagesize($image_path);
        
        // WebP?
        if (!is_array($info)) {
            if ($this->is_webp($image_path)) {
                $info = $this->get_webp_size($image_path);
            }
        }

        // SVG?
        $svg = false;
        if (!is_array($info)) {
            // $svg gets populated with the mime type if it's an SVG
            $svg = $this->is_svg($image_path);
            if ($svg) {
                $info = $this->get_svg_size($image_path);

                // Can't crop SVG
                $crop = false;
            }
        }

        if (!is_array($info)) return false;
        
        if ($svg) {
            // Only need one SVG file for all sizes.
            $save_as = $image_path;
        }else{
            $save_as = $this->get_resized_filename($image_path, $target_w, $target_h, $suffix, $this->density);    
        }
        
        $image_w = $info[0];
        $image_h = $info[1];
        
        $crop_x  = 0;
        $crop_y  = 0;
        $crop_w  = 0;
        $crop_h  = 0;
        
        $image_ratio = $image_w/$image_h;

        if ($svg) {

            // Constrain by width
            if ($target_w) {
                $new_w = $target_w;
                $new_h = $target_w/$image_ratio;
            }
            
            // Constrain by height
            if ($target_h) {
                $new_h = $target_h;
                $new_w = $target_h*$image_ratio;
            }

        }else{

            // Constrain by width
            if ($target_w && $image_w>=$target_w) {
                $new_w = $target_w;
                $new_h = $target_w/$image_ratio;
            }
            
            // Constrain by height
            if ($target_h && $image_h>=$target_h) {
                $new_h = $target_h;
                $new_w = $target_h*$image_ratio;
            }

        }

        
        // Both specified, and crop set
        if ($target_w && $target_h && $crop) {

                $crop_w = $target_w;
                $crop_h = $target_h;
                
                $crop_ratio = $crop_w/$crop_h;
                            
                if ($image_ratio >= $crop_ratio) {
                    // Landscape or square crop 
                    $new_h = (int)$target_h;
                    $new_w = $target_h*$image_ratio;
                    $crop_y = 0;
                    $crop_x = ($new_w/2)-($target_w/2);
                }

                if ($crop_ratio > $image_ratio) {
                    // Portrait crop                   
                    $new_w = (int)$target_w;
                    $new_h = $target_w/$image_ratio;
                    $crop_x = 0;
                    $crop_y = ($new_h/2)-($target_h/2);

                }
                
            // Check we're not cropping upwardly
            if ($crop_w > $image_w || $crop_h > $image_h) {
                $crop_x  = 0;
                $crop_y  = 0;
                $crop_w  = 0;
                $crop_h  = 0;
                
                $crop    = false;
            }

            //PerchUtil::debug("Crop info: $crop_x, $crop_y, $crop_w, $crop_h");
        }
                
        if ($target_w && $target_h && !$crop) {

                // Normal resize
                if ($this->box_constrain) {                    
                    if (($image_w / $target_w) > ($image_h / $target_h)) {
                        $new_w = $target_w;
                        $new_h = $target_w/$image_ratio;
                    } else {
                        $new_h = $target_h;
                        $new_w = $target_h*$image_ratio;
                    }
                }else{
                    if ($image_w > $image_h) {
                        $new_w = $target_w;
                        $new_h = $target_w/$image_ratio;
                    }

                    if ($image_h > $image_w) {
                        $new_h = $target_h;
                        $new_w = $target_h*$image_ratio;
                    }
                }
                    
        }
        
        // Default
        if (!isset($new_w)) {
            $new_w = $image_w;
            $new_h = $image_h;
        }
        
        
        // Prepare returned array
        $out = array();
        $out['w'] = (int) $new_w;
        $out['h'] = (int) $new_h;
        $out['file_path'] = $save_as;
        $parts = explode(DIRECTORY_SEPARATOR, $save_as);
        $out['file_name'] = array_pop($parts);
        $out['web_path'] = str_replace(PERCH_RESFILEPATH.DIRECTORY_SEPARATOR, PERCH_RESPATH.'/', $save_as);
        $out['density'] = $this->density;


        // If SVG, we can return at this point.
        if ($svg) {
            $out['mime'] = $svg;
            return $out;
        }
        


        if ($crop) {
            if ($crop_w) $out['w'] = (int) $crop_w;
            if ($crop_h) $out['h'] = (int) $crop_h;
        }

                
        // Check we're not upsizing
        if ($crop) {
            if ($crop_w > $image_w || $crop_h > $image_h) {
                $bail = true;
            }
        }else{
            if ($new_w > $image_w || $new_h > $image_h) {
                $bail = true;
            }
        }
        
        // Check we're not resizing to the same exact size, as this just kills quality
        if ($crop) {
            if ($crop_w == $image_w && $crop_h == $image_h) {
                $bail = true;
            }
        }else{
            if ($new_w == $image_w && $new_h == $image_h) {
                $bail = true;
            }
        }

        

        
        // Bail? 
        if ($bail) {
            copy($image_path, $save_as);
            PerchUtil::set_file_permissions($save_as);
            
            // reset sizes
            $out['w'] = (int) $image_w;
            $out['h'] = (int) $image_h;

            $Perch->event('assets.create_image', new PerchAssetFile($out));
            return $out;
        }
        
        
        // Density 
        $new_w  = floor($new_w * $this->density);
        $new_h  = floor($new_h * $this->density);
        $crop_w = floor($crop_w * $this->density);
        $crop_h = floor($crop_h * $this->density);
        $crop_x = floor($crop_x * $this->density);
        $crop_y = floor($crop_y * $this->density);

        //PerchUtil::debug('Density: '.$this->density);


        $r = false;
        
        if ($this->mode == 'gd') {
            $r = $this->resize_with_gd($image_path, $save_as, $new_w, $new_h, $crop_w, $crop_h, $crop_x, $crop_y); 
        }
        
        if ($this->mode == 'imagick') {
            $r = $this->resize_with_imagick($image_path, $save_as, $new_w, $new_h, $crop_w, $crop_h, $crop_x, $crop_y);
        }

        if ($r) $out['mime'] = $r;
        
        PerchUtil::set_file_permissions($save_as);

        $Perch->event('assets.create_image', new PerchAssetFile($out));

        if ($r) return $out;
        
        return false;
    }

    public function get_resize_profile($image_path)
    {
        $info = getimagesize($image_path);
        
        // WebP?
        if (!is_array($info)) {
            if ($this->is_webp($image_path)) {
                $info = $this->get_webp_size($image_path);
            }
        }

        // SVG?
        $svg = false;
        if (!is_array($info)) {
            // $svg gets populated with the mime type if it's an SVG
            $svg = $this->is_svg($image_path);
            if ($svg) {
                $info = $this->get_svg_size($image_path);
            }
        }

        if (!is_array($info)) return false;
        
        $save_as = $image_path;
       
        $image_w = $info[0];
        $image_h = $info[1];

        $new_w = $image_w;
        $new_h = $image_h;
        
        
        // Prepare returned array
        $out              = array();
        $out['w']         = (int) $new_w;
        $out['h']         = (int) $new_h;
        $out['file_path'] = $save_as;
        $parts            = explode(DIRECTORY_SEPARATOR, $save_as);
        $out['file_name'] = array_pop($parts);
        $out['web_path']  = str_replace(PERCH_RESFILEPATH.DIRECTORY_SEPARATOR, PERCH_RESPATH.'/', $save_as);
        $out['density']   = $this->density;


        // If SVG, we can return at this point.
        if ($svg) {
            $out['mime'] = $svg;
        }
        
        return $out;
    }
    
    
    public function get_resized_filename($image_path, $w=0, $h=0, $suffix=false, $density=1)
    {
        if ($suffix) {
            $suffix = '-'.$suffix;
        }else{
            $suffix = '-';
            if ($w) $suffix .= 'w'.$w;
            if ($h) $suffix .= 'h'.$h;
        }

        if ((float)$density!=1) {
            $suffix .= '@'.$density.'x';
        }

        $file_ex = '.'.PerchUtil::file_extension($image_path);
        return str_replace($file_ex, $suffix.$file_ex, $image_path);
        
    }
    
    public function set_quality($quality)
    {
        $this->jpeg_quality = intval($quality);
    }

    public function set_density($density=1)
    {
        $this->density = floatval($density);
    }

    public function get_density()
    {
        return $this->density;
    }

    public function set_sharpening($sharpening=false)
    {
        $this->sharpening = $sharpening;
    }

    public function is_svg($image_path) 
    {
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $mime_type = finfo_file($finfo, $image_path);  
                finfo_close($finfo);

                if (strpos($mime_type, 'svg')) {
                    return $mime_type;
                }
            } 
        }else{
            // This is deprecated, but throw me a bone
            if (function_exists('mime_content_type')) {
                $mime_type = mime_content_type($image_path);
                if ($mime_type && strpos($mime_type, 'svg')) {
                    return $mime_type;
                }
            }
        }

        // This is not an exact science, people.
        if (isset($mime_type)) {
            if ($mime_type=='text/plain' && PerchUtil::file_extension($image_path)=='svg') {
                return 'image/svg+xml';
            }
            if ($mime_type=='text/plain' && PerchUtil::file_extension($image_path)=='svgz') {
                return 'image/svg+xml';
            }
        }

        return false;
    }

    public function is_webp($image_path)
    {
        return false; // disabled for now. PHP isn't ready for WebP.
        // stop-gap
        return PerchUtil::file_extension($image_path)=='webp';
    }

    public function get_svg_size($image_path) 
    {
        if (class_exists('SimpleXMLElement')) {
            $XML = new SimpleXMLElement(file_get_contents($image_path));
            if (is_object($XML)) {
                $out = array();
                $out['w'] = (int)$XML->attributes()->width;
                $out['h'] = (int)$XML->attributes()->height;
                $out['mime'] = 'image/svg+xml';

                // Compatibility with getimagesize
                $out[0] = $out['w'];
                $out[1] = $out['h'];
                return $out;
            }
            
        }
        return array(0=>150, 1=>150, 'w'=>150, 'h'=>150, 'mime'=>'image/svg+xml');
    }

    public function get_webp_size($image_path)
    {
        if (function_exists('imagecreatefromwebp')) {
            $orig_image = imagecreatefromwebp($image_path);           
            $out = array();
            $out[0] = imagesx($orig_image);
            $out[1] = imagesy($orig_image);
            return $out;    
        }
        return false;
    }

    public function parse_file_name($image_path)
    {
        if (strpos($image_path, '-')!==false) {
            $s     = PerchUtil::strip_file_extension($image_path);    
            $parts = explode('-', $s);
            $meta  = array_pop($parts);

            if (substr($meta, 0, 5)=='thumb') {
                
                $meta = str_replace('thumb', 'w150h150', $meta);
            }

            $pattern = '#(w[0-9]*){0,1}-{0,1}(h[0-9]*){0,1}-{0,1}(c[01]){0,1}(@[0-9\.]*x){0,1}#';
            if (preg_match($pattern, $meta, $match)) {
                if (isset($match[1])) {

                    PerchUtil::debug($image_path);
                    PerchUtil::debug($match);
                    $out = array(
                        'w'=>null,
                        'h'=>null,
                        'd'=>null,
                        'c'=>null,
                    );

                    if (isset($match[1])) $out['w'] = substr($match[1], 1);
                    if (isset($match[2])) $out['h'] = substr($match[2], 1);
                    if (isset($match[3])) $out['c'] = substr($match[3], 1);
                    if (isset($match[4])) $out['d'] = substr($match[4], 1, strlen($match[4])-2);

                    if ($out['w']=='')   $out['w'] = null;
                    if ($out['h']=='')   $out['h'] = null;
                    if ($out['c']!=='1') $out['c'] = 0;
                    if ($out['d']=='')   $out['d']  = 1;

                    return $out;
                }
                
            }
        }

        return false;
    }
    
    public function thumbnail_file($file_path, $width=150, $height=150, $suffix='thumb')
    {
        if (class_exists('Imagick')) {
            $w       = $width;
            $h       = $height;
            $save_as = $this->get_resized_filename($file_path, $w, $h, $suffix, $this->density);    
            $save_as = PerchUtil::strip_file_extension($save_as).'.png';
            return $this->thumbnail_file_with_imagick($file_path, $save_as, $w, $h);
        }
        
        return false;
    }

    public function orientate_image($file_path)
    {
        if (!function_exists('exif_read_data')) return;

        if (defined('PERCH_ENABLE_EXIF') && PERCH_ENABLE_EXIF == false) return;

        if (exif_imagetype($file_path) != IMAGETYPE_JPEG) return;

        $exif = exif_read_data($file_path);
        if ($exif && is_array($exif) && isset($exif['Orientation'])) {
            $orientation = (int) $exif['Orientation'];

            switch($orientation) {
                case 1:
                    return;
                    break;

                case 2:
                    $rotate = 0;
                    $flip   = true;
                    break;

                case 3:
                    $rotate = 180;
                    $flip   = false;
                    break;

                case 4:
                    $rotate = 180;
                    $flip   = true;
                    break;

                case 5:
                    $rotate = 270;
                    $flip   = true;
                    break;

                case 6:
                    $rotate = 270;
                    $flip   = false;
                    break;

                case 7:
                    $rotate = 90;
                    $flip   = true;
                    break;

                case 8:
                    $rotate = 90;
                    $flip   = false;
                    break;
            }

            if ($this->mode == 'gd') {
                $this->rotate_with_gd($file_path, $rotate, $flip); 
            }
            
            if ($this->mode == 'imagick') {
                $this->rotate_with_imagick($file_path, $rotate, $flip);
            }

        }

        return;
    }

    private function resize_with_gd($image_path, $save_as, $new_w, $new_h, $crop_w, $crop_h, $crop_x, $crop_y)
    {   
        if ($this->is_webp($image_path)) {
            $mime    = 'image/webp';
        }else{
            $info = getimagesize($image_path);
            if (!is_array($info)) return false;
            
            $image_w = $info[0];
            $image_h = $info[1];
            $mime    = $info['mime'];
        }

      
        $crop    = false;
        if ($crop_w != 0 && $crop_h != 0) $crop = true;
        
        if (function_exists('imagecreatetruecolor')) {
            $new_image = imagecreatetruecolor($new_w, $new_h);
            if ($crop) $crop_image = imagecreatetruecolor($crop_w, $crop_h);
        }else{
            $new_image = imagecreate($new_w, $new_h);
            if ($crop) $crop_image = imagecreate($crop_w, $crop_h);
        }

        $real_save_as = $save_as;
        $save_as = tempnam(PERCH_RESFILEPATH, '_gd_tmp_');

        
        switch ($mime) {
            case 'image/jpeg':
                $orig_image = imagecreatefromjpeg($image_path);
                
                if (function_exists('imagecopyresampled')) {
                    imagecopyresampled($new_image, $orig_image, 0, 0, 0, 0, $new_w, $new_h, $image_w, $image_h);
                }else{
                    imagecopyresized($new_image, $orig_image, 0, 0, 0, 0, $new_w, $new_h, $image_w, $image_h);
                }

                // sharpen
                if ($this->sharpening) {
                    $new_image = $this->sharpen_with_gd($new_image, $this->sharpening);
                }

                // progressive jpg?
                if ($this->progressive_jpeg) {
                    imageinterlace($new_image, 1);    
                }
                
                
                if ($crop) {
                    imagecopy($crop_image, $new_image, 0, 0, $crop_x, $crop_y, $new_w, $new_h);
                    imagejpeg($crop_image, $save_as, $this->jpeg_quality);
                }else{
                    imagejpeg($new_image, $save_as, $this->jpeg_quality);
                }
                                
                break;
                               
            case 'image/gif':
                $orig_image = imagecreatefromgif($image_path);
                imagetruecolortopalette($new_image, true, 256);                             
                $new_image = $this->gd_set_transparency($new_image, $orig_image);

                if (function_exists('imagecopyresampled')) {
                    imagecopyresampled($new_image, $orig_image, 0, 0, 0, 0, $new_w, $new_h, $image_w, $image_h);
                }else{
                    imagecopyresized($new_image, $orig_image, 0, 0, 0, 0, $new_w, $new_h, $image_w, $image_h);
                }

                if ($crop) {
                    imagetruecolortopalette($crop_image, true, 256);            
                    $crop_image = $this->gd_set_transparency($crop_image, $new_image);
                    imagecopy($crop_image, $new_image, 0, 0, $crop_x, $crop_y, $new_w, $new_h);
                    imagegif($crop_image, $save_as);
                }else{
                    imagegif($new_image, $save_as);
                }

                break;
                
            case 'image/png':
                $orig_image = imagecreatefrompng($image_path);           

                imagealphablending($new_image, false);
                imagesavealpha($new_image, true);

                if (function_exists('imagecopyresampled')) {
                    imagecopyresampled($new_image, $orig_image, 0, 0, 0, 0, $new_w, $new_h, $image_w, $image_h);
                }else{
                    imagecopyresized($new_image, $orig_image, 0, 0, 0, 0, $new_w, $new_h, $image_w, $image_h);
                }

                // sharpen
                if ($this->sharpening) {
                    $colour = imagecolorat($new_image, 0, 0);
                    $new_image = $this->sharpen_with_gd($new_image, floor($this->sharpening/2));
                    imagesetpixel($new_image, 0, 0, $colour);
                }

                if ($crop) {                    
                    imagealphablending($crop_image, false);
                    imagesavealpha($crop_image, true);
                    
                    imagecopy($crop_image, $new_image, 0, 0, $crop_x, $crop_y, $new_w, $new_h);
                    imagepng($crop_image, $save_as, $this->png_compression);
                }else{
                    imagepng($new_image, $save_as, $this->png_compression);
                }
                
                break;
            
            case 'image/webp':
                $orig_image = imagecreatefromwebp($image_path);           

                $image_w = imagesx($orig_image);
                $image_h = imagesy($orig_image);

                imagealphablending($new_image, false);
                imagesavealpha($new_image, true);
                          
                if (function_exists('imagecopyresampled')) {
                    imagecopyresampled($new_image, $orig_image, 0, 0, 0, 0, $new_w, $new_h, $image_w, $image_h);
                }else{
                    imagecopyresized($new_image, $orig_image, 0, 0, 0, 0, $new_w, $new_h, $image_w, $image_h);
                }

                // sharpen
                if ($this->sharpening) {
                    $colour = imagecolorat($new_image, 0, 0);
                    $new_image = $this->sharpen_with_gd($new_image, $this->sharpening);
                    imagesetpixel($new_image, 0, 0, $colour);
                }

                if ($crop) {                    
                    imagealphablending($crop_image, false);
                    imagesavealpha($crop_image, true);
                    
                    imagecopy($crop_image, $new_image, 0, 0, $crop_x, $crop_y, $new_w, $new_h);
                    imagewebp($crop_image, $save_as);
                }else{
                    imagewebp($new_image, $save_as);
                }
                
                break;


            default: 
                $orig_image = imagecreatefromjpeg($image_path);
                break;
        }
        
        imagedestroy($orig_image);
        imagedestroy($new_image);    
        if ($crop) imagedestroy($crop_image);

        if (isset($orig_image)) unset($orig_image);
        if (isset($new_image))  unset($new_image);
        if (isset($crop_image)) unset($crop_image);

        copy($save_as, $real_save_as);
        unlink($save_as);
        
        return $mime;
        
    }
    
    private function sharpen_with_gd($image, $setting=4)
    {
        if (!function_exists('imageconvolution')) return $image;

        // These versions have a bug with imageconvolution() so skip.
        if (version_compare(PHP_VERSION, '5.5.9')===0) return $image;
        if (version_compare(PHP_VERSION, '5.5.10')===0) return $image;

        switch($setting) {
            case 0:  return $image; // no sharpening
            case 1:  $amount = 4; break;
            case 2:  $amount = 5; break;
            case 3:  $amount = 6; break;
            case 4:  $amount = 8; break;
            case 5:  $amount = 9; break;
            case 6:  $amount = 14; break;
            case 7:  $amount = 19; break;
            case 8:  $amount = 24; break;
            case 9:  $amount = 32; break;
            case 10: $amount = 48; break;
        }

        /**
            Matrix calculations from Intervention Image.
            MIT licensed. Copyright (c) 2014 Oliver Vogel.
            Thanks Oliver.
        **/
        $min = $amount >= 10 ? $amount * -0.01 : 0;
        $max = $amount * -0.025;
        $abs = ((4 * $min + 4 * $max) * -1) + 1;
        $div = 1;

        $matrix = array(
            array($min, $max, $min),
            array($max, $abs, $max),
            array($min, $max, $min)
        );

        imageconvolution($image, $matrix, $div, 0);

        return $image;
    }

    
    private function gd_set_transparency($new_image, $orig_image)
    {

        $transparencyIndex = imagecolortransparent($orig_image);
        $transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255);

        if ($transparencyIndex >= 0) {
            if ($transparencyIndex < imagecolorstotal($orig_image)) {
                $transparencyColor = imagecolorsforindex($orig_image, $transparencyIndex);
            }
        }

        $transparencyIndex = imagecolorallocate($new_image, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']);
        imagefill($new_image, 0, 0, $transparencyIndex);
        imagecolortransparent($new_image, $transparencyIndex);  

        return $new_image;      

    }
    
    
    private function resize_with_imagick($image_path, $save_as, $new_w=0, $new_h=0, $crop_w, $crop_h, $crop_x, $crop_y)
    {    
        $crop    = false;
        if ($crop_w != 0 && $crop_h != 0) $crop = true;
        
        $real_save_as = $save_as;
        $save_as = tempnam(PERCH_RESFILEPATH, '_im_tmp_');

        $Image = new Imagick();
        $Image->readImage($image_path);
        $Image->thumbnailImage($new_w, $new_h);
        
        if ($crop) {
            $Image->cropImage($crop_w, $crop_h, $crop_x, $crop_y);
        }

        // sharpen
        if ($this->sharpening) {
            $Image->unsharpMaskImage(0, $this->sharpening/10, $this->sharpening/2, 0.05);
        }
        
        $mime = 'image/'.$Image->getImageFormat();

        // jpg optimisations
        if (strtolower($mime) == 'image/jpeg') {

            // progressive jpg?
            if ($this->progressive_jpeg) {
                $Image->setInterlaceScheme(Imagick::INTERLACE_PLANE);    
            }
            
            $Image->setImageCompressionQuality($this->jpeg_quality);

            $profiles = $Image->getImageProfiles("icc", true);

            $Image->stripImage();

            if (!empty($profiles)) {
                $Image->profileImage("icc", $profiles['icc']);
            }

        }

        $Image->writeImage($save_as);
        $Image->destroy();        

        copy($save_as, $real_save_as);
        unlink($save_as);


        return $mime;    
    }

    private function thumbnail_file_with_imagick($file_path, $save_as, $w=0, $h=0)
    {
        try {
            $real_save_as = $save_as;
            $save_as = tempnam(PERCH_RESFILEPATH, '_im_tmp_');

            $Image = new Imagick($file_path.'[0]');
            $Image->setImageFormat('jpg');

            if ($Image->getImageHeight() <= $Image->getImageWidth()) {
                $Image->thumbnailImage($w*$this->density,0);
            }else{
                $Image->thumbnailImage(0,$w*$this->density);
            }           

            // progressive jpg?
            if ($this->progressive_jpeg) {
                $Image->setInterlaceScheme(Imagick::INTERLACE_PLANE);
            }

            $Image->writeImage($save_as);
            
            copy($save_as, $real_save_as);
            unlink($save_as);
            $save_as = $real_save_as;

            $out = array();
            $out['w']         = (int) $Image->getImageWidth()/$this->density;
            $out['h']         = (int) $Image->getImageHeight()/$this->density;
            $out['file_path'] = $save_as;
            $parts            = explode(DIRECTORY_SEPARATOR, $save_as);
            $out['file_name'] = array_pop($parts);
            $out['web_path']  = str_replace(PERCH_RESFILEPATH.DIRECTORY_SEPARATOR, PERCH_RESPATH.'/', $save_as);
            $out['density']   = $this->density;
            $out['mime']      = 'image/'.$Image->getImageFormat();

            $Image->destroy(); 

            return $out;
        }catch(Exception $e) {
            PerchUtil::debug('Unable to create thumbnail', 'error');
            PerchUtil::debug($e, 'error');
        }

        return false;
    }
    

    private function rotate_with_gd($image_path, $degrees, $flip) 
    {
        if ($this->is_webp($image_path)) {
            $mime    = 'image/webp';
        }else{
            $info = getimagesize($image_path);
            if (!is_array($info)) return false;
            
            $image_w = $info[0];
            $image_h = $info[1];
            $mime    = $info['mime'];
        }

        switch ($mime) {
            case 'image/jpeg':
                $orig_image = imagecreatefromjpeg($image_path);
                $new_image  = imagerotate($orig_image, $degrees, 0);
                if ($flip && function_exists('imageflip')) {
                    imageflip($new_image, IMG_FLIP_HORIZONTAL);
                }
                imagejpeg($new_image, $image_path, 100);

                imagedestroy($orig_image);
                imagedestroy($new_image);  

                break;
        }

        return;
    }

    private function rotate_with_imagick($image_path, $degrees, $flip) 
    {
        $degrees = $this->normalize_imagick_rotation($degrees);

        $Image = new Imagick();
        $Image->readImage($image_path);
        if ($degrees > 0) {
            $Image->rotateImage(new ImagickPixel(), $degrees);
        }
        if ($flip) {
            $Image->flopImage();
        }
        $Image->writeImage($image_path);
        $Image->destroy();  
        return;
    }

    private function normalize_imagick_rotation($value)
    {
        if ($value == 0 || $value == 180) {
            return $value;
        }
        if ($value < 0 || $value > 360) {
            $value = 90;
        }

        $total_degree = 360;
        $output = intval($total_degree-$value);
        return $output;
    }
}