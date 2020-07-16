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
$this->cart = VirtueMartCart::getCart();       
$this->cart->prepareCartData();

$configDelivery = file_get_contents(JPATH_ADMINISTRATOR.'/components/com_vmmanager/helpers/config/delivery.json');
$configDelivery = json_decode($configDelivery);
$requiredDeli = '';
if($configDelivery->options->deliRequired){$requiredDeli = 'required';}
$fontFamily = array(0=>'',1=>"'Lato',sans-serif",2=>"'Open Sans', sans-serif",3=>"'Oswald', sans-serif",
                    4=>"'PT Sans', sans-serif",5=>"'Raleway', sans-serif",6=>"'Roboto', sans-serif");
$configDelivery->color->titleTxtFont = $fontFamily[$configDelivery->color->titleTxtFont];
$configDelivery->color->contentTxtFont = $fontFamily[$configDelivery->color->contentTxtFont];

//add new
$cur_country = "";
$cur_city = "";
$post_code = "";
if (is_array($this->cart->ST)) {
    $cur_country = $this->cart->ST['virtuemart_country_id'];
    $cur_city = $this->cart->ST['virtuemart_state_id'];
    $zip = $this->cart->ST['zip'];
} else if (isset($this->cart->BT)) {
    $cur_country = $this->cart->BT['virtuemart_country_id'];
    $cur_city = $this->cart->BT['virtuemart_state_id'];
    $zip = $this->cart->BT['zip'];
}

if (file_exists(JPATH_PLUGINS.BS.'system'.BS.'delivery_date'.BS.'helper.php'))
{
    include_once JPATH_PLUGINS.BS.'system'.BS.'delivery_date'.BS.'helper.php';
}

$reso_date_time = plgDeliverydateHelp::getOptionCity($cur_country, $cur_city, $zip);

$option_time_list_arr = array();
if (!empty($reso_date_time['select_time'])) {
    $option_time_list_arr = explode("\r\n", $reso_date_time['select_time']);
} else if (!empty($time_list)) {
    $option_time_list_arr = explode("\r\n", $time_list);
}

if(isset($reso_date_time['block_date'])){
    $block_date=$reso_date_time['block_date'];
}else{
    $block_date = implode(',',$configDelivery->options->deliHolidays);
}

?>
<style>
    .opc-module-content h2.opc-title.deliveryTitle{
        background-color:<?php echo  '#'.$configDelivery->color->titleBg ?> !important;
        color: <?php echo  '#'.$configDelivery->color->titleTxtCl ?> !important;
        font-size: <?php echo $configDelivery->color->titleTxtSize.'px' ?>;
        font-family: <?php echo $configDelivery->color->titleTxtFont ?>;
    }
    form#deliveryForm .select-date, form#deliveryForm .select-time,
    form#deliveryForm .select-date h3, form#deliveryForm .select-time h3{
        font-family: <?php echo $configDelivery->color->contentTxtFont ?>!important;
        color: <?php echo  '#'.$configDelivery->color->contentTxtCl ?>!important;
        font-size: <?php echo $configDelivery->color->contentTxtSize.'px' ?>!important;
    }
    .opc-module-content form.opc-form#deliveryForm{
        background-color:<?php echo  '#'.$configDelivery->color->contentBg ?>;
        font-family: <?php echo $configDelivery->color->contentTxtFont ?>;
    }
    .ui-datepicker-header,td .ui-state-default,.ui-timepicker-div .ui-widget-header,button.ui-datepicker-close,button.ui-datepicker-current{
        background: <?php echo  '#'.$configDelivery->color->titleBg ?>!important;
        color: <?php echo  '#'.$configDelivery->color->titleTxtCl ?>!important;
        text-align: center!important;
        border-color: <?php echo  '#'.$configDelivery->color->titleBg ?>!important;
    }
    td .ui-state-default.ui-state-highlight{  background: #D5D2D2!important;border-color: #000000;color: #333!important;}
    td .ui-state-default.ui-state-active,td .ui-state-default.ui-state-hover,
    div#ui-datepicker-div{
        background: <?php echo  '#'.$configDelivery->color->contentBg ?>!important;
        color: <?php echo  '#'.$configDelivery->color->contentTxtCl ?>!important;
        border-color:<?php echo  '#'.$configDelivery->color->titleBg ?>;
    }
    form#deliveryForm .select-date h3 i, form#deliveryForm .select-time h3 i{color:<?php echo  '#'.$configDelivery->color->titleBg ?>;}
    a.ui-datepicker-next.ui-corner-all.ui-state-hover.ui-datepicker-next-hover,
    a.ui-datepicker-prev.ui-corner-all.ui-state-hover.ui-datepicker-prev-hover
    {background: transparent;border-color:transparent;cursor: pointer;}
    .ui_tpicker_hour_slider.ui-widget-content,
    .ui_tpicker_minute_slider.ui-widget-content
    {background: <?php echo  '#'.$configDelivery->color->contentBg ?>;
    border-color:<?php echo  '#'.$configDelivery->color->titleBg ?>;}
    .ui_tpicker_hour_slider.ui-widget-content a,.ui_tpicker_minute_slider.ui-widget-content a{
        background: <?php echo  '#'.$configDelivery->color->titleBg ?>;
        border-color:<?php echo  '#'.$configDelivery->color->titleBg ?>;
    }


