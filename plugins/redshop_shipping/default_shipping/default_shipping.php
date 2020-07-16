<?php
	/**
	 * @version        $Id: log.php 14401 2010-01-26 14:10:00Z louis $
	 * @package        Joomla
	 * @copyright    Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
	 * @license        GNU/GPL, see LICENSE.php
	 * Joomla! is free software. This version may have been modified pursuant
	 * to the GNU General Public License, and as distributed it includes or
	 * is derivative of works licensed under the GNU General Public License or
	 * other free or open source software licenses.
	 * See COPYRIGHT.php for copyright notices and details.
	 */

// no direct access
	defined('_JEXEC') or die('Restricted access');
	
	jimport('joomla.plugin.plugin');
	
	/**
	 * Joomla! System Logging Plugin
	 *
	 * @package        Joomla
	 * @subpackage    System
	 */
	class  plgredshop_shippingdefault_shipping extends JPlugin
	{
		public $total_fee = 0;
		public $chargeable_weight = 0;
		public $country = "VN";
		public $province = "";
		public $contact_name = "";
		public $address = "";
		public $phone = "";
		public $ward = "";
		
		public function onAjaxGetData()
		{
			$tem['country'] = $_POST['country'];
			$tem['province'] = $_POST['province'];
			$tem['ward'] = $_POST['ward'];
			$tem['contact_name'] = $_POST['first_name'];
			$tem['address'] = $_POST['address'];
			$tem['phone'] = $_POST['phone'];
			$tem['infordelivery'] = $_POST['infordelivery'];
			
			if (!class_exists('VmConfig')) {
				require_once(JPATH_SITE . '/administrator/components/com_virtuemart/helpers/config.php');
				VmConfig::loadConfig();
			}
			if (!class_exists('VirtueMartCart')) {
				require_once(JPATH_SITE . '/components/com_virtuemart/helpers/cart.php');
			}
			$cart = VirtueMartCart::getCart();
			$data = $cart->prepareAjaxData();
			
			$GLOBALS["orderboxme"] = $tem;
			$cart->orderboxme = $tem;
			
			$session = JFactory::getSession();
			$session->set('orderboxme', $tem);

		}
		
		public function plgCreateOrderBoxme(&$viewData)
		{
			if(empty($viewData['order_number']))
				return;

			$order_number = $viewData['order_number'];

			$this->onAjaxGetCreate($order_number);
		}
		
		public function onAjaxGetCreate($order_number)
		{
			$chargeable_weight = "1";
			
			if (!class_exists('VmConfig')) {
				require_once(JPATH_SITE . '/administrator/components/com_virtuemart/helpers/config.php');
				VmConfig::loadConfig();
			}
			if (!class_exists('VirtueMartCart')) {
				require_once(JPATH_SITE . '/components/com_virtuemart/helpers/cart.php');
			}
			$cart = VirtueMartCart::getCart();
			$data = $cart->prepareAjaxData();
			
			$parcels = array();
			$items = array();
			$i=0;
			foreach ($cart->products as $product) {
				$temp = new stdClass();
				$chargeable_weight += $product->product_weight;
				
				$temp->sku = $product->product_sku;
				$temp->label_code = "";
				$temp->origin_country = "VN";
				$temp->name = $product->product_name;
				$temp->desciption = "";
				$temp->weight = $product->product_weight;
				$temp->amount = $product->quantity;
				$temp->quantity = $product->quantity;
				
				$items [] = $temp;
				$i++;
			}
			
			$session = JFactory::getSession();
			$boxme =  $session->get('orderboxme');
			
			$parcels['weight'] = $chargeable_weight == 0 ? 1 : $chargeable_weight;
			$parcels['description'] = "";
			$parcels['items'] = $items;
			
			$pickup_id = 148703;
			// $pickup_id = 37529;
			
			$data = new stdClass();
			$data->ship_from->country = $boxme['country'];
			$data->ship_from->pickup_id = $pickup_id;
			
			$data->ship_to->contact_name = $boxme['contact_name'];
			$data->ship_to->address = $boxme['address'];
			$data->ship_to->phone = $boxme['phone'];
			$data->ship_to->country = "VN";
			$data->ship_to->province = $boxme['province'];
			$data->ship_to->district = $boxme['ward'];
			
			$data->shipments->content = "shipping Cod";
			$data->shipments->total_parcel = count($parcels);
			$data->shipments->total_amount = $i;
			$data->shipments->chargeable_weight = $chargeable_weight;
			$data->shipments->description = "";
			$data->shipments->parcels = $parcels;
			
			$data->config->order_type = "normal";
			$data->config->delivery_service = $boxme['infordelivery'];
			$data->config->document = "Y";
			$data->config->currency = "VND";
			
			$data->payment->fee_paid_by = "receiver";
			$data->payment->cod_amount = $cart->cartPrices['billTotal'];

			$data->referral->order_number = $order_number;
			
			$body = json_encode($data);
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_URL, "https://s.boxme.asia/api/v1/courier/pricing/create_order");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization: Token 424dce789c54156334f53818d2c86dc4daf6d9089672a5315ba2ad2a4e33ad88',
			));
			$output = curl_exec($ch);
			
			curl_close($ch);
			
			echo $output;
		}
		
		public function onAjaxGetFee()
		{
			$country = !empty($_POST['country']) ? $_POST['country'] : 0;
			$province = !empty($_POST['province']) ? $_POST['province'] : 0;
			$ward = !empty($_POST['ward']) ? $_POST['ward'] : 0;
			$contact_name = !empty($_POST['first_name']) ? $_POST['first_name'] : '';
			$address = !empty($_POST['address']) ? $_POST['address'] : '';
			$email = !empty($_POST['email']) ? $_POST['email'] : '';
			$phone = !empty($_POST['phone']) ? $_POST['phone'] : '';
			$chargeable_weight = "1";
			
			if (!class_exists('VmConfig')) {
				require_once(JPATH_SITE . '/administrator/components/com_virtuemart/helpers/config.php');
				VmConfig::loadConfig();
			}
			if (!class_exists('VirtueMartCart')) {
				require_once(JPATH_SITE . '/components/com_virtuemart/helpers/cart.php');
			}
			$cart = VirtueMartCart::getCart(false);
			$data = $cart->prepareAjaxData();
			
			foreach ($cart->products as $product) {
				$chargeable_weight += $product->product_weight;
			}
			
			$pickup_id = 148703;
			
			$data = new stdClass();
			$data->ship_from->country = $country;
			$data->ship_from->pickup_id = $pickup_id;
			
			$data->ship_to->contact_name = $contact_name;
			$data->ship_to->address = $address;
			$data->ship_to->phone = $phone;
			$data->ship_to->country = "VN";
			$data->ship_to->province = $province;
			$data->ship_to->district = $ward;
			
			$data->shipments->content = "shipping Cod";
			$data->shipments->total_parcel = 1;
			$data->shipments->total_amount = 1;
			$data->shipments->chargeable_weight = $chargeable_weight;
			
			$data->config->order_type = "normal";
			$data->config->document = "Y";
			$data->config->currency = "VND";
			
			$data->payment->fee_paid_by = "receiver";
			$data->payment->tax_paid_by = "receiver";
			$data->payment->cod_amount = $cart->cartPrices['billTotal'];
			
			$body = json_encode($data);
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_URL, "http://s.boxme.asia/api/v1/courier/pricing/calculate");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization: Token 424dce789c54156334f53818d2c86dc4daf6d9089672a5315ba2ad2a4e33ad88',
			));
			$output = curl_exec($ch);
			
			curl_close($ch);
			
			echo $output;
		}
		
		public function onAjaxUpdateCart()
		{
			$this->service_id = $_POST['service_id'];
			$this->service_name = $_POST['service_name'];
			$this->courier_name = $_POST['courier_name'];
			$this->total_fee = $_POST['total_fee'];
			
			
			if (!class_exists('VmConfig')) {
				require_once(JPATH_SITE . '/administrator/components/com_virtuemart/helpers/config.php');
				VmConfig::loadConfig();
			}
			if (!class_exists('VirtueMartCart')) {
				require_once(JPATH_SITE . '/components/com_virtuemart/helpers/cart.php');
			}
			$cart = VirtueMartCart::getCart(false);
			$data = $cart->prepareAjaxData();
			
			echo json_encode($data);
			
		}
		
		protected function loadView($layoutName)
		{
			$layoutName = trim(strval($layoutName));
			$path = JPath::clean('plugins/system/onestepcheckout/views/tmpl/' . $layoutName . '.php');
			
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
		
		public function plgVmOnSelectCheckPaymentMekozzy(&$method)
		{
			$method->shipment_cost = $this->total_fee;
		}
	}

?>