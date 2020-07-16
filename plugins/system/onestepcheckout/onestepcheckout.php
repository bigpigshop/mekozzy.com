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
jimport('joomla.plugin.plugin');
jimport( 'joomla.filesystem.file' );
jimport( 'joomla.filesystem.folder' );

class plgSystemOneStepCheckout extends JPlugin {
    function __construct($config, $params) {
        if (!JFile::exists(JPATH_ADMINISTRATOR.'/components/com_vmmanager/helpers/opc.json')){
            // copy default config file if don't exists
            $helperFolder = JPATH_ADMINISTRATOR.'/components/com_vmmanager/helpers';
            JFile::move($helperFolder. '/opc.json.org', $helperFolder. '/opc.json');
            $files = JFolder::files($helperFolder . '/config');
            foreach ($files as $file) {
                $ext = JFile::getExt($file);
                if ($ext == 'org'){                    
                    JFile::move($helperFolder. '/config/'. $file,$helperFolder. '/config/'.  str_replace('.org', '', $file));
                }                
            }
        }
        // for PHP < 5.5
        if (!function_exists('boolval')) {
            function boolval($val) {
                return (bool) $val;
            }
        }
        if (JFactory::getApplication()->isAdmin()) {
            return;
        }
        parent::__construct($config, $params);
        $vmlang = JFactory::getLanguage();
        $vmlang->load('plg_system_onestepcheckout', 'plugins/system/onestepcheckout');
        $vmlang->load('com_virtuemart', JPATH_SITE . '/components/com_virtuemart');
    }

    public function onAfterDispatch() {
        $view = JFactory::getApplication()->input->getString('view');
       
        // exit if in backend        
        if (JFactory::getApplication()->isAdmin()) {
            return;
        }
        $data_opc = file_get_contents(JPATH_ADMINISTRATOR . '/components/com_vmmanager/helpers/opc.json');
        $data_opc = (array) json_decode($data_opc);
        $document = JFactory::getDocument();
        $document->addStyleSheet('components/com_vmmanager/assets/icons/css/opc-fonts.css');
        $document->addStyleSheet('//fonts.googleapis.com/css?family=Lato:400,300italic,900,900italic,400italic,300,700,700italic,100italic,100|Open+Sans:400,300,300italic,400italic,700,700italic,600italic,600,800italic,800|Oswald:400,700,300|Raleway:400,100,200,300,500,600,800,700,900|Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic|PT+Sans:400,400italic,700,700italic');
        $document->addStyleSheet('plugins/system/onestepcheckout/assets/css/jquery-ui.min.css');
        if ($data_opc['loadCss']) {
            $document->addStyleSheet('plugins/system/onestepcheckout/assets/css/fontend.min.css');
        }
        if ($data_opc['loadJs']) {
            $document->addScript('plugins/system/onestepcheckout/assets/js/jquery.min.js');
            $document->addScript('media/jui/js/jquery-migrate.min.js');
        }
        if ($data_opc['loadJsUI']) {
            $document->addScript('plugins/system/onestepcheckout/assets/js/jquery-ui.min.js');
        }
        if ($data_opc['geolocal']) {
            $geolocalcity = '';            
            $ip = $this->get_client_ip();
            $geolocal = $this->getMap($ip);
            if($geolocal){
                if (isset($data_opc['geolocalcity']) && $data_opc['geolocalcity']){
                    $geolocalcity = $geolocal->city;
                }
                $js_geo = <<<ENDJS
//<![CDATA[
var opc_country_code = "$geolocal->country_code";        
var opc_country_id = "$geolocal->country_id";        
var opc_city = "$geolocalcity";        
//]]>
ENDJS;
$document->addScriptDeclaration("$js_geo");                
            }

        }
        
        $document->addScript('components/com_vmmanager/assets/js/jquery.ui.touch-punch.min.js');
        $ajax = $data_opc['loadAjaxIcon'];
        $js = <<<ENDJS
//<![CDATA[
var showajax = $ajax;               
//]]>
ENDJS;
        $fixconflict_juis = json_decode($data_opc['opc-design']);
        $document->addScriptDeclaration("$js");
        foreach ($fixconflict_juis as $fixconflict_jui) {
            if ($fixconflict_jui->element == 'delivery') {
                $document->addScript('plugins/system/onestepcheckout/assets/js/jquery.cookies.2.2.0.min.js');
                $document->addScript('plugins/system/onestepcheckout/assets/js/jquery-ui-timepicker-addon.min.js');
            }
        }
        $document->addScript('plugins/system/onestepcheckout/assets/js/onestepcheckout.min.js');
    }

