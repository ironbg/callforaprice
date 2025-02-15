<?php

if (!defined('_PS_VERSION_')) {
  exit;
}

require_once(dirname(__FILE__) . '/classes/CallForPriceItem.php');
require_once(dirname(__FILE__) . '/classes/CallForPriceSetting.php');
require_once(dirname(__FILE__) . '/classes/CallForPriceProduct.php');
class callForPrice extends Module
{
  private $_callForPriceSetting;
  private $_callForPriceItem;

  public function __construct()
  {
    $this->name = 'callforprice';
    $this->tab = 'pricing_promotion';
    $this->version = '3.4.5';
    $this->author = 'MyPrestaModules';
    $this->need_instance = 0;
    $this->module_key = "a360d7bd6f42edec438359610e986fcc";
    $this->author_address = '0x289929BB6B765f9668Dc1BC709E5949fEB83455e';
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = $this->l('Call For Price + Call Back Product Button');
    $this->description = $this->l('This module allows you to enable/disable your “Add to cart” button for those products that don’t have a fixed price. Instead a button “Call for price” or any other text will be displayed.');
    $this->_callForPriceSetting = new CallForPriceSetting();
    $this->_callForPriceItem = new CallForPriceItem();
  }

  public function install()
  {
    if (!parent::install()
      || !$this->registerHook('header')
      || !$this->registerHook('actionAdminControllerSetMedia')
      || !$this->registerHook('displayAdminProductsExtra')
      || !$this->registerHook('displayCallForPrice')
      || !$this->registerHook('displayCallForPriceClass')
      || !$this->registerHook('displayCallForPriceClassCategory')
      || !$this->registerHook('displayCallForPriceButton')
      || !$this->registerHook('displayCallForPriceCategory')
      || !$this->registerHook('displayCallForPriceCategoryButton')
    ) {
      return false;
    }

    $this->_createTab('AdminCallForPrice', 'Call For Price');
    $this->_installDb();
    $this->_setDataDb();

    Configuration::updateGlobalValue('MPM_CL_CHANGES_IS_DONE', 0);

    return true;
  }

  public function uninstall()
  {
    if (!parent::uninstall())
      return false;

    $this->_removeTab('AdminCallForPrice');
    $this->_uninstallDb();

    Configuration::deleteByName('GOMAKOIL_CALLFORPRICE_PRICE_VALUE');
    Configuration::deleteByName('GOMAKOIL_CALLFORPRICE_QUANTITY_VALUE');
    Configuration::deleteByName('GOMAKOIL_CALLFORPRICE_PRICE_TYPE');
    Configuration::deleteByName('GOMAKOIL_CALLFORPRICE_QUANTITY_TYPE');

    return true;
  }

  private function _createTab($class_name, $name)
  {
    $tab = new Tab();
    $tab->active = 1;
    $tab->class_name = $class_name;
    $tab->name = array();
    foreach (Language::getLanguages(true) as $lang)
      $tab->name[$lang['id_lang']] = $name;
    $tab->id_parent = -1;
    $tab->module = $this->name;
    $tab->add();
  }

  private function _removeTab($class_name)
  {
    $id_tab = (int)Tab::getIdFromClassName($class_name);
    if ($id_tab) {
      $tab = new Tab($id_tab);
      $tab->delete();
    }
  }

