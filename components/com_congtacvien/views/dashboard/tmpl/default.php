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

    .annual_chart_container {
        height: 200px;
    }

</style>


<div class="wrapper " style="z-index: 5" ng-controller="MainController">
    <toaster-container toaster-options="{'position-class': 'toast-bottom-right', 'progress-bar': true, 'time-out':2000}"></toaster-container>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">CTV Menu</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active text-info">
                    <a class="nav-link" href="#0">
                        <i class="material-icons" style="font-size: 1rem">dashboard</i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
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

            <div class="card card text-white bg-info mb-3">
                <div class="card-header"><strong><i class="fa fa-user"></i> Thông tin Cộng tác viên</strong></div>
                <div class="card-body">
                    <div class="dashboard-vendorinfo"></div>
                </div>
            </div>


            <div class="row-fluid">
                <div class="col-12">
                    <div class="card panel-warning">
                        <div class="card-header"><strong><i class="fa fa-dollar"></i> Lương thưởng</strong></div>
                        <div class="card-body">
                            <input type="month" class="form-control" datepicker-popup ng-model="saleDate" ng-change="getSalary()" datepicker-options="dateOptions" close-text="Close" />
                            <div class="clearfix"></div>
                            <br>
                            <div class="row text-info">
                                <div class="col-3">
                                    Doanh số: {{vendorSalary.sale | number}}
                                </div>
                                <div class="col-3">
                                    Lương cơ bản: {{vendorSalary.luong_coban | number}}
                                </div>
                                <div class="col-3">
                                    Thưởng: {{vendorSalary.thuong | number}}
                                </div>
                                <div class="col-3 text-success font-weight-bold">
                                    Tổng cộng: {{vendorSalary.total | number}}
                                </div>
                            </div>
                            <div class="row" style="height: 20rem">
                                <canvas id="line" class="chart chart-line" chart-dataset-override="datasetOverride" chart-data="month.data"
                                        chart-colors="month.colors" chart-labels="month.labels" chart-series="month.series" chart-options="month.options">
                                </canvas>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix">&nbsp;</div>

            <div class="row-fluid">
                <div class="col-md-8 col-sm-12">
                    <div class="card panel-warning">
                        <div class="card-header"><strong><i class="fa fa-dollar"></i> Doanh số trong năm</strong></div>
                        <div class="card-body">
                            <canvas id="annual_chart" class="chart chart-line" chart-data="annual.data"
                                    chart-labels="annual.labels" chart-series="annual.series"
                                    chart-options="annual.options">
                            </canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card panel-warning">
                        <div class="card-header"><strong><i class="fa fa-dollar"></i> Khung tính lương thưởng</strong></div>
                        <div class="card-body">
                            <div class="">Lương cơ bản: {{vendor.luong_coban | number}}</div>
                            <div class=""  style="margin-top: 0.8rem">Thưởng doanh số:
                                <div class="text-info row-fluid">
                                    <div class="col-6 text-capitalize">Ngưỡng doanh số (VNĐ)</div>
                                    <div class="col-6 text-center">Phần % thưởng</div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row-fluid" ng-repeat="tmp in profileInfo">
                                    <div class="col-6 text-center">{{tmp.nguong | number}}</div>
                                    <div class="col-6 text-center">{{tmp.bonus}}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
    </div>

</div>

<?php //var_dump($this->vendor);?>

