<?php
	/**
	 * @package CATEGORY PRODUCT DETAIL
	 * @version 1.0.0
	 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 * @copyright (c) 2020 Mekozzy Company. All Rights Reserved.
	 * @author BIGPIG
	 *
	 */
	
	defined('_JEXEC') or die;

	foreach ($categories as $i => $category) {
		$categories[$i]->childs = $categoryModel->getChildCategoryList($vendorId, $category->virtuemart_category_id);
		if ($level > 2) {
			foreach ($categories[$i]->childs as $j => $cat) {
				$categories[$i]->childs[$j]->childs = $categoryModel->getChildCategoryList($vendorId, $cat->virtuemart_category_id);
			}
		}
	}
	$currency = CurrencyDisplay::getInstance();
	
	ob_start();
?>
<div class="row">
    <div class="nav nav-tabs col-12" id="tablistproducbigpig" role="tablist">
        <a class="d-block nav-item active show" data-toggle="pill"
           href="#v-pills-all<?php echo $categories['0']->virtuemart_category_id ?>" role="tablist"
           aria-selected="true">Tất Cả</a>
		<?php
			foreach ($categories as $cate) {
				$db = JFactory::getDBO();
				$db->setQuery(' SELECT `virtuemart_product_id` FROM `#__virtuemart_product_categories` WHERE `virtuemart_category_id` =' . (int)$cate->virtuemart_category_id);
				$listIDProduct = $db->loadObjectList();
				?>
                <a class="nav nav-tabs text-center p-3">
                    <a class="d-block nav-item" data-toggle="pill"
                       href="#v-pills-<?php echo $cate->virtuemart_category_id ?>" role="tablist"
                       aria-controls="#v-pills-<?php echo $cate->virtuemart_category_id ?>"
                       aria-selected="false"><?php echo $cate->category_name ?></a>
                </a>
			
			<?php } ?>

    </div>
