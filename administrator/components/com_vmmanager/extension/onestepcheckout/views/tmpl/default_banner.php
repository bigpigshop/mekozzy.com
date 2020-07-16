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
$configBanner = file_get_contents(JPATH_ADMINISTRATOR.'/components/com_vmmanager/helpers/config/banner.json');
$configBanner = json_decode($configBanner);
$fontFamily = array(0=>'',1=>"'Lato',sans-serif",2=>"'Open Sans', sans-serif",3=>"'Oswald', sans-serif",
                    4=>"'PT Sans', sans-serif",5=>"'Raleway', sans-serif",6=>"'Roboto', sans-serif");
$configBanner->color->titleTxtFont = $fontFamily[$configBanner->color->titleTxtFont];

?>
<?php if($configBanner->options->showtitle) : ?>
<style>
    .opc-module-content h2.opc-title.bannerTitle{
        background-color:<?php echo  '#'.$configBanner->color->titleBg ?> !important;
        color: <?php echo  '#'.$configBanner->color->titleTxtCl ?> !important;
        font-size: <?php echo $configBanner->color->titleTxtSize.'px' ?>;
        font-family: <?php echo $configBanner->color->titleTxtFont ?>;
    }
</style>
<h2 class="opc-title bannerTitle">
    <i class="<?php echo $configBanner->color->titleIcon ?>"></i>
    <span><?php echo $configBanner->options->banerTitle ?></span>
</h2>
<?php endif; ?>
<?php if(!$configBanner->options->showtitle) : ?>
    <style>
            #opc-wapper > .opc-module#opc-banner > .opc-module-content{border:none}
    </style>
<?php endif; ?>
<div>
    <img src="<?php echo JUri::root().'administrator/' . $configBanner->options->banerImg ?>" />
</div>