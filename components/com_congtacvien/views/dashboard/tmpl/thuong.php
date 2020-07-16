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


?>
<style type="text/css">
    <!--
    input, select, textarea {
        font-size: 1em !important;
    }
    .form-group > select { height: 2.8em !important;}

    .ui-jqgrid tr.ui-search-toolbar td > input, .ui-search-table > select, .ui-search-table > textarea {
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


    <div class="panel panel-info" style="height: 90px;">
        <div class="panel-heading"><strong><i class="fa fa-user"></i> Thông tin Cộng tác viên</strong></div>
        <div class="panel-body">

            <div class="vendor-info"></div>

        </div>
    </div>


    <uib-tabset active="0">
        <uib-tab index="0" select="selectTab(0)">
            <uib-tab-heading>
                <i class="fa fa-users"></i> <strong>Cộng tác viên</strong>
            </uib-tab-heading>

            <div class="row-fluid">
                <table id="vendor_list" class="scroll"></table>
                <div id="vendor_navi" class="scroll" style="text-align:right"></div>
            </div>

        </uib-tab>

        <uib-tab index="1" select="selectTab(1)">
            <uib-tab-heading>
                <i class="fa fa-cog"></i> <strong>Cấu hình</strong>
            </uib-tab-heading>

            <div class="row-fluid">

                <form ng-submit="saveConfig()">
                    <div class="row-fluid">
                        <div class="col-12 text-right">
                            <input type="hidden" ng-model="configData.vendor_id">
                            <input type="hidden" ng-model="configData.id">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Lưu thông tin</button>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="inputDoiTuong">Nhóm đối tượng</label>
                                <select ng-model="configData.doituong" type="text" class="form-control" id="inputDoiTuong">
                                    <option value="">-- Chọn nhóm cộng tác viên --</option>
                                    <option value="CTVCT">CTV chính thức</option>
                                    <option value="CTVTT">CTV hợp tác</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="inputLuongCoban">Lương cơ bản</label>
                                <input ng-model="configData.luong_coban" type="text" class="form-control" id="inputLuongCoban" aria-describedby="luongcbHelp">
                                <small id="luongcbHelp" class="form-text text-muted">Mức lương cơ bản dành cho cộng tác viên nếu có.</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="profileFormControlSelect">Mẫu lương thưởng</label>
                                <select class="form-control" id="profileFormControlSelect" ng-model="configData.profile_id" ng-change="getSchemeBonus(configData.profile_id)">
                                    <option value='0'>---Please select---</option>
                                    <option ng-repeat="item in profiles" ng-value='{{item.id}}'>{{item.text}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="scheme-bonusinfo"></div>
                        </div>
                    </div>

                </form>


            </div>

        </uib-tab>

        <uib-tab index="3" select="selectTab(3)">
            <uib-tab-heading>
                <i class="fas fa-coins"></i> <strong>Doanh số</strong>
            </uib-tab-heading>

            <div class="row-fluid">
                <table id="sales_list" class="scroll"></table>
                <div id="sales_navi" class="scroll" style="text-align:right"></div>
            </div>

        </uib-tab>

    </uib-tabset>


</div>


<script type="text/javascript">

    myApp = angular.module("myApp", ['toaster', 'ui.bootstrap', 'ui.mask']);

    myApp.controller('MainController', ['$scope', '$http', 'toaster', '$interval', function($scope, $http, toaster, $interval){

        $scope.profiles = <?php echo json_encode($this->lists['profile'], JSON_NUMERIC_CHECK); ?>;
        $scope.vendor = null;
        $scope.configData = null;
        $scope.sales = null;
        $scope.profileInfo = null;


        $scope.getVendorConfig = function()
        {
            if ($scope.vendor == null) return;

            toaster.pop('info', 'Đang lấy dữ liệu...');
            let url = 'index.php?option=com_congtacvien&task=dashboard.getvendorconfig&vendor_id='+$scope.vendor.virtuemart_vendor_id;
            $http.get(url)
                .then(function(response){
                    if (response.status == 200) {
                        if (response.data.success) {
                            $scope.configData = response.data.data;
                            $scope.getSchemeBonus($scope.configData.profile_id);
                        } else {
                            toaster.pop("error", response.data.message, "", 0);
                        }
                    } else {
                        toaster.pop("error", response.statusText, "", "");
                    }
                    toaster.clear();
                });


        };

        $scope.getVendorSales = function()
        {
            if ($scope.vendor == null) return;
            angular.element("#sales_list")
                .setGridParam({
                    url: 'index.php?option=com_congtacvien&task=dashboard.listvendorsale&vendor_id=' + $scope.vendor.virtuemart_vendor_id
                })
                .trigger('reloadGrid');
        };

        $scope.saveConfig = function()
        {
            toaster.pop('info', 'Đang lưu dữ liệu...');
            let url = 'index.php?option=com_congtacvien&task=dashboard.savevendorconfig';

            $http.post(url, $scope.configData)
                .then(function(response){
                    if (response.status == 200) {
                        if (response.data.success) {
                            $scope.configData = response.data.data;
                        } else {
                            toaster.pop("error", response.data.message, "", 0);
                        }
                    } else {
                        toaster.pop("error", response.statusText, "", "");
                    }
                    toaster.clear();
                });
        };

        $scope.getSchemeBonus = function(profile_id)
        {
            let url = 'index.php?option=com_congtacvien&task=dashboard.getschemebonus&profile_id=' + profile_id;
            $http.get(url)
                .then(function(response){
                    if (response.status == 200) {
                        if (response.data.success) {
                            $scope.profileInfo = response.data.data;
                        } else {
                            toaster.pop("error", response.data.message, "", 0);
                        }
                    } else {
                        toaster.pop("error", response.statusText, "", "");
                    }
                });

        };

        angular.element(document).ready(function(){

            angular.element("#vendor_list").jqGrid({
                url:'index.php?option=com_congtacvien&task=dashboard.listvendor',
                editurl:'index.php?option=com_congtacvien&task=dashboard.processlistvendor&<?php echo Jsession::getFormToken() ?>=1',
                datatype: 'json',
                mtype: 'POST',
                styleUI : 'Bootstrap',
                responsive : false,
                colModel :[
                    {label:'#', name:'virtuemart_vendor_id', index:'virtuemart_vendor_id',width:40, align:'center', editable:true},
                    {label:'Đ.Tượng', name:'doituong_code', index:'doituong_code', width:100, align:'center',formatter:'select', editable:true, edittype:"select",editoptions:{value:"CTVCT:CTV chính thức;CTVTT:CTV hợp tác"}, stype:'select', searchoptions:{value:":--Tất cả--;CTVCT:CTV chính thức;CTVTT:CTV hợp tác"}},
                    {label:'Tên Cửa hàng', name:'vendor_store_name', index:'vendor_store_name', width:180, editable:true},

                    {label:'Địa chỉ', name:'address', index:'address', width:180},

                    {label:'Tỉnh', name:'virtuemart_state_id', index:'virtuemart_state_id', width:100, formatter:'select', edittype:"select", editoptions:{value:"<?php echo $this->lists['province_json'];?>"}
                        , stype:'select', searchoptions:{value:"<?php echo $this->lists['province_json'];?>"}},
                    {label:'Tỉnh/Thành', name:'province', index:'province', width:180, hidden:true},
                    {name:'Đ.Thoại', name:'phone', index:'phone', width:80, align:'center'},
                    {name:'Email', name:'email', index:'email', width:80, align:'center'},
                    {label:"Tr.Thái", name:'state', index:'state', width:80, align:'center',formatter:'checkbox', editable:true,edittype:"checkbox",editoptions:{value:"1:0", defaultValue: "1"}
                        , stype:'select', searchoptions:{value:":All;1:SD;0:Ngưng"}}
                ],
                pager: jQuery('#vendor_navi'),
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
                height: 'auto',
                width: 1100,
                shrinkToFit: true,
                autoResizing: {
                    compact: true
                },
                onSelectRow: function(ids) {
                    if(ids != null) {

                        var selectedRow = jQuery("#vendor_list").getRowData(ids);

                        $scope.$apply(function () {
                            $scope.vendor = selectedRow;
                            $scope.getVendorConfig();
                            $scope.getVendorSales();
                        });


                    }
                },
                loadComplete: function () {
                    var ids = angular.element("#vendor_list").getDataIDs();
                    for( var i=0;i < ids.length; i++ ){
                        var rowid = ids[i];
                        doituong_id = angular.element("#vendor_list").getCell(rowid, "doituong_code");
                        switch (doituong_id) {
                            case 'CTVTT':
                                angular.element("#vendor_list").jqGrid('setRowData', rowid, false, {color:'blue'});
                                break;
                            case 'CTVCT':
                                angular.element("#vendor_list").jqGrid('setRowData', rowid, false, {color:'red'});
                                break;
                        }

                    }
                    $scope.$apply(function () {
                        $scope.patient = {'PatientID': 0};
                    });
                }
            })
                .navGrid('#vendor_navi',{del:true,search:false, add:false, edit:false, refreshtext:"Tải lại", deltext:"Xóa",
                        beforeRefresh: function(){
                            angular.element("#vendor_list")
                                .setGridParam({
                                    url: 'index.php?option=com_congtacvien&task=dashboard.listvendor'
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
                                jQuery("#vendor_list").trigger("reloadGrid");
                                return [true, res.data, ""];
                            }
                        }
                    }
                );

            angular.element("#vendor_list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false}).jqGrid('bindKeys');


        });

        angular.element(document).ready(function(){

            angular.element("#sales_list").jqGrid({
                url:'index.php?option=com_congtacvien&task=dashboard.listVendorSale',
                datatype: 'json',
                mtype: 'POST',
                styleUI : 'Bootstrap',
                responsive : false,
                colModel :[
                    {label:'#', name:'virtuemart_order_id', index:'virtuemart_order_id',width:40, align:'center', editable:true},
                    {label:'Ngày HĐ', name:'created_on', index:'created_on', width:100, align:'center', formatter:'date', formatoptions: { srcformat: 'Y-m-d', newformat: 'd/m/Y'}},
                    {label:'Mã hóa đơn', name:'order_number', index:'order_number', width:100, editable:true},

                    {label:"Tr.Thái HĐ", name:'invoice_locked', index:'invoice_locked', width:80, align:'center',formatter:'select', editable:true,edittype:"select",editoptions:{value:"1:Khóa;0:Mở", defaultValue: "1"}
                        , stype:'select', searchoptions:{value:":Tất cả;1:Khóa;0:Mở"}},
                    {label:'Giá trị HĐ', name:'order_total', index:'order_total', width:100, align:'right', formatter:'number', formatoptions:{decimalSeparator:",", thousandsSeparator:".",decimalPlaces:0, defaultValue:0}},
                    {label:'Giá bán', name:'order_salesprice', index:'order_salesprice', width:100, align:'right', formatter:'number', formatoptions:{decimalSeparator:",", thousandsSeparator:".",decimalPlaces:0, defaultValue:0}},
                    {label:'Giảm giá', name:'order_billdiscountamount', index:'order_billdiscountamount', width:100, align:'right', formatter:'number', formatoptions:{decimalSeparator:",", thousandsSeparator:".",decimalPlaces:0, defaultValue:0}},
                    {label:'Tiền thanh toán', name:'order_subtotal', index:'order_subtotal', width:100, align:'right', formatter:'number', formatoptions:{decimalSeparator:",", thousandsSeparator:".",decimalPlaces:0, defaultValue:0}},

                ],
                pager: jQuery('#sales_navi'),
                rowNum:50,
                rownumbers:true,
                viewrecords: true,
                scrollPopUp:true,
                scrollLeftOffset: "90%",
                scroll: 1,
                emptyrecords: 'Kéo xuống để xem tiếp dữ liệu',
                sortname: 'created_on',
                sortorder: "desc",
                caption: '',
                height: 'auto',
                width: 1100,
                shrinkToFit: true,
                autoResizing: {
                    compact: true
                },
                footerrow: true, userDataOnFooter: true,
                onSelectRow: function(ids) {
                    if(ids != null) {

                        var selectedRow = jQuery("#sales_list").getRowData(ids);


                    }
                }
            })
                .navGrid('#sales_navi',{del:false,search:false, add:false, edit:false, refreshtext:"Tải lại", deltext:"Xóa",
                        beforeRefresh: function(){
                            angular.element("#sales_list")
                                .setGridParam({
                                    url: 'index.php?option=com_congtacvien&task=dashboard.listvendorsale&vendor_id=' + $scope.vendor.virtuemart_vendor_id
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
                                jQuery("#sale_list").trigger("reloadGrid");
                                return [true, res.data, ""];
                            }
                        }
                    }
                );

            angular.element("#sales_list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false}).jqGrid('bindKeys');


        });

        }]);

    myApp.directive('vendorInfo', function(){
        return {
            restrict: 'AEC',
            templateUrl: '<?php echo JUri::root(true)?>/media/com_congtacvien/templates/vendor_info.html'
        }
    })
    .directive('schemeBonusinfo', function(){
        return {
            restrict: 'AEC',
            templateUrl: '<?php echo JUri::root(true)?>/media/com_congtacvien/templates/scheme_bonusinfo.html'
        }
    });


</script>
