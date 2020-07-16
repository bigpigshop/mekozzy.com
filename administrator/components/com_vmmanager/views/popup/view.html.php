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
defined('_JEXEC') or die;
class VmmanagerViewPopup extends JViewLegacy
{
	public function display($tpl = null)
	{
		if (count($errors = $this->get('Errors')))
		{
			JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');
			return false;
		}
        $vmlang = JFactory::getLanguage();
        $vmlang->load('com_virtuemart', JPATH_SITE.'/components/com_virtuemart');
		parent::display($tpl);
        JFactory::getApplication()->close();
	}
    public function getFieldInfo($array){
        if (empty($array)){
            return false;
        }
        $where = array();
        foreach($array as $key=>$value){
            if($value['formcode']){
                $where[] = "'".$value['name']."'";
            }
        }
        $where = join(',',$where);
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query = 'SELECT `virtuemart_userfield_id`,`name`,`title`,`ordering`
                    FROM `#__virtuemart_userfields`
                    WHERE `name` IN('.$where.')
                    ORDER BY `ordering`';
        $db->setQuery($query);
        $result = $db->loadObjectlist();
        return $result;
    }
}
