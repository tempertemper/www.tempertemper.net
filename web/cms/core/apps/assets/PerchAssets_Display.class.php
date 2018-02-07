<?php

class PerchAssets_Display
{

	static public function grid_item(PerchAssets_Asset $Asset, PerchAPI_HTML $HTML)
	{
		$has_thumb = false;
		if ($Asset->thumb()!='' || $Asset->is_svg()) {
			$has_thumb = true;
		}

		$s = '';
		$s .= PerchXMLTag::create('a', 'opening', array(
				'href' => PERCH_LOGINPATH.'/core/apps/assets/edit/?id='.$Asset->id()
			));
			$s .= PerchXMLTag::create('div', 'opening', array(
				'class'=>'grid-asset asset-'.$Asset->get_type().($has_thumb ? '' : ' asset-display-thumbless')
			));
			
			if ($has_thumb) {
				$s .= self::thumbnail($Asset);	
			} else {
				$s .= PerchUI::icon($Asset->icon_for_type(), 64);
			}
			
				$s .= PerchXMLTag::create('div', 'opening', array('class'=>'asset-meta'));
					$s .= PerchXMLTag::create('span', 'opening', array('class'=>'title'));
						/*
						$s .= PerchXMLTag::create('input', 'single', array(
								'type'=>'checkbox',
								));
						*/
						$s .= $HTML->encode($Asset->resourceTitle());
					$s .= PerchXMLTag::create('span', 'closing');
				$s .= PerchXMLTag::create('div', 'closing');
			$s .= PerchXMLTag::create('div', 'closing');
		$s .= PerchXMLTag::create('a', 'closing');
		return $s;
	}

	static public function thumbnail($Asset)
	{
		$w = $Asset->thumb_display_width();
		$h = $Asset->thumb_display_height();
		$mode = 'landscape';

		if ($h>$w) $mode = 'portrait';

		if ($h==$w) $mode = 'square';

		return PerchXMLTag::create('img', 'single', array(
			'src'    => $Asset->thumb_url(),
			//'width'  => $w,
			//'height' => $h,
			'alt'	 => $Asset->resourceTitle(),
			'class'  => 'thumb '.$mode,
			));
	}
}