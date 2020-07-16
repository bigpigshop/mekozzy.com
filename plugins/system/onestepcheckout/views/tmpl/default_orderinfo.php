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
$orderInfo = file_get_contents(JPATH_ADMINISTRATOR.'/components/com_vmmanager/helpers/config/orderInfo.json');
$orderInfo = json_decode($orderInfo);
//set default options
if (!isset($orderInfo->options)){
    $orderInfo->options = new \stdClass();
}
if (!isset($orderInfo->options->reload)){
    $orderInfo->options->reload = false;
}
if (!isset($orderInfo->options->hide_tax)){
    $orderInfo->options->hide_tax = false;
}
if (!isset($orderInfo->options->hide_sku)){
    $orderInfo->options->hide_sku = false;
}
if (!isset($orderInfo->options->hideempty)){
    $orderInfo->options->hideempty = false;
}
if (!isset($orderInfo->options->show_sale_price)){
    $orderInfo->options->show_sale_price = false;
}
if (!isset($orderInfo->options->hide_payment_cost)){
    $orderInfo->options->hide_payment_cost = false;
}
if (!isset($orderInfo->options->hide_total_base_price)){
    $orderInfo->options->hide_total_base_price = false;
}
if (!isset($orderInfo->options->hide_discount)){
    $orderInfo->options->hide_discount = false;
}

$fontFamily = array(0=>'',1=>"'Lato',sans-serif",2=>"'Open Sans', sans-serif",3=>"'Oswald', sans-serif",
                    4=>"'PT Sans', sans-serif",5=>"'Raleway', sans-serif",6=>"'Roboto', sans-serif");
$orderInfo->color->titleTxtFont = $fontFamily[$orderInfo->color->titleTxtFont];
$orderInfo->color->contentTxtFont = $fontFamily[$orderInfo->color->contentTxtFont];
?>
<script type="text/javascript">
    var reload = <?= boolval($orderInfo->options->reload) ? 'true' : 'false' ?>;
</script>
<style>
    .opc-module-content h2.opc-title.priceTitle{
        background-color:<?php echo  '#'.$orderInfo->color->titleBg ?> !important;
        color: <?php echo  '#'.$orderInfo->color->titleTxtCl ?> !important;
        font-size: <?php echo $orderInfo->color->titleTxtSize.'px' ?>;
        font-family: <?php echo $orderInfo->color->titleTxtFont ?>;
    }
    .opc-module-content form.opc-form#priceForm{
        background-color:<?php echo  '#'.$orderInfo->color->contentBg ?>;
    }
    .opc-module-content form.opc-form#priceForm .opc-table,.opc-module-content form.opc-form#priceForm .opc-table li{
        color: <?php echo  '#'.$orderInfo->color->contentTxtCl ?>;
        font-size: <?php echo $orderInfo->color->contentTxtSize.'px' ?>;
        font-family: <?php echo $orderInfo->color->contentTxtFont ?>;
    }
    .order-result li.total{color:<?php echo  '#'.$orderInfo->color->titleBg ?>}
    <?php if(isset($orderInfo->color->quantityColumnWidth)){ ?>
    .opc-table li.opc-order-qty{width:<?= $orderInfo->color->quantityColumnWidth ?>px;}
    <?php } ?>
    <?php if(isset($orderInfo->color->priceColumnWidth)){ ?>
    .opc-table li.opc-order-total,.opc-table li.opc-order-price,.opc-table li.opc-order-tax,.order-result li.result-total,.order-result li.result-tax{width:<?= $orderInfo->color->priceColumnWidth ?>px;}
    <?php } ?>
</style>
<h2 class="opc-title priceTitle">
    <i class="<?php echo $orderInfo->color->titleIcon ?>"></i>
    <span><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_ORDER_INFO'); ?></span>
</h2>