  private function _installDb()
  {
    // Table  pages
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'callforprice';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'callforprice(
				id_callforprice int(11) unsigned NOT NULL AUTO_INCREMENT,
				id_product int(11) unsigned NOT NULL,
				id_category TEXT NULL,
				type varchar(255) NULL,
				logged boolean NOT NULL,
				filter_by_customer_group boolean NOT NULL,
				customer_group_ids TEXT NULL,
				disable_price boolean NOT NULL,
				disable_button_add boolean NOT NULL,
				enable_button_callforprice boolean NOT NULL,
				PRIMARY KEY (`id_callforprice`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);

    // Table  pages lang
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'callforprice_lang';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'callforprice_lang(
				id_callforprice int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				label_price varchar(255) NULL,
				title_button varchar(255) NULL,
				PRIMARY KEY(id_callforprice, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);

    // Table  pages
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'callforprice_settings';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'callforprice_settings(
				id_callforprice_settings int(11) unsigned NOT NULL AUTO_INCREMENT,
				background_form varchar(255) NULL,
				background_button varchar(255) NULL,
				color_form varchar(255) NULL,
				email varchar(255) NULL,
				logged boolean NOT NULL,
				use_only_global_settings boolean NOT NULL,
				filter_by_manufacturer boolean NOT NULL,
				filter_by_customer_group boolean NOT NULL,
				manufacturer_ids TEXT NULL,
				customer_group_ids TEXT NULL,
				show_name boolean NOT NULL,
				required_name boolean NOT NULL,
				show_email boolean NOT NULL,
				required_email boolean NOT NULL,
				show_phone boolean NOT NULL,
				required_phone boolean NOT NULL,
				show_message boolean NOT NULL,
				required_message boolean NOT NULL,
				show_captcha boolean NOT NULL,
				show_recaptcha boolean NOT NULL,
				recaptcha_key varchar(255) NULL,
				show_delay boolean NOT NULL,
				delay_date_from int(11) NULL,
        delay_date_to int(11) NULL,
				all_products boolean NOT NULL,
				products_category boolean NOT NULL,
				css_code TEXT NULL,
				show_product_name_in_title boolean NOT NULL,
				hover_color varchar(255) NOT NULL,
				form_footer_message_color varchar(255) NOT NULL,
				form_title_font_size int(11) NOT NULL,
				form_product_name_font_size int(11) NOT NULL,
				form_button_text_font_size int(11) NOT NULL,
				form_footer_message_font_size int(11) NOT NULL,
				show_consent_checkbox int(11) NULL,
				PRIMARY KEY (`id_callforprice_settings`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);

    // Table  pages lang
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'callforprice_settings_lang';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'callforprice_settings_lang(
				id_callforprice_settings int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				title_form varchar(255) NULL,
				form_footer_message varchar(255) NULL,
				consent_checkbox_message varchar(2000) NULL,
				PRIMARY KEY(id_callforprice_settings, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);


    // Table  pages
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'callforprice_list';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'callforprice_list(
				id_callforprice_list int(11) unsigned NOT NULL AUTO_INCREMENT,
				id_product int(11) unsigned NOT NULL,
	      name varchar(255) NULL,
	      phone varchar(255) NULL,
	      email varchar(255) NULL,
	      hour varchar(255) NULL,
	      message varchar(2000) NULL,
	      state  varchar(2000) NULL,
	      date_add datetime NULL,
				PRIMARY KEY (`id_callforprice_list`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);

  }

  private function _uninstallDb()
  {
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'callforprice';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'callforprice_lang';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'callforprice_settings';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'callforprice_settings_lang';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'callforprice_list';
    Db::getInstance()->execute($sql);
  }

  private function _setDataDb()
  {
    $languages = Language::getLanguages(false);
    $obj = new CallForPriceSetting();
    $obj_item = new CallForPriceItem();

    foreach ($languages as $lang) {
      $obj->title_form[$lang['id_lang']] = 'Call For Price:';
      $obj->form_footer_message[$lang['id_lang']] = 'We\'ll contact you as soon as possible';
      $obj_item->label_price[$lang['id_lang']] = 'Call For Price';
      $obj_item->title_button[$lang['id_lang']] = 'Call For Price';
      $obj->consent_checkbox_message[$lang['id_lang']] = 'I agree to the terms and conditions and the privacy policy';
    }

    $obj->background_form = '#fefdff';
    $obj->background_button = '#8e44ad';
    $obj->hover_color = '#9b59b6';
    $obj->color_form = '#ffffff';
    $obj->form_footer_message_color = '#000000';
    $obj->email = 'demo@demo.com';
    $obj->logged = 0;
    $obj->use_only_global_settings = 0;
    $obj->show_name = 1;
    $obj->show_product_name_in_title = 1;
    $obj->required_name = 1;
    $obj->show_email = 1;
    $obj->required_email = 0;
    $obj->show_phone = 1;
    $obj->required_phone = 0;
    $obj->show_message = 1;
    $obj->required_message = 0;
    $obj->show_captcha = 0;
    $obj->show_recaptcha = 0;
    $obj->recaptcha_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
    $obj->show_delay = 0;
    $obj->delay_date_from = 1;
    $obj->delay_date_to = 1;
    $obj->all_products = 0;
    $obj->specific_products = 1;
    $obj->products_category = 0;
    $obj->form_title_font_size = 32;
    $obj->form_product_name_font_size = 22;
    $obj->form_button_text_font_size = 18;
    $obj->form_footer_message_font_size = 16;
    $obj->show_consent_checkbox = 1;
    $obj->save();

    $obj_item->id_product = 0;
    $obj_item->id_category = '';
    $obj_item->disable_price = 0;
    $obj_item->disable_button_add = 0;
    $obj_item->logged = 0;
    $obj_item->enable_button_callforprice = 0;
    $obj_item->type = 'no_product';
    $obj_item->save();

    return true;
  }

