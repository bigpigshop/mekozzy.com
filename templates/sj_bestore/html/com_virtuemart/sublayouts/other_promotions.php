<?php
	/**
	 * sublayout products
	 *
	 * @package    VirtueMart
	 * @author Max Milbers
	 * @link http://www.virtuemart.net
	 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
	 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL2, see LICENSE.php
	 * @version $Id: cart.php 7682 2014-02-26 17:07:20Z Milbo $
	 */
	defined('_JEXEC') or die('Restricted access');
	$product = $viewData['product'];
	$position = $viewData['position'];
	foreach ($product->customfieldsSorted['addtocart'] as $fied)
	{
		if($fied->custom_title == 'other_promotions')
		{
			$da = $fied->customfield_value;
		}
	}
?>
<div class="iq-card">
	<div class="iq-card-header d-flex justify-content-between">
		<div class="iq-header-title">
			<h4 class="card-title">Các Uư Đãi khác</h4>
		</div>
	</div>
	<div class="iq-card-body">
		<ul class="iq-timeline">
			<?php echo $da; ?>
		</ul>
	</div>
</div>