<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Symfony\Component\Yaml\Yaml;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\User\UserHelper;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\Image\Image;
use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

JLoader::register('CTVHelperRoute', JPATH_COMPONENT . '/helpers/route.php');
JLoader::register('CTVHelper', JPATH_COMPONENT . '/helpers/helper.php');

if (!class_exists( 'VmConfig' ))
    require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');

VmConfig::loadConfig();
vmLanguage::loadJLang('com_virtuemart', true);
vmLanguage::loadJLang('com_virtuemart_orders', true);
vmLanguage::loadJLang('com_virtuemart_shoppers', true);

/**
 * Search Component Search Model
 *
 * @since  1.5
 */
class CtvModelDashboard extends JModelLegacy
{

    protected $input = null;
    protected $sessionId = null;
    protected $app = null;
    protected $config = null;
    protected $user = null;
    /**
     * Constructor
     *
     * @since 1.5
     */
    public function __construct()
    {
        $this->input = JFactory::getApplication()->input;
        $session = JFactory::getSession();
        $this->sessionId = $session->getId();
        // Get configuration
        $this->app    = JFactory::getApplication();
        $this->config = JFactory::getConfig();
        $this->user = JFactory::getUser();

        parent::__construct();

    }


    public function listVendor()
    {
        $page = $this->input->getInt('page', 1);
        $limit = $this->input->getInt('rows', 10);
        $sidx = $this->input->getString('sidx', 'PatientID');
        $sord = $this->input->getString('sord', 'asc');


        $filters = $this->input->getString('filters', '{}');
        $filters = json_decode($filters);

        $totalrows = $this->input->getInt('totalrows', $limit);

        $isSearch = $this->input->getWord('_search') == 'true' ? true : false;

        $user = JFactory::getUser();

        $asset		= 'com_oongtacvien';
        $authorise['admin']  = $user->authorise('thuong.admin', $asset);
        $authorise['edit']  = $user->authorise('thuong.edit', $asset);
        $authorise['editown']  = $user->authorise('thuong.editown', $asset);
        $authorise['view'] = $user->authorise('thuong.view', $asset);

        $query = $this->_db->getQuery(true);
        $q1 = $this->_db->getQuery(true);

        if ( !( $authorise['admin'] || $authorise['editown'] || $authorise['edit'] || $authorise['view'])  ) {
            $response = new JObject;
            $response->page = 1;
            $response->records = 0;
            $response->total = 0;
//            return $response;
        }

        $query->select("a.virtuemart_vendor_id, IFNULL(c.doituong_code, '') as doituong_code, b.vendor_store_name, e.address_1 as address, e.virtuemart_state_id, f.state_name as province, b.vendor_phone, b.vendor_mail_free1 as email")
            ->select('c.state')
            ->from("#__virtuemart_vendors as a")
            ->leftJoin("#__virtuemart_vendors_vi_vn as b ON b.virtuemart_vendor_id = a.virtuemart_vendor_id")
            ->leftJoin("#__congtacvien_vendor_group as c ON c.virtuemart_vendor_id = a.virtuemart_vendor_id AND c.state = 1")
            ->leftJoin('#__virtuemart_vendor_users as d ON d.virtuemart_vendor_user_id = a.virtuemart_vendor_id')
            ->leftJoin('#__virtuemart_userinfos as e ON e.virtuemart_user_id = d.virtuemart_user_id AND e.address_type = "BT"')
            ->leftJoin('#__virtuemart_states as f ON f.virtuemart_state_id = e.virtuemart_state_id')

        ;

        if ($isSearch)
        {
            foreach ($filters->rules as $rule)
            {
                switch ($rule->field)
                {

                    default:
                        $where = $this->_db->quoteName($rule->field) . " LIKE " . $this->_db->quote($rule->data . "%");
                        $query->where($where);
                        break;
                }
            }
        }

//echo $query;

        $count = $this->_getListCount($query);

        if( $count > 0 ) {
            $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }

        $response = new stdClass();
        $response->page = $page;
        $response->records = $count;
        $response->total = $total_pages;

        $records = $this->_getList($query, ($page -1) * $limit, $limit) ;

        $i = 0;
        $response->rows = array();

        foreach ($records as $record) {
            $response->rows[$i]['id'] = $record->virtuemart_vendor_id;

            $result = array();
            foreach (get_object_vars($record) as $k => $v) {
                $result[] = $v;
            }
            $response->rows[$i]['cell'] = $result;
            $i++;
        }

        return $response;
    }