    function onAfterRoute() {
        //Var
        $app = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getString('option');
        $view = JFactory::getApplication()->input->getString('view');
        $format = JFactory::getApplication()->input->getString('format');
        $task = JFactory::getApplication()->input->getString('task');

        $uri = JFactory::getURI();
        $document = JFactory::getDocument();
        //Check admin
        if ($app->isAdmin()) {
            return;
        }
        if ($option != 'com_virtuemart')
            return;
        $template = $app->getTemplate(true);
        if (!class_exists('VmConfig')) {
            require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
        }
        VmConfig::loadConfig();

        if (($view == 'cart' && $format != 'json') || ($view == 'pluginresponse' && $task == 'pluginUserPaymentCancel') || ($view == 'vmplg' && $task == 'pluginUserPaymentCancel')) {
            if ($uri->isSSL() == false && VmConfig::get('useSSL', 0)) {
                $uri->setScheme('https');
                $app->redirect($uri->toString());
                return $app->close();
            }
            /**
             * ----- Fix bug checkoutcart (VM 3.0.9.6) -----------
             */
            if (!class_exists('vmVersion'))
                require_once JPATH_ADMINISTRATOR . '/components/com_virtuemart/version.php';
            if (!class_exists('VirtueMartCart'))
                require_once JPATH_SITE . '/components/com_virtuemart/helpers/cart.php';
            $version = vmVersion::$RELEASE;

            $request = vRequest::getRequest();
            $task = vRequest::getCmd('task');
            if (($task == 'confirm' or isset($request['confirm']))) {
                $cart = VirtueMartCart::getCart();
                $cart->prepareCartData(true);
                $html = true;
                $force = 1;
                if ($cart->virtuemart_shipmentmethod_id == 0 and ( ($s_id = VmConfig::get('set_automatic_shipment', false)) > 0)) {
                    vRequest::setVar('virtuemart_shipmentmethod_id', $s_id);
                    $cart->setShipmentMethod($force, !$html);
                }
                if ($cart->virtuemart_paymentmethod_id == 0 and ( ($s_id = VmConfig::get('set_automatic_payment', false)) > 0) and $cart->products) {
                    vRequest::setVar('virtuemart_paymentmethod_id', $s_id);
                    $cart->setPaymentMethod($force, !$html);
                }
                if (version_compare($version, '3.0.9.6', '>=')) {
                    $cart->_dataValidated = $cart->getCartHash();
                }
                $cart->setCartIntoSession(true);
                $cart->prepareAjaxData(false);
            }
            /**
             * ----- End Fix bug -----------
             */
            require_once(dirname(__FILE__) . '/views/opc.html.php');
        }
    }

    function plgVmConfirmedOrder(VirtueMartCart $cart, $order) {
        $order_bt = $order['details']['BT'];
        //Delivery
        $configDelivery = file_get_contents(JPATH_ADMINISTRATOR . '/components/com_vmmanager/helpers/config/delivery.json');
        $configDelivery = json_decode($configDelivery);
        $data_opc = file_get_contents(JPATH_ADMINISTRATOR . '/components/com_vmmanager/helpers/opc.json');
        $data_opc = (array) json_decode($data_opc);

        $date = '';
        $cookie = JFactory::getApplication()->input->cookie;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // check advance Delivery running.
        $sess = JFactory::getSession();
        $nb_delivery_ext = $sess->get('nb_delivery_ext');

        if (strpos($data_opc['opc-design'], '"element": "delivery"')!== false && (!$nb_delivery_ext)){
            if (($cookie->getString('delivery_date'))!==null) {
                $dateF = $cookie->getString('delivery_date');
                $dateT = '';
                if ($configDelivery->options->dateType) {
                    $dateT = $cookie->getString('delivery_todate');
                }
                if ($configDelivery->options->deliTime) {
                    $timeF = $cookie->getString('delivery_time');
                    if ($configDelivery->options->timeType) {
                        $timeT .= $cookie->getString('delivery_totime');
                    }
                }
                $date = $dateF;
                if ($configDelivery->options->dateType) {
                    $date .= ' - ' . $dateT;
                }
                if($cookie->getString('option_time')){
                    $date .=' '.$cookie->getString('option_time');
                }elseif ($configDelivery->options->deliTime) {
                    $date .= ' (' . $timeF;
                    if ($configDelivery->options->timeType) {
                        $date .= ' - ' . $timeT;
                    }
                    $date .= ')';
                }
            } else {
                $date = 'Same as invoice date';
            }

            $order_bt->customer_note .= "\n" . $date;
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->update('#__virtuemart_orders');
            $query->set('delivery_date=' . $db->quote($date));
            $query->where('virtuemart_order_id=' . $order_bt->virtuemart_order_id);

            $db->setQuery($query);
            $db->query();

            $sess = JFactory::getSession();
            $sess->set('opc_delivery_date',$date);

            $cookie->set('delivery_date',null,time()-1);
            $cookie->set('option_time',null,time()-1);
            $cookie->set('delivery_todate',null,time()-1);
            $cookie->set('delivery_time',null,time()-1);
            $cookie->set('delivery_totime',null,time()-1);
        }
        
        //Spam
        $ip = $this->get_client_ip();
        $query = $db->getQuery(true);
        $columns = array('ip', 'order_id', 'date');
        $values = array($db->quote($ip), $db->quote($order_bt->virtuemart_order_id), $db->quote(time()));
        $query
                ->insert($db->quoteName('#__block_spam'))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
        $db->setQuery($query);
        $db->execute();
    }

