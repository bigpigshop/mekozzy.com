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

if (!class_exists( 'VmConfig' ))
    require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');

VmConfig::loadConfig();
vmLanguage::loadJLang('com_virtuemart', true);
vmLanguage::loadJLang('com_virtuemart_orders', true);
vmLanguage::loadJLang('com_virtuemart_shoppers', true);


jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file'); 

JLoader::register('CTVHelperRoute', JPATH_COMPONENT . '/helpers/route.php');
JLoader::register('CTVHelper', JPATH_COMPONENT . '/helpers/helper.php');


/**
 * Search Component Search Model
 *
 * @since  1.5
 */
class CtvModelShop extends JModelLegacy
{
    
    protected $input = null;
    protected $sessionId = null;
    protected $app = null;
    protected $config = null;
    protected $user = null;
    protected $vendor = null;
    protected $authorise;
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

    /**
     * @return @
     */
    public function getTestAPI()
    {

        $vmProductModel = new VirtueMartModelProduct();
        $products = $vmProductModel->getProductsInCategory(110);

        $vmProductModel->setPaginationLimits(50);
//var_dump( $limits);

        $products = $vmProductModel->getProductListing(FALSE, FALSE, TRUE, FALSE, FALSE, FALSE, 0, FALSE, 0, 0);
        var_dump($products);

        // getCategoryTree
        $vmCategoryModel = new VirtueMartModelCategory();
        $categories = $vmCategoryModel->getChildCategoryList(1, 128);


//var_dump($categories);


    }

    public function getInventoryProducts_old()
    {
        $result = new \JResponseJson();

        // Lấy thông tin sản phẩm thuộc VendorID đang đăng nhập
        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);
        $query = $this->_db->getQuery(true);
        $query->select('product_sku')
        ->from("#__virtuemart_products")
        ->where("virtuemart_vendor_id = ". (int)$vendor_id);
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();

        $vendor_product_skus = array();
        foreach ($rows as $row) {
            $vendor_product_skus[] = $row->product_sku;
        }

//        $vendorModel = new VirtueMartModelVendor();
//        $vendor = $vendorModel->getVendor($vendor_id);
//        var_dump($vendor);

        $pager = $this->input->json->get('pager', array(), 'array');
        $catid = $this->input->json->getInt('catid', 0);
        $manid = $this->input->json->getInt('manid', 0);

//        var_dump( $pager);
        $vmProductModel = new VirtueMartModelProduct();

        $limit = (int)$pager['itemsperpage'] > 30 ? 30 : (int)$pager['itemsperpage'];

        $limitStartString  = 'com_virtuemart.virtuemart';
        $this->app->setUserState($limitStartString.'.limit', (int)$limit);
        $limitstart = floor(((int)$pager['currentPage'] - 1) * $limit);
        $this->app->setUserState($limitStartString.'.limitstart', $limitstart);

        $limitsTemp = $vmProductModel->setPaginationLimits(false);

        if ($catid > 0) {
            $products = $vmProductModel->getProductListing(FALSE, FALSE, TRUE, true, FALSE, True, $catid, FALSE, 0);
        } else {
            $products = $vmProductModel->getProductListing(FALSE, FALSE, TRUE, true, FALSE, FALSE, 0, FALSE, 0);
        }

        $pagination = $vmProductModel->getPagination();
//var_dump($pagination);

        $result->limitstart = $pagination->get('limitstart') + 1;
        $result->limit  = $pagination->limit;
        $result->total  = $pagination->total;

        $result->data = [];

        if (count($products)) {
            $vmProductModel->addImages($products);
        }
//        var_dump( $products);
        $currency = CurrencyDisplay::getInstance( );
        $skus = array();

        foreach ($products as $product) {

            if (in_array($product->product_sku, $skus)) {
                continue;
            }

            $skus[] = $product->product_sku;

            $objProduct = new JObject();
            $objProduct->virtuemart_product_id  = $product->virtuemart_product_id;
            $objProduct->virtuemart_vendor_id   = $product->virtuemart_vendor_id;
            $objProduct->product_sku            = $product->product_sku;
            $objProduct->product_name           = $product->product_name;
            $objProduct->product_desc           = $product->product_s_desc;
            $objProduct->category_name          = $product->category_name;
            $objProduct->link                   = JRoute::_($product->link) ;
            $objProduct->prices                 = $product->prices;
            $objProduct->priceFormatted         = $currency->createPriceDiv ('salesPrice', '', $product->prices, true, false, 1.0, TRUE);
            $objProduct->inVendorStore          = in_array($product->product_sku, $vendor_product_skus) ? true : false;

            $imagePath = $product->images[0]->getFullPath();
            if (JFile::exists($imagePath)) {
                $objProduct->imageUrl               = $product->images[0]->getUrl();
            } else {
                $objProduct->imageUrl = 'images/virtuemart/typeless/noimage_810x810.gif';
            }

            $result->data[] = $objProduct;
        }


