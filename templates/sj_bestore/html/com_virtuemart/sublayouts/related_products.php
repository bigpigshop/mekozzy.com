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
	$customTitle = isset($viewData['customTitle']) ? $viewData['customTitle'] : false;;
	if (isset($viewData['class'])) {
		$class = $viewData['class'];
	} else {
		$class = 'product-fields';
	}
	
	if (!empty($product->customfieldsSorted[$position])) {
		?>
        <div class="module box2 <?php echo $class ?>">
			<?php
				if ($customTitle and isset($product->customfieldsSorted[$position][0])) {
					$field = $product->customfieldsSorted[$position][0];
					?>
                    <h4 class="card-title"><?php echo vmText::_($field->custom_title) ?> :</h4><br>
					<?php
				}
			?>
            <div id="owl-carousel-related-id" class="owl-carousel modcontent product-fields-content-wrapper">
				<?php
					foreach ($product->customfieldsSorted[$position] as $field) {
						if ($field->is_hidden || empty($field->display)) continue;
						?>
                        <div class="category item vertical-separator">
							<?php if (!$customTitle and $field->custom_title != $custom_title and $field->show_title) { ?>
                                <span class="product-fields-title-wrapper"><span
                                            class="product-fields-title"><strong><?php echo vmText::_($field->custom_title) ?></strong></span>
                            <?php if ($field->custom_tip) {
	                            echo JHtml::tooltip(vmText::_($field->custom_tip), vmText::_($field->custom_title), 'tooltip.png');
                            } ?></span>
							<?php }
								if (!empty($field->display)) {
									?>
                                    <div class="spacer"><?php echo $field->thumb_images ?></a></div><?php
								}
							?>
                            <a class="text-center">
								<?php
									echo $field->product_link;
								?>
                            </a>
                        </div>
					
					<?php } ?>
            </div>
            <style>
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
                .product-related-products #owl-carousel-related .item a {
                    display: block;
                    width: 70px;
                    overflow: hidden;
                    white-space: nowrap;
                    text-overflow: ellipsis;
                }

                .owl-carousel .owl-item img {
                    display: block;
                    width: 250px;
                }
            </style>
            <!-- Javascript Block -->
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $("#owl-carousel-related-id").owlCarousel({
                        autoPlay: 3000, //Set AutoPlay to 3 seconds
                        items: 4,
                        //Pagination
                        pagination: false,
                        paginationNumbers: false,
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
        </div>
		<?php
	} ?>
