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
	
	use Joomla\CMS\Factory;
	
	$document = Factory::getDocument();
	$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/typography.css');
	$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/style.css');
	$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/responsive.css');
	$document->addScript('modules/mod_bigpig_category_product_detail/assets/js/bigpig.js');
	
	if (!class_exists('VmConfig')) require(JPATH_ROOT . '/administrator/components/com_virtuemart/helpers/config.php');
	VmConfig::loadConfig();
	if (!class_exists('VmConfig')) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');
	VmConfig::loadConfig();
	if (!class_exists('VmModel')) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'vmmodel.php');
	
	$categoryModel = VmModel::getModel('Category');
	
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select('*')
		->from("#__virtuemart_category_categories a")
		->leftJoin('#__virtuemart_categories_vi_vn as c ON c.virtuemart_category_id = a.category_child_id')
		->where($db->quoteName('a.category_parent_id') . ' = 0');
	$db->setQuery($query);
	$result = $db->loadObjectList();

?>
<div class="col-sm-12">
	<div class="iq-card position-relative inner-page-bg bg-warning" style="height: 100px;margin-top: 20px;">
		<div class="inner-page-title">
			<h3 class="text-white">Danh Muc</h3>
			<p class="text-white">Mekozzy.com</p>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-3">
		<!--        <div class="iq-sidebar">-->
		<div>
			<div id="sidebar-scrollbar">
				<ul id="iq-sidebar-toggle" class="iq-menu">
					<?php
						foreach ($result as $category) {
							$db = JFactory::getDbo();
							$query = $db->getQuery(true);
							$query->select('*')
								->from("#__virtuemart_category_medias a")
								->leftJoin('#__virtuemart_medias as c ON c.virtuemart_media_id = a.virtuemart_media_id')
								->where($db->quoteName('a.virtuemart_category_id') . ' = ' . $category->virtuemart_category_id);
							$db->setQuery($query);
							$json = $db->loadObject();
							?>
							<li>
								<a class="iq-waves-effect"
								   onclick="openPage('category_<?php echo $category->virtuemart_category_id; ?>')">
									<div class="media align-items-center mb-4">
										<div class="profile-avatar">
											<img class="rounded-circle avatar-50"
												 src="<?php echo is_file($json->file_url) ? $json->file_url : 'images/noimages.png' ?>"
												 alt="">
											<a><?php echo $category->category_name ?></a>
										</div>
										<div class="media-body ml-3">
											<h6 class="mb-0"></h6>
										</div>
									</div>
								</a>
							</li>
							<?php
						}
					?>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-9">
		<?php
			
			foreach ($result as $i => $category) {
				$categories[$category->virtuemart_category_id]->childs = $categoryModel->getChildCategoryList(1, $category->virtuemart_category_id);
				if ($level > 2) {
					foreach ($categories[$category->virtuemart_category_id]->childs as $j => $cat) {
						$categories[$category->virtuemart_category_id]->childs[$cat->virtuemart_category_id]->childs = $categoryModel->getChildCategoryList(1, $cat->virtuemart_category_id);
					}
				}
			}
			
			foreach ($categories as $key => $cate) {
				?>
				<div id="category_<?php echo $key ?>" class="tabcontent pull-left iq-card-body">
					
					<?php
						foreach ($cate->childs as $cat) {
							
							$query2 = $db->getQuery(true);
							$query2->select('*')
								->from("#__virtuemart_category_medias a")
								->leftJoin('#__virtuemart_medias as c ON c.virtuemart_media_id = a.virtuemart_media_id')
								->where($db->quoteName('a.virtuemart_category_id') . ' = ' . $cat->virtuemart_category_id);
							$db->setQuery($query2);
							$image = $db->loadObject();
							?>
							<div class="pull-left">
								<a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $cat->virtuemart_category_id); ?>">
									<p class="danhmuc"><?php echo $cat->category_name ?></p>
									<img src="<?php echo is_file($image->file_url) ? $image->file_url : 'images/noimages.png' ?>"
										 style="max-width: 50px;" class="img-thumbnail"
										 alt="<?php echo $cat->category_name ?>">
								</a>
							</div>
							<?php
						}
					?>
				</div>
			<?php } ?>
	</div>
</div>
<style>
	@media (max-width: 1499px) and (min-width: 1300px) {
		.iq-sidebar {
			/*width: 200px;*/
		}
	}
	.danhmuc {
		display: block;
		width: 100px;
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
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
		elmnt.style.backgroundColor = color;
	}
	document.getElementById("defaultOpen").click();
</script>