</style>
<h2 class="opc-title deliveryTitle">
    <i class="<?php echo $configDelivery->color->titleIcon ?>"></i>
    <span><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_DELIVERY'); ?></span>
</h2>
<form method="Post" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=cart&opc_task=loadDelivery');  ?>" name="deliveryForm" id="deliveryForm" class="opc-form">
    <div class="select-date">
        <h3><i class="opc-iconcalendar-2 logo"></i>
            <span><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_DELIVERY_SELECTDATE'); ?></span>
            <?php 
                if($configDelivery->options->deliRequired){
                    echo "<i>*</i>";
                }
            ?>
        </h3>
        <input name="delivery_date" readonly="readonly" type="text" class="datepicker-input <?php echo $requiredDeli ?>" id="date-from" <?php echo $requiredDeli ?> />
        <?php if($configDelivery->options->dateType): ?>
        <span><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_DELIVERY_TO'); ?></span>
        <input name="delivery_todate" readonly="readonly" type="text" class="datepicker-input <?php echo $requiredDeli ?>" id="date-to" <?php echo $requiredDeli ?> />
        <?php endif; ?>
    </div>
    <?php if($configDelivery->options->deliTime): ?>
    <div class="select-time">
        <h3><i class="opc-iconstopwatch logo"></i>
            <span><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_DELIVERY_SELECTTIME'); ?></span>
            <?php 
                if($configDelivery->options->deliRequired){
                    echo "<i>*</i>";
                }
            ?>
        </h3>
        <?php if(isset($reso_date_time['time_type']) && $reso_date_time['time_type']==0): ?>
            <select id="list_delivery_time" class="type_time_list" name="delivery_time">
                <option value="">
                    <?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_SELECT_TIME') ?>
                </option>
                <?php foreach ($option_time_list_arr as $time): ?>
                    <option value="<?php echo $time ?>">
                        <?php echo $time ?>
                    </option>
                <?php endforeach; ?>
            </select>
        
        <?php else: ?>
            <input name="delivery_time" readonly="" type="text" class="timepicker-input <?php echo $requiredDeli ?>" id="time-from" <?php echo $requiredDeli ?> />
            <?php if($configDelivery->options->timeType): ?>
                <span><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_DELIVERY_TO'); ?></span>
                <input name="delivery_totime" readonly="" type="text" class="timepicker-input <?php echo $requiredDeli ?>" id="time-to" <?php echo $requiredDeli ?> />
            <?php endif; ?>
        <?php endif; ?>
        
        
    </div>
    <?php endif; ?>
</form>
<script>
    jQuery(document).ready(function(){
       var blockDate=[<?php echo $block_date; ?>]
       var dateOption = {
                showAnim: "slideDown",
                dateFormat: "<?php echo (!empty($configDelivery->options->dateFormat))? $configDelivery->options->dateFormat:'mm/dd/yy' ?>",
                minDate: '0',
                firstDay: 1,
                changeFirstDay: false,
                beforeShowDay: function(date) {
                    if(blockDate.indexOf(date.getDay()) >= 0){
                        return [false, "Holiday"];
                    }
                    return [true, ""];
                },
                onSelect: function(dateVal){
                    jQuery.cookies.set(jQuery(this).attr('name'),dateVal);
                    if(jQuery(this).attr('name') == 'delivery_todate'){
                        var t = Date.parse(dateVal);
                        var d = Date.parse(jQuery('input[name=delivery_date]').val());
                        if(d>t){
                            alert("date >= " + jQuery('input[name=delivery_date]').val() );
                            jQuery.cookies.del(jQuery(this).attr('name'),dateVal);
                            jQuery(this).val('');
                        }                                  
                    }                   
                }
            };
       var timeOption = {
                showPeriod: true,
                showLeadingZero: true,
                timeOnlyTitle: '<?php echo JText::_('DELIVERY_CHOOSE_TIME'); ?>',
                currentText: '<?php echo JText::_('DELIVERY_NOW'); ?>',
                closeText: '<?php echo JText::_('DELIVERY_DONE'); ?>',
                timeText: '<?php echo JText::_('DELIVERY_TIME'); ?>',
                hourText: '<?php echo JText::_('DELIVERY_HOUR'); ?>',
                minuteText: '<?php echo JText::_('DELIVERY_MINUTE'); ?>',
                onSelect: function(timeVal) {
                     jQuery.cookies.set(jQuery(this).attr('name'),timeVal);
                     
                 }
        };

        jQuery('.datepicker-input').datepicker(dateOption);
        jQuery('.timepicker-input').timepicker(timeOption);
        jQuery('.datepicker-input').each(function(){
             jQuery(this).datepicker( "setDate" ,jQuery.cookies.get(jQuery(this).attr('name'), "") );
        });
        jQuery('.timepicker-input').each(function(){
             jQuery(this).datepicker( "setTime" ,jQuery.cookies.get(jQuery(this).attr('name'), "") );
             jQuery.cookies.del('option_time');
        });
        
        

        jQuery('#list_delivery_time').on('change', function() {
             var op_time = jQuery(this).find(":selected").val();
             jQuery.cookies.set('option_time',op_time);
         });     

    });
</script>

















