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
//echo json_encode($this->product);
?>
<style>
    .tag-category {
        padding: 0.5em !important;
        border: 1px solid #7f7f7f;
        border-radius: 0.5em;
        margin-right: 1rem  ;
    }

</style>

<div ng-controller="MainController">
    <toaster-container toaster-options="{'position-class': 'toast-bottom-right', 'progress-bar': true}"></toaster-container>


    <form>
        <div class="row-fluid text-right">
            <button class="btn btn-success" ng-click="saveProduct()"><i class="fa fa-save"></i> Lưu thông tin</button>
            <button class="btn btn-warning" ng-click="resetProduct()"><i class="fa fa-recycle"></i> Hoàn tác</button>
        </div>

        <div class="form-group">
            <label for="inputProductName"><h2>Tên sản phẩm</h2></label>
            (<a ng-href="{{product.link}}" target="_blank" title="Xem trang chi tiết">Xem trên gian hàng <i class="fa fa-link"></i></a>)
            <span class="product-sku"><span class="font-weight-bold">Mã sản phẩm:</span> {{product.product_sku}}</span>
            <input type="text" class="form-control" id="inputProductName" ng-model="product.product_name">

        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="inputProductPublished"><h2>Trạng thái</h2></label>
                    <select class="form-control" id="inputProductPublished" ng-model="product.published">
                        <option value="1">Xuất bản</option>
                        <option value="0">Ẩn</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputProductInStock"><h2>Số lượng tồn kho</h2></label>
                    <input type="text" class="form-control" id="inputProductInStock" ng-model="product.product_in_stock">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputProductInStock"><h2>Giá gốc</h2></label>
                    <input type="text" ng-disabled="true" class="form-control" id="inputProductInStock" ng-model="product.prices.product_price">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="inputProductInStock"><h2>Sử dụng giảm giá</h2></label>
                    <select ng-model="product.prices.override">
                        <option ng-value="1">Có</option>
                        <option ng-value="0">Không</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="inputProductInStock"><h2>Giảm giá</h2></label>
                    <input type="text" class="form-control" id="inputProductInStock" ng-model="product.prices.product_override_price">
                </div>
            </div>
        </div>


        <div class="form-group">
            <label for="formControlShortDescription"><h2>Giới thiệu</h2></label>
            <textarea class="form-control" id="formControlShortDescription" rows="5" ng-model="product.product_s_desc"></textarea>
        </div>
        <div class="form-group">
            <label for="formControlFullDescription"><h2>Chi tiết sản phẩm</h2></label>
            <trumbowyg ng-model="product.product_desc" placeholder="Nhập thông tin mô tả sản phẩm"
                options="trumbowygOption"
            ></trumbowyg>
        </div>

        <div class="row-fluid">
            <label for=""><h2>Chuyên mục:</h2></label>
            <span class="tag-category" ng-repeat="category in product.categoryItem">{{category.category_name}}</span>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="formControlMetadesc">Metadesc</label>
                    <textarea class="form-control" id="formControlMetadesc" rows="5" ng-model="product.metadesc"></textarea>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="formControlMetakey">Metakey</label>
                    <textarea class="form-control" id="formControlMetakey" rows="5" ng-model="product.metakey"></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12"><h2>Hình ảnh</h2></div>
            <div class="form-group">
                <div class="button" style="width: 100%; display: inline-block; vertical-align: top;" id="upload_button" >Upload hình ảnh</div>
                <div class="text-muted">Có thể nhiều hình, kích thước 1080x600px. Kích thước file &lt;1.5MB. Các định dạng file: bmp, jpg, jpeg, png, gif</div>
            </div>
        </div>

        <div class="row">
            <div class="col-3" ng-repeat="image in product.images">
                <img class="img-thumbnail img-circle" ng-src="{{image.file_url}}"/>
                <span class="" style="position: relative;bottom: 2em; left: 1em;" ng-if="image.file_url == product.file_url">Hình đại diện</span>
                <button class="btn btn-sm btn-primary" ng-click="deleteImage(image.virtuemart_media_id)" title="Xóa ảnh này"><i class="fa fa-trash"></i></button>
            </div>
        </div>

        <div class="clearfix">&nbsp;</div>
    </form>

<!--    <pre>-->
<!--    {{product | json}}-->
<!--    </pre>-->

</div>

<style type="text/css">
    <!--
    input, select, textarea {
        font-size: 1em !important;
    }
    .form-group > select { height: 2.8em !important;}

    .nav {
        margin-bottom: 10px;
    }

    -->
</style>


<script type="text/javascript">

    var uploader = null;
    var itemId = null;

    myApp = angular.module("myApp", ['toaster', 'ui.bootstrap', 'trumbowyg']);

    myApp.controller('MainController', ['$scope', '$http', 'toaster', '$interval', function($scope, $http, toaster, $interval){

        $scope.product = <?php echo json_encode($this->product);?>;

        $scope.trumbowygOption = "{btns: [['bold', 'italic'], ['unorderedList', 'orderedList']]}";

        $scope.deleteImage = function(media_id, type)
        {
            var result = confirm("Bạn có muốn xóa hình này?");
            if (result) {

                url = "index.php?option=com_congtacvien&task=shop.deleteImage&<?php echo JSession::getFormToken() ?>=1";
                var data = {
                    id: $scope.product.virtuemart_product_id,
                    media_id: media_id
                };
                $http.post(url, data)
                    .then(function(response){
                        if (response.data.success) {
                            $scope.product.images = response.data.data;
                        } else {
                            toaster.pop("error", response.data.message, "", 0);
                        }
                    });
            }
        };

        $scope.saveProduct = function()
        {
            toaster.pop("info", 'Đang lưu dữ liệu...', "", 0);
            // console.log($scope.product);
            let url = "index.php?option=com_congtacvien&task=shop.saveProduct&<?php echo JSession::getFormToken() ?>=1";
            $http.post(url, $scope.product)
                .then(function(response){
                    toaster.clear();
                    if (response.data.success) {

                        toaster.pop("success",response.data.message);
                    } else {
                        toaster.pop("error", response.data.message, "", 0);
                    }
                });

        };

        $scope.resetProduct = function()
        {
            $scope.product = <?php echo json_encode($this->product);?>;


        };

        var triggerUploader = function(){

            var scope = angular.element(jQuery("#main-container")).scope();

            uploader = new qq.FileUploader({
                element: document.getElementById('upload_button'),
                action: '<?php echo JURI::root();?>index.php?option=com_congtacvien&task=shop.uploadimage&<?php echo JSession::getFormToken() ?>=1',
                debug: false,
                params : {
                    'id': <?php echo $this->product->virtuemart_product_id;?>
                },
                allowedExtensions: ['bmp','jpg', 'jpeg', 'png', 'gif'],
                onSubmit: function(id, fileName){

                },
                onProgress: function(id, fileName, loaded, total){},
                onComplete: function(id, fileName, responseJSON){
                    if (responseJSON.success) {
                        $scope.$apply(function(){
                            $scope.product.images = responseJSON.data;
                        })
                    } else {
                        $scope.$apply(function(){
                            toaster.pop('error', responseJSON.message, "", 0);
                        })
                    }
                },
                template: '<div class="qq-uploader">' +
                    '<div class="qq-upload-drop-area"><span>Kéo thả file vào đây</span></div>' +
                    '<div class="btn btn-medium btn-danger qq-upload-button">Upload file</div>' +
                    '<ul class="qq-upload-list"></ul>' +
                    '</div>',
            });


        };

        triggerUploader();


    }]);



</script>
