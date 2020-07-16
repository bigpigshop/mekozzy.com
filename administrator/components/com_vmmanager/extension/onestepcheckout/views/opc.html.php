<?php

/** ------------------------------------------------------------------------
  One Page Checkout
  author CMSMart Team
  copyright: Copyright (c) 2012 http://cmsmart.net. All Rights Reserved.
  @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
  Websites: http://cmsmart.net
  Email: team@cmsmart.net
  Technical Support: Forum - http://cmsmart.net/forum
  ------------------------------------------------------------------------- */
defined('_JEXEC') or die('Restricted access');
defined('BS') or define('BS',DIRECTORY_SEPARATOR);
jimport('joomla.html.parameter');
jimport('joomla.application.component.view');
if (!class_exists('OpcCartHelper'))
    require_once('plugins/system/onestepcheckout/helpers/cart_opc.php');
if (!class_exists('VmView'))
    require_once(VMPATH_SITE . BS . 'helpers' . BS . 'vmview.php');

class VirtueMartViewCart extends VmView {

    private $_model;
    var $pointAddress = false;
    protected $cart;
    protected $cart_opc;
    protected $_checkcomdelivery = false;
    var $html = false;

    //Display function
    public function display($tpl = null) {
        $this->cart = VirtueMartCart::getCart();
        $this->_model = VmModel::getModel('user');

        $plugin = JPluginHelper::isEnabled('system', 'delivery_date');
        if ($plugin && file_exists(JPATH_ADMINISTRATOR.BS.'components'.BS.'com_deliverycity'.BS.'deliverycity.php'))
        {
            $this->_checkcomdelivery = TRUE;
        }
            
        JFactory::getLanguage()->load('plg_system_onestepcheckout', 'plugins/system/onestepcheckout');
        VmConfig::loadJLang('com_virtuemart', true);
        $this->cart_opc = new OpcCartHelper;
        $app = JFactory::getApplication();
        $this->prepareContinueLink();
        $opc_task = JFactory::getApplication()->input->getString('opc_task', '');
        $opctask = JFactory::getApplication()->input->getString('opctask', '');
        if (VmConfig::get('use_as_catalog', 0)) {
            vmInfo('This is a catalogue, you cannot access the cart');
            $app->redirect($this->continue_link);
        }
        $pathway = $app->getPathway();
        $document = JFactory::getDocument();
        $document->setMetaData('robots', 'NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET');
        $this->layoutName = $this->getLayout();
        if (!$this->layoutName)
            $this->layoutName = vRequest::getCmd('layout', 'default');
        if ($this->layoutName == 'orderdone' or $this->layoutName == 'order_done') {
            VmConfig::loadJLang('com_virtuemart_shoppers', true);
            $this->lOrderDone();
            $pathway->addItem(vmText::_('COM_VIRTUEMART_CART_THANKYOU'));
            $document->setTitle(vmText::_('COM_VIRTUEMART_CART_THANKYOU'));
        } else {
            $this->layoutName = 'default';
            $format = vRequest::getCmd('format');
            if (!class_exists('VirtueMartCart')){require_once(VMPATH_SITE . '/helpers/cart.php');}
            if (!class_exists('CurrencyDisplay')){require_once(VMPATH_ADMIN . BS . 'helpers' . BS . 'currencydisplay.php');}
           
            $this->currencyDisplay = CurrencyDisplay::getInstance(); 
           
            
            $customfieldsModel = VmModel::getModel('Customfields');
            $this->assignRef('customfieldsModel', $customfieldsModel);
            /** ------------------------------------------------------------------------------------- */
            /** Select - Update Ajax */
            /** ------------------------------------------------------------------------------------- */
            switch ($opc_task) {
                case 'select_shipment':
                    $return = $this->cart_opc->setShipmentMethod();
                    echo intval($return);
                    JFactory::getApplication()->close();
                    break;
                case 'select_payment':
                    $return = $this->cart_opc->setPaymentMethod(true);
                    echo intval($return);
                    JFactory::getApplication()->close();
                    break;
                case 'saveBillTo':
                    $this->cart_opc->saveBillTo();
                    echo 'true';
                    JFactory::getApplication()->close();
                    break;
                case 'saveShipTo':
                    $this->cart_opc->saveShipTo();
                    echo 'true';
                    JFactory::getApplication()->close();
                    break;
                case 'useShipto':
                    $this->cart_opc->useShipto();
                    echo 'true';
                    JFactory::getApplication()->close();
                    break;
                case 'setAddress':
                    $this->cart_opc->setAddress();
                    echo 'true';
                    JFactory::getApplication()->close();
                    break;
                case 'updateProduct':
                    $result = $this->cart_opc->updateProduct();
                    echo json_encode($result);
                    JFactory::getApplication()->close();
                    break;
                case 'deleteProduct':
                    $result = $this->cart_opc->deleteProductCart();
                    echo json_encode($result);
                    JFactory::getApplication()->close();
                    break;
                case 'saveCouponCode':
                    $result = $this->cart_opc->saveCouponCode();
                    echo json_encode($result);
                    JFactory::getApplication()->close();
                    break;
                case 'register':
                    $result = $this->cart_opc->registerF();
                    echo json_encode($result);
                    JFactory::getApplication()->close();
                    break;
                case 'loadDelivery':
                    $this->cart_opc->saveBillTo();
                    echo 'true';
                    JFactory::getApplication()->close();
                    break;
            }
            /** ------------------------------------------------------------------------------------- */
            /** End Select - Update Ajax */
            /** ------------------------------------------------------------------------------------- */
            
            $this->cart->prepareVendor();
            $this->cart->prepareCartData();
            $lSelectShipment = null;
            $lSelectPayment = null;
            if ($this->cart->products) {
                $lSelectShipment = $this->lSelectShipment();
                $lSelectPayment = $this->lSelectPayment();
                $this->lSelectCoupon();
            }
            /** ------------------------------------------------------------------------------------- */
            /** GET HTML FORM AJAX */
            /** ------------------------------------------------------------------------------------- */
            switch ($opctask) {
                case 'get3form':
                    $result['error'] = 0;
                    $data['shipment'] = $this->loadView('default_shipment');
                    $data['payment'] = $this->loadView('default_payment');
                    $data['order'] = $this->loadView('default_orderinfo');
                    if($this->_checkcomdelivery){
                        $data['delivery'] = $this->loadView('default_delivery_1');
                    }
                    
                    $result['msg'] = $data;
                    echo json_encode($result);
                    JFactory::getApplication()->close();
                    break;
            }
            /** ------------------------------------------------------------------------------------- */
            /** END GET HTML FORM AJAX */
            /** ------------------------------------------------------------------------------------- */
            VmConfig::loadJLang('com_virtuemart_shoppers', true);
            if (!class_exists('VirtueMartModelUserfields')) {
                require_once(VMPATH_ADMIN . BS . 'models' . BS . 'userfields.php');
            }
            $userFieldsModel = VmModel::getModel('userfields');
            $userFieldsCart = $userFieldsModel->getUserFields(
                    'cart'
                    , array('captcha' => true, 'delimiters' => true) // Ignore these types
                    , array('delimiter_userinfo', 'user_is_vendor', 'username', 'password', 'password2', 'agreed', 'address_type') // Skips
            );
            $this->userFieldsCart = $userFieldsModel->getUserFieldsFilled(
                    $userFieldsCart
                    , $this->cart->cartfields
            );

            $userFields = null;
            $virtuemart_userinfo_id = 0;
            $virtuemart_userinfo_id = $this->_model->getBTuserinfo_id();

            $userFieldsBT = $this->_model->getUserInfoInUserFields($layoutName, 'BT', $virtuemart_userinfo_id,false);
            $userFieldsST = $this->_model->getUserInfoInUserFields($layoutName, 'ST', $virtuemart_userinfo_id,false);

            $this->assignRef('userFieldsBT', $userFieldsBT[$virtuemart_userinfo_id]);
            $this->assignRef('userFieldsST', $userFieldsST[$virtuemart_userinfo_id]);

            $customfieldsModel = VmModel::getModel('Customfields');
            $this->assignRef('customfieldsModel', $customfieldsModel);

            $totalInPaymentCurrency = $this->getTotalInPaymentCurrency();
            $checkoutAdvertise = $this->getCheckoutAdvertise();
            if ($this->cart->getDataValidated()) {
                if ($this->cart->_inConfirm) {
                    $pathway->addItem(vmText::_('COM_VIRTUEMART_CANCEL_CONFIRM_MNU'));
                    $document->setTitle(vmText::_('COM_VIRTUEMART_CANCEL_CONFIRM_MNU'));
                    $text = vmText::_('COM_VIRTUEMART_CANCEL_CONFIRM');
                    $this->checkout_task = 'cancel';
                } else {
                    $pathway->addItem(vmText::_('COM_VIRTUEMART_ORDER_CONFIRM_MNU'));
                    $document->setTitle(vmText::_('COM_VIRTUEMART_ORDER_CONFIRM_MNU'));
                    $text = vmText::_('COM_VIRTUEMART_ORDER_CONFIRM_MNU');
                    $this->checkout_task = 'confirm';
                }
            } else {
                $pathway->addItem(vmText::_('COM_VIRTUEMART_CART_OVERVIEW'));
                $document->setTitle(vmText::_('COM_VIRTUEMART_CART_OVERVIEW'));
                $text = vmText::_('COM_VIRTUEMART_CHECKOUT_TITLE');
                $this->checkout_task = 'checkout';
            }
            $this->checkout_link_html = '<button type="submit"  id="checkoutFormSubmit" name="' . $this->checkout_task . '" value="1" class="vm-button-correct" ><span>' . $text . '</span> </button>';
            if (VmConfig::get('oncheckout_opc', 1)) {
                if (!class_exists('vmPSPlugin'))
                    require_once(JPATH_VM_PLUGINS . BS . 'vmpsplugin.php');
                JPluginHelper::importPlugin('vmshipment');
                JPluginHelper::importPlugin('vmpayment');
                if ($this->cart->products) {
                    if ($lSelectPayment == null){
                        $lSelectPayment = $this->lSelectPayment();
                    }
                    if ($lSelectShipment == null){
                        $lSelectShipment = $this->lSelectShipment();
                    }
                    if (!$lSelectShipment or ! $lSelectPayment) {
                        vmInfo('COM_VIRTUEMART_CART_ENTER_ADDRESS_FIRST');
                        $this->pointAddress = true;
                    }
                }
            } else {
                $this->checkPaymentMethodsConfigured();
                $this->checkShipmentMethodsConfigured();
            }
            if ($this->cart->virtuemart_shipmentmethod_id) {
                $shippingText = vmText::_('COM_VIRTUEMART_CART_CHANGE_SHIPPING');
            } else {
                $shippingText = vmText::_('COM_VIRTUEMART_CART_EDIT_SHIPPING');
            }
            $this->assignRef('select_shipment_text', $shippingText);
            if ($this->cart->virtuemart_paymentmethod_id) {
                $paymentText = vmText::_('COM_VIRTUEMART_CART_CHANGE_PAYMENT');
            } else {
                $paymentText = vmText::_('COM_VIRTUEMART_CART_EDIT_PAYMENT');
            }

            $this->assignRef('select_payment_text', $paymentText);
            $this->cart->prepareAddressFieldsInCart();
            
            /* check template override path */
            $template = \JFactory::getApplication()->getTemplate();
            $temPath = JPATH_THEMES . '/' . $template . '/html/plg_system_onestepcheckout/';
            if (file_exists($temPath)){
                $this->addTemplatePath($temPath);
            }else{
                $this->addTemplatePath(dirname(__FILE__) . BS . 'tmpl' . BS);
            }
        }

        $lang = JFactory::getLanguage();
        $order_language = $lang->getTag();
        $this->assignRef('order_language', $order_language);
        $this->useSSL = VmConfig::get('useSSL', 0);
        $this->useXHTML = false;
        $this->assignRef('totalInPaymentCurrency', $totalInPaymentCurrency);
        $this->assignRef('checkoutAdvertise', $checkoutAdvertise);
        $document->setMetaData('robots', 'NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET');

        $current = JFactory::getUser();
        $this->allowChangeShopper = false;
        $this->adminID = false;
        if(VmConfig::get ('oncheckout_change_shopper')){
            $this->allowChangeShopper = vmAccess::manager('user');
        }

        $this->shopperGroupList = false;
        if($this->allowChangeShopper){
            $this->userList = $this->getUserList();
            $this->shopperGroupList = $this->getShopperGroupList();
        }

        parent::display($tpl);
    }