  public function hookHeader()
  {
    $this->context->controller->registerStylesheet('callforprice', 'modules/' . $this->name . '/views/css/callforprice.css', array('media' => 'all', 'priority' => 150));
    $this->context->controller->registerStylesheet('callforprice2', 'modules/' . $this->name . '/views/css/codemirror_custom.css', array('media' => 'all', 'priority' => 150));
    $this->context->controller->registerStylesheet('callforprice3', '<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">', array('media' => 'all', 'priority' => 150, 'inline' => true));
    $this->context->controller->registerStylesheet('callforprice4', 'modules/' . $this->name . '/views/css/fonts_style.css', array('media' => 'all', 'priority' => 150));
    $this->context->controller->registerJavascript('callforprice', 'modules/' . $this->name . '/views/js/callforprice.js', array('position' => 'top', 'priority' => 150));
  }

  public function hookActionAdminControllerSetMedia()
  {
      if (Tools::getValue('controller') != 'AdminCallForPrice' && Tools::getValue('controller') != 'AdminProducts') {
          return false;
      }

    $this->context->controller->addCSS($this->_path.'/views/css/style.css', 'all');
    $this->context->controller->addJS($this->_path.'/views/js/main.js', 'all');


    if(version_compare(_PS_VERSION_, '1.7.8.0', '>=')){
      $this->context->controller->addCSS($this->_path.'/views/css/callforprice_style_1780_more.css');
    } 

    $controller = Dispatcher::getInstance()->getController();
    $is_module_controller = ($controller == 'AdminCallForPrice');

    if ($is_module_controller) {
        $this->context->controller->addCSS(array(
            _PS_MODULE_DIR_ . 'https://fonts.googleapis.com/css?family=Open+Sans',
            _PS_MODULE_DIR_ . 'callforprice/views/css/callforprice.css',
            _PS_MODULE_DIR_ . 'callforprice/views/css/fonts_style.css',
            _PS_MODULE_DIR_ . 'callforprice/views/css/form_preview.css',
            _PS_MODULE_DIR_ . 'callforprice/libraries/codemirror/lib/codemirror.css',
            _PS_MODULE_DIR_ . 'callforprice/views/css/codemirror_custom.css',
        ));

        $this->context->controller->addJquery();

        $this->context->controller->addJS(array(
            _PS_MODULE_DIR_ . 'callforprice/libraries/codemirror/lib/codemirror.js',
            _PS_MODULE_DIR_ . 'callforprice/libraries/codemirror/mode/css/css.js',
            _PS_MODULE_DIR_ .'callforprice/views/js/form_preview.js',
        ));


    }

  }