        return $result;
    }

    public function getInventoryProducts()
    {
        $result = new \JResponseJson();

        // Lấy thông tin sản phẩm thuộc VendorID đang đăng nhập
        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);
        $query = $this->_db->getQuery(true);
        $query->select('product_sku')
            ->from("#__virtuemart_products")
            ->where("virtuemart_vendor_id = ". (int)$vendor_id);
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();

        $vendor_product_skus = array();
        foreach ($rows as $row) {
            $vendor_product_skus[] = $row->product_sku;
        }


        $pager = $this->input->json->get('pager', array(), 'array');
        $catid = $this->input->json->getInt('catid', 0);
        $manid = $this->input->json->getInt('manid', 0);
        $searchString = $this->input->json->getString('search', '');
//        var_dump( $pager);
        $vmProductModel = new VirtueMartModelProduct();

        $limit = (int)$pager['itemsperpage'] > 30 ? 30 : (int)$pager['itemsperpage'];
        $currentPage = (int)$pager['currentPage'];

//        $limitstart = $currentPage * $limit;
        $result->limit  = $limit;
        $result->limitstart = $currentPage;

        $query = $this->_db->getQuery(true);
        $query->select('DISTINCT a.virtuemart_product_id')
            ->from("#__virtuemart_products as a")
            ->leftJoin("#__virtuemart_products_vi_vn as b ON b.virtuemart_product_id = a.virtuemart_product_id")
            ->where("a.virtuemart_vendor_id IN (0,1)")
            ->where('a.published = 1');

        $fieldsWhere = array();

        if ($searchString != "") {
            $fieldsWhere[] = "a.product_sku = ". $this->_db->quote($searchString);
            $fieldsWhere[] =  "b.product_name LIKE ". $this->_db->quote("%".$searchString."%");
            $fieldsWhere[] =  "b.product_s_desc LIKE ". $this->_db->quote("%".$searchString."%");
            $fieldsWhere[] =  "b.product_desc LIKE ". $this->_db->quote("%".$searchString."%");
            $fieldsWhere[] =  "b.customtitle LIKE ". $this->_db->quote("%".$searchString."%");
            $query->extendWhere('AND', $fieldsWhere, "OR");
        }

        if ($catid > 0) {
            $query->leftJoin('#__virtuemart_product_categories as c ON c.virtuemart_product_id = a.virtuemart_product_id')
                ->where('c.virtuemart_category_id = ' . $catid);
        }
//echo $query;
//        die;

        $result->total = $this->_getListCount($query);

        $this->_db->setQuery($query, ($currentPage - 1) * $limit, $limit);
        $rows = $this->_db->loadObjectList();

        $vendor_product_ids = array();
        foreach ($rows as $row) {
            $vendor_product_ids[] = $row->virtuemart_product_id;
        }

        $vmProductModel = new VirtueMartModelProduct();

        $products = $vmProductModel->getProducts ($vendor_product_ids, true, true, false, false);

        $result->data = [];

        if (count($products)) {
            $vmProductModel->addImages($products);
        }
//        var_dump( $products);
        $currency = CurrencyDisplay::getInstance( );

        foreach ($products as $product) {

            $objProduct = new JObject();
            $objProduct->virtuemart_product_id  = $product->virtuemart_product_id;
            $objProduct->virtuemart_vendor_id   = $product->virtuemart_vendor_id;
            $objProduct->product_sku            = $product->product_sku;
            $objProduct->product_name           = $product->product_name;
            $objProduct->product_desc           = $product->product_s_desc;
            $objProduct->category_name          = $product->category_name;
            $objProduct->link                   = JRoute::_($product->link) ;
            $objProduct->prices                 = $product->prices;
            $objProduct->priceFormatted         = $currency->createPriceDiv ('salesPrice', '', $product->prices, true, false, 1.0, TRUE);
            $objProduct->inVendorStore          = in_array($product->product_sku, $vendor_product_skus) ? true : false;
            $objProduct->published              = (int)$product->published;

            $imagePath = $product->images[0]->getFullPath();
            if (JFile::exists($imagePath)) {
                $objProduct->imageUrl               = $product->images[0]->getUrl();
            } else {
                $objProduct->imageUrl = 'images/virtuemart/typeless/noimage_810x810.gif';
            }

            $result->data[] = $objProduct;
        }

        return $result;
    }


    public function addProductByVendor()
    {
        $result = new \JResponseJson();

        error_reporting(E_ERROR);

        // Lấy thông tin sản phẩm thuộc VendorID đang đăng nhập
        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);
        $pager = $this->input->json->get('pager', array(), 'array');
        $product_id = $this->input->json->getInt('product_id', 0);

        if ($vendor_id <= 0) {
            $result->message = JText::_('COM_CONGTACVIEN_NOT_VENDOR');
            $result->success = false;
            return $result;
        }

        if ($product_id <= 0) {
            $result->message = JText::_('COM_CONGTACVIEN_PRODUCT_EMPTY');
            $result->success = false;
            return $result;
        }


        $vmProductModel = new VirtueMartModelProduct();
        $clone_product_id = $vmProductModel->createClone($product_id);
        if ($clone_product_id <= 0) {
            $result->message = JText::_('COM_CONGTACVIEN_PRODUCT_CLONE_FAIL');
            $result->success = false;
            return $result;
        }

        $query = $this->_db->getQuery(true);
        $query->update("#__virtuemart_products")
            ->set("published = 1")
            ->set("virtuemart_vendor_id = ". $vendor_id)
            ->where("virtuemart_product_id = ". $clone_product_id);
        $this->_db->setQuery($query)->execute();

        $result->success = true;
        $result->message = JText::_('COM_CONGTACVIEN_PRODUCT_CLONE_SUCCESSFUL');

        return $result;
    }

    public function removeProductByVendor()
    {
        $result = new \JResponseJson();

//        error_reporting(E_ERROR);

        // Lấy thông tin sản phẩm thuộc VendorID đang đăng nhập
        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);
        $pager = $this->input->json->get('pager', array(), 'array');
        $product_id = $this->input->json->getInt('product_id', 0);

        if ($vendor_id <= 0) {
            $result->message = JText::_('COM_CONGTACVIEN_NOT_VENDOR');
            $result->success = false;
            return $result;
        }

        if ($product_id <= 0) {
            $result->message = JText::_('COM_CONGTACVIEN_PRODUCT_EMPTY');
            $result->success = false;
            return $result;
        }

        $query = $this->_db->getQuery(true);
        $query
            ->select("a.virtuemart_product_id")
            ->from("#__virtuemart_products as a")
            ->leftJoin('#__virtuemart_products as b ON b.product_sku = a.product_sku')
            ->where("a.virtuemart_vendor_id = ". $vendor_id)
            ->where("b.virtuemart_product_id = ". $product_id);
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();

        $product_ids = array();
        foreach ($rows as $row) {
            $product_ids[] = $row->virtuemart_product_id;
        }

        $vmProductModel = new VirtueMartModelProduct();
        $tmp = $vmProductModel->remove($product_ids);

        $result->success = true;
        $result->message = JText::_('COM_CONGTACVIEN_PRODUCT_REMOVE_SUCCESSFUL');

        return $result;
    }


    public function getVendorProducts()
    {
        $result = new \JResponseJson();

        // Lấy thông tin sản phẩm thuộc VendorID đang đăng nhập
        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);
        $query = $this->_db->getQuery(true);
        $query->select('virtuemart_product_id')
            ->from("#__virtuemart_products")
            ->where("virtuemart_vendor_id = ". (int)$vendor_id);
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();

        $vendor_product_ids = array();
        foreach ($rows as $row) {
            $vendor_product_ids[] = $row->virtuemart_product_id;
        }

