<?php // no direct access
defined('_JEXEC') or die('Restricted access');
vmJsApi::jQuery();
vmJsApi::chosenDropDowns();
// JHtml::_('behavior.multiselect');
// JHtml::_('formbehavior.chosen', 'select');
$app    = JFactory::getApplication();
$path   = JURI::base(true).'/templates/'.$app->getTemplate().'/';
$doc   = JFactory::getDocument(); 
$doc->addScript( $path.'assets/bootstrap/js/bootstrap-select.js');
$doc->addStyleSheet( $path.'assets/bootstrap/css/bootstrap-select.css' );
?>

<!-- Currency Selector Module -->
<?php echo $text_before ?>
<div class="mod-currency">
    <form class="demo" action="<?php echo vmURI::getCleanUrl() ?>" method="post">

        <div class="vm-chzncur">

        <?php echo JHTML::_('select.genericlist', $currencies, 'virtuemart_currency_id', 'class="inputbox selectpicker" onchange="this.form.submit()"', 'virtuemart_currency_id', 'currency_txt', $virtuemart_currency_id) ; ?>
        </div>

    </form>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
    // Selectpicker
    $('.selectpicker').selectpicker();
	
	var ua = navigator.userAgent,
    _device = (ua.match(/iPad/i)||ua.match(/iPhone/i)||ua.match(/iPod/i)) ? "smartphone" : "desktop";

    if(_device == "desktop") {
        $(".bootstrap-select").bind('hover', function() {
            $(this).stop().children(".dropdown-menu").slideToggle('300');
        });
    }else{
        $('.bootstrap-select .selectpicker').bind('touchstart', function(){
            $('.bootstrap-select .dropdown-menu').toggle();
        });
    }
	// change the menu display at different resolutions
	
	/*function desktopInit(){
		jQuery('.bootstrap-select .dropdown-menu').show();
	}
   function mobileInit() {
		jQuery('.mod-currency .dropdown-menu').hide();
		jQuery('.mod-currency .selectpicker').unbind('touchstart').bind('touchstart', function(){
			
			jQuery(this).toggleClass('active').parent().find('.dropdown-menu').stop().slideToggle('medium');
			
		});
	}*/
});
</script>