<?php
/**
 * @package    ThuVien
 * @subpackage C:
 * @author     Hau Pham {@link }
 * @author     Created on 20-Jul-2017
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

JHtml::_('jquery.framework');

/**
 * HTML View class for the ThuVien Component.
 *
 * @package ThuVien
 */
class CTVViewDashboard extends JViewLegacy
{
    protected $params;
    protected $form;
    protected $item;
    protected $return_page;
    protected $app;
    protected $user;
    protected $input;
    protected $authorise;

    function __construct($config = array())
    {
        $this->input = JFactory::getApplication()->input;
        $this->params = JComponentHelper::getParams('com_congtacvien');
        $this->app = JFactory::getApplication();
        $this->user		= JFactory::getUser();

        $asset		= 'com_congtacvien';
        $this->authorise['admin']  = $this->user->authorise('products.admin', $asset);
        $this->authorise['edit']  = $this->user->authorise('products.edit', $asset);
        $this->authorise['editown']  = $this->user->authorise('products.editown', $asset);
        $this->authorise['view'] = $this->user->authorise('products.view', $asset);
        $this->authorise['statistic'] = $this->user->authorise('products.statistic', $asset);

        if (!$this->authorise['view']) {
//            $this->app->enqueueMessage(JText::_("COM_CONGTACVIEN_USER_NOT_LOGIN"));
//            $this->app->redirect(JRoute::_("/index.php?option=com_users&view=login"));
        }


        parent::__construct($config);
    }

    /**
     * ThuVien view display method.
     *
     * @param string $tpl The name of the template file to parse;
     *
     * @return void
     */
    public function display($tpl = null)
    {
        $layout = $this->getLayout();

        switch ($layout) {
            case 'thuong':
                self::_layoutThuong($tpl);
                return;
            case 'mucluong':
                self::_layoutMucLuong($tpl);
                return;
            default:
                self::_layoutDefault($tpl);
                return;
        }

        parent::display($tpl);
    }

    private function _layoutDefault($tpl)
    {
        $asset		= 'com_congtacvien';
        $this->authorise['admin']  = $this->user->authorise('dashboard.admin', $asset);
        $this->authorise['edit']  = $this->user->authorise('dashboard.edit', $asset);
        $this->authorise['editown']  = $this->user->authorise('dashboard.editown', $asset);
        $this->authorise['view'] = $this->user->authorise('dashboard.view', $asset);
        $this->authorise['statistic'] = $this->user->authorise('dashboard.statistic', $asset);

        if (!$this->authorise['view']) {
            $this->app->enqueueMessage(JText::_("COM_CONGTACVIEN_USER_NOT_LOGIN"));
            $this->app->redirect(JRoute::_("/index.php?option=com_users&view=login"));
        }

        JHtml::_('jquery.framework');
//        JHtml::_('jquery.ui');

//        JHtml::stylesheet("com_congtacvien/material-dashboard.min.css", array(), true);
        $this->document->addStyleSheet("//fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons", array('relative' => 'stylesheet'), array('type'=>'text/css'));
        $this->document->addStyleSheet("//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/3.0.0/toaster.min.css", array('relative' => 'stylesheet'));
//        $this->document->addStyleSheet(JURI::root(true)."/media/com_congtacvien/js/chartist/chartist.min.css", array('relative' => 'stylesheet'));


        $this->document->addScript("//ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js");
        $this->document->addScript("//code.angularjs.org/1.4.2/angular-animate.min.js");
        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/2.1.0/toaster.js");

        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/ui-bootstrap-tpls-3.0.6.min.js");
        $this->document->addScript("//cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/angular-chart-js/angular-chart.min.js");



//        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js");
//
        $this->document->setTitle('Dashboard');

        $model = $this->getModel();

        $this->vendor = $model->getVendorInfo();

        parent::display($tpl);
    }

