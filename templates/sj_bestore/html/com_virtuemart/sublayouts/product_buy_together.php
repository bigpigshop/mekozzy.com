<?php
	/**
	 *
	 * Show the product_buy_together
	 * @author BigPig
	 */
	// Check to ensure this file is included in Joomla!
	defined('_JEXEC') or die('Restricted access');
	$product = $viewData['product'];
	$currency = $viewData['currency'];
?>
<div class="iq-card center" style="margin: 10px;">
    <div class="iq-card-header d-flex justify-content-between">
        <div class="iq-header-title">
            <h4 class="card-title">Sản phẩm thường được mua cùng : </h4>
        </div>
    </div>
    <div class="iq-card-body">
		<?php
            $i=1;
			foreach ($product->customfieldsSorted['related_products'] as $fied) {
				?>
                <a href="">
                    <img src="<?php echo $fied->link_pic ?>" style="height: 100px;float: left;" class="img-thumbnail"
                         alt="<?php echo $fied->product_name ?>">
                    
                </a>
				<?php
                if($i< count($product->customfieldsSorted['related_products']))
                {
                    ?>
                    <i class="fa fa-plus pl-2 pr-2 pt-5" style="height: 100px;float: left;" aria-hidden="true"></i>
                <?php
                }
                $i++;
			}
		?>


        <table class="table table-hover">
            <tbody>
			<?php
                $sum=0;
				$currencyDisplay = CurrencyDisplay::getInstance();
				foreach ($product->customfieldsSorted['related_products'] as $fied) {
				    $sum += $fied->infor->allPrices['0']['product_price'];
					?>
                    <tr>
                        <td><?php echo $fied->product_name ?></td>
                        <td></td>
                        <td><?php echo $currencyDisplay->priceDisplay($fied->infor->allPrices['0']['product_price']);  ?></td>
                    </tr>
					<?php
				}
			?>
            <tr>
                <td colspan="2"></td>
                <td><?php echo $currencyDisplay->priceDisplay($sum); ?></td>
            </tr>
            </tbody>
        </table>


    </div>
</div>
<div class="clear"></div>