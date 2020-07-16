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
	if (!class_exists( 'VmConfig' )) require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');
	
	VmConfig::loadConfig();
	
	$layout = $params->get('layout', 'default');
	if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'config.php');
	VmConfig::loadConfig();
	if (!class_exists( 'VmModel' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'vmmodel.php');
	$categoryModel = VmModel::getModel('Category');
//	$cats = $categoryModel->getCategoryTree();
	
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select('*')
		->from("#__virtuemart_category_categories a")
		->leftJoin('#__virtuemart_categories_vi_vn as c ON c.virtuemart_category_id = a.category_child_id')
		->where($db->quoteName('a.category_parent_id') . ' = 0');
	$db->setQuery($query);
	$result = $db->loadObjectList();
	
	if (isset($result) && count($result) > 0)
	{
		require JModuleHelper::getLayoutPath($module->module, $layout);
	} else
	{
		echo 'Has no Item';
	}
