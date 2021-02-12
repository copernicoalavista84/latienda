<?php

class HeaderLinks extends ObjectModel
{
	public $id;	
	public $id_shop;	
	public $text_info;
	public $url_info;

	public static $definition = array(
		'table' => 'tptnheaderlinks',
		'primary' => 'id_info',
		'multilang' => true,
		'fields' => array(
			'id_shop' =>	array('type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedId'),
			'text_info' =>	array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true),
			'url_info' =>	array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isUrl'),
		)
	);

}