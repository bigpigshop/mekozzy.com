<?php
/**
 * @package    Joomla.Language
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * vi-VN localise class
 *
 * @package		Joomla.Language
 * @since		1.6
 */
abstract class vi_VNLocalise
{
    /**
     * Returns the potential suffixes for a specific number of items
     *
     * @param   int  $count  The number of items.
     *
     * @return  array  An array of potential suffixes.
     *
     * @since   1.6
     */
    public static function getPluralSuffixes($count)
    {
        if ($count == 0)
        {
            $return = array('0');
        }
        elseif($count == 1)
        {
            $return = array('1');
        }
        else
        {
            $return = array('MORE');
        }
        return $return;
    }
    /**
     * Returns the ignored search words
     *
     * @return  array  An array of ignored search words.
     *
     * @since   1.6
     */
    public static function getIgnoredSearchWords()
    {
        $search_ignore = array();
        $search_ignore[] = "and";
        $search_ignore[] = "in";
        $search_ignore[] = "on";
        return $search_ignore;
    }
    /**
     * Returns the lower length limit of search words
     *
     * @return  integer  The lower length limit of search words.
     *
     * @since   1.6
     */
    public static function getLowerLimitSearchWord()
    {
        return 3;
    }
    /**
     * Returns the upper length limit of search words
     *
     * @return	integer  The upper length limit of search words.
     *
     * @since	1.6
     */
    public static function getUpperLimitSearchWord()
    {
        return 20;
    }
    /**
     * Returns the number of chars to display when searching
     *
     * @return	integer  The number of chars to display when searching.
     *
     * @since	1.6
     */
    public static function getSearchDisplayedCharactersNumber()
    {
        return 200;
    }
    /**
     *
     * @param type $string
     * @return type
     */
    public static function transliterate($string)
    {
        $str = JString::strtolower($string);
        $str = self::lowerCaseVN($str);
        return $str;
    }

    public static function lowerCaseVN($str)
    {

        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|ấ|ầ|ẩ|ẫ|ậ|ắ|ằ|ẳ|ẵ|ặ|á|à|ả|ã|ạ)/", 'a', $str);

        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|ế|ề|ể|ễ|ệ|é|è|ẻ|ẽ|ẹ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ|í|ì|ỉ|ĩ|ị)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|ố|ổ|ỗ|ộ|ớ|ờ|ở|ỡ|ợ|ó|ò|ỏ|õ|ọ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|ứ|ừ|ữ|ự|ú|ù|ủ|ũ|ụ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ|ý|ỳ|ỷ|ỹ|ỵ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ắ|Ằ|Ặ|Ẳ|Ẵ|Ấ|Ầ|Ẩ|Ẫ|Ậ|Ắ|Ằ|Ẳ|Ẵ|Ặ|Á|À|Ả|Ã|Ạ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|Ế|Ề|Ể|Ễ|Ệ|É|È|Ẻ|Ẽ|Ẹ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ|Í|Ì|Ỉ|Ĩ|Ị)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ợ|Ở|Ỡ|Ố|Ồ|Ổ|Ộ|Ớ|Ờ|Ở|Ỗ|Ộ|Ớ|Ờ|Ở|Ỡ|Ợ|Ó|Ò|Ỏ|Õ|Ọ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|Ứ|Ừ|Ử|Ữ|Ự|Ú|Ù|Ủ|Ũ|Ụ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ|Ý|Ỳ|Ỷ|Ỹ|Ỵ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        $str = preg_replace("/( |&nbsp;)/", '-', $str);
        $str = preg_replace("/(\?|`|\'|\"|%|\/|,|”|“)/", '', $str);
        return $str;
    }
}