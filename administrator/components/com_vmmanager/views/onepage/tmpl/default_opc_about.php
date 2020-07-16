<?php
/** ------------------------------------------------------------------------
  Virtuemart manager
  author CMSMart Team
  copyright: Copyright (c) 2012 http://cmsmart.net. All Rights Reserved.
  @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
  Websites: http://cmsmart.net
  Email: team@cmsmart.net
  Technical Support: Forum - http://cmsmart.net/forum
  ------------------------------------------------------------------------- */
defined('_JEXEC') or die;

$pluginXML = JPATH_PLUGINS . '/system/onestepcheckout/onestepcheckout.xml';
$xml = simplexml_load_file($pluginXML);
$updated = date ("d F Y", filemtime($pluginXML));

?>
<h2><?php echo JText::_('COM_VM_MANAGER_ABOUT') ?></h2>
<div class="about-row about-row-1">
    <div class="about-image"><img src="components/com_vmmanager/assets/images/opc.png" /></div>
    <div class="about-opc-info">
        <h3>One page checkout for Virtuemart</h3>
        <p><span class="title">Version</span><span class="value"><?php echo (empty($xml->version)) ? '???' : $xml->version ?></span></p>
        <p><span class="title">Updated</span><span><?php echo (empty($updated)) ? '???' : $updated ?></span></p>
        <p><span class="title">Author</span><span>Netbase Team</span></p>
        <p><span class="title">Email</span><span><a href="mailto:team@cmsmart.net">Team@cmsmart.net</a></span></p>
        <p><span class="title">Support</span><span><a href="https://cmsmart.net/support_ticket/">Support Ticket</a></span></p>
        <div class="social">
            <p>Follow us to stay informed about updates :</p>
            <a class="facebook" href="https://www.facebook.com/CmsmartMarketplace" target="_blank"><i class="opc-iconfacebook-official"></i></a>
            <a class="twitter" href="https://twitter.com/cmsmartnet" target="_blank"><i class=" opc-icontwitter-squared"></i></a>
            <a class="google" href="https://plus.google.com/u/1/104281224509625581222/posts" target="_blank"><i class="opc-icongplus-squared"></i></a>
            <a class="pinterest" href="http://www.pinterest.com/cmsmartnet/" target="_blank"><i class="opc-iconpinterest-squared"></i></a>
        </div>       

    </div>
</div>
<div class="about-row about-row-2">
    <div class="about-desc">
        <h3>Update & documentation</h3>
        <div class="sort-desc">
            <p>One page checkout, as the name shows such carts occupy just a single page, has faster checkout time,
                anonymous checkout option, and reduced cart abandonment. </p>   
            <p>Shopping cart desertion is ubiquitous in online retailing, many companies reporting that more than 60% of checkouts end without completion. The main problem is multipage checkout pages , which causes distractions and problems for the customers.</p>
            <p>One page for checkout is based on AJAX, which stands for Asynchronous JavaScript And Xml.Which is the most advanced web developing technique used these days. So that’s why one page checkout has more conversions, better page performance and customers satisfaction.</p>
            <p>One of the biggest benefits of one page checkout is the option for guest checkout. Because many times customers do not want to create an account and log in, they simply want to visit the site as a "guest" and make their purchase anonymously. When this is an option it is much easier for the shoppers to complete their purchase quickly and easily without having sign in or create an account.</p>
        </div>
    </div>
    <div class="changlog">
        <h3>Change log</h3>
        <div class="changlog-val">
            <?php if (!empty($this->get_version_api)) foreach($this->get_version_api->data as $key=>$value):?>
           
                <div class="ver-1">
                    <h4>Version <?php echo $value->version_number; ?>   Updated: <?php echo JFactory::getDate($value->created)->Format('Y, F d'); ?></h4>
                    <div>
                        <?php echo $value->descriptions; ?>
                    </div>
                </div>
            <?php endforeach;?>            
           
        </div>        
    </div>
</div>