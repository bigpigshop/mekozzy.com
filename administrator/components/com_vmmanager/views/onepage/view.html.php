<?php

/** ------------------------------------------------------------------------
  Virtuemart manager
  author CMSMart Team
  copyright: Copyright (c) 2012 http://cmsmart.net. All Rights Reserved.
  @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
  Websites: http://cmsmart.net
  Email: team@cmsmart.net
  Technical Support: Forum - http://cmsmart.net/forum
  ------------------------------------------------------------------------- */
defined('_JEXEC') or die;

class VmmanagerViewonepage extends JViewLegacy {

    public function display($tpl = null) {
        require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/translateJs.php');
        if (count($errors = $this->get('Errors'))) {
            JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');
            return false;
        }
        //get version from cmsmart.net
        $this->get_version_api = json_decode(file_get_contents("https://cmsmart.net/api/product_version/netbase/45", false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))));
        
        $this->addToolbar();
        $popup = JFactory::getApplication()->input->get('popup', 0);
        if ($popup) {
            $tpl = 'popup';
        }
        $vmlang = JFactory::getLanguage();
        $vmlang->load('com_virtuemart', JPATH_SITE . '/components/com_virtuemart');
        parent::display($tpl);
        if ($popup) {
            JFactory::getApplication()->close();
        }
    }

    protected function addToolbar() {
        JToolbarHelper::title(JText::_('COM_VM_MANAGER_TITLE'), 'vm-manager');
    }

}