    private function lSelectCoupon() {
        $this->couponCode = (!empty($this->cart->couponCode) ? $this->cart->couponCode : '');
        $this->coupon_text = $this->cart->couponCode ? vmText::_('COM_VIRTUEMART_COUPON_CODE_CHANGE') : vmText::_('COM_VIRTUEMART_COUPON_CODE_ENTER');
    }

    private function lSelectShipment() {
        $found_shipment_method = false;
        $shipment_not_found_text = vmText::_('COM_VIRTUEMART_CART_NO_SHIPPING_METHOD_PUBLIC');
        $this->assignRef('shipment_not_found_text', $shipment_not_found_text);
        $this->assignRef('found_shipment_method', $found_shipment_method);
        $shipments_shipment_rates = array();
        if (!$this->checkShipmentMethodsConfigured()) {
            $this->assignRef('shipments_shipment_rates', $shipments_shipment_rates);
            return;
        }
        $selectedShipment = (empty($this->cart->virtuemart_shipmentmethod_id) ? 0 : $this->cart->virtuemart_shipmentmethod_id);

        $shipments_shipment_rates = array();
        if (!class_exists('vmPSPlugin'))
            require_once(JPATH_VM_PLUGINS . BS . 'vmpsplugin.php');
        JPluginHelper::importPlugin('vmshipment');
        $dispatcher = JDispatcher::getInstance();

        $returnValues = $dispatcher->trigger('plgVmDisplayListFEShipment', array($this->cart, $selectedShipment, &$shipments_shipment_rates));
        // if no shipment rate defined
        $found_shipment_method = count($shipments_shipment_rates);

        $ok = true;
        if ($found_shipment_method == 0) {
            $validUserDataBT = $this->cart->validateUserData();

            if ($validUserDataBT === -1) {
                if (VmConfig::get('oncheckout_opc', 1)) {
                    vmdebug('lSelectShipment $found_shipment_method === 0 show error');
                    $ok = false;
                } else {
                    $mainframe = JFactory::getApplication();
                    $mainframe->redirect(JRoute::_('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT'), vmText::_('COM_VIRTUEMART_CART_ENTER_ADDRESS_FIRST'));
                }
            }
        }
        if (empty($selectedShipment)) {
            if ($s_id = VmConfig::get('set_automatic_shipment', false)) {
                $j = 'radiobtn = document.getElementById("shipment_id_' . $s_id . '");
				if(radiobtn!==null){ radiobtn.checked = true;}';
                vmJsApi::addJScript('autoShipment', $j);
            }
        }

        $shipment_not_found_text = vmText::_('COM_VIRTUEMART_CART_NO_SHIPPING_METHOD_PUBLIC');
        $this->assignRef('shipment_not_found_text', $shipment_not_found_text);
        $this->assignRef('shipments_shipment_rates', $shipments_shipment_rates);
        $this->assignRef('found_shipment_method', $found_shipment_method);

        return $ok;
    }

