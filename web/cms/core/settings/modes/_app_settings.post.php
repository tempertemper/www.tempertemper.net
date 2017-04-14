<?php
    
    if (PerchUtil::count($app_settings)) {
        $prev_app_id = 'perch_content';
        $settings_copy = $app_settings;
        reset($settings_copy);

        foreach($app_settings as $id=>$setting) {
            if ($setting['app_id']!=$prev_app_id) {
                $app = $Perch->get_app($setting['app_id']);
                if (PerchUtil::count($app)) echo '<h2 id="'.PerchUtil::html(($app['id']=='content'?'perch_content':$app['id'])).'" class="divider"><div>'.PerchUtil::html($app['label']).'</div></h2>';
            
                $API   = new PerchAPI(1, $app['id']);
                $Lang  = $API->get('Lang');
            }
            
            $c = '';
            $next_item = next($settings_copy);
            
            if ($next_item) {
                if ($next_item['app_id']!=$setting['app_id']) {
                    $c = ' last';
                }
            }
?>
        <div class="field-wrap <?php echo $Form->error($id, false); echo $c; if ($setting['type']=='checkbox') echo ' checkbox-single'; ?>">
            <?php echo $Form->label($id, ($app['id']=='content' ? PerchLang::get($setting['label']) : $Lang->get($setting['label'])), false, false, false); ?>
            <div class="form-entry">
            <?php 
            
                switch($setting['type']) {
                    case 'text':
                        echo $Form->text($id, $Form->get($details, $id, $setting['default'])); 
                        break;
                    case 'checkbox':
                        echo $Form->checkbox($id, '1', $Form->get($details, $id)); 
                        break;
                    case 'textarea':
                        echo $Form->textarea($id, $Form->get($details, $id, $setting['default'])); 
                        break;
                    case 'select':
                        if (PerchUtil::count($setting['opts'])) {
                            foreach ($setting['opts'] as &$opt) {
                                if (isset($opt['label'])) {
                                    $opt['label'] = $Lang->get($opt['label']);
                                }
                            }
                        }
                        echo $Form->select($id, $setting['opts'], $Form->get($details, $id, $setting['default'])); 
                        break;                  
                    default:
                        if (is_callable($setting['type'])) {
                            echo call_user_func($setting['type'], $Form, $id, $details, $setting);
                        }else{
                            echo $Form->text($id, $Form->get($details, $id, $setting['default'])); 
                        }
                        break;
                }
                
                if ($setting['hint']) {
                    echo $Form->hint($setting['hint']);
                }
                
                $prev_app_id = $setting['app_id'];
            ?>
            </div>
        </div>

<?php            
        }

        $Lang = false;
    }
