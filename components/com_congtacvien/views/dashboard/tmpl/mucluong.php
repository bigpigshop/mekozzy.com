<?php
/**
 * @package    api
 * @subpackage C:
 * @author     Hau Pham {@link jooext.com}
 * @author     Created on 02-Oct-2017
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

//$document = JFactory::getDocument();
//$document->addStyleSheet(JUri::root(true)."/media/com_congtacvien/css/bootstrap.min.css");

?>
<style type="text/css">
    <!--
    input, select, textarea {
        height: auto !important;
    }

    .ui-jqgrid .ui-jqgrid-btable tbody tr.jqgrow td {
        font-size: 0.9em;
    }

    .nav {
        margin-bottom: 10px;
    }

    .ui-jqgrid td input, .ui-jqgrid td select, .ui-jqgrid td textarea {
        margin: auto;
    }

    .ui-jqgrid .ui-jqgrid-htable thead th {
        padding: 3px;
    }

    .ui-jqgrid-labels th {
        text-align: center;
    }


    -->
</style>

<div ng-controller="MainController">
    <toaster-container toaster-options="{'position-class': 'toast-bottom-right', 'progress-bar': true}"></toaster-container>


    <div class="row-fluid">
        <div class="col-7">
            <table id="luongprofile_list" class="scroll"></table>
            <div id="luongprofile_navi" class="scroll" style="text-align:right"></div>
        </div>

        <div class="col-5">
            <table id="mucluong_list" class="scroll"></table>
            <div id="mucluong_navi" class="scroll" style="text-align:right"></div>
        </div>

    </div>


</div>


<script type="text/javascript">

    myApp = angular.module("myApp", ['toaster', 'ui.bootstrap']);

    myApp.controller('MainController', ['$scope', '$http', 'toaster', '$interval', function($scope, $http, toaster, $interval){

        $scope.profile = null;

        angular.element(document).ready(function(){

            angular.element("#luongprofile_list").jqGrid({
                url:'index.php?option=com_congtacvien&task=dashboard.listmucluongprofile',
                editurl:'index.php?option=com_congtacvien&task=dashboard.processlistmucluongprofile&<?php echo Jsession::getFormToken() ?>=1',
                datatype: 'json',
                mtype: 'POST',
                styleUI : 'Bootstrap',
                responsive : false,
                colModel :[
                    {label:'#', name:'id', index:'id',width:40, align:'center', editable:true, editoptions:{readonly:true}},
                    {label:'Đ.Tượng', name:'doituong_code', index:'doituong_code', width:100, align:'center',formatter:'select', editable:true, edittype:"select",editoptions:{value:"CTVCT:CTV chính thức;CTVTT:CTV hợp tác"}, stype:'select', searchoptions:{value:":--Tất cả--;CTVCT:CTV chính thức;CTVTT:CTV hợp tác"}},
                    {label:'Tên Profile', name:'title', index:'title', width:150, editable:true},
                    {label:'Mô tả', name:'description', index:'description', width:100, editable:true},
                    {label:'Ngày tạo', name:'created', index:'created', width:100, editable:false},
                    {label:"Tr.Thái", name:'published', index:'published', width:80, align:'center',formatter:'checkbox', editable:true,edittype:"checkbox",editoptions:{value:"1:0", defaultValue: "1"}
                        , stype:'select', searchoptions:{value:":All;1:SD;0:Ngưng"}},
                    {label:'Ordering', name:'ordering', index:'ordering', width:40, editable:true},
                ],
                pager: jQuery('#luongprofile_navi'),
                rowNum:50,

                rownumbers:true,
                viewrecords: true,

                scrollPopUp:true,
                scrollLeftOffset: "90%",
                scroll: 1,
                emptyrecords: 'Kéo xuống để xem tiếp dữ liệu',
                sortname: 'created',
                sortorder: "desc",
                caption: '',
                height: 300,
                width: 650,
                shrinkToFit: true,
                autoResizing: {
                    compact: true
                },
                onSelectRow: function(ids) {
                    if(ids != null) {

                        var selectedRow = jQuery("#luongprofile_list").getRowData(ids);

                        $scope.$apply(function () {
                            $scope.profile = selectedRow;
                            angular.element("#mucluong_list")
                                .setGridParam({
                                    url: 'index.php?option=com_congtacvien&task=dashboard.listluongnguong&profile_id='  + $scope.profile.id
                                })
                                .trigger('reloadGrid');
                        });


                    }
                },
                loadComplete: function () {

                }
            })
                .navGrid('#luongprofile_navi',{del:true,search:false, add:true, edit:true, refreshtext:"Tải lại", deltext:"Xóa", addtext:"Thêm",edittext:"Sửa",
                        beforeRefresh: function(){
                            angular.element("#luongprofile_list")
                                .setGridParam({
                                    url: 'index.php?option=com_congtacvien&task=dashboard.listmucluongprofile'
                                })
                                .trigger('reloadGrid');
                        }
                    }
                    , {	width:500, height:'auto',closeAfterEdit:true,reloadAfterSubmit:true}
                    , {	width:500, height:'auto',closeAfterAdd:true,reloadAfterSubmit:true}
                    , { afterSubmit: function (response, postdata){
                            var res = jQuery.parseJSON(response.responseText);
                            if (res.code == 0) {
                                return [false, res.data, ""];
                            } else {
                                jQuery("#luongprofile_list").trigger("reloadGrid");
                                return [true, res.data, ""];
                            }
                        }
                    }
                );

            angular.element("#luongprofile_list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false}).jqGrid('bindKeys');

            angular.element("#mucluong_list").jqGrid({
                url:'index.php?option=com_congtacvien&task=dashboard.listluongnguong',
                editurl:'index.php?option=com_congtacvien&task=dashboard.processlistluongnguong&<?php echo Jsession::getFormToken() ?>=1',
                datatype: 'json',
                mtype: 'POST',
                styleUI : 'Bootstrap',
                responsive : false,
                colModel :[
                    {label:'#', name:'id', index:'id',width:40, align:'center', editable:true, editoptions:{readonly:true}},
                    {label:'#Profile', name:'profile_id', index:'profile_id', editable:true, width:180, formatter:'select', edittype:"select", editoptions:{value:"<?php echo $this->lists['profile_json']; ?>"},
                        stype:"select", searchoptions:{value:"<?php echo $this->lists['profile_json']; ?>"}},
                    {label:'Ngưỡng', name:'nguong', index:'nguong', width:120, editable:true, align:'right', formatter:"number", formatoptions:{decimalSeparator:",", thousandsSeparator:".",decimalPlaces:0, defaultValue:0}},
                    {label:'% Thưởng', name:'bonus', index:'bonus',width:60, align:'center', editable:true, formatter:"number", formatoptions:{decimalSeparator:",", thousandsSeparator:".",decimalPlaces:1, defaultValue:0}},
                ],
                pager: jQuery('#mucluong_navi'),
                rowNum:50,

                rownumbers:true,
                viewrecords: true,

                scrollPopUp:true,
                scrollLeftOffset: "90%",
                scroll: 1,
                emptyrecords: 'Kéo xuống để xem tiếp dữ liệu',
                sortname: 'nguong',
                sortorder: "asc",
                caption: '',
                height: 300,
                width: 450,
                shrinkToFit: true,
                autoResizing: {
                    compact: true
                },
                onSelectRow: function(ids) {
                    if(ids != null) {

                        var selectedRow = jQuery("#mucluong_list").getRowData(ids);

                        $scope.$apply(function () {

                        });


                    }
                },
                loadComplete: function () {

                }
            })
                .navGrid('#mucluong_navi',{del:true,search:false, add:true, edit:true, refreshtext:"Tải lại", deltext:"Xóa", addtext:"Thêm",edittext:"Sửa",
                        beforeRefresh: function(){
                            angular.element("#mucluong_list")
                                .setGridParam({
                                    url: 'index.php?option=com_congtacvien&task=dashboard.listluongnguong&profile_id='  + $scope.profile.id
                                })
                                .trigger('reloadGrid');
                        }
                    }
                    , {	width:500, height:'auto',closeAfterEdit:true,reloadAfterSubmit:true}
                    , {	width:500, height:'auto',closeAfterAdd:true,reloadAfterSubmit:true}
                    , { afterSubmit: function (response, postdata){
                            var res = jQuery.parseJSON(response.responseText);
                            if (res.code == 0) {
                                return [false, res.data, ""];
                            } else {
                                jQuery("#mucluong_list").trigger("reloadGrid");
                                return [true, res.data, ""];
                            }
                        }
                    }
                );

            angular.element("#mucluong_list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false}).jqGrid('bindKeys');


        });



    }]);


</script>