    private function lSelectPayment() {

        $this->payment_not_found_text = '';
        $this->payments_payment_rates = array();

        $this->found_payment_method = 0;
        $selectedPayment = empty($this->cart->virtuemart_paymentmethod_id) ? 0 : $this->cart->virtuemart_paymentmethod_id;

        $this->paymentplugins_payments = array();
        if (!$this->checkPaymentMethodsConfigured()) {
            return;
        }

        if (!class_exists('vmPSPlugin'))
            require_once(JPATH_VM_PLUGINS . BS . 'vmpsplugin.php');
        JPluginHelper::importPlugin('vmpayment');
        $dispatcher = JDispatcher::getInstance();

        $returnValues = $dispatcher->trigger('plgVmDisplayListFEPayment', array($this->cart, $selectedPayment, &$this->paymentplugins_payments));

        $this->found_payment_method = count($this->paymentplugins_payments);
        if (!$this->found_payment_method) {
            $link = ''; // todo
            $this->payment_not_found_text = vmText::sprintf('COM_VIRTUEMART_CART_NO_PAYMENT_METHOD_PUBLIC', '<a href="' . $link . '" rel="nofollow">' . $link . '</a>');
        }

        $ok = true;
        if ($this->found_payment_method == 0) {
            $validUserDataBT = $this->cart->validateUserData();
            if ($validUserDataBT === -1) {
                if (VmConfig::get('oncheckout_opc', 1)) {
                    $ok = false;
                } else {
                    $mainframe = JFactory::getApplication();
                    $mainframe->redirect(JRoute::_('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT'), vmText::_('COM_VIRTUEMART_CART_ENTER_ADDRESS_FIRST'));
                }
            }
        }

        if (empty($selectedPayment)) {
            if ($p_id = VmConfig::get('set_automatic_payment', false)) {
                $j = 'radiobtn = document.getElementById("payment_id_' . $p_id . '");
				if(radiobtn!==null){ radiobtn.checked = true;}';
                vmJsApi::addJScript('autoPayment', $j);
            }
        }
        return $ok;
    }

