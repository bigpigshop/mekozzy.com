<?php
/**
 * @package    api
 * @subpackage C:
 * @author     Hau Pham {@link jooext.com}
 * @author     Created on 02-Oct-2017
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die;

use Joomla\Registry\Registry;
use Symfony\Component\Yaml\Yaml;
use Joomla\CMS\Response\JsonResponse;

JLoader::register('CTVHelperRoute', JPATH_COMPONENT . '/helpers/route.php');
JLoader::register('CTVHelper', JPATH_COMPONENT . '/helpers/helper.php');

// Set the table directory
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');

if (!class_exists( 'VmConfig' ))
    require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');

$controller = JControllerLegacy::getInstance('CTV');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
