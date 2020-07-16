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
$configShipTo = file_get_contents(JPATH_ADMINISTRATOR.'/components/com_vmmanager/helpers/config/shipTo.json');
$configShipTo = json_decode($configShipTo);
$fontFamily = array(0=>'',1=>"'Lato',sans-serif",2=>"'Open Sans', sans-serif",3=>"'Oswald', sans-serif",
                    4=>"'PT Sans', sans-serif",5=>"'Raleway', sans-serif",6=>"'Roboto', sans-serif");
$configShipTo->color->titleTxtFont = $fontFamily[$configShipTo->color->titleTxtFont];
$configShipTo->color->contentTxtFont = $fontFamily[$configShipTo->color->contentTxtFont];

$addressFields = $this->cart->STaddress['fields'];
if (version_compare(JVERSION, '3.6', '>=')){
    $addressFields = $this->userFieldsST['fields'];
}
?>
<style>
    .opc-module-content h2.opc-title.shipToTitle{
        background-color:<?php echo  '#'.$configShipTo->color->titleBg ?> !important;
        color: <?php echo  '#'.$configShipTo->color->titleTxtCl ?> !important;
        font-size: <?php echo $configShipTo->color->titleTxtSize.'px' ?>;
        font-family: <?php echo $configShipTo->color->titleTxtFont ?>;
    }
    .opc-module-content form.opc-form#shipToForm{
        background-color:<?php echo  '#'.$configShipTo->color->contentBg ?>;
        font-family: <?php echo $configShipTo->color->contentTxtFont ?>;
    }
    .opc-module-content form.opc-form#shipToForm label{
        color: <?php echo  '#'.$configShipTo->color->contentTxtCl ?>;
        font-size: <?php echo $configShipTo->color->contentTxtSize.'px' ?>;
    }
</style>
<h2 class="opc-title shipToTitle">
    <i class="<?php echo $configShipTo->color->titleIcon ?>"></i>
    <span><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_SHIPMENT_ADDRESS'); ?></span>
</h2>

<form method="Post" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=cart&opc_task=saveShipTo');?>" name="shipToForm" id="shipToForm" class="opc-form">
    <?php if(!$configShipTo->options->disable_same_billto){ ?>
    <div class="opc-useShipTo">
        <input class="checkbox-input" id="userShipTo" value="<?php echo $this->cart->STsameAsBT; ?>" type="checkbox" name="userShipTo" <?php if($this->cart->STsameAsBT>0) echo 'checked'; ?> />    
        <label class="checkbox-label" for="userShipTo"><?php echo JText::_('OPC_USE_SHIP_TO') ?></label>
    </div>
    <?php }else{ $this->cart->STsameAsBT = 0; } ?>
    <ul class="opc-listShipTo">
        <?php foreach($addressFields as $key=>$fields) : ?>
            <li>
                <label for="<?php echo $fields['name'].'_field' ?>" class="title-field"><?php echo $fields['title'].($fields['required'] ? ' *' : '')?></label>
                <?php echo $fields['formcode'] ?>
            </li>
        <?php endforeach; ?>
        <div style="clear: both;"></div>
    </ul>
</form>