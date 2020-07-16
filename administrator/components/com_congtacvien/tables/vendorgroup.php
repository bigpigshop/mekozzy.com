<?php
/**
 * @version		$Id: frontpage.php 10381 2008-06-01 03:35:53Z pasamio $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * @package		Joomla
 * @subpackage	Content
 */
class CTVTableVendorGroup extends JTable
{
    /** @var int Primary key */
    var $id = null;
    var $virtuemar_vendor_id = null;
    var $doituong_code = 'CTVCT';
    var $created = null;
    var $published_on = null;
    var $expired_on = null;
    var $state = 1;

    /**
     * @param database A database connector object
     */
    function __construct( &$db ) {
        parent::__construct( '#__congtacvien_vendor_group', 'id', $db );
    }

}