</div>
<div class="row">
    <div class="tab-content mt-0">
		<?php
			foreach ($categories as $cate) {
				$list[] += $cate->virtuemart_category_id;
			}
			$db = JFactory::getDBO();
			$db->setQuery(' SELECT `virtuemart_product_id` FROM `#__virtuemart_product_categories` WHERE `virtuemart_category_id`IN (' . implode(',', $list) . ')');
			$listIDProduct = $db->loadObjectList();
			if (isset($listIDProduct) && $listIDProduct) {
				foreach ($listIDProduct as $temp) {
					$listid[] += $temp->virtuemart_product_id;
				}
				?>
                <div class="tab-pane fade show active"
                     id="v-pills-all<?php echo $categories['0']->virtuemart_category_id ?>" role="tabpanel"
                     aria-labelledby="v-pills-all">
                    <div class="category-view bigpig">
                        <ul class="row flexbigshop">
					<?php
						$db = JFactory::getDBO();
						$query = $db->getQuery(true);
						$query->select('*')
							->from("#__virtuemart_products_vi_vn as a")
							->where("a.virtuemart_product_id IN (" . implode(',', $listid) . ')');
						$db->setQuery($query);
						$data = $db->loadObjectList();
						
						if (!empty($data)) {
							foreach(array_slice($data, 0, 8) as $pro)
							{
								if (!empty($pro)) {
								 
									$image = VMListingTabsHelper::pmedia($pro->virtuemart_product_id);
									
									$ddbb = JFactory::getDBO();
									$q = 'SELECT `product_price`,`price_quantity_start`,`price_quantity_end`,`product_tax_id`,`product_currency` FROM `#__virtuemart_product_prices` WHERE `virtuemart_product_id`="' . $pro->virtuemart_product_id . '" ORDER BY `price_quantity_start` ';
									$db->setQuery($q);
									$multiprices = $db->loadObject();
									?>
                                    <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 flex-item">
                                        <div class="item-inner clearfix">
                                            <div class="item-image">
                                                <a title="<?php echo $pro->product_name; ?>" target="_blank"  href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$pro->virtuemart_product_id); ?>">
                                                    <img src="<?php echo is_file($image) ? $image : 'images/noimages.png'; ?>" alt="<?php echo $pro->product_name; ?>"> </a>
                                            </div>
                                            <div class="item-content">
                                                <div class="item-title">
                                                    <a ><?php echo $pro->product_name; ?></a>
                                                    <p class="text-center"><?php echo $currency->createPriceDiv('salesPrice', '', $multiprices->product_price, FALSE, FALSE, 1.0, TRUE); ?></p>
                                                </div>
                                               
                                            </div>
                                        </div>
                                    </li>
									<?php
								} else {
									?>
                                    <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 flex-item">
                                        <div class="item-inner clearfix">
                                            <div class="item-image">
                                                <a title="" target="_blank"  href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">
                                                    <img src="images/noimages.png" alt="noimages"> </a>
                                            </div>
                                            <div class="item-content">
                                                <div class="item-title">
                                                    <a>No Name </a>
                                                </div>
                                                <div class="item-description">
                                                    <p class="product_s_desc hidden-xs">
                                                        No Name
                                                    </p>
                                                    <p class="text-center">No Price </p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
									<?php
								}
								
							}
						}
					?>
                        </ul>
                    </div>
                </div>
			
			<?php } else { ?>
                <div class="tab-pane fade" id="v-pills-all<?php echo $cate->virtuemart_category_id ?>"
                     role="tabpanel" aria-selected="true"
                     aria-labelledby="v-pills-all<?php echo $cate->virtuemart_category_id ?>">
                    <div class="col-12" role="tabpanel"><h6 class="text-center">Khong Co San Pham</h6></div>
                </div>
				<?php
			}
		?>
		
		<?php
			foreach ($categories as $cate) {
				$db = JFactory::getDBO();
				$query = $db->getQuery(true);
				$query->select('*')
					->from("#__virtuemart_product_categories as a")
					->where("a.virtuemart_category_id =" . $cate->virtuemart_category_id);
				$query->setLimit(8);
				$db->setQuery($query);
				
				$listIDProduct = $db->loadObjectList();
				
				if (is_array($listIDProduct) && count($listIDProduct) > 0) {
					?>
                    <div class="tab-pane fade" id="v-pills-<?php echo $cate->virtuemart_category_id ?>"
                         role="tabpanel" aria-labelledby="v-pills-<?php echo $cate->virtuemart_category_id ?>">
                        <div class="category-view bigpig">
                            <ul class="row flexbigshop">
						<?php
							foreach(array_slice($listIDProduct, 0, 8) as $prod)
							{
								if(!empty($prod->virtuemart_product_id))
								{
									$db = JFactory::getDBO();
									$query = $db->getQuery(true);
									$query->select('*')
										->from("#__virtuemart_products_vi_vn as a")
										->leftJoin("#__virtuemart_product_categories as c ON c.virtuemart_product_id = a.virtuemart_product_id")
										->where("a.virtuemart_product_id =" . $prod->virtuemart_product_id);
									$db->setQuery($query);
									$data = $db->loadObject();
								}
								if (!empty($data->virtuemart_product_id)) {
								 
									$image2 = VMListingTabsHelper::pmedia($data->virtuemart_product_id);
									
									$ddbb = JFactory::getDBO();
									$q = 'SELECT `product_price`,`price_quantity_start`,`price_quantity_end`,`product_tax_id`,`product_currency` FROM `#__virtuemart_product_prices` WHERE `virtuemart_product_id`="' . $data->virtuemart_product_id . '" ORDER BY `price_quantity_start` ';
									$db->setQuery($q);
									$prices = $db->loadObject();
									?>
                                    <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 flex-item">
                                        <div class="item-inner clearfix">
                                            <div class="item-image">
                                                <a title="<?php echo $data->product_name; ?>" target="_blank"  href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$data->virtuemart_product_id); ?>">
                                                    <img src="<?php  echo is_file($image2) ? $image2 : 'images/noimages.png'?>" alt="<?php echo $data->product_name; ?>"> </a>
                                            </div>
                                            <div class="item-content">
                                                <div class="item-title">
                                                    <a><?php echo $data->product_name; ?></a>
                                                    <p class="text-center"><?php echo $currency->createPriceDiv('salesPrice', '', $prices->product_price, FALSE, FALSE, 1.0, TRUE); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
									<?php
								}
								else
								{
									?>
                                    <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 flex-item">
                                        <div class="item-inner clearfix">
                                            <div class="item-image">
                                                <a title="" target="_blank"  href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">
                                                    <img src="images/noimages.png" alt="noimages"> </a>
                                            </div>
                                            <div class="item-content">
                                                <div class="item-title">
                                                    <a >No Name </a>
                                                </div>
                                                <div class="item-description">
                                                    <p class="product_s_desc hidden-xs">
                                                        No Name
                                                    </p>
                                                    <p class="text-center">No Price </p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
									<?php
								}
							}
						?>
                            </ul>
                        </div>
                    </div>
					<?php
				}
				else
				{
					?>
                    <div class="tab-pane fade" id="v-pills-<?php echo $cate->virtuemart_category_id ?>"
                         role="tabpanel" aria-selected="false"
                         aria-labelledby="v-pills-<?php echo $cate->virtuemart_category_id ?>">
                        <div class="col-12" role="tabpanel"><h6 class="text-center">Khong Co San Pham</h6></div>
                    </div>
					<?php
				}
			}
		?>
    </div>

</div>
<div class="row xemthem">
    <a class="d-block nav-item loadmore"
		<?php
			$caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id);
		?>
       href="<?php echo $caturl ?>"
       target="_blank">Xem Them </a>
</div>
<style>
    div#tablistproducbigpig {
        overflow: auto;
        white-space: nowrap;
        flex-wrap: nowrap;
    }

    /* .col-3 {
        display: grid;
        grid-template-columns: auto auto auto auto;
        grid-template-rows: 100px 300px;
        grid-gap: 10px;
    } */

    .xemthem {
        display: flex;
        flex-direction: column;
        -webkit-box-align: center;
        align-items: center;
    }
</style>
