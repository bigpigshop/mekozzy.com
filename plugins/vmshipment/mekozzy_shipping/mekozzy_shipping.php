<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Authentication.cookie
 *
 * @copyright   Copyright (C) 2020 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport( 'joomla.plugin.plugin' );

if (!class_exists('vmPSPlugin')) {
	require(VMPATH_PLUGINLIBS . DS . 'vmpsplugin.php');
}
/**
 * Plugins Mekozzy shipping by phuc.pham
 *
 * @since  1.0
 */
class plgVmshipmentMekozzy_shipping extends vmPSPlugin
{
	const PRODUCTION_URL = "https://s.boxme.asia/";
	const LOCALE = "vi_VN";
	const COUNTRY_CODE = "VN";
	const CURRENCY_CODE = "VND";

	private $productionurl ='';
	private $authorization ='';
	
	function __construct (& $subject, $config)
	{

		parent::__construct ($subject, $config);

		$this->authorization = $this->params->get('authorization');
		$this->productionurl = $this->params->get('productionurl');
	}
	public function index()
	{
		echo "1234";
		
		die('yyyy');
	}
		
	public function onAjaxGetFee()
	{
		echo "1234";
		
		die('yyyy');
		


		
		$data = new stdClass();
		$data->ship_from->country= "VN";
		$data->ship_from->pickup_id= 123274;
		
		$data->ship_to->contact_name= "Bao Ngoc";
		$data->ship_to->address= "32/1 P Dong Lan Ba Diem Hoc Mon";
		$data->ship_to->phone= "0938788091";
		$data->ship_to->country= "VN";
		$data->ship_to->province= 79;
		$data->ship_to->district= 766;
		
		$data->shipments->content= "shipping Cod";
		$data->shipments->total_parcel= 1;
		$data->shipments->total_amount= 1;
		$data->shipments->chargeable_weight= "3800";
		
		$data->config->order_type= "normal";
		$data->config->document= "Y";
		$data->config->currency= "VND";
		
		
		$data->payment->fee_paid_by= "receiver";
		$data->payment->tax_paid_by= "receiver";
		$data->payment->cod_amount= 460000.00;
		
		$body = json_encode($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://s.boxme.asia/api/v1/courier/pricing/calculate");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Token 424dce789c54156334f53818d2c86dc4daf6d9089672a5315ba2ad2a4e33ad88',
		));
		$output = curl_exec($ch);
		$obj = $output;
		echo $obj;die('xxxx');
		
