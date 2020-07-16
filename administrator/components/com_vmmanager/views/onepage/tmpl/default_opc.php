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
    $data = file_get_contents('components/com_vmmanager/helpers/opc.json');
    $data = (array) json_decode($data);
?>
<form action="#" method="POST" name="opcExtension" id="opcExtension">
<div class="extenPage">
    <!--Menu 2-->
    <div id="main-mennu-2">
        <ul>
            <li tabs="#Opc-about" class="tab-2 active"><?php echo JText::_('OPC_MENU_ABOUT') ?></li>
            <li tabs="#Opc-design" class="tab-2"><?php echo JText::_('OPC_MENU_LAYOUT') ?></li>
            <li tabs="#Opc-config" class="tab-2"><?php echo JText::_('OPC_MENU_SETTING') ?></li>
            <li></li>
        </ul>
    </div>
    <!--End-->
    <!----------------------------------------------------------------------->
    <!--Content 2-->
    <div id="main-content-2">
        <div id="Opc-about" class="active">
            <?php echo $this->loadTemplate('opc_about'); ?>
        </div>
        <div id="Opc-config">
            <div class="desc-cog-parent">Please click on icons to change status.</div>
            <div class="parent-cog">
                <!--Test--->
                <div class="opc-cog-child">
                    <div class="title-action">
                        <p><?php echo JText::_('OPC_COG_LOAD_CSS') ?></p>
                        <div class="onoffswitch">
                            <input <?php if($data['loadCss']){?> checked <?php } ?> type="checkbox" value="1" name="loadCss" class="onoffswitch-checkbox" id="loadCss"/>
                            <label class="onoffswitch-label" for="loadCss">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                    <div class="desc-cog">
                        <p class="description-config"><?php echo JText::_('OPC_COG_LOAD_CSS_DESC') ?></p>
                        
                    </div>
                </div>
                <div class="opc-cog-child">
                    <div class="title-action">
                        <p><?php echo JText::_('OPC_COG_LOAD_JS') ?></p>
                        <div class="onoffswitch">
                            <input <?php if($data['loadJs']){?> checked <?php } ?> type="checkbox" value="1" name="loadJs" class="onoffswitch-checkbox" id="loadJs"/>
                            <label class="onoffswitch-label" for="loadJs">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                    <div class="desc-cog">
                        <p class="description-config"><?php echo JText::_('OPC_COG_LOAD_JS_DESC') ?></p>                        
                    </div>
                </div>
                <div class="opc-cog-child">
                    <div class="title-action">
                        <p><?php echo JText::_('OPC_COG_LOAD_JUI') ?></p>
                        <div class="onoffswitch">
                            <input <?php if($data['loadJsUI']){?> checked <?php } ?> type="checkbox" value="1" name="loadJsUI" class="onoffswitch-checkbox" id="loadJsUI"/>
                            <label class="onoffswitch-label" for="loadJsUI">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                    <div class="desc-cog">
                        <p class="description-config"><?php echo JText::_('OPC_COG_LOAD_JUI_DESC') ?></p>                  
                    </div>
                </div>
                <div class="opc-cog-child">
                    <div class="title-action">
                        <p><?php echo JText::_('OPC_COG_LOAD_AJAX') ?></p>
                        <div class="onoffswitch">
                            <input <?php if($data['loadAjaxIcon']){?> checked <?php } ?>  type="checkbox" checked="" value="1" name="loadAjaxIcon" class="onoffswitch-checkbox" id="loadAjaxIcon"/>
                            <label class="onoffswitch-label" for="loadAjaxIcon">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                    <div class="desc-cog">
                        <p class="description-config"><?php echo JText::_('OPC_COG_LOAD_AJAX_DESC') ?></p>                 
                    </div>
                </div>
                <div class="opc-cog-child">
                    <div class="title-action">
                        <p><?php echo JText::_('OPC_COG_BLOCK_SPAM') ?></p>
                        <div class="onoffswitch">
                            <input <?php if($data['blockspam']){?> checked <?php } ?> type="checkbox" value="1" name="blockspam" class="onoffswitch-checkbox" id="blockspam"/>
                            <label class="onoffswitch-label" for="blockspam">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                    <div class="desc-cog">
                        <p class="description-config"><?php echo JText::_('OPC_COG_BLOCK_SPAM_DESC') ?></p>
                        
                    </div>
                </div>
                <div class="opc-cog-child">
                    <div class="title-action">
                        <p><?php echo JText::_('OPC_COG_ENABLE_GEO_LOCATION') ?></p>
                        <div class="onoffswitch">
                            <input <?php if($data['geolocal']){?> checked <?php } ?> type="checkbox" value="1" name="geolocal" class="onoffswitch-checkbox" id="geolocal"/>
                            <label class="onoffswitch-label" for="geolocal">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                    <div class="desc-cog">
                        <p class="description-config"><?php echo JText::_('OPC_COG_ENABLE_GEO_LOCATION_DESC') ?></p>
                        
                    </div>

                    <div class="title-action">
                        <p><?php echo JText::_('OPC_COG_ENABLE_GEO_LOCATION_CITY') ?></p>
                        <div class="onoffswitch">
                            <input <?php if($data['geolocalcity']){?> checked <?php } ?> type="checkbox" value="1" name="geolocalcity" class="onoffswitch-checkbox" id="geolocalcity"/>
                            <label class="onoffswitch-label" for="geolocalcity">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                    <div class="desc-cog">
                        <p class="description-config"><?php echo JText::_('OPC_COG_ENABLE_GEO_LOCATION_CITY_DESC') ?></p>
                        
                    </div>
                </div>
                <!--End--->
            </div>
        </div>
        <div id="Opc-design">
            <div class="help-opc"><p><?php echo JText::_('OPC_VISUAL_LAYOUT_DESC') ?></p></div>
            <div class="main-design">
                <div id="opc-design-content" class="grid-stack"></div>
            </div>
            <div class="queue-design">
                <div class="queue-item" data-custom="1" data="opc-module" data-title="Delivery" data-element="delivery" data-tmp="trash-delivery">Delivery</div>
                <div class="queue-item" data-custom="1" data="opc-module" data-title="Banner" data-element="banner" data-tmp="trash-banner">Banner</div>
                <div class="queue-item" data-custom="1" data="opc-module" data-title="HTML" data-element="custom_html" data-tmp="trash-html">HTML</div>
                <div class="queue-item" data-custom="1" data="opc-module" data-title="Login" data-element="logIn" data-tmp="trash-login">Login</div>
            </div>
            <textarea name="opc-design" style="display:none ;">
                <?php print_r($data['opc-design']); ?>
            </textarea>
        </div>
    </div>
    <!--End-->
</div>
</form>