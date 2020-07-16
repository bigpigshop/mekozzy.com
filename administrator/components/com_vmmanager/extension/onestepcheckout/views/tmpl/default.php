<?php
/** ------------------------------------------------------------------------
One Page Checkout
author CMSMart Team
copyright: Copyright (c) 2012 http://cmsmart.net. All Rights Reserved.
@license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
Websites: http://cmsmart.net
Email: team@cmsmart.net
Technical Support: Forum - http://cmsmart.net/forum
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
$lang 	= JFactory::getLanguage();
VmConfig::loadJLang('com_virtuemart_shoppers',TRUE);
$lang->load('plg_system_onestepcheckout',JPATH_SITE.'/plugins/system/onestepcheckout'); // added in ver 3.0.x
$vmlang = JFactory::getLanguage();
$lang->load('com_virtuemart', JPATH_SITE.'/components/com_virtuemart');  
require_once('plugins/system/onestepcheckout/helpers/translate.php');
//get plugin params
$data_opc = file_get_contents(JPATH_ADMINISTRATOR.'/components/com_vmmanager/helpers/opc.json');
$data_opc = (array) json_decode($data_opc);
$spam = $this->checkSpam();

$shopfront_link = JRoute::_('index.php?option=com_virtuemart&view=virtuemart');

if(count($spam) >= 8 && $data_opc['blockspam']){
?>
<div class="cart-view" id="cart-view-3cols" data-layout="">
    <div class="cart-title">
        <h1><?php echo JText::_('OPC_SYSTEM_SPAM_MSG');?></h1>
    </div>
</div>
<?php
}else{
?>
<?php if(!$this->cart->products):?>

<div class="cart-view" id="cart-view-3cols" data-layout="">
	<div class="cart-title">
	    <h1><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_CART_TITLE_EMPTY');?></h1>
	</div>
	<div class="cart-empty">
        <?php echo JText::sprintf('SYSTEM_ONESTEPCHECKOUT_CART_TITLE_EMPTY_DESC',$shopfront_link);?>
    </div>
</div>
<?php else:?>
<?php
JHtml::_('behavior.formvalidation');
$layouts = json_decode($data_opc['opc-design']);
//Function
function compare_layout($a, $b){
    $retval = strnatcmp($a->y, $b->y);
    if(!$retval) $retval = strnatcmp($a->x, $b->x);
    return $retval;
}
usort($layouts, 'compare_layout');
vmJsApi::jPrice();
JHtml::_('jquery.framework');

?>
<?php if(!empty($this->cart->cartProductsData)) : ?>
<script id="keepAliveTime_js" type="text/javascript">
//<![CDATA[
    var sessMin = 30;
    var vmAliveUrl = "index.php?option=com_virtuemart&view=virtuemart&task=keepalive";
    var maxlps = "4";
    var minlps = "1";
//]]>
</script>
<?php endif; ?>

<!--Render Layout-->
<div id="header-OPC">
    <h2><?php echo JText::_('OPC_SHOPPING_CART') ?></h2>
    <p><a href="<?php echo $shopfront_link ?>"><span><?php echo JText::_('OPC_CONTINUE_SHOPPING') ?></span><i class="opc-iconright-circled"></i></a></p>
</div>
<?php
// This check to displays the form to change the current shopper
if ($this->allowChangeShopper and !$this->isPdf){
    echo $this->loadTemplate('change_shopper');
}
?>
<div class="text-opc"><p><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_TEXT_CART') ?></p></div>
<div id="opc-wapper" class="loader render_">
    <?php foreach($layouts as $key=>$layout): ?>
        <div class="opc-module" id="<?php echo'opc-'.$layout->element;  ?>"
            data-x="<?php echo $layout->x ?>"
            data-y="<?php echo $layout->y ?>"
            data-width="<?php echo $layout->width ?>">
            <div class="opc-module-content">
                <?php 
                if($this->_checkcomdelivery && $layout->element=="delivery"){
                    echo $this->loadTemplate(strtolower($layout->element.'_1')); 
                }else{
                    echo $this->loadTemplate(strtolower($layout->element)); 
                }
                
                ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php
endif;
}







