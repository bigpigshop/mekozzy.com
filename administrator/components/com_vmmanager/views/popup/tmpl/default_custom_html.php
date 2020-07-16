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
$data = file_get_contents('components/com_vmmanager/helpers/config/custom_html.json');
$data = json_decode($data);
$dataStyle = $data->color;
$dataOption = $data->options;
?>

<div id="popupHeader">
    <ul>
        <li data="#privateItem" class="active"><?php echo JText::_('OPC_POPUP_TABS_OPTION') ?></li>
        <li data="#styleItem"><?php echo JText::_('OPC_POPUP_TABS_STYLE') ?></li>
    </ul>
</div>
<div id="popupItem">
    <div id="styleItem">
        <div id="example-color"><?php echo $this->loadTemplate('style_example'); ?></div>
        <form action="#" method="POST" name="styleForm">
            <fieldset class="popupField">
                <legend><?php echo JText::_('OPC_POPUP_COLOR_TITLE') ?></legend>
                <p class="color-title-desc"><?php echo JText::_('OPC_POPUP_TITLE_DESC') ?></p>
                <div class="cols-left">
                    <div class="lb-config">
                        <span class="color-lb">
                            <input class="input-color" value="<?php echo $dataStyle->titleBg ?>" name="titleBg" readonly=""/>
                            <span class="color-value"></span>
                        </span>
                        <div class="title-and-desc">
                            <span class="title"><?php echo JText::_('OPC_POPUP_BACKGROUND_COLOR_TITLE') ?></span>
                            <p class="desc"><?php echo JText::_('OPC_POPUP_STYLE_TITLE_BG_DESC') ?></p>
                        </div>
                    </div>
                    <div class="lb-config">
                        <span class="input-val-popup">
                            <input class="input-size"  value="<?php echo $dataStyle->titleTxtSize ?>" name="titleTxtSize"/>
                        </span>                        
                        <div class="title-and-desc">
                            <span class="title"><?php echo JText::_('OPC_POPUP_TEXT_SIZE_TITLE') ?></span>
                            <p class="desc"><?php echo JText::_('OPC_POPUP_STYLE_TITLE_SIZE_DESC') ?></p>
                        </div>
                    </div>
                </div>
                <div class="cols-right">
                    <div class="lb-config">
                        <span class="color-lb">
                            <input class="input-color" value="<?php echo $dataStyle->titleTxtCl ?>" name="titleTxtCl" readonly=""/>
                            <span class="color-value"></span>
                        </span>
                        <div class="title-and-desc">
                            <span class="title"><?php echo JText::_('OPC_POPUP_TEXT_COLOR_TITLE') ?></span>
                            <p class="desc"><?php echo JText::_('OPC_POPUP_STYLE_TITLE_COLOR_DESC') ?></p>
                        </div>
                    </div>
                    <div class="lb-config">
                        <span class="input-val-popup">
                            <select class="select-pop" name="titleTxtFont">
                                <option value="0"><?php echo JText::_('OPC_POPUP_CHOSE_FONT') ?></option>
                                <option value="1" <?php if($dataStyle->contentTxtFont == 1) echo 'selected'; ?>>Lato</option>
                                <option value="2" <?php if($dataStyle->contentTxtFont == 2) echo 'selected'; ?>>Open Sans</option>
                                <option value="3" <?php if($dataStyle->contentTxtFont == 3) echo 'selected'; ?>>Oswald</option>
                                <option value="4" <?php if($dataStyle->contentTxtFont == 4) echo 'selected'; ?>>PT Sans</option>
                                <option value="5" <?php if($dataStyle->contentTxtFont == 5) echo 'selected'; ?>>Raleway</option>
                                <option value="6" <?php if($dataStyle->contentTxtFont == 6) echo 'selected'; ?>>Roboto</option>
                            </select>
                        </span>
                        <div class="title-and-desc">
                            <span class="title"><?php echo JText::_('OPC_POPUP_TEXT_FONT_TITLE') ?></span>
                            <p class="desc"><?php echo JText::_('OPC_POPUP_STYLE_TITLE_FONT_DESC') ?></p>
                        </div>
                        
                    </div>
                </div>
                <div class="icons-title">
                    <div class="desc-title">
                        <span class="title"><?php echo JText::_('OPC_POPUP_ICON_TITLE') ?></span>
                        <p class="desc"><?php echo JText::_('OPC_POPUP_STYLE_TITLE_ICON_DESC') ?></p>
                    </div>
                    <div class="list-icon">
                        <h4>Font: Entypo-fontello</h4>
                        <div class="list-icon-val">
                            <?php
                                $icons = file_get_contents(JPATH_COMPONENT_ADMINISTRATOR.'/assets/icons/config.json');
                                $icons = json_decode($icons);
                            ?>
                            <?php foreach($icons->glyphs as $icon): ?>
                            <span data-icons="<?php echo $icons->css_prefix_text.$icon->css ;?>" 
                                  class="opc-list-icons <?php echo $icons->css_prefix_text.$icon->css ;?>
                                    <?php if($dataStyle->titleIcon == $icons->css_prefix_text.$icon->css) echo " cur_icon" ?>
                                  ">
                            </span>
                            <?php endforeach; ?>
                            <input value="<?php echo $dataStyle->titleIcon ?>" name="titleIcon" type="hidden"/>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="popupField">
                <legend><?php echo JText::_('OPC_POPUP_COLOR_CONTENT') ?></legend>
                <p class="color-title-desc"><?php echo JText::_('OPC_POPUP_CONTENT_DESC') ?></p>
                <div class="cols-left">
                    <div class="lb-config">
                        <span class="color-lb">
                            <input class="input-color" value="<?php echo $dataStyle->contentBg ?>" name="contentBg" readonly=""/>
                            <span class="color-value"></span>
                        </span>
                        <div class="title-and-desc">
                            <span class="title"><?php echo JText::_('OPC_POPUP_BACKGROUND_COLOR_TITLE') ?></span>
                            <p class="desc"><?php echo JText::_('OPC_POPUP_STYLE_CONTENT_BG_DESC') ?></p>
                        </div>
                    </div>
                    <div class="lb-config">
                        <span class="input-val-popup">
                            <select class="select-pop" name="contentTxtFont">
                                <option value="0">---Chose Font---</option>
                                <option value="1" <?php if($dataStyle->titleTxtFont == 1) echo 'selected'; ?>>Lato</option>
                                <option value="2" <?php if($dataStyle->titleTxtFont == 2) echo 'selected'; ?>>Open Sans</option>
                                <option value="3" <?php if($dataStyle->titleTxtFont == 3) echo 'selected'; ?>>Oswald</option>
                                <option value="4" <?php if($dataStyle->titleTxtFont == 4) echo 'selected'; ?>>PT sans</option>
                                <option value="5" <?php if($dataStyle->titleTxtFont == 5) echo 'selected'; ?>>Raleway</option>
                                <option value="6" <?php if($dataStyle->titleTxtFont == 6) echo 'selected'; ?>>Roboto</option>
                            </select>
                        </span>
                        <div class="title-and-desc">
                            <span class="title"><?php echo JText::_('OPC_POPUP_TEXT_FONT_TITLE') ?></span>
                            <p class="desc"><?php echo JText::_('OPC_POPUP_STYLE_CONTENT_FONT_DESC') ?></p>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <div id="privateItem"  class="active">
        <div class="description-example-color"><p><?php echo JText::_('OPC_POPUP_HTML_DESC') ?></p></div>
        <form name="optionForm" action="#" method="POST" id="optionForm">
            <div class="baner-row">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo JText::_('OPC_POPUP_BANNER_OPTION_HTML_SHOW_TITLE') ?></h3>
                    <p class="desc"><?php echo JText::_('OPC_POPUP_BANNER_OPTION_HTML_SHOW_TITLE_DESC') ?></p>
                </div>
                <div class="row-val">
                    <span class="onoffswitch">
                    <input <?php if($dataOption->showtitle):?> checked="" <?php endif; ?>  type="checkbox" value="1" name="showtitle" class="onoffswitch-checkbox" id="showtitle"/>
                        <label class="onoffswitch-label" for="showtitle">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </span>
                </div>
            </div>
            <div class="baner-row">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo JText::_('OPC_POPUP_BANNER_OPTION_HTML_TITLE') ?></h3>
                    <p class="desc"><?php echo JText::_('OPC_POPUP_BANNER_OPTION_HTML_TITLE_DESC') ?></p>
                </div>
                <div class="row-val">
                    <input type="text" name="htmltitle" value="<?php echo $dataOption->htmltitle ?>" />
                </div>
            </div>
            <div class="htmlCustom">
                <textarea name="htmlContent" class="jqte-html-input">
                    <?php echo $dataOption->htmlContent ?>
                </textarea>
            </div>
        </form>
    </div>
    <div id="popupBottom">
        <span data-file="<?php echo JFactory::getApplication()->input->get('popupcf'); ?>" class="savePopup"><?php echo JText::_('OPC_POPUP_SAVE') ?></span>
        <span class="closePopup"><?php echo JText::_('OPC_POPUP_CANCEL') ?></span>
    </div>
</div>


