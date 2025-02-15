<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.09.15
 * Time: 20:33
 */
require_once(dirname(__FILE__) . '/../../classes/CallForPriceSetting.php');
require_once(dirname(__FILE__) . '/../../callforprice.php');

class callforpriceAjaxFormModuleFrontController extends FrontController
{
  private $_callForPrice;

  public function initContent()
  {
    if (!$this->ajax) {
      parent::initContent();
    }
  }

  public function displayAjax()
  {
    $this->_callForPrice = new CallForPriceSetting();
    $json = array();
    try{
      if (Tools::getValue('action') == 'sendEmail'){
        $settings = $this->_callForPrice->getSettings(Tools::getValue('id_lang'), Tools::getValue('id_shop'));
        $this->validFieldsForm($settings);
        $this->setToCustomerService();

        if(isset($settings[0]['email']) && $settings[0]['email']){
          $emails = explode(',', $settings[0]['email']);
          foreach($emails as $send_to){
            $template_vars = $this->templateMail();
            $template_vars = array('{content}' => $template_vars);
            $send = $this->sendMessage($template_vars, trim($send_to), Tools::getValue('email'));
          }

          if(!$send){
            $json['error'] = Module::getInstanceByName('callforprice')->l('Message not sent, try again!', 'AjaxForm');
          } else{
            $json['success'] = Module::getInstanceByName('callforprice')->l('Message is successfully sent!', 'AjaxForm');
          }
        }
      }


      if (Tools::getValue('action') == 'showForm'){
        $settings = $this->_callForPrice->getSettings(Tools::getValue('id_lang'), Tools::getValue('id_shop'));
        $json['form'] = $this->getCallForPriceForm($settings, Tools::getValue('id_product'), Tools::getValue('id_lang'), Tools::getValue('id_shop')) ;
      }

      die( json_encode($json) );
    } catch(Exception $e){
      $error_info = explode(':', $e->getMessage());
      $json['error_field'] = $error_info[0];
      $json['error_message'] = $error_info[1];

      if( $e->getCode() == 10 ){
        $json['error_message'] = $e->getMessage();
      }
    }

    die( json_encode($json) );
  }

  public function sendMessage($template_vars, $send_to, $email =  false){
    $mail = Mail::Send(
      Configuration::get('PS_LANG_DEFAULT'),
      'callforprice',
      Module::getInstanceByName('callforprice')->l('Call For Price', 'AjaxForm'),
      $template_vars,
      "$send_to",
      NULL,
      $email ? $email : NULL,
      NULL,
      NULL,
      NULL,
      dirname(__FILE__).'/../../mails/');

    return $mail;
  }

