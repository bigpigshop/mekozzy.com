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
        margin: 5px 0; height: 200px;
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

<div class="wrapper " style="z-index: 5" ng-controller="OrderController">

    <toaster-container toaster-options="{'position-class': 'toast-bottom-right', 'progress-bar': true, 'time-out':2000}"></toaster-container>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">CTV Menu</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item ">
                    <a class="nav-link" href="#0">
                        <i class="material-icons" style="font-size: 1rem">dashboard</i> Dashboard
                    </a>
                </li>
                <li class="nav-item active text-info">
                    <a class="nav-link" href="<?php echo JRoute::_('index.php?option=com_congtacvien&task=shop.orders');?>" >
                        <i class="fa fa-cart-plus"></i> Đơn hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo JRoute::_('index.php?option=com_congtacvien&task=shop.customers');?>" >
                        <i class="fa fa-users"></i> Khách hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo JRoute::_('index.php?option=com_congtacvien&task=shop.khohang');?>" >
                        <i class="fa fa-database"></i> Kho hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo JRoute::_('index.php?option=com_congtacvien&task=shop.products');?>" >
                        <i class="fa fa-product-hunt"></i> Sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo JRoute::_('index.php?option=com_congtacvien&task=shop.config');?>" >
                        <i class="fa fa-cogs"></i> Cấu hình
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#1" >
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
                <div class="row-fluid" id="category-container">

                </div>
                <div class="row-fluid" id="orders-container">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th class="col-1">#</th>
                            <th class="col-2">Ngày hóa đơn</th>
                            <th class="col-2">Mã ĐH</th>
                            <th class="col-3">Tên khách hàng</th>
                            <th class="col-2">Tổng tiền</th>
                            <th class="col-2">Trạng thái ĐH</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="order in orders" ng-click="getVendorOrderDetail(order.virtuemart_order_id)">
                            <td class="col-1">{{order.virtuemart_order_id}}</td>
                            <td class="col-2">{{order.created_on}}</td>
                            <td class="col-2">{{order.order_number}}</td>
                            <td class="col-3">{{order.customer}}</td>

                            <td class="col-2">{{order.order_total | number}}<br>{{order.order_subtotal | number}}</td>
                            <td class="col-2">{{order.order_status_name}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row-fluid" id="detail-container">
                    <h2>Danh sách sản phẩm đơn hàng</h2>
                    <div ng-repeat="product in orderitems">
                        <div class="col-3"><img class="img-thumbnail width-20" src="{{product.imageUrl}}"></div>
                        <div class="col-9 text-info">
                            <h2><a href="{{product.link}}" target="_blank">{{product.product_name}} <i class="fa fa-external-link text-info"></i></a></h2>
                            <div>Mã SP: {{product.product_sku}}</div>
                            <div class="category">Nhóm SP: {{product.category_name}}</div>
                            <div class="product-small-item_content_price">
                                Giá: <span>{{product.priceFormatted}}</span>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

</div>

<script type="text/javascript">

    myApp = angular.module("myApp", ['toaster', 'ui.bootstrap']);

    myApp.controller('OrderController', ['$scope', '$http', 'toaster', '$interval', function($scope, $http, toaster, $interval){

        $scope.orders = [];
        $scope.orderitems = '';
        $scope.pager = {
            totalItems: 0,
            currentPage: 1,
            itemsperpage: 20
        };

        $scope.getOrders = function($pager, catid) {
            toaster.pop('info', 'Đang lấy dữ liệu...');
            let url = 'index.php?option=com_congtacvien&task=shop.getvendororders';
            $http.post(url, {'pager':$scope.pager, catid})
                .then(function(response){
                    if (response.status == 200) {
                        if (response.data.success) {
                            $scope.pager.totalItems = response.data.total;
                            $scope.pager.itemsperpage = response.data.limit;
                            $scope.pager.currentPage = response.data.limitstart;
                            $scope.orders = response.data.data;
                        } else {
                            toaster.pop("error", response.data.message, "", 0);
                        }
                    } else {
                        toaster.pop("error", response.statusText, "", "");
                    }
                    toaster.clear();
                });
        };

        $scope.getOrders($scope.pager, 0);

        $scope.pageChanged = function() {
            $scope.getOrders($scope.pager, 0);
        };

        $scope.getVendorOrderDetail = function (virtuemart_order_id) {
            console.log(virtuemart_order_id);
            toaster.pop('info', 'Đang lấy dữ liệu...');
            let url = 'index.php?option=com_congtacvien&task=shop.getvendororderdetail&order_id='+ virtuemart_order_id;
            $http.get(url)
                .then(function(response){
                    if (response.status == 200) {
                        if (response.data.success) {
                            $scope.orderitems = response.data.data;
                        } else {
                            toaster.pop("error", response.data.message, "", 0);
                        }
                    } else {
                        toaster.pop("error", response.statusText, "", "");
                    }
                    toaster.clear();
                });
        }

    }]);

    myApp.directive('vendorOrder', function(){
        return {
            restrict: 'AEC',
            templateUrl: '<?php echo JUri::root(true)?>/media/com_congtacvien/templates/vendor_order.html'
        }
    });


</script>