  public function getContent()
  {
    $settings = $this->_callForPriceSetting->getSettings(Context::getContext()->language->id, Context::getContext()->shop->id);
    if (isset($settings[0]['id_callforprice_settings']) && $settings[0]['id_callforprice_settings']) {
      $settings = $settings[0];
    } else {
      $settings = false;
    }

    if (!$settings) {
      Tools::redirectAdmin($this->context->link->getAdminLink('AdminCallForPrice') . '&addcallforprice');
    } else {
      Tools::redirectAdmin($this->context->link->getAdminLink('AdminCallForPrice') . '&updatecallforprice_settings&id_callforprice_settings=' . $settings['id_callforprice_settings']);
    }
  }

  public function hookDisplayCallForPriceClass($params)
  {
    $product_configuration = new CallForPriceProduct($params['product']);
    $settings = $product_configuration->getCallForPriceProductConfiguration();

    $class = '';

      if ($settings && isset($settings['disable_price']) && $settings['disable_price']) {
          $class .= 'CallForPriceLabel';
      }

      if ($settings && isset($settings['disable_button_add']) && $settings['disable_button_add']) {
          $class .= ' CallForPriceButton';
      }

    return $class;
  }

  public function hookDisplayCallForPrice($params)
  {
    $product_configuration = new CallForPriceProduct($params['product']);
    $settings = $product_configuration->getCallForPriceProductConfiguration();

      if (!$settings || !isset($settings['disable_price']) || !$settings['disable_price']) {
          return false;
      }

    $this->context->smarty->assign(
      array(
        'label_price' => $settings['label_price'],
      )
    );

    return $this->display(__FILE__, 'views/templates/hook/price_block.tpl');
  }

  public function hookDisplayCallForPriceButton($params)
  {
    $product = $params['product'];
    $product_configuration = new CallForPriceProduct($product);
    $settings = $product_configuration->getCallForPriceProductConfiguration();

    $this->context->smarty->assign(
      array(
        'title_button'               => isset($settings['title_button']) ? $settings['title_button'] : '',
        'enable_button_callforprice' => isset($settings['enable_button_callforprice']) ? $settings['enable_button_callforprice'] : false,
        'disable_button_add'         => isset($settings['disable_button_add']) ? $settings['disable_button_add'] : false,
        'id_product'                 => is_array($product) ? $product['id_product'] : $product->id,
        'base_dir'                   => _PS_BASE_URL_SSL_ . __PS_BASE_URI__,
        'id_lang'                    => Context::getContext()->language->id,
        'id_shop'                    => Context::getContext()->shop->id,
      )
    );

    return $this->display(__FILE__, 'views/templates/hook/button_block.tpl');
  }

  public function hookDisplayCallForPriceClassCategory($params)
  {
    $product_configuration = new CallForPriceProduct($params['product']);
    $settings = $product_configuration->getCallForPriceProductConfiguration();

    $class = '';

      if ($settings && isset($settings['disable_price']) && $settings['disable_price']) {
          $class .= 'CallForPriceLabelCategory';
      }

      if ($settings && isset($settings['disable_button_add']) && $settings['disable_button_add']) {
          $class .= ' CallForPriceButtonCategory';
      }

    return $class;
  }

  public function hookDisplayCallForPriceCategory($params)
  {
    $product_configuration = new CallForPriceProduct($params['product']);
    $settings = $product_configuration->getCallForPriceProductConfiguration();

      if (!$settings || !isset($settings['disable_price']) || !$settings['disable_price']) {
          return false;
      }

    $this->context->smarty->assign(
      array(
        'label_price' => $settings['label_price'],
      )
    );
    return $this->display(__FILE__, 'views/templates/hook/price_block_category.tpl');
  }


  public function hookDisplayCallForPriceCategoryButton($params)
  {
    $product = $params['product'];
    $product_configuration = new CallForPriceProduct($product);
    $settings = $product_configuration->getCallForPriceProductConfiguration();

    $this->context->smarty->assign(
      array(
        'title_button'               => $settings['title_button'],
        'enable_button_callforprice' => $settings['enable_button_callforprice'],
        'disable_button_add'         => $settings['disable_button_add'],
        'id_product'                 => is_array($product) ? $product['id_product'] : $product->id,
        'id_lang'                    => Context::getContext()->language->id,
        'id_shop'                    => Context::getContext()->shop->id,
      )
    );

    return $this->display(__FILE__, 'views/templates/hook/button_block_category.tpl');
  }

