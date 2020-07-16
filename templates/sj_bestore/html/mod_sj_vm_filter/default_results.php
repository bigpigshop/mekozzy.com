<?php
/**
 * @package Sj Filter for VirtueMart
 * @version 3.0.1
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2015 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;
$document = JFactory::getDocument();
$app = JFactory::getApplication();
//$templateDir = JURI::base() . 'templates/' . $app->getTemplate().'/html/com_virtuemart/category/default.php';

?>

<div id="ft_results_<?php echo $this->_module->id; ?>" class="ft-results">
	<?php
	if (!empty($this->products)) {
		//$path_template = JPATH_VM_SITE . DS . 'views' . DS . 'category' . DS . 'tmpl' . DS . 'default.php';
		
		$path_template = JPATH_BASE . '/templates/' . $app->getTemplate().'/html/com_virtuemart/category/default.php';
		
		if (file_exists($path_template)) {
			require($path_template);
		}
	} else {
		echo '<span id="close-sidebar" class="fa fa-times hidden-lg hidden-md"></span>';
		echo '<a href="javascript:void(0)" class="open-sidebar hidden-lg hidden-md"><i class="fa fa-bars"></i>Sidebar</a><div class="sidebar-overlay"></div>';
		echo '<p>No Results</p>';
	}
	?>
</div>