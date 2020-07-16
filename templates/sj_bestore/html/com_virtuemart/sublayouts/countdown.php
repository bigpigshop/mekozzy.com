<?php
	/**
	 *
	 * Show the countdown
	 * @author BigPig
	 */
	// Check to ensure this file is included in Joomla!
	defined('_JEXEC') or die('Restricted access');
	$product = $viewData['product'];
	$currency = $viewData['currency'];
	
	foreach ($product->customfieldsSorted['addtocart'] as $fied)
	{
        if($fied->custom_title == 'countdown')
        {
?>
<div class="row">
	<div class="container-fluid">
		<div class="justify-content-center">
			<div class="iq-comingsoon-info">
				<p>Thời gian còn lại : </p>
				<ul class="countdown" data-date="<?php echo $fied->customfield_value ?>">
					<!--                <li><span data-days>0</span>Days</li>-->
					<li><span data-hours>0</span>Hours</li>
					<li><span data-minutes>0</span>Minutes</li>
					<li><span data-seconds>0</span>Seconds</li>
				</ul>
			</div>
		</div>
	
	</div>

</div>
 <?php }} ?>