  public function hookDisplayAdminProductsExtra($param)
  {
    return $this->_displayTabContent($param);
  }

  private function _displayTabContent($param)
  {
    $label_price = array();
    $title_button = array();
    $id_product = $param['id_product'];
    $settings = $this->_callForPriceItem->getSettingsItem(Context::getContext()->language->id, Context::getContext()->shop->id, $id_product, 'product');

    if ($id_product && $settings) {
      $obj = new callForPriceItem($settings[0]['id_callforprice']);
      $disable_price = $obj->disable_price;
      $disable_button_add = $obj->disable_button_add;
      $enable_button_callforprice = $obj->enable_button_callforprice;
      $logged = $obj->logged;

      if (!$obj->title_button) {
        foreach (Language::getLanguages(true) as $lang) {
          $title_button[$lang['id_lang']] = $this->l('Call For Price');
        }
      } else {
        $title_button = $obj->title_button;
      }
      if (!$obj->label_price) {
        foreach (Language::getLanguages(true) as $lang) {
          $label_price[$lang['id_lang']] = $this->l('Call For Price');;
        }
      } else {
        $label_price = $obj->label_price;
      }
    }
    else {
      $disable_price = 0;
      $disable_button_add = 0;
      $enable_button_callforprice = 0;
      $logged = 0;

      foreach (Language::getLanguages(true) as $lang) {
        $label_price[$lang['id_lang']] = $this->l('Call For Price');;
        $title_button[$lang['id_lang']] = $this->l('Call For Price');;
      }
    }

    $all_customer_groups = Group::getGroups(Context::getContext()->language->id);
    $checked_customer_groups = !empty($settings[0]['customer_group_ids']) ? unserialize($settings[0]['customer_group_ids']) : '';
    $filter_by_customer_group = !empty($settings[0]['filter_by_customer_group']) ? $settings[0]['filter_by_customer_group'] : false;

    $this->context->smarty->assign(
      array(
        'path_tpl'                   => _PS_MODULE_DIR_ . 'callforprice/views/templates/hook/',
        'path_base'                  => __PS_BASE_URI__,
        'disable_price'              => $disable_price,
        'logged'                     => $logged,
        'disable_button_add'         => $disable_button_add,
        'enable_button_callforprice' => $enable_button_callforprice,
        'label_price'                => $label_price,
        'title_button'               => $title_button,
        'filter_by_customer_group'   => $filter_by_customer_group,
        'all_customer_groups'        => $all_customer_groups,
        'checked_customer_groups'    => $checked_customer_groups,
        'languages'                  => Language::getLanguages(false),
        'default_form_language'      => Context::getContext()->language->id,
        'id_shop'                    => Context::getContext()->shop->id,
        'id_lang'                    => Context::getContext()->language->id,
        'id_product'                 => $id_product,
        'callforprice_token'         => Tools::getAdminTokenLite('AdminCallForPrice'),
        'admin_url'                  => _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . basename(_PS_ADMIN_DIR_),
      )
    );

    return $this->display(__FILE__, 'views/templates/hook/tabContent.tpl');
  }

  public function checkIfColumnExists($col_name, $table_name)
  {
    $check_query = "SELECT NULL
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_name = '" . _DB_PREFIX_ . $table_name . "'
            AND table_schema = '" . _DB_NAME_ . "'
            AND column_name = '" . $col_name . "'
        ";

    if (!Db::getInstance()->executeS($check_query)) {
      return false;
    }

    return true;
  }

  private function addNewColumnsToDbTables($new_columns)
  {
    foreach ($new_columns as $column_short_name => $column_details) {
      $table_name = $new_columns[$column_short_name]['table'];
      $column_complete_name = $new_columns[$column_short_name]['complete_name'];

      if (!$this->checkIfColumnExists($column_short_name, $table_name)) {
        $alter_callforprice_settings = 'ALTER TABLE ' . _DB_PREFIX_ . $table_name . ' ADD COLUMN ' . $column_complete_name;

        if (!Db::getInstance()->execute($alter_callforprice_settings)) {
          return false;
        }
      }
    }

    return true;
  }