    public function listMucluongProfile()
    {
        $page = $this->input->getInt('page', 1);
        $limit = $this->input->getInt('rows', 10);
        $sidx = $this->input->getString('sidx', 'id');
        $sord = $this->input->getString('sord', 'asc');


        $filters = $this->input->getString('filters', '{}');
        $filters = json_decode($filters);

        $totalrows = $this->input->getInt('totalrows', $limit);

        $isSearch = $this->input->getWord('_search') == 'true' ? true : false;

        $user = JFactory::getUser();

        $asset		= 'com_oongtacvien';
        $authorise['admin']  = $user->authorise('thuong.admin', $asset);
        $authorise['edit']  = $user->authorise('thuong.edit', $asset);
        $authorise['editown']  = $user->authorise('thuong.editown', $asset);
        $authorise['view'] = $user->authorise('thuong.view', $asset);

        $query = $this->_db->getQuery(true);
        $q1 = $this->_db->getQuery(true);

        if ( !( $authorise['admin'] || $authorise['editown'] || $authorise['edit'] || $authorise['view'])  ) {
            $response = new JObject;
            $response->page = 1;
            $response->records = 0;
            $response->total = 0;
//            return $response;
        }

        $query->select("id, doituong_code, title, description, created, published, ordering")
            ->from("#__congtacvien_luong_profile as a")
            ->order($sidx. " ". $sord)
        ;

        if ($isSearch)
        {
            foreach ($filters->rules as $rule)
            {
                switch ($rule->field)
                {

                    default:
                        $where = $this->_db->quoteName($rule->field) . " LIKE " . $this->_db->quote($rule->data . "%");
                        $query->where($where);
                        break;
                }
            }
        }

//echo $query;

        $count = $this->_getListCount($query);

        if( $count > 0 ) {
            $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }

        $response = new stdClass();
        $response->page = $page;
        $response->records = $count;
        $response->total = $total_pages;

        $records = $this->_getList($query, ($page -1) * $limit, $limit) ;

        $i = 0;
        $response->rows = array();

        foreach ($records as $record) {
            $response->rows[$i]['id'] = $record->id;

            $result = array();
            foreach (get_object_vars($record) as $k => $v) {
                $result[] = $v;
            }
            $response->rows[$i]['cell'] = $result;
            $i++;
        }

        return $response;
    }

    public function processlistMucluongProfile()
    {
        $result = new JObject;
        $result->code = 1;
        $result->data = '';

        $user = JFactory::getUser();

        $oper = $this->input->getWord('oper');
        $table  = JTable::getInstance('LuongProfile', 'CTVTable');

        switch ($oper) {
            case "edit":
            case "add":


                $postData = JRequest::get('post');

                // Bind the form fields to the web link table
                if (!$table->bind($postData)) {
                    $this->setError($this->_db->getErrorMsg());
                    $result->code = 0;
                    $result->data = $table->getError();
                    return $result;
                }

                // Store the article table to the database
                if (!$table->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    $result->code = 0;
                    $result->data = $table->getError();
                    return $result;
                }
                break;
            case "del":
                $id = $this->input->getInt("id");
                if (!$table->delete($id)) {
                    $this->setError($this->_db->getErrorMsg());
                    $result->code = 0;
                    $result->data = $table->getError();
                    return $result;
                }
                break;
            default: break;
        }

        return $result;
    }


