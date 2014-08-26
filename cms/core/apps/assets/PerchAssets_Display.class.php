<?php

class PerchAssets_Display
{
	static public function grid_item($Asset)
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
				'class'=>'grid-asset asset-'.$Asset->get_type().($has_thumb ? '' : ' asset-icon')
			));
			
			if ($has_thumb) {
				$s .= self::thumbnail($Asset);	
			}
			
				$s .= PerchXMLTag::create('div', 'opening', array('class'=>'asset-meta'));
					$s .= PerchXMLTag::create('span', 'opening', array('class'=>'title'));
						/*
						$s .= PerchXMLTag::create('input', 'single', array(
								'type'=>'checkbox',
								));
						*/
						$s .= $Asset->resourceTitle();
					$s .= PerchXMLTag::create('span', 'closing');
				$s .= PerchXMLTag::create('div', 'closing');
			$s .= PerchXMLTag::create('div', 'closing');
		$s .= PerchXMLTag::create('a', 'closing');
		return $s;
	}

	static public function thumbnail($Asset)
	{
		return PerchXMLTag::create('img', 'single', array(
			'src'    => $Asset->thumb_url(),
			'width'  => $Asset->thumb_display_width(),
			'height' => $Asset->thumb_display_height(),
			'alt'	 => $Asset->resourceTitle(),
			'class'  => 'thumb',
			));
	}
}