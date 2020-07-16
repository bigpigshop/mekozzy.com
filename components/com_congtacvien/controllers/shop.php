<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\Response\JsonResponse;

jimport('joomla.application.component.controller');

class CTVControllerShop extends JControllerLegacy
{
    protected $user;
    protected $authorise;
    protected $input;

    function __construct($config = array())
    {
        $this->input = JFactory::getApplication()->input;
        $this->user = JFactory::getUser();

        parent::__construct($config);
    }


    public function getInventoryProducts()
    {
        // Get/Create the model
        $model = $this->getModel('Shop');
        $result = $model->getInventoryProducts();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    function khohang()
    {
        $model = $this->getModel('Shop');

        $view = $this->getView('Shop', 'html');
        $view->setLayout('khohang');
        $view->setModel($model, true);
        $view->display();
    }

    public function addProductByVendor()
    {
        // Get/Create the model
        $model = $this->getModel('Shop');
        $result = $model->addProductByVendor();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function removeProductByVendor()
    {
        // Get/Create the model
        $model = $this->getModel('Shop');
        $result = $model->removeProductByVendor();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }


    function products()
    {
        $model = $this->getModel('Shop');

        $view = $this->getView('Shop', 'html');
        $view->setLayout('products');
        $view->setModel($model, true);
        $view->display();
    }

    function orders()
    {
        $model = $this->getModel('Shop');

        $view = $this->getView('Shop', 'html');
        $view->setLayout('orders');
        $view->setModel($model, true);
        $view->display();
    }

    function customers()
    {
        $model = $this->getModel('Shop');

        $view = $this->getView('Shop', 'html');
        $view->setLayout('customers');
        $view->setModel($model, true);
        $view->display();
    }

    public function getVendorProducts()
    {
        // Get/Create the model
        $model = $this->getModel('Shop');
        $result = $model->getVendorProducts();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function removeProductOnStoreByVendor()
    {
        // Get/Create the model
        $model = $this->getModel('Shop');
        $result = $model->removeProductOnStoreByVendor();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function setStateProductByVendor()
    {
        // Get/Create the model
        $model = $this->getModel('Shop');
        $result = $model->setStateProductByVendor();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function getVendorOrders()
    {
        // Get/Create the model
        $model = $this->getModel('Shop');
        $result = $model->getVendorOrders();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function getVendorOrderDetail()
    {
        // Get/Create the model
        $model = $this->getModel('Shop');
        $result = $model->getVendorOrderDetail();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function getCustomers()
    {
        // Get/Create the model
        $model = $this->getModel('Shop');
        $result = $model->getCustomers();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function getCustomerProducts()
    {
        // Get/Create the model
        $model = $this->getModel('Shop');
        $result = $model->getCustomerProducts();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function editProduct()
    {
        JSession::checkToken('GET') or jexit( 'COM_CONGTACVIEN_INVALID_TOKEN' );
        $view = $this->getView('Shop', 'html');
        $model = $this->getModel('Shop');
        $view->setModel($model, true);
        $view->setLayout('editproduct');
        $view->display();
    }

    public function saveProduct()
    {
        // Get/Create the model
        $model = $this->getModel('Shop');
        $result = $model->saveProduct();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function uploadImage()
    {
        // Get/Create the model
        $model = $this->getModel('Shop');
        $result = $model->uploadImage();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function deleteImage()
    {
        // Get/Create the model
        $model = $this->getModel('Shop');
        $result = $model->deleteImage();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    function config()
    {
        $model = $this->getModel('Shop');

        $view = $this->getView('Shop', 'html');
        $view->setLayout('config');
        $view->setModel($model, true);
        $view->display();
    }

    public function getFrontVendorProducts()
    {
// Get/Create the model
        $model = $this->getModel('Shop');
        $result = $model->getFrontVendorProducts();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function getVendorConfig()
    {
// Get/Create the model
        $model = $this->getModel('Shop');
        $result = $model->getVendorConfig();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function saveVendorConfig()
    {
        $result = new JsonResponse();

        if (!JSession::checkToken('GET')) {
            $result->success = false;
            $result->message = JText::_('COM_CONGTACVIEN_INVALID_TOKEN');
        } else {
            $model = $this->getModel('Shop');
            $result = $model->saveVendorConfig();
        }
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

}