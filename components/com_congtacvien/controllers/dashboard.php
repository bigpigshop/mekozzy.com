<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class CTVControllerDashboard extends JControllerLegacy
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


    public function listVendor()
    {
        // Get/Create the model
        $model = $this->getModel('Dashboard');
        $result = $model->listVendor();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }


    public function listMucluongProfile()
    {
        // Get/Create the model
        $model = $this->getModel('Dashboard');
        $result = $model->listMucluongProfile();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function processlistMucluongProfile()
    {
        // Get/Create the model
        $model = $this->getModel('Dashboard');
        $result = $model->processlistMucluongProfile();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function listLuongNguong()
    {
        // Get/Create the model
        $model = $this->getModel('Dashboard');
        $result = $model->listLuongNguong();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function processlistLuongNguong()
    {
        // Get/Create the model
        $model = $this->getModel('Dashboard');
        $result = $model->processlistLuongNguong();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function getVendorConfig()
    {
        // Get/Create the model
        $model = $this->getModel('Dashboard');
        $result = $model->getVendorConfig();
        echo json_encode($result, JSON_NUMERIC_CHECK);
        JFactory::getApplication()->close();
    }

    public function saveVendorConfig()
    {
        $model = $this->getModel('Dashboard');
        $result = $model->saveVendorConfig();
        echo json_encode($result, JSON_NUMERIC_CHECK);
        JFactory::getApplication()->close();
    }

    public function listVendorSale()
    {
        // Get/Create the model
        $model = $this->getModel('Dashboard');
        $result = $model->listVendorSale();
        echo json_encode($result, JSON_NUMERIC_CHECK);
        JFactory::getApplication()->close();
    }

    public function getSchemeBonus()
    {
        $model = $this->getModel('Dashboard');
        $result = $model->getSchemeBonus();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function getSalaryByVendor()
    {
        $model = $this->getModel('Dashboard');
        $result = $model->getSalaryByVendor();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function getAnnualData()
    {
        $model = $this->getModel('Dashboard');
        $result = $model->getAnnualDataByVendor();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

    public function getMonthlyData()
    {
        $model = $this->getModel('Dashboard');
        $result = $model->getMonthlyDataByVendor();
        echo json_encode($result);
        JFactory::getApplication()->close();
    }

}