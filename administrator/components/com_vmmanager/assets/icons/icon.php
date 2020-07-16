<?php
/** ------------------------------------------------------------------------
Virtuemart manager
author CMSMart Team
copyright: Copyright (c) 2012 http://cmsmart.net. All Rights Reserved.
@license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
Websites: http://cmsmart.net
Email: team@cmsmart.net
Technical Support: Forum - http://cmsmart.net/forum
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
    $icons = file_get_contents('config.json');
    $icons = json_decode($icons);
?>
<div class="icon-list-title">Font: entypo-fontello</div>
<?php foreach($icons->glyphs as $icon): ?>
<span data-icons="<?php echo $icons->css_prefix_text .  $icon->css ;?>" class="opc-list-icons <?php echo $icons->css_prefix_text .  $icon->css ;?>"></span>
<?php endforeach; ?>


<script>
    jQuery(document).ready(function(){
       jQuery("#list-icons").mCustomScrollbar({
        	autoHideScrollbar:false,
        	theme:"rounded-dark"
            //rounded-dots-dark
       });
    });
</script>
