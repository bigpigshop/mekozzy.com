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
if ($this->display_title) {
	echo "<h3>".vmText::_('COM_VIRTUEMART_CART_ORDERDONE_THANK_YOU')."</h3>";
}
$this->html = vRequest::get('html', vmText::_('COM_VIRTUEMART_ORDER_PROCESSED') );
echo $this->html;
$cuser = JFactory::getUser();
if(!$cuser->guest) echo shopFunctionsF::getLoginForm ();