  public function upgradeCallForPrice_3_3_7()
  {
    $this->registerHook('actionAdminControllerSetMedia');

    $new_columns = array(
      'show_recaptcha' => array('complete_name' => '`show_recaptcha` boolean NOT NULL', 'table' => 'callforprice_settings'),
      'recaptcha_key' =>  array('complete_name' => '`recaptcha_key` varchar(255) NULL', 'table' => 'callforprice_settings')
    );

    if (!$this->addNewColumnsToDbTables($new_columns)) {
      return false;
    }

    $obj = new CallForPriceSetting(1);
    $obj->show_recaptcha = 0;
    $obj->recaptcha_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
    $obj->update(false, false);

    return true;
  }

  public function upgradeCallForPrice_3_3_6()
  {
    $new_columns = array(
        'use_only_global_settings' => array('complete_name' => '`use_only_global_settings` BOOLEAN NULL', 'table' => 'callforprice_settings'),
    );

    if (!$this->addNewColumnsToDbTables($new_columns)) {
        return false;
    }

    $obj = new CallForPriceSetting(1);
    $obj->use_only_global_settings = 0;
    $obj->update(false, false);

    return true;
  }

  public function upgradeCallForPrice_3_3_4()
  {

    $new_columns = array(
      'show_consent_checkbox'        => array('complete_name' => '`show_consent_checkbox` int(11) NULL', 'table' => 'callforprice_settings'),
      'consent_checkbox_message'     => array('complete_name' => '`consent_checkbox_message` varchar(2000) NULL', 'table' => 'callforprice_settings_lang'),
    );

    if (!$this->addNewColumnsToDbTables($new_columns)) {
      return false;
    }

    $languages = Language::getLanguages(false);
    $obj = new CallForPriceSetting(1);

    foreach ($languages as $lang) {
      $obj->consent_checkbox_message[$lang['id_lang']] = 'I agree to the terms and conditions and the privacy policy';
    }

    $obj->show_consent_checkbox = 1;
    $obj->save();

    return true;
  }

  public function upgradeCallForPrice_3_3_2()
  {
    $new_columns = array(
      'filter_by_manufacturer' => array('complete_name' => '`filter_by_manufacturer` BOOLEAN NOT NULL', 'table' => 'callforprice_settings'),
      'filter_by_customer_group' => array('complete_name' => '`filter_by_customer_group` BOOLEAN NOT NULL', 'table' => 'callforprice_settings'),
      'manufacturer_ids'       => array('complete_name' => '`manufacturer_ids` TEXT NULL', 'table' => 'callforprice_settings'),
      'customer_group_ids'       => array('complete_name' => '`customer_group_ids` TEXT NULL', 'table' => 'callforprice_settings'),
      'filter_by_customer_group' => array('complete_name' => '`filter_by_customer_group` BOOLEAN NOT NULL', 'table' => 'callforprice'),
      'customer_group_ids'       => array('complete_name' => '`customer_group_ids` TEXT NULL', 'table' => 'callforprice'),
    );

    $this->registerHook('displayBackOfficeHeader');
    if (!$this->addNewColumnsToDbTables($new_columns)) {
      return false;
    }

    return true;
  }

