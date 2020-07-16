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
	
	foreach ($product->customfieldsSorted['addtocart'] as $fied) {
		
		if($fied->custom_title == 'product_detail_introduce_category')
		{
			$product_detail_introduce_category =  $fied->customfield_value ? $fied->customfield_value : "";
		}
		
		if($fied->custom_title == 'product_detail_introduce_manufacturer')
		{
		    $product_detail_introduce_manufacturer = $fied->customfield_value ? $fied->customfield_value : "";
		}
		
		if($fied->custom_title == 'product_detail_introduce_produce')
		{
			$product_detail_introduce_produce =  $fied->customfield_value ? $fied->customfield_value : "";
		}
		
		if($fied->custom_title == 'product_detail_introduce_country')
		{
			$product_detail_introduce_country =  $fied->customfield_value ? $fied->customfield_value : "";
		}
		
		if($fied->custom_title == 'product_detail_introduce_material')
		{
			$product_detail_introduce_material =  $fied->customfield_value ? $fied->customfield_value : "";
		}
		
		if($fied->custom_title == 'product_detail_infor')
		{
			$product_detail_infor =  $fied->customfield_value ? $fied->customfield_value : "";
		}
		
		if($fied->custom_title == 'product_detail_box_delivery')
		{
			$product_detail_box_delivery =  $fied->customfield_value ? $fied->customfield_value : "";
		}
		
		if($fied->custom_title == 'product_detail_warranty_return')
		{
			$product_detail_warranty_return =  $fied->customfield_value ? $fied->customfield_value : "";
		}
		
		if($fied->custom_title == 'product_detail_use_storage')
		{
			$product_detail_use_storage =  $fied->customfield_value ? $fied->customfield_value : "";
		}
		
		if($fied->custom_title == 'product_detail_tech_description')
		{
			$product_detail_tech_description =  $fied->customfield_value ? $fied->customfield_value : "";
		}
		
		if($fied->custom_title == 'product_detail_video_tutorial')
		{
			$product_detail_video_tutorial =  $fied->customfield_value ? $fied->customfield_value : "";
		}
	}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="iq-accordion career-style faq-style  ">
                <div class="iq-card iq-accordion-block accordion-active">
                    <div class="active-faq clearfix">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12"><a class="accordion-title"><span> chi tiết sản phẩm  </span> </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-details">
                        <p class="mb-0">
                            <div class="iq-card-body">
                                <dl class="row">
                                    <dt class="col-sm-3">Danh mục</dt>
                                    <dd class="col-sm-9"><?php echo $product_detail_introduce_category; ?></dd>

                                    <dt class="col-sm-3">Cung cấp bởi</dt>
                                    <dd class="col-sm-9"><?php echo $product_detail_introduce_manufacturer; ?></dd>

                                    <dt class="col-sm-3">Nhà sản xuất</dt>
                                    <dd class="col-sm-9"><?php echo $product_detail_introduce_produce; ?></dd>

                                    <dt class="col-sm-3">xuất xứ</dt>
                                    <dd class="col-sm-9"><?php echo $product_detail_introduce_country ?></dd>

                                    <dt class="col-sm-3">chất liệu</dt>
                                    <dd class="col-sm-9"> <?php echo $product_detail_introduce_material ?> </dd>

                                    <dt class="col-sm-3">mã sku</dt>
                                    <dd class="col-sm-9"><?php echo $product->product_sku ?></dd>
                                    
                                    <dt class="col-sm-3">Kích thước</dt>
                                    <dd class="col-sm-9">
                                        <dl class="row">
                                            <dt class="col-sm-4">Dài</dt>
                                            <dd class="col-sm-8"> <?php echo floatval($product->product_length); ?> -  <?php echo $product->product_lwh_uom ?></dd>
                                        </dl>
                                        <dl class="row">
                                            <dt class="col-sm-4">Rộng</dt>
                                            <dd class="col-sm-8"> <?php echo floatval($product->product_width); ?> -  <?php echo $product->product_lwh_uom ?></dd>
                                        </dl>
                                        <dl class="row">
                                            <dt class="col-sm-4">Cao</dt>
                                            <dd class="col-sm-8"> <?php echo floatval($product->product_height); ?> -  <?php echo $product->product_lwh_uom ?></dd>
                                        </dl>
                                        <dl class="row">
                                            <dt class="col-sm-4">Cân Nặng</dt>
                                            <dd class="col-sm-8"> <?php echo floatval($product->product_weight); ?> -  <?php echo $product->product_weight_uom ?></dd>
                                        </dl>
                                    </dd>
                                </dl>
                            </div>
                            
                            
                        </p>
                    </div>
                </div>
                <div class="iq-card iq-accordion-block ">
                    <div class="active-faq clearfix">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12"><a class="accordion-title"><span> giới thiệu sản phẩm </span> </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-details">
                        <p class="mb-0"><?php echo $product_detail_infor ? $product_detail_infor : ""; ?></p>
                    </div>
                </div>
                <div class="iq-card iq-accordion-block ">
                    <div class="active-faq clearfix">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12"><a class="accordion-title"><span>qui cách đóng gói và giao hàng </span> </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-details">
                        <p class="mb-0"><?php echo $product_detail_box_delivery ? $product_detail_box_delivery : ""; ?></p>
                    </div>
                </div>
                <div class="iq-card iq-accordion-block ">
                    <div class="active-faq clearfix">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12"><a class="accordion-title"><span>  chính sách bảo hành và đổi trả </span> </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-details">
                        <p class="mb-0"><?php echo $product_detail_warranty_return ? $product_detail_warranty_return : ""; ?></p>
                    </div>
                </div>
                <div class="iq-card iq-accordion-block ">
                    <div class="active-faq clearfix">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12"><a class="accordion-title"><span> hướng dẫn sử dụng và bảo quản </span> </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-details">
                        <p class="mb-0"><?php echo $product_detail_use_storage ? $product_detail_use_storage : ""; ?></p>
                    </div>
                </div>
                <div class="iq-card iq-accordion-block ">
                    <div class="active-faq clearfix">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12"><a class="accordion-title"><span> mô tả chi tiết kỹ thuật </span> </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-details">
                        <p class="mb-0"><?php echo $product_detail_tech_description ? $product_detail_tech_description : ""; ?></p>
                    </div>
                </div>
               
            </div>
        </div>
        <div class="col-lg-12">
            <div class="iq-accordion career-style faq-style  ">
                <div class="iq-card iq-accordion-block accordion-active">
                    <div class="active-faq clearfix">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12"><a class="accordion-title"><span>  video hướng dẫn lắp ráp </span> </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-details">
                        <p class="mb-0"><?php echo $product_detail_video_tutorial ? $product_detail_video_tutorial : ""; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