//        $vendorModel = new VirtueMartModelVendor();
//        $vendor = $vendorModel->getVendor($vendor_id);
//        var_dump($vendor);

        $pager = $this->input->json->get('pager', array(), 'array');
        $catid = $this->input->json->getInt('catid', 0);
        $manid = $this->input->json->getInt('manid', 0);

//        var_dump( $pager);
        $vmProductModel = new VirtueMartModelProduct();

        $limit = (int)$pager['itemsperpage'] > 30 ? 30 : (int)$pager['itemsperpage'];

        $limitStartString  = 'com_virtuemart.virtuemart';
        $this->app->setUserState($limitStartString.'.limit', (int)$limit);
        $limitstart = floor(((int)$pager['currentPage'] - 1) * $limit);
        $this->app->setUserState($limitStartString.'.limitstart', $limitstart);

        $limitsTemp = $vmProductModel->setPaginationLimits(false);

//        $products = $vmProductModel->getProductListing(FALSE, FALSE, TRUE, false, FALSE, FALSE, 0, FALSE, 0);
        $products = $vmProductModel->getProducts ($vendor_product_ids, true, true, false, false);
        $pagination = $vmProductModel->getPagination();
//var_dump($pagination);

        $result->limitstart = $pagination->get('limitstart') + 1;
        $result->limit  = $pagination->limit;
        $result->total  = $pagination->total;

        $result->data = [];

        if (count($products)) {
            $vmProductModel->addImages($products);
        }
