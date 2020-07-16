<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$categoryModel->addImages($categories);
$categories_per_row = vmConfig::get('categories_per_row');
$col_width = floor ( 100 / $categories_per_row);
//var_dump($this);
?>
<style>
    @media (min-width: 350px) {
        .vm-categories-wall-img {
            height: 150px;
        }
    }
@media (min-width: 700px) {
        .vm-categories-wall-img {
            height: 200px;
        }
    }
@media (min-width: 992px) {
    .vm-categories-wall-img {
        height: 250px;
    }
}
</style>

<div class="home1_categories">
    <h3 class="modtitle"><span><?php echo $module->title;?></span></h3>
    <div class="row">
        <?php foreach ($categories as $category) : ?>
        <?php
        $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id);
        $catname = $category->category_name ;
        ?>
        <div class="col-md-3 col-sm-6 col-xs-12 col1">
            <div class="content"><a href="<?php echo $caturl; ?>">
                <?php echo $category->images[0]->displayMediaThumb('class="vm-categories-wall-img"',false) ?>
                </a>
                <div class="title"><a href="<?php echo $caturl; ?>"><?php echo $catname; ?></a></div>
                <?php if (count($category->childs) > 0) : ?>
                <ul>
                    <?php foreach ($category->childs as $childCat) : ?>
                        <?php
                            $childCaturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$childCat->virtuemart_category_id);
                        ?>
                    <li><a href="<?php echo $childCaturl; ?>"><?php echo $childCat->category_name?></a></li>
                    <?php  endforeach;?>
                </ul>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>