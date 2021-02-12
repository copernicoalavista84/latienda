<?php
/**
 * Carts Guru
 *
 * @author    LINKT IT
 * @copyright Copyright (c) LINKT IT 2016
 * @license   Commercial license
 */

class CartsGuruAdminFrontController
{
    public static function toggleFeatures($data)
    {
        if (!is_array($data)) {
            return;
        }

        $result;

        // Enable facebook display ads
        if (array_key_exists('facebook', $data)) {
            if ($data['facebook'] == true) {
                Configuration::updateValue('CARTSG_FEATURE_FB', true);

                if (array_key_exists('pixel', $data)) {
                    Configuration::updateValue('CARTSG_FB_PIXEL', $data['pixel']);
                }

                if (array_key_exists('catalogId', $data)) {
                    Configuration::updateValue('CARTSG_FB_CATALOGID', $data['catalogId']);
                }
                
                if (array_key_exists('trackerUrl', $data)) {
                    Configuration::updateValue('CARTSG_TRACKERURL', $data['trackerUrl']);
                }

                $catalogUrl = version_compare(_PS_VERSION_, '1.5.0', '<') ?
                    _PS_BASE_URL_.__PS_BASE_URI__ . 'modules/cartsguru/controllers14/catalog.php':
                    Context::getContext()->link->getModuleLink('cartsguru', 'catalog');
                $result = array('catalogUrl' => $catalogUrl);
            } 
            else if ($data['facebook'] == false) {
                Configuration::updateValue('CARTSG_FEATURE_FB', false);
                $result = array('CARTSG_FEATURE_FB' => false);
            }
        }

        // Manage facebook messenger feature
        if (array_key_exists('fbm', $data)) {
            if ($data['fbm'] == true && array_key_exists('appId', $data) && array_key_exists('pageId', $data)) {
                Configuration::updateValue('CARTSG_FEATURE_FBM', true);
                Configuration::updateValue('CARTSG_FB_APPID', $data['appId']);
                Configuration::updateValue('CARTSG_FB_PAGEID', $data['pageId']);

                if (array_key_exists('trackerUrl', $data)) {
                    Configuration::updateValue('CARTSG_TRACKERURL', $data['trackerUrl']);
                }

                $result = array('CARTSG_FEATURE_FBM' => true);
            } 
            else if ($data['fbm'] == false) {
                Configuration::updateValue('CARTSG_FEATURE_FBM', false);
                $result = array('CARTSG_FEATURE_FBM' => false);
            }
        }

        // Manage CI feature
        if (array_key_exists('ci', $data)) {
            if ($data['ci'] == true && array_key_exists('trackerUrl', $data)) {
                Configuration::updateValue('CARTSG_FEATURE_CI', true);
                Configuration::updateValue('CARTSG_TRACKERURL', $data['trackerUrl']);
                $result = array('CARTSG_FEATURE_CI' => true);
            } 
            else if ($data['ci'] == false) {
                Configuration::updateValue('CARTSG_FEATURE_CI', false);
                $result = array('CARTSG_FEATURE_CI' => false);
            }
        }

        // Manage widgets feature
        if (array_key_exists('widgets', $data) && is_array($data['widgets'])) {
            Configuration::updateValue('CARTSG_WIDGETS', Tools::jsonEncode($data['widgets']));

            if (array_key_exists('trackerUrl', $data)) {
                Configuration::updateValue('CARTSG_TRACKERURL', $data['trackerUrl']);
            }

            $result = array('CARTSG_WIDGETS' => $data['widgets']);
        }

        CartsGuruHelper::clearSmartyCache();
        return $result;
    }

    public static function getConfig()
    {
        $curl_version = null;
        try {
            $info = curl_version();
            $curl_version = $info["version"];
        } catch (Exception $e) {
            $curl_version  = 'No curl';
        }

        return array(
            'CARTSG_API_SUCCESS' => Configuration::get('CARTSG_API_SUCCESS'),
            'CARTSG_SITE_ID' => Configuration::get('CARTSG_SITE_ID'),
            'CARTSG_IMAGE_TYPE' => Configuration::get('CARTSG_IMAGE_TYPE'),
            'CARTSG_FEATURE_FB' => Configuration::get('CARTSG_FEATURE_FB'),
            'CARTSG_FB_PIXEL' => Configuration::get('CARTSG_FB_PIXEL'),
            'CARTSG_FB_CATALOGID' => Configuration::get('CARTSG_FB_CATALOGID'),
            'CARTSG_FEATURE_CI' => Configuration::get('CARTSG_FEATURE_CI'),
            'CARTSG_WIDGETS' => Configuration::get('CARTSG_WIDGETS'),
            'CARTSG_FEATURE_FBM' => Configuration::get('CARTSG_FEATURE_FBM'),
            'CARTSG_TRACKERURL' => Configuration::get('CARTSG_TRACKERURL'),
            'CARTSG_FB_APPID' => Configuration::get('CARTSG_FB_APPID'),
            'CARTSG_FB_PAGEID' => Configuration::get('CARTSG_FB_PAGEID'),
            'PLUGIN_VERSION'=> _CARTSGURU_VERSION_,
            'CURL_VERSION' => $curl_version
        );
    }
}