<script type="text/javascript">

    myApp = angular.module("myApp", ['toaster', 'ui.bootstrap', 'chart.js']);

    myApp.controller('MainController', ['$scope', '$http', 'toaster', '$interval', function($scope, $http, toaster, $interval) {

        $scope.vendor = <?php echo json_encode($this->vendor) ?>;
        $scope.configData = null;
        $scope.sales = null;
        $scope.profileInfo = null;

        $scope.saleDate = new Date();
        $scope.vendorSalary = null;

        $scope.annual = {
            type: 'line',
            labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            series : ['Doanh số'],
            data: [65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40],
            colors : ['#45b7cd', '#ff6384', '#ff8e72'],
            options : {
                title: {
                    display: true,
                    text: 'Doanh số trong năm hiện tại'
                },
                scales: {
                    yAxes: [
                        {
                            id: 'y-axis-1',
                            type: 'linear',
                            display: true,
                            position: 'left',
                            ticks: {
                                callback: function(value, index, values) {
                                    return number_format(value) + " VNĐ";
                                }
                            }
                        }
                    ]
                },
            }
        };

        $scope.month = {
            labels: ["1"],
            series : ['Series A', 'Series B'],
            colors : ['#45b7cd', '#ff6384', '#ff8e72'],
            data : [
                [0],
                [0]
            ],
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [
                        {
                            id: 'y-axis-1',
                            type: 'linear',
                            display: true,
                            position: 'left',
                            ticks: {
                                callback: function(value, index, values) {
                                    return number_format(value) + " VNĐ";
                                }
                            }
                        },
                        {
                            id: 'y-axis-2',
                            type: 'linear',
                            display: true,
                            position: 'right',
                            ticks: {
                                min: 0,
                                stepSize: 1,
                                callback: function(value, index, values) {
                                    return number_format(value) + " ĐH";
                                }
                            }
                        }
                    ]
                }
            }
        };

        $scope.datasetOverride = [
            {
                yAxisID: 'y-axis-1',
                borderWidth: 3,
                hoverBackgroundColor: "rgba(255,99,132,0.4)",
                hoverBorderColor: "rgba(255,99,132,1)",
            },
            { yAxisID: 'y-axis-2', borderWidth: 1, type: 'bar' }
        ];

        $scope.getAnnualData = function()
        {
            let url = 'index.php?option=com_congtacvien&task=dashboard.getannualdata&<?php echo JSession::getFormToken();?>=1';
            $http.get(url)
                .then(function (response) {
                    if (response.status == 200) {
                        if (response.data.success) {
                            $scope.annual.data = response.data.data.total;
                        } else {
                            toaster.pop("error", response.data.message, "", 0);
                        }
                    } else {
                        toaster.pop("error", response.statusText, "", "");
                    }
                });
        };


        $scope.getSchemeBonus = function () {
            let url = 'index.php?option=com_congtacvien&task=dashboard.getschemebonus&profile_id=' + $scope.vendor.profile_id;
            $http.get(url)
                .then(function (response) {
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

        $scope.getSchemeBonus();

        $scope.getSalary = function () {
            let url = 'index.php?option=com_congtacvien&task=dashboard.getSalaryByVendor&date=' + $scope.saleDate.toISOString().split('T')[0];
            $http.get(url)
                .then(function (response) {
                    if (response.status == 200) {
                        if (response.data.success) {
                            $scope.vendorSalary = response.data.data.salary;
                            $scope.month.labels = response.data.data.labels;
                            $scope.month.series = response.data.data.series;
                            $scope.month.data = response.data.data.data;
                        } else {
                            toaster.pop("error", response.data.message, "", 0);
                        }
                    } else {
                        toaster.pop("error", response.statusText, "", "");
                    }
                });
        };
        $scope.getSalary();
        $scope.getAnnualData();

    }]);

    myApp.directive('dashboardVendorinfo', function(){
        return {
            restrict: 'AEC',
            templateUrl: '<?php echo JUri::root(true)?>/media/com_congtacvien/templates/dashboard_vendorinfo.html'
        }
    })
    .directive('schemeBonusinfo', function(){
        return {
            restrict: 'AEC',
            templateUrl: '<?php echo JUri::root(true)?>/media/com_congtacvien/templates/scheme_bonusinfo.html'
        }
    });



    function number_format(number, decimals, dec_point, thousands_sep) {
// *     example: number_format(1234.56, 2, ',', ' ');
// *     return: '1 234,56'
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

</script>