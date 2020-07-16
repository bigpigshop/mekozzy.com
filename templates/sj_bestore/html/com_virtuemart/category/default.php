<?php
/**
 *
 * Show the products in a category
 *
 * @package    VirtueMart
 * @subpackage
 * @author RolandD
 * @author Max Milbers
 * @todo add pagination
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 9288 2016-09-12 15:20:56Z Milbo $
 */

defined ('_JEXEC') or die('Restricted access');

if(vRequest::getInt('dynamic')){
	if (!empty($this->products)) {
		if($this->fallback){
			$p = $this->products;
			$this->products = array();
			$this->products[0] = $p;
			vmdebug('Refallback');
		}

		echo shopFunctionsF::renderVmSubLayout($this->productsLayout,array('products'=>$this->products,'currency'=>$this->currency,'products_per_row'=>$this->perRow,'showRating'=>$this->showRating));

	}

	return ;
}
?> <div class="category-view"> <?php
$js = "
jQuery(document).ready(function () {
    jQuery('.orderlistcontainer').hover(
        function() { jQuery(this).find('.orderlist').stop().show()},
        function() { jQuery(this).find('.orderlist').stop().hide()}
    )
	
	// Click Button
	function display(view) {
		jQuery('.browse-view .row').removeClass('vm-list vm-grid').addClass(view);
		jQuery('.icon-list-grid .vm-view').removeClass('active');
		if(view == 'vm-list') {
			jQuery('.browse-view .product').addClass('col-lg-12');
			jQuery('.products-list .product .vm-product-media-container').addClass('col-md-4');
			jQuery('.products-list .product .product-info').addClass('col-md-8');
			jQuery('.icon-list-grid .' + view).addClass('active');
		}else{
			jQuery('.browse-view .product').removeClass('col-lg-12');
			jQuery('.products-list .product .vm-product-media-container').removeClass('col-md-4');
			jQuery('.products-list .product .product-info').removeClass('col-md-8');
			jQuery('.icon-list-grid .' + view).addClass('active');
		}
	}
		
    jQuery('.vm-view-list .vm-view').each(function() {
        var ua = navigator.userAgent,
        event = (ua.match(/iPad/i)) ? 'touchstart' : 'click';
        jQuery(this).bind(event, function() {
            jQuery(this).addClass(function() {
                if(jQuery(this).hasClass('active')) return '';
                return 'active';
            });
            jQuery(this).siblings('.vm-view').removeClass('active');
			catalog_mode = jQuery(this).data('view');
			display(catalog_mode);
			
        });

    });

});
";

vmJsApi::addJScript('vm.hover',$js);
?>

<?php

if ($this->showsearch or !empty($this->keyword)) {
	//id taken in the view.html.php could be modified
	$category_id  = vRequest::getInt ('virtuemart_category_id', 0); ?>
<?php
	/*if(!empty($this->keyword)){
		?><h3><?php echo vmText::sprintf('COM_VM_SEARCH_KEYWORD_FOR', $this->keyword); ?></h3><?php
	}*/
	$j = 'jQuery(document).ready(function() {

jQuery(".changeSendForm")
	.off("change",Virtuemart.sendCurrForm)
    .on("change",Virtuemart.sendCurrForm);
})';

	vmJsApi::addJScript('sendFormChange',$j);
} ?>
<?php if (empty($this->keyword) and !empty($this->category) and !empty($this->category->category_description)) {
	
	?>
	
<div class="category_description">
	<?php echo $this->category->category_description; ?>
</div>
<?php
}

// Show child categories

if ($this->showcategory and empty($this->keyword)) {
	if (!empty($this->category->haschildren)) {
		echo ShopFunctionsF::renderVmSubLayout('categories',array('categories'=>$this->category->children));
	}
}

if($this->showproducts){
?>
<div class="browse-view">
<h1 class="cate-title"><?php echo vmText::_($this->category->category_name); ?></h1>
<span id="close-sidebar" class="fa fa-times hidden-lg hidden-md"></span>
<a href="javascript:void(0)" class="open-sidebar hidden-lg hidden-md"><i class="fa fa-bars"></i>Sidebar</a><div class="sidebar-overlay"></div>
<?php // Show child categories
if(!empty($this->orderByList)) { ?>
<div class="orderby-displaynumber top row">
	<div class="vm-view-list col-md-2 col-sm-3 col-xs-12">
		<div class="icon-list-grid">
			<div class="vm-view vm-grid active" data-view="vm-grid"><i class="listing-icon"></i></div>
			<div class="vm-view vm-list" data-view="vm-list"><i class="listing-icon"></i></div>
		</div>
    </div>
	
	<div class="toolbar-center col-md-10 col-sm-9 col-xs-12">
		<?php echo $this->orderByList['orderby']; ?>
		<div class="orderlistcontainer limitbox">
		<?php echo $this->vmPagination->getLimitBox ($this->category->limit_list_step); ?>
		</div>
		<div class="orderlistcontainer counter">
		<?php echo $this->vmPagination->getResultsCounter ();?>
		</div>
		
	</div>
</div> <!-- end of orderby-displaynumber -->

<?php } ?>



	<?php
	if (!empty($this->products)) {
	//revert of the fallback in the view.html.php, will be removed vm3.2
	if($this->fallback){
		$p = $this->products;
		$this->products = array();
		$this->products[0] = $p;
		vmdebug('Refallback');
	}

	echo shopFunctionsF::renderVmSubLayout($this->productsLayout,array('products'=>$this->products,'currency'=>$this->currency,'products_per_row'=>$this->perRow,'showRating'=>$this->showRating));
	?>
	
	<?php if(!empty($this->orderByList)) { ?>
	<div class="vm-pagination vm-pagination-bottom"><?php echo $this->vmPagination->getPagesLinks (); ?><span class="vm-page-counter"><?php echo $this->vmPagination->getPagesCounter (); ?></span></div>
	<?php } ?>
	
<?php } elseif (!empty($this->keyword)) {
	echo vmText::_ ('COM_VIRTUEMART_NO_RESULT') . ($this->keyword ? ' : (' . $this->keyword . ')' : '');
}
?>
</div>

<?php } ?>
</div>

<?php
if(VmConfig::get ('jdynupdate', TRUE)){
	$j = "Virtuemart.container = jQuery('.category-view');
	Virtuemart.containerSelector = '.category-view';";

	vmJsApi::addJScript('ajaxContent',$j);
}
?>
<!-- end browse-view -->