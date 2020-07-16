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
$logIn = file_get_contents(JPATH_ADMINISTRATOR.'/components/com_vmmanager/helpers/config/logIn.json');
$logIn = json_decode($logIn);
$user  = JFactory::getUser();
$fontFamily = array(0=>'',1=>"'Lato',sans-serif",2=>"'Open Sans', sans-serif",3=>"'Oswald', sans-serif",
                    4=>"'PT Sans', sans-serif",5=>"'Raleway', sans-serif",6=>"'Roboto', sans-serif");
$logIn->color->titleTxtFont = $fontFamily[$logIn->color->titleTxtFont];
$logIn->color->contentTxtFont = $fontFamily[$logIn->color->contentTxtFont];
?>
<style>
    .opc-module-content h2.opc-title.logInTitle{
        background-color:<?php echo  '#'.$logIn->color->titleBg ?> !important;
        color: <?php echo  '#'.$logIn->color->titleTxtCl ?> !important;
        font-size: <?php echo $logIn->color->titleTxtSize.'px' ?>;
        font-family: <?php echo $logIn->color->titleTxtFont ?>;
    }
    .opc-module-content form.opc-form#logInForm,.opc-module-content form.opc-form#logInForm h3,.opc-module-content form.opc-form#logInForm label{
        background-color:<?php echo  '#'.$logIn->color->contentBg ?>;
        color: <?php echo  '#'.$logIn->color->contentTxtCl ?>;
        font-size: <?php echo $logIn->color->contentTxtSize.'px' ?>;
        font-family: <?php echo $logIn->color->contentTxtFont ?>;
    }
</style>
<div class="payments-signin-button" ></div>
<?php  if($logIn->options->popupType){ ?>
    <style>
        #opc-wapper > .opc-module#opc-logIn > .opc-module-content{border:0}
        .LoginPopup{background-color:<?php echo  '#'.$logIn->color->contentBg ?>;}
        .LogIn-success,.logIn-popup{
            color: <?php echo  '#'.$logIn->color->contentTxtCl ?>;
            font-size: <?php echo $logIn->color->contentTxtSize.'px' ?>;
            font-family: <?php echo $logIn->color->contentTxtFont ?>;
        }
    </style>
    <?php if($user->id){ ?>
    <p class="LogIn-success">
        <?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_LOGIN_HELLO'); ?>
        <b><?php echo $user->name; ?></b>
        <span id="submit-logout" class="btn-login login-ajax"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_LOGIN_OUT'); ?></span>
    </p>
    <?php }else{ ?>
    <p class="logIn-popup"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_LOGIN_POPUP') ?></p>
    <?php } ?>
    <div class="LoginPopup">
<?php } ?>


<h2 class="opc-title logInTitle">
    <i class="<?php echo $logIn->color->titleIcon ?>"></i>
    <span><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_LOGIN'); ?></span>
    <?php  if($logIn->options->popupType){ ?>
        <i class="close-logIn opc-iconcancel"></i>
    <?php  } ?>
</h2>
<form method="Post" action="<?php echo JRoute::_('index.php?option=com_ajax&plugin=onestepcheckout&format=json'); ?>" name="logInForm" id="logInForm" class="opc-form">
    <?php if($user->id){ ?>
    <h3>
        <?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_LOGIN_HELLO'); ?>
        <b><?php echo $user->name; ?></b>
        <span id="submit-logout" class="btn-login login-ajax"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_LOGIN_OUT'); ?></span>
    </h3>
    <input type="hidden" name="task" value="logout" />
    <?php }else{ ?>
    <h3><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_LOGIN_DESC') ?></h3>
    <p class="opc-login-username opc-login-val">
        <span><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_LOGIN_USER') ?><i>*</i></span>
        <input type="text" name="username" placeholder="Username" autocomplete="false" />
    </p>
    <p class="opc-login-password opc-login-val">
        <span><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_LOGIN_PASS') ?><i>*</i></span>
        <input type="password" name="password" placeholder="*********" />
    </p>
    <div class="opc-login-action">
        <p class="login-remember">
            <input type="checkbox" id="opc-remember" name="remember" value="yes" />
            <label for="opc-remember"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_LOGIN_REMEMBER') ?></label>
        </p>
        <p class="action-login">  
            <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset') ?>"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_LOGIN_FORGOT_PASS') ?></a>        
            <span id="submit-login" class="btn-login login-ajax"><?php echo JText::_('SYSTEM_ONESTEPCHECKOUT_LOGIN_IN'); ?></span>
        </p>
    </div>
    <input type="hidden" name="task" value="login" />
    <?php } ?>
    <div id="notice-login"><p></p></div>
</form>

<?php if($logIn->options->popupType){ ?>
</div>
<?php } ?>