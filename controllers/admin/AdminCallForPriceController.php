<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
require_once(dirname(__FILE__) . '/../../classes/CallForPriceSetting.php');
require_once(dirname(__FILE__) . '/../../callforprice.php');

class AdminCallForPriceController extends ModuleAdminController
{

  private $_idShop;
  private $_idLang;
  private $_callForPriceSetting;
  private $_callForPriceItem;

  protected $position_identifier = 'id_callforprice_settings';

  public $moduleCore;

  public function __construct()
  {
    $this->className = 'CallForPriceSetting';
    $this->table = 'callforprice_settings';
    $this->bootstrap = true;
    $this->lang = true;
    $this->edit = true;
    $this->delete = true;
    parent::__construct();
    $this->multishop_context = -1;
    $this->multishop_context_group = true;
    $this->position_identifier = 'id_callforprice_settings';
    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;
    $this->_callForPriceSetting = new CallForPriceSetting();
    $this->_callForPriceItem = new CallForPriceItem();
    $this->moduleCore = new callForPrice();

    $this->fields_list = array(
      'id_callforprice_settings' => array(
        'title' => $this->l('ID'),
        'search' => true,
        'onclick' => false,
        'filter_key' => 'a!id_callforprice_settings',
        'width' => 20
      ),
    );
  }

  public function init()
  {
    parent::init();
    if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive() && Tools::getValue('viewcallforprice_settings') === false)
      $this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;

