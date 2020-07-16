<?php
/**
 * @package SJ Categories for VirtueMart
 * @version 2.2.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;

JHtml::stylesheet('modules/' . $module->module . '/assets/css/sj-categories.css');
ImageHelper::setDefault($params);
$uniqued = 'sj_categories_' . rand() . time();
$options = $params->toObject();
$big_image_config = array(
    'type' => $params->get('imgcfgcat_type'),
    'width' => $params->get('imgcfgcat_width'),
    'height' => $params->get('imgcfgcat_height'),
    'quality' => 90,
    'function' => ($params->get('imgcfgcat_function') == 'none') ? null : 'resize',
    'function_mode' => ($params->get('imgcfgcat_function') == 'none') ? null : substr($params->get('imgcfgcat_function'), 7),
    'transparency' => $params->get('imgcfgcat_transparency', 1) ? true : false,
    'background' => $params->get('imgcfgcat_background'));
?>
<div class="modcontent">
    <h3 class="modtitle"><?php echo JText::_('POPULAR_CATEGORIES'); ?></h3>    
    <div class="cat-wrap theme3">
        <?php $j = 0;
        foreach ($list as $key => $items) {
            $j++;
            ?>
            <div class="content-box">
                <?php  $cat_img = VmCategoriesHelper::getVmCImage($items, $params, 'imgcfgcat');
                if (($cat_img) && (strlen($cat_img['src']) != '')) {
                    ?>
                    <div class="image-cat">
                        <?php $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $items->virtuemart_category_id); ?>
                        <a href="<?php echo $caturl; ?>"
                           title="<?php echo $items->category_name; ?>" <?php echo VmCategoriesHelper::parseTarget($options->target); ?> >
                            <img src="<?php echo VmCategoriesHelper::imageSrc($cat_img, $big_image_config); ?>"
                                 title="<?php echo $items->category_name; ?>" alt="<?php echo $items->category_name; ?>"/>
                        </a>
                    </div>
                <?php } ?>
                <?php if ((int)$params->get('cat_title_display', 1)) { ?>
                    <div class="cat-title">
                        <?php $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $items->virtuemart_category_id); ?>
                        <a href="<?php echo $caturl; ?>"
                           title="<?php echo $items->category_name; ?> " <?php echo VmCategoriesHelper::parseTarget($options->target); ?> >
                            <?php echo VmCategoriesHelper::truncate($items->category_name, (int)$params->get('cat_title_maxcharacs', 25)); ?>
                        </a>
                    </div>
                <?php } ?>

                <?php if ((int)$params->get('cat_sub_title_display', 1)) { ?>
                    <div class="child-cat">
                        <?php
                        if (!empty($items->child_cat)) {
                            foreach ($items->child_cat as $key1 => $item) {
                                ?>
                                <div class="arrow"></div>
                                <div class="child-cat-title">
                                    <?php $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $item->virtuemart_category_id); ?>
                                    <a href="<?php echo $caturl; ?>" <?php echo VmCategoriesHelper::parseTarget($options->target); ?>>
                                        <?php echo VmCategoriesHelper::truncate($item->category_name, (int)$params->get('cat_sub_title_maxcharacs', 25)); ?><?php if ($params->get('cat_all_product') == 1) {
                                            echo '&nbsp;&nbsp;(' . $item->number_product . ')';
                                        } ?>
                                    </a>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <p>
                                <?php echo JText::_('No sub-categories to show!'); ?>
                            </p>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>        
</div>
 
<!-- Javascript Block -->
<script type="text/javascript">
jQuery(document).ready(function($) {
    var carousel = $(".cat-wrap");   
    carousel.owlCarousel({
        items : 1,
        //Pagination
        pagination : false,
        paginationNumbers: false,
        nav: true,
        dots: false,
        loop: true,
        margin: 0,
        navText: '',
        navClass: ['owl2-prev','owl2-next'],
        responsive:{
            320:{
                items: 2,
                margin: 8,
            },
            768:{
                items: 4,
                margin: 8,
            },
            992:{
                items: 3,
                margin: 8,
            },
            1200:{
                items: 6,
            },
            1024:{
                items: 6,
            },
        }
    });
    checkClasses(); 
    carousel.on('translated.owl.carousel', function(event) {
        checkClasses();
    });
    function checkClasses(){
        var total = $('.cat-wrap .owl-stage .owl-item.active').length;
        
        $('.cat-wrap .owl-stage .owl-item').removeClass('firstActiveItem lastActiveItem');
        
        $('.cat-wrap .owl-stage .owl-item.active').each(function(index){
            if (index === 0) {
                // this is the first one
                $(this).addClass('firstActiveItem');
            }
            if (index === total - 1 && total>1) {
                // this is the last one
                $(this).addClass('lastActiveItem');
            }
        });
    }
    
}); 
</script>