    private function getTotalInPaymentCurrency() {

        if (empty($this->cart->virtuemart_paymentmethod_id)) {
            return null;
        }

        if (!$this->cart->paymentCurrency or ( $this->cart->paymentCurrency == $this->cart->pricesCurrency)) {
            return null;
        }

        $paymentCurrency = CurrencyDisplay::getInstance($this->cart->paymentCurrency);
        $totalInPaymentCurrency = $paymentCurrency->priceDisplay($this->cart->cartPrices['billTotal'], $this->cart->paymentCurrency);
        $this->currencyDisplay = CurrencyDisplay::getInstance($this->cart->pricesCurrency);

        return $totalInPaymentCurrency;
    }

    /*
     * Trigger to place Coupon, payment, shipment advertisement on the cart
     */

    private function getCheckoutAdvertise() {
        $checkoutAdvertise = array();
        JPluginHelper::importPlugin('vmextended');
        JPluginHelper::importPlugin('vmcoupon');
        JPluginHelper::importPlugin('vmshipment');
        JPluginHelper::importPlugin('vmpayment');
        JPluginHelper::importPlugin('vmuserfield');
        $dispatcher = JDispatcher::getInstance();
        $returnValues = $dispatcher->trigger('plgVmOnCheckoutAdvertise', array($this->cart, &$checkoutAdvertise));
        return $checkoutAdvertise;
    }

