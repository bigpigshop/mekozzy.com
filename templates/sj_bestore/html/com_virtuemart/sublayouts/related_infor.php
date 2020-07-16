<?php defined('_JEXEC') or die('Restricted access');
	
	$related = $viewData['related'];
	$customfield = $viewData['customfield'];
	$thumb = $viewData['thumb'];


	$data['name'] = $related->product_name;
	$data['virtuemart_product_id'] = $related->virtuemart_product_id;
	$data['virtuemart_category_id'] = $related->virtuemart_category_id;
	$data['thumb'] = $related->thumb;
	$data['related'] = $related->related;

	
	echo $related;
	
