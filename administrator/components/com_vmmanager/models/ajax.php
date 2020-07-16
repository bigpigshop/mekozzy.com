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
defined('_JEXEC') or die;
if (!class_exists( 'JFile' )) require(JPATH_LIBRARIES.'/joomla/filesystem/file.php');
class VmmanagerModelAjax extends JModelList
{
    public function __construct($config = array()){
        parent::__construct();
    }
    function createFileStyle(){
        $data = JFactory::getApplication()->input->post->getArray();
        $file = $data['file'];
        $txt['color'] = $data['color'];
        if($file != 'billTo' && $file != 'shipTo'){
            if(!empty($data['options'])){
                if ($file == 'custom_html'){
                    $txt['options'] = JFactory::getApplication()->input->post->get('options','','RAW');
                }else{
                    $txt['options'] = $data['options'];
                } 
            }
        }else{
            //save other options of billTo or shipTo
            unset($data['options']['sortValue']);
            unset($data['options']['sortBillTo']);
            $txt['options'] = $data['options'];        
        }
        if($file == 'delivery'){
            $txt['options']['deliRequired'] = empty($txt['options']['deliRequired'])? 0:$txt['options']['deliRequired'];
            $txt['options']['deliTime'] = empty($txt['options']['deliTime'])? 0:$txt['options']['deliTime'];
            //$txt['options']['deliHolidays'] = empty($txt['options']['deliHolidays'])? array():$txt['options']['deliHolidays'];
            if(!empty($txt['options']['deliHolidays']) || $txt['options']['deliHolidays'] == 0){
                if(!is_array($txt['options']['deliHolidays'])){
                    $tmp = array();
                    $tmp[] = $txt['options']['deliHolidays'];
                    $txt['options']['deliHolidays'] = $tmp;
                }

            }else{
                $txt['options']['deliHolidays'] = array();
            }
        }
        if($file == 'banner' || $file == 'custom_html' || $file == 'confirm'){
            $txt['options']['showtitle'] = empty($txt['options']['showtitle'])? 0:$txt['options']['showtitle'];
        }
        if($file == "logIn"){
            $txt['options']['popupType'] = empty($txt['options']['popupType'])? 0:$txt['options']['popupType'];
        }
        $path = JPATH_COMPONENT_ADMINISTRATOR.'/helpers/config/'.$file.'.json';
        $myfile = fopen($path, "w");
        fwrite($myfile, json_encode($txt));
        fclose($myfile);
        return true;
    }
    function unpublicBT($id){
        $id = (int) $id;
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query = 'UPDATE `#__virtuemart_userfields`
                    SET `account` = "0"
                    WHERE `virtuemart_userfield_id` = '.$id;
        $db->setQuery($query);
        $result = $db->execute();
        return 1;
    }
    function unpublicST($id){
        $id = (int) $id;
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query = 'UPDATE `#__virtuemart_userfields`
                    SET `shipment` = "0"
                    WHERE `virtuemart_userfield_id` = '.$id;
        $db->setQuery($query);
        $result = $db->execute();
        return 1;
    }
    function updateOdering($obj){
        if(empty($obj)){
            return 0;
        }else{
            foreach($obj as $key=>$value){
                $db    = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query = 'UPDATE `#__virtuemart_userfields`
                            SET `ordering` = "'.$value.'"
                            WHERE `name` = '.$db->quote($key);
                $db->setQuery($query);
                $result = $db->execute();
            }
        }
        return 1;
    }
    function getImages($e,$sort = false){
        $typeArray = '';
        $dir = JPATH_ADMINISTRATOR.'/'.$e;
        $info=array();
        $dh=opendir($dir);
        $infod=array();$infof=array();
        $typeArray=array('jpg','png','ico','gif','bmp');
        while ( $name = readdir( $dh ))
        {
            if( $name=="." || $name==".." ) continue;
            if ( is_file( "$dir/$name" )){
                if(!$sort){
                    if(in_array($this->getTypeFile($dir.'/'.$name),$typeArray)){
                        $size = getimagesize($dir.'/'.$name);
                        $TypeFile = pathinfo($dir.'/'.$name);
                        $tinfo['name']=$TypeFile['filename'];
                        $tinfo['filetype']=$TypeFile['extension'];
                        $tinfo['path']=$e.'/'.$name;
                        $tinfo['dir']=$dir.'/'.$name;
                        $tinfo['created']=date('d/m/Y',filectime("$dir/$name")) ;
                        $tinfo['filesize']=$this->FileSizeConvert(filesize("$dir/$name"));
                        $tinfo['size']= $size[0] . ' x ' . $size[1];
                        $infof[]=$tinfo;

                    }
                }else{
                   $infof[]=$name;
                }
            }
        }
        return $infof;
    }
    function deleteFile($dir){
        if(JFile::delete($dir)){
            return 'true';
        }else{
            return 'false';
        }
    }
    function uploadFile(){
        \JLoader::import('joomla.filesystem.file');
        
        if((JFactory::getApplication()->input->files->count==0)) die('not file');
        $dir = 'components/com_vmmanager/helpers/media';
        $img = $this->getImages($dir,true);
        $uplFile = JFactory::getApplication()->input->files->get('upl_file');
        $name = $uplFile['name'];
        $file = $uplFile['tmp_name'];
        if(in_array($name,$img)){
            $name = $this->changNameFile($name,$img);
        }
        $msg = 'error';
        if($uplFile!==null && $uplFile['error'] == 0){
            //$JFile = new JFile;
            if(JFile::move($file,JPATH_ADMINISTRATOR.'/'.$dir.'/'.$name)){
                $msg = 'suscess';
            }else{
                $msg = 'error';
            }
        }
        return $msg;
    }
    function createFileConfigOpc(){
        $data = JFactory::getApplication()->input->post->getArray();
        $data['opc']['loadCss'] = empty($data['opc']['loadCss']) ? 0:$data['opc']['loadCss'];
        $data['opc']['loadJs'] = empty($data['opc']['loadJs']) ? 0:$data['opc']['loadJs'];
        $data['opc']['loadJsUI'] = empty($data['opc']['loadJsUI']) ? 0:$data['opc']['loadJsUI'];
        $data['opc']['loadAjaxIcon'] = empty($data['opc']['loadAjaxIcon']) ? 0:$data['opc']['loadAjaxIcon'];
        $data['opc']['blockspam'] = empty($data['opc']['blockspam']) ? 0:$data['opc']['blockspam'];
        $data['opc']['geolocal'] = empty($data['opc']['geolocal']) ? 0:$data['opc']['geolocal'];
        $txt = $data['opc'];
        $path = JPATH_COMPONENT_ADMINISTRATOR.'/helpers/opc.json';
        $myfile = fopen($path, "w");
        fwrite($myfile, json_encode($txt));
        fclose($myfile);
        // kiem tra neu khong co element custom html thi reset content cua element        
        $design = $txt['opc-design'];
        if (strpos($design,'"element": "custom_html",')== false){            
            $data = file_get_contents(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/config/custom_html.json');
            $data = json_decode($data);
            $data->options->htmlContent = '';
            $data = json_encode($data);
            file_put_contents(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/config/custom_html.json', $data);
        }
        return true;
    }
    function refUser(){
        $current = JFactory::getUser();
		$session = JFactory::getSession();
		$session->set('user', new JUser($current->id));
        $current = JFactory::getUser();
    }
    /** Cover function */
    function getTypeFile($path){
        $path_parts = pathinfo($path);
        return $path_parts['extension'];
    }
    function FileSizeConvert($bytes)
    {
        $bytes = floatval($bytes);
            $arBytes = array(
                0 => array(
                    "UNIT" => "TB",
                    "VALUE" => pow(1024, 4)
                ),
                1 => array(
                    "UNIT" => "GB",
                    "VALUE" => pow(1024, 3)
                ),
                2 => array(
                    "UNIT" => "MB",
                    "VALUE" => pow(1024, 2)
                ),
                3 => array(
                    "UNIT" => "KB",
                    "VALUE" => 1024
                ),
                4 => array(
                    "UNIT" => "B",
                    "VALUE" => 1
                ),
            );

        foreach($arBytes as $arItem)
        {
            if($bytes >= $arItem["VALUE"])
            {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
                break;
            }
        }
        return $result;
    }
    function changNameFile($oldname,$arr){
       $name  = explode('.',$oldname);
       $extension = array_pop($name);
       $name = implode('.',$name);
       $f = 1;
       $n = 1;
       $newname = '';
       while($f>0){
            $newname = $name.'('.$n.').'.$extension;
            if(!in_array($newname,$arr)){
                $f = -1;
            }else{
                $n++;
            }
       }
        return $newname;
    }
}