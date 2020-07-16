<?php
/** ------------------------------------------------------------------------
Virtuemart manager
author CMSMart Team
copyright: Copyright (c) 2012 http://cmsmart.net. All Rights Reserved.
@license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
Websites: http://cmsmart.net
Email: team@cmsmart.net
Technical Support: Forum - http://cmsmart.net/forum
-------------------------------------------------------------------------*/
ini_set('display_errors',0);
defined('_JEXEC') or die('Restricted access');
if (!JFactory::getUser()->authorise('core.manage', 'com_vmmanager')) {
	return JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
}
$doc = JFactory::getDocument();
$doc->addStyleSheet('components/com_vmmanager/assets/fonts/Raleway/Raleway.css');
$doc->addStyleSheet('../components/com_vmmanager/assets/icons/css/opc-fonts.css');
$doc->addStyleSheet('components/com_vmmanager/assets/css/media.min.css');
$doc->addStyleSheet('components/com_vmmanager/assets/css/style.min.css');
$doc->addStyleSheet('components/com_vmmanager/assets/css/gridstack.min.css');
$doc->addStyleSheet('components/com_vmmanager/assets/css/popup.min.css');
$doc->addStyleSheet('//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic');
$controller = JControllerLegacy::getInstance('vmmanager');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();