<?php
/**
 * @package   OSMap
 * @copyright 2007-2014 XMap - Joomla! Vargas. All rights reserved.
 * @copyright 2016-2017 Open Source Training, LLC. All rights reserved..
 * @author    Guillermo Vargas <guille@vargas.co.cr>
 * @author    Joomlashack <help@joomlashack.com>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 *
 * This file is part of OSMap.
 *
 * OSMap is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * OSMap is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OSMap. If not, see <http://www.gnu.org/licenses/>.
 */

defined('_JEXEC') or die('Restricted access');

use Alledia\OSMap;
use Alledia\OSMap\Sitemap\Collector;
use Alledia\OSMap\Sitemap\Item;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

if (!class_exists('VmConfig')) {
    $vmPath = JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
    if (is_file($vmPath)) {
        require_once $vmPath;
    }
}
if (class_exists('VmConfig')) {
    // Make sure Virtuemart is installed and initialized
    VmConfig::loadConfig();
}

class osmap_com_virtuemart extends OSMap\Plugin\Base implements OSMap\Plugin\ContentInterface
{
    /**
     * @var array
     */
    protected static $categoriesCache = array();

    /**
     * @var static
     */
    protected static $instance = null;

    /**
     * @var VirtueMartModelProduct
     */
    protected static $productModel = null;

    /**
     * @var VirtueMartModelCategory
     */
    protected static $categoryModel = null;

    public static function getInstance()
    {
        if (empty(static::$instance)) {
            $dispatcher = \JEventDispatcher::getInstance();
            $instance   = new static($dispatcher);

            static::$instance = $instance;
        }

        return static::$instance;
    }

    public function getComponentElement()
    {
        return 'com_virtuemart';
    }

    /**
     * This function is called before a menu item is used. We use it to set the
     * proper uniqueid for the item
     *
     * @param Item $node
     * @param Registry $params
     *
     * @return bool
     * @throws Exception
     */
    public static function prepareMenuItem($node, $params)
    {
        $app = JFactory::getApplication();

        $linkQuery = parse_url($node->link);

        parse_str(html_entity_decode($linkQuery['query']), $linkVars);

        $view = ArrayHelper::getValue($linkVars, 'view', '');

        $catId  = ArrayHelper::getValue($linkVars, 'virtuemart_category_id', 0);
        $prodId = ArrayHelper::getValue($linkVars, 'virtuemart_product_id', 0);

        if (!in_array($view, array('categories', 'category'))) {
            if (empty($catId)) {
                $menu       = $app->getMenu();
                $menuParams = $menu->getParams($node->id);
                $catId      = $menuParams->get('virtuemart_category_id', 0);
            }

            if (empty($prodId)) {
                $menu       = $app->getMenu();
                $menuParams = $menu->getParams($node->id);
                $prodId     = $menuParams->get('virtuemart_product_id', 0);
            }

            if ($prodId && $catId) {
                $node->uid        = 'com_virtuemartc' . $catId . 'p' . $prodId;
                $node->expandible = false;

            } elseif ($catId) {
                $node->uid        = 'com_virtuemartc' . $catId;
                $node->expandible = true;
            }

        } else {
            $node->uid        = 'com_virtuemart.' . $view;
            $node->expandible = true;
        }

        return true;
    }

    /**
     * Expand a com_virtuemart menu item
     *
     * @param Collector $collector
     * @param Item      $parent
     * @param Registry  $params
     *
     * @return void
     * @throws Exception
     */
    public static function getTree($collector, $parent, $params)
    {
        if (!class_exists('VmConfig')) {
            // Let's assume Virtuemart is not installed
            return;
        }

        $linkQuery = parse_url($parent->link);

        parse_str(html_entity_decode($linkQuery['query']), $linkVars);

        $params['Itemid'] = intval(ArrayHelper::getValue($linkVars, 'Itemid', $parent->id));

        $categories = array();
        if (isset($linkVars['virtuemart_category_id'])) {
            $categories[] = intval(ArrayHelper::getValue($linkVars, 'virtuemart_category_id'));
        } else {
            // We don't have a category set for the current menu/view. Let's use the global setting
            $categories = ArrayHelper::getValue($params, 'global_categories', array());
        }

        if (empty($categories)) {
            return;
        }

        $params['include_products']          = (int)ArrayHelper::getValue($params, 'include_products', 1);
        $params['include_product_images']    = ArrayHelper::getValue($params, 'include_product_images', 1);
        $params['product_image_license_url'] = trim(ArrayHelper::getValue($params, 'product_image_license_url', ''));
        $params['product_image_limit']       = (int)ArrayHelper::getValue($params, 'product_image_limit', 1);

        $priority   = ArrayHelper::getValue($params, 'cat_priority', $parent->priority);
        $changefreq = ArrayHelper::getValue($params, 'cat_changefreq', $parent->changefreq);

        if ($priority == '-1') {
            $priority = $parent->priority;
        }

        if ($changefreq == '-1') {
            $changefreq = $parent->changefreq;
        }

        $params['cat_priority']   = $priority;
        $params['cat_changefreq'] = $changefreq;

        $priority   = ArrayHelper::getValue($params, 'prod_priority', $parent->priority);
        $changefreq = ArrayHelper::getValue($params, 'prod_changefreq', $parent->changefreq);

        if ($priority == '-1') {
            $priority = $parent->priority;
        }

        if ($changefreq == '-1') {
            $changefreq = $parent->changefreq;
        }

        $params['prod_priority']   = $priority;
        $params['prod_changefreq'] = $changefreq;

        if (!empty($categories)) {
            foreach ($categories as $catId) {
                self::getCategoryTree($collector, $parent, $params, $catId);
            }
        }

        self::$categoriesCache = array();
    }

