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

	foreach ($categories as $i => $category) {
		$categories[$i]->childs = $categoryModel->getChildCategoryList($vendorId, $category->virtuemart_category_id) ;
		if($level>2){
			foreach ($categories[$i]->childs as $j => $cat) {
				$categories[$i]->childs[$j]->childs = $categoryModel->getChildCategoryList( $vendorId, $cat->virtuemart_category_id );
			}
		}
	}
	
	$parentCategories = $categoryModel->getCategoryRecurse($catid[0],0);

?>
<div class="tab iq-card position-relative inner-page-bg">
	<?php
		
		foreach ($categories as $cate)
		{
			$dbb = JFactory::getDBO();
			$qs = "SELECT file_url FROM #__virtuemart_medias WHERE virtuemart_media_id = '" . $cate->virtuemart_category_id . "'";
			$dbb->setQuery($qs);
			$results = $dbb->loadObjectList();
			?>
            <button class="tablinks" onmouseover="openCity(event, <?php echo $cate->virtuemart_category_id ?> )"><?php echo $cate->category_name ?></button>
			<?php
		}
	?>
</div>

<?php
	
	foreach ($categories as $cate)
	{
		$dbb = JFactory::getDBO();
		$qs = "SELECT file_url FROM #__virtuemart_medias WHERE virtuemart_media_id = '" . $cate->virtuemart_category_id . "'";
		$dbb->setQuery($qs);
		$results = $dbb->loadObjectList();
		?>
        <div id="<?php echo $cate->virtuemart_category_id ?>" class="tabcontent">
            <h3><?php echo $cate->category_name ?></h3>
            <div class="user-images position-relative overflow-hidden">
                <p>Load San Pham cua Danh Muc</p>
                <a href="#">
                    <img src="<?php echo JURI::root() . $results[0]->file_url; ?>" style="height: 120px;display: block;" class="img-fluid rounded" alt="<?php echo $cate->category_name ?>">
                </a>
            </div>
            
        </div>
		<?php
	}
?>
<div class="clearfix"></div>




<style>
    /* Style the tab */
    .tab {
        float: left;
        border: 1px solid #ccc;
        background-color: #ffd200;
        width: 30%;
        height: 300px;
    }

    /* Style the buttons that are used to open the tab content */
    .tab button {
        display: block;
        background-color: inherit;
        color: black;
        padding: 22px 16px;
        width: 100%;
        border: none;
        outline: none;
        text-align: left;
        cursor: pointer;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current "tab button" class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        float: left;
        padding: 0px 12px;
        width: 70%;
        border-left: none;
        height: 300px;
        display: none;
    }
</style>
<script>
    function openCity(evt, cityName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the link that opened the tab
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>

 