    function plgVmOnUpdateOrderShipment($data,$old_order_status,$inputOrder = ''){
        $sess = JFactory::getSession();
        $nb_delivery_ext = $sess->get('nb_delivery_ext',false);
        $opc_delivery_date = $sess->get('opc_delivery_date');
        if (!empty($opc_delivery_date) && !$nb_delivery_ext){
            $data->delivery_date = $opc_delivery_date;
            $sess->set('opc_delivery_date',null);
        }
        return true;
    }

    //plgVmDisplayInOrderFEVM3
    public function onAjaxOneStepCheckout() {
        $task = JFactory::getApplication()->input->get('task', '');
        switch ($task) {
            case 'login':
                return $this->Opclogin();
            case 'logout':
                return $this->Opclogout();
                break;
        }
    }

    /** -------------------------------------------------------------------------------------------------------------------- */
    public function Opclogin() {
        $app = JFactory::getApplication();
        $data = array();
        $data['username'] = JFactory::getApplication()->input->get('username', '', 'username');
        $data['password'] = JFactory::getApplication()->input->getString('password', '', 'post', JREQUEST_ALLOWRAW);
        $options = array();
        $options['remember'] = JFactory::getApplication()->input->getBool('remember', false);
        $credentials = array();
        $credentials['username'] = $data['username'];
        $credentials['password'] = $data['password'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__users'));
        $query->where($db->quoteName('username') . ' = ' . $db->quote($credentials['username']));
        $query->where($db->quoteName('block') . ' = 0');
        $query->where('(' . $db->quoteName('activation') . ' = 0 OR ' . $db->quoteName('activation') . '= "" )');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        if (count($results) == 0) {
            $mess = 'notactive';
            return $mess;
        }

        if (true === $app->login($credentials, $options)) {
            $app->setUserState('users.login.form.data', array());
            $mess = 'true';
        } else {
            $data['remember'] = (int) $options['remember'];
            $app->setUserState('users.login.form.data', $data);
            $mess = 'false';
        }
        return $mess;
    }

    public function Opclogout() {
        $app = JFactory::getApplication();
        $error = $app->logout();
        if (!($error instanceof Exception)) {
            $return = JFactory::getApplication()->input->get('return', '', 'base64');
            $return = base64_decode($return);
            if (!JURI::isInternal($return)) {
                $return = '';
            }
            $mess = 'true';
        } else {
            $mess = 'false';
        }
        return $mess;
    }

    function get_client_ip() {
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
    function getMap($ip) {
        if (file_exists(JPATH_PLUGINS . BS . 'system' . BS . 'onestepcheckout' . BS . 'lib' . BS . "geoip.inc") ||
                file_exists(JPATH_PLUGINS . BS . 'system' . BS . 'onestepcheckout' . BS . 'lib' . BS . "geoipcity.inc") ||
                file_exists(JPATH_PLUGINS . BS . 'system' . BS . 'onestepcheckout' . BS . 'lib' . BS . "geoipregionvars.php") ||
                file_exists(JPATH_PLUGINS . BS . 'system' . BS . 'onestepcheckout' . BS . 'lib' . BS . "GeoLiteCity.dat") ||
                file_exists(JPATH_PLUGINS . BS . 'system' . BS . 'onestepcheckout' . BS . 'lib' . BS . "GeoLiteCity.dat")) {
            include(JPATH_PLUGINS . BS . 'system' . BS . 'onestepcheckout' . BS . 'lib' . BS . "geoip.inc");
            include(JPATH_PLUGINS . BS . 'system' . BS . 'onestepcheckout' . BS . 'lib' . BS . "geoipcity.inc");
            include(JPATH_PLUGINS . BS . 'system' . BS . 'onestepcheckout' . BS . 'lib' . BS . "geoipregionvars.php");
            $gi = geoip_open(JPATH_PLUGINS . BS . 'system' . BS . 'onestepcheckout' . BS . 'lib' . BS . "GeoLiteCity.dat", GEOIP_STANDARD);
            $rsGeoData = geoip_record_by_addr($gi, $ip);
            if(!$rsGeoData){
                return null;
            }
            $rsGeoData->country_id = $gi->GEOIP_COUNTRY_ID[$rsGeoData->country_code];
            geoip_close($gi);
            if (count($rsGeoData)) {
                return $rsGeoData;
            }
        }
        return null;
    }

}
?>