    /**
     * @param Collector $collector
     * @param Item      $parent
     * @param Registry  $params
     * @param int       $catId
     *
     * @return void
     * @throws Exception
     */
    public static function getCategoryTree($collector, $parent, $params, $catId = null)
    {
        $children = self::getChildCategories($catId);

        if (!empty($children)) {
            $collector->changeLevel(1);

            foreach ($children as $row) {
                $linkQuery = array(
                    'option'                 => 'com_virtuemart',
                    'view'                   => 'category',
                    'virtuemart_category_id' => $row->virtuemart_category_id,
                    'Itemid'                 => $parent->id
                );

                $node = (object)array(
                    'id'         => $parent->id,
                    'uid'        => $parent->uid . 'c' . $row->virtuemart_category_id,
                    'browserNav' => $parent->browserNav,
                    'name'       => htmlspecialchars_decode(stripslashes($row->category_name)),
                    'priority'   => $params['cat_priority'],
                    'changefreq' => $params['cat_changefreq'],
                    'expandible' => true,
                    'link'       => 'index.php?' . http_build_query($linkQuery, null, '&amp;')
                );

                if ($params['include_product_images']) {
                    $node->images = array();
                }

                if ($collector->printNode($node) !== false) {
                    self::getCategoryTree($collector, $parent, $params, $row->virtuemart_category_id);
                }

                $node = null;
            }

            $children = null;

            $collector->changeLevel(-1);
        }

        if ($params['include_products'] > 0 && !is_null($catId)) {
            $collector->changeLevel(1);

            $products = self::getProducts($catId);

            foreach ($products as $row) {
                $linkQuery = array(
                    'option'                 => 'com_virtuemart',
                    'view'                   => 'productdetails',
                    'virtuemart_product_id'  => $row->virtuemart_product_id,
                    'virtuemart_category_id' => $row->virtuemart_category_id,
                    'Itemid'                 => $parent->id
                );

                $node = (object)array(
                    'id'             => $parent->id,
                    'uid'            => "{$parent->uid}c{$row->virtuemart_category_id}p{$row->virtuemart_product_id}",
                    'browserNav'     => $parent->browserNav,
                    'priority'       => $params['prod_priority'],
                    'changefreq'     => $params['prod_changefreq'],
                    'name'           => htmlspecialchars_decode($row->product_name),
                    'modified'       => strtotime($row->modified_on),
                    'expandible'     => false,
                    'link'           => 'index.php?' . http_build_query($linkQuery, null, '&amp;'),
                    'visibleForXML'  => in_array($params['include_products'], array(1, 2)),
                    'visibleForHTML' => in_array($params['include_products'], array(1, 3)),
                    'images'         => array()
                );

                if ($params['include_product_images']) {
                    $images = self::getProductImages($row->virtuemart_product_id, $params['product_image_limit']);

                    foreach ($images as $image) {
                        if (isset($image->file_url)) {
                            $imageNode          = new stdClass;
                            $imageNode->src     = JURI::base() . $image->file_url;
                            $imageNode->title   = htmlspecialchars_decode($row->product_name);
                            $imageNode->license = $params['product_image_license_url'];

                            $node->images[] = $imageNode;
                        }
                    }
                }

                $collector->printNode($node);
            }

            $collector->changeLevel(-1);
        }
    }

    /**
     * @param int $catId
     *
     * @return object[]
     * @throws Exception
     */
    protected static function getChildCategories($catId)
    {
        if (!isset(self::$categoriesCache[$catId])) {
            static::checkMemory();

            try {
                $categoryModel = static::getCategoryModel();

                static::$categoriesCache[$catId] = $categoryModel->getCategories(true, $catId);

                return static::$categoriesCache[$catId];

            } catch (Exception $e) {
                JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            }
        }

        return array();
    }

    /**
     * @param int $productId
     * @param int $limit
     *
     * @return object[]
     */
    protected static function getProductImages($productId, $limit)
    {
        $db = OSMap\Factory::getDbo();

        $query = $db->getQuery(true)
            ->select(
                array(
                    'm.file_url'
                )
            )
            ->from('#__virtuemart_product_medias AS pm')
            ->innerJoin('#__virtuemart_medias AS m ON (pm.virtuemart_media_id = m.virtuemart_media_id)')
            ->where(
                array(
                    'pm.virtuemart_product_id = ' . $db->quote((int)$productId),
                )
            );

        $db->setQuery($query, 0, (int)$limit);

        return $db->loadObjectList();
    }

    /**
     * @param int $catId
     *
     * @return object[]
     * @throws Exception
     */
    protected static function getProducts($catId)
    {
        try {
            $productModel = static::getProductModel();
            $ids = $productModel->sortSearchListQuery(true, $catId);

            return $productModel->getProducts($ids);

        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');

        }

        return array();
    }

    /**
     * @return VirtueMartModelProduct
     */
    protected static function getProductModel()
    {
        if (static::$productModel === null) {
            if (static::$productModel = VmModel::getModel('product')) {
                static::$productModel->set('_noLimit', true);
            }
        }

        return static::$productModel;
    }

    /**
     * @return VirtueMartModelCategory
     */
    protected static function getCategoryModel()
    {
        if (static::$categoryModel === null) {
            if (static::$categoryModel = VmModel::getModel('Category')) {
                static::$categoryModel->set('_noLimit');
            }
        }

        return static::$categoryModel;
    }
}
