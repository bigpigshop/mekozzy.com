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

//var_dump($this->vendor);

?>
<style>

    .container-logo {

    }
    .description {
        margin: 10px 5px;
    }
    .card-img-top{
        width: auto;height: 15rem;
        vertical-align:middle; horiz-align: center;
        margin: 10px auto;
    }
</style>
<div class="container-fluid" ng-controller="MainController">

    <toaster-container toaster-options="{'position-class': 'toast-bottom-right', 'progress-bar': true, 'time-out':2000}"></toaster-container>


    <h1><?php echo $this->vendor->customtitle != "" ? $this->vendor->customtitle : $this->vendor->vendor_store_name; ?></h1>

    <div class="row">
        <div class="col-md-3">
            <div ng-if="vendor.hasBanner" class="container-logo"><img class="img-rounded" height="10rem" src="<?php echo $this->vendor->file_url;?>"/></div>
        </div>
        <div class="col-md-9">
            <div class="description"><?php echo $this->vendor->vendor_store_desc;?></div>
        </div>
    </div>

    <div class="clearfix">&nbsp;</div>

    <div class="row toolbar">
        <div class="col-md-12">
            <form class="form-inline my-2 my-lg-0" ng-submit="searchInShop()">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="inputProductGroup" class="col-sm-4 col-form-label">Nhóm sản phẩm</label>
                        <div class="col-sm-6">
                            <select ng-model="searchData.catid" id="inputProductGroup" ng-change="searchInShop()">
                                <option ng-value="0">--- Tất cả ---</option>
                                <option ng-repeat="category in categories" ng-value="category.id">{{category.text}} <span class="font-italic font-weight-bold">({{category.total}})</span> </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <input ng-model="searchData.searchString" class="form-control mr-sm-2" type="search" placeholder="Tìm trong cửa hàng" aria-label="Tìm trong cửa hàng">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm kiếm</button>
                </div>
            </form>
        </div>
    </div>

    <div class="clearfix">&nbsp;</div>

    <div class="ph-item" ng-if="status.loading">
        <div class="ph-col-12">
            <div class="ph-picture"></div>
            <div class="ph-row">
                <div class="ph-col-6 big"></div>
                <div class="ph-col-4 empty big"></div>
                <div class="ph-col-2 big"></div>
                <div class="ph-col-4"></div>
                <div class="ph-col-8 empty"></div>
                <div class="ph-col-6"></div>
                <div class="ph-col-6 empty"></div>
                <div class="ph-col-12"></div>
            </div>
        </div>
    </div>

    <div class="row product-container" ng-if="!status.loading">
        <div class="col-md-3 col-sm-12 vendor-product" ng-repeat="product in products"></div>
    </div>

</div>

<script>
    myApp = angular.module("myApp", ['toaster', 'ui.bootstrap']);

    myApp.controller('MainController', ['$scope', '$http', 'toaster', '$interval', function ($scope, $http, toaster, $interval) {

        $scope.vendor = {
            id: <?php echo $this->vendor->virtuemart_vendor_id;?>,
            hasBanner: <?php echo ($this->vendor->file_url == "") ? '0' : "1";?>
        };

        $scope.products = [];
        $scope.status = {
            loading: false
        };

        $scope.searchData = {
            catid: 0,
            searchString: ""
        };

        $scope.categories = [];

        $scope.getProducts = function () {
            $scope.status.loading = true;
            let url = 'index.php?option=com_congtacvien&task=shop.getfrontvendorproducts&vendor_id=<?php echo $this->vendor->virtuemart_vendor_id;?>';
            url += "&catid="+ $scope.searchData.catid + "&search="+$scope.searchData.searchString;
            $http.get(url)
                .then(function(response){
                    if (response.status == 200) {
                        if (response.data.success) {
                            $scope.products = response.data.data;
                            $scope.categories = response.data.categories;
                            $scope.status.loading = false;
                        } else {
                            toaster.pop("error", response.data.message, "", 0);
                            $scope.status.loading = false;
                        }
                    } else {
                        toaster.pop("error", response.statusText, "", "");
                    }
                });

        };

        $scope.getProducts();


        $scope.addToCart = function () {

        };

        $scope.searchInShop = function () {

            console.log($scope.searchData);
            $scope.getProducts();

        };

    }]);

    myApp.directive('vendorProduct', function(){
        return {
            restrict: 'AEC',
            templateUrl: '<?php echo JUri::root(true)?>/media/com_congtacvien/templates/vendor_product.html'
        }
    });

</script>