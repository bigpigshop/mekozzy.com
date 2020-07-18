<?php
	
	// Check to ensure this file is included in Joomla!
	defined('_JEXEC') or die('Restricted access');
	
	/* Let's see if we found the product */
	if (empty($this->product)) {
		echo vmText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
		echo '<br /><br />  ' . $this->continue_link_html;
		return;
	}
	
	echo shopFunctionsF::renderVmSubLayout('askrecomjs', array('product' => $this->product));
	
	vmJsApi::jDynUpdate();
	vmJsApi::addJScript('updDynamicListeners', "
jQuery(document).ready(function() { // GALT: Start listening for dynamic content update.
	// If template is aware of dynamic update and provided a variable let's
	// set-up the event listeners.
	if (Virtuemart.container)
		Virtuemart.updateDynamicUpdateListeners();

}); ");
	
	if (vRequest::getInt('print', false)){ ?>
<body onload="javascript:print();">
<?php } ?>

<div class="productdetails-view productdetails">
	<span id="close-sidebar" class="fa fa-times hidden-lg hidden-md"></span>
	<!-- <a href="javascript:void(0)" class="open-sidebar hidden-lg hidden-md"><i class="fa fa-bars"></i>Sidebar</a><div class="sidebar-overlay"></div> -->
	<?php
		// Product Navigation
		// if (VmConfig::get('product_navigation', 1)) {
	?>
	<!-- //     <div class="product-neighbours"> -->
	<?php
		//     if (!empty($this->product->neighbours ['previous'][0])) {
		//     $prev_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['previous'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id, FALSE);
		//     echo JHtml::_('link', $prev_link, $this->product->neighbours ['previous'][0]
		//         ['product_name'], array('rel'=>'prev', 'class' => 'previous-page','data-dynamic-update' => '0'));
		//     }
		//     if (!empty($this->product->neighbours ['next'][0])) {
		//     $next_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['next'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id, FALSE);
		//     echo JHtml::_('link', $next_link, $this->product->neighbours ['next'][0] ['product_name'], array('rel'=>'next','class' => 'next-page','data-dynamic-update' => '0'));
		//     }
	?>
	<!-- <div class="clear"></div>
	</div> -->
	<?php
		// } // Product Navigation END
	?>
	<div class="product_detail row">
		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 clear_xs">
			<?php echo $this->loadTemplate('images'); ?>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 clear_xs">
			<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 clear_xs">
				<div class="content_product_detail">
					<?php // Back To Category Button
						if ($this->product->virtuemart_category_id) {
							$catURL = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $this->product->virtuemart_category_id, FALSE);
							$categoryName = $this->product->category_name;
						} else {
							$catURL = JRoute::_('index.php?option=com_virtuemart');
							$categoryName = vmText::_('COM_VIRTUEMART_SHOP_HOME');
						}
					?>
					
					<?php // afterDisplayTitle Event
						echo $this->product->event->afterDisplayTitle ?>
					
					<?php
						// Product Edit Link
						echo $this->edit_link;
						// Product Edit Link END
					?>
					<?php
						// PDF - Print - Email Icon
						if (VmConfig::get('show_emailfriend') || VmConfig::get('show_printicon') || VmConfig::get('pdf_icon')) {
							?>
							<div class="icons">
								<?php
									
									$link = 'index.php?tmpl=component&option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id;
									
									echo $this->linkIcon($link . '&format=pdf', 'COM_VIRTUEMART_PDF', 'pdf_button', 'pdf_icon', false);
									//echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon');
									echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon', false, true, false, 'class="printModal"');
									$MailLink = 'index.php?option=com_virtuemart&view=productdetails&task=recommend&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component';
									echo $this->linkIcon($MailLink, 'COM_VIRTUEMART_EMAIL', 'emailButton', 'show_emailfriend', false, true, false, 'class="recommened-to-friend"');
								?>
								<div class="clearfix"></div>
							</div>
						<?php }
						// PDF - Print - Email Icon END
					?>
					
					<?php
						echo shopFunctionsF::renderVmSubLayout('prices', array('product' => $this->product, 'currency' => $this->currency));
						//			            echo shopFunctionsF::renderVmSubLayout('rating',array('showRating'=>$this->showRating,'product'=>$this->product));
						echo shopFunctionsF::renderVmSubLayout('countdown', array('product' => $this->product, 'currency' => $this->currency));
						echo shopFunctionsF::renderVmSubLayout('progress_sold', array('product' => $this->product, 'currency' => $this->currency));
					?>
					<div class="product-info product_meta">
						<?php
							// echo shopFunctionsF::renderVmSubLayout('stockhandle', array('product' => $this->product));
							// Product Sku
							if (!empty($this->product->product_sku)) { ?>
								<!-- <p class="btn mb-3 btn-primary mt-3"><span
											class="text"><?php //echo JText::_('COM_VIRTUEMART_PRODUCT_SKU'); ?></span>: <?php //echo $this->product->product_sku; ?>
								</p> -->
							<?php } ?>
					</div>
					<?php
						// Manufacturer of the Product
						if (VmConfig::get('show_manufacturers', 1) && !empty($this->product->virtuemart_manufacturer_id))
						{
						    ?>
							<!-- <div class="btn mb-3 btn-primary mt-3">
								<?php //echo $this->loadTemplate('manufacturer'); ?>
							</div> -->
						<?php }
						// Product Short Description
						if (!empty($this->product->product_s_desc)) {
							?>
							<div class="iq-card">
								<div class="iq-card-body">
									<dl class="row">
										<dt class="col-sm-3" >Thông tin Sản Phẩm</dt>
										<dd class="col-sm-9" id="product_s_desc" ><?php echo nl2br($this->product->product_s_desc); ?></dd>
									</dl>
								</div>
							</div>
							<?php
						} // Product Short Description END
					?>
                    <?php
                        //<!-- START API CALL BOXME -->
	                    echo shopFunctionsF::renderVmSubLayout('call_api_boxme_fee', array('product' => $this->product, 'currency' => $this->currency));
	                    // <!-- END API CALL BOXME -->
                    ?>
                   
					<div class="spacer-buy-area">
						<?php
							echo shopFunctionsF::renderVmSubLayout('addtocart', array('product' => $this->product));
							if (VmConfig::get('ask_question', 0) == 1) {
								$askquestion_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component', FALSE);
								?>
								<div class="form-group">
									<a class="ask-a-question button" href="<?php echo $askquestion_url ?>"
									   rel="nofollow"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?></a>
								</div>
								<?php
							}
						?>
					</div>

                        <!-- start social-share-->
