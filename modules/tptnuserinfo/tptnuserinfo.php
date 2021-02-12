<?php

if (!defined('_PS_VERSION_'))
	exit;

class TptnUserInfo extends Module
{
	public function __construct()
	{
		$this->name = 'tptnuserinfo';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();
		
		$this->displayName = $this->l('User Info');
		$this->description = $this->l('Adds a block that displays your account links in header.');
	}

	public function install()
	{
		return (parent::install() && $this->registerHook('displayTop'));
	}
	
	public function uninstall()
	{
		if (!parent::uninstall())
			return false;
		return true;
	}
	
	public function hookDisplayTop($params)
	{
		$this->smarty->assign(array(
			'logged' => $this->context->customer->isLogged()
		));
		return $this->display(__FILE__, 'tptnuserinfo.tpl');
	}
}