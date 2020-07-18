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
<div class="profile-header-image">
	<div class="cover-container">
		<img src="images/page-img/profile-bg7.jpg" alt="profile-bg" class="rounded img-fluid w-100">
	</div>
	<div class="profile-info p-4">
		<div class="user-detail">
			<div class="d-flex flex-wrap justify-content-between align-items-start">
				<div class="profile-detail">
					<div class="profile-img">
						<img src="images/noimages.png" alt="profile-img" class="avatar-130 img-fluid">
					</div>
					<div class="user-data-block">
						<?php
							$vendorModel = new VirtueMartModelVendor();
							$vendor = $vendorModel->getVendor($product->virtuemart_vendor_id);
							require_once JPATH_ROOT . "/components/com_congtacvien/helpers/route.php";
							$link = CTVHelperRoute::getVendorShop($product->virtuemart_vendor_id);
							$text = vmText::_('COM_VIRTUEMART_VENDOR_FORM_INFO_LBL');
							
							echo '<span class="bold">' . vmText::_('COM_VIRTUEMART_PRODUCT_DETAILS_VENDOR_LBL') . '</span>';
						?>
						<a class=""
						   href="<?php echo $link ?>"><?php echo $vendor->vendor_store_name ?></a><br/>
						<a class=""
						   href="<?php echo $link ?>"><?php echo $vendor->vendor_phone ?></a><br/>
						<a class=""
						   href="<?php echo $link ?>"><?php echo $vendor->customtitle ?></a><br/>
					</div>
				</div>
				<a class="btn btn-primary"
				   href="<?php echo $link ?>"><?php echo $vendor->vendor_store_name ?></a><br/>
			</div>
		</div>
	</div>
</div>