  public function templateMail(){

    $fio = Tools::getValue('fio');
    $email = Tools::getValue('email');
    $tel_number = Tools::getValue('tel_number');
    $message = Tools::getValue('message');
    $id_product = Tools::getValue('id_product');
    $id_lang = Tools::getValue('id_lang');
    $productName = Product::getProductName($id_product);
    $product = new Product($id_product, false, $id_lang);
    $delay = Tools::getValue('delay');

    $productLink = $product->getLink();
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'callforprice/views/templates/hook/templateMail.tpl');
    $baseUrl = _PS_BASE_URL_SSL_.__PS_BASE_URI__;
    $logo = Context::getContext()->link->getMediaLink(_PS_IMG_.Configuration::get('PS_LOGO'));
    $data->assign(
      array(
        'logo_url'     =>  $logo,
        'baseUrl'      => $baseUrl,
        'fio'          => $fio,
        'email'        => $email,
        'tel_number'   => $tel_number,
        'message'      => $message,
        'productName'  => $productName,
        'productLink'  => $productLink,
        'delay'        => $delay,
      )
    );
    return $data->fetch();
  }

  public function setToCustomerService()
  {
    $fio = Tools::getValue('fio');
    $email = Tools::getValue('email');
    $tel_number = Tools::getValue('tel_number');
    $message = Tools::getValue('message');
    $id_lang = Tools::getValue('id_lang');
    $id_shop = Tools::getValue('id_shop');
    $id_product = Tools::getValue('id_product');
    $productName = Product::getProductName($id_product);
    $delay = Tools::getValue('delay');
    $this->setListToBd($fio, $email, $tel_number, $delay, $message, $id_product);


    $com = ' ';
    $id_contact = 2;
    $contact = new Contact($id_contact, $id_lang);

    $com .= Module::getInstanceByName('callforprice')->l('CALL FOR PRICE.', 'AjaxForm')."\n";
    if($productName){
      $com .= Module::getInstanceByName('callforprice')->l('Product:', 'AjaxForm').$productName."\n";
    }
    if($fio){
      $com .= Module::getInstanceByName('callforprice')->l('Name:', 'AjaxForm').$fio."\n";
    }
    if($email){
      $com .= Module::getInstanceByName('callforprice')->l('E-mail:', 'AjaxForm').$email."\n";
    }
    if($tel_number){
      $com .= Module::getInstanceByName('callforprice')->l('Phone number:', 'AjaxForm').$tel_number."\n";
    }
    if($delay){
      $com .= Module::getInstanceByName('callforprice')->l('Callback convenient hour:', 'AjaxForm').$delay."\n";
    }
    if($message){
      $com .= Module::getInstanceByName('callforprice')->l('Message:', 'AjaxForm').$message."\n";
    }
    if($email){
      $id_customer_thread = $this->getIdCustomerThreadByEmail($email, $id_shop);
    }
    else{
      $id_customer_thread = false;
    }
    if($id_customer_thread){
      $old = $this->oldMessage($id_customer_thread, $id_shop);
      if ($old == $com) {
        $contact->email = '';
        $contact->customer_service = 0;
      }
    }
    if ($contact->customer_service) {
      if ((int)$id_customer_thread) {
        $ct = new CustomerThread($id_customer_thread);
        $ct->id_shop = (int)$id_shop;
        $ct->id_lang = (int)$id_lang;
        $ct->id_contact = $id_contact;
        $ct->email = $email;
        $ct->status = 'open';
        $ct->token = Tools::passwdGen(12);
        $ct->update();
      }
      else{
        $ct = new CustomerThread();
        $ct->id_shop = (int)$id_shop;
        $ct->id_lang = (int)$id_lang;
        $ct->id_contact = $id_contact;
        $ct->email = $email;
        $ct->status = 'open';
        $ct->token = Tools::passwdGen(12);
        $ct->add();
      }

      if ($ct->id) {
        $cm = new CustomerMessage();
        $cm->id_customer_thread = $ct->id;
        $cm->message = $com;
        $cm->ip_address = (int)ip2long(Tools::getRemoteAddr());
        $cm->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $cm->add();
      }
    }
  }

  public function setListToBd($fio, $email ,$tel_number, $delay, $message, $id_product){

    if($fio){
      $fio = pSQL($fio);
    }
    else{
      $fio = pSQL('No');
    }
    if($tel_number){
      $phone = pSQL($tel_number);
    }
    else{
      $phone = pSQL('No');
    }
    if($email){
      $email = pSQL($email);
    }
    else{
      $email = pSQL('No');
    }
    if($delay){
      $delay = pSQL($delay);
    }
    else{
      $delay = pSQL('No');
    }
    if($message){
      $comment = pSQL($message);
    }
    else{
      $comment = pSQL('No');
    }
    if($id_product){
      $id_product = (int)$id_product;
    }
    else{
      $id_product = 0;
    }

    $state = 'Waiting';

    $date_add = pSQL(date('Y-m-d H:i:00', time()));

    $value = array(
      'name'   => $fio,
      'phone'   => $phone,
      'email'   => $email,
      'hour'   => $delay,
      'message' => $comment,
      'state'   => $state,
      'id_product'   => $id_product,
      'date_add'   => $date_add,
    );

   Db::getInstance()->insert('callforprice_list', $value);
  }


  public function getIdCustomerThreadByEmail($email, $id_shop)
  {
    return Db::getInstance()->getValue('
			SELECT cm.id_customer_thread
			FROM '._DB_PREFIX_.'customer_thread cm
			WHERE cm.email = \''.pSQL($email).'\'
				AND cm.id_shop = '.(int)$id_shop
    );
  }

  public function oldMessage($id_customer_thread, $id_shop){
    return Db::getInstance()->getValue('
					SELECT cm.message FROM '._DB_PREFIX_.'customer_message cm
					LEFT JOIN '._DB_PREFIX_.'customer_thread cc on (cm.id_customer_thread = cc.id_customer_thread)
					WHERE cc.id_customer_thread = '.(int)$id_customer_thread.' AND cc.id_shop = '.(int)$id_shop.'
					ORDER BY cm.date_add DESC');
  }

  public function validFieldsForm($settings){
    $fio = Tools::getValue('fio');
    $email = Tools::getValue('email');
    $tel_number = Tools::getValue('tel_number');
    $message = Tools::getValue('message');
    $captcha_value = Tools::getValue('captcha_value');
    $moduleCore = Module::getInstanceByName('callforprice');


    if(isset($settings[0]) && $settings[0]){
      $config = $settings[0];
    }

    if(!$fio && $config['required_name'] == 1){
      throw new Exception ('fio:' . $moduleCore->l('Name is required', 'AjaxForm'));
    }

    if ($config['required_email'] == 1 && !$email) {
      throw new Exception ('email:' . $moduleCore->l('E-mail is required', 'AjaxForm'));
    } elseif ($email && !Validate::isEmail($email)) {
      throw new Exception ('email:' . $moduleCore->l('E-mail is not valid', 'AjaxForm'));
    }

    if($config['required_phone'] == 1 && !$tel_number){
      throw new Exception ('tel_number:' . $moduleCore->l('Phone number is required', 'AjaxForm'));
    } elseif ($tel_number && !Validate::isPhoneNumber($tel_number)) {
      throw new Exception ('tel_number:' . $moduleCore->l('Phone number is not valid', 'AjaxForm'));
    }

    if(!$message  && $config['required_message'] == 1){
      throw new Exception ('message:' . $moduleCore->l('Message is required', 'AjaxForm'));
    }

    if($config['show_captcha']){
      $captcha_session = Tools::strtolower(Context::getContext()->cookie->_CAPTCHA);
      if(Tools::strtolower($captcha_value) !== Tools::strtolower($captcha_session) ){
        throw new Exception ('captcha_res:' . $moduleCore->l('It is looks like captcha does not match, make sure you are not a robot and try again', 'AjaxForm'));
      }
    }

  }

  public function getCallForPriceForm($settings, $id_product, $id_lang, $id_shop)
  {
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'callforprice/views/templates/hook/formCallForPrice.tpl');
    if(isset($settings[0]) && $settings[0]){
      $settings = $settings[0];
    }

    $productName = Product::getProductName($id_product);
    $hours = Module::getInstanceByName('callforprice')->getHours($settings['delay_date_from'], $settings['delay_date_to']);
    $is_logged = $this->context->customer->isLogged();


    $name = '';
    $phone = false;
    $email = false;


    if($is_logged){

      if($this->context->customer->firstname){
        $name = $this->context->customer->firstname;
      }

      if($this->context->customer->lastname){
        $name .= ' '.$this->context->customer->lastname;
      }

      if($this->context->customer->email){
        $email = $this->context->customer->email;
      }

      $id_address = Address::getFirstCustomerAddressId($this->context->customer->id);

      if(isset($id_address) && $id_address){
        $address = new Address($id_address, $id_lang);
        if(isset($address->phone) && $address->phone){
          $phone = $address->phone;
        }
      }
    }

    $data->assign(
      array(
        'id_shop'           => $id_shop,
        'id_lang'           => $id_lang,
        'productName'       => $productName,
        'config'            => $settings,
        'hours'             => $hours,
        'name'              => $name,
        'email'             => $email,
        'phone'             => $phone,
        'captcha_url'       => _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/callforprice/secpic.php',
        'base_dir'          => _PS_BASE_URL_SSL_.__PS_BASE_URI__,
        'id_product'        => $id_product,
        'is_mobile'         => Context::getContext()->getMobileDevice(),
      )
    );
    return $data->fetch();
  }

 }