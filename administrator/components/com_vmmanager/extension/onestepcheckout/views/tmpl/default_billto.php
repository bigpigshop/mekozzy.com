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
JHtml::_ ('behavior.formvalidation');
$userFields = array('username','name','password','password2');
if (count ($this->cart->BTaddress['functions']) > 0) {
	echo '<script language="javascript">' . "\n";
	echo join ("\n", $this->cart->BTaddress['functions']);
	echo '</script>' . "\n";
}

$configBillTo = file_get_contents(JPATH_ADMINISTRATOR.'/components/com_vmmanager/helpers/config/billTo.json');
$configBillTo = json_decode($configBillTo);
$fontFamily = array(0=>'',1=>"'Lato',sans-serif",2=>"'Open Sans', sans-serif",3=>"'Oswald', sans-serif",
                    4=>"'PT Sans', sans-serif",5=>"'Raleway', sans-serif",6=>"'Roboto', sans-serif");
$configBillTo->color->titleTxtFont = $fontFamily[$configBillTo->color->titleTxtFont];
$configBillTo->color->contentTxtFont = $fontFamily[$configBillTo->color->contentTxtFont];
$user  = JFactory::getUser();

$addressFields = $this->cart->BTaddress['fields'];
if (version_compare(JVERSION, '3.6', '>=')){
    $addressFields = $this->userFieldsBT['fields'];
}
?>
<style>
    .opc-module-content h2.opc-title.billToTitle{
        background-color:<?php echo  '#'.$configBillTo->color->titleBg ?> !important;
        color: <?php echo  '#'.$configBillTo->color->titleTxtCl ?> !important;
        font-size: <?php echo $configBillTo->color->titleTxtSize.'px' ?>;
        font-family: <?php echo $configBillTo->color->titleTxtFont ?>;
    }
    .opc-module-content form.opc-form#billToForm{
        background-color:<?php echo  '#'.$configBillTo->color->contentBg ?>;
        font-family: <?php echo $configBillTo->color->contentTxtFont ?>;
    }
    .opc-module-content form.opc-form#billToForm label{
        color: <?php echo  '#'.$configBillTo->color->contentTxtCl ?>;
        font-size: <?php echo $configBillTo->color->contentTxtSize.'px' ?>;
        font-family: <?php echo $configBillTo->color->contentTxtFont ?>;
    }
    .opc-module-content form#billToForm ul li > label i{color: <?php echo  '#'.$configBillTo->color->titleBg ?>;}
</style>
<h2 class="opc-title billToTitle">
    <i class="<?php echo $configBillTo->color->titleIcon ?>"></i>
    <span><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_BILLING_INFO'); ?></span>
</h2>
<form method="Post" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=cart&opc_task=saveBillTo');  ?>" name="billToForm" id="billToForm" class="opc-form">
    <ul>
        <?php foreach($addressFields as $key=>$fields) : ?>
            <?php if(!in_array($key,$userFields) && $fields['formcode']): ?>
                <li>
                    <label for="<?php echo $fields['name'].'_field' ?>" class="title-field"><?php echo $fields['title'].($fields['required'] ? ' <i>*</i>' : '')?></label>
                    <?php echo $fields['formcode'] ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <?php 
        $usersConfig = JComponentHelper::getParams( 'com_users' );
        if($usersConfig->get('allowUserRegistration') == 1){
    ?>
	<div style="clear:both;"></div>
	<?php if(!$user->id && !$configBillTo->options->hide_create_account){ ?>
    <div class="create-account">
        <div class="check-box">
            <input type="checkbox"  name="checkCreateAcount" id="checkCreateAcount" class="checkbox-input"/>
            <label class="checkbox-label" for="checkCreateAcount"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_CREATE_ACCOUNT') ?></label>
        </div>
        <div class="user-info" <?php if($configBillTo->options->show_register):?> style="display: block;" <?php endif; ?>>
            <ul style="overflow: hidden;">
            <?php foreach($addressFields as $key=>$fields) : ?>
                <?php if(in_array($key,$userFields) && $fields['formcode']): ?>
                  <li>
                    <label for="<?php echo $fields['name'].'_field' ?>" class="title-field"><?php echo $fields['title'].($fields['required'] ? ' <i>*</i>' : '')?></label>
                    <?php echo $fields['formcode'] ?>
                  </li>  
                <?php endif; ?>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>	
		<?php } } ?>
    <?php echo JHtml::_ ('form.token'); ?>
</form>

