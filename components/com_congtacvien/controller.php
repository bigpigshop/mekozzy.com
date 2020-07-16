<?php
/**
 * @package    ThuVien
 * @subpackage C:
 * @author     Hau Pham {@link }
 * @author     Created on 20-Jul-2017
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');


jimport('joomla.application.component.controller');

use Joomla\Registry\Registry;
use Symfony\Component\Yaml\Yaml;
use Joomla\CMS\Response\JsonResponse;

JLoader::register('CTVHelperRoute', JPATH_COMPONENT . '/helpers/route.php');
JLoader::register('CTVHelper', JPATH_COMPONENT . '/helpers/helper.php');

if (!class_exists( 'VmConfig' ))
    require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');

VmConfig::loadConfig();
vmLanguage::loadJLang('com_virtuemart', true);
//vmJsApi::jQuery();
//vmJsApi::cssSite();

/**
 * ThuVien Controller.
 *
 * @package    ThuVien
 * @subpackage Controllers
 */
class CTVController extends JControllerLegacy
{
        
    /**
	 * Method to display a view.
	 *
	 * @param   boolean        $cachable   If true, the view output will be cached
	 * @param   mixed|boolean  $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
	 *
	 * @since   3.1
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$user = JFactory::getUser();

		// Set the default view name and format from the Request.
		$vName = $this->input->get('view', 'dashboard');
		$this->input->set('view', $vName);

		if ($user->get('id') || ($this->input->getMethod() === 'POST' && $vName === 'dashboard'))
		{
			$cachable = false;
		}

		$safeurlparams = array(
			'id'               => 'ARRAY',
			'type'             => 'ARRAY',
			'limit'            => 'UINT',
			'limitstart'       => 'UINT',
			'filter_order'     => 'CMD',
			'filter_order_Dir' => 'CMD',
			'lang'             => 'CMD'
		);

		return parent::display($cachable, $safeurlparams);
	}
    

}
