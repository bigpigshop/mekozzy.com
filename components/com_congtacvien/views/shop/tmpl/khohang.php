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

    .product-small-item {
        margin: 5px 0;
        height: 200px;
    }

    .product-small-item_content {
        height: 100%;
        border: 1px solid #73AD21;
        border-radius: 10px;
    }

    .product-selected {
        border: 2px solid red !important;
    }

    .product-small-item_content:hover {
        border: 2px solid red;
    }

    .product-small-item_content_header {
        background-color: #3c3f41AA;
        color: white;
        font-size: 0.9em;
    }

    .product-small-item_content h2 {
        padding: 5px;
        color: white;
        font-size: 1.1em;
    }

    .product-small-item_content h2 a {
        color: white;
    }

    .product-small-item_content h2 a:hover {
        color: yellow;
    }

    .product-small-item_content > div {
        padding: 5px;
    }

    .product-small-item_content_footer {
        position: absolute;
        bottom: 0;
        text-align: center;
        padding: 0;
    }

    .product-small-item_content_footer button {
        padding: 0.3em !important;
    }

    .product-small-item_content_popup {
        width: 300px;
    }

    .product-small-item_content_price {
        color: white;
        font-weight: bold;
    }
</style>

<div class="wrapper " style="z-index: 5" ng-controller="KhohangController">

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
                <li class="nav-item active text-info">
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
                <li class="nav-item ">
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
                    <a class="nav-link" href="<?php echo CTVHelperRoute::getVendorShop(CTVHelper::getVendorID()); ?>">
                        <i class="fa fa-star"></i> Đến gian hàng
                    </a>
                </li>
            </ul>

        </div>
    </nav>

    <div class="content">

        <div class="container-fluid">
            <div class="nagivation-header">
                <div class="row-fluid">
                    <form class="form-inline" ng-submit="search()">
                        <div class="col-md-3 col-sm-12 form-inline">
                            <input ng-model="searchData.searchString" class="form-control mr-sm-2 col-9" type="search"
                                   placeholder="Tìm trong kho" aria-label="Tìm trong kho hàng">
                            <div class="input-group-append">
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i
                                            class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 form-inline">
                            <div class="input-group-prepend hidden">
                                <label class="input-group-text" for="inputCategorySelect">Nhóm</label>
                            </div>
                            <select ng-model="searchData.catid" id="inputCategorySelect" ng-change="search()">
                                <option value="">-- Tất cả --</option>
                                <?php echo $this->categories; ?>
                            </select>

                        </div>
                        <div class="col-md-3 col-sm-12">
                            <ul uib-pagination total-items="pager.totalItems" ng-model="pager.currentPage" max-size="4"
                                items-per-page="pager.itemsperpage" ng-change="pageChanged()"></ul>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="input-group mb-3 my-2 my-sm-0">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">Limit</label>
                                </div>
                                <select id="inputGroupSelect01" class="input custom-select"
                                        ng-model="pager.itemsperpage" ng-change="pageChanged()">
                                    <option ng-value='10'>10</option>
                                    <option ng-value='20'>20</option>
                                    <option ng-value='30'>30</option>
                                    <option ng-value='50'>50</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-12 my-2 my-sm-2">
                                <span class="float-right pull-right">
                                    <button title="chế độ xem hàng" ng-if="layoutColumns" ng-click="changeLayout(false)"
                                            class="btn btn-small btn-sm"><i class="fa fa-list"></i></button>
                                    <button title="chế độ xem cột" ng-if="!layoutColumns" ng-click="changeLayout(true)"
                                            class="btn btn-small btn-sm"><i class="fa fa-columns"></i></button>
                                </span>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 text-center"><i class="fa fa-eye"></i> {{pager.showItems}}/{{pager.totalItems}}
                        sản phẩm</di>
                    </div>
                </div>

                <div class="clearfix">&nbsp;</div>

                <div class="row-fluid" id="products-container">

                    <div ng-repeat="product in products" class="row-fluid">
                        <div ng-if="layoutColumns" class="product-small-item col-md-3 col-sm-12"></div>
                        <div ng-if="!layoutColumns" class="product-small-item-list"></div>
                    </div>

                </div>

                <div class="clearfix">&nbsp;</div>

                <div class="row-fluid">
                    <div class="nagivation-footer justify-content-center align-items-center">
                        <ul uib-pagination total-items="pager.totalItems" ng-model="pager.currentPage" max-size="4"
                            items-per-page="pager.itemsperpage" ng-change="pageChanged()"></ul>
                    </div>
                </div>


            </div>
        </div>

    </div>

    <script type="text/javascript">

        myApp = angular.module("myApp", ['toaster', 'ui.bootstrap']);

        myApp.controller('KhohangController', ['$scope', '$http', 'toaster', '$interval', function ($scope, $http, toaster, $interval) {

            $scope.layoutColumns = true;
            $scope.searchData = {
                searchString: "",
                catid: "",
                searching: false
            };

            $scope.products = [];
            $scope.pager = {
                totalItems: 0,
                currentPage: 1,
                itemsperpage: 20,
                showItems: 0
            };

            $scope.getProducts = function ($pager) {
                toaster.pop('info', 'Đang lấy dữ liệu...');
                let url = 'index.php?option=com_congtacvien&task=shop.getinventoryproducts';
                $http.post(url, {
                    'pager': $scope.pager,
                    catid: $scope.searchData.catid,
                    search: $scope.searchData.searchString
                })
                    .then(function (response) {
                        if (response.status == 200) {
                            if (response.data.success) {
                                $scope.pager.totalItems = response.data.total;
                                $scope.pager.itemsperpage = response.data.limit;
                                $scope.pager.currentPage = response.data.limitstart;
                                $scope.pager.showItems = response.data.limit * response.data.limitstart;
                                $scope.products = response.data.data;
                            } else {
                                toaster.pop("error", response.data.message, "", 0);
                            }
                        } else {
                            toaster.pop("error", response.statusText, "", "");
                        }
                        toaster.clear();
                    });
            };

            $scope.getProducts($scope.pager, 0);

            $scope.pageChanged = function () {
                $scope.getProducts($scope.pager);
            };

            $scope.addToCart = function (product_id) {
                toaster.pop('info', 'Đang lấy dữ liệu...');
                let url = 'index.php?option=com_congtacvien&task=shop.addProductByVendor&<?php echo JSession::getFormToken();?>=1';
                var data = {
                    'pager': $scope.pager,
                    product_id
                };
                $http.post(url, data)
                    .then(function (response) {
                        toaster.clear();
                        if (response.status == 200) {
                            if (response.data.success) {
                                toaster.pop('success', 'Đã thêm vào danh sách sản phẩm của bạn');
                                $scope.getProducts($scope.pager);
                            } else {
                                toaster.pop("error", response.data.message, "", 0);
                            }
                        } else {
                            toaster.pop("error", response.statusText, "", "");
                        }

                    });
            };

            $scope.removeFromToCart = function (product_id) {
                toaster.pop('info', 'Đang xóa dữ liệu...');
                let url = 'index.php?option=com_congtacvien&task=shop.removeProductByVendor&<?php echo JSession::getFormToken();?>=1';
                var data = {
                    'pager': $scope.pager,
                    product_id
                };
                $http.post(url, data)
                    .then(function (response) {
                        toaster.clear();
                        if (response.status == 200) {
                            if (response.data.success) {
                                toaster.pop('success', 'Xóa sản phẩm khỏi danh sách thành công');
                                $scope.getProducts($scope.pager);
                            } else {
                                toaster.pop("error", response.data.message, "", 0);
                            }
                        } else {
                            toaster.pop("error", response.statusText, "", "");
                        }
                    });
            };

            $scope.changeLayout = function (layout) {
                $scope.layoutColumns = layout;
            };

            $scope.search = function () {
                // reset pager
                $scope.pager.currentPage = 1;
                $scope.getProducts();
            };

        }]);

        myApp.directive('productSmallItem', function () {
            return {
                restrict: 'AEC',
                templateUrl: '<?php echo JUri::root(true)?>/media/com_congtacvien/templates/product_small_item.html'
            }
        }).directive('productSmallItemList', function () {
            return {
                restrict: 'AEC',
                templateUrl: '<?php echo JUri::root(true)?>/media/com_congtacvien/templates/product_small_item_list.html'
            }
        });


    </script>