<form method="Post" action="" name="priceForm" id="priceForm" class="opc-form">
    <div class="opc-table" >
        <div class="order-title">
            <ul>
                <li class="opc-order-total"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_TOTAL') ?></li>
                <?php if (!$orderInfo->options->hide_discount): ?>
                    <li class="opc-order-discount"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_DISCOUNT') ?></li>
                <?php endif; ?>
                <?php if (!$orderInfo->options->hide_tax): ?>
                    <li class="opc-order-tax"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_TAX') ?></li>
                <?php endif; ?>
                <li class="opc-order-qty"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_QUANTITY') ?></li>
                <li class="opc-order-price"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_PRICE') ?></li>
                <?php if (!$orderInfo->options->hide_sku): ?>
                    <li class="opc-order-sku"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_SKU') ?></li>
                <?php endif ?>
                <li class="opc-order-name"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_NAME') ?></li>                       
            </ul>
        </div>
        <?php foreach ($this->cart->products as $pkey => $pvalue):  ?>
        <div class="order-product">            
            <ul>
                <li class="opc-order-total">
                    <?php
                        if (!$orderInfo->options->hide_total_base_price){
                    		if (VmConfig::get ('checkout_show_origprice', 1) && !empty($pvalue->prices['basePriceWithTax']) && $pvalue->prices['basePriceWithTax'] != $pvalue->prices['salesPrice']) {
                    			echo '<span class="line-through opc-basePriceWithTax">' . $this->currencyDisplay->createPriceDiv ('basePriceWithTax', '', $pvalue->prices['basePriceWithTax'], TRUE, FALSE, $pvalue->quantity) . '</span>';
                    		}
                    		elseif (VmConfig::get ('checkout_show_origprice', 1) && empty($pvalue->prices['basePriceWithTax']) && $pvalue->prices['basePriceVariant'] != $pvalue->prices['salesPrice']) {
                    			echo '<span class="line-through opc-basePriceVariant">' . $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $pvalue->prices['basePriceVariant'], TRUE, FALSE, $pvalue->quantity) . '</span>';
                    		}
                        }
                		echo '<span class="opc-salesPrice">'.$this->currencyDisplay->createPriceDiv ('salesPrice', '', $pvalue->prices['salesPrice'], True, FALSE, $pvalue->quantity).'</span>';
                    ?>
                </li>
                <?php if (!$orderInfo->options->hide_discount): ?>
                    <li class="opc-order-discount">
                        <?php echo "<span class='opc-discountAmount'>" . $this->currencyDisplay->createPriceDiv ('discountAmount', '', $pvalue->prices['discountAmount'], True, FALSE, $pvalue->quantity) . "</span>" ?>
                    </li>
                <?php endif; ?>
                <?php if (!$orderInfo->options->hide_tax): ?>
                <li class="opc-order-tax">
                    <?php echo "<span class='opc-taxAmount'>" . $this->currencyDisplay->createPriceDiv ('taxAmount', '', $pvalue->prices['taxAmount'], True, FALSE, $pvalue->quantity) . "</span>" ?>
                </li>
                <?php endif; ?>
                <li class="opc-order-qty">
                    <?php /** Show quantity */ ?>
                    <input type="text" value="<?php echo $pvalue->quantity ?>" name="quantity[<?php echo $pkey; ?>]" id="quantity_<?php echo $pkey; ?>" class="quantity_product" />
                    <span class="delete-product-cart" data-pid="<?php echo $pkey; ?>"><i class="opc-iconcancel-circled-outline"></i></span>
                    <span class="update-quantity"><i class="opc-iconcw"></i></span>
                </li>
                <li class="opc-order-price">
                    <?php
                        /** Show price*/
                        if ($orderInfo->options->show_sale_price){
                            if ($pvalue->prices['discountAmount'] != -0){
                                echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv ('basePriceWithTax', '', $pvalue->prices, TRUE, FALSE) . '</span><br />';
                            }
                            echo $this->currencyDisplay->createPriceDiv ('salesPrice', '', $pvalue->prices, True, FALSE);
                        }else{
                            if (VmConfig::get ('checkout_show_origprice', 1) && $pvalue->prices['discountedPriceWithoutTax'] != $pvalue->prices['priceWithoutTax']) {
                                echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $pvalue->prices, TRUE, FALSE) . '</span><br />';
                            }
                            if ($pvalue->prices['discountedPriceWithoutTax']) {
                                echo $this->currencyDisplay->createPriceDiv ('discountedPriceWithoutTax', '', $pvalue->prices, FALSE, FALSE, 1.0, false, true);
                            } else {
                                echo $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $pvalue->prices, True, FALSE);
                            }
                        }
                    ?>
                </li>     
                <?php if (!$orderInfo->options->hide_sku): ?>
                    <li class="opc-order-sku" ><?php  echo ($pvalue->product_sku) ?></li>                 
                <?php endif; ?>
                <li class="opc-order-name">
                    <?php if ($pvalue->virtuemart_media_id && (!empty($pvalue->images[0]))) { ?>
                		<span class="cart-images">
     						<?php                    			
                    			echo $pvalue->images[0]->displayMediaThumb ('', FALSE);                    			
                			?>
                		</span>
                	<?php } ?>
                    <div class="name-val">
                        <?php 
                            if ($orderInfo->options->disable_link){
                                echo $pvalue->product_name;
                            }else{
                                echo JHtml::link ($pvalue->url, $pvalue->product_name);
                            }
                            /* hide custom field with empty value */
                            if ($orderInfo->options->hideempty){
                                $customfields = array();
                                $selectedField = $pvalue->customProductData;
                                foreach ($pvalue->customfields as $cf) {
                                    if (isset($selectedField[$cf->virtuemart_custom_id]) and($cf->virtuemart_customfield_id==$selectedField[$cf->virtuemart_custom_id])){
                                        if(!empty($cf->customfield_value)){
                                            $customfields[$cf->virtuemart_custom_id] = $selectedField[$cf->virtuemart_custom_id];
                                        }
                                    }
                                }                            
                                $pvalue->customProductData = $customfields;
                            }                            
                            echo $this->customfieldsModel->CustomsFieldCartDisplay($pvalue);
                        ?>
                    </div>
                </li>
            </ul>          
        </div>
        <?php endforeach; ?>
        <div id="orderinfordefault">
            <div class="order-result">
                <ul>
                    <li class="result-total"><?php echo "<span  class='opc-salesPrice-total'>" . $this->currencyDisplay->createPriceDiv ('salesPrice', '', $this->cart->cartPrices['salesPrice'],True, FALSE) . "</span>"?></li>
			        <?php if (!$orderInfo->options->hide_discount): ?>
                        <li class="result-discount"><?php echo "<span  class='opc-discountAmount-total'>" . $this->currencyDisplay->createPriceDiv ('discountAmount', '', $this->cart->cartPrices['discountAmount'],True, FALSE) . "</span>" ?></li>
			        <?php endif; ?>
			        <?php if (!$orderInfo->options->hide_tax): ?>
                        <li class="result-tax"><?php echo "<span  class='opc-taxAmount-total'>" . $this->currencyDisplay->createPriceDiv ('taxAmount', '', $this->cart->cartPrices['taxAmount'], True,False) . "</span>" ?></li>
			        <?php endif; ?>
                    <li class="result-title"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_PRODUCT_PRICES_RESULT') ?></li>
                </ul>
            </div>
	        <?php foreach ($this->cart->cartData['DATaxRulesBill'] as $rule) { ?>
                <div class="order-result">
                    <ul>
                        <li class="result-total"><?php echo $this->currencyDisplay->createPriceDiv ($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], true); ?></li>
				        <?php if (!$orderInfo->options->hide_discount): ?>
                            <li class="result-discount">&nbsp;</li>
				        <?php endif; ?>
				        <?php if (!$orderInfo->options->hide_tax): ?>
                            <li class="result-tax">&nbsp;</li>
				        <?php endif; ?>
                        <li class="result-title"><?php echo   $rule['calc_name'] ?></li>
                    </ul>
                </div>
	        <?php } ?>
            <div class="order-result">
                <ul>
                    <li class="result-total"><span class="opc-salesPriceShipment"><?php echo $this->currencyDisplay->createPriceDiv ('salesPriceShipment', '', $this->cart->cartPrices['salesPriceShipment'],true, FALSE); ?> </span></li>
			        <?php if (!$orderInfo->options->hide_discount): ?>
                        <li class="result-discount"></li>
			        <?php endif; ?>
			        <?php if (!$orderInfo->options->hide_tax): ?>
                        <li class="result-tax"><?php echo "<span  class='opc-shipmentTax'>" . $this->currencyDisplay->createPriceDiv ('shipmentTax', '', $this->cart->cartPrices['shipmentTax'],True, FALSE) . "</span>"; ?></li>
			        <?php endif; ?>
                    <li class="result-title"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_SHIPPING_COST') ?></li>
                </ul>
            </div>
	        <?php if (!$orderInfo->options->hide_payment_cost): ?>
                <div class="order-result">
                    <ul>
                        <li class="result-total"><span class="opc-salesPricePayment"><?php echo $this->currencyDisplay->createPriceDiv ('salesPricePayment', '', $this->cart->cartPrices['salesPricePayment'],true, FALSE); ?></span></li>
				        <?php if (!$orderInfo->options->hide_discount): ?>
                            <li class="result-discount"></li>
				        <?php endif; ?>
				        <?php if (!$orderInfo->options->hide_tax): ?>
                            <li class="result-tax"><?php echo "<span  class='opc-paymentTax'>" . $this->currencyDisplay->createPriceDiv ('paymentTax', '', $this->cart->cartPrices['paymentTax'],True, FALSE) . "</span>" ?></li>
				        <?php endif; ?>
                        <li class="result-title"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_PAYMENT_COST') ?></li>
                    </ul>
                </div>
	        <?php endif; ?>
	        <?php if($this->cart->cartPrices['salesPriceCoupon']): ?>
                <div class="order-result">
                    <ul>
                        <li class="result-total"><?php echo "<span  class='opc-salesPriceCoupon'>" . $this->currencyDisplay->createPriceDiv ('salesPriceCoupon', '', $this->cart->cartPrices['salesPriceCoupon'], True,FALSE) . "</span>" ?></li>
				        <?php if (!$orderInfo->options->hide_discount): ?>
                            <li class="result-discount"><?php echo "<span  class='opc-billDiscountAmount'>" . $this->currencyDisplay->createPriceDiv ('billDiscountAmount', '', $this->cart->cartPrices['billDiscountAmount'], True,FALSE) . "</span>" ?> </li>
				        <?php endif; ?>
				        <?php if (!$orderInfo->options->hide_tax): ?>
                            <li class="result-tax"><?php echo "<span  class='opc-couponTax'>" . $this->currencyDisplay->createPriceDiv ('couponTax', '', $this->cart->cartPrices['couponTax'], True,FALSE) . "</span>" ?></li>
				        <?php endif; ?>
                        <li class="result-title"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_COUPON_PRICE') ?></li>
                    </ul>
                </div>
	        <?php endif; ?>
            <div class="order-result">
                <ul>
                    <li class="result-total total"><span class="opc-billTotal"><?php echo $this->currencyDisplay->createPriceDiv ('billTotal', '', $this->cart->cartPrices['billTotal'],True, FALSE); ?></span></li>
			        <?php if (!$orderInfo->options->hide_discount): ?>
                        <li class="result-discount total"><?php echo "<span  class='opc-billDiscountAmount'>" . $this->currencyDisplay->createPriceDiv ('billDiscountAmount', '', $this->cart->cartPrices['billDiscountAmount'], True,FALSE) . "</span>" ?> </li>
			        <?php endif; ?>
			        <?php if (!$orderInfo->options->hide_tax): ?>
                        <li class="result-tax total"><?php echo "<span  class='opc-billTaxAmount'>" . $this->currencyDisplay->createPriceDiv ('billTaxAmount', '', $this->cart->cartPrices['billTaxAmount'], True,FALSE) . "</span>" ?></li>
			        <?php endif; ?>
                    <li class="result-title total"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_TOTAL_PRICE') ?></li>
                </ul>
            </div>
        </div>
		<?php if (VmConfig::get ('coupons_enable')): ?>
        <div class="order-coupon-code">
            <input name="coupon_code" placeholder="<?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_COUPON_TEXT') ?>" maxlength="50" class="couponOpc" alt="<?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_COUPON_TEXT') ?>" />
            <span id="addCouponCode"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_COUPON_SAVE_BUTTON_TEXT') ?></span>
            <span><?php if($this->cart->couponCode) echo $this->cart->couponCode ?></span>
        </div>
		<?php endif; ?>
    </div>
</form>