		curl_close($ch);
	}
	
	public function getVmPluginCreateTableSQL () {
		
		return $this->createTableSQL ('Shipment Weight Countries Table');
	}
	
	function getTableSQLFields () {
		
		$SQLfields = array(
			'id'                           => 'int(1) UNSIGNED NOT NULL AUTO_INCREMENT',
			'virtuemart_order_id'          => 'int(11) UNSIGNED',
			'order_number'                 => 'char(32)',
			'virtuemart_shipmentmethod_id' => 'mediumint(1) UNSIGNED',
			'shipment_name'                => 'varchar(5000)',
			'order_weight'                 => 'decimal(10,4)',
			'shipment_weight_unit'         => 'char(3) DEFAULT \'KG\'',
			'shipment_cost'                => 'decimal(10,2)',
			'shipment_package_fee'         => 'decimal(10,2)',
			'tax_id'                       => 'smallint(1)'
		);
		return $SQLfields;
	}
	
	
	public function plgVmOnShowOrderFEShipment ($virtuemart_order_id, $virtuemart_shipmentmethod_id, &$shipment_name) {
		
		$this->onShowOrderFE ($virtuemart_order_id, $virtuemart_shipmentmethod_id, $shipment_name);
	}
	
	
	function plgVmConfirmedOrder (VirtueMartCart $cart, $order) {
		
		if (!($method = $this->getVmPluginMethod ($order['details']['BT']->virtuemart_shipmentmethod_id))) {
			return NULL; // Another method was selected, do nothing
		}
		if (!$this->selectedThisElement ($method->shipment_element)) {
			return FALSE;
		}
		$values['virtuemart_order_id'] = $order['details']['BT']->virtuemart_order_id;
		$values['order_number'] = $order['details']['BT']->order_number;
		$values['virtuemart_shipmentmethod_id'] = $order['details']['BT']->virtuemart_shipmentmethod_id;
		$values['shipment_name'] = $this->renderPluginName ($method);
		$values['order_weight'] = $this->getOrderWeight ($cart, $method->weight_unit);
		$values['shipment_weight_unit'] = $method->weight_unit;
		
		$costs = $this->getCosts($cart,$method,$cart->cartPrices);
		if(!empty($costs)){
			$values['shipment_cost'] = $method->shipment_cost;
			$values['shipment_package_fee'] = $method->package_fee;
		}
		if(empty($values['shipment_cost'])) $values['shipment_cost'] = 0.0;
		if(empty($values['shipment_package_fee'])) $values['shipment_package_fee'] = 0.0;
		
		$values['tax_id'] = $method->tax_id;
		$this->storePSPluginInternalData ($values);
		
		return TRUE;
	}
	
	
	public function plgVmOnShowOrderBEShipment ($virtuemart_order_id, $virtuemart_shipmentmethod_id) {
		
		if (!($this->selectedThisByMethodId ($virtuemart_shipmentmethod_id))) {
			return NULL;
		}
		$html = $this->getOrderShipmentHtml ($virtuemart_order_id);
		return $html;
	}
	
	
	function getOrderShipmentHtml ($virtuemart_order_id) {
		
		$db = JFactory::getDBO ();
		$q = 'SELECT * FROM `' . $this->_tablename . '` '
			. 'WHERE `virtuemart_order_id` = ' . $virtuemart_order_id;
		$db->setQuery ($q);
		if (!($shipinfo = $db->loadObject ())) {
			$msg=vmText::sprintf('VMSHIPMENT_WEIGHT_COUNTRIES_NO_ENTRY_FOUND', $virtuemart_order_id);
			vmWarn ($msg);
			vmDebug($msg, $q . " " . $db->getErrorMsg ());
			return '';
		}
		
		$currency = CurrencyDisplay::getInstance ();
		$tax = ShopFunctions::getTaxByID ($shipinfo->tax_id);
		$taxDisplay = is_array ($tax) ? $tax['calc_value'] . ' ' . $tax['calc_value_mathop'] : $shipinfo->tax_id;
		$taxDisplay = ($taxDisplay == -1) ? vmText::_ ('COM_VIRTUEMART_PRODUCT_TAX_NONE') : $taxDisplay;
		
		$html = '<table class="adminlist table">' . "\n";
		$html .= $this->getHtmlHeaderBE ();
		$html .= $this->getHtmlRowBE ('WEIGHT_COUNTRIES_SHIPPING_NAME', $shipinfo->shipment_name);
		$html .= $this->getHtmlRowBE ('WEIGHT_COUNTRIES_WEIGHT', $shipinfo->order_weight . ' ' . ShopFunctions::renderWeightUnit ($shipinfo->shipment_weight_unit));
		$html .= $this->getHtmlRowBE ('WEIGHT_COUNTRIES_COST', $currency->priceDisplay ($shipinfo->shipment_cost));
		$html .= $this->getHtmlRowBE ('WEIGHT_COUNTRIES_PACKAGE_FEE', $currency->priceDisplay ($shipinfo->shipment_package_fee));
		$html .= $this->getHtmlRowBE ('WEIGHT_COUNTRIES_TAX', $taxDisplay);
		$html .= '</table>' . "\n";
		
		return $html;
	}
	
	
	/** @noinspection PhpDeprecationInspection */
	function getCosts (VirtueMartCart $cart, $method, $cart_prices) {
		
		if ($method->free_shipment && $cart_prices['salesPrice'] >= $method->free_shipment) {
			return 0.0;
		} else {
			if(empty($method->shipment_cost)) $method->shipment_cost = 0.0;
			if(empty($method->package_fee)) $method->package_fee = 0.0;
			
			JPluginHelper::importPlugin('redshop_shipping');
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger('plgVmOnSelectCheckPaymentMekozzy', array(&$method));
			
			return $method->shipment_cost + $method->package_fee;
		}
	}
	
	protected function checkConditions ($cart, $method, $cart_prices) {
		
		static $result = array();
		
		if($cart->STsameAsBT == 0){
			$type = ($cart->ST == 0 ) ? 'BT' : 'ST';
		} else {
			$type = 'BT';
		}
		
		$address = $cart -> getST();
		
		if(!is_array($address)) $address = array();
		if(isset($cart_prices['salesPrice'])){
			$hashSalesPrice = $cart_prices['salesPrice'];
		} else {
			$hashSalesPrice = '';
		}
		
		
		if(empty($address['virtuemart_country_id'])) $address['virtuemart_country_id'] = 0;
		if(empty($address['zip'])) $address['zip'] = 0;
		
		$hash = $method->virtuemart_shipmentmethod_id.$type.$address['virtuemart_country_id'].'_'.$address['zip'].'_'.$hashSalesPrice;
		
		if(isset($result[$hash])){
			return $result[$hash];
		}
		
		$this->convert ($method);
		
		if($this->_toConvert){
			$this->convertToVendorCurrency($method);
		}
		
		
		$orderWeight = $this->getOrderWeight ($cart, $method->weight_unit);
		
		$countries = array();
		if (!empty($method->countries)) {
			if (!is_array ($method->countries)) {
				$countries[0] = $method->countries;
			} else {
				$countries = $method->countries;
			}
		}
		
		
		$weight_cond = $this->testRange($orderWeight,$method,'weight_start','weight_stop','weight');
		$nbproducts_cond = $this->_nbproductsCond ($cart, $method);
		
		if(isset($cart_prices['salesPrice'])){
			$orderamount_cond = $this->testRange($cart_prices['salesPrice'],$method,'orderamount_start','orderamount_stop','order amount');
		} else {
			$orderamount_cond = FALSE;
		}
		
		$userFieldsModel =VmModel::getModel('Userfields');
		if ($userFieldsModel->fieldPublished('zip', $type)){
			if (!isset($address['zip'])) {
				$address['zip'] = '';
			}
			$zip_cond = $this->testRange($address['zip'],$method,'zip_start','zip_stop','zip');
		} else {
			$zip_cond = true;
		}
		
		if ($userFieldsModel->fieldPublished('virtuemart_country_id', $type)){
			
			if (!isset($address['virtuemart_country_id'])) {
				$address['virtuemart_country_id'] = 0;
			}
			
			if (in_array ($address['virtuemart_country_id'], $countries) || count ($countries) == 0) {
				
				//vmdebug('checkConditions '.$method->shipment_name.' fit ',$weight_cond,(int)$zip_cond,$nbproducts_cond,$orderamount_cond);
				vmdebug('shipmentmethod '.$method->shipment_name.' = TRUE for variable virtuemart_country_id = '.$address['virtuemart_country_id'].', Reason: Countries in rule '.implode($countries,', ').' or none set');
				$country_cond = true;
			}
			else{
				vmdebug('shipmentmethod '.$method->shipment_name.' = FALSE for variable virtuemart_country_id = '.$address['virtuemart_country_id'].', Reason: Country '.implode($countries,', ').' does not fit');
				$country_cond = false;
			}
		} else {
			vmdebug('shipmentmethod '.$method->shipment_name.' = TRUE for variable virtuemart_country_id, Reason: no boundary conditions set');
			$country_cond = true;
		}
		
		$cat_cond = true;
		if($method->categories or $method->blocking_categories){
			if($method->categories)$cat_cond = false;
			//vmdebug('hmm, my value',$method);
			//if at least one product is  in a certain category, display this shipment
			if(!is_array($method->categories)) $method->categories = array($method->categories);
			if(!is_array($method->blocking_categories)) $method->blocking_categories = array($method->blocking_categories);
			//Gather used cats
			foreach($cart->products as $product){
				if(array_intersect($product->categories,$method->categories)){
					$cat_cond = true;
					//break;
				}
				if(array_intersect($product->categories,$method->blocking_categories)){
					$cat_cond = false;
					break;
				}
			}
			//if all products in a certain category, display the shipment
			//if a product has a certain category, DO NOT display the shipment
		}
		
		$allconditions = (int) $weight_cond + (int)$zip_cond + (int)$nbproducts_cond + (int)$orderamount_cond + (int)$country_cond + (int)$cat_cond;
		if($allconditions === 6){
			$result[$hash] = true;
			return TRUE;
		} else {
			$result[$hash] = false;
			//vmdebug('checkConditions '.$method->shipment_name.' does not fit ',(int)$weight_cond,(int)$zip_cond,(int)$nbproducts_cond,(int)$orderamount_cond,(int)$country_cond);
			return FALSE;
		}
		
		$result[$hash] = false;
		return FALSE;
	}
	
	
	function convert (&$method) {
		
		//$method->weight_start = (float) $method->weight_start;
		//$method->weight_stop = (float) $method->weight_stop;
		$method->orderamount_start =  (float)str_replace(',','.',$method->orderamount_start);
		$method->orderamount_stop =   (float)str_replace(',','.',$method->orderamount_stop);
		$method->zip_start = (int)$method->zip_start;
		$method->zip_stop = (int)$method->zip_stop;
		$method->nbproducts_start = (int)$method->nbproducts_start;
		$method->nbproducts_stop = (int)$method->nbproducts_stop;
		$method->free_shipment = (float)str_replace(',','.',$method->free_shipment);
	}
	
	
	private function _nbproductsCond ($cart, $method) {
		
		if (empty($method->nbproducts_start) and empty($method->nbproducts_stop)) {
			//vmdebug('_nbproductsCond',$method);
			return true;
		}
		
		$nbproducts = 0;
		foreach ($cart->products as $product) {
			$nbproducts += $product->quantity;
		}
		
		if ($nbproducts) {
			
			$nbproducts_cond = $this->testRange($nbproducts,$method,'nbproducts_start','nbproducts_stop','products quantity');
			
		} else {
			$nbproducts_cond = false;
		}
		
		return $nbproducts_cond;
	}
	
	
	private function testRange($value, $method, $floor, $ceiling,$name){
		
		$cond = true;
		if(!empty($method->$floor) and !empty($method->$ceiling)){
			$cond = (($value >= $method->$floor AND $value <= $method->$ceiling));
			if(!$cond){
				$result = 'FALSE';
				$reason = 'is NOT within Range of the condition from '.$method->$floor.' to '.$method->$ceiling;
			} else {
				$result = 'TRUE';
				$reason = 'is within Range of the condition from '.$method->$floor.' to '.$method->$ceiling;
			}
		} else if(!empty($method->$floor)){
			$cond = ($value >= $method->$floor);
			if(!$cond){
				$result = 'FALSE';
				$reason = 'is not at least '.$method->$floor;
			} else {
				$result = 'TRUE';
				$reason = 'is over min limit '.$method->$floor;
			}
		} else if(!empty($method->$ceiling)){
			$cond = ($value <= $method->$ceiling);
			if(!$cond){
				$result = 'FALSE';
				$reason = 'is over '.$method->$ceiling;
			} else {
				$result = 'TRUE';
				$reason = 'is lower than the set '.$method->$ceiling;
			}
		} else {
			$result = 'TRUE';
			$reason = 'no boundary conditions set';
		}
		
		vmdebug('shipmentmethod '.$method->shipment_name.' = '.$result.' for variable '.$name.' = '.$value.' Reason: '.$reason);
		return $cond;
	}
	
	
	function plgVmOnProductDisplayShipment($product, &$productDisplayShipments){
		
		if ($this->getPluginMethods($product->virtuemart_vendor_id) === 0) {
			
			return FALSE;
		}
		
		$html = array();
		
		$currency = CurrencyDisplay::getInstance();
		
		foreach ($this->methods as $this->_currentMethod) {
			
			if($this->_currentMethod->show_on_pdetails){
				
				if(!isset($cart)){
					$cart = VirtueMartCart::getCart();
					$cart->products['virtual'] = $product;
					$cart->_productAdded = true;
					$cart->prepareCartData();
				}
				if($this->checkConditions($cart,$this->_currentMethod,$cart->cartPrices)){
					
					$product->prices['shipmentPrice'] = $this->getCosts($cart,$this->_currentMethod,$cart->cartPrices);
					
					if(isset($product->prices['VatTax']) and count($product->prices['VatTax'])>0){
						reset($product->prices['VatTax']);
						$rule = current($product->prices['VatTax']);
						if(isset($rule[1])){
							$product->prices['shipmentTax'] = $product->prices['shipmentPrice'] * $rule[1]/100.0;
							$product->prices['shipmentPrice'] = $product->prices['shipmentPrice'] * (1 + $rule[1]/100.0);
						}
					}
					
					$html[$this->_currentMethod->virtuemart_shipmentmethod_id] = $this->renderByLayout( 'default', array("method" => $this->_currentMethod, "cart" => $cart,"product" => $product,"currency" => $currency) );
				}
			}
		}
		if(isset($cart)){
			unset($cart->products['virtual']);
			$cart->_productAdded = true;
			$cart->prepareCartData();
		}
		
		
		$productDisplayShipments[] = $html;
		
	}
	
	
	function plgVmOnStoreInstallShipmentPluginTable ($jplugin_id) {
		
		return $this->onStoreInstallPluginTable ($jplugin_id);
	}
	
	
	public function plgVmOnSelectCheckShipment (VirtueMartCart &$cart) {
		
		return $this->OnSelectCheck ($cart);
	}
	
	
	public function plgVmDisplayListFEShipment (VirtueMartCart $cart, $selected = 0, &$htmlIn) {
		
		return $this->displayListFE ($cart, $selected, $htmlIn);
	}
	
	
	public function plgVmOnSelectedCalculatePriceShipment (VirtueMartCart $cart, array &$cart_prices, &$cart_prices_name) {
		
		return $this->onSelectedCalculatePrice ($cart, $cart_prices, $cart_prices_name);
	}
	
	
	function plgVmOnCheckAutomaticSelectedShipment (VirtueMartCart $cart, array $cart_prices, &$shipCounter) {
		
		return $this->onCheckAutomaticSelected ($cart, $cart_prices, $shipCounter);
	}
	
	function plgVmOnCheckoutCheckDataShipment(VirtueMartCart $cart){
		
		if(empty($cart->virtuemart_shipmentmethod_id)) return false;
		
		$virtuemart_vendor_id = 1; //At the moment one, could make sense to use the cart vendor id
		if ($this->getPluginMethods($virtuemart_vendor_id) === 0) {
			return NULL;
		}
		
		foreach ($this->methods as $this->_currentMethod) {
			if($cart->virtuemart_shipmentmethod_id == $this->_currentMethod->virtuemart_shipmentmethod_id){
				if(!$this->checkConditions($cart,$this->_currentMethod,$cart->cartPrices)){
					return false;
				}
				break;
			}
		}
	}
	
	
	function plgVmonShowOrderPrint ($order_number, $method_id) {
		return $this->onShowOrderPrint ($order_number, $method_id);
	}
	
	function plgVmDeclarePluginParamsShipment ($name, $id, &$dataOld) {
		return $this->declarePluginParams ('shipment', $name, $id, $dataOld);
	}
	
	function plgVmDeclarePluginParamsShipmentVM3 (&$data) {
		return $this->declarePluginParams ('shipment', $data);
	}
	
	function plgVmSetOnTablePluginShipment(&$data,&$table){
		
		$name = $data['shipment_element'];
		$id = $data['shipment_jplugin_id'];
		
		if (!empty($this->_psType) and !$this->selectedThis ($this->_psType, $name, $id)) {
			return FALSE;
		} else {
			$tCon = array('weight_start','weight_stop','orderamount_start','orderamount_stop','shipment_cost','package_fee');
			foreach($tCon as $f){
				if(!empty($data[$f])){
					$data[$f] = str_replace(array(',',' '),array('.',''),$data[$f]);
				}
			}
			
			$data['nbproducts_start'] = (int) $data['nbproducts_start'];
			$data['nbproducts_stop'] = (int) $data['nbproducts_stop'];
			
			//Reasonable tests:
			if(!empty($data['zip_start']) and !empty($data['zip_stop']) and (int)$data['zip_start']>=(int)$data['zip_stop']){
				vmWarn('VMSHIPMENT_WEIGHT_COUNTRIES_ZIP_CONDITION_WRONG');
			}
			if(!empty($data['weight_start']) and !empty($data['weight_stop']) and (float)$data['weight_start']>=(float)$data['weight_stop']){
				vmWarn('VMSHIPMENT_WEIGHT_COUNTRIES_WEIGHT_CONDITION_WRONG');
			}
			
			if(!empty($data['orderamount_start']) and !empty($data['orderamount_stop']) and (float)$data['orderamount_start']>=(float)$data['orderamount_stop']){
				vmWarn('VMSHIPMENT_WEIGHT_COUNTRIES_AMOUNT_CONDITION_WRONG');
			}
			
			if(!empty($data['nbproducts_start']) and !empty($data['nbproducts_stop']) and (float)$data['nbproducts_start']>=(float)$data['nbproducts_stop']){
				vmWarn('VMSHIPMENT_WEIGHT_COUNTRIES_NBPRODUCTS_CONDITION_WRONG');
			}
			
			//$data['show_on_pdetails'] = (int) $data['show_on_pdetails'];
			return $this->setOnTablePluginParams ($name, $id, $table);
		}
	}
	

}