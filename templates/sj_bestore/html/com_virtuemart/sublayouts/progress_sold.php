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
        <div class="row mt-3">
            <p>Đã Bán : <?php echo $fied->customfield_value ?> sản phẩm</p>

        </div>
        <!-- <div class="progress mb-3" style="height: 25px">

            <div class="progress-bar" role="progressbar" style="width: <?php echo $fied->customfield_value ?>%;"
                 aria-valuenow="50" aria-valuemin="0"
                 aria-valuemax="100"><?php //echo $fied->customfield_value ?></div>

        </div> -->
<?php }} ?>
