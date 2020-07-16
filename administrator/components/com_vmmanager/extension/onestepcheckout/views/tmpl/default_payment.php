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
$configPayment = file_get_contents(JPATH_ADMINISTRATOR.'/components/com_vmmanager/helpers/config/payment.json');
$configPayment = json_decode($configPayment);
$fontFamily = array(0=>'',1=>"'Lato',sans-serif",2=>"'Open Sans', sans-serif",3=>"'Oswald', sans-serif",
                    4=>"'PT Sans', sans-serif",5=>"'Raleway', sans-serif",6=>"'Roboto', sans-serif");
$configPayment->color->titleTxtFont = $fontFamily[$configPayment->color->titleTxtFont];
$configPayment->color->contentTxtFont = $fontFamily[$configPayment->color->contentTxtFont];
?>
<!--[if IE 9]>
<style>
    .opc-payment .vmpayment_cardinfo table tr td{float:left} 
</style>
<![endif]--> 
<style>
    .opc-module-content h2.opc-title.paymentTitle{
        background-color:<?php echo  '#'.$configPayment->color->titleBg ?> !important;
        color: <?php echo  '#'.$configPayment->color->titleTxtCl ?> !important;
        font-size: <?php echo $configPayment->color->titleTxtSize.'px' ?>;
        font-family: <?php echo $configPayment->color->titleTxtFont ?>;
    }
    .opc-module-content form.opc-form#paymentForm{
        background-color:<?php echo  '#'.$configPayment->color->contentBg ?>;
        font-family: <?php echo $configPayment->color->contentTxtFont ?>;
    }
    .opc-module-content form.opc-form#paymentForm label{
        color: <?php echo  '#'.$configPayment->color->contentTxtCl ?>;
        font-size: <?php echo $configPayment->color->contentTxtSize.'px' ?>;
    }
</style>
<h2 class="opc-title paymentTitle">
    <i class="<?php echo $configPayment->color->titleIcon ?>"></i>
    <span><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_PAYMENT_METHOD'); ?></span>
</h2>
<form method="Post" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=cart&opc_task=select_payment'); ?>" name="paymentForm" id="paymentForm" class="opc-form">
    <?php
		if(!empty($this->paymentplugins_payments)){
			foreach ($this->paymentplugins_payments as $paymentplugin_payments) {
				if (is_array($paymentplugin_payments)) {
					foreach ($paymentplugin_payments as $paymentplugin_payment) {
						echo '<div class="opc-payment">'.$paymentplugin_payment.'</div>';
					}
				}
			}
		}else{
			echo '<h3>'.JText::_('OPC_PAYMENT_NOT_FOUND').'</h3>';
		}
     ?>
</form>