<!--					<div class="social-share">-->
<!--						<h4 class="title-share">--><?php //echo JText::_('COM_VM_SHARE_THIS'); ?><!--</h4>-->
<!--						<div class="wrap-content">-->
<!--							<a href="#" title="Ut enim ad mini"-->
<!--							   onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i-->
<!--										class="fa fa-facebook"></i></a>-->
<!--							<a href="#"-->
<!--							   onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i-->
<!--										class="fa fa-twitter"></i></a>-->
<!--							<a href="#"-->
<!--							   onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i-->
<!--										class="fa fa-google-plus"></i></a>-->
<!--						</div>-->
<!--					</div>-->
                    <!-- end social-share-->
					<?php
						//<!-- 3 product thuong dc mua kem theo  -->
						echo shopFunctionsF::renderVmSubLayout('product_buy_together', array('product' => $this->product, 'currency' => $this->currency));
						//<!-- 3 product thuong dc mua kem theo  -->
					?>
					
					<?php
						//<!-- related_products  -->
//						echo shopFunctionsF::renderVmSubLayout('related_products', array('product' => $this->product, 'currency' => $this->currency));
						//<!-- related_products  -->
					?>
                    
                    
					<div class="clear"></div>
				</div>

			</div>
			<div class="col-lg-4 col-md-8 col-sm-12 col-xs-12 clear_xs">
				<?php echo shopFunctionsF::renderVmSubLayout('vendor_store', array('product' => $this->product, 'currency' => $this->currency)); ?>
			</div>
		</div>
	</div>
    
    <div class="product-description">
	    <?php
		    // Product View together
		    if (!empty($this->product->product_desc)) {
			    ?>
                <div class="product-description">
				    <?php /** @todo Test if content plugins modify the product description */ ?>
				    <?php //echo $this->product->product_desc; ?>
				    <?php echo shopFunctionsF::renderVmSubLayout('product_view_together', array('product' => $this->product, 'currency' => $this->currency)); ?>
				    <?php echo shopFunctionsF::renderVmSubLayout('usp', array('product' => $this->product, 'currency' => $this->currency)); ?>
                </div>
			    <?php
		    } // Product View together
	    ?>
    </div>

	<div id="yt_tab_products" class="tab-product-detail">
		<div class="tab-product">
			<ul class="nav nav-tabs" id="add-reviews">
				<li class="active"><a href="#description"
									  data-toggle="tab"><span><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_DESC_TITLE') ?></span></a>
				</li>