    public function listLuongNguong()
    {
        $page = $this->input->getInt('page', 1);
        $limit = $this->input->getInt('rows', 10);
        $sidx = $this->input->getString('sidx', 'id');
        $sord = $this->input->getString('sord', 'asc');


        $filters = $this->input->getString('filters', '{}');
        $filters = json_decode($filters);

        $totalrows = $this->input->getInt('totalrows', $limit);

        $isSearch = $this->input->getWord('_search') == 'true' ? true : false;

        $user = JFactory::getUser();

        $asset		= 'com_oongtacvien';
        $authorise['admin']  = $user->authorise('thuong.admin', $asset);
        $authorise['edit']  = $user->authorise('thuong.edit', $asset);
        $authorise['editown']  = $user->authorise('thuong.editown', $asset);
        $authorise['view'] = $user->authorise('thuong.view', $asset);

        $query = $this->_db->getQuery(true);

        if ( !( $authorise['admin'] || $authorise['editown'] || $authorise['edit'] || $authorise['view'])  ) {
            $response = new JObject;
            $response->page = 1;
            $response->records = 0;
            $response->total = 0;
//            return $response;
        }

        $profile_id = $this->input->getInt('profile_id', 0);

        $query->select("id, profile_id, nguong, bonus, ordering")
            ->from("#__congtacvien_luong_nguong as a")
            ->where('a.profile_id = '. $profile_id)
            ->order($sidx. " ". $sord)
        ;

        if ($isSearch)
        {
            foreach ($filters->rules as $rule)
            {
                switch ($rule->field)
                {

                    default:
                        $where = $this->_db->quoteName($rule->field) . " LIKE " . $this->_db->quote($rule->data . "%");
                        $query->where($where);
                        break;
                }
            }
        }

//echo $query;

        $count = $this->_getListCount($query);

        if( $count > 0 ) {
            $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }

        $response = new stdClass();
        $response->page = $page;
        $response->records = $count;
        $response->total = $total_pages;

        $records = $this->_getList($query, ($page -1) * $limit, $limit) ;

        $i = 0;
        $response->rows = array();

        foreach ($records as $record) {
            $response->rows[$i]['id'] = $record->id;

            $result = array();
            foreach (get_object_vars($record) as $k => $v) {
                $result[] = $v;
            }
            $response->rows[$i]['cell'] = $result;
            $i++;
        }

        return $response;
    }

    public function processlistLuongNguong()
    {
        $result = new JObject;
        $result->code = 1;
        $result->data = '';

        $user = JFactory::getUser();

        $oper = $this->input->getWord('oper');
        $table  = JTable::getInstance('LuongNguong', 'CTVTable');

        switch ($oper) {
            case "edit":
            case "add":


                $postData = JRequest::get('post');

                // Bind the form fields to the web link table
                if (!$table->bind($postData)) {
                    $this->setError($this->_db->getErrorMsg());
                    $result->code = 0;
                    $result->data = $table->getError();
                    return $result;
                }

                // Store the article table to the database
                if (!$table->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    $result->code = 0;
                    $result->data = $table->getError();
                    return $result;
                }
                break;
            case "del":
                $id = $this->input->getInt("id");
                if (!$table->delete($id)) {
                    $this->setError($this->_db->getErrorMsg());
                    $result->code = 0;
                    $result->data = $table->getError();
                    return $result;
                }
                break;
            default: break;
        }

        return $result;
    }


    public function getVendorConfig()
    {
        $result = new JResponseJson();

        $now = JFactory::getDate();

        $vendor_id = $this->input->getInt('vendor_id', 0);
        $query = $this->_db->getQuery(true);
        $query->select('a.id, b.virtuemart_vendor_id as vendor_id, a.luong_coban, a.thuong_profile_id as profile_id, c.doituong_code as doituong, c.id as doituong_id')
            ->from('#__virtuemart_vendors as b')
            ->leftJoin('#__congtacvien_vendor as a ON a.vendor_id = b.virtuemart_vendor_id')
            ->leftJoin('#__congtacvien_vendor_group as c ON c.virtuemart_vendor_id = b.virtuemart_vendor_id')
            ->where('b.virtuemart_vendor_id = '. $vendor_id)
            ;

        $this->_db->setQuery($query);
        $row = $this->_db->loadObject();

        if (!$row) {
            $row = new stdClass();
            $row->id = 0;
            $row->vendor_id = $vendor_id;
            $row->profile_id = 0;
            $row->luong_coban = 0;
        } else {
            if (!$row->id) {
                $table = JTable::getInstance('Vendor', 'CTVTable');
                $table->vendor_id = $vendor_id;
                $table->thuong_profile_id = 0;
                $table->luong_coban = 0;
                if ($table->store()) {
                    $row->id = $table->id;
                }
            }

            if (!$row->doituong) {
                $table = JTable::getInstance('VendorGroup', 'CTVTable');
                $table->virtuemart_vendor_id = $vendor_id;
                $table->doituong_code = 'CTVCT';
                $table->created = $now->toSql(true);
                $table->published_on = $now->toSql(true);
                $table->expired_on = '2030-12-31';
                $table->state = 1;

                if ($table->store()) {
                    $row->doituong = $table->doituong_code;
                    $row->doituong_id = $table->id;
                }
            }


        }
        $result->data = $row;

        return $result;
    }


