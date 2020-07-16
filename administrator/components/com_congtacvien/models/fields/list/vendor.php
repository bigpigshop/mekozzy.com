<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

JFormHelper::loadFieldClass('list');

/**
 * Category Edit field..
 *
 * @since  1.6
 */
class JFormFieldList_Vendor extends JFormFieldList
{
    /**
     * To allow creation of new categories.
     *
     * @var    integer
     * @since  3.6
     */
    protected $allowAdd;

    /**
     * Optional prefix for new categories.
     *
     * @var    string
     * @since  3.9.11
     */
    protected $customPrefix;

    /**
     * A flexible category list that respects access controls
     *
     * @var    string
     * @since  1.6
     */
    public $type = 'List_Vendor';


    /**
     * Method to get a list of categories that respects access controls and can be used for
     * either category assignment or parent category assignment in edit screens.
     * Use the parent element to indicate that the field will be used for assigning parent categories.
     *
     * @return  array  The field option objects.
     *
     * @since   1.6
     */
    protected function getOptions()
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('vendor_store_name AS text, CONCAT_WS(":", virtuemart_vendor_id, slug) AS value')
            ->from('#__virtuemart_vendors_vi_vn')
            ->order('vendor_store_name')
        ;

        $items = $db->setQuery($query)->loadObjectList();

        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $items);

        return $options;
    }
}
