<?php
	
	defined('_JEXEC') or die;
	if(!isset($params) || !(count($params) > 0)) return;
	require_once dirname(__FILE__) . '/core/helper.php';
	
	$layout = $params->get('layout', 'defaultbigpig');
	$cacheid = md5(serialize(array($layout, $module->id)));
	$cacheparams = new stdClass;
	$cacheparams->cachemode = 'id';
	$cacheparams->class = 'BigPigGoodIdeasHelper';
	$cacheparams->method = 'getList';
	$cacheparams->methodparams = $params;
	$cacheparams->modeparams = $cacheid;
	$list = JModuleHelper::moduleCache($module, $params, $cacheparams);

	require JModuleHelper::getLayoutPath('mod_bigpig_good_ideas', 'defaultbigpig');?>