    private function lOrderDone() {
        $this->display_title = vRequest::getBool('display_title', true);
        $this->html = empty($this->html) ? vRequest::get('html', $this->cart->orderdoneHtml) : $this->html;
    }

    private function checkPaymentMethodsConfigured() {
        $paymentModel = VmModel::getModel('Paymentmethod');
        $payments = $paymentModel->getPayments(true, false);
        if (empty($payments)) {

            $text = '';
            $user = JFactory::getUser();
            if ($user->authorise('core.admin', 'com_virtuemart') or $user->authorise('core.manage', 'com_virtuemart') or VmConfig::isSuperVendor()) {
                $uri = JFactory::getURI();
                $link = $uri->root() . 'administrator/index.php?option=com_virtuemart&view=paymentmethod';
                $text = vmText::sprintf('COM_VIRTUEMART_NO_PAYMENT_METHODS_CONFIGURED_LINK', '<a href="' . $link . '" rel="nofollow">' . $link . '</a>');
            }
            vmInfo('COM_VIRTUEMART_NO_PAYMENT_METHODS_CONFIGURED', $text);
            $this->cart->virtuemart_paymentmethod_id = 0;
            return false;
        }
        return true;
    }

    private function checkShipmentMethodsConfigured() {
        $shipmentModel = VmModel::getModel('Shipmentmethod');
        $shipments = $shipmentModel->getShipments();
        if (empty($shipments)) {

            $text = '';
            $user = JFactory::getUser();
            if ($user->authorise('core.admin', 'com_virtuemart') or $user->authorise('core.manage', 'com_virtuemart') or VmConfig::isSuperVendor()) {
                $uri = JFactory::getURI();
                $link = $uri->root() . 'administrator/index.php?option=com_virtuemart&view=shipmentmethod';
                $text = vmText::sprintf('COM_VIRTUEMART_NO_SHIPPING_METHODS_CONFIGURED_LINK', '<a href="' . $link . '" rel="nofollow">' . $link . '</a>');
            }

            vmInfo('COM_VIRTUEMART_NO_SHIPPING_METHODS_CONFIGURED', $text);

            $tmp = 0;
            $this->assignRef('found_shipment_method', $tmp);
            $this->cart->virtuemart_shipmentmethod_id = 0;
            return false;
        }
        return true;
    }

