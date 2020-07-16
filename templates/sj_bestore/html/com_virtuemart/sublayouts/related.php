<?php defined('_JEXEC') or die('Restricted access');

$related = $viewData['related'];
$customfield = $viewData['customfield'];
$thumb = $viewData['thumb'];


//juri::root() For whatever reason, we used this here, maybe it was for the mails
echo JHtml::link (JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $related->virtuemart_product_id . '&virtuemart_category_id=' . $related->virtuemart_category_id), $thumb   . $related->product_name, array('title' => $related->product_name,'target'=>'_blank'));
if($customfield->wPrice){
	$currency = calculationHelper::getInstance()->_currencyDisplay;
	echo $currency->createPriceDiv ('salesPrice', '', $related->prices);
}