  public function upgradeCallForPrice_3_3_0()
  {
    $new_columns = array(
      'hover_color'                   => array('complete_name' => '`hover_color` varchar(255) NOT NULL', 'table' => 'callforprice_settings'),
      'form_footer_message_color'     => array('complete_name' => '`form_footer_message_color` varchar(255) NOT NULL', 'table' => 'callforprice_settings'),
      'show_product_name_in_title'    => array('complete_name' => '`show_product_name_in_title` boolean NOT NULL', 'table' => 'callforprice_settings'),
      'form_title_font_size'          => array('complete_name' => '`form_title_font_size` int(11) NOT NULL', 'table' => 'callforprice_settings'),
      'form_product_name_font_size'   => array('complete_name' => '`form_product_name_font_size` int(11) NOT NULL', 'table' => 'callforprice_settings'),
      'form_button_text_font_size'    => array('complete_name' => '`form_button_text_font_size` int(11) NOT NULL', 'table' => 'callforprice_settings'),
      'form_footer_message_font_size' => array('complete_name' => '`form_footer_message_font_size` int(11) NOT NULL', 'table' => 'callforprice_settings'),
      'form_footer_message'           => array('complete_name' => '`form_footer_message` varchar(255) NULL', 'table' => 'callforprice_settings_lang')
    );

    if (!$this->addNewColumnsToDbTables($new_columns)) {
      return false;
    }

    $languages = Language::getLanguages(false);
    $obj = new CallForPriceSetting(1);
    $obj_item = new CallForPriceItem(1);

    foreach ($languages as $lang) {
      $obj_item->label_price[$lang['id_lang']] = $obj_item->label_price[$lang['id_lang']];
      $obj_item->title_button[$lang['id_lang']] = $obj_item->title_button[$lang['id_lang']];

      $obj->form_footer_message[$lang['id_lang']] = 'We\'ll contact you as soon as possible';
    }

    $obj->background_form = '#fefdff';
    $obj->background_button = '#8e44ad';
    $obj->hover_color = '#9b59b6';
    $obj->form_footer_message_color = '#000000';
    $obj->color_form = '#ffffff';
    $obj->show_product_name_in_title = 1;
    $obj->form_title_font_size = 32;
    $obj->form_product_name_font_size = 22;
    $obj->form_button_text_font_size = 18;
    $obj->form_footer_message_font_size = 16;
    $obj->save();
    $obj_item->save();

    return true;
  }

  public function upgradeCallForPrice_3_2_6()
  {
    if (!$this->checkIfColumnExists('css_code', 'callforprice_settings')) {
      $alter_callforprice_settings = 'ALTER TABLE ' . _DB_PREFIX_ . 'callforprice_settings
                ADD COLUMN `css_code` TEXT NULL;';

      if (!Db::getInstance()->execute($alter_callforprice_settings)) {
        return false;
      }
    }

    return true;
  }

  public function upgradeCallForPrice_3_2_0()
  {
    // Table  pages
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'callforprice_list';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'callforprice_list(
				id_callforprice_list int(11) unsigned NOT NULL AUTO_INCREMENT,
								id_product int(11) unsigned NOT NULL,
	      name varchar(255) NULL,
	      phone varchar(255) NULL,
	      email varchar(255) NULL,
	      hour varchar(255) NULL,
	      message varchar(2000) NULL,
	      state  varchar(2000) NULL,
	      date_add datetime NULL,
				PRIMARY KEY (`id_callforprice_list`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);


    $sql = "
   SELECT NULL
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE table_name = '" . _DB_PREFIX_ . "callforprice_settings'
             AND table_schema = '" . _DB_NAME_ . "'
             AND column_name = 'show_delay'
    ";

    $check = Db::getInstance()->executeS($sql);
    if (!$check) {
      $sql = '
      ALTER TABLE ' . _DB_PREFIX_ . 'callforprice_settings
      ADD COLUMN `show_delay` INT(1) NOT NULL DEFAULT "1" AFTER `show_email`;

    ';
      Db::getInstance()->execute($sql);
    }

    $sql = "
   SELECT NULL
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE table_name = '" . _DB_PREFIX_ . "callforprice_settings'
             AND table_schema = '" . _DB_NAME_ . "'
             AND column_name = 'delay_date_from'
    ";

    $check = Db::getInstance()->executeS($sql);
    if (!$check) {
      $sql = '
      ALTER TABLE ' . _DB_PREFIX_ . 'callforprice_settings
      ADD COLUMN `delay_date_from` INT(1) NOT NULL DEFAULT "1" AFTER `show_email`;

    ';
      Db::getInstance()->execute($sql);
    }


    $sql = "
   SELECT NULL
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE table_name = '" . _DB_PREFIX_ . "callforprice_settings'
             AND table_schema = '" . _DB_NAME_ . "'
             AND column_name = 'delay_date_to'
    ";

    $check = Db::getInstance()->executeS($sql);
    if (!$check) {
      $sql = '
      ALTER TABLE ' . _DB_PREFIX_ . 'callforprice_settings
      ADD COLUMN `delay_date_to` INT(1) NOT NULL DEFAULT "1" AFTER `show_email`;

    ';
      Db::getInstance()->execute($sql);
    }


    return true;
  }

