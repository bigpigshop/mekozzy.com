<?php
/**
 * @package Sj Newletter Popup
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2016 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;
/*-- Process---*/
$layout = $params->get('layout', 'default');
$intro = $params->get('intro_text', '');
$footer = $params->get('footer_text', '');
$subject = $params->get('email_template_subject');
$content_email = $params->get('content_email');
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
if ($is_ajax && isset($_POST['is_newletter']) && $_POST['is_newletter']) {
    $get_email = $_POST['email'];
    // Send email
    $email = trim($get_email);
    $email = str_replace("'", "", $email);

    $db	   = JFactory::getDBO();
    $query = "SELECT id FROM #__sj_newsletter_email WHERE `email`='".$email."' LIMIT 1";
    $db->setQuery( $query );
    $emailalreadyexist = $db->loadResult();
    $json = array();
    if($emailalreadyexist)
    {
        $message= "0";
    }else{
        $date = date('Y-m-d h:i:s', time());
        $query = $db->getQuery(true);
        $query = "INSERT INTO #__sj_newsletter_email(email,checked_out,checked_out_time,ordering,state,created_date,confirm_mail)
        values ('".$email."' , 0 , '0000-00-00 00:00:00' , 0, 1, '".$date."',0)";
        $db->setQuery($query);
        $db->execute();
        $emailalready = $db->insertid();
        if($emailalready)
        {
            $message = "1";
        }
        else
        {
            $message = "2";
        }
    }
    die (json_encode($message));
}
require JModuleHelper::getLayoutPath($module->module, $layout);

?>
