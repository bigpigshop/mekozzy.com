<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Routing class from com_tags
 *
 * @since  3.3
 */
class CongtacvienRouter extends JComponentRouterBase
{
    /**
     * Build the route for the com_tags component
     *
     * @param   array  &$query  An array of URL arguments
     *
     * @return  array  The URL arguments to use to assemble the subsequent URL.
     *
     * @since   3.3
     */
    public function build(&$query)
    {
        $segments = array();

        // Get a menu item based on Itemid or currently active
        $params = JComponentHelper::getParams('com_congtacvien');

        // We need a menu item.  Either the one specified in the query, or the current active one if none specified
        if (empty($query['Itemid']))
        {
            $menuItem = $this->menu->getActive();
        }
        else
        {
            $menuItem = $this->menu->getItem($query['Itemid']);
        }

        $mView = empty($menuItem->query['view']) ? null : $menuItem->query['view'];
        $mId   = empty($menuItem->query['id']) ? null : $menuItem->query['id'];

        if (is_array($mId))
        {
            $mId = ArrayHelper::toInteger($mId);
        }

        $view = '';

        if (isset($query['view']))
        {
            $view = $query['view'];

            if (empty($query['Itemid']))
            {
                $segments[] = $view;
            }

            unset($query['view']);
        }


        if (isset($query['layout']))
        {
            if ((!empty($query['Itemid']) && isset($menuItem->query['layout'])
                    && $query['layout'] == $menuItem->query['layout'])
                || $query['layout'] === 'default')
            {
                unset($query['layout']);
            }
        }

        if (!isset($query['layout']) && isset($query['id']))
        {
            $segments[] = $this->getIdSegment($query['id']);
            unset($query['id']);
        }

        $total = count($segments);

        return $segments;
    }

    /**
     * Parse the segments of a URL.
     *
     * @param   array  &$segments  The segments of the URL to parse.
     *
     * @return  array  The URL attributes to be used by the application.
     *
     * @since   3.3
     */
    public function parse(&$segments)
    {
        $total = count($segments);
        $vars = array();

        // Get the active menu item.
        $item = $this->menu->getActive();

        // Count route segments
        $count = count($segments);

        // Standard routing for tags.
        if (!isset($item))
        {
            $vars['view'] = $segments[0];
            $vars['id']   = $this->fixSegment($segments[$count - 1]);

            return $vars;
        } else {
            if ($item->query['view'] == 'shop') {
                $vars['id'] = $this->fixSegment($segments[0]);
                $vars['view'] = 'shop';
            }

        }

        if ($count==2 && $vars[0] == 'shop') {
            $vars['id'] = $this->fixSegment($segments[1]);
            $vars['view'] = 'shop';
        }

        return $vars;
    }

    /**
     * Try to add missing id to segment
     *
     * @param   string  $segment  One piece of segment of the URL to parse
     *
     * @return  string  The segment with founded id
     *
     * @since   3.7
     */
    protected function fixSegment($segment)
    {
        $db = JFactory::getDbo();
        // Try to find tag id

        $query = $db->getQuery(true)
            ->select('a.virtuemart_vendor_id as id')
            ->from('#__virtuemart_vendors as a')
            ->leftJoin('#__virtuemart_vendors_vi_vn as b ON b.virtuemart_vendor_id = a.virtuemart_vendor_id')
            ->where('b.slug = ' . $db->quote($segment));

        $id = $db->setQuery($query)->loadResult();

        if ($id)
        {
            $segment = "$id:$segment";
        }

        return $segment;
    }

    protected function getIdSegment($segment)
    {

        $tmp = explode(':', $segment, 2);

        if (count($tmp) <= 1) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('b.slug as alias')
                ->from('#__virtuemart_vendors as a')
                ->leftJoin('#__virtuemart_vendors_vi_vn as b ON b.virtuemart_vendor_id = a.virtuemart_vendor_id')
                ->where('a.virtuemart_vendor_id = ' . (int)$tmp[0]);
            $segment = $db->setQuery($query)->loadResult();
        }

        return $segment;
    }
}
