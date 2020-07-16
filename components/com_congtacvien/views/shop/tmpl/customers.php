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

    .product-container {
        border: solid 1px #f6f6f6;
        border-radius: 0.8em;
        margin: 5px;
        padding: 1em;
    }
    .product-container:hover {
        background-color: #f5f5f5;
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

    #detail_container {
        height: auto;

    }
</style>

<div class="container-fluid" ng-controller="CustomerController">

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
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo JRoute::_('index.php?option=com_congtacvien&task=shop.orders');?>" >
                        <i class="fa fa-cart-plus"></i> Đơn hàng
                    </a>
                </li>
                <li class="nav-item active text-info">
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
                <li class="nav-item ">
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

    <div class="container">

        <div class="row-fluid" id="category-container">

        </div>
        <div class="row-fluid" id="orders-container">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th class="col-1">Mã KH</th>
                    <th class="col-3">Tên khách hàng</th>
                    <th class="col-4">Địa chỉ</th>
                    <th class="col-2">Điện thoại</th>
                    <th class="col-2">Ngày tham gia</th>
                </tr>
                </thead>
                <tbody ng-repeat="customer in customers">
                <tr ng-click="getCustomerProducts(customer.virtuemart_user_id)">
                    <td class="col-1">{{customer.virtuemart_user_id}}</td>
                    <td class="col-3">{{customer.customer}}</td>
                    <td class="col-4">{{customer.address}}</td>
                    <td class="col-2">{{customer.phone}}</td>
                    <td class="col-2">{{customer.created_on}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="row-fluid" id="detail_container">
            <h2>Danh sách sản phẩm</h2>
            <div class="row product-container" ng-repeat="product in products">
                <div class="col-md-2"><a ng-href="{{product.product.link}}" target="_blank">
                        <img class="img-rounded" src="{{product.product.imageUrl}}" /></a></div>
                <div class="col-md-8">
                    <div class="row-fluid">
                        <div class="col-4">Tên sản phẩm</div>
                        <div class="col-8"><a ng-href="{{product.product.link}}" target="_blank"><h2>{{product.product.product_name}}</h2></a></div>
                    </div>
                    <div class="row-fluid">
                        <div class="col-4">#</div>
                        <div class="col-8">{{product.virtuemart_order_id}}</div>
                    </div>
                    <div class="row-fluid">
                        <div class="col-4">#Order</div>
                        <div class="col-8">{{product.order_number}}</div>
                    </div>
                    <div class="row-fluid">
                        <div class="col-4">Ngày mua</div>
                        <div class="col-8">{{product.created_on}}</div>
                    </div>
                    <div class="row-fluid">
                        <div class="col-4">SKU</div>
                        <div class="col-8">{{product.product.product_sku}}</div>
                    </div>

                    <div class="row-fluid">
                        <div class="col-4">Giá</div>
                        <div class="col-8">{{product.product.prices.product_price | number}}</div>
                    </div>
                    <div class="row-fluid">
                        <div class="col-4">SL</div>
                        <div class="col-8">{{product.product.amount | number}}</div>
                    </div>
                </div>

            </div>

        </div>
    </div>


</div>

<script type="text/javascript">

    myApp = angular.module("myApp", ['toaster', 'ui.bootstrap']);

    myApp.controller('CustomerController', ['$scope', '$http', 'toaster', '$interval', function($scope, $http, toaster, $interval){

        $scope.customers = [];
        $scope.products = [];
        $scope.pager = {
            totalItems: 0,
            currentPage: 1,
            itemsperpage: 20
        };

        $scope.getOrders = function($pager, catid) {
            toaster.pop('info', 'Đang lấy dữ liệu...');
            let url = 'index.php?option=com_congtacvien&task=shop.getcustomers';
            $http.post(url, {'pager':$scope.pager, catid})
                .then(function(response){
                    if (response.status == 200) {
                        if (response.data.success) {
                            $scope.pager.totalItems = response.data.total;
                            $scope.pager.itemsperpage = response.data.limit;
                            $scope.pager.currentPage = response.data.limitstart;
                            $scope.customers = response.data.data;
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

        $scope.getCustomerProducts = function (customer_id) {
            toaster.pop('info', 'Đang lấy dữ liệu...');
            let url = 'index.php?option=com_congtacvien&task=shop.getcustomerproducts&customer_id='+customer_id;
            $http.get(url)
                .then(function(response){
                    if (response.status == 200) {
                        if (response.data.success) {
                            $scope.products = response.data.data;
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