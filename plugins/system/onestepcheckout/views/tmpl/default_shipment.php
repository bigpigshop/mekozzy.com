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
$configShipment = file_get_contents(JPATH_ADMINISTRATOR.'/components/com_vmmanager/helpers/config/shipment.json');
$configShipment = json_decode($configShipment);
$fontFamily = array(0=>'',1=>"'Lato',sans-serif",2=>"'Open Sans', sans-serif",3=>"'Oswald', sans-serif",
                    4=>"'PT Sans', sans-serif",5=>"'Raleway', sans-serif",6=>"'Roboto', sans-serif");
$configShipment->color->titleTxtFont = $fontFamily[$configShipment->color->titleTxtFont];
$configShipment->color->contentTxtFont = $fontFamily[$configShipment->color->contentTxtFont];
?>
<style>
    .opc-module-content h2.opc-title.shipmentTitle{
        background-color:<?php echo  '#'.$configShipment->color->titleBg ?> !important;
        color: <?php echo  '#'.$configShipment->color->titleTxtCl ?> !important;
        font-size: <?php echo $configShipment->color->titleTxtSize.'px' ?>;
        font-family: <?php echo $configShipment->color->titleTxtFont ?>;
    }
    .opc-module-content form.opc-form#shipmentForm{
        background-color:<?php echo  '#'.$configShipment->color->contentBg ?>;
        font-family: <?php echo $configShipment->color->contentTxtFont ?>;
    }
    .opc-module-content form.opc-form#shipmentForm label,.opc-module-content form.opc-form#shipmentForm h3{
        color: <?php echo  '#'.$configShipment->color->contentTxtCl ?>;
        font-size: <?php echo $configShipment->color->contentTxtSize.'px' ?>;
    }
</style>
<h2 class="opc-title shipmentTitle">
    <i class="<?php echo $configShipment->color->titleIcon ?>"></i>
    <span><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_SHIPPING_METHOD'); ?></span>
</h2>
<form method="Post" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=cart&opc_task=select_shipment'); ?>" name="shipmentForm" id="shipmentForm" class="opc-form">
    <?php
        if ($this->found_shipment_method 
            && ($configShipment->options->st_zip_required 
                && !empty($this->cart->ST['zip'])
            || !$configShipment->options->st_zip_required)) {
            
            foreach ($this->shipments_shipment_rates as $shipment_shipment_rates) {
    			if (is_array($shipment_shipment_rates)) {
    			    foreach ($shipment_shipment_rates as $shipment_shipment_rate) {
    					echo '<div class="opc-shipment">'.$shipment_shipment_rate.'</div>';
    			    }
    			}
    	    }
         }else{
            echo '<h3>'.JText::_('OPC_SHIPMENT_NOT_FOUND').'</h3>';
         }
     ?>
</form>