<?php
/** ------------------------------------------------------------------------
One Page Checkout
author CMSMart Team
copyright: Copyright (c) 2012 http://cmsmart.net. All Rights Reserved.
@license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
Websites: http://cmsmart.net
Email: team@cmsmart.net
Technical Support: Forum - http://cmsmart.net/forum
-------------------------------------------------------------------------*/
defined ('_JEXEC') or die('Restricted access');
$configHTML = file_get_contents(JPATH_ADMINISTRATOR.'/components/com_vmmanager/helpers/config/custom_html.json');
$configHTML = json_decode($configHTML);
$fontFamily = array(0=>'',1=>"'Lato',sans-serif",2=>"'Open Sans', sans-serif",3=>"'Oswald', sans-serif",
                    4=>"'PT Sans', sans-serif",5=>"'Raleway', sans-serif",6=>"'Roboto', sans-serif");
$configHTML->color->titleTxtFont = $fontFamily[$configHTML->color->titleTxtFont];
$configHTML->color->contentTxtFont = $fontFamily[$configHTML->color->contentTxtFont];
?>
<?php if($configHTML->options->showtitle) { ?>
<style>
    .opc-module-content h2.opc-title.customHTML{
        background-color:<?php echo  '#'.$configHTML->color->titleBg ?> !important;
        color: <?php echo  '#'.$configHTML->color->titleTxtCl ?> !important;
        font-size: <?php echo $configHTML->color->titleTxtSize.'px' ?>;
        font-family: <?php echo $configHTML->color->titleTxtFont ?>;
    }
    .contentHtml{
        padding:10px;
        font-family: <?php echo $configHTML->color->contentTxtFont ?>;
    }
</style>
<h2 class="opc-title customHTML">
    <i class="<?php echo $configHTML->color->titleIcon ?>"></i>
    <span><?php echo $configHTML->options->htmltitle ?></span>
</h2>
<?php }else{ ?>
<style>
    #opc-wapper > .opc-module#opc-custom_html > .opc-module-content{border:0}
    .contentHtml{font-family: <?php echo $configHTML->color->contentTxtFont ?>;}
</style>
<?php } ?>
<div class="contentHtml">
    <?php echo $configHTML->options->htmlContent ?>
</div>