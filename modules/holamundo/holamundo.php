<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class HolaMundo extends Module {

    public function __construct() {
        $this->name = 'holamundo';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Fernando Mangas';
        $this->boostrap = true;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->displayName = 'Hola mundo';
        $this->description = 'Modulo que te saluda';

        parent::__construct();
    }

    public function install() {
        if (!parent::install() ||
                !$this->registerHook('displayFooter') || !$this->registerHook('displayProductAdditionalInfo')) {
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

    public function hookDisplayProductAdditionalInfo($params) {
        return 'mensaje de ejemplo';
    }

    public function hookDisplayFooter($params) {
        return $this->display(__FILE__, 'displayFooter.tpl');
    }

}
