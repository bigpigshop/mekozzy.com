<?php
/**
 * @package BIGPIG CATEGORY TABS
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2020 Mekozzy Company. All Rights Reserved.
 * @author BIGPIG 
 *
 */

defined('_JEXEC') or die;
if (!class_exists( 'VmConfig' )) require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');

VmConfig::loadConfig();

$layout = $params->get('layout', 'default');
$catid = $params->get('catid', 0);
VmConfig::loadConfig();
$categoryModel = VmModel::getModel('category');
$categoryModel->_noLimit = true;
$vendorId = '1';
$level = 10;
$categories = $categoryModel->getChildCategoryList($vendorId, $catid[0]);

// $list = VMListingTabsHelper::getCategories(true,false,false,"",false);
if (isset($categories) && count($categories) > 0)
{
	require JModuleHelper::getLayoutPath($module->module, $layout);
} else
{
	echo 'Has no Item';
}
