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
$data = file_get_contents('components/com_vmmanager/helpers/config/delivery.json');
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
                                <option><?php echo JText::_('OPC_POPUP_CHOSE_FONT') ?></option>
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
                            <input class="input-size" value="<?php echo $dataStyle->contentTxtSize ?>" name="contentTxtSize"/>
                        </span>                        
                        <div class="title-and-desc">
                            <span class="title"><?php echo JText::_('OPC_POPUP_TEXT_SIZE_TITLE') ?></span>
                            <p class="desc"><?php echo JText::_('OPC_POPUP_STYLE_CONTENT_SIZE_DESC') ?></p>
                        </div>
                    </div>
                </div>
                <div class="cols-right">
                    <div class="lb-config">
                        <span class="color-lb">
                            <input class="input-color" value="<?php echo $dataStyle->contentTxtCl ?>" name="contentTxtCl" readonly=""/>
                            <span class="color-value"></span>
                        </span>
                        <div class="title-and-desc">
                            <span class="title"><?php echo JText::_('OPC_POPUP_TEXT_COLOR_TITLE') ?></span>
                            <p class="desc"><?php echo JText::_('OPC_POPUP_STYLE_CONTENT_COLOR_DESC') ?></p>
                        </div>
                    </div>
                    <div class="lb-config">
                        <span class="input-val-popup">
                            <select class="select-pop" name="contentTxtFont">
                                <option>---Chose Font---</option>
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
    <!--Option-->
    <div id="privateItem" class="active">
        <div class="description-example-color">
            <p><?php echo JText::_('OPC_POPUP_DELIVERY_DESC') ?></p>
        </div>
        <form name="optionForm" action="#" method="POST" id="optionForm">
            <div class="delivery-row deli-row-1">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo JText::_('OPC_POPUP_DELIVERY_REQUIRED') ?></h3>
                    <p class="desc"><?php echo JText::_('OPC_POPUP_DELIVERY_REQUIRED_DESC') ?></p>
                </div>
                <div class="row-val">
                    <span class="onoffswitch">
                    <input <?php if($dataOption->deliRequired):?> checked="" <?php endif; ?> type="checkbox" value="1" name="deliRequired" class="onoffswitch-checkbox" id="deliRequired"/>
                        <label class="onoffswitch-label" for="deliRequired">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </span>
                </div>
            </div>            
            <div class="delivery-row deli-row-2">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo JText::_('OPC_POPUP_DELIVERY_FORMAT') ?></h3>
                    <p class="desc">
                        <?php echo JText::_('OPC_POPUP_DELIVERY_FORMAT_DESC') ?>
                        <a href="http://php.net/manual/en/datetime.formats.date.php" target="_blank" title="PHP: Date Formats - Manual"><?php echo JText::_('OPC_POPUP_CLICK_HERE')  ?></a>
                    </p>
                </div>
                <div class="row-val">
                    <input type="text" value="<?php echo $dataOption->dateFormat ?>" name="dateFormat" id="dateFormat" class="inputText" />
                </div>
            </div>
            <div class="delivery-row deli-row-3">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo JText::_('OPC_POPUP_DELIVERY_HOLYDAYS') ?></h3>
                    <p class="desc">
                        <?php echo JText::_('OPC_POPUP_DELIVERY_HOLYDAYS_DESC') ?>                        
                    </p>
                </div>
                <div class="row-val">
                    <span class="checkbox-text first check-1">
                        <input <?php if(in_array(1,$dataOption->deliHolidays)):?> checked=""<?php endif; ?> type="checkbox" name="deliHolidays" value="1" id="deli-monday" class="css-checkbox" />
    			        <label for="deli-monday" class="css-checkbox-label"><?php echo JText::_('OPC_POPUP_DELIVERY_MONDAY') ?></label>
                    </span>
                    <span class="checkbox-text check-2">
                        <input <?php if(in_array(2,$dataOption->deliHolidays)):?> checked=""<?php endif; ?> type="checkbox" name="deliHolidays" value="2" id="deli-tuesday" class="css-checkbox" />
    			        <label for="deli-tuesday" class="css-checkbox-label"><?php echo JText::_('OPC_POPUP_DELIVERY_TUESDAY') ?></label>
                    </span>
                    <span class="checkbox-text check-1">
                        <input <?php if(in_array(3,$dataOption->deliHolidays)):?> checked=""<?php endif; ?> type="checkbox" name="deliHolidays" value="3" id="deli-webnesday" class="css-checkbox" />
    			        <label for="deli-webnesday" class="css-checkbox-label"><?php echo JText::_('OPC_POPUP_DELIVERY_WEBNESDAY') ?></label>
                    </span>
                    <span class="checkbox-text check-2">
                        <input <?php if(in_array(4,$dataOption->deliHolidays)):?> checked=""<?php endif; ?> type="checkbox" name="deliHolidays" value="4" id="deli-thursday" class="css-checkbox" />
    			        <label for="deli-thursday" class="css-checkbox-label"><?php echo JText::_('OPC_POPUP_DELIVERY_THURSDAY') ?></label>
                    </span>
                    <span class="checkbox-text check-1">
                        <input <?php if(in_array(5,$dataOption->deliHolidays)):?> checked=""<?php endif; ?> type="checkbox" name="deliHolidays" value="5" id="deli-friday" class="css-checkbox" />
    			        <label for="deli-friday" class="css-checkbox-label"><?php echo JText::_('OPC_POPUP_DELIVERY_FRIDAY') ?></label>
                    </span>
                    <span class="checkbox-text check-2">
                        <input <?php if(in_array(6,$dataOption->deliHolidays)):?> checked=""<?php endif; ?> type="checkbox" name="deliHolidays" value="6" id="deli-saturday" class="css-checkbox" />
    			        <label for="deli-saturday" class="css-checkbox-label"><?php echo JText::_('OPC_POPUP_DELIVERY_SATURDAY') ?></label>
                    </span>
                    <span class="checkbox-text check-1">
                        <input <?php if(in_array(0,$dataOption->deliHolidays)):?> checked=""<?php endif; ?> type="checkbox" name="deliHolidays" value="0" id="deli-sunday" class="css-checkbox" />
    			        <label for="deli-sunday" class="css-checkbox-label"><?php echo JText::_('OPC_POPUP_DELIVERY_SUNDAY') ?></label>
                    </span>
                </div>
            </div>
            <div class="delivery-row deli-row-4">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo JText::_('OPC_POPUP_DELIVERY_DATE_TYPE') ?></h3>
                    <p class="desc">
                        <?php echo JText::_('OPC_POPUP_DELIVERY_DATE_TYPE_DESC') ?>                        
                    </p>
                </div>
                <div class="row-val">
                    <span class="checkbox-text">
                        <input <?php if(!$dataOption->dateType):?> checked=""<?php endif; ?> type="checkbox" value="0" name="dateType" id="dateOnly" class="css-checkbox dateType" />
    			        <label for="dateOnly" class="css-checkbox-label"><?php echo JText::_('OPC_POPUP_DELIVERY_ONLY_FIELD') ?></label>
                    </span>
                    <span class="checkbox-text">
                        <input <?php if($dataOption->dateType):?> checked=""<?php endif; ?> type="checkbox" value="1" name="dateType" id="dateFromTo" class="css-checkbox dateType" />
    			        <label for="dateFromTo" class="css-checkbox-label"><?php echo JText::_('OPC_POPUP_DELIVERY_FROM_TO') ?></label>
                    </span>
                </div>
            </div>
            <div class="delivery-row deli-row-5">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo JText::_('OPC_POPUP_DELIVERY_TIME') ?></h3>
                    <p class="desc"><?php echo JText::_('OPC_POPUP_DELIVERY_TIME_DESC') ?></p>
                </div>
                <div class="row-val">
                    <span class="onoffswitch">
                        <input <?php if($dataOption->deliTime):?> checked=""<?php endif; ?> type="checkbox" value="1" name="deliTime" class="onoffswitch-checkbox" id="deliTime"/>
                        <label class="onoffswitch-label" for="deliTime">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </span>
                </div>
            </div>
            <div class="delivery-row deli-row-6">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo JText::_('OPC_POPUP_DELIVERY_TIME_TYPE') ?></h3>
                    <p class="desc">
                        <?php echo JText::_('OPC_POPUP_DELIVERY_TIME_TYPE_DESC') ?>                        
                    </p>
                </div>
                <div class="row-val">
                    <span class="checkbox-text">
                        <input <?php if(!$dataOption->timeType):?> checked=""<?php endif; ?> type="checkbox" value="0" name="timeType" id="timeOnly" class="css-checkbox timeType" />
    			        <label for="timeOnly" class="css-checkbox-label"><?php echo JText::_('OPC_POPUP_DELIVERY_ONLY_FIELD_TIME') ?></label>
                    </span>
                    <span class="checkbox-text">
                        <input <?php if($dataOption->timeType):?> checked=""<?php endif; ?> type="checkbox" value="1" name="timeType" id="timeFromTo" class="css-checkbox timeType" />
    			        <label for="timeFromTo" class="css-checkbox-label"><?php echo JText::_('OPC_POPUP_DELIVERY_FROM_TO') ?></label>
                    </span>
                </div>
            </div>
            <!------------------------------------------->
        </form>
    </div>
    <!--End-->
    <div id="popupBottom">
        <span data-file="<?php echo JFactory::getApplication()->input->get('popupcf'); ?>" class="savePopup">Save</span>
        <span class="closePopup">Cancel</span>
    </div>
</div>