//        var_dump( $products);
        $currency = CurrencyDisplay::getInstance( );

        foreach ($products as $product) {

            $objProduct = new JObject();
            $objProduct->virtuemart_product_id  = $product->virtuemart_product_id;
            $objProduct->virtuemart_vendor_id   = $product->virtuemart_vendor_id;
            $objProduct->product_sku            = $product->product_sku;
            $objProduct->product_name           = $product->product_name;
            $objProduct->product_desc           = $product->product_s_desc;
            $objProduct->category_name          = $product->category_name;
            $objProduct->link                   = JRoute::_($product->link) ;
            $objProduct->prices                 = $product->prices;
            $objProduct->priceFormatted         = $currency->createPriceDiv ('salesPrice', '', $product->prices, true, false, 1.0, TRUE);
            $objProduct->published              = (int)$product->published;

            $imagePath = $product->images[0]->getFullPath();
            if (JFile::exists($imagePath)) {
                $objProduct->imageUrl               = $product->images[0]->getUrl();
            } else {
                $objProduct->imageUrl = 'images/virtuemart/typeless/noimage_810x810.gif';
            }

            $result->data[] = $objProduct;
        }


        return $result;
    }

    public function removeProductOnStoreByVendor()
    {
        $result = new \JResponseJson();

//        error_reporting(E_ERROR);

        // Lấy thông tin sản phẩm thuộc VendorID đang đăng nhập
        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);
        $pager = $this->input->json->get('pager', array(), 'array');
        $product_id = $this->input->json->getInt('product_id', 0);

        if ($vendor_id <= 0) {
            $result->message = JText::_('COM_CONGTACVIEN_NOT_VENDOR');
            $result->success = false;
            return $result;
        }

        if ($product_id <= 0) {
            $result->message = JText::_('COM_CONGTACVIEN_PRODUCT_EMPTY');
            $result->success = false;
            return $result;
        }


        $vmProductModel = new VirtueMartModelProduct();
        $product = $vmProductModel->getProduct($product_id);

        if ($product->virtuemart_vendor_id != $vendor_id) {
            $result->message = JText::_('COM_CONGTACVIEN_PRODUCT_VENDOR_INCORRECT');
            $result->success = false;
            return $result;
        }

        $tmp = $vmProductModel->remove(array($product_id));

        if ($tmp) {
            $result->success = true;
            $result->message = JText::_('COM_CONGTACVIEN_PRODUCT_REMOVE_SUCCESSFUL');
        } else {
            $result->success = false;
            $result->message = JText::_('COM_CONGTACVIEN_PRODUCT_REMOVE_FAIL');
//            var_dump($tmp);
        }

        return $result;
    }



    public function setStateProductByVendor()
    {
        $result = new \JResponseJson();

        // Lấy thông tin sản phẩm thuộc VendorID đang đăng nhập
        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);
        $pager = $this->input->json->get('pager', array(), 'array');
        $product_id = $this->input->json->getInt('product_id', 0);
        $state      = $this->input->json->getInt('state', 0);

        if ($vendor_id <= 0) {
            $result->message = JText::_('COM_CONGTACVIEN_NOT_VENDOR');
            $result->success = false;
            return $result;
        }

        if ($product_id <= 0) {
            $result->message = JText::_('COM_CONGTACVIEN_PRODUCT_EMPTY');
            $result->success = false;
            return $result;
        }

        $query = $this->_db->getQuery(true);
        $query->from("#__virtuemart_products")
            ->select("virtuemart_vendor_id, virtuemart_product_id")
            ->where("virtuemart_product_id = ". $product_id);
        $this->_db->setQuery($query);
        $row = $this->_db->loadObject();

        if ($row->virtuemart_vendor_id != $vendor_id) {
            $result->message = JText::_('COM_CONGTACVIEN_PRODUCT_VENDOR_INCORRECT');
            $result->success = false;
            return $result;
        }

        if ($row->virtuemart_product_id <= 0) {
            $result->message = JText::_('COM_CONGTACVIEN_PRODUCT_NO_EXIST');
            $result->success = false;
            return $result;
        }

        $query = $this->_db->getQuery(true);
        $query->update("#__virtuemart_products")
            ->set("published = ". $state)
            ->where("virtuemart_product_id = ". $product_id);
        $this->_db->setQuery($query)->execute();

        $result->success = true;
        $result->message = JText::_('COM_CONGTACVIEN_PRODUCT_UPDATE_STATUS_SUCCESSFUL');

        return $result;
    }

    public function getVendorOrders()
    {
        $result = new \JResponseJson();

        // Lấy thông tin sản phẩm thuộc VendorID đang đăng nhập
        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);
        $query = $this->_db->getQuery(true);
        $query->select('a.virtuemart_order_id, CONCAT_WS(" ", b.first_name, b.last_name) as customer, a.order_number, a.customer_number, a.order_pass, a.order_create_invoice_pass')
            ->select('invoice_locked, order_total, a.order_salesPrice, a.order_subtotal, a.order_discountAmount, c.order_status_name')
            ->select('a.created_on')
            ->from("#__virtuemart_orders as a")
            ->leftJoin('#__virtuemart_userinfos as b ON b.virtuemart_user_id = a.virtuemart_user_id')
            ->leftJoin('#__virtuemart_orderstates as c ON c.order_status_code = a.order_status AND c.virtuemart_vendor_id = 1')
            ->where("a.virtuemart_vendor_id = ". (int)$vendor_id)
            ->order('a.created_on desc');

        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
//echo $query;
        foreach ($rows as &$row) {
            $row->order_status_name = vmText::_($row->order_status_name);
        }

        $result->data = $rows;

        return $result;
    }


    public function getVendorOrderDetail()
    {
        $result = new \JResponseJson();

        $order_id = $this->input->getInt('order_id', 0);

        $query = $this->_db->getQuery(true);
        $query->select('a.virtuemart_product_id')
            ->from("#__virtuemart_order_items as a")
            ->where("a.virtuemart_order_id = ". (int)$order_id)
            ->order('a.created_on');

        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();

        $products = array();
        $vmProductModel = new VirtueMartModelProduct();

        foreach ($rows as $row) {
            $product = $vmProductModel->getProduct($row->virtuemart_product_id);
            $vmProductModel->addImages($product);
            $imagePath = $product->images[0]->getFullPath();
            if (JFile::exists($imagePath)) {
                $product->imageUrl  = $product->images[0]->getUrl();
            } else {
                $product->imageUrl = 'images/virtuemart/typeless/noimage_810x810.gif';
            }

            $products[] = $product;
        }
        $result->data = $products;

        return $result;
    }


    public function getCustomers()
    {
        $result = new \JResponseJson();

        // Lấy thông tin sản phẩm thuộc VendorID đang đăng nhập
        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);
        $query = $this->_db->getQuery(true);
        $query->select('DISTINCTROW a.virtuemart_user_id, b.virtuemart_userinfo_id, CONCAT_WS(" ", b.first_name, b.last_name) as customer')
            ->from("#__virtuemart_orders as a")
            ->leftJoin('#__virtuemart_userinfos as b ON b.virtuemart_user_id = a.virtuemart_user_id')
            ->where("a.virtuemart_vendor_id = ". (int)$vendor_id)
            ->order('a.created_on');

        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
