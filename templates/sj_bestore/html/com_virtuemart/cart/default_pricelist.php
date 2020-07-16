<?php
	$document = JFactory::getDocument();
	$document->addStyleSheet('templates/sj_bestore/css/modules/nouislider.css');
	$document->addStyleSheet('templates/sj_bestore/css/modules/css.css');
	$document->addStyleSheet('templates/sj_bestore/css/modules/owl.carousel.css');
	$document->addStyleSheet('templates/sj_bestore/css/modules/ekko-lightbox.css');
	$document->addStyleSheet('templates/sj_bestore/css/modules/style.default.9d9629d7.css');
	$document->addStyleSheet('htemplates/sj_bestore/css/modules/custom.0a822280.css');
	if($this->cart->products && count($this->cart->products)>0)
	{
		?>
		<section>
			<div class="container">
				<div class="row mb-5">
					<div class="col-lg-8">
						<div class="cart">
							<div class="cart-wrapper">
								<div class="cart-header text-center">
									<div class="row">
										<div class="col-5">Product</div>
										<div class="col-2">Price</div>
										<div class="col-2">Quantity</div>
										<div class="col-2">Total</div>
										<div class="col-1"></div>
									</div>
								</div>
								<div class="cart-body">
									<?php
										$i = 1;
										foreach ($this -> cart -> products as $pkey => $prow) {
											$prow -> prices = array_merge($prow -> prices, $this -> cart -> cartPrices[$pkey]);
											?>
											<!-- Product-->
											<div class="cart-item">
												<div class="row d-flex align-items-center text-center">
													<div class="col-5">
														<div class="d-flex align-items-center">
															<a>
																<?php
																	if ($prow -> virtuemart_media_id && !empty($prow -> images[0])) {
																		echo $prow -> images[0] -> displayMediaThumb('', FALSE);
																	} else {
																		echo '<img src="images/virtuemart/product/Logo_MKZ.png" alt="No images" class="img-fluid">';
																	}
																?>
															</a>
															<div class="cart-title text-left">
																<a class="text-uppercase text-dark" href="">
																	<strong><?php echo JHtml ::link($prow -> url, $prow -> product_name); ?></strong>
																</a><br>
																<span class="text-muted text-sm">SKU : <?php echo $prow -> product_sku ?></span><br>
															</div>
														</div>
													</div>
													<div class="col-2">
														<?php
															if (VmConfig ::get('checkout_show_origprice', 1) && $prow -> prices['discountedPriceWithoutTax'] != $prow -> prices['priceWithoutTax']) {
																echo '<span class="line-through">' . $this -> currencyDisplay -> createPriceDiv('basePriceVariant', '', $prow -> prices, TRUE, FALSE) . '</span><br />';
															}

															if ($prow -> prices['discountedPriceWithoutTax']) {
																echo $this -> currencyDisplay -> createPriceDiv('discountedPriceWithoutTax', '', $prow -> prices, FALSE, FALSE, 1.0, false, true);
															} else {
																echo $this -> currencyDisplay -> createPriceDiv('basePriceVariant', '', $prow -> prices, FALSE, FALSE, 1.0, false, true);
															}
														?>
													</div>
													<div class="col-2">
														<div class="d-flex align-items-center">
															<!-- Quality -->
															<?php
																if ($prow -> step_order_level)
																	$step = $prow -> step_order_level;
																else
																	$step = 1;
																
																if ($step == 0)
																	$step = 1;
															?>
															<input type="text"
																   class="form-control text-center input-items"
																   onblur="Virtuemart.checkQuantity(this,<?php echo $step ?>,'<?php echo vmText ::_('COM_VIRTUEMART_WRONG_AMOUNT_ADDED', true) ?>');"
																   onclick="Virtuemart.checkQuantity(this,<?php echo $step ?>,'<?php echo vmText ::_('COM_VIRTUEMART_WRONG_AMOUNT_ADDED', true) ?>');"
																   onchange="Virtuemart.checkQuantity(this,<?php echo $step ?>,'<?php echo vmText ::_('COM_VIRTUEMART_WRONG_AMOUNT_ADDED', true) ?>');"
																   onsubmit="Virtuemart.checkQuantity(this,<?php echo $step ?>,'<?php echo vmText ::_('COM_VIRTUEMART_WRONG_AMOUNT_ADDED', true) ?>');"
																   title="<?php echo vmText ::_('COM_VIRTUEMART_CART_UPDATE') ?>"
																   class="quantity-input js-recalculate"
																   size="3"
																   maxlength="4"
																   name="quantity[<?php echo $pkey; ?>]"
																   value="<?php echo $prow -> quantity ?>"
															/>
															<button
																	type="submit"
																	class="vmicon vm2-add_quantity_cart"
																	name="updatecart.<?php echo $pkey ?>"
																	title="<?php echo vmText ::_('COM_VIRTUEMART_CART_UPDATE') ?>"
															>
															</button>
														</div>
													</div>
													<div class="col-2 text-center">
														<?php
															if (VmConfig ::get('checkout_show_origprice', 1) && !empty($prow -> prices['basePriceWithTax']) && $prow -> prices['basePriceWithTax'] != $prow -> prices['salesPrice']) {
																echo '<span class="line-through">' . $this -> currencyDisplay -> createPriceDiv('basePriceWithTax', '', $prow -> prices, TRUE, FALSE, $prow -> quantity) . '</span><br />';
															} elseif (VmConfig ::get('checkout_show_origprice', 1) && empty($prow -> prices['basePriceWithTax']) && !empty($prow -> prices['basePriceVariant']) && $prow -> prices['basePriceVariant'] != $prow -> prices['salesPrice']) {
																echo '<span class="line-through">' . $this -> currencyDisplay -> createPriceDiv('basePriceVariant', '', $prow -> prices, TRUE, FALSE, $prow -> quantity) . '</span><br />';
															}

															echo $this -> currencyDisplay -> createPriceDiv('salesPrice', '', $prow -> prices, FALSE, FALSE, $prow -> quantity)
														?>
													</div>
													<div class="col-1 text-center">
														<a class="cart-remove">
															<button
																	type="submit"
																	class="fa fa-times"
																	name="delete.<?php echo $pkey ?>"
																	title="<?php echo vmText ::_('COM_VIRTUEMART_CART_DELETE') ?>">
															</button>
														</a>
													</div>
												</div>
											</div>
											<?php
										}
									?>

								</div>
							</div>
						</div>
						<div class="my-5 d-flex justify-content-between flex-column flex-lg-row">
							<a class="btn btn-link text-muted" href="<?= JURI::root(); ?>">
								<i class="fa fa-chevron-left"></i> <?= vmText::_('COM_VIRTUEMART_CONTINUE_SHOPPING') ?>
							</a>
							<!-- button checkout -->
							<?= $this->checkout_link_html ?>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="block mb-5">
							<div class="block-header">
								<h6 class="text-uppercase mb-0">Order Summary</h6>
							</div>
							<div class="block-body bg-light pt-1">
								<ul class="order-summary mb-0 list-unstyled">
									<li class="order-summary-item">
										<span>Order Subtotal </span>
										<span>
                                            <?php
	                                            echo $this -> currencyDisplay -> createPriceDiv('salesPrice', '', $this -> cart -> cartPrices, FALSE);
                                            ?>
                                        </span>
									</li>
									<li class="order-summary-item">
										<?php
											if (VmConfig ::get('coupons_enable'))
											{
												if (!empty($this -> layoutName) && $this -> layoutName == 'default')
												{
													echo $this -> loadTemplate('coupon');
												}

												if (!empty($this -> cart -> cartData['couponCode']))
												{
												    echo "<span>";
													echo $this -> cart -> cartData['couponCode'];
													echo $this -> cart -> cartData['couponDescr'] ? (' (' . $this -> cart -> cartData['couponDescr'] . ')') : '';

													if (VmConfig ::get('show_tax')) {
														echo $this -> currencyDisplay -> createPriceDiv('couponTax', '', $this -> cart -> cartPrices['couponTax'], FALSE);
													}

													echo $this -> currencyDisplay -> createPriceDiv('salesPriceCoupon', '', $this -> cart -> cartPrices['salesPriceCoupon'], FALSE);
													echo "</span>";
												}
											}
										?>
									</li>
									<!-- <li class="order-summary-item"><span>Shipping and handling</span><span>$10.00</span></li> -->
									<!-- <li class="order-summary-item"><span>Tax</span><span>$0.00</span></li> -->
									<li class="order-summary-item border-0">
										<span>Total</span>
                                        <strong class="order-summary-total">
                                            <?php echo $this->currencyDisplay->createPriceDiv ('billTotal', '', $this->cart->cartPrices['billTotal'], FALSE); ?>
                                        </strong>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
<?php
	}
	else
	{
		
		?>
		<section class="hero">
			<div class="container">
				<!-- Hero Content-->
				<div class="hero-content pb-5 text-center">
					<h1 class="hero-heading">Shopping cart</h1>
					<div class="row">
						<div class="col-xl-8 offset-xl-2"><p class="lead text-muted">You have 0 items in your shopping cart</p></div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
?>