    /**
     * Todo, works only for small stores, we need a new solution there with a bit filtering
     * For example by time, if already shopper, and a simple search
     * @return object list of users
     */
    function getUserList() {

        $result = false;

        if($this->allowChangeShopper){
            $this->adminID = vmAccess::getBgManagerId();
            $superVendor = vmAccess::isSuperVendor($this->adminID);
            if($superVendor){
                $uModel = VmModel::getModel('user');
                $result = $uModel->getSwitchUserList($superVendor,$this->adminID);
            }
        }
        if(!$result) $this->allowChangeShopper = false;
        return $result;
    }

    function getShopperGroupList() {

        $result = false;

        if($this->allowChangeShopper){
            $userModel = VmModel::getModel('user');
            $vmUser = $userModel->getCurrentUser();

            $attrs = array();
            $attrs['style']='width: 220px;';

            $result = ShopFunctions::renderShopperGroupList($vmUser->shopper_groups, TRUE, 'virtuemart_shoppergroup_id', 'COM_VIRTUEMART_DRDOWN_AVA2ALL', $attrs);
        }

        return $result;
    }

    static public function addCheckRequiredJs() {
        $j = 'jQuery(document).ready(function(){
                jQuery(".output-shipto").find(":radio").change(function(){
                    var form = jQuery("#checkoutFormSubmit");
                    jQuery(this).vm2front("startVmLoading");
            		document.checkoutForm.submit();
                });
                jQuery(".required").change(function(){
                	var count = 0;
                	var hit = 0;
                	jQuery.each(jQuery(".required"), function (key, value){
                		count++;
                		if(jQuery(this).attr("checked")){
                    		hit++;
                   		}
                	});
                    if(count==hit){
                    	jQuery(this).vm2front("startVmLoading");
                    	var form = jQuery("#checkoutFormSubmit");
                    	//document.checkoutForm.task = "checkout";
            			document.checkoutForm.submit();
                    }
                });
            });';
        vmJsApi::addJScript('autocheck', $j);
    }

    protected function loadView($layoutName) {
        $layoutName = trim(strval($layoutName));
        $path = JPath::clean(dirname(__FILE__) . '/tmpl/' . $layoutName . '.php');
        if (!file_exists($path) || !is_file($path)) {
            JFactory::getApplication()->enqueueMessage('Layout file ' . $path . ' not found.', 'error');
            return '';
        }

        ob_start();
        require_once($path);
        $layout = ob_get_contents();
        ob_end_clean();

        return $layout;
    }

    public function checkSpam() {
        $ip = $this->get_client_ip();
        $db = JFactory::getDbo();
        $date_ = date("F j, Y");
        $date_ = strtotime($date_);
        $query = $db->getQuery(true);
        $query->select("p.*");
        $query->from($db->quoteName('#__block_spam') . 'AS p');
        $query->select("o.virtuemart_order_id,o.order_number,o.order_status");
        $query->join("LEFT", "#__virtuemart_orders AS o ON (p.order_id = o.virtuemart_order_id) ");
        $query->where('o.order_status = ' . $db->quote('P'));
        $query->where('p.ip = ' . $db->quote($ip));
        $query->where('p.date > ' . $date_);
        $query->where('p.date <= ' . time());
        $db->setQuery($query);
        $data = $db->loadObjectList();
        return $data;
    }

    public function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

}
