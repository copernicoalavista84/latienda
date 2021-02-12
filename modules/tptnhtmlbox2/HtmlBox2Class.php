<?php

class HtmlBox2 extends ObjectModel
{
	public $id;
	public $id_shop;
	public $icon_info;
	public $bkg_info;
	public $text_info;
	public $url_info;

	public static $definition = array(
		'table' => 'tptnhtmlbox2',
		'primary' => 'id_info',
		'multilang' => true,
		'fields' => array(
			'id_shop' =>	array('type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedId'),
			'icon_info' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
			'bkg_info' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
			'text_info' =>	array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true),
			'url_info' =>	array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isUrl'),
		)
	);

}
