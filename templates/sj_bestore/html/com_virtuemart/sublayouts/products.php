<?php
/**
 * sublayout products
 *
 * @package	VirtueMart
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL2, see LICENSE.php
 * @version $Id: cart.php 7682 2014-02-26 17:07:20Z Milbo $
 */

defined('_JEXEC') or die('Restricted access');
$products_per_row = empty($viewData['products_per_row'])? 1:$viewData['products_per_row'] ;
$currency = $viewData['currency'];
$showRating = $viewData['showRating'];
$verticalseparator = " vertical-separator";
echo shopFunctionsF::renderVmSubLayout('askrecomjs');

$ItemidStr = '';
$Itemid = shopFunctionsF::getLastVisitedItemId();
if(!empty($Itemid)){
	$ItemidStr = '&Itemid='.$Itemid;
}

$dynamic = false;
if (vRequest::getInt('dynamic',false)) {
	$dynamic = true;
}

foreach ($viewData['products'] as $type => $products ) {

	$col = 1;
	$nb = 1;
	$row = 1;

	if($dynamic){
		$rowsHeight[$row]['product_s_desc'] = 1;
		$rowsHeight[$row]['price'] = 1;
		$rowsHeight[$row]['customfields'] = 1;
		$col = 2;
		$nb = 2;
	} else {
	$rowsHeight = shopFunctionsF::calculateProductRowsHeights($products,$currency,$products_per_row);

		if( (!empty($type) and count($products)>0) or (count($viewData['products'])>1 and count($products)>0)){
			$productTitle = vmText::_('COM_VIRTUEMART_'.strtoupper($type).'_PRODUCT'); ?>
	<div class="category-view">
	  <h3 class="form-group"><?php echo $productTitle ?></h3><div class="clear"></div>
			<?php // Start the Output
		}
	}

	// Calculating Products Per Row
	$cellwidth = ' width'.floor ( 100 / $products_per_row );

	$BrowseTotalProducts = count($products);


	foreach ( $products as $product ) {
		if(!is_object($product) or empty($product->link)) {
			vmdebug('$product is not object or link empty',$product);
			continue;
		}
		// Show the horizontal seperator
		if ($col == 1 && $nb > $products_per_row) { ?>
		<?php }

		// this is an indicator wether a row needs to be opened or not
		if ($col == 1) { ?>
	<ul class="row">
		<?php }

		// Show the vertical seperator
		if ($nb == $products_per_row or $nb % $products_per_row == 0) {
			$show_vertical_separator = ' ';
		} else {
			$show_vertical_separator = $verticalseparator;
		}

	    // Show Products ?>
		<li class="item product col-md-4 col-sm-6 col-xs-6 col-lg-3<?php echo $show_vertical_separator ?>">
			<div class="item-inner clearfix">
				<div class="item-image">
					<a title="<?php echo $product->product_name ?>" href="<?php echo $product->link.$ItemidStr; ?>">
						<?php
						echo $product->images[0]->displayMediaThumb('class="browseProductImage"', false);
						?>
					</a>		
				</div>
				<div class="item-content">
					<div class="item-title">
						<?php echo JHtml::link ($product->link.$ItemidStr, $product->product_name); ?>
					</div>	
					<div class="item-price vm3pr-<?php echo $rowsHeight[$row]['price'] ?>"> <?php
						echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$product,'currency'=>$currency)); ?>
					</div>
					<div class="reviews-content">
						<?php echo shopFunctionsF::renderVmSubLayout('rating',array('showRating'=>$showRating, 'product'=>$product)); ?>
					</div>
					<div class="item-description">
						
						<?php if(!empty($rowsHeight[$row]['product_s_desc'])){
						?>
						<p class="product_s_desc hidden-xs">
							<?php // Product Short Description
							if (!empty($product->product_s_desc)) {
								echo shopFunctionsF::limitStringByWord ($product->product_s_desc, 300, ' ...') ?>
							<?php } ?>
						</p>
						<?php  } ?>
					</div>
					<div class="group-addtocart vm3pr-<?php echo $rowsHeight[$row]['customfields'] ?>"> <?php
						echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$product,'rowHeights'=>$rowsHeight[$row], 'position' => array('ontop', 'addtocart'))); ?>
					</div>
					
					<?php if(vRequest::getInt('dynamic')){
						echo vmJsApi::writeJS();
					} ?>	
				</div>
			</div>
		</li>

		<?php
	    $nb ++;

	      // Do we need to close the current row now?
	      if ($col == $products_per_row || $nb>$BrowseTotalProducts) { ?>
	    <div class="clear"></div>
  	</ul>
      <?php
      	$col = 1;
		$row++;
    } else {
      $col ++;
    }
  }

      if(!empty($type)and count($products)>0){
        // Do we need a final closing row tag?
        //if ($col != 1) {
      ?>
    <div class="clear"></div>
    <script type="text/javascript">
	    //<![CDATA[
	    jQuery(document).ready(function ($) { 
	    	
	        var $quickview = $(".item-bottom .addtocart-bar");
	             $(".sj_quickview_handler").insertAfter($quickview);
	    });
	    //]]>
	</script> 
  </div>
    <?php
    // }
    }
  }