<!--				<li><a href="#reviews" data-toggle="tab"><span>--><?php //echo vmText::_('COM_VIRTUEMART_REVIEWS') ?><!--</span></a>-->
<!--				</li>-->
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="description">
					
					<?php
						// Product Description
						if (!empty($this->product->product_desc)) {
							?>
							<div class="product-description">
								<?php /** @todo Test if content plugins modify the product description */ ?>
								<?php //echo $this->product->product_desc; ?>
								
								<?php echo shopFunctionsF::renderVmSubLayout('other_promotions', array('product' => $this->product, 'currency' => $this->currency)); ?>
								
                                <?php echo shopFunctionsF::renderVmSubLayout('product_description', array('product' => $this->product, 'currency' => $this->currency)); ?>
							</div>
							<?php
						} // Product Description END
					?>
					<?php
						echo shopFunctionsF::renderVmSubLayout('customfields', array('product' => $this->product, 'position' => 'ontop'));
						echo shopFunctionsF::renderVmSubLayout('customfields', array('product' => $this->product, 'position' => 'normal'));
						echo shopFunctionsF::renderVmSubLayout('customfields', array('product' => $this->product, 'position' => 'onbot'));
						//file
						$product = $this->product;
						$position = 'file';
						$class = 'product-fields';
						
						if (!empty($product->customfieldsSorted[$position])) {
							$custom_title = null;
							foreach ($product->customfieldsSorted[$position] as $field) {
								if ($field->is_hidden) //OSP http://forum.virtuemart.net/index.php?topic=99320.0
									continue;
								?>
								<div class="product-field product-field-type-<?php echo $field->field_type ?>">
									<?php if (!$customTitle and $field->custom_title != $custom_title and $field->show_title) { ?>
										<span class="product-fields-title-wrapper"><span
													class="product-fields-title"><strong><?php echo vmText::_($field->custom_title) ?></strong></span>
										<?php if ($field->custom_tip) {
											echo JHtml::tooltip($field->custom_tip, vmText::_($field->custom_title), 'tooltip.png');
										} ?></span>
									<?php }
										if (!empty($field->display)) {
											?>
											<div class="product-field-display"><a
													href="images/stories/virtuemart/pdf/<?php echo $field->display; ?>">pdf
												file</a></div><?php
										}
										if (!empty($field->custom_desc)) {
											?>
											<div class="product-field-desc"><?php echo vmText::_($field->custom_desc) ?></div> <?php
										}
									?>
								</div>
								<?php
								$custom_title = $field->custom_title;
							}
						}
						// Product Packaging
						$product_packaging = '';
						if ($this->product->product_box) {
							?>
							<div class="product-box">
								<?php
									echo vmText::_('COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX') . $this->product->product_box;
								?>
							</div>
						<?php } // Product Packaging END ?>
				</div>
				<div class="tab-pane" id="reviews">
					<?php echo $this->loadTemplate('reviews'); ?>
				</div>

			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php
		// event onContentBeforeDisplay
		echo $this->product->event->beforeDisplayContent; ?>
	<div class="bottom-single-product theme-clearfix">
		<?php
			echo shopFunctionsF::renderVmSubLayout('related_product_category', array('product' => $this->product, 'currency' => $this->currency));
			echo shopFunctionsF::renderVmSubLayout('related_products', array('product' => $this->product, 'position' => 'related_products', 'class' => 'product-related-products', 'customTitle' => true));
			echo shopFunctionsF::renderVmSubLayout('related_categories', array('product' => $this->product, 'position' => 'related_categories', 'class' => 'product-related-categories'));
			
		?>
	</div>
	<?php // onContentAfterDisplay event
		echo $this->product->event->afterDisplayContent;
		echo vmJsApi::writeJS();
	?>
</div>


<script>
	// GALT
	/*
	 * Notice for Template Developers!
	 * Templates must set a Virtuemart.container variable as it takes part in
	 * dynamic content update.
	 * This variable points to a topmost element that holds other content.
	 */
	// If this <script> block goes right after the element itself there is no
	// need in ready() handler, which is much better.
	//jQuery(document).ready(function() {
	Virtuemart.container = jQuery('.productdetails-view');
	Virtuemart.containerSelector = '.productdetails-view';
	//Virtuemart.container = jQuery('.main');
	//Virtuemart.containerSelector = '.main';
	//});
</script>

