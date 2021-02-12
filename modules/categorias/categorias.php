<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class categorias extends Module {

    public function __construct() {
        $this->name = 'categorias';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Fernando Mangas';
        $this->boostrap = true;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->displayName = 'categorias';
        $this->description = 'Modulo que las categorias de la tienda virtual, como HP.';

        parent::__construct();
    }

    public function install() {
        if (!parent::install() ||
                !$this->registerHook('displayHome')) {
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

    public function hookDisplayHome($params) {


        return $this->display(__FILE__, 'displayHome.tpl');
    }

}
