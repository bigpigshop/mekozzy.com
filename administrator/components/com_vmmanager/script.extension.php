<?php
//@license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
defined('_JEXEC') or die('Restricted access');
$max_execution_time = ini_get ('max_execution_time');
if ((int)$max_execution_time < 120) {
	@ini_set ('max_execution_time', '120');
}
$memory_limit = (int)substr (ini_get ('memory_limit'), 0, -1);
if ($memory_limit < 128) {
	@ini_set ('memory_limit', '128M');
}
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.path');
class com_vmmanagerInstallerScript{
	function install($parent)
	{
		
	}
	function uninstall($parent)
	{
		$plg   = $this->getextension('plugin','onestepcheckout');
		$installer = new JInstaller();
        if(!empty($plg)){
            $installer->uninstall($plg->type,$plg->extension_id);
        }		
	}
	function update($parent)
	{

	}
	//before
	function preflight($type, $parent)
	{

	}
	//after
	function postflight($type, $parent)
	{
		$type = strtolower($type);//convert text
		if($type == 'install' || $type == 'update'){
		  
            $extFolder = JPATH_ADMINISTRATOR.'/components/com_vmmanager/extension';
    		$exts = JFolder::folders($extFolder);
            $installer = new JInstaller();
            foreach ($exts as $ext){
				if($type == 'install'){
					$result = $installer->install($extFolder.'/'.$ext);
				}else{
					$result = $installer->update($extFolder.'/'.$ext);
				}    			
				if ($result){
					echo '<br /><b style="color:green;">DONE: '.$type.' '.$ext.' complete</b><br />';
				}else{
					echo '<b style="color:red;">FAILED: Not '.$type.' '.$ext.'</b><br />';
				}
			}
			echo '<br /><br />';
			$this->setplugin('plugin','onestepcheckout');
		}
	}
    public function setplugin($type,$element){
		$published = 1;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('extension_id');
		$query->from('#__extensions');
		$query->where('element='.$db->quote($element));
        $query->where('type='.$db->quote($type));
		$db->setQuery($query);
		$r = $db->loadObject();
		if ($r)
		{
			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__extensions'))
			->set($db->quoteName('enabled').' = '.$db->quote($published))
			->where($db->quoteName('extension_id').' = '.$db->quote($r->extension_id));
			$db->setQuery($query);
			$db->query();
		}
    }   
	public function getextension($type,$element){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('extension_id,type');
		$query->from('#__extensions');
		$query->where('element='.$db->quote($element));
        $query->where('type='.$db->quote($type));
		$db->setQuery($query);
		$r = $db->loadObject();
		return $r;
	}
}