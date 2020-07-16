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

/**
 * HTML View class for the Congtacvien Component.
 *
 * @package Congtacvien
 */
class CTVViewShop extends JViewLegacy
{

    protected $params;
    protected $form;
    protected $item;
    protected $return_page;
    protected $app;
    protected $user;
    protected $input;
    protected $authorise;
    protected $vendor;
    protected $product;
    protected $categories;

    function __construct($config = array())
    {
        $this->input = JFactory::getApplication()->input;
        $this->params = JComponentHelper::getParams('com_congtacvien');
        $this->app = JFactory::getApplication();
        $this->user		= JFactory::getUser();
        $this->document = JFactory::getDocument();

        $asset		= 'com_congtacvien';
        $this->authorise['admin']  = $this->user->authorise('inventory.admin', $asset);
        $this->authorise['edit']  = $this->user->authorise('inventory.edit', $asset);
        $this->authorise['editown']  = $this->user->authorise('inventory.editown', $asset);
        $this->authorise['view'] = $this->user->authorise('inventory.view', $asset);
        $this->authorise['statistic'] = $this->user->authorise('inventory.statistic', $asset);

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
            case 'khohang':
                self::_layoutKhoHang($tpl);
                return;
            case 'products':
                self::_layoutProducts($tpl);
                return;
            case 'orders':
                self::_layoutOrders($tpl);
                return;
            case 'customers':
                self::_layoutCustomers($tpl);
                return;
            case 'editproduct':
                self::_layoutEditProduct($tpl);
                return;
            case 'config':
                self::_layoutConfig($tpl);
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
        $this->authorise['admin']  = $this->user->authorise('shop.admin', $asset);
        $this->authorise['edit']  = $this->user->authorise('shop.edit', $asset);
        $this->authorise['editown']  = $this->user->authorise('shop.editown', $asset);
        $this->authorise['view'] = $this->user->authorise('shop.view', $asset);
        $this->authorise['statistic'] = $this->user->authorise('shop.statistic', $asset);

        if (!$this->authorise['view']) {
//            $this->app->enqueueMessage(JText::_("COM_CONGTACVIEN_USER_NOT_LOGIN"));
//            $this->app->redirect(JRoute::_("/index.php?option=com_users&view=login"));
        }

        JHtml::_('jquery.framework');

        $this->document->addStyleSheet("//fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons", array('relative' => 'stylesheet'), array('type'=>'text/css'));
        $this->document->addStyleSheet("//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/3.0.0/toaster.min.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet("//cdn.jsdelivr.net/npm/placeholder-loading/dist/css/placeholder-loading.min.css", array('relative' => 'stylesheet'));

        $this->document->addScript("//ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js");
        $this->document->addScript("//code.angularjs.org/1.4.2/angular-animate.min.js");
        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/2.1.0/toaster.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/ui-bootstrap-tpls-3.0.6.min.js");


        $model = $this->getModel();

        $this->vendor = $model->getVendorInfo();

        if ($this->vendor) {
            $title = $this->vendor->customtitle ? $this->vendor->customtitle : $this->document->getTitle();
            $this->document->setTitle($title);

            if ($this->vendor->metaauthor) {
                $this->document->setMetaData('author', $this->vendor->metaauthor);
            }

            if ($this->vendor->metadesc) {
                $this->document->setDescription($this->vendor->metadesc);
            }

            if ($this->vendor->metakey) {
                $this->document->setMetaData('keywords', $this->vendor->metakey);
            }

            $image = JURI::root(). $this->vendor->file_url;

            // FOR FACEBOOK
            $this->document->addCustomTag('<meta property="og:site_name" content="mekozzy.com"/>');
            $this->document->addCustomTag('<meta property="og:rich_attachment" content="true"/>');
            $this->document->addCustomTag('<meta property="og:type" content="website"/>');
            $this->document->addCustomTag('<meta property="og:title" itemprop="url" content="'.$title.'"/>');
            $this->document->addCustomTag('<meta property="og:url" itemprop="url" content="'.JUri::current().'"/>');
            $this->document->addCustomTag('<meta property="og:image" itemprop="thumbnailUrl" content="'.$image.'"/>');
            $this->document->addCustomTag('<meta property="og:image:width" content="800"/>');
            $this->document->addCustomTag('<meta property="og:image:height" content="354"/>');
            $this->document->addCustomTag('<meta property="og:description" content="'.$this->vendor->metadesc.'" itemprop="description" />');

            /*
             * <!-- Twitter Card -->
             */
            $this->document->addCustomTag('<meta name="twitter:card" value="summary"/>');
            $this->document->addCustomTag('<meta name="twitter:site" value="@mekozzy.com"/>');
            $this->document->addCustomTag('<meta name="twitter:creator" value="@mekozzy.com"/>');
            $this->document->addCustomTag('<meta property="twitter:url" itemprop="url" content="'.JUri::current().'"/>');
            $this->document->addCustomTag('<meta property="twitter:title" content="'.$title.'"/>');
            $this->document->addCustomTag('<meta property="twitter:image" itemprop="thumbnailUrl" content="'.$image.'"/>');
            $this->document->addCustomTag('<meta property="twitter:description" content="'.$this->vendor->metadesc.'"/>');


            $this->document->setMetaData('keywords', $this->vendor->metakey);

        }

        parent::display($tpl);
    }

    private function _layoutEditProduct($tpl)
    {
        $asset		= 'com_congtacvien';
        $this->authorise['admin']  = $this->user->authorise('thuong.admin', $asset);
        $this->authorise['edit']  = $this->user->authorise('thuong.edit', $asset);
        $this->authorise['editown']  = $this->user->authorise('thuong.editown', $asset);
        $this->authorise['view'] = $this->user->authorise('thuong.view', $asset);
        $this->authorise['statistic'] = $this->user->authorise('thuong.statistic', $asset);

        if (!$this->authorise['view']) {
//            $this->app->enqueueMessage(JText::_("COM_CONGTACVIEN_USER_NOT_LOGIN"));
//            $this->app->redirect(JRoute::_("/index.php?option=com_users&view=login"));
        }

        JHtml::_('jquery.framework');

        JHtml::stylesheet("com_congtacvien/bootstrap.min.css", array(), true);
//        JHtml::stylesheet("com_congtacvien/material-dashboard.min.css", array(), true);
        $this->document->addStyleSheet("//fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons", array('relative' => 'stylesheet'), array('type'=>'text/css'));
        $this->document->addStyleSheet("//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/3.0.0/toaster.min.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet(JURI::root(true)."/media/com_congtacvien/js/jqgrid/ui.jqgrid-bootstrap.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet("//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.19.1/ui/trumbowyg.min.css");


        $this->document->addScript("//kit.fontawesome.com/996e6b779a.js", array(), array('crossorigin'=>'anonymous'));
        $this->document->addScript("//ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js");
        $this->document->addScript("//code.angularjs.org/1.4.2/angular-animate.min.js");
        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/2.1.0/toaster.js");
//        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/popper.min.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/ui-bootstrap-tpls-3.0.6.min.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/angular-mask.min.js");

        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.19.1/trumbowyg.min.js");

        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.19.1/plugins/pasteimage/trumbowyg.pasteimage.min.js");
        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.19.1/plugins/cleanpaste/trumbowyg.cleanpaste.min.js");
        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.19.1/langs/vi.js");

        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/trumbowyg/angular-trumbowyg.min.js");

        $this->document->addScript(JURI::root(true). '/media/com_congtacvien/js/uploadfile/fileuploader.js');
        $this->document->addStyleSheet(JURI::root(true). '/media/com_congtacvien/js/uploadfile/fileuploader.css');


        $model = $this->getModel();

        $data = $model->getProduct();
        $this->product = $data->data;

        if ($data->success) {
            $this->document->setTitle($this->product->product_name);
        } else {
            $this->document->setTitle('Edit Product');
        }

        parent::display($tpl);
    }


    private function _layoutKhoHang($tpl)
    {
        $asset		= 'com_congtacvien';
        $this->authorise['admin']  = $this->user->authorise('inventory.admin', $asset);
        $this->authorise['edit']  = $this->user->authorise('inventory.edit', $asset);
        $this->authorise['editown']  = $this->user->authorise('inventory.editown', $asset);
        $this->authorise['view'] = $this->user->authorise('inventory.view', $asset);
        $this->authorise['statistic'] = $this->user->authorise('inventory.statistic', $asset);

        if (!$this->authorise['view']) {
//            $this->app->enqueueMessage(JText::_("COM_CONGTACVIEN_USER_NOT_LOGIN"));
//            $this->app->redirect(JRoute::_("/index.php?option=com_users&view=login"));
        }

        JHtml::_('jquery.framework');

//        JHtml::stylesheet("com_congtacvien/material-dashboard.min.css", array(), true);
        $this->document->addStyleSheet("//fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons", array('relative' => 'stylesheet'), array('type'=>'text/css'));
        $this->document->addStyleSheet("//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/3.0.0/toaster.min.css", array('relative' => 'stylesheet'));


//        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/plugins/perfect-scrollbar.jquery.min.js");

        $this->document->addScript("//ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js");
        $this->document->addScript("//code.angularjs.org/1.4.2/angular-animate.min.js");
        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/2.1.0/toaster.js");
//        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/popper.min.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/ui-bootstrap-tpls-3.0.6.min.js");


        $this->document->setTitle('Inventory');

        $this->categories = new stdClass();

        $model = $this->getModel();
        $this->categories = $model->getKhoHangCategories();

        parent::display($tpl);
    }

    private function _layoutProducts($tpl)
    {
        $asset		= 'com_congtacvien';
        $this->authorise['admin']  = $this->user->authorise('inventory.admin', $asset);
        $this->authorise['edit']  = $this->user->authorise('inventory.edit', $asset);
        $this->authorise['editown']  = $this->user->authorise('inventory.editown', $asset);
        $this->authorise['view'] = $this->user->authorise('inventory.view', $asset);
        $this->authorise['statistic'] = $this->user->authorise('inventory.statistic', $asset);

        if (!$this->authorise['view']) {
//            $this->app->enqueueMessage(JText::_("COM_CONGTACVIEN_USER_NOT_LOGIN"));
//            $this->app->redirect(JRoute::_("/index.php?option=com_users&view=login"));
        }

        JHtml::_('jquery.framework');


//        JHtml::stylesheet("com_congtacvien/material-dashboard.min.css", array(), true);
        $this->document->addStyleSheet("//fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons", array('relative' => 'stylesheet'), array('type'=>'text/css'));
        $this->document->addStyleSheet("//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/3.0.0/toaster.min.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet("//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.19.1/ui/trumbowyg.min.css", array('relative' => 'stylesheet'), array('type'=>'text/css'));

//        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/plugins/perfect-scrollbar.jquery.min.js");

        $this->document->addScript("//ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js");
        $this->document->addScript("//code.angularjs.org/1.4.2/angular-animate.min.js");
        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/2.1.0/toaster.js");
//        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/popper.min.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/ui-bootstrap-tpls-3.0.6.min.js");

        $this->document->setTitle('Products');

        $model = $this->getModel();
//        $model->getTestAPI();

        parent::display($tpl);
    }

    private function _layoutOrders($tpl)
    {
        $asset		= 'com_congtacvien';
        $this->authorise['admin']  = $this->user->authorise('order.admin', $asset);
        $this->authorise['edit']  = $this->user->authorise('order.edit', $asset);
        $this->authorise['editown']  = $this->user->authorise('order.editown', $asset);
        $this->authorise['view'] = $this->user->authorise('order.view', $asset);
        $this->authorise['statistic'] = $this->user->authorise('order.statistic', $asset);

        if (!$this->authorise['view']) {
//            $this->app->enqueueMessage(JText::_("COM_CONGTACVIEN_USER_NOT_LOGIN"));
//            $this->app->redirect(JRoute::_("/index.php?option=com_users&view=login"));
        }

        JHtml::_('jquery.framework');

//        JHtml::stylesheet("com_congtacvien/material-dashboard.min.css", array(), true);
        $this->document->addStyleSheet("//fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons", array('relative' => 'stylesheet'), array('type'=>'text/css'));
        $this->document->addStyleSheet("//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/3.0.0/toaster.min.css", array('relative' => 'stylesheet'));


//        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/plugins/perfect-scrollbar.jquery.min.js");

        $this->document->addScript("//ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js");
        $this->document->addScript("//code.angularjs.org/1.4.2/angular-animate.min.js");
        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/2.1.0/toaster.js");
//        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/popper.min.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/ui-bootstrap-tpls-3.0.6.min.js");


        $this->document->setTitle('Orders');

        $model = $this->getModel();
//        $model->getTestAPI();

        parent::display($tpl);
    }

    private function _layoutCustomers($tpl)
    {
        $asset		= 'com_congtacvien';
        $this->authorise['admin']  = $this->user->authorise('customer.admin', $asset);
        $this->authorise['edit']  = $this->user->authorise('customer.edit', $asset);
        $this->authorise['editown']  = $this->user->authorise('customer.editown', $asset);
        $this->authorise['view'] = $this->user->authorise('customer.view', $asset);
        $this->authorise['statistic'] = $this->user->authorise('customer.statistic', $asset);

        if (!$this->authorise['view']) {
//            $this->app->enqueueMessage(JText::_("COM_CONGTACVIEN_USER_NOT_LOGIN"));
//            $this->app->redirect(JRoute::_("/index.php?option=com_users&view=login"));
        }

        JHtml::_('jquery.framework');

//        JHtml::stylesheet("com_congtacvien/material-dashboard.min.css", array(), true);
        $this->document->addStyleSheet("//fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons", array('relative' => 'stylesheet'), array('type'=>'text/css'));
        $this->document->addStyleSheet("//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/3.0.0/toaster.min.css", array('relative' => 'stylesheet'));


//        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/plugins/perfect-scrollbar.jquery.min.js");

        $this->document->addScript("//ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js");
        $this->document->addScript("//code.angularjs.org/1.4.2/angular-animate.min.js");
        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/2.1.0/toaster.js");
//        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/popper.min.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/ui-bootstrap-tpls-3.0.6.min.js");


        $this->document->setTitle('Customers');

        $model = $this->getModel();
//        $model->getTestAPI();

        parent::display($tpl);
    }

    private function _layoutConfig($tpl)
    {
        $asset		= 'com_congtacvien';
        $this->authorise['admin']  = $this->user->authorise('config.admin', $asset);
        $this->authorise['edit']  = $this->user->authorise('config.edit', $asset);
        $this->authorise['editown']  = $this->user->authorise('config.editown', $asset);
        $this->authorise['view'] = $this->user->authorise('config.view', $asset);

        if (!$this->authorise['view']) {
            $this->app->enqueueMessage(JText::_("COM_CONGTACVIEN_USER_NOT_PERMITTED"));
            $this->app->redirect(JRoute::_("/index.php?option=com_users&view=login"));
        }

        JHtml::_('jquery.framework');

//        JHtml::stylesheet("com_congtacvien/material-dashboard.min.css", array(), true);
        $this->document->addStyleSheet("//fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons", array('relative' => 'stylesheet'), array('type'=>'text/css'));
        $this->document->addStyleSheet("//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css", array('relative' => 'stylesheet'));
        $this->document->addStyleSheet("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/3.0.0/toaster.min.css", array('relative' => 'stylesheet'));

        $this->document->addStyleSheet("//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.19.1/ui/trumbowyg.min.css");

        $this->document->addScript("//kit.fontawesome.com/996e6b779a.js", array(), array('crossorigin'=>'anonymous'));
        $this->document->addScript("//ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js");
        $this->document->addScript("//code.angularjs.org/1.4.2/angular-animate.min.js");
        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/2.1.0/toaster.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/ui-bootstrap-tpls-3.0.6.min.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/angular-mask.min.js");

        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.19.1/trumbowyg.min.js");

        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.19.1/plugins/pasteimage/trumbowyg.pasteimage.min.js");
        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.19.1/plugins/cleanpaste/trumbowyg.cleanpaste.min.js");
        $this->document->addScript("//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.19.1/langs/vi.js");
        $this->document->addScript(JURI::root(true)."/media/com_congtacvien/js/trumbowyg/angular-trumbowyg.min.js");

        $this->document->addScript(JURI::root(true). '/media/com_congtacvien/js/uploadfile/fileuploader.js');
        $this->document->addStyleSheet(JURI::root(true). '/media/com_congtacvien/js/uploadfile/fileuploader.css');

        $this->document->setTitle('Configuration');

        $model = $this->getModel();

        parent::display($tpl);
    }

}