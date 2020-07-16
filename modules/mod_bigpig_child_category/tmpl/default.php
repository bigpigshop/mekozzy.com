<?php
	/**
	 * @package MOD_BIGPIG_CHILD_CATEGORY
	 * @version 1.0.0
	 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 * @copyright (c) 2020 Mekozzy Company. All Rights Reserved.
	 * @author BIGPIG
	 *
	 */
	
	defined('_JEXEC') or die;
	
	use Joomla\CMS\Factory;
	
	$document = Factory::getDocument();
	$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/typography.css');
	$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/style.css');
	$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/responsive.css');
	$document->addScript(  'modules/mod_bigpig_category_product_detail/assets/js/bigpig.js');
?>
<div class="category-view bigpig">
    <div style="float: right" class="row xemthem">
        <a class="d-block nav-item loadmore"
           href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>"
           target="_blank">Xem Them </a>
    </div>
    <div class="clear"></div>
    <ul class="row flex">
        <?php
        
            foreach ($result as $cat)
            {
             
	            $db = JFactory::getDbo();
	            $query = $db->getQuery(true);
	            $query->select('*')
		            ->from("#__virtuemart_category_medias a")
		            ->leftJoin('#__virtuemart_medias as c ON c.virtuemart_media_id = a.virtuemart_media_id')
		            ->where($db->quoteName('a.virtuemart_category_id') . ' = ' . $cat->virtuemart_category_id);
	            $db->setQuery($query);
	            $json = $db->loadObject();
        ?>
        <li class="item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 flex-item">
            <div class="item-inner clearfix">
                <div class="item-image">
                    <a title="<?php echo $cat->category_name; ?>" target="_blank"  href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=137&layout=loadmore&tmpl=component'); ?>">
                        <img src="<?php  echo is_file($json->file_url) ? $json->file_url : 'images/noimages.png'?>" alt="<?php echo $cat->category_name; ?>"> </a>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <a href=""><?php echo $cat->category_name; ?></a>
                    </div>
                    <div class="item-description">

                        <p class="product_s_desc hidden-xs">
	                        <?php echo $cat->category_name; ?>
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <?php
            }
            ?>
        <div class="clear"></div>
    </ul>
    <div class=" text-center">
        <button type="submit" id="loadMore" class="btn btn-primary">Load More</button>
    </div>
    
    <div class="clear"></div>
    
</div>
<style>
    ul.flex>li{
        display: none;
        padding: 10px;
        margin-bottom: 5px;
    }

    ul.flex>li>a>img{
        display: block;
        height: 80px;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
        border-radius: 10px;
    }


    #loadMore {
        padding: 10px;
        text-align: center;
        box-shadow: 0 1px 1px #ccc;
        transition: all 600ms ease-in-out;
        -webkit-transition: all 600ms ease-in-out;
        -moz-transition: all 600ms ease-in-out;
        -o-transition: all 600ms ease-in-out;
    }

</style>
<script>
    jQuery(function ($) {
    $(function () {
        $("ul.flex>li").slice(0, 8).show();

        $("#loadMore").on('click', function (e) {
            e.preventDefault();
            $("ul.flex>li:hidden").slice(0,8).slideDown();
            if ($("ul.flex>li:hidden").length == 0) {
                $("#load").fadeOut('slow');
            }
            $('html,body').animate({
                scrollTop: $(this).offset().top
            }, 1500);
        });
    });

});
</script>