    public function saveVendorConfig()
    {
        $result = new JResponseJson();

        $config = $this->input->json->getArray(array('id'=>0, 'vendor_id'=>0, 'profile_id'=>0, 'luong_coban' => 0, 'doituong_id'=>0, 'doituong'=>''));

        $config['thuong_profile_id'] = $config['profile_id'];
        unset($config['profile_id']);

        $table = JTable::getInstance('Vendor', 'CTVTable');

        if (!$table->bind($config))
        {
            $result->success = false;
            $result->message = $table->getError();
            return $result;
        }
        if ($table->check()) {
            if (!$table->store()) {
                $result->success = false;
                $result->message = $table->getError();
                return $result;
            }
        } else {
            $result->success = false;
            $result->message = $table->getError();
            return $result;
        }

        $tmp = \Joomla\Utilities\ArrayHelper::fromObject($table);
        $tmp = new stdClass();
        $tmp->id = $table->id;
        $tmp->vendor_id = $table->vendor_id;
        $tmp->profile_id = $table->thuong_profile_id;
        $tmp->luong_coban = $table->luong_coban;
        $result->data = $tmp;

        $table = JTable::getInstance('VendorGroup', 'CTVTable');
        $table->load($config['doituong_id']);
        $table->doituong_code =  $config['doituong'];

        if ($table->check()) {
            if (!$table->store()) {
                $result->success = false;
                $result->message = $table->getError();
                return $result;
            }
        } else {
            $result->success = false;
            $result->message = $table->getError();
            return $result;
        }
        $tmp->doituong = $table->doituong_code;
        $tmp->doituong_id = $table->id;

        $result->success = true;
        return $result;
    }


