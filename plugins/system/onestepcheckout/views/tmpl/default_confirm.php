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
defined ('_JEXEC') or die('Restricted access');
defined('BS') or define('BS',DIRECTORY_SEPARATOR);
if (!class_exists ('VirtueMartModelUserfields')) {
	require(VMPATH_ADMIN . BS . 'models' . BS . 'userfields.php');
}
$userFieldsModel = VmModel::getModel ('userfields');
$userFieldsCart = $userFieldsModel->getUserFields(
	'cart'
	, array('captcha' => true, 'delimiters' => true)
	, array('delimiter_userinfo','user_is_vendor' ,'username','password', 'password2', 'agreed', 'address_type') // Skips
);
$this->userFieldsCart = $userFieldsModel->getUserFieldsFilled(
	$userFieldsCart
	,$this->cart->cartfields
);
$configConfirm = file_get_contents(JPATH_ADMINISTRATOR.'/components/com_vmmanager/helpers/config/confirm.json');
$configConfirm = json_decode($configConfirm);
$fontFamily = array(0=>'',1=>"'Lato',sans-serif",2=>"'Open Sans', sans-serif",3=>"'Oswald', sans-serif",
                    4=>"'PT Sans', sans-serif",5=>"'Raleway', sans-serif",6=>"'Roboto', sans-serif");
$configConfirm->color->titleTxtFont = $fontFamily[$configConfirm->color->titleTxtFont];
$configConfirm->color->contentTxtFont = $fontFamily[$configConfirm->color->contentTxtFont];

$user  = JFactory::getUser();
$only_user = VmConfig::get ('oncheckout_only_registered');
?>
<style>
    .opc-module-content h2.opc-title.checkoutTitle{
        background-color:<?php echo  '#'.$configConfirm->color->titleBg ?> !important;
        color: <?php echo  '#'.$configConfirm->color->titleTxtCl ?> !important;
        font-size: <?php echo $configConfirm->color->titleTxtSize.'px' ?>;
        font-family: <?php echo $configConfirm->color->titleTxtFont ?>;
    }
    .opc-module-content form.opc-form#checkoutForm{
        /*background-color:*/<?php //echo  '#'.$configConfirm->color->contentBg ?>/*;*/
        color: <?php echo  '#'.$configConfirm->color->contentTxtCl ?>;
        font-size: <?php echo $configConfirm->color->contentTxtSize.'px' ?>;
        font-family: <?php echo $configConfirm->color->contentTxtFont ?>;
        padding-top: 150px;
    }
    <?php if(!$configConfirm->options->showtitle): ?>
    #opc-wapper > .opc-module#opc-confirm > .opc-module-content{border: 0;}
    .opc-module-content form.opc-form#checkoutForm{
        padding: 10px 0;
        padding-top: 60px;
    }
    <?php endif; ?>
</style>
<?php if($configConfirm->options->showtitle): ?>
<h2 class="opc-title checkoutTitle">
    <i class="<?php echo $configConfirm->color->titleIcon ?>"></i>
    <span><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_CONFIRM_ORDER'); ?></span>
</h2>
<?php endif; ?>
<form method="post" id="checkoutForm" name="checkoutForm" action="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=cart', $this->useXHTML, $this->useSSL); ?>" class="opc-form">
    <?php
    $closeDelimiter = false;
$openTable = true;
$hiddenFields = '';

if(!empty($this->userFieldsCart['fields'])) {
	// Output: Userfields
	foreach($this->userFieldsCart['fields'] as $field) {
	?>
		<?php
		if ($field['hidden'] == true) {
			$hiddenFields .= $field['formcode'] . "\n";
		} else {

            ?>
                <div class="customer_note <?php echo str_replace('_','-',$field['name']) ?>">
                    <span class="<?php echo str_replace('_','-',$field['name']) ?>" ><?php echo $field['title'] ?></span>
                    <?php echo $field['formcode']; ?>
                </div>
            <?php 
			
        }
	}

	echo $hiddenFields;
    }
    ?>
    <div id='opc-submit-button' name='opc-submit-button' style="display: none;">
        <?= $this->checkout_link_html ?>
    </div>
    <?php if($only_user && $user->guest){ ?>
    <div class="submit_order"><?php echo JText::_('OPC_ONLY_USER_CHECKOUT') ?></div> 
    <?php }else{ ?>
    <div class="submit_order"><span id="submit_order_done"><?php echo JText::_('OPC_COMFIRM_ORDER') ?></span></div> 
    <?php } ?>
    <input type='hidden' name='order_language' value='<?php echo $this->order_language; ?>'/>
    <input type='hidden' name='task' value='updatecart'/>
    <input type='hidden' name='option' value='com_virtuemart'/>
    <input type='hidden' name='view' value='cart'/>
    <input type='hidden' id="STsameAsBT" name='STsameAsBT' value='<?php echo $this->cart->STsameAsBT; ?>'/>
    <input type="hidden" value="1" name="confirm"/>
</form>