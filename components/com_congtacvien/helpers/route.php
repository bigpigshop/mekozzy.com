<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Bucketlist Component Route Helper
 *
 * @package     Bucketlist
 * @subpackage  Helpers
 */
abstract class CTVHelperRoute
{
	protected static $lookup;

	protected static $lang_lookup = array();

    static public function getVendorShop($vendor_id, $sef = true)
    {
        $params = JComponentHelper::getParams('com_congtacvien');

        $link = 'index.php?option=com_congtacvien&view=shop&id='.$vendor_id . "&Itemid=".$params->get('vendor_shopmenu_id', 0);
        if ($sef) {
            $link = JRoute::_($link);
        }

        return $link;
    }

}