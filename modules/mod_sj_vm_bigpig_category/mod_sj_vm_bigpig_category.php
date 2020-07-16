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

$layout = $params->get('layout', 'default');
$categoryModel = VmModel::getModel('category');
$categoryModel->_noLimit = true;
$list = $categoryModel->getCategories( 0 );
// $list = VMListingTabsHelper::getCategories(true,false,false,"",false);
if (isset($list) && count($list) > 0)
{
	require JModuleHelper::getLayoutPath($module->module, $layout);
} else
{
	echo 'Has no Item';
}

?>
