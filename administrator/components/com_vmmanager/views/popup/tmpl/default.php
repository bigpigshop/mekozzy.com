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
$config = JFactory::getApplication()->input->get('popupcf');
?>
<link href="components/com_vmmanager/assets/css/jquery-te.min.css" type="text/css" rel="stylesheet"/>
<link href="components/com_vmmanager/assets/css/colpick.min.css" type="text/css" rel="stylesheet"/>
<script src="components/com_vmmanager/assets/js/jquery-te.min.js"></script>
<script src="components/com_vmmanager/assets/js/colpick.min.js"></script>
<div id="popupContent">
    <i class="opc-iconcancel-1 close-setting"></i>
    <?php echo $this->loadTemplate(strtolower($config)); ?>
</div>
<script src="components/com_vmmanager/assets/js/popup.min.js"></script>