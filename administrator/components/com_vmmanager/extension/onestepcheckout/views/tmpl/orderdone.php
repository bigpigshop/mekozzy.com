<?php
/** ------------------------------------------------------------------------
One Page Checkout
author CMSMart Team
copyright: Copyright (c) 2012 http://cmsmart.net. All Rights Reserved.
@license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
Websites: http://cmsmart.net
Email: team@cmsmart.net
Technical Support: Forum - http://cmsmart.net/forum
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
echo '<div class="vm-wrap vm-order-done">';

if (vRequest::getBool('display_title',true)) {
	echo '<h3>'.vmText::_('COM_VIRTUEMART_CART_ORDERDONE_THANK_YOU').'</h3>';
}

echo $this->html;

if (vRequest::getBool('display_loginform',true)) {
	$cuser = JFactory::getUser();
	if (!$cuser->guest) echo shopFunctionsF::getLoginForm();
}
echo '</div>';