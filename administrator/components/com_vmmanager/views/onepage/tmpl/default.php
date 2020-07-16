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
$config_ = JFactory::getConfig();
$lifetime = $config_->get( 'lifetime' ) * 60;
if($lifetime == 1){
   $lifetime = ($lifetime-0.5)*1000;
}else{
   $lifetime = ($lifetime-1)*1000; 
}

$useragent=$_SERVER['HTTP_USER_AGENT'];
$mobi_ = '';
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|ipad|tablet|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
    $mobi_ = ' mobile ';
?>
<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->

<script>
//<![CDATA[
var url_opc = "<?php echo JURI::root(); ?>";
var lifetime = <?php echo $lifetime ?>;
//]]>
</script>
<script src="components/com_vmmanager/assets/js/jquery.ui.min.js"></script>
<script src="components/com_vmmanager/assets/js/lodash.min.js"></script>
<script src="components/com_vmmanager/assets/js/gridstack.min.js"></script>
<script src="../components/com_vmmanager/assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="components/com_vmmanager/assets/js/jquery.fileupload.min.js"></script>
<script src="components/com_vmmanager/assets/js/develop.min.js"></script>
<div class="component-title">
    <span class="responsive-menu opc-iconindent-left"></span>
    <h1><?php echo JText::_('COM_VM_MANAGER_TITLE') ?></h1>
</div>
<div id="manager-wapper" class="active<?php echo $mobi_; ?>">
    <!--Menu 1-->
    <div id="main-menu">
        <h3><span class="sort-tabs" data-active="full"><i class="opc-iconleft-open-big"></i><i class="opc-iconright-open-big"></i></span></h3>
        <ul>
            <span class="close-menu opc-iconcancel"></span>
            <!--
            <li tabs="#main-dashboard" class="tab-1"><i class="opc-iconhome-outline"></i><span><?php echo JText::_('COM_VM_MANAGER_MENU_DASHBOARD') ?></span></li>
            -->
            <li tabs="#main-opc" class="tab-1 active"><i class="opc-iconbasket-1"></i><span><?php echo JText::_('COM_VM_MANAGER_MENU_OPC') ?></span></li>
        </ul>
    </div>
    <!-- End -->
    <!-- Content 1 -->
    <div id="main-content">
        <div id="manager-breadcrumbs">
            <span class="breadcrumb-parent"><?php echo JText::_('COM_VM_MANAGER_MENU_OPC') ?></span>
            <span class="breadcrumb-child">About</span>
            <span class="save-all">SAVE</span>
            <div class="save-icon-pacman-parent saved-opc">
            	<div class="save-icon-pacman">
            		<span></span>
            		<span></span>
            		<span></span>
            		<span></span>
            		<span></span>
            	</div>
            </div>
        </div>
        <div id="main-dashboard" class="">
            <?php echo $this->loadTemplate('dashboard'); ?>
        </div>
        <div id="main-opc" class="active">
            <?php echo $this->loadTemplate('opc'); ?>
        </div>
    </div>
    <!-- End -->
</div>
<div class="reponsive-menu-cus">
    <ul>
        <span class="opc-iconcancel-1 close-respon-menu"></span>
        <!--
        <li >
            <a class="active" tabs="#main-dashboard"><i class="opc-iconhome-outline"></i><span><?php echo JText::_('COM_VM_MANAGER_MENU_DASHBOARD') ?></span></a>
        </li>
        -->
        <li>
            <a tabs="#main-opc" ><i class="opc-iconbasket-1"></i><span><?php echo JText::_('COM_VM_MANAGER_MENU_OPC') ?></span></a>            
            <ul>
                <li class="tab-2">
                    <a class="active" tabtmp="#main-opc" tab="#Opc-about"><?php echo JText::_('OPC_MENU_ABOUT') ?></a>
                </li>
                <li class="tab-2">
                    <a tabtmp="#main-opc" tab="#Opc-design"><?php echo JText::_('OPC_MENU_LAYOUT') ?></a>
                </li>
                <li class="tab-2">
                    <a tabtmp="#main-opc" tab="#Opc-config"><?php echo JText::_('OPC_MENU_SETTING') ?></a>
                </li>
            </ul>
        </li>
    </ul>
</div>