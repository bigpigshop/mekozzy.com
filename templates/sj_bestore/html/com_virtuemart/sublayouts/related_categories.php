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
?>
<h4 class="card-title">Danh mục liên quan :</h4><br>
<div id="owl-carousel-related-related" class="owl-carousel modcontent product-fields-content-wrapper">
	<?php
		if (!empty($product->customfieldsSorted[$position])) {
			foreach ($product->customfieldsSorted[$position] as $field) {
				if ($field->is_hidden || empty($field->display)) continue;
				?>
                    <div class="category item">
                          <div class="spacer"><?php echo $field->display ?></a></div>
                    </div>
			<?php }
		} ?>
</div>
<div class="clearfix"></div>
<style>
    .category.item >a {
        display: block;
        width: 70px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>
<!-- Javascript Block -->
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("#owl-carousel-related-related").owlCarousel({
            autoPlay: 3000, //Set AutoPlay to 3 seconds
            items: 1,
            //Pagination
            pagination: true,
            paginationNumbers: true,
            nav: false,
            loop: true,
            margin: 15,
            responsive: {
                0: {
                    items: 1,
                    margin: 8,
                },
                480: {
                    items: 2,
                    margin: 8,
                },
                768: {
                    items: 3,
                    margin: 8,
                },
                992: {
                    items: 3,
                    margin: 8,
                },
                1200: {
                    items: 4,
                },
            },
            navText: '',
        });
    });
</script>