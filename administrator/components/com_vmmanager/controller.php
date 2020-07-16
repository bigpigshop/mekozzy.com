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
class VmmanagerController extends JControllerLegacy
{
    protected $default_view = 'onepage';
    public function display($cachable = false, $urlparams = false)
    {
        parent::display();
        return $this;
    }
    function saveJson(){
        $ajaxModel = $this->getModel('ajax');
        $status = 0;
        $data = JFactory::getApplication()->input->post->getArray();
        $type = $data['file'];
        if(!empty($data['options'])){
            $option = $data['options'];
            if($type == 'billTo' || $type == 'shipTo'){
                if($option['sortBillTo']){
                    $option['sortBillTo'] = json_decode($option['sortBillTo']);
                    $status = $ajaxModel->updateOdering($option['sortBillTo']);
                }
            }
        }
        if($ajaxModel->createFileStyle()){
           $status = 1;

        }
        echo $status;
        JFactory::getApplication()->close();

    }
    function unPublicField(){
        $id = JFactory::getApplication()->input->getInt('fieldid',0);
        $type = JFactory::getApplication()->input->get('type',0);
        if($id && $type == 'BT'){
            $ajaxModel = $this->getModel('ajax');
            echo $ajaxModel->unpublicBT($id);
        }
        if($id && $type == 'ST'){
            $ajaxModel = $this->getModel('ajax');
            echo $ajaxModel->unpublicST($id);
        }
        JFactory::getApplication()->close();
    }
    function getImages(){
        $ajaxModel = $this->getModel('ajax');
        $dir = JFactory::getApplication()->input->get('dir');
        $img = $ajaxModel->getImages($dir);
        $img = json_encode($img);
        echo $img;
        JFactory::getApplication()->close();
    }
    function deleteFile(){
        $ajaxModel = $this->getModel('ajax');
        $dir = JFactory::getApplication()->input->get('source',0);
        if(!$dir){
            echo 'false';
            JFactory::getApplication()->close();
        }
        $msg = $ajaxModel->deleteFile($dir);
        echo $msg;
        JFactory::getApplication()->close();
    }
    function uploadFile(){
       $ajaxModel = $this->getModel('ajax');
       $msg = $ajaxModel->uploadFile();
       echo $msg;
       JFactory::getApplication()->close();
    }
    function saveAllJson(){
        $ajaxModel = $this->getModel('ajax');
        $ajaxModel->createFileConfigOpc();
        JFactory::getApplication()->close();
    }
    function refUser(){
        $ajaxModel = $this->getModel('ajax');
        $ajaxModel->refUser();
        JFactory::getApplication()->close();
    }
}
