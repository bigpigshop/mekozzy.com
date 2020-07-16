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
	
//	require_once "smodules/mod_bigpig_category_product_detail/core/helper.php";
	
	$productModel = VmModel::getModel('Product');
	$productsChildren = array();
	$productsChildren = $productModel->getProductListing(false, false, true, true, false, true, $product->virtuemart_category_id, false, 0);
    
?>
<h4 class="card-title">Sản Phẩm Cùng Danh Mục :</h4><br>
<div id="owl-carousel-related-category-product" class="owl-carousel modcontent product-fields-content-wrapper">
	<?php
            foreach ($productsChildren as $product)
            {
				?>
                <div class="category item">
                    <img src="<?php echo $productModel->pmedia($product->virtuemart_product_id) ?>">
                    <a href="<?php echo $product->link ?>" class="text-center">
                        <?php
                            echo $product->product_name;
                        ?>
                    </a>
                </div>
            <?php } ?>
</div>

<div class="clearfix"></div>
<style>
    /*.owl-item { width : 350px !important; }*/
   
    /* Extra small devices (phones, 600px and down) */
    @media only screen and (max-width: 600px) {
        .owl-item { width : 70px !important; padding: 10px }
    }

    /* Small devices (portrait tablets and large phones, 600px and up) */
    @media only screen and (min-width: 600px) {
        .owl-item { width : 150px !important; padding: 10px }
    }

    /* Medium devices (landscape tablets, 768px and up) */
    @media only screen and (min-width: 768px) {
        .owl-item {
            width : 200px !important;
            padding: 10px;
        }
    }

    /* Large devices (laptops/desktops, 992px and up) */
    @media only screen and (min-width: 992px) {
        .owl-item { width : 250px !important; }
    }

    /* Extra large devices (large laptops and desktops, 1200px and up) */
    @media only screen and (min-width: 1200px) {
        .owl-item { width : 350px !important; }
    }
    a.text-center {
        display: block;
        width: 70px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .owl-carousel .owl-item img {
        display: block;
        width: 200px;
    }
</style>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("#owl-carousel-related-category-product").owlCarousel({
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
                    items: 4,
                    margin: 0,
                },
                480: {
                    items: 4,
                    margin: 0,
                },
                768: {
                    items: 4,
                    margin: 0,
                },
                992: {
                    items: 6,
                    margin: 0,
                },
                1200: {
                    items: 6,
                    margin: 0,
                },
            },
            navText: '',
        });
    });
</script>
	