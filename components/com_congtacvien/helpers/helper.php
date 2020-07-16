<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


if (!class_exists( 'VmConfig' ))
    require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');


abstract class CTVHelper
{
    static public function getVendorID()
    {
        $user = JFactory::getUser();
        $vendor_id = VirtueMartModelVendor::getVendorId('user', $user->id);
        return $vendor_id;
    }

}