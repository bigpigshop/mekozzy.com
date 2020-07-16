<?php
/**
 * @package Sj Quick View for VirtueMart
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

class plgSystemPlg_Sj_Vm_QuickView extends JPlugin
{
    protected $app = array();
    protected $_params = null;

    function __construct($subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
        $this->app = JFactory::getApplication();
        $this->_params = $this->params;

    }

    function onBeforeRender()
    {
        if ($this->app->isAdmin()) return;
        $app = JFactory::getApplication();
        $option = $app->input->get('option');
        $view = $app->input->get('view');
        $tmpl = $app->input->get('tmpl');
        $document = JFactory::getDocument();
        if ($app->isSite() && $tmpl != 'component') {
            if (!defined('SMART_JQUERY') && ( int )$this->params->get('include_jquery', '1')) {
                $document->addScript(JURI::root(true) . '/plugins/system/plg_sj_vm_quickview/assets/js/jquery-1.8.2.min.js');
                $document->addScript(JURI::root(true) . '/plugins/system/plg_sj_vm_quickview/assets/js/jquery-noconflict.js');
                define('SMART_JQUERY', 1);
            }
            if (!class_exists ('VmConfig')) {
                require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');
            }

            $app = JFactory::getApplication();
            $option = $app->input->get('option');
            $view = $app->input->get('view');

            if(class_exists('vmJsApi') && JVERSION >= 3){
                vmJsApi::jPrice();
            }else{
                if( $option != 'com_virtuemart' && $view != 'category'){
                    vmJsApi::css('vm-ltr-site');
                    vmJsApi::css('vm-ltr-common');
                    vmJsApi::css('jquery.fancybox-1.3.4');
                    $document->addScript(JURI::root(true) . '/components/com_virtuemart/assets/js/vmprices.js');
                    $document->addScript(JURI::root(true) . '/components/com_virtuemart/assets/js/fancybox/jquery.fancybox-1.3.4.pack.js');
                }
            }
            $document->addScript(JURI::root(true) . '/plugins/system/plg_sj_vm_quickview/assets/js/jquery.fancybox.js');
            $document->addStyleSheet(JURI::root(true) . '/plugins/system/plg_sj_vm_quickview/assets/css/jquery.fancybox.css');
            $document->addStyleSheet(JURI::root(true) . '/plugins/system/plg_sj_vm_quickview/assets/css/quickview.css');

        }
        return true;
    }

    public function onAfterRender()
    {

        if ($this->app->isAdmin()) return;
        $app = JFactory::getApplication();
        $option = $app->input->get('option');
        $view = $app->input->get('view');
        $tmpl = $app->input->get('tmpl');
        $body = JResponse::GetBody();

        if ($app->isSite() && $tmpl != 'component') {
            $_cls = explode(',', $this->_params->get('item_class'));
            if(empty($_cls)) return;
            $cls = array();
            for($i = 0 ; $i < count($_cls) ; $i++) {
                $cls[] = trim($_cls[$i]);
            }

            $cls_str = implode(', ', $cls);
            $body = str_replace('</body>', $this->_addScriptQV($cls_str) . '</body>', $body);
            JResponse::setBody($body);
            return true;
        }

        $is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        $is_ajax_qv = (int)JRequest::getVar('isajax_qv', 0);
        if ($is_ajax && $is_ajax_qv) {
            $body = JResponse::GetBody();
            preg_match("~<body.*?>(.*?)<\/body>~is", $body, $match);
            echo '<div id="sj_quickview">'.$match[1].'</div>';
            die;
          
        }

    }

    function onAfterDispatch(){

    }

    protected function _addScriptQV($cls_str)
    {
        ob_start();

        ?>
        <script type="text/javascript">
            //<![CDATA[

            if(typeof  Virtuemart !== 'undefined')
            {
                Virtuemart.updateImageEventListeners = function() {};
                Virtuemart.updateDynamicUpdateListeners  = function() {};
            }

            jQuery(document).ready(function ($) {
                function _SJQuickView(){
                    var $item_class = $('<?php echo $cls_str; ?>');
                    if ($item_class.length > 0) {
                        for (var i = 0; i < $item_class.length; i++) {
                            if($($item_class[i]).find('.sj_quickview_handler').length <= 0){
                                var producturlpath = $($item_class[i]).find('a', $(this)).attr('href');
                                if(typeof producturlpath !== 'undefined' && producturlpath.length > 0 ){
                                    producturlpath = ( producturlpath.indexOf('?')  >= 0 ) ? producturlpath + '&tmpl=component' : producturlpath + '?tmpl=component' ;
                                    var _quickviewbutton = "<div class='button-group so-quickview'><a class='sj_quickview_handler' href='" + producturlpath + "'></a></div>";
                                    $($item_class[i]).append(_quickviewbutton);
                                }
                            }
                        }
                    }
                }
                $('.sj_quickview_handler')._fancybox({
                    width: '<?php echo $this->_params->get('popup_width');?>',
                    height: '<?php echo $this->_params->get('popup_height');?>',
                    autoSize:  <?php echo $this->_params->get('auto_size');?>,
                    scrolling: 'auto',
                    type: 'ajax',
                    openEffect: '<?php echo $this->_params->get('open_effect'); ;?>',
                    closeEffect: '<?php echo $this->_params->get('close_effect'); ?>',
                   
                    beforeShow: function (){

                        var $_price_on_qv = $('#sj_quickview').find(".product-price"),
                            _id_price = $_price_on_qv.attr('id') ;
                        $_price_on_qv.addClass('price-on-qv');
                        $('.product-price').each(function(){
                            var $this = $(this);
                            if(!$this.hasClass('price-on-qv')){
                                if($this.attr('id') == _id_price){
                                    $this.attr('data-idprice',_id_price);
                                    $this.attr('id',_id_price+'_clone');
                                }
                            }
                        });

                    },
                    afterShow: function () {

                        if(typeof  Virtuemart !== 'undefined'){
                            var $_form = $("form.product",$('#sj_quickview'));
                            Virtuemart.product($_form);
                            $("form.js-recalculate").each(function(){
                                var _cart = $(this);
                                if ($(this).find(".product-fields").length && !$(this).find(".no-vm-bind").length) {
                                    var id= $(this).find('input[name="virtuemart_product_id[]"]').val();
                                    Virtuemart.setproducttype($(this),id);
                                }
                            });
                        }
                    },
                    afterClose: function (){
                        $('.product-price').each(function(){
                            var $this = $(this), _id_price = $this.attr('data-idprice') ;
                            if($this.attr('data-idprice') != '') {
                                $this.removeAttr('data-idprice');
                                $this.attr('id',_id_price);
                            }
                        });
                    }
                });
                setInterval(function(){ _SJQuickView(); } ,1000);
            });
            //]]>
        </script>
        <?php
        $jq = ob_get_contents();
        ob_end_clean();
        return $jq;
    }
}
