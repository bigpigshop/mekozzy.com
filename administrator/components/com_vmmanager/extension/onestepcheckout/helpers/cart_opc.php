<?php
/** ------------------------------------------------------------------------
One Page Checkout
author CMSMart Team
copyright: Copyright (c) 2012 http://cmsmart.net. All Rights Reserved.
@license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
Websites: http://cmsmart.net
Email: team@cmsmart.net
Technical Support: Forum - http://cmsmart.net/forum
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
defined('BS') or define('BS',DIRECTORY_SEPARATOR);

class OpcCartHelper{
    protected $cart;
    function __construct(){
        if (!class_exists('VirtueMartCart'))
            require_once JPATH_SITE.'/components/com_virtuemart/helpers/cart.php';
        if (!class_exists( 'VmConfig' )) require_once(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
        VmConfig::loadConfig();
        VmConfig::loadJLang('com_virtuemart', true);
        $this->cart = VirtueMartCart::getCart();
       
        $this->cart->prepareCartData();
        JFactory::getLanguage()->load('com_virtuemart');
        JFactory::getLanguage()->load('plg_system_onestepcheckout','plugins/system/onestepcheckout');
        $this->cart->STsameAsBT = 1;
    }
    /** Set Shipment ------------------------------------------------------------------------------------------ */
    public function setShipmentMethod($force = false)
	{
		$virtuemart_shipmentmethod_id = JFactory::getApplication()->input->getInt('virtuemart_shipmentmethod_id', $this->cart->virtuemart_shipmentmethod_id);
        $msg = true;
		if(($this->cart->virtuemart_shipmentmethod_id != $virtuemart_shipmentmethod_id) || $force)
		{
			$this->cart->_dataValidated = false;
			$this->cart->virtuemart_shipmentmethod_id = $virtuemart_shipmentmethod_id;
			JPluginHelper::importPlugin('vmshipment');
			//Add a hook here for other payment methods, checking the data of the choosed plugin
			$_dispatcher = JDispatcher::getInstance();
			$_retValues = $_dispatcher->trigger('plgVmOnSelectCheckShipment', array(&$this->cart));

			foreach ($_retValues as $_retVal)
			{
				if ($_retVal === true )
				{
					$msg = true;
				}
				elseif ($_retVal === false)
				{
					$msg = false;
				}
			}
			$this->cart->setCartIntoSession();
		}
		return $msg;
	}
    /** Set Payment ------------------------------------------------------------------------------------------ */
    public function setPaymentMethod($force=false)
        {
        $virtuemart_paymentmethod_id = JFactory::getApplication()->input->getInt('virtuemart_paymentmethod_id', $this->cart->virtuemart_paymentmethod_id);
		$msg = '';
		if($this->cart->virtuemart_paymentmethod_id != $virtuemart_paymentmethod_id or $force){
			$this->cart->_dataValidated = false;
			$this->cart->virtuemart_paymentmethod_id = $virtuemart_paymentmethod_id;
			if(!class_exists('vmPSPlugin')) require(JPATH_VM_PLUGINS.BS.'vmpsplugin.php');
			JPluginHelper::importPlugin('vmpayment');
			$_dispatcher = JDispatcher::getInstance();
			$_retValues = $_dispatcher->trigger('plgVmOnSelectCheckPayment', array( $this->cart, &$msg));

			foreach ($_retValues as $_retVal) {
				if ($_retVal === true ) {
					$this->cart->setCartIntoSession();
					break;
				}
			}
			$this->cart->setCartIntoSession();
		}
        return $msg;
	}
    /** Set Address ------------------------------------------------------------------------------------------ */
    public function saveBillTo() {
        $data = vRequest::getPost();
        $data['address_type'] = 'BT';
        
        $currentUser = JFactory::getUser();        
        $userModel = VmModel::getModel('user');
        $this->cart->saveAddressInCart($data, 'BT', true);      
        
        if (!empty($this->cart->vendorId) and $this->cart->vendorId != 1) {
            $data['vendorId'] = $this->cart->vendorId;
        }  
        if ($currentUser->guest != 1) {
            $ret = $userModel->store($data);
        }
        // save shipto if is same Bill To
        if ($this->cart->STsameAsBT){
            $data['address_type'] = 'ST'; 
        
            $userModel = VmModel::getModel('user');     
            $currentUser = JFactory::getUser();
            if(isset($currentUser->id)){
                $billTo['virtuemart_userinfo_id'] = $currentUser->id; 
                $shipTo['virtuemart_userinfo_id'] = $currentUser->id; 
            }        
            $userModel->storeAddress($data);
            $this->cart->saveAddressInCart($data, 'ST', true);
        }
        $this->cart->setCartIntoSession(true,true);

    }

    public function saveShipTo(){    
        $data = vRequest::getPost();
        $data['address_type'] = 'ST'; 
        
        $userModel = VmModel::getModel('user');     
        $currentUser = JFactory::getUser();
        if(isset($currentUser->id)){
            $billTo['virtuemart_userinfo_id'] = $currentUser->id; 
            $shipTo['virtuemart_userinfo_id'] = $currentUser->id; 
        }        
        $userModel->storeAddress($data);
        $this->cart->saveAddressInCart($data, 'ST', true,'shipto_');
        $this->cart->setCartIntoSession(true);
    }
    public function useShipto(){
        $data = vRequest::getPost();
        $this->cart->_fromCart = true;
        $useShipto = $data['use_shipto'];
        if($useShipto=='1'){
            $this->cart->STsameAsBT = 1;
            $this->cart->selected_shipto=0;
            $this->cart->ST=0;
        }else{
            $this->cart->STsameAsBT = 0;
            $this->cart->selected_shipto=2;
        }
        
        $currentUser = JFactory::getUser();       
        if (!$currentUser->guest) {   
            if (!empty($this->cart->selected_shipto)) {
                $userModel = VmModel::getModel('user');
                $stData = $userModel->getUserAddressList($currentUser->id, 'ST', $this->cart->selected_shipto);
                if (isset($stData[0]) and is_object($stData[0])) {
                    $stData = get_object_vars($stData[0]);
                    $this->cart->ST = $stData;
                    $this->cart->STsameAsBT = 0;
                } else {
                    $this->cart->selected_shipto = 0;
                }
            }

            if (empty($this->cart->selected_shipto)) {
                $this->cart->STsameAsBT = 1;
                $this->cart->selected_shipto = 0;
                //$cart->ST = $cart->BT;
            }
        } else {
            $this->cart->selected_shipto = 0;
            if (!empty($this->cart->STsameAsBT)) {
            }
        }        
    // $this->cart->prepareCartData();   
        
        
        $this->cart->setCartIntoSession(true);

    }
    public function setAddress(){
                //save Bill To - Ship To
        $data = vRequest::getPost();
        $billTo = !empty($data['billto']) ? $data['billto']: 0;
        $shipTo = !empty($data['shipto']) ? $data['shipto']: 0;        
        $userModel = VmModel::getModel('user');     
        $currentUser = JFactory::getUser(); 
        $this->cart->_fromCart = true;
        $this->cart->_dataValidated = $this->cart->getCartHash();
        //check minimum purchase order value for your shop
        $check_vendor_min_pov =  $this->checkPurchaseValue();
        $return['error']="";
       if($check_vendor_min_pov!=NULL){
           $return['error'] = $check_vendor_min_pov;
           echo json_encode($return);
           die();
       }

        if($shipTo && $billTo){
            $billTo['agreed']=1;
            $shipTo['agreed']=1;
            $this->cart->STsameAsBT = 0;            
            if(isset($currentUser->id)){
                $billTo['virtuemart_userinfo_id'] = $currentUser->id; 
                $shipTo['virtuemart_userinfo_id'] = $currentUser->id; 
             }
            //save shipto
            $shipTo['address_type'] = 'ST'; 
            $userModel->storeAddress($shipTo);
            $this->cart->saveAddressInCart($shipTo, 'ST', true,'shipto_');
            
            //save billto
            $billTo['address_type'] = 'BT';            
            $userModel->storeAddress($billTo);
            $this->cart->saveAddressInCart($billTo, 'BT', true);  
        }
        else{
            $billTo['address_type'] = 'BT';
            $userModel->storeAddress($billTo);
            $this->cart->saveAddressInCart($billTo, 'BT', true); 
            
                if (!empty($this->cart->vendorId) and $this->cart->vendorId != 1) {
                        $data['vendorId'] = $this->cart->vendorId;
                }        
                if ($currentUser->guest != 1) {
                        $ret = $userModel->store($data);
                }
        }
        echo json_encode($return);
       die();

    }
    
    public function updateProduct() {
        $quantities = vRequest::getInt('quantity');
		if(empty($quantities)) return 'false';
		$updated = 'false';
		foreach($quantities as $key=>$quantity){
			if (isset($this->cart->cartProductsData[$key]) and !empty($quantity)) {
				if($quantity==$this->cart->cartProductsData[$key]['quantity']){
					$this->cart->cartProductsData[$key]['quantity'] = $quantity;
					$updated = 'true';
				}
			}
		}
		$this->cart->setCartIntoSession(true);
		return $updated;
	}
    public function deleteProductCart() {
        $pid = vRequest::getInt('pid');
        if(empty($this->cart->cartProductsData[$pid])) return 'false';
        unset($this->cart->cartProductsData[$pid]);
        $this->cart->setCartIntoSession(true);
        return 'true';

    }
    public function saveCouponCode(){
        $coupon_code = vRequest::getCmd('coupon');
		if (!class_exists('CouponHelper')) {
			require(VMPATH_SITE . BS . 'helpers' . BS . 'coupon.php');
		}
		$this->cart->getCartPrices(true);
		if(!in_array($coupon_code,$this->cart->_triesValidateCoupon)){
			$this->cart->_triesValidateCoupon[] = $coupon_code;
		}

		if(count($this->cart->_triesValidateCoupon)<8){
			vmdebug('setCouponCode',$coupon_code, $this->cart->cartPrices['salesPrice']);
			$msg = CouponHelper::ValidateCouponCode($coupon_code, $this->cart->cartPrices['salesPrice']);;
		} else{
			$msg = vmText::_('COM_VIRTUEMART_CART_COUPON_TOO_MANY_TRIES');
		}

		if (!empty($msg)) {
			$this->cart->_dataValidated = false;
			$this->cart->_blockConfirm = true;
			$this->cart->getCartPrices(true);
			$this->cart->setCartIntoSession();
			return $msg;
		}
		$this->cart->couponCode = $coupon_code;
		$this->cart->setCartIntoSession(true);
		return 'true';

    }
    public function registerF(){
        $data = vRequest::getPost();
        $userModel = VmModel::getModel('user');

        $data['address_type'] = 'BT';
        $ret = $userModel->store($data);
        if (!$ret){
            $error = JFactory::getApplication()->getMessageQueue();
            if (count($error)>0){
                return $error[0];
            }
        }
        $current = JFactory::getUser($ret['newId']);
        if($current->id){
            return 'true';
        }else{
            return 'false';
        }
    }
    private function checkPurchaseValue() {
        $vendor = VmModel::getModel('vendor');
        $get_vendor_info = $vendor->getVendor();
        $vendor_min_pov = $get_vendor_info->vendor_min_pov;

        if ($vendor_min_pov > 0) {
            $cart_price = $this->cart->getCartPrices();
            if ($cart_price['salesPrice'] < $vendor_min_pov) {
                if (!class_exists('CurrencyDisplay'))
                    require(VMPATH_ADMIN . BS . 'helpers' . BS . 'currencydisplay.php');
                $currency = CurrencyDisplay::getInstance();
                return vmText::sprintf('COM_VIRTUEMART_CART_MIN_PURCHASE', $currency->priceDisplay($vendor_min_pov));                
            }
        }
        return null;
    }
    public function loadDelivery() {
       
        $data = vRequest::getPost();
        if (file_exists(JPATH_PLUGINS.BS.'system'.BS.'delivery_date'.BS.'helper.php'))
        {
            include_once JPATH_PLUGINS.BS.'system'.BS.'delivery_date'.BS.'helper.php';
        }
        
        $country= $data['virtuemart_country_id'];
        $state= $data['virtuemart_state_id'];
        $zip= $data['zip'];
        if($country && $state){
            $load = plgDeliverydateHelp::getOptionCity($country,$state,$zip);
        }
        
//echo __FILE__;
//echo '<pre>';
//    print_r($load);
//echo '</pre>';
//die; 

    }

}