    public function listVendorSale()
    {

        $page = $this->input->getInt('page', 1);
        $limit = $this->input->getInt('rows', 10);
        $sidx = $this->input->getString('sidx', 'created_on');
        $sord = $this->input->getString('sord', 'asc');


        $filters = $this->input->getString('filters', '{}');
        $filters = json_decode($filters);

        $totalrows = $this->input->getInt('totalrows', $limit);

        $isSearch = $this->input->getWord('_search') == 'true' ? true : false;

        $user = JFactory::getUser();

        $asset		= 'com_oongtacvien';
        $authorise['admin']  = $user->authorise('thuong.admin', $asset);
        $authorise['edit']  = $user->authorise('thuong.edit', $asset);
        $authorise['editown']  = $user->authorise('thuong.editown', $asset);
        $authorise['view'] = $user->authorise('thuong.view', $asset);

        $query = $this->_db->getQuery(true);

        if ( !( $authorise['admin'] || $authorise['editown'] || $authorise['edit'] || $authorise['view'])  ) {
            $response = new JObject;
            $response->page = 1;
            $response->records = 0;
            $response->total = 0;
//            return $response;
        }

        $vendor_id = $this->input->getInt('vendor_id', 0);

        $query->select("a.virtuemart_order_id, a.created_on, a.order_number, a.invoice_locked, a.order_total, a.order_salesprice, a.order_billdiscountamount, a.order_subtotal")
//            ->select('')
            ->from("#__virtuemart_orders as a")
            ->where('a.virtuemart_vendor_id = '. $vendor_id)
            ->order($sidx. " ". $sord)
        ;

        if ($isSearch)
        {
            foreach ($filters->rules as $rule)
            {
                switch ($rule->field)
                {

                    default:
                        $where = $this->_db->quoteName($rule->field) . " LIKE " . $this->_db->quote($rule->data . "%");
                        $query->where($where);
                        break;
                }
            }
        }

//echo $query;

        $count = $this->_getListCount($query);

        if( $count > 0 ) {
            $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }

        $response = new stdClass();
        $response->page = $page;
        $response->records = $count;
        $response->total = $total_pages;

        $records = $this->_getList($query, ($page -1) * $limit, $limit) ;

        $i = 0;
        $response->rows = array();

        foreach ($records as $record) {
            $response->rows[$i]['id'] = $record->virtuemart_order_id;

            $result = array();
            foreach (get_object_vars($record) as $k => $v) {
                $result[] = $v;
            }
            $response->rows[$i]['cell'] = $result;
            $i++;
        }

        $query->clear('select')->clear('order')->clear('limit');
        $query->select("sum(a.order_total) order_total, sum(a.order_salesprice) order_salesprice")
        ->select("sum(a.order_billdiscountamount) order_billdiscountamount, sum(a.order_subtotal) order_subtotal");
        $this->_db->setQuery($query);
        $totalValue = $this->_db->loadObject();

        //$response->userdata = array('total' => $totalValue);
        $response->userdata['order_total'] = (int) $totalValue->order_total;
        $response->userdata['order_salesprice'] = (int) $totalValue->order_salesprice;
        $response->userdata['order_billdiscountamount'] = (int) $totalValue->order_billdiscountamount;
        $response->userdata['order_subtotal'] = (int) $totalValue->order_subtotal;
        $response->userdata['order_number'] = JText::_('Total');


        return $response;
    }

    public function getSchemeBonus()
    {
        $result = new JResponseJson();

        $profile_id = $this->input->getInt('profile_id', 0);

        $query = $this->_db->getQuery(true);
        $query->select("a.id, a.nguong, a.bonus")
            ->from('#__congtacvien_luong_nguong as a')
            ->where('a.profile_id = '. $profile_id)
            ->ordering('a.nguong');
//echo $query;
        $this->_db->setQuery($query);

        $result->data = $this->_db->loadObjectList();
        $result->success = true;
        return $result;
    }

    public function getVendorInfo()
    {

        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);
        $query = $this->_db->getQuery(true);

        $query->select("a.virtuemart_vendor_id as vendor_id, IFNULL(c.doituong_code, '') as doituong_code, b.vendor_store_name, e.address_1 as address, e.virtuemart_state_id, f.state_name as province, b.vendor_phone, b.vendor_mail_free1 as email")
            ->select('c.state, g.luong_coban, g.thuong_profile_id as profile_id')
            ->from("#__virtuemart_vendors as a")
            ->leftJoin("#__virtuemart_vendors_vi_vn as b ON b.virtuemart_vendor_id = a.virtuemart_vendor_id")
            ->leftJoin("#__congtacvien_vendor_group as c ON c.virtuemart_vendor_id = a.virtuemart_vendor_id AND c.state = 1")
            ->leftJoin('#__virtuemart_vendor_users as d ON d.virtuemart_vendor_user_id = a.virtuemart_vendor_id')
            ->leftJoin('#__virtuemart_userinfos as e ON e.virtuemart_user_id = d.virtuemart_user_id AND e.address_type = "BT"')
            ->leftJoin('#__virtuemart_states as f ON f.virtuemart_state_id = e.virtuemart_state_id')
            ->leftJoin('#__congtacvien_vendor as g ON g.vendor_id = a.virtuemart_vendor_id')
            ->where('a.virtuemart_vendor_id = '. (int) $vendor_id)
        ;

        $this->_db->setQuery($query);
        $row = $this->_db->loadObject();
        if (!$row) {
            $row = new stdClass();
            $row->vendor_id = $vendor_id;
            $row->profile_id = 0;
            $row->luong_coban = 0;
            $row->doituong_code = '';
            $row->doituong = '';
        }