    if (Tools::isSubmit('submitAddcallforprice_settingsAndStay')) {

      if(Tools::getValue('price_value') !== '' && !Validate::isInt(Tools::getValue('price_value'))){
        $this->errors[] = Tools::displayError('Please enter valid price value');
      }

      if(Tools::getValue('price_value') !== '' && Validate::isInt(Tools::getValue('price_value')) && !Tools::getValue('selection_type_price')){
        $this->errors[] = Tools::displayError('Please select sign inequality');
      }

      if(Tools::getValue('quantity_value') !== '' && !Validate::isInt(Tools::getValue('quantity_value'))){
        $this->errors[] = Tools::displayError('Please enter valid quantity value');
      }

      if(Tools::getValue('quantity_value') !== '' && Validate::isInt(Tools::getValue('quantity_value')) && !Tools::getValue('selection_type_quantity')){
        $this->errors[] = Tools::displayError('Please select sign inequality');
      }
        Configuration::updateValue('GOMAKOIL_CALLFORPRICE_QUANTITY_VALUE', Tools::getValue('quantity_value'));
        Configuration::updateValue('GOMAKOIL_CALLFORPRICE_QUANTITY_TYPE', Tools::getValue('selection_type_quantity'));
        Configuration::updateValue('GOMAKOIL_CALLFORPRICE_PRICE_VALUE', Tools::getValue('price_value'));
        Configuration::updateValue('GOMAKOIL_CALLFORPRICE_PRICE_TYPE', Tools::getValue('selection_type_price'));
    }
  }

  public function initProcess(){
    parent::initProcess();
  }

  public function initContent()
  {
    parent::initContent();
  }

  public function postProcess()
  {
    $codemirror_css = $this->getCodemirrorCssForm();

    file_put_contents(_PS_MODULE_DIR_ . 'callforprice/views/css/codemirror_custom.css', $codemirror_css);

    return parent::postProcess();
  }

  public function getCodemirrorCssForm()
  {
    $codemirror_css_template = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'callforprice/views/templates/hook/codemirror_custom.tpl');

    $settings = $this->_callForPriceSetting->getSettings($this->_idLang, $this->_idShop);

    $codemirror_css_template->assign(
      array(
        'config' => $settings[0],
      )
    );

    return $codemirror_css_template->fetch();
  }


  public function renderList()
  {
    $this->addRowAction('edit');
    return parent::renderList();
  }


  public function displayAjax()
  {
    $json = array();
    try{


      if (Tools::getValue('action') == 'search'){


        $field = array(
          'id_callforprice_list' =>  Tools::getValue('search_id'),
          'name' =>  Tools::getValue('name'),
          'id_product' =>  Tools::getValue('id_product'),
          'prod_name' =>  Tools::getValue('prod_name'),
          'phone' =>  Tools::getValue('search_phone'),
          'email' =>  Tools::getValue('search_email'),
          'hour' =>  Tools::getValue('search_hour'),
          'state' =>  Tools::getValue('search_state'),
          'message' =>  Tools::getValue('search_message'),
          'date' =>  Tools::getValue('search_date'),
        );

        $json['success'] = $this->listCallBack(Tools::getValue('p'),$field);
      }


      if (Tools::getValue('action') == 'pagination'){

        $field = array(
          'id_callforprice_list' =>  Tools::getValue('search_id'),
          'phone' =>  Tools::getValue('search_phone'),
          'name' =>  Tools::getValue('name'),
          'prod_name' =>  Tools::getValue('prod_name'),
          'id_product' =>  Tools::getValue('id_product'),
          'email' =>  Tools::getValue('search_email'),
          'hour' =>  Tools::getValue('search_hour'),
          'state' =>  Tools::getValue('search_state'),
          'message' =>  Tools::getValue('search_message'),
          'date' =>  Tools::getValue('search_date'),
        );

        $json['success'] = $this->listCallBack(Tools::getValue('p'), $field);
      }

      if (Tools::getValue('action') == 'saveCallforpriceSettingsForSpecificProduct'){
        if ($this->saveCallforpriceSettingsForSpecificProduct()) {
          $json['success'] = Module::getInstanceByName('callforprice')->l('CallForPrice settings has been updated!');
        } else  {
          throw new Exception( Module::getInstanceByName('callforprice')->l('Can not update CallForPrice settings'));
        }
      }

      if (Tools::getValue('action') == 'deleteCallforpriceItemSettings'){
        if ($this->deleteCallforpriceItemSettings()) {
            $json['success'] = Module::getInstanceByName('callforprice')->l('CallForPrice settings for this product deleted!');
        } else  {
            throw new Exception( Module::getInstanceByName('callforprice')->l('Can not delete CallForPrice settings'));
        }
      }

      if (Tools::getValue('action') == 'deleteItem'){
        $result = $this->deleteItemList(Tools::getValue('id'));

        if(!$result){
          throw new Exception( Module::getInstanceByName('callforprice')->l("Some error!"));
        }

        $json['success'] = Module::getInstanceByName('callforprice')->l("Successfully delete!") ;
      }

      if (Tools::getValue('action') == 'changeState'){
        $result = $this->updateState(Tools::getValue('id'), Tools::getValue('state'));

        if(!$result){
          throw new Exception( Module::getInstanceByName('callforprice')->l("Some error!"));
        }

        $json['success'] = Module::getInstanceByName('callforprice')->l("Successfully update!") ;
      }

      if (Tools::getValue('action') == 'checkIfEssentialInstallationChangesIsDoneCl') {
          $json['installation_changes_remainder'] = false;

          if (!$this->isChangesDone()) {
              $json['installation_changes_remainder'] = $this->getChangesNotificationTemplate();
          }
      }


      die( json_encode($json) );
    }
    catch(Exception $e){
      $json['error'] = $e->getMessage();
      if( $e->getCode() == 10 ){
        $json['error_message'] = $e->getMessage();
      }
    }
    die( json_encode($json) );
  }

  private function deleteCallforpriceItemSettings()
  {
      $id_product = Tools::getValue('id_product');
      $callforprice_item_id = Db::getInstance()->getValue("SELECT id_callforprice FROM " . _DB_PREFIX_ . "callforprice WHERE id_product = " . (int)$id_product);

      if (empty($callforprice_item_id)) {
          throw new Exception(Module::getInstanceByName('callforprice')->l('There is no saved settings associated with this product!'));
      }

      $callforprice_item = new CallForPriceItem($callforprice_item_id);
      return $callforprice_item->delete();
  }

  private function saveCallforpriceSettingsForSpecificProduct()
  {
      $input_fields_data = json_decode(Tools::getAllValues()['input_values'], true);
      $saved = $this->processSaveCallForPrice(Tools::getValue('id_product'), $input_fields_data);

      if (!$saved) {
          return false;
      }

      return true;
  }

  private function processSaveCallForPrice($id_product, $input_fields_data)
  {
    require_once(_PS_MODULE_DIR_ . 'callforprice/classes/CallForPriceItem.php');

    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
    $call = new CallForPriceItem();
    $val = $call->getSettingsItem($this->context->language->id, $this->context->shop->id, $id_product, 'product');

    if (!$val) {
      $obj = new CallForPriceItem();
    } else {
      $obj = new CallForPriceItem($val[0]['id_callforprice']);
    }

    foreach (Language::getLanguages(true) as $lang) {
      $price_text = $input_fields_data['price_text_' . $lang['id_lang']];
      $title_button = $input_fields_data['title_button_' . $lang['id_lang']];

      if (!$title_button) {
        $title_button = $input_fields_data['title_button_' . $default_lang];
      }

      if (!$price_text) {
        $price_text = $input_fields_data['price_text_' . $default_lang];
      }

      $obj->label_price[$lang['id_lang']] = $price_text;
      $obj->title_button[$lang['id_lang']] = $title_button;
    }

    $disable_price = $input_fields_data['disable_price'];
    $logged = $input_fields_data['logged'];
    $disable_button_add = $input_fields_data['disable_button_add'];
    $enable_button_callforprice = $input_fields_data['enable_button_callforprice'];
    $filter_by_customer_group = $input_fields_data['filter_by_customer_group'];


    $obj->id_product = $id_product;
    $obj->type = 'product';
    $obj->disable_price = $disable_price;
    $obj->logged = $logged;
    $obj->filter_by_customer_group = $filter_by_customer_group;
    $obj->disable_button_add = $disable_button_add;
    $obj->enable_button_callforprice = $enable_button_callforprice;
    $obj->customer_group_ids = callForPrice::getValuesFromCheckboxTableInSingleArray('customer_group_ids', $input_fields_data);

    $res = $obj->save();

    return $res;
  }

  public function renderForm()
  {
    $price_value = $quantity_value = '';
    $price_type = $quantity_type = 0;
    $price_value =  Configuration::get('GOMAKOIL_CALLFORPRICE_PRICE_VALUE');
    $quantity_value =  Configuration::get('GOMAKOIL_CALLFORPRICE_QUANTITY_VALUE');
    $price_type =  Configuration::get('GOMAKOIL_CALLFORPRICE_PRICE_TYPE');
    $quantity_type =  Configuration::get('GOMAKOIL_CALLFORPRICE_QUANTITY_TYPE');
    $settings = $this->_callForPriceSetting->getSettings(Context::getContext()->language->id, Context::getContext()->shop->id);
    if (isset($settings[0]['id_callforprice_settings']) && $settings[0]['id_callforprice_settings']) {
      $settings = $settings[0];
    }
    else{
      $settings = false;
    }

    $hours = array(
      array(
        'id' => '24',
        'name' => $this->l('12:00 am')
      ),
      array(
        'id' => '1',
        'name' => $this->l('1:00 am')
      ),
      array(
        'id' => '2',
        'name' => $this->l('2:00 am')
      ),
      array(
        'id' => '3',
        'name' => $this->l('3:00 am')
      ),
      array(
        'id' => '4',
        'name' => $this->l('4:00 am')
      ),
      array(
        'id' => '5',
        'name' => $this->l('5:00 am')
      ),
      array(
        'id' => '6',
        'name' => $this->l('6:00 am')
      ),
      array(
        'id' => '7',
        'name' => $this->l('7:00 am')
      ),
      array(
        'id' => '8',
        'name' => $this->l('8:00 am')
      ),
      array(
        'id' => '9',
        'name' => $this->l('9:00 am')
      ),
      array(
        'id' => '10',
        'name' => $this->l('10:00 am')
      ),
      array(
        'id' => '11',
        'name' => $this->l('11:00 am')
      ),
      array(
        'id' => '12',
        'name' => $this->l('12:00 pm')
      ),

      array(
        'id' => '13',
        'name' => $this->l('1:00 pm')
      ),
      array(
        'id' => '14',
        'name' => $this->l('2:00 pm')
      ),
      array(
        'id' => '15',
        'name' => $this->l('3:00 pm')
      ),
      array(
        'id' => '16',
        'name' => $this->l('4:00 pm')
      ),
      array(
        'id' => '17',
        'name' => $this->l('5:00 pm')
      ),
      array(
        'id' => '18',
        'name' => $this->l('6:00 pm')
      ),
      array(
        'id' => '19',
        'name' => $this->l('7:00 pm')
      ),
      array(
        'id' => '20',
        'name' => $this->l('8:00 pm')
      ),
      array(
        'id' => '21',
        'name' => $this->l('9:00 pm')
      ),
      array(
        'id' => '22',
        'name' => $this->l('10:00 pm')
      ),
      array(
        'id' => '23',
        'name' => $this->l('11:00 pm')
      ),
    );

    $settings_no = $this->_callForPriceItem->getSettingsItem(Context::getContext()->language->id, Context::getContext()->shop->id, false, 'no_product');
    if(isset($settings_no[0]['id_callforprice']) && $settings_no[0]['id_callforprice']) {
      $obj = new callForPriceItem($settings_no[0]['id_callforprice']);
      $ids  = Tools::unSerialize($obj->id_category);
    }

    if(!$ids){
      $ids = array();
    }

    if(isset($settings_no[0]['id_callforprice']) && $settings_no[0]['id_callforprice']){
      $disable_price = $obj->disable_price;
      $disable_button_add = $obj->disable_button_add;
      $enable_button_callforprice = $obj->enable_button_callforprice;
      if(!$obj->title_button){
        $title_button = array();
        foreach (Language::getLanguages(true) as $lang){
          $title_button[$lang['id_lang']] = 'Call For Price';
        }
      }
      else{
        $title_button = $obj->title_button;
      }
      if(!$obj->label_price){
        $label_price = array();
        foreach (Language::getLanguages(true) as $lang){
          $label_price[$lang['id_lang']] = 'Call For Price';
        }
      }
      else{
        $label_price = $obj->label_price;
      }
    }
    else{
      $disable_price = 0;
      $disable_button_add = 0;
      $enable_button_callforprice = 0;

      foreach (Language::getLanguages(true) as $lang){
        $label_price[$lang['id_lang']] = 'Call For Price';
        $title_button[$lang['id_lang']] = 'Call For Price';
      }
    }

    $all_manufacturers = Manufacturer::getManufacturers(false, Context::getContext()->language->id, true, false, false, false, true );
    $all_customer_groups = Group::getGroups(Context::getContext()->language->id);

    $checked_manufacturers = unserialize($settings['manufacturer_ids']);
    $checked_customer_groups = unserialize($settings['customer_group_ids']);

    $this->fields_form = array(
      'tinymce' => true,
      'legend' => array(
        'title' => $this->l('Settings'),
        'icon' => 'icon-list-ul'
      ),
      'tabs' => array(
        'general_tab_settings' => $this->l('General settings'),
        'form_tab_settings' => $this->l('Form Settings'),
        'form_list' => $this->l('Call For Price list'),
        'code_mirror' => $this->l('Advanced Styles (CSS)'),
        'support' => $this->l('Support'),
        'modules' => $this->l('Related Modules'),
      ),
      'input' => array(
          array(
              'type' => 'hidden',
              'name' => 'changes_is_done',
              'tab' => 'general_tab_settings'
          ),
        array(
          'type' => 'switch',
          'label' => $this->l('Use global settings for all products'),
          'desc' => $this->l('When this option is active, settings for specific products will be disabled'),
          'name' => 'use_only_global_settings',
          'is_bool' => true,
          'tab' => 'general_tab_settings',
          'values' => array(
              array(
                  'id' => 'use_only_global_settings_on',
                  'value' => 1,
                  'label' => $this->l('Yes')),
              array(
                  'id' => 'use_only_global_settings_off',
                  'value' => 0,
                  'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'textarea',
          'label' => $this->l('Send notification for'),
          'name' => 'email',
          'autoload_rte' => false,
          'rows' => 3,
          'cols' => 20,
          'required' => true,
          'form_group_class' => 'field_width_50',
          'desc' => $this->l('Each email must be separated by a comma'),
          'tab' => 'general_tab_settings',
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Show just for not logged customers'),
          'name' => 'logged',
          'is_bool' => true,
          'tab' => 'general_tab_settings',
          'values' => array(
            array(
              'id' => 'logged_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'logged_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Activate only for customers from selected groups'),
          'name' => 'filter_by_customer_group',
          'tab' => 'general_tab_settings',
          'class' => 'filter_by_customer_group',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'checkbox_table',
          'name' => 'customer_group_ids',
          'class_block' => 'customer_group_list',
          'label' => $this->l('Filter by customer group:'),
          'class_input' => 'select_customer_groups',
          'lang' => true,
          'tab' => 'general_tab_settings',
          'hint' => '',
          'search' => true,
          'display'=> true,
          'values' => array(
            'query' => $all_customer_groups,
            'id' => 'id_group',
            'name' => 'name',
            'value' => $checked_customer_groups,
          ),
        ),
        array(
          'type' => 'html',
          'label' => $this->l('Products with price'),
          'form_group_class' => 'form_group_class_hide',
          'tab' => 'general_tab_settings',
          'name' => $this->priceSelection($price_value, $price_type),
        ),
        array(
          'type' => 'html',
          'label' => $this->l('Products with quantity'),
          'form_group_class' => 'form_group_class_hide',
          'tab' => 'general_tab_settings',
          'name' => $this->quantitySelection($quantity_value, $quantity_type),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Activate for all products'),
          'name' => 'all_products',
          'class' => 'all_products',
          'is_bool' => true,
          'tab' => 'general_tab_settings',
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Activate for products in category'),
          'name' => 'products_category',
          'tab' => 'general_tab_settings',
          'class' => 'products_category',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Activate for products from selected manufacturers'),
          'name' => 'filter_by_manufacturer',
          'tab' => 'general_tab_settings',
          'class' => 'filter_by_manufacturer',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'html',
          'form_group_class' => 'block_settings_line',
          'tab' => 'general_tab_settings',
          'name' => '',
        ),
        array(
          'type' => 'categories',
          'name' => 'id_category',
          'label' => $this->l('Categories'),
          'tab' => 'general_tab_settings',
          'form_group_class' => 'block_settings_cat mpm_callforprice_additional_filter',
          'tree' => array(
            'id' => 'categories-tree',
            'selected_categories' => $ids,
            'root_category' => 2,
            'use_search' => true,
            'use_checkbox' => true
          )
        ),
        array(
          'type' => 'checkbox_table',
          'name' => 'manufacturer_ids',
          'class_block' => 'manufacturer_list_callforprice',
          'label' => $this->l('Filter by manufacturer:'),
          'class_input' => 'select_manufacturers',
          'lang' => true,
          'tab' => 'general_tab_settings',
          'hint' => '',
          'search' => true,
          'display'=> true,
          'values' => array(
            'query' => $all_manufacturers,
            'id' => 'id_manufacturer',
            'name' => 'name',
            'value' => $checked_manufacturers,
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Disable price display:'),
          'name' => 'disable_price',
          'tab' => 'general_tab_settings',
          'form_group_class' => 'block_settings_general',
          'class' => 'disable_price',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'textarea',
          'tab' => 'general_tab_settings',
          'label' => $this->l('Price label text:'),
          'name' => 'price_text',
          'form_group_class' => 'block_settings_general',
          'autoload_rte' => true,
          'rows' => 3,
          'cols' => 20,
          'lang' => true,
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Disable "Add to cart" button:'),
          'name' => 'disable_button_add',
          'class' => 'disable_button_add',
          'form_group_class' => 'block_settings_general',
          'tab' => 'general_tab_settings',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Show "Call For Price" button:'),
          'name' => 'enable_button_callforprice',
          'class' => 'enable_button_callforprice',
          'form_group_class' => 'block_settings_general',
          'is_bool' => true,
          'tab' => 'general_tab_settings',
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Button title:'),
          'name' => 'title_button',
          'form_group_class' => 'block_settings_general',
          'tab' => 'general_tab_settings',
          'lang' => true,
        ),


         array(
            'type' => 'html',
            'name' => '',
            'html_content' =>  $this->getFormPreview(),
            'tab' => 'form_tab_settings',
            'form_group_class' => 'form-preview',
         ),


        array(
          'type' => 'text',
          'label' => $this->l('Form title'),
          'name' => 'title_form',
          'class' => 'title_form',
          'form_group_class' => 'callback-form-title',
          'tab' => 'form_tab_settings',
          'required' => true,
          'lang' => true,
          'maxlength' => 255,
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Form footer message'),
          'name' => 'form_footer_message',
          'class' => 'form_footer_message',
          'form_group_class' => 'form-footer-message-group',
          'tab' => 'form_tab_settings',
          'required' => true,
          'lang' => true,
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Show Product Name'),
          'name' => 'show_product_name_in_title',
          'class' => 'show_product_name_in_title',
          'tab' => 'form_tab_settings',
          'form_group_class' => 'form_tab_settings',
          'is_bool' => true,
          'values' => array(
              array(
                  'id' => 'display_on',
                  'value' => 1,
                  'label' => $this->l('Yes')),
              array(
                  'id' => 'display_off',
                  'value' => 0,
                  'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'html',
          'label' => $this->l('Title font size'),
          'form_group_class' => 'settings-font-size-group',
          'tab' => 'form_tab_settings',
          'name' => $this->getRangeInputFieldForFontSize('title'),
        ),
        array(
          'type' => 'html',
          'label' => $this->l('Product name font size'),
          'form_group_class' => 'settings-font-size-group',
          'tab' => 'form_tab_settings',
          'name' => $this->getRangeInputFieldForFontSize('product_name'),
        ),
        array(
          'type' => 'html',
          'label' => $this->l('Button text font size'),
          'form_group_class' => 'settings-font-size-group',
          'tab' => 'form_tab_settings',
          'name' => $this->getRangeInputFieldForFontSize('button_text'),
        ),
        array(
          'type' => 'html',
          'label' => $this->l('Footer message font size'),
          'form_group_class' => 'settings-font-size-group',
          'tab' => 'form_tab_settings',
          'name' => $this->getRangeInputFieldForFontSize('footer_message'),
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Background color'),
          'name' => 'background_form',
          'class' => 'background_form',
          'form_group_class' => 'form_tab_settings',
            'required' => true,
          'tab' => 'form_tab_settings',
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Background color for header and button'),
          'name' => 'background_button',
          'class' => 'background_button',
          'required' => true,
          'tab' => 'form_tab_settings',
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").'),
          'form_group_class' => 'form_tab_settings'
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Font color for header and button'),
          'name' => 'color_form',
          'class' => 'color_form',
          'tab' => 'form_tab_settings',
          'required' => true,
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").'),
          'form_group_class' => 'form_tab_settings'
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Color on hover'),
          'name' => 'hover_color',
          'class' => 'hover_color',
          'tab' => 'form_tab_settings',
          'required' => true,
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").'),
          'form_group_class' => 'form_tab_settings'
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Footer message font color'),
          'name' => 'form_footer_message_color',
          'class' => 'form_footer_message_color',
          'tab' => 'form_tab_settings',
          'required' => true,
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").'),
          'form_group_class' => 'form_tab_settings'
        ),
        array(
          'type' => 'html',
          'name' => $this->l('Display field settings'),
          'form_group_class' => 'title_form_admin',
          'tab' => 'form_tab_settings',
          'html_content' => $this->l('Display field settings')
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Name'),
          'name' => 'show_name',
          'class' => 'show_name',
          'tab' => 'form_tab_settings',
          'form_group_class' => 'form_tab_settings',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Required'),
          'name' => 'required_name',
          'class' => 'required_name',
          'is_bool' => true,
          'tab' => 'form_tab_settings',
          'form_group_class' => 'form_tab_settings',
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'html',
          'tab' => 'form_tab_settings',
          'form_group_class' => 'block_hr',
          'name' => '<hr>',
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Email'),
          'name' => 'show_email',
          'class' => 'show_email',
          'tab' => 'form_tab_settings',
          'form_group_class' => 'form_tab_settings',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Required'),
          'name' => 'required_email',
          'class' => 'required_email',
          'tab' => 'form_tab_settings',
          'form_group_class' => 'form_tab_settings',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'html',
          'tab' => 'form_tab_settings',
          'form_group_class' => 'block_hr',
          'name' => '<hr>',
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Phone'),
          'name' => 'show_phone',
          'class' => 'show_phone',
          'tab' => 'form_tab_settings',
          'form_group_class' => 'form_tab_settings',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Required'),
          'name' => 'required_phone',
          'class' => 'required_phone',
          'tab' => 'form_tab_settings',
          'form_group_class' => 'form_tab_settings',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'html',
          'tab' => 'form_tab_settings',
          'form_group_class' => 'block_hr',
          'name' => '<hr>',
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Message'),
          'name' => 'show_message',
          'class' => 'show_message',
          'tab' => 'form_tab_settings',
          'form_group_class' => 'form_tab_settings',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Required'),
          'name' => 'required_message',
          'tab' => 'form_tab_settings',
          'class' => 'required_message',
          'form_group_class' => 'form_tab_settings',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'html',
          'tab' => 'form_tab_settings',
          'name' => '<hr>',
          'form_group_class' => 'block_hr',
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Captcha'),
          'name' => 'show_captcha',
          'tab' => 'form_tab_settings',
          'form_group_class' => 'form_tab_settings',
          'class' => 'show_captcha',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'html',
          'tab' => 'form_tab_settings',
          'name' => '<hr>',
          'form_group_class' => 'block_hr',
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('ReCAPTCHA'),
          'name' => 'show_recaptcha',
          'tab' => 'form_tab_settings',
          'class' => 'show_recaptcha',
          'form_group_class' => 'form_tab_settings',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('ReCAPTCHA Key'),
          'name' => 'recaptcha_key',
          'class' => 'recaptcha_key',
          'tab' => 'form_tab_settings',
          'form_group_class' => 'form_tab_settings',
          'maxlength' => 255,
        ),
        array(
          'type' => 'html',
          'tab' => 'form_tab_settings',
          'name' => '<hr>',
          'form_group_class' => 'block_hr',
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Ask customer for consent to process his personal data'),
          'name' => 'show_consent_checkbox',
          'is_bool' => true,
          'tab' => 'form_tab_settings',
          'form_group_class' => 'form_tab_settings',
          'values' => array(
            array(
              'id' => 'show_consent_checkbox_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'show_consent_checkbox_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'textarea',
          'label' => $this->l('Consent message'),
          'name' => 'consent_checkbox_message',
          'tab' => 'form_tab_settings',
          'lang' => true,
          'autoload_rte' => true,
          'form_group_class' => 'consent_checkbox_message_textarea',
          'rows' => 10,
          'cols' => 100
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Let user choose convenient hour'),
          'name' => 'show_delay',
          'is_bool' => true,
          'tab' => 'form_tab_settings',
          'form_group_class' => 'form_tab_settings',
          'values' => array(
            array(
              'id' => 'show_delay_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'show_delay_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'html_content' =>  $this->l('Possible period of time'),
          'tab' => 'form_tab_settings',
          'form_group_class' => 'date_label delay_hidden',
        ),


        array(
          'type' => 'select',
          'label' => $this->l('From:'),
          'name' => 'delay_date_from',
          'tab' => 'form_tab_settings',
          'form_group_class' => 'date_from delay_hidden',
          'options' => array(
            'query' =>$hours,
            'name' => 'name',
            'id' => 'id'
          ),
        ),
        array(
          'type' => 'select',
          'label' => $this->l('To:'),
          'name' => 'delay_date_to',
          'tab' => 'form_tab_settings',
          'form_group_class' => 'date_to delay_hidden',
          'options' => array(
            'query' =>$hours,
            'name' => 'name',
            'id' => 'id'
          ),
        ),
        array(
          'type' => 'html',
          'tab' => 'form_tab_settings',
          'name' => '<hr>',
          'form_group_class' => 'block_hr',
        ),

        array(
          'type' => 'html',
          'name' => '',
          'html_content' =>  $this->listCallBack(1, false),
          'tab' => 'form_list',
          'form_group_class' => 'list_callback',
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'html_content' =>  $this->supportBlock(),
          'tab' => 'support',
          'form_group_class' => 'supportBlock',
        ),
        array(
          'type' => 'html',
          'tab' => 'modules',
          'form_group_class' => 'support_tab_content supportBlock',
          'name' => 'html_data',
          'html_content' =>  $this->displayTabModules(),
        ),
        array(
          'type' => 'hidden',
          'name' => 'token_callforprice',
        ),
        array(
          'type' => 'hidden',
          'name' => 'idLang',
        ),

        array(
          'type' => 'textarea',
          'label' => $this->l('Advanced Styles (CSS)'),
          'name' => 'css_code',
          'class' => 'css_code',
          'form_group_class' => 'codeMirror',
          'height' => 300,
          'tab' => 'code_mirror',
        ),
      ),
      'buttons' => array(
        'save-and-stay' => array(
          'title' => $this->l('Save and stay'),
          'name' => 'submitAdd'.$this->table.'AndStay',
          'type' => 'submit',
          'class' => 'btn btn-default pull-right',
          'icon' => 'process-icon-save'
        ),
      ),
    );

    $this->fields_value['disable_price'] = $disable_price;
    $this->fields_value['disable_button_add'] = $disable_button_add;
    $this->fields_value['enable_button_callforprice'] = $enable_button_callforprice;
    $this->fields_value['price_text'] = $label_price;
    $this->fields_value['title_button'] = $title_button;
    $this->fields_value['idLang'] = $this->_idLang;
    $this->tpl_form_vars['idLang'] = $this->_idLang;
    $this->tpl_form_vars['idShop'] = $this->_idShop;
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');
    $this->fields_value['token_callforprice'] = Tools::getAdminTokenLite('AdminCallForPrice');
    $this->fields_value['changes_is_done'] = (int)$this->isChangesDone();

    return parent::renderForm();
  }

  public function priceSelection($value, $type){
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'callforprice/views/templates/hook/blockSelectionPrice.tpl');
    $data->assign(
      array(
        'value'   => $value,
        'type'   => $type,
      )
    );
    return $data->fetch();
  }

  public function quantitySelection($value, $type){
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'callforprice/views/templates/hook/blockSelectionQuantity.tpl');
    $data->assign(
      array(
        'value'   => $value,
        'type'   => $type,
      )
    );
    return $data->fetch();
  }

  public function supportBlock(){

    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'callforprice/views/templates/hook/supportForm.tpl');
    return $data->fetch();

  }


  public function displayTabModules(){
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'callforprice/views/templates/hook/modules.tpl');
    return $data->fetch();
  }

  public function listCallBack($p, $search){

    $n = 25;
    $all = 0;
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'callforprice/views/templates/hook/callBackList.tpl');

    $limit = ($p-1)*(int)$n.','.$n;

    $values = $this->getCallBackList($limit, $search);



    $states = array(
      'Waiting' => 'Waiting',
      'To Recall' => 'To Recall',
      'Processed' => 'Processed',
      'Canceled' => 'Canceled',
      'Not Responding' => 'Not Responding',
    );


    if(isset($values[0]) && $values[0]){

      $count = $this->countCallBackList($search);


      if(isset($count[0]['count_id']) && $count[0]['count_id']){
        $all = $count[0]['count_id'];
      }

      $productNb = (int)$all;
      $pages_nb = ceil($productNb/$n);

      if( $pages_nb > 5 && $p > 3){
        $stop = 2 + $p;
      }
      elseif( $pages_nb > 5){
        $stop = 5;
      }
      else{
        $stop = $pages_nb;
      }
      if( $p == $pages_nb || $p == ($pages_nb + 1) ){
        $stop = $p;
      }
      if( ($p +1) == $pages_nb ){
        $stop = $p + 1;
      }
      $start = 1;
      if( $p >= 5 ){
        $start = $p - 2;
      }

      if( version_compare(_PS_VERSION_, '1.6.0.0') >= 0 && version_compare(_PS_VERSION_, '1.7.0.0') < 0) {
        $version_new = false;
      }
      else{
        $version_new = true;
      }


      foreach ($values as $key => $val){
        $values[$key]['prod_name'] = ProductCore::getProductName($val['id_product']);
      }

      $data->assign(
        array(
          'items'    => $values,
          'states'   => $states,
          'count'   => $all,
          'n'   => $n,
          'p'   => $p,
          'start'             => $start,
          'search'             => $search,
          'stop'              => $stop,
          'pages_nb'          => $pages_nb,
          'version_new'          => $version_new,
          'path_pagination'   => '',
        )
      );
      return $data->fetch();
    }

  }


  public function countCallBackList($search){

    $where = '';

    if( $search ){

      $id = $search['id_callforprice_list'];
      $phone = $search['phone'];
      $id_product = $search['id_product'];
      $name = $search['name'];
      $prod_name = $search['prod_name'];
      $email = $search['email'];
      $hour = $search['hour'];
      $state = $search['state'];
      $message = $search['message'];
      $date = $search['date'];

      $where = " AND (f.id_callforprice_list LIKE '%$id%' AND f.id_product LIKE '%$id_product%' AND pl.name LIKE '%$prod_name%' AND f.name LIKE '%$name%' AND f.phone LIKE '%$phone%' AND f.email LIKE '%$email%' AND f.hour LIKE '%$hour%' AND f.state LIKE '%$state%' AND f.message LIKE '%$message%' AND f.date_add LIKE '%$date%')";

    }

    $sql = '
			SELECT COUNT(DISTINCT f.id_callforprice_list) as count_id
      FROM ' . _DB_PREFIX_ . 'callforprice_list as f
      LEFT JOIN ' . _DB_PREFIX_ . 'product_lang as pl
      ON f.id_product = pl.id_product
           WHERE 1
      '.$where.'
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function getCallBackList($limit, $search){

    $where = '';


    if( $search ){

      $id = $search['id_callforprice_list'];
      $id_product = $search['id_product'];
      $name = $search['name'];
      $prod_name = $search['prod_name'];
      $phone = $search['phone'];
      $email = $search['email'];
      $hour = $search['hour'];
      $state = $search['state'];
      $message = $search['message'];
      $date = $search['date'];

      $where = " AND (f.id_callforprice_list LIKE '%$id%' AND f.id_product LIKE '%$id_product%' AND pl.name LIKE '%$prod_name%' AND f.name LIKE '%$name%' AND f.phone LIKE '%$phone%' AND f.email LIKE '%$email%' AND f.hour LIKE '%$hour%' AND f.state LIKE '%$state%' AND f.message LIKE '%$message%' AND f.date_add LIKE '%$date%')";

    }

    $limit = ' LIMIT '.$limit;

    $sql = '
			SELECT f.*, pl.name as prod_name
      FROM ' . _DB_PREFIX_ . 'callforprice_list as f
      LEFT JOIN ' . _DB_PREFIX_ . 'product_lang as pl
      ON f.id_product = pl.id_product
      WHERE 1
      '.$where.'
      GROUP BY f.id_callforprice_list
      ORDER BY f.id_callforprice_list DESC
      '.$limit.'
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function updateState($id, $state){
    $res = Db::getInstance()->update('callforprice_list', array('state' => pSQL($state)), 'id_callforprice_list = '.(int)$id );
    return $res;
  }

  public function deleteItemList($id){
    $res = Db::getInstance()->delete('callforprice_list', 'id_callforprice_list = '.(int)$id );
    return $res;
  }

  private function getFormPreview()
  {
      $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'callforprice/views/templates/hook/formPreview.tpl');
      $settings = $this->_callForPriceSetting->getSettings(Context::getContext()->language->id, Context::getContext()->shop->id)[0];

      $data->assign(array(
          'captcha_url' => _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/callforprice/secpic.php',
          'hours' => $this->moduleCore->getHours($settings['delay_date_from'], $settings['delay_date_to']),
          'config' => $settings,
      ));

      return $data->fetch();
  }

  private function getRangeInputFieldForFontSize($text_id)
  {
    $settings = $this->_callForPriceSetting->getSettings(Context::getContext()->language->id, Context::getContext()->shop->id)[0];
    $tpl = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'callforprice/views/templates/hook/font_size_input_field.tpl');

    $settings_key = 'form_'.$text_id.'_font_size';

    $tpl->assign(array(
      'text_id' => $text_id,
      'value' => $settings[$settings_key],
    ));

    return $tpl->fetch();
  }

  /**
   * Needed for corrent work of helper form template override
   * @param Helper $helper
   */
  public function setHelperDisplay(Helper $helper)
  {
    if (empty($this->toolbar_title)) {
      $this->initToolbarTitle();
    }
    // tocheck
    if ($this->object && $this->object->id) {
      $helper->id = $this->object->id;
    }

    $helper->title = is_array($this->toolbar_title) ? implode(' '.Configuration::get('PS_NAVIGATION_PIPE').' ', $this->toolbar_title) : $this->toolbar_title;
    $helper->toolbar_btn = $this->toolbar_btn;
    $helper->show_toolbar = $this->show_toolbar;
    $helper->toolbar_scroll = $this->toolbar_scroll;
    $helper->override_folder = false;
    $helper->module = Module::getInstanceByName('callforprice');
    $helper->actions = $this->actions;
    $helper->simple_header = $this->list_simple_header;
    $helper->bulk_actions = $this->bulk_actions;
    $helper->currentIndex = self::$currentIndex;
    $helper->className = $this->className;
    $helper->table = $this->table;
    $helper->name_controller = Tools::getValue('controller');
    $helper->orderBy = $this->_orderBy;
    $helper->orderWay = $this->_orderWay;
    $helper->listTotal = $this->_listTotal;
    $helper->shopLink = $this->shopLink;
    $helper->shopLinkType = $this->shopLinkType;
    $helper->identifier = $this->identifier;
    $helper->token = $this->token;
    $helper->languages = $this->_languages;
    $helper->specificConfirmDelete = $this->specificConfirmDelete;
    $helper->imageType = $this->imageType;
    $helper->no_link = $this->list_no_link;
    $helper->colorOnBackground = $this->colorOnBackground;
    $helper->ajax_params = (isset($this->ajax_params) ? $this->ajax_params : null);
    $helper->default_form_language = $this->default_form_language;
    $helper->allow_employee_form_lang = $this->allow_employee_form_lang;
    $helper->multiple_fieldsets = $this->multiple_fieldsets;
    $helper->row_hover = $this->row_hover;
    $helper->position_identifier = $this->position_identifier;
    $helper->position_group_identifier = $this->position_group_identifier;
    $helper->controller_name = $this->controller_name;
    $helper->list_id = isset($this->list_id) ? $this->list_id : $this->table;
    $helper->bootstrap = $this->bootstrap;

    // For each action, try to add the corresponding skip elements list
    $helper->list_skip_actions = $this->list_skip_actions;

    $this->helper = $helper;
  }


  private function assembleManufacturerIdsFromPost()
  {
    $manufacturer_ids = array();
    $form_values = Tools::getAllValues();

    foreach ($form_values as $key => $value) {
      if (preg_match('/^manufacturer_ids_\d+$/', $key)) {
        array_push($manufacturer_ids, $value);
        unset($_POST[$key]);
      }
    }

    return serialize($manufacturer_ids);
  }

  public function processSave()
  {
    $_POST['manufacturer_ids'] = callForPrice::getValuesFromCheckboxTableInSingleArray('manufacturer_ids');
    $_POST['customer_group_ids'] = callForPrice::getValuesFromCheckboxTableInSingleArray('customer_group_ids');

    if ($this->id_object) {
      $this->object = $this->loadObject();
      return $this->processUpdate();
    } else {
      return $this->processAdd();
    }
  }
  public function addJqueryPlugin($name, $folder = null, $css = true)
  {
    if(version_compare(_PS_VERSION_, '1.7.8.0', '<')){
      return parent::addJqueryPlugin($name, $folder, $css);
    }

    if (!is_array($name)) {
      $name = [$name];
    }

    foreach ($name as $plugin) {
      if ($plugin == 'colorpicker') {
        $this->addJS(_PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/callforprice/libraries/jquery.colorpicker.js');
        continue;
      }

      $plugin_path = Media::getJqueryPluginPath($plugin, $folder);

      if (!empty($plugin_path['js'])) {
        $this->addJS($plugin_path['js'], false);
      }
      if ($css && !empty($plugin_path['css'])) {
        $this->addCSS(key($plugin_path['css']), 'all', null, false);
      }
    }
  }

    public function getChangesNotificationTemplate()
    {
        $tpl = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'callforprice/views/templates/admin/installation_changes_remainder.tpl');

        $tpl->assign([
            'img_folder' => _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/callforprice/views/img/',
            'link_to_readme' => _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/callforprice/readme.pdf',
        ]);

         return $tpl->fetch();
    }

    private function isChangesDone()
    {
        $product_tpl_file_content = Tools::file_get_contents(_PS_THEME_DIR_ . 'templates/catalog/product.tpl');
        $quickview_tpl_file_content = Tools::file_get_contents(_PS_THEME_DIR_ . 'templates/catalog/_partials/quickview.tpl');
        $miniatures_product_tpl_file_content = Tools::file_get_contents(_PS_THEME_DIR_ . 'templates/catalog/_partials/miniatures/product.tpl');

        $change_1_is_done = (strpos($product_tpl_file_content, 'displayCallForPriceClass') !== false) &&
            (strpos($product_tpl_file_content, 'displayCallForPrice') !== false) &&
            (strpos($product_tpl_file_content, 'displayCallForPriceButton') !== false);


        $change_2_is_done = (strpos($quickview_tpl_file_content, 'displayCallForPriceClass') !== false) &&
            (strpos($quickview_tpl_file_content, 'displayCallForPrice') !== false) &&
        (strpos($quickview_tpl_file_content, 'displayCallForPriceButton') !== false);

        $change_3_is_done = (strpos($miniatures_product_tpl_file_content, 'displayCallForPriceClassCategory') !== false) &&
            (strpos($miniatures_product_tpl_file_content, 'displayCallForPriceCategory') !== false);

        if ($change_1_is_done && $change_2_is_done && $change_3_is_done) {
            Configuration::updateGlobalValue('MPM_CL_CHANGES_IS_DONE', 1);
            return true;
        }

        Configuration::updateGlobalValue('MPM_CL_CHANGES_IS_DONE', 0);
        return false;
    }

}