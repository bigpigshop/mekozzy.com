<?php
/** ------------------------------------------------------------------------
* Product name:  One page checkout for Virtuemart
* author: CmsMart Team
* copyright Copyright (C) 2015 www.cms-extensions.net All Rights Reserved.
* @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* Websites: www.cms-extensions.net
* Technical Support: Forum - www.cms-extensions.net
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
$orderInfo = file_get_contents(JPATH_ADMINISTRATOR.'/components/com_vmmanager/helpers/config/orderInfo.json');
$orderInfo = json_decode($orderInfo);
?>
<style>
    .opc-module-content h2.opc-title.priceTitle{
        background-color:<?php echo  '#'.$orderInfo->color->titleBg ?> !important;
        color: <?php echo  '#'.$orderInfo->color->titleTxtCl ?> !important;
        font-size: <?php echo $orderInfo->color->titleTxtSize.'px' ?>;
    }
    .opc-module-content form.opc-form#priceForm{
        background-color:<?php echo  '#'.$orderInfo->color->contentBg ?>;
    }
    .opc-module-content form.opc-form#priceForm table{
        color: <?php echo  '#'.$orderInfo->color->contentTxtCl ?>;
        font-size: <?php echo $orderInfo->color->contentTxtSize.'px' ?>;
    }
</style>
<h2 class="opc-title priceTitle">
    <i class="<?php echo $orderInfo->color->titleIcon ?>"></i>
    <span><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_ORDER_INFO'); ?></span>
</h2>
<form method="Post" action="" name="priceForm" id="priceForm" class="opc-form">
    <div class="opc-table" >
        <table >
            <tr>
                <td class="opc-order-title name text-align-left"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_NAME') ?></td>
                <td class="opc-order-title price"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_PRICE') ?></td>
                <td class="opc-order-title quantity"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_QUANTITY') ?></td>
                <td class="opc-order-title tax"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_TAX') ?></td>
                <td class="opc-order-title discount"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_DISCOUNT') ?></td>
                <td class="opc-order-title total"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_TOTAL') ?></td>
            </tr>
            <!--List product-->
            <?php foreach ($this->cart->products as $pkey => $pvalue): ?>
            <tr class="opc-row">
                <td class="text-align-left"><?php echo $pvalue->product_name ?></td>
                <td>
                <?php
                    /** Show price*/
            		if (VmConfig::get ('checkout_show_origprice', 1) && $pvalue->prices['discountedPriceWithoutTax'] != $pvalue->prices['priceWithoutTax']) {
            			echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $pvalue->prices, TRUE, FALSE) . '</span><br />';
            		}
            		if ($pvalue->prices['discountedPriceWithoutTax']) {
            			echo $this->currencyDisplay->createPriceDiv ('discountedPriceWithoutTax', '', $pvalue->prices, FALSE, FALSE);
            		} else {
            			echo $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $pvalue->prices, FALSE, FALSE);
            		}
		          ?>
                </td>
                <td>
                    <?php /** Show quantity */ ?>
                    <input type="text" value="<?php echo $pvalue->quantity ?>" name="quantity[<?php echo $pkey; ?>]" id="quantity_<?php echo $pkey; ?>" class="quantity_product" />
                    <span class="delete-product-cart" data-pid="<?php echo $pkey; ?>"><i class="opc-iconcancel"></i></span>
                    <span class="update-quantity"><i class="opc-iconcw"></i></span>

                </td>
                <td><?php echo "<span class='opc-taxAmount'>" . $this->currencyDisplay->createPriceDiv ('taxAmount', '', $pvalue->prices['taxAmount'], True, FALSE, $pvalue->quantity) . "</span>" ?></td>
                <td><?php echo "<span class='opc-discountAmount'>" . $this->currencyDisplay->createPriceDiv ('discountAmount', '', $pvalue->prices['discountAmount'], True, FALSE, $pvalue->quantity) . "</span>" ?></td>
                <td>
                <?php
            		if (VmConfig::get ('checkout_show_origprice', 1) && !empty($pvalue->prices['basePriceWithTax']) && $pvalue->prices['basePriceWithTax'] != $pvalue->prices['salesPrice']) {
            			echo '<span class="line-through opc-basePriceWithTax">' . $this->currencyDisplay->createPriceDiv ('basePriceWithTax', '', $pvalue->prices['basePriceWithTax'], TRUE, FALSE, $pvalue->quantity) . '</span><br />';
            		}
            		elseif (VmConfig::get ('checkout_show_origprice', 1) && empty($pvalue->prices['basePriceWithTax']) && $pvalue->prices['basePriceVariant'] != $pvalue->prices['salesPrice']) {
            			echo '<span class="line-through opc-basePriceVariant">' . $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $pvalue->prices['basePriceVariant'], TRUE, FALSE, $pvalue->quantity) . '</span><br />';
            		}
            		echo '<span class="opc-salesPrice">'.$this->currencyDisplay->createPriceDiv ('salesPrice', '', $pvalue->prices['salesPrice'], True, FALSE, $pvalue->quantity).'</span>';
                ?>
                </td>
            </tr>
            <?php endforeach; ?>

            <!-- DATaxRulesBill & taxRulesBill -->
            <?php foreach ($this->cart->cartData['DATaxRulesBill'] as $rule) : ?>
            <tr class="opc-row">
                <td colspan="3" class="text-align-left"><?php echo   $rule['calc_name'] ?></td>
                <td></td>
                <td><?php echo $this->currencyDisplay->createPriceDiv ($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], FALSE); ?> </td>
                <td><?php echo $this->currencyDisplay->createPriceDiv ($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], FALSE); ?></td>
            </tr>
            <?php endforeach;?>
            <?php foreach ($this->cart->cartData['taxRulesBill'] as $rule) : ?>
            <tr class="opc-row">
                <td colspan="3" class="text-align-left"><?php echo $rule['calc_name'] ?></td>
                <td><?php echo $this->currencyDisplay->createPriceDiv ($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], FALSE); ?></td>
                <td></td>
                <td><?php echo $this->currencyDisplay->createPriceDiv ($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], FALSE); ?></td>
            </tr>
            <?php endforeach;?>

            <!-- End DATaxRulesBill & taxRulesBill -->
            <!--price total-->
            <tr>
                <td class="text-align-left" colspan="3"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_PRODUCT_PRICES_RESULT') ?></td>
                <td><?php echo "<span  class='opc-taxAmount-total'>" . $this->currencyDisplay->createPriceDiv ('taxAmount', '', $this->cart->cartPrices['taxAmount'], True,False) . "</span>" ?></td>
                <td><?php echo "<span  class='opc-discountAmount-total'>" . $this->currencyDisplay->createPriceDiv ('discountAmount', '', $this->cart->cartPrices['discountAmount'],True, FALSE) . "</span>" ?></td>
                <td><?php echo "<span  class='opc-salesPrice-total'>" . $this->currencyDisplay->createPriceDiv ('salesPrice', '', $this->cart->cartPrices['salesPrice'],True, FALSE) . "</span>"?></td>
            </tr>
            <tr>
                <td class="text-align-left" colspan="3"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_SHIPPING_COST') ?></td>
                <td><?php echo "<span  class='opc-shipmentTax'>" . $this->currencyDisplay->createPriceDiv ('shipmentTax', '', $this->cart->cartPrices['shipmentTax'],True, FALSE) . "</span>"; ?></td>
                <td></td>
                <td><span class="opc-salesPriceShipment"><?php echo $this->currencyDisplay->createPriceDiv ('salesPriceShipment', '', $this->cart->cartPrices['salesPriceShipment'],true, FALSE); ?> </span></td>
            </tr>
            <tr>
                <td class="text-align-left" colspan="3"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_PAYMENT_COST') ?></td>
                <td><?php echo "<span  class='opc-paymentTax'>" . $this->currencyDisplay->createPriceDiv ('paymentTax', '', $this->cart->cartPrices['paymentTax'],True, FALSE) . "</span>" ?></td>
                <td></td>
                <td><span class="opc-salesPricePayment"><?php echo $this->currencyDisplay->createPriceDiv ('salesPricePayment', '', $this->cart->cartPrices['salesPricePayment'],true, FALSE); ?></span></td>
            </tr>
            <tr>
                <td class="text-align-left" colspan="3"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_TOTAL_PRICE') ?></td>
                <td><?php echo "<span  class='opc-billTaxAmount'>" . $this->currencyDisplay->createPriceDiv ('billTaxAmount', '', $this->cart->cartPrices['billTaxAmount'], True,FALSE) . "</span>" ?></td>
                <td><?php echo "<span  class='opc-billDiscountAmount'>" . $this->currencyDisplay->createPriceDiv ('billDiscountAmount', '', $this->cart->cartPrices['billDiscountAmount'], True,FALSE) . "</span>" ?> </td>
                <td><strong><span class="opc-billTotal"><?php echo $this->currencyDisplay->createPriceDiv ('billTotal', '', $this->cart->cartPrices['billTotal'],True, FALSE); ?></span></strong></td>
            </tr>
            <!--Coupond-->
            <tr>
                <td colspan="3" class="text-align-left">
                    <input name="coupon_code" placeholder="Enter your Coupon code" maxlength="50" class="couponOpc" alt="Enter your Coupon code" />
                    <span id="addCouponCode">Save</span>
                </td>
                <td><?php echo "<span  class='opc-couponTax'>" . $this->currencyDisplay->createPriceDiv ('couponTax', '', $this->cart->cartPrices['couponTax'], True,FALSE) . "</span>" ?> </td>
                <td></td>
                <td><?php echo "<span  class='opc-salesPriceCoupon'>" . $this->currencyDisplay->createPriceDiv ('salesPriceCoupon', '', $this->cart->cartPrices['salesPriceCoupon'], True,FALSE) . "</span>" ?></td>
            </tr>
        </table>
    </div>
</form>
