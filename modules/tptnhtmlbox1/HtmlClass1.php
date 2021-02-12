<?php

class HtmlClass1 extends ObjectModel
{
	public $id;
	public $id_shop;
	public $paragraph;

	public static $definition = array(
		'table' => 'tptnhtmlbox1',
		'primary' => 'id_htmlbox1',
		'multilang' => true,
		'fields' => array(
			'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'paragraph' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString')
		)
	);

	public static function getByIdShop($id_shop)
	{
		$id = Db::getInstance()->getValue('SELECT `id_htmlbox1` FROM `'._DB_PREFIX_.'tptnhtmlbox1` WHERE `id_shop` ='.(int)$id_shop);

		return new HtmlClass1($id);
	}

	public function copyFromPost()
	{
		/* Classical fields */
		foreach ($_POST as $key => $value)
		{
			if (key_exists($key, $this) && $key != 'id_'.$this->table)
				$this->{$key} = $value;
		}

		/* Multilingual fields */
		if (count($this->fieldsValidateLang))
		{
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				foreach ($this->fieldsValidateLang as $field => $validation)
				{
					if (Tools::getIsset($field.'_'.(int)$language['id_lang']))
						$this->{$field}[(int)$language['id_lang']] = $_POST[$field.'_'.(int)$language['id_lang']];
				}
			}
		}
	}
}