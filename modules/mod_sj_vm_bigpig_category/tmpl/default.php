<?php
/**
 * @package BIGPIG CATEGORY TABS
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2020 Mekozzy Company. All Rights Reserved.
 * @author BIGPIG 
 *
 */

defined('_JEXEC') or die;
	use Joomla\CMS\Factory;

	$document = Factory::getDocument();
	$document->addStyleSheet('modules/mod_sj_vm_bigpig_category/assets/css/bootstrap.min.css');
	$document->addStyleSheet('modules/mod_sj_vm_bigpig_category/assets/css/typography.css');
	$document->addStyleSheet('modules/mod_sj_vm_bigpig_category/assets/css/style.css');
	$document->addStyleSheet('modules/mod_sj_vm_bigpig_category/assets/css/responsive.css');
	$document->addScript(  'modules/mod_sj_vm_bigpig_category/assets/js/bigpig.js');

	
?>
<div class="container-fluid">
    <div class="row">
        <ul class="profile-img-gallary d-flex flex-wrap p-0 m-0">
            <?php
	            $app = JFactory::getApplication();
	            $pathway = $app->getPathway();
                foreach ($list as $product)
                {
                 
	                $dbb = JFactory::getDBO();
	                $qs = "SELECT file_url FROM #__virtuemart_medias WHERE virtuemart_media_id = '".$product->virtuemart_category_id."'";
	                $dbb->setQuery($qs);
	                $results = $dbb->loadObjectList();
            ?>
            <li class="col-lg-2 col-md-3 col-4 pl-2 pr-0 pb-3">
                <a href="#" class="text-center">
                    <img src="<?php  echo JURI::root () . $results[0]->file_url; ?>" alt="<?php echo $product->category_name; ?>" class="center"></a>
                <h6 class="text-center mt-2"><?php echo $product->category_name; ?></h6>
            </li>
            <?php
                }
            ?>
        </ul>
    </div>
    <div class=" text-center">
        <button type="submit" id="loadMore" class="btn btn-primary">Load More</button>
    </div>
    
</div>

<script>


    
</script>
<style>
    ul.profile-img-gallary.d-flex.flex-wrap.p-0.m-0>li{
        display: none;
        padding: 10px;
        margin-bottom: 5px;
    }

    ul.profile-img-gallary.d-flex.flex-wrap.p-0.m-0>li>a>img{
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