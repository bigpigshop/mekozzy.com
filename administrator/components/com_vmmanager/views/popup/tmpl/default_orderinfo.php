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
$data = file_get_contents('components/com_vmmanager/helpers/config/orderInfo.json');
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
                <legend>Title Style</legend>
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
                <legend>Content Style</legend>
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
                <div class="cols-left">
                    <div class="lb-config">
                        <span class="input-val-popup">
                            <input class="input-size" value="<?php echo $dataStyle->quantityColumnWidth ?>" name="quantityColumnWidth"/>
                        </span>                        
                        <div class="title-and-desc">
                            <span class="title"><?php echo JText::_('OPC_POPUP_QTY_COLUMN_WIDTH') ?></span>
                            <p class="desc"><?php echo JText::_('OPC_POPUP_STYLE_WIDTH_DESC') ?></p>
                        </div>
                    </div>
                    <div class="lb-config">
                        <span class="input-val-popup">
                            <input class="input-size" value="<?php echo $dataStyle->priceColumnWidth ?>" name="priceColumnWidth"/>
                        </span>                        
                        <div class="title-and-desc">
                            <span class="title"><?php echo JText::_('OPC_POPUP_PRICE_COLUMN_WIDTH') ?></span>
                            <p class="desc"><?php echo JText::_('OPC_POPUP_STYLE_WIDTH_DESC') ?></p>
                        </div>
                    </div>
                </div>
            </fieldset>

        </form>
    </div>
    <div id="privateItem" class="active">
        <form name="optionForm" action="#" method="POST" id="optionForm">
            <div class="baner-row">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo 'Disable product link' ?></h3>
                    <p class="desc"><?php echo 'Remove link to product page on product name' ?></p>
                </div>
                <div class="row-val">
                    <span class="onoffswitch">
                    <input <?php if($dataOption->disable_link):?> checked="" <?php endif; ?>  type="checkbox" value="1" name="disable_link" class="onoffswitch-checkbox" id="disable_link"/>
                        <label class="onoffswitch-label" for="disable_link">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </span>
                </div>
            </div>
            <div class="baner-row">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo 'Hide empty Custom Fields' ?></h3>
                    <p class="desc"><?php echo 'Hide all Custom Fields with empty value' ?></p>
                </div>
                <div class="row-val">
                    <span class="onoffswitch">
                    <input <?php if($dataOption->hideempty):?> checked="" <?php endif; ?>  type="checkbox" value="1" name="hideempty" class="onoffswitch-checkbox" id="hideempty"/>
                        <label class="onoffswitch-label" for="hideempty">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </span>
                </div>
            </div>
            <div class="baner-row">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo 'Reload when update' ?></h3>
                    <p class="desc"><?php echo 'Reload when update quantity, delete product in cart' ?></p>
                </div>
                <div class="row-val">
                    <span class="onoffswitch">
                    <input <?php if($dataOption->reload):?> checked="" <?php endif; ?>  type="checkbox" value="1" name="reload" class="onoffswitch-checkbox" id="reload"/>
                        <label class="onoffswitch-label" for="reload">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </span>
                </div>
            </div>
            <div class="baner-row">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo 'Hide SKU' ?></h3>
                    <p class="desc"><?php echo 'Hide SKU column' ?></p>
                </div>
                <div class="row-val">
                    <span class="onoffswitch">
                    <input <?php if($dataOption->hide_sku):?> checked="" <?php endif; ?>  type="checkbox" value="1" name="hide_sku" class="onoffswitch-checkbox" id="hide_sku"/>
                        <label class="onoffswitch-label" for="hide_sku">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </span>
                </div>
            </div>
            <div class="baner-row">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo 'Hide Tax' ?></h3>
                    <p class="desc"><?php echo 'Hide Tax column' ?></p>
                </div>
                <div class="row-val">
                    <span class="onoffswitch">
                    <input <?php if($dataOption->hide_tax):?> checked="" <?php endif; ?>  type="checkbox" value="1" name="hide_tax" class="onoffswitch-checkbox" id="hide_tax"/>
                        <label class="onoffswitch-label" for="hide_tax">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </span>
                </div>
            </div>
            <div class="baner-row">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo 'Hide Discount' ?></h3>
                    <p class="desc"><?php echo 'Hide Discount column' ?></p>
                </div>
                <div class="row-val">
                    <span class="onoffswitch">
                    <input <?php if($dataOption->hide_discount):?> checked="" <?php endif; ?>  type="checkbox" value="1" name="hide_discount" class="onoffswitch-checkbox" id="hide_discount"/>
                        <label class="onoffswitch-label" for="hide_discount">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </span>
                </div>
            </div>
            <div class="baner-row">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo 'Hide Payment Cost' ?></h3>
                    <p class="desc"><?php echo 'Hide Payment Cost row' ?></p>
                </div>
                <div class="row-val">
                    <span class="onoffswitch">
                    <input <?php if($dataOption->hide_payment_cost):?> checked="" <?php endif; ?>  type="checkbox" value="1" name="hide_payment_cost" class="onoffswitch-checkbox" id="hide_payment_cost"/>
                        <label class="onoffswitch-label" for="hide_payment_cost">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </span>
                </div>
            </div>
            <div class="baner-row">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo 'Show item Sale Price' ?></h3>
                    <p class="desc"><?php echo 'Show item Sale Price and Base Price With Tax on each items' ?></p>
                </div>
                <div class="row-val">
                    <span class="onoffswitch">
                    <input <?php if($dataOption->show_sale_price):?> checked="" <?php endif; ?>  type="checkbox" value="1" name="show_sale_price" class="onoffswitch-checkbox" id="show_sale_price"/>
                        <label class="onoffswitch-label" for="show_sale_price">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </span>
                </div>
            </div>
            <div class="baner-row">
                <div class="title-and-desc">
                    <h3 class="title"><?php echo 'Hide item Total Base Price' ?></h3>
                    <p class="desc"><?php echo 'Hide Base Price on Total column for each items' ?></p>
                </div>
                <div class="row-val">
                    <span class="onoffswitch">
                    <input <?php if($dataOption->hide_total_base_price):?> checked="" <?php endif; ?>  type="checkbox" value="1" name="hide_total_base_price" class="onoffswitch-checkbox" id="hide_total_base_price"/>
                        <label class="onoffswitch-label" for="hide_total_base_price">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </span>
                </div>
            </div>
        </form>
    </div>
    <div id="popupBottom">
        <span data-file="<?php echo JFactory::getApplication()->input->get('popupcf'); ?>" class="savePopup">Save</span>
        <span class="closePopup">Cancel</span>
    </div>
</div>