        $query = $this->_db->getQuery(true);
        $query->select("a.virtuemart_order_id, a.created_on, a.order_number, a.invoice_locked, a.order_total, a.order_salesprice, a.order_billdiscountamount, a.order_subtotal")
            ->from("#__virtuemart_orders as a")
            ->where('a.virtuemart_vendor_id = '. $vendor_id)
            ->order('a.created_on')
        ;
        $this->_db->setQuery($query);
        $row->orders = $this->_db->loadObjectList();

        return $row;
    }

    public function getSalaryByVendor()
    {
        $result = new JResponseJson();

        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);
        if ($vendor_id <= 0) {
            $result->success = false;
            $result->message = JText::_('COM_CONGTACVIEN_NOT_VENDOR');
            return $result;
        }

        $date = $this->input->getString('date', '');

        $config = JFactory::getConfig();
        $now = new Joomla\CMS\Date\Date($date, $config->get('offset'));
        $numberDayOfMonth = cal_days_in_month(CAL_GREGORIAN, $now->format('n'), $now->format('M'));


        $query = $this->_db->getQuery(true);
        $query->select("a.id, b.luong_coban, a.nguong, a.bonus")
            ->from('#__congtacvien_luong_nguong as a')
            ->leftJoin('#__congtacvien_vendor as b ON b.thuong_profile_id = a.profile_id')
            ->where('b.vendor_id = '. $vendor_id)
            ->ordering('a.nguong');
//echo $query;
        $this->_db->setQuery($query);
        $nguong = $this->_db->loadObjectList();

        $result->data = $nguong;


        $query = $this->_db->getQuery(true);
        $query->select('SUM(a.order_subtotal) as saletotal')
            ->select('a.created_on')
            ->from("#__virtuemart_orders as a")
            ->where("a.virtuemart_vendor_id = ". (int)$vendor_id)
            ->where('YEAR(a.created_on) = '. $this->_db->quote($now->format('Y')))
            ->where('MONTH(a.created_on) = '. $this->_db->quote($now->format('n')))
        ;
//echo $query;
        $this->_db->setQuery($query);
        $sale = $this->_db->loadResult();