  public function upgradeCallForPrice_3_1_2()
  {
    $sql = "
   SELECT NULL
            FROM INFORMATION_SCHEMA.COLUMNS
           WHERE table_name = '" . _DB_PREFIX_ . "callforprice_settings'
             AND table_schema = '" . _DB_NAME_ . "'
             AND column_name = 'logged'
    ";

    $check = Db::getInstance()->executeS($sql);
    if (!$check) {
      $sql = '
      ALTER TABLE ' . _DB_PREFIX_ . 'callforprice_settings
      ADD COLUMN `logged` INT(1) NULL AFTER `products_category`;

    ';
      Db::getInstance()->execute($sql);
    }
    return true;
  }

  public function upgradeCallForPrice_3_1_3()
  {
    $sql = "
   SELECT NULL
            FROM INFORMATION_SCHEMA.COLUMNS
           WHERE table_name = '" . _DB_PREFIX_ . "callforprice'
             AND table_schema = '" . _DB_NAME_ . "'
             AND column_name = 'logged'
    ";

    $check = Db::getInstance()->executeS($sql);
    if (!$check) {
      $sql = '
      ALTER TABLE ' . _DB_PREFIX_ . 'callforprice
      ADD COLUMN `logged` INT(1) NULL AFTER `enable_button_callforprice`;

    ';
      Db::getInstance()->execute($sql);
    }
    return true;
  }

  public static function getValuesFromCheckboxTableInSingleArray($input_name, $list_with_old_values = null)
  {
    $result = array();
    $form_values = Tools::getAllValues();

    if ($list_with_old_values !== null) {
      $form_values = $list_with_old_values;
    }

    foreach ($form_values as $key => $value) {
      if (preg_match('/^'.$input_name.'_\d+$/', $key)) {
        array_push($result, $value);
        unset($_POST[$key]);
      }
    }

    return serialize($result);
  }

  public function getHours($delay_date_from, $delay_date_to)
  {
    $hours = array(
      '24' => $this->l('12:00 am'),
      '1'  => $this->l('1:00 am'),
      '2'  => $this->l('2:00 am'),
      '3'  => $this->l('3:00 am'),
      '4'  => $this->l('4:00 am'),
      '5'  => $this->l('5:00 am'),
      '6'  => $this->l('6:00 am'),
      '7'  => $this->l('7:00 am'),
      '8'  => $this->l('8:00 am'),
      '9'  => $this->l('9:00 am'),
      '10' => $this->l('10:00 am'),
      '11' => $this->l('11:00 am'),
      '12' => $this->l('12:00 pm'),
      '13' => $this->l('1:00 pm'),
      '14' => $this->l('2:00 pm'),
      '15' => $this->l('3:00 pm'),
      '16' => $this->l('4:00 pm'),
      '17' => $this->l('5:00 pm'),
      '18' => $this->l('6:00 pm'),
      '19' => $this->l('7:00 pm'),
      '20' => $this->l('8:00 pm'),
      '21' => $this->l('9:00 pm'),
      '22' => $this->l('10:00 pm'),
      '23' => $this->l('11:00 pm'),
    );

    foreach ($hours as $key => $value) {
      if ($delay_date_from !== $delay_date_to) {
        if ($delay_date_from < $delay_date_to) {
          if ($key < $delay_date_from || $key > $delay_date_to) {
            unset($hours[$key]);
          }
        } else {
          if ($key > $delay_date_to && $key < $delay_date_from) {
            unset($hours[$key]);
          }
        }
      }
    }

    return $hours;
  }

  /**
   * @return string
   */
}