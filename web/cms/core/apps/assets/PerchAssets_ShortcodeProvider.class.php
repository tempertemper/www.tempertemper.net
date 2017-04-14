<?php

class PerchAssets_ShortcodeProvider extends PerchShortcode_Provider
{
	public $shortcodes = ['asset'];

	public function get_shortcode_replacement($Shortcode, $Tag)
	{
		$assetID = $Shortcode->arg(0);

		$data = [];

		$Assets = new PerchAssets_Assets;
		$Asset  = $Assets->find($assetID);
		if ($Asset) {
			$data = $Asset->to_array();
		}

		$Tag->remap_attributes('image', '');

		$Tag->set_bulk($Shortcode->get_args());

		$Tag->set('type', 'image');
		$Tag->set('output', 'tag');

		$FieldType = PerchFieldTypes::get('image', null, $Tag, [$Tag]);

		$data[$Tag->id().'_assetID'] = $assetID;

		$raw = $FieldType->get_raw($data);

		$processed  = $FieldType->get_processed($raw);

		return $processed;
	}
}