//echo $query;

        $data = new stdClass();
        $data->salary = $this->calculateSalary($nguong, $sale);


        $data->series = array();
        $data->series[] = JText::_('COM_CONGTACVIEN_SALES_TOTAL');
        $data->series[] = JText::_('COM_CONGTACVIEN_ORDERS_TOTAL');

        $data->labels = array_fill(0, $numberDayOfMonth, '');
        foreach ($data->labels as $k => &$tmp) {
            $tmp = $k + 1;
        }

        $data->data = array();
        $data->data[] = array_fill(0, $numberDayOfMonth, 0);
        $data->data[] = array_fill(0, $numberDayOfMonth, 0);

        $query = $this->_db->getQuery(true);
        $query->select('DAY(IFNULL(a.modified_on, a.created_on)) AS order_day, IFNULL(a.modified_on, a.created_on) AS order_date, a.order_subtotal AS order_total')
            ->from('#__virtuemart_orders as a')
            ->where('a.virtuemart_vendor_id = '. $vendor_id)
            ->where('a.order_status IN ("P", "U", "C", "S", "F")')
            ->where('YEAR(IFNULL(a.modified_on, a.created_on)) = '. $this->_db->quote($now->format('Y')))
            ->where('MONTH(IFNULL(a.modified_on, a.created_on)) = '. $this->_db->quote($now->format('n')))
        ;

        $q1 = $this->_db->getQuery(true);
        $q1->select('b.order_day, SUM(b.order_total) as order_total, count(b.order_total) as order_count')
            ->from($query, 'b')
            ->group('b.order_day');

        $this->_db->setQuery($q1);
        $rows = $this->_db->loadObjectList();

        foreach ($rows as $row) {
            $data->data[0][$row->order_day-1] = $row->order_total;
            $data->data[1][$row->order_day-1] = $row->order_count;
        }

        $result->data = $data;

        $result->success = true;
        return $result;
    }

    private function calculateSalary($thuong_profile, $sale)
    {
        $result = array();
        $result['sale'] = (int)$sale;
        $thuong = 0;

        if (!is_array($thuong_profile)) {
            $result['luong_coban'] = 0;
            $result['thuong'] = 0;
            return $result;
        }

        $result['luong_coban'] = $thuong_profile[0]->luong_coban;
        $index = 0;

        while ($sale >= $thuong_profile[$index]->nguong) {
            $thuong += $thuong_profile[$index]->nguong * $thuong_profile[$index]->bonus * 0.01;
            $index += 1;
        }

        // xử lý phần dư
        if ($index > 0) {
            $thuong += ($sale - $thuong_profile[$index-1]->nguong) * $thuong_profile[$index]->bonus * 0.01;
        }

        $result['thuong'] = $thuong;
        $result['total'] = $result['luong_coban'] + $result['thuong'];

        return $result;
    }

    public function getAnnualDataByVendor()
    {
        $result = new JResponseJson();
        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);

        if ($vendor_id <= 0) {
            $result->success = false;
            $result->message = JText::_('COM_CONGTACVIEN_NOT_VENDOR');
            return $result;
        }

        $config = JFactory::getConfig();
        $now = JFactory::getDate('now', $config->get('offset'));

        $query = $this->_db->getQuery(true);
        $query->select('MONTH(IFNULL(a.modified_on, a.created_on)) AS order_month, IFNULL(a.modified_on, a.created_on) AS order_date, a.order_subtotal AS order_total')
        ->from('#__virtuemart_orders as a')
        ->where('a.virtuemart_vendor_id = '. $vendor_id)
        ->where('a.order_status IN ("P", "U", "C", "S", "F")')
            ->where('YEAR(IFNULL(a.modified_on, a.created_on)) = '. $this->_db->quote($now->format('Y')))
        ;

        $q1 = $this->_db->getQuery(true);
        $q1->select('b.order_month, SUM(b.order_total) as order_total')
            ->from($query, 'b')
            ->group('b.order_month');

        $this->_db->setQuery($q1);
        $rows = $this->_db->loadObjectList();

        $data = new stdClass();
        $data->total = array_fill(0, 12, 0);

        foreach ($rows as $row) {
            $data->total[$row->order_month-1] = $row->order_total;
        }
//var_dump($data);

        $result->data = $data;

        return $result;
    }


    public function getMonthlyDataByVendor()
    {
        $result = new JResponseJson();
        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);

        if ($vendor_id <= 0) {
            $result->success = false;
            $result->message = JText::_('COM_CONGTACVIEN_NOT_VENDOR');
            return $result;
        }

        $config = JFactory::getConfig();
        $now = JFactory::getDate('now', $config->get('offset'));
        $number = cal_days_in_month(CAL_GREGORIAN, $now->format('n'), $now->format('M'));

        $query = $this->_db->getQuery(true);
        $query->select('MONTH(IFNULL(a.modified_on, a.created_on)) AS order_month, IFNULL(a.modified_on, a.created_on) AS order_date, a.order_subtotal AS order_total')
            ->from('#__virtuemart_orders as a')
            ->where('a.virtuemart_vendor_id = '. $vendor_id)
            ->where('a.order_status IN ("P", "U", "C", "S", "F")')
            ->where('YEAR(IFNULL(a.modified_on, a.created_on)) = '. $this->_db->quote($now->format('Y')))
        ;

        $q1 = $this->_db->getQuery(true);
        $q1->select('b.order_month, SUM(b.order_total) as order_total')
            ->from($query, 'b')
            ->group('b.order_month');

        $this->_db->setQuery($q1);
        $rows = $this->_db->loadObjectList();

        $data = new stdClass();
        $total = array_fill(0, 12, 0);


        foreach ($rows as $row) {
            $total[$row->order_month-1] = $row->order_total;
        }
//var_dump($data);
        $data->data = array();
        $data->data[] = $total;
        $data->data[] = $total;

        $result->data = $data;


        return $result;
    }
}