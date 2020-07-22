<?php
	/**
	 *
	 * Show progress_sold
	 * @author BigPig
	 */
	// Check to ensure this file is included in Joomla!
	defined('_JEXEC') or die('Restricted access');
	$product = $viewData['product'];
	$currency = $viewData['currency'];
	foreach ($product->customfieldsSorted['addtocart'] as $fied)
	{
		if($fied->custom_title == 'progress_sold')
		{
?>
<div class="iq-card" style="margin: 10px">
	<div class="row mt-3 ml-3 pt-3">
		<p>Đã Bán : <?php echo $fied->customfield_value ?> sản phẩm</p>
	</div>
</div>
<?php }} ?>
