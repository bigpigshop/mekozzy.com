<?php
	defined('_JEXEC') or die;
	
	use Joomla\CMS\Factory;
	
	$document = Factory::getDocument();
	$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/typography.css');
	$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/style.css');
	$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/responsive.css');
	
	if (!class_exists('VmConfig')) require(JPATH_ROOT . '/administrator/components/com_virtuemart/helpers/config.php');
	VmConfig::loadConfig();
	if (!class_exists('VmConfig')) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');
	VmConfig::loadConfig();
	if (!class_exists('VmModel')) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'vmmodel.php');
	
	
	$idcategory = JFactory::getApplication()->input->getString('idcategory');
	$currency = CurrencyDisplay::getInstance();
	ob_start();
	$categoryModel = VmModel::getModel('category');
	$categoryModel->_noLimit = true;
	$categories = $categoryModel->getChildCategoryList(1, $idcategory);
	
	require_once 'modules/mod_bigpig_category_product_detail/core/helper.php';
?>
<div class="row">
    <div class="col-sm-12">
        <div class="iq-card">
            <div class="iq-card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="todo-date d-flex mr-3">
                        <span>Danh Sách Sản Phẩm</span>
                    </div>
                    <div class="todo-notification d-flex align-items-center">
                        <div class="notification-icon position-relative d-flex align-items-center mr-3">
                            <a href="#"><i class="ri-notification-3-line font-size-16"></i></a>
                            <span class="bg-danger text-white"><?php echo count($categories); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-lg-3">
        <div class="iq-card">
            <div class="iq-card-body">
                <div class="iq-todo-page">

                    <ul class="todo-task-list p-0 m-0">
                        <li>
							<?php
								foreach ($categories as $category) {
									?>
                                    <a onclick="openPage('category_product_<?php echo $category->virtuemart_category_id ?>') "><i
                                                class="ri-stack-fill mr-2"></i><?php echo $category->category_name ?>
                                    </a>
									<?php
									
								}
							?>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card">
                    <div class="iq-card-body p-0">
						
						<?php
							foreach ($categories as $category) {
								?>
                                <div id="category_product_<?php echo $category->virtuemart_category_id ?>"
                                     class="tabcontent">
                                    <div class="d-none iq-card">
                                        <div class="iq-card-body">
                                            <div class="iq-todo-page">
	                                            <?php echo $category->category_name ?>
                                            </div>
                                        </div>
                                    </div>
                                    
									<?php
										
										$db = JFactory::getDBO();
										$query = $db->getQuery(true);
										$query->select('*')
											->from("#__virtuemart_product_categories as a")
											->where("a.virtuemart_category_id =" . $category->virtuemart_category_id);
										$db->setQuery($query);
										$data = $db->loadObjectList();
										
										if (!empty($data) && count($data) > 0) {
											foreach ($data as $temp) {
												$listid[] += $temp->virtuemart_product_id;
											}
											
											$query = $db->getQuery(true);
											$query->select('*')
												->from("#__virtuemart_products_vi_vn as a")
												->where("a.virtuemart_product_id IN (" . implode(',', $listid) . ')');
											$db->setQuery($query);
											$products = $db->loadObjectList();
											
											foreach ($products as $product) {
												$image = VMListingTabsHelper::pmedia($product->virtuemart_product_id);
												
												$q = 'SELECT `product_price` FROM `#__virtuemart_product_prices` WHERE `virtuemart_product_id`="' . $product->virtuemart_product_id . '" ORDER BY `price_quantity_start` ';
												$db->setQuery($q);
												$multiprices = $db->loadObject();
												
												?>
                                                <div class="col-xl-3 col-sm-4 col-md-4 col-lg-3 mb-3">
                                                    <div class="iq-friendlist-block">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $category->virtuemart_category_id); ?>">
                                                                <div class="d-flex align-items-center">
                                                                    <img src="<?php echo is_file($image) ? $image : 'images/noimages.png'; ?>"
                                                                         alt="profile-img" class="img-fluid">
                                                                    <div class="friend-info ml-3">
                                                                        <p><?php echo $product->product_name; ?></p>
                                                                        <p class="mb-0"><?php echo $currency->createPriceDiv('salesPrice', '', $multiprices->product_price, FALSE, FALSE, 1.0, TRUE); ?></p>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
												<?php
												
											}
										} else {
											?>
                                            <div class="col-xl-3 col-sm-4 col-md-4 col-lg-3 mb-3">
                                                <div class="iq-friendlist-block">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <a>
                                                            <div class="d-flex align-items-center">
                                                                <img src="images/noimages.png"
                                                                     alt="profile-img" class="img-fluid">
                                                                <div class="friend-info ml-3">
                                                                    <p>Không Có Sản Phẩm</p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
											<?php
										}
									?>

                                </div>
								
								<?php
								
							}
						?>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .danhmucsanpham {
        display: block;
        width: 100px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .img-fluid {
        width: 150px;
        height: 150px;
    }
</style>

<script>
    function openPage(pageName) {
        var i, tabcontent;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        document.getElementById(pageName).style.display = "block";
    }

    document.getElementById("defaultOpen").click();
</script>