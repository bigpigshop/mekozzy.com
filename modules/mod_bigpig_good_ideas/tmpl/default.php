<?php
	/**
	 * @package MOD_BIGPIG_CHILD_CATEGORY
	 * @version 1.0.0
	 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 * @copyright (c) 2020 Mekozzy Company. All Rights Reserved.
	 * @author BIGPIG
	 *
	 */
	
	defined('_JEXEC') or die;
	
//	use Joomla\CMS\Factory;
//
//	$document = Factory::getDocument();
//	$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/typography.css');
//	$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/style.css');
//	$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/responsive.css');
//	$document->addScript(  'modules/mod_bigpig_category_product_detail/assets/js/bigpig.js');
	
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select('*')
		->from("#__virtuemart_category_categories a")
		->leftJoin('#__virtuemart_categories_vi_vn as c ON c.virtuemart_category_id = a.category_child_id')
		->where($db->quoteName('a.category_parent_id') . ' = 0');
	$db->setQuery($query);
	$result = $db->loadObjectList();
?>
<div class="category-view bigpigideas">
	<div style="float: right" class="row xemthem">
		<a class="d-block nav-item loadmore"
		   href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>"
		   target="_blank">Xem Them </a>
	</div>
	<div class="clear"></div>
	<ul class="row goodideas">
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
					<div class="item-inner clearfix">
						<div class="item-image">
							<a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
								<img src="images/010.png" alt="Y tuong Hay"> </a>
						</div>
						<div class="item-content">
							<div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
							<div class="item-description">
								
								<p class="product_s_desc hidden-xs">
									asdasdasdas
								</p>
							</div>
						</div>
					</div>
				</li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/00000001.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/0000000000.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/003.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/003a.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/005.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/005a.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/027.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/007.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/023.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/27m.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/028.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/0000005.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/21.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/63m.jpeg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/068.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/7777.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/6666.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/Anh_chup_Man_hinh_2020-06-15_luc_84157_CH.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/Anh_chup_Man_hinh_2020-06-15_luc_82121_CH.png" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/Capture002.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/Capture.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/c_ch.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/Colorful-Throw-Pillows.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/kitchen-ventilation.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/metallic-track-lights.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/project_prism_color_search_archive_a29b608ee6278f52f987e98514f4d8eeb2acc323.jpeg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/small-bedroom-ideas-1_1.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/shutterstock_709983043_huge.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/sarti-is-only-too-happy-to-demonstrate-one-of-his-favorite-inventions-a-mobile-kitchen-supply-boxcocktail-stationbreakfast-bar-with-casters-that-hides-beneath-the-stairs.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/small-bedroom-ideas-9.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/small-bedroom-ideas-10.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/Wood-Textures-in-the-Kitchen-Contrast.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/small-bedroom-ideas-7.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/trendy-kitchen.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/Wood-Textures-in-the-Kitchen-Rustic.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/cuoc_song_gia_tri_qpzz.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 ">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="Y tuong Hay" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>" >
                        <img src="images/cau_thang_go_gkconcept.jpg" alt="Y tuong Hay"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">Kho Ý Tưởng Hay - mekozzy </a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
                            Ý Tưởng Hay
                        </p>
                    </div>
                </div>
            </div>
        </li>
        
        <div class="clear"></div>
	</ul>
	<div class=" text-center">
		<button type="submit" id="loadMoreideas" class="btn btn-primary">Load More</button>
	</div>
	
	<div class="clear"></div>

</div>
<style>
	ul.goodideas>li{
		display: none;
		padding: 10px;
		margin-bottom: 5px;
	}
	
	ul.goodideas>li>a>img{
		display: block;
		height: 80px;
		margin-left: auto;
		margin-right: auto;
		width: 50%;
		border-radius: 10px;
	}
	
	
	#loadMoreideas {
		padding: 10px;
		text-align: center;
		box-shadow: 0 1px 1px #ccc;
		transition: all 600ms ease-in-out;
		-webkit-transition: all 600ms ease-in-out;
		-moz-transition: all 600ms ease-in-out;
		-o-transition: all 600ms ease-in-out;
	}

</style>
<script>
    jQuery(function ($) {
        $(function () {
            $("ul.goodideas>li").slice(0, 8).show();

            $("#loadMoreideas").on('click', function (e) {
                e.preventDefault();
                $("ul.goodideas >li:hidden").slice(0,8).slideDown();
                if ($("ul.goodideas >li:hidden").length == 0) {
                    $("#load").fadeOut('slow');
                }
                $('html,body').animate({
                    scrollTop: $(this).offset().top
                }, 1500);
            });
        });

    });
</script>
