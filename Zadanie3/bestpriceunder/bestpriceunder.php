<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class BestPriceUnder extends Module
{
    public function __construct()
    {
        $this->name = 'bestpriceunder';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'ebochenek';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Shows best price under 100');
        $this->description = $this->l('Adds a new flag.');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() && $this->registerHook('actionProductFlagsModifier');
    }

    public function hookActionProductFlagsModifier(array $params)
    {
        if (!isset($params['flags']) || !isset($params['product']['id_product'])) {
            return;
        }

        $idProduct = (int)$params['product']['id_product'];
        $productObject = new Product($idProduct);

        $productPrice = $productObject->getPrice();

        if ($productPrice < 100) {
            $params['flags']['under_100'] = [
                'type' => 'new',
                'label' => $this->l('Best online price'),
            ];
        }

        return $params;
    }
}