//echo $query;
        $customers = array();
        foreach ($rows as &$row) {
            $customer = new VirtueMartModelUser();
            $tmp = $customer->getUserAddressList($row->virtuemart_user_id, "ST", $row->virtuemart_userinfo_id);
            if ($tmp) {
                $row->address = $tmp[0]->address_1;
                $row->phone = $tmp[0]->phone_1;
            } else {
                $row->address = "";
                $row->phone = "";
            }
//var_dump($tmp);
        }

        $result->data = $rows;

        return $result;
    }


    public function getCustomerProducts()
    {

        $result = new \JResponseJson();
        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);

        $virtuemar_user_id = $this->input->getInt('customer_id', 0);

        $query = $this->_db->getQuery(true);
        $query->select('a.virtuemart_order_id, a.virtuemart_user_id, a.order_number, a.created_on')
            ->select('b.virtuemart_product_id')
            ->from("#__virtuemart_orders as a")
            ->leftJoin('#__virtuemart_order_items as b ON b.virtuemart_order_id = a.virtuemart_order_id')
            ->where("a.virtuemart_vendor_id = ". (int)$vendor_id)
            ->where("a.virtuemart_user_id = ". (int)$virtuemar_user_id)
            ->order('a.created_on');

        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();

//        $products = array();
        $vmProductModel = new VirtueMartModelProduct();

        foreach ($rows as $row) {
            $product = $vmProductModel->getProduct($row->virtuemart_product_id);
            $vmProductModel->addImages($product);
            $imagePath = $product->images[0]->getFullPath();
            if (JFile::exists($imagePath)) {
                $product->imageUrl  = $product->images[0]->getUrl();
            } else {
                $product->imageUrl = 'images/virtuemart/typeless/noimage_810x810.gif';
            }
            $link = JRoute::_($product->link);
            $product->link = strpos($link, 'http') === false ? rtrim(JUri::root(), "/") . $link : $link;
//            $product->link = rtrim(JUri::root(), "/") . JRoute::_($product->link);
            $row->product = $product;
        }
        $result->data = $rows;

        return $result;
    }

    public function getProduct()
    {
        $result = new \JResponseJson();

        $product_id = $this->input->getInt('product_id', 0);
        if ($product_id <= 0) {
            $result->success = false;
            $result->message =  JText::_("COM_CONGTACVIEN_PRODUCT_NO_EXIST");
            return $result;
        }

        $vmProductModel = new VirtueMartModelProduct();
        $product = $vmProductModel->getProduct($product_id, true, true, false);
        $vmProductModel->addImages($product);

        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);
        if (!$product || $product->virtuemart_vendor_id != $vendor_id) {
            $result->success = false;
            $result->message = JText::_("COM_CONGTACVIEN_PRODUCT_NOT_BELONG_TO_VENDOR");
            return $result;
        }

        $result->data = $product;

        return $result;
    }


    public function uploadImage()
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        $response = new \JResponseJson();
        $id = $this->input->getInt('id', 0);

        if ($id <= 0) {
            // to pass data through iframe you will need to encode all html tags
            $response->success = false;
            $response->message = JText::_('COM_CONGTACVIEN_PRODUCT_IS_EMPTY');
            return $response;
        }

        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);

        $dimension = array(
            "width"=> VmConfig::get('img_width_full', 900),
            'height'=> VmConfig::get('img_height_full', 900),
            "width_thumb" => VmConfig::get('img_width', 300),
            'height_thumb' => VmConfig::get('img_height', 300)
        );

        // use server file belong package
        require_once JPATH_COMPONENT.'/helpers/uploadserver.php';

        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $allowedExtensions = array('bmp', 'jpg', 'jpeg', 'png', 'gif');
        // max file size in bytes
        $sizeLimit = 2 * 1024 * 1024;

        $path = JPATH_ROOT. DS. VmConfig::get('media_product_path', 'images/virtuemart/product/');

        if (!JFolder::exists($path)) {
            JFolder::create($path);
        }

        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);

        $result = $uploader->handleUpload($path, true);

        $filename = $uploader->getFileName();
        $tmp_dest 	= $path. $filename;

        if (isset($result->error)) {
            // to pass data through iframe you will need to encode all html tags
            $response->success = false;
            $response->message = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
            return $response;
        }

        jimport('joomla.image.image');

        $targetPath = sprintf("%d_%d_%s", $id, time(), $filename);
        $image = new JImage;
        $image->loadFile($tmp_dest);
        $image->resize($dimension['width'], $dimension['height'])->toFile($path . $targetPath);

        $resizedFilename = sprintf('%s_%sx%s.%s', JFile::stripExt($targetPath),$dimension['width_thumb'], $dimension['height_thumb'], JFile::getExt($targetPath));

        $image->resize($dimension['width_thumb'], $dimension['height_thumb'])->toFile($path ."resized".DS. $resizedFilename);

        $config = JFactory::getConfig();
        $now = JFactory::getDate('now', $config->get('offset'));

        // delete temporary file
        if (JFile::exists($tmp_dest)) JFile::delete($tmp_dest);

        $tmp =  JImage::getImageFileProperties($path . $targetPath);

        $data = array();
        $data['virtuemart_media_id'] = null;
        $data['virtuemart_vendor_id'] = $vendor_id;
        $data['file_title'] = JFile::stripExt($targetPath);
        $data['file_mimetype'] = $tmp->mime;
        $data['file_type'] = 'product';
        $data['file_url'] = VmConfig::get('media_product_path', 'images/virtuemart/product/'). $targetPath;
        $data['file_url_thumb'] = VmConfig::get('media_product_path', 'images/virtuemart/product/').'resized/'. $resizedFilename;
        $data['shared'] = 0;
        $data['published'] = 1;
        $data['created_on'] = $now->toSql(true);
        $data['created_by'] = $this->user->id;
        $data['modified_on'] = $now->toSql(true);
        $data['modified_by'] = $this->user->id;
        $data['locked_by'] = 0;


        $mediaTable = VmTable::getInstance('medias');
        $mediaTable->bind($data);
        $data = VmMediaHandler::prepareStoreMedia($mediaTable,$data,$data['file_type']); //this does not store the media, it process the actions and prepares data
        if ($mediaTable->bind($data)) {
            if (!$mediaTable->store()) {
                $response->success = false;
                $response->message = $mediaTable->getError();
                $response->data = "";
                return $response;
            } else {
                // add media into product_media
                $query = $this->_db->getQuery(true);
                $query->insert('#__virtuemart_product_medias')
                    ->set('virtuemart_media_id='. (int)$mediaTable->virtuemart_media_id)
                    ->set('virtuemart_product_id='.(int)$id)
                    ;
                $this->_db->setQuery($query);
                $this->_db->execute();
            }
        } else {
            $response->success = false;
            $response->message = $mediaTable->getError();
            $response->data = "";
            return $response;
        }

        $vmProductModel = new VirtueMartModelProduct();
        $product = $vmProductModel->getProduct($id, true, true, false);
        $vmProductModel->addImages($product);
        $response->data = $product->images;

        $response->success = true;
        $response->message = "Upload successful";

        return $response;
    }

    public function deleteImage()
    {
        VmTableXarray::addIncludePath(JPATH_ROOT. '/administrator/components/com_virtuemart/tables');

        $result = new \JResponseJson();
        $product_id = $this->input->json->getInt('id', 0);
        $media_id = $this->input->json->getInt('media_id', 0);

        if ($product_id <= 0) {
            $result->success = false;
            $result->message =  JText::_("COM_CONGTACVIEN_PRODUCT_NO_EXIST");
            return $result;
        }
        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);
        $vmProductModel = new VirtueMartModelProduct();

        $query = $this->_db->getQuery(true);
        $query->delete("#__virtuemart_product_medias")
            ->where("virtuemart_product_id = ". $product_id)
            ->where("virtuemart_media_id = ". $media_id)
            ;
        $this->_db->setQuery($query);


        if ($this->_db->execute()) {
            $query = $this->_db->getQuery(true);
            $query->select('count(id) as ids')->from("#__virtuemart_product_medias")
                ->where("virtuemart_media_id = ". $media_id)
            ;

            $this->_db->setQuery($query);
            $count = (int)$this->_db->loadResult();

            if ($count<=0) {
                // delete file on server
                //
                $query = $this->_db->getQuery(true);
                $query->delete("#__virtuemart_medias")
                    ->where("virtuemart_media_id = ". $media_id)
                    ->where('virtuemart_vendor_id = '. $vendor_id)
                ;
                $this->_db->setQuery($query);
                $this->_db->execute();
            }
        } else {
            $result->success = false;
            $result->message = $this->_db->getError();
            return $result;
        }


        $product = $vmProductModel->getProduct($product_id, true, true, false);
        $vmProductModel->addImages($product);
        $result->data = $product->images;

        return $result;
    }

    public function saveProduct()
    {
        $result = new \JResponseJson();

        $fields = array(
            'id'=>0,
            'virtuemart_product_id'=>0,
            'metadesc'=>'',
            'metakey'=>'',
            'product_in_stock'=>0,
            'product_name'=>'',
            'product_s_desc'=>'',
            'product_desc' => '',
            'prices'=>null
        );

        $data = $this->input->json->getArray($fields);
        $data['product_desc'] = $this->input->json->get('product_desc', "", "RAW");
//var_dump($data);
        $query = $this->_db->getQuery(true);
        $query->update("#__virtuemart_products_vi_vn")
            ->set('product_s_desc = '. $this->_db->quote($data['product_s_desc']))
            ->set('product_desc = '. $this->_db->quote($data['product_desc']))
            ->set('product_name = '. $this->_db->quote($data['product_name']))
            ->set('metadesc = '. $this->_db->quote($data['metadesc']))
            ->set('metakey = '. $this->_db->quote($data['metakey']))
            ->where('virtuemart_product_id='.(int)$data['virtuemart_product_id'])
            ;
        $this->_db->setQuery($query);
        $this->_db->execute();

        $query = $this->_db->getQuery(true);
        $query->update("#__virtuemart_products")
            ->set('product_in_stock = '. (int)$data['product_in_stock'])
            ->where('virtuemart_product_id='.(int)$data['virtuemart_product_id'])
        ;
        $this->_db->setQuery($query);
        $this->_db->execute();

        $query = $this->_db->getQuery(true);
        $query->update("#__virtuemart_product_prices")
            ->set('product_price = '. (float) $data['prices']['product_price'])
            ->set('override = '. (int)$data['prices']['override'])
            ->set('product_override_price = '. (float) $data['prices']['product_override_price'])
            ->where('virtuemart_product_price_id='.(int)$data['prices']['virtuemart_product_price_id'])
        ;
        $this->_db->setQuery($query);
        $this->_db->execute();


        $result->message = 'Save successful';
        return $result;
    }

    public function getVendorInfo()
    {

        $vendor_id = $this->input->getInt('id', 0);
        $model = new VirtueMartModelVendor();
        $vendor = $model->getVendor($vendor_id);
        $model->addImages($vendor);

        return $vendor;
    }


    public function getFrontVendorProducts()
    {
        $result = new \JResponseJson();

        $vendor_id = $this->input->getInt('vendor_id', 0);

        if ($vendor_id <= 0) {
            $result->data = array();
            $result->message = JText::_("COM_CONGTACVIEN_VENDOR_EMPTY");
            $result->success = false;
            return $result;
        }

        // get category list
        $query = $this->_db->getQuery(true);
        $query->select('b.virtuemart_category_id as id, b.category_name as text, count(a.virtuemart_product_id) as total')
            ->from("#__virtuemart_products as a")
            ->leftJoin('#__virtuemart_product_categories as c ON c.virtuemart_product_id = a.virtuemart_product_id')
            ->leftJoin("#__virtuemart_categories_vi_vn as b ON b.virtuemart_category_id = c.virtuemart_category_id")
            ->where("a.virtuemart_vendor_id = ". (int)$vendor_id)
            ->where('a.published = 1')
            ->group('b.virtuemart_category_id');
        $this->_db->setQuery($query);
        $result->categories = $this->_db->loadObjectList();

        $catid = $this->input->getInt('catid', 0);
        $searchString = $this->input->getString('search', "");

        $query = $this->_db->getQuery(true);
        $query->select('DISTINCT a.virtuemart_product_id')
            ->from("#__virtuemart_products as a")
            ->leftJoin("#__virtuemart_products_vi_vn as b ON b.virtuemart_product_id = a.virtuemart_product_id")
            ->where("a.virtuemart_vendor_id = ". (int)$vendor_id)
            ->where('a.published = 1');

        $fieldsWhere = array();

        if ($searchString != "") {
            $fieldsWhere[] = "a.product_sku = ". $this->_db->quote($searchString);
            $fieldsWhere[] =  "b.product_name LIKE ". $this->_db->quote("%".$searchString."%");
            $fieldsWhere[] =  "b.product_s_desc LIKE ". $this->_db->quote("%".$searchString."%");
            $fieldsWhere[] =  "b.product_desc LIKE ". $this->_db->quote("%".$searchString."%");
            $fieldsWhere[] =  "b.customtitle LIKE ". $this->_db->quote("%".$searchString."%");
            $query->extendWhere('AND', $fieldsWhere, "OR");
        }

        if ($catid > 0) {
            $query->leftJoin('#__virtuemart_product_categories as c ON c.virtuemart_product_id = a.virtuemart_product_id')
            ->where('c.virtuemart_category_id = ' . $catid);
        }
//echo $query;
//        die;

        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();

        $vendor_product_ids = array();
        foreach ($rows as $row) {
            $vendor_product_ids[] = $row->virtuemart_product_id;
        }

        $vmProductModel = new VirtueMartModelProduct();

        $products = $vmProductModel->getProducts ($vendor_product_ids, true, true, false, false);

        $result->data = [];

        if (count($products)) {
            $vmProductModel->addImages($products);
        }
//        var_dump( $products);
        $currency = CurrencyDisplay::getInstance( );

        foreach ($products as $product) {

            $objProduct = new JObject();
            $objProduct->virtuemart_product_id  = $product->virtuemart_product_id;
            $objProduct->virtuemart_vendor_id   = $product->virtuemart_vendor_id;
            $objProduct->product_sku            = $product->product_sku;
            $objProduct->product_name           = $product->product_name;
            $objProduct->product_desc           = $product->product_s_desc;
            $objProduct->category_name          = $product->category_name;
            $objProduct->link                   = JRoute::_($product->link) ;
            $objProduct->prices                 = $product->prices;
            $objProduct->priceFormatted         = $currency->createPriceDiv ('salesPrice', '', $product->prices, true, false, 1.0, TRUE);
            $objProduct->published              = (int)$product->published;

            $imagePath = $product->images[0]->getFullPath();
            if (JFile::exists($imagePath)) {
                $objProduct->imageUrl               = $product->images[0]->getUrl();
            } else {
                $objProduct->imageUrl = 'images/virtuemart/typeless/noimage_810x810.gif';
            }

            $result->data[] = $objProduct;
        }

        return $result;
    }

    public function getVendorConfig()
    {
        $result = new \JResponseJson();

        $asset		= 'com_congtacvien';
        $this->authorise['admin']  = $this->user->authorise('config.admin', $asset);
        $this->authorise['edit']  = $this->user->authorise('config.edit', $asset);
        $this->authorise['editown']  = $this->user->authorise('config.editown', $asset);
        $this->authorise['view'] = $this->user->authorise('config.view', $asset);

        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);

        if ($vendor_id <= 0) {
            $result->data = array();
            $result->message = JText::_("COM_CONGTACVIEN_VENDOR_EMPTY");
            $result->success = false;
            return $result;
        }

        if (!$this->authorise['view']) {
            $result->data = array();
            $result->message = JText::_("COM_CONGTACVIEN_USER_NOT_PERMITTED");
            $result->success = false;
            return $result;
        }

        $query = $this->_db->getQuery(true);
        $query->select('a.virtuemart_vendor_id as vendor_id, a.vendor_store_name, a.vendor_store_desc, a.vendor_phone, a.customtitle')
            ->select('a.vendor_mail_free1 as vendor_email, a.metadesc, a.metakey')
            ->from('#__virtuemart_vendors_vi_vn as a')
            ->innerJoin('#__virtuemart_vendors as b ON b.virtuemart_vendor_id = a.virtuemart_vendor_id')
            ->where('a.virtuemart_vendor_id = ' . $vendor_id);
        $this->_db->setQuery($query);

        $result->data= $this->_db->loadObject();

        return $result;
    }

    public function saveVendorConfig()
    {
        $result = new JsonResponse();

        $asset		= 'com_congtacvien';
        $this->authorise['admin']  = $this->user->authorise('config.admin', $asset);
        $this->authorise['edit']  = $this->user->authorise('config.edit', $asset);
        $this->authorise['editown']  = $this->user->authorise('config.editown', $asset);
        $this->authorise['view'] = $this->user->authorise('config.view', $asset);

        $vendor_id = VirtueMartModelVendor::getVendorId('user', $this->user->id);

        if ($vendor_id <= 0) {
            $result->data = array();
            $result->message = JText::_("COM_CONGTACVIEN_VENDOR_EMPTY");
            $result->success = false;
            return $result;
        }

        if (!$this->authorise['editown']) {
            $result->data = array();
            $result->message = JText::_("COM_CONGTACVIEN_USER_NOT_PERMITTED");
            $result->success = false;
            return $result;
        }

        $fields = array(
            'vendor_id' => 0,
            'vendor_store_name' => "",
            'vendor_store_desc' => "",
            'customtitle' => "",
            'vendor_phone' => "",
            'vendor_email' => "",
            'metadesc' => "",
            'metakey' => ""
        );

        $data = $this->input->json->getArray($fields);
        $data['vendor_id'] = (int)$data['vendor_id'];
        $data['vendor_store_desc'] = $this->input->json->get('vendor_store_desc', '', 'RAW');
        $data['metadesc'] = preg_replace("/[\\n\\r]+/", " ", $data['metadesc']);

//        var_dump($data);

        if ($vendor_id != $data['vendor_id']) {
            $result->message = JText::_("COM_CONGTACVIEN_INVALID_VENDOR");
            $result->success = false;
            return $result;
        }

        $query = $this->_db->getQuery(true);
        $query->update("#__virtuemart_vendors_vi_vn")
            ->set('vendor_store_name = '. $this->_db->quote($data['vendor_store_name']))
            ->set('vendor_store_desc = '. $this->_db->quote($data['vendor_store_desc']))
            ->set('vendor_phone = '. $this->_db->quote($data['vendor_phone']))
            ->set('customtitle = '. $this->_db->quote($data['customtitle']))
            ->set('vendor_mail_free1 = '. $this->_db->quote($data['vendor_email']))
            ->set('metadesc = '. $this->_db->quote($data['metadesc']))
            ->set('metakey = '. $this->_db->quote($data['metakey']))
            ->where('virtuemart_vendor_id='.(int)$data['vendor_id'])
        ;
        $this->_db->setQuery($query);
        $this->_db->execute();


        $query = $this->_db->getQuery(true);
        $query->select('a.virtuemart_vendor_id as vendor_id, a.vendor_store_name, a.vendor_store_desc, a.vendor_phone, a.customtitle')
            ->select('a.vendor_mail_free1 as vendor_email, a.metadesc, a.metakey')
            ->from('#__virtuemart_vendors_vi_vn as a')
            ->innerJoin('#__virtuemart_vendors as b ON b.virtuemart_vendor_id = a.virtuemart_vendor_id')
            ->where('a.virtuemart_vendor_id = ' . $data['vendor_id']);
        $this->_db->setQuery($query);

        $result->data= $this->_db->loadObject();

        $result->message = JText::_("COM_CONGTACVIEN_SAVE_SUCCESSFUL");

        return $result;
    }

    public function getKhoHangCategories()
    {
        VmConfig::loadJLang('com_virtuemart', true);
        VmConfig::loadConfig();
        $categoryModel = VmModel::getModel('category');
        $categoryModel->_noLimit = true;
        if (!class_exists('ShopFunctions')) require(VMPATH_ADMIN.DS.'helpers'.DS.'shopfunctions.php');
        return ShopFunctions::categoryListTreeLoop(array(),0,0,array(),"",1, VmConfig::$vmlang);
    }

}
