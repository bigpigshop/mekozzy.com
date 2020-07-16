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
<style>
    .sidebar {
        position: relative !important;
        float: left;
    }

    .nav-tabs .nav-item .nav-link, .nav-tabs .nav-item .nav-link:focus, .nav-tabs .nav-item .nav-link {
        color: #1c1c1c !important;
    }

    .nav-tabs .nav-item .nav-link:hover {
        color: #0a0a0a !important;
    }

</style>

<div class="wrapper " style="z-index: 5" ng-controller="MainController">

    <toaster-container
            toaster-options="{'position-class': 'toast-bottom-right', 'progress-bar': true, 'time-out':2000}"></toaster-container>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">CTV Menu</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item ">
                    <a class="nav-link" href="#0">
                        <i class="material-icons" style="font-size: 1rem">dashboard</i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       href="<?php echo JRoute::_('index.php?option=com_congtacvien&task=shop.orders'); ?>">
                        <i class="fa fa-cart-plus"></i> Đơn hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       href="<?php echo JRoute::_('index.php?option=com_congtacvien&task=shop.customers'); ?>">
                        <i class="fa fa-users"></i> Khách hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       href="<?php echo JRoute::_('index.php?option=com_congtacvien&task=shop.khohang'); ?>">
                        <i class="fa fa-database"></i> Kho hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       href="<?php echo JRoute::_('index.php?option=com_congtacvien&task=shop.products'); ?>">
                        <i class="fa fa-product-hunt"></i> Sản phẩm
                    </a>
                </li>
                <li class="nav-item active text-info">
                    <a class="nav-link"
                       href="<?php echo JRoute::_('index.php?option=com_congtacvien&task=shop.config'); ?>">
                        <i class="fa fa-cogs"></i> Cấu hình
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#1">
                        <i class="fa fa-question-circle"></i> Hướng dẫn sử dụng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo CTVHelperRoute::getVendorShop(CTVHelper::getVendorID());?>" >
                        <i class="fa fa-star"></i> Đến gian hàng
                    </a>
                </li>
            </ul>

        </div>
    </nav>


    <div class="content">
        <div class="container-fluid">
            <form ng-submit="saveConfig()" >
                <button type="submit" class="btn btn-small btn-primary pull-right"><i class="fa fa-save"></i> Lưu thông
                    tin
                </button>
                <uib-tabset active="active">
                    <uib-tab index="0">
                        <uib-tab-heading>
                            <i class="fa fa-info-circle"></i> Thông tin gian hàng
                        </uib-tab-heading>
                        <uib-tab-content>
                            <div class="row-fluid">
                                <br>

                                <div class="row-fluid">
                                    <div class="col-12">

                                        <div class="form-group">
                                            <label for="inputVendorName">Tên đại lý</label>
                                            <input ng-model="vendor.vendor_store_name" type="text" class="form-control"
                                                   id="inputVendorName" aria-describedby="vendorNameHelp">
                                            <small id="vendorNameHelp" class="form-text text-muted">Tên đại lý / Cộng
                                                tác viên.</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputVendorCustomTitle">Tên gian hàng tùy chỉnh</label>
                                            <input ng-model="vendor.customtitle" type="text" class="form-control"
                                                   id="inputVendorCustomTitle" aria-describedby="vendorNameHelp">
                                            <small id="vendorNameHelp" class="form-text text-muted">Tên hiển thị trên
                                                gian hàng theo ý chủ cửa hàng. Nếu không đặt sẽ hiện tên đại lý</small>
                                        </div>
                                    </div>

                                </div>

                                <div class="row-fluid">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="inputVendorAddress">Địa chỉ</label>
                                            <input ng-model="vendor.vendor_address" type="text" class="form-control"
                                                   id="inputVendorAddress">
                                        </div>
                                    </div>
                                </div>

                                <div class="row-fluid">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="inputVendorPhone">Điện thoại</label>
                                            <input ng-model="vendor.vendor_phone" type="text" class="form-control"
                                                   id="inputVendorPhone">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="inputVendorEmail">Email</label>
                                            <input ng-model="vendor.vendor_email" type="text" class="form-control"
                                                   id="inputVendorEmail">
                                        </div>
                                    </div>

                                </div>

                                <div class="row-fluid">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="formControlFullDescription">Giới thiệu cửa hàng</label>
                                            <trumbowyg ng-model="vendor.vendor_store_desc"
                                                       options="{btns: [['viewHTML','formatting','strong', 'em', 'del'],['unorderedList', 'orderedList'],['link'],['insertImage'], ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],['removeformat'],['fullscreen']]}">
                                            </trumbowyg>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </uib-tab-content>

                    </uib-tab>

                    <!--<uib-tab index="1">
                        <uib-tab-heading>
                            <i class="far fa-images"></i> Hình ảnh
                        </uib-tab-heading>



                    </uib-tab>
-->
                    <uib-tab index="2">
                        <uib-tab-heading>
                            <i class="fab fa-internet-explorer"></i> SEO
                        </uib-tab-heading>
                        <br/>

                        <div class="row-fluid form-text text-muted">Thông tin head trang chủ cửa hàng dành cho SEO.
                        </div>
                        <div class="clearfix">&nbsp;</div>

                        <div class="row-fluid">

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="inputVendorMetadesc">Meta description</label>
                                    <textarea ng-model="vendor.metadesc" rows="5" class="form-control" style="height: 10em"
                                              id="inputVendorMetadesc"></textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="inputVendorMetakey">Keywords</label>
                                    <textarea ng-model="vendor.metakey" rows="5" class="form-control" style="height: 10em"
                                              id="inputVendorMetakey"></textarea>
                                </div>
                            </div>

                        </div>

                    </uib-tab>


                </uib-tabset>
            </form>
        </div>
    </div>

    <footer class="footer">
        <div class="container-fluid">
            <!-- your content here -->
        </div>
    </footer>
</div>

<script type="text/javascript">

    myApp = angular.module("myApp", ['toaster', 'ui.bootstrap', 'trumbowyg']);

    myApp.controller('MainController', ['$scope', '$http', 'toaster', '$interval', function ($scope, $http, toaster, $interval) {

        $scope.vendor = {};

        $scope.getVendorConfig = function()
        {
            let url = 'index.php?option=com_congtacvien&task=shop.getvendorconfig';
            $http.post(url)
                .then(function(response){
                    if (response.status == 200) {
                        if (response.data.success) {
                            $scope.vendor = response.data.data;
                        } else {
                            toaster.pop("error", response.data.message, "", 0);
                        }
                    } else {
                        toaster.pop("error", response.statusText, "", "");
                    }
                    toaster.clear();
                });
        };

        $scope.getVendorConfig();

        $scope.saveConfig = function()
        {
            console.log($scope.vendor);
            let url = 'index.php?option=com_congtacvien&task=shop.savevendorconfig&<?php echo JSession::getFormToken()?>=1';
            toaster.pop("info", 'Đang lưu dữ liệu...', "", 0);
            $http.post(url, $scope.vendor)
                .then(function(response){
                    if (response.status == 200) {
                        toaster.clear();
                        if (response.data.success) {
                            $scope.vendor = response.data.data;
                            toaster.pop("success", response.data.message);
                        } else {
                            toaster.pop("error", response.data.message, "", 0);
                        }
                    } else {
                        toaster.pop("error", response.statusText, "", "");
                    }
                });
        };


    }]);

    myApp.directive('vendorForm', function () {
        return {
            restrict: 'AEC',
            templateUrl: '<?php echo JUri::root(true)?>/media/com_congtacvien/templates/vendor_form.html'
        }
    });


</script>