<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class cabecera extends Module {

    public function __construct() {
        $this->name = 'cabecera';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Fernando Mangas';
        $this->boostrap = true;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->displayName = 'cabecera';
        $this->description = 'Modulo que contiene el header de la web, con su título y logo.';

        parent::__construct();
    }

    public function install() {
        if (!parent::install() ||
                !$this->registerHook('displayNav')) {
            return false;
        }
        return true;
    }

    public function uninstall() {

        if (!parent::uninstall()) {
            return false;
        }
        return true;
    }

    public function getContent() {
        return $this->display(__FILE__, 'configure.tpl');
    }

    public function hookDisplayNav($params) {

        //obtenemos la url de la web.
        global $smarty;
        $valor = $smarty->tpl_vars['base_dir']->value;
        
        //pasamos el varlos al tpl (vista).
        $this->smarty->assign('url',$valor);

        return $this->display(__FILE__, 'displayNav.tpl');
    }

}