    private function _layoutThuong($tpl)
    {
        $asset		= 'com_congtacvien';
        $this->authorise['admin']  = $this->user->authorise('thuong.admin', $asset);
        $this->authorise['edit']  = $this->user->authorise('thuong.edit', $asset);
        $this->authorise['editown']  = $this->user->authorise('thuong.editown', $asset);
        $this->authorise['view'] = $this->user->authorise('thuong.view', $asset);
        $this->authorise['statistic'] = $this->user->authorise('thuong.statistic', $asset);

        if (!$this->authorise['view']) {
            $this->app->enqueueMessage(JText::_("COM_CONGTACVIEN_USER_NOT_LOGIN"));
            $this->app->redirect(JRoute::_("/index.php?option=com_users&view=login"));
        }

        JHtml::_('jquery.framework');

        JHtml::stylesheet("com_congtacvien/bootstrap.min.css", array(), true);
//        JHtml::stylesheet("com_congtacvien/material-dashboard.min.css", array(), true);
        $this->document->addStyleSheet("//fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons", array('relative' => 'stylesheet'), array('type'=>'text/css'));
        $this->document->addStyleSheet("//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/3.0.0/toaster.min.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet(JURI::root(true)."/media/com_congtacvien/js/jqgrid/ui.jqgrid-bootstrap.css", array('relative' => 'stylesheet'));


//        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/plugins/perfect-scrollbar.jquery.min.js");

        $this->document->addScript("//kit.fontawesome.com/996e6b779a.js", array(), array('crossorigin'=>'anonymous'));
        $this->document->addScript("//ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js");
        $this->document->addScript("//code.angularjs.org/1.4.2/angular-animate.min.js");
        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/2.1.0/toaster.js");
//        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/popper.min.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/ui-bootstrap-tpls-3.0.6.min.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/angular-mask.min.js");

        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/jqgrid/grid.locale-en.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/jqgrid/grid.locale-vi.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/jqgrid/jquery.jqGrid.min.js");
        $this->document->addScriptDeclaration("jQuery.jgrid.no_legacy_api = true;");


        $this->document->setTitle('Cấu hình thưởng');

        $model = $this->getModel();

        $this->lists = $this->_getListDataThuong();

        parent::display($tpl);
    }

    private function _getListDataThuong()
    {
        $lists = array();


        $db = JFactory::getDbo();

        $tmp = null;
        $query = $db->getQuery(true);
        $query->select('a.virtuemart_state_id as id, a.state_name as text')
            ->from('#__virtuemart_states as a')
            ->leftJoin('#__virtuemart_countries as b ON b.virtuemart_country_id = a.virtuemart_country_id')
            ->where('a.virtuemart_vendor_id = 1')
            ->where('b.country_3_code = "VNM"')
            ;
        $db->setQuery($query);
        $tmp = $db->loadObjectList();

        $types = array();
        $types[] = ":";
		foreach ($tmp as $type) {
            $types[] = $type->id .":" . $type->text;
        }
        $lists['province_json'] = implode(";", $types);


        $query = $db->getQuery(true);
        $query->select('a.id as id, a.title as text')
            ->from('#__congtacvien_luong_profile as a')
            ->where('a.published = 1')
            ->order('a.ordering')
        ;
        $db->setQuery($query);
        $lists['profile'] = $db->loadObjectList();

        return $lists;
    }

    private function _layoutMucLuong($tpl)
    {
        $asset		= 'com_congtacvien';
        $this->authorise['admin']  = $this->user->authorise('thuong.admin', $asset);
        $this->authorise['edit']  = $this->user->authorise('thuong.edit', $asset);
        $this->authorise['editown']  = $this->user->authorise('thuong.editown', $asset);
        $this->authorise['view'] = $this->user->authorise('thuong.view', $asset);
        $this->authorise['statistic'] = $this->user->authorise('thuong.statistic', $asset);

        if (!$this->authorise['view']) {
            $this->app->enqueueMessage(JText::_("COM_CONGTACVIEN_USER_NOT_LOGIN"));
            $this->app->redirect(JRoute::_("/index.php?option=com_users&view=login"));
        }

        JHtml::_('jquery.framework');

        JHtml::stylesheet("com_congtacvien/bootstrap.min.css", array(), true);
//        JHtml::stylesheet("com_congtacvien/material-dashboard.min.css", array(), true);
        $this->document->addStyleSheet("//fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons", array('relative' => 'stylesheet'), array('type'=>'text/css'));
        $this->document->addStyleSheet("//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/3.0.0/toaster.min.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet(JURI::root(true)."/media/com_congtacvien/js/jqgrid/ui.jqgrid-bootstrap.css", array('relative' => 'stylesheet'));


//        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/plugins/perfect-scrollbar.jquery.min.js");

        $this->document->addScript("//kit.fontawesome.com/996e6b779a.js", array(), array('crossorigin'=>'anonymous'));
        $this->document->addScript("//ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js");
        $this->document->addScript("//code.angularjs.org/1.4.2/angular-animate.min.js");
        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/2.1.0/toaster.js");
//        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/popper.min.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/ui-bootstrap-tpls-3.0.6.min.js");

        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/jqgrid/grid.locale-en.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/jqgrid/grid.locale-vi.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/jqgrid/jquery.jqGrid.min.js");
        $this->document->addScriptDeclaration("jQuery.jgrid.no_legacy_api = true;");


        $this->document->setTitle('Cấu hình thưởng');

        $model = $this->getModel();

        $this->lists = $this->_getListDataMucLuong();

        parent::display($tpl);
    }

    private function _getListDataMucLuong()
    {
        $lists = array();


        $db = JFactory::getDbo();

        $tmp = null;
        $query = $db->getQuery(true);
        $query->select('a.id, a.title as text')
            ->from('#__congtacvien_luong_profile as a')
            ->order('a.ordering')
        ;
        $db->setQuery($query);
        $tmp = $db->loadObjectList();

        $types = array();
        $types[] = ":";
        foreach ($tmp as $type) {
            $types[] = $type->id .":" . $type->text;
        }
        $lists['profile_json'] = implode(";", $types);

        return $lists;
    }
}