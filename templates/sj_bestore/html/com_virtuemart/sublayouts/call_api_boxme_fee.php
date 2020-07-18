<?php
	/**
	 *
	 * Show the Call API BOXME FEE SHIPPING
	 * @author BigPig
	 */
	// Check to ensure this file is included in Joomla!
	defined('_JEXEC') or die('Restricted access');
	$product = $viewData['product'];
	$currency = $viewData['currency'];
?>
<div class="iq-card">
    <div class="iq-card-header d-flex justify-content-between">
        <div class="iq-header-title">
            <h4 class="card-title">Kiểm tra phí giao hàng :</h4>
        </div>
    </div>
    <div class="iq-card-body">
        <p>Bạn ơi hãy NHẬP ĐỊA CHỈ nhận hàng để được dự báo thời gian & chi phí giao hàng một cách chính xác nhất:</p>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalScrollable">
            Kiểm tra phí giao hàng
        </button>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Kiểm tra phí giao hàng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- START API CALL BOXME -->
                        <div class="iq-card">
                            <div id="ulbilltoformproductdetail">
                            </div>
                        </div>
                        <!-- END API CALL BOXME -->
                        <div class="row">
                            <div class="row iq-card" >
                                <div id="infordeliveryproductdetail">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    (function($){
        $(document).ready(function(event) {
            var country = `<select required style="margin-bottom: 10px" id="country" class="title-field">
                      <option value="VN" selected>Viet Nam</option>
                    </select>`;

            $("#ulbilltoformproductdetail").append(country);
            

            $.ajax({
                url: 'https://s.boxme.asia/api/v1/locations/countries/VN/provinces/',
                type: 'GET',
                dataType:'JSON',
                success:function(obj)
                {
                    var output = '';
                    output = `<select id="province" required style="margin-bottom: 10px" class="province title-field">`;

                    $.each(obj.data, function(key, value) {
                        output = output + ('<option value="'+ value.id +'">'+ value.province_name +'</option>');
                    });
                    output = output + `</select>`
                    $("#ulbilltoformproductdetail").append(output);
                    var ward = `<select required style="margin-bottom: 10px" id="ward1" class="title-field">
                      <option value="">-----</option>
                    </select>`;
                    $("#ulbilltoformproductdetail").append(ward);
                    // $("#shipToFormdivadd").append(ward);

                }
            });

            $(document).on('change', '#province', function() {
                var value = $(this).val();
                var text = $(this).find('option:selected').text();
                $("#ward").remove();
                $("#infordeliveryproductdetail").html();
                getWard(value, text, "");
            });

            function getWard(value, text, type) {
                var url = 'https://s.boxme.asia/api/v1/locations/countries/VN/'+value+ '/district/'
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (obj) {
                        $("#ward1").remove();
                        var output = '';
                        output = `<select required id="ward1" style="margin-bottom: 20px" class="ward title-field">`;

                        $.each(obj.data, function(key, value) {
                            output = output + ('<option value="'+ value.id +'">'+ value.district_name +'</option>');
                        });
                        output = output + `</select>`
                        $("#ulbilltoformproductdetail").append(output);
                        // $("#shipToFormdivadd").append(output);
                    }
                })};

            $(document).on('change', '#ward1', function() {
                var ward = $(this).val();
                var province = $('#province option:selected').val();
                var country = "VN";
                var idproduct= "<?php echo $product->virtuemart_product_id; ?>";
                var html = "";

                $.ajax({
                    url: "index.php?option=com_ajax&plugin=GetFeeProductDetail&group=redshop_shipping&format=raw",
                    type: "POST",
                    data: {
                        country,
                        province,
                        ward,
                        idproduct
                    },
                    success: function (result) {
                        let jsonObject = JSON.parse(result);
                        var data = jsonObject.data.couriers;

                        function compare(a, b) {
                            if (a.courier_name < b.courier_name)
                                return -1;
                            if (a.courier_name > b.courier_name)
                                return 1;
                            return 0;
                        }

                        data.sort(compare);
                        var i = 1, html2 = "", html1 = "";
                        $.each(data, function (arrayID, group) {
                            console.log(group);
                            var link_image = group.courier_logo;
                            console.log(link_image);
                            id = 'infordeliveryproductdetail' + i;
                            html += `<div class="col-md-3 col-xs-6" style="    border-radius: 20px;margin: 20px;border: 2px #ffd200 solid;">
                                    <label>
                                    <a href="#" class="thumbnail">
                                        <img alt="` + group.courier_name + ` -- ` + group.service_name + `"
                                             style="padding-top: 10px;height: 80px; width: 100%; display: block;"
                                             src='` + link_image + `'
                                             data-holder-rendered="true">
                                    </a>
                                    <p class="text-center">`
                                + group.min_delivery_time + ` - ` + group.max_delivery_time + ` Ngày</br><strong>`
                                + group.service_name + `</br></strong>` +
                                `Phi giao hang: <strong>` + group.total_fee + `</strong></br>` +
                                `</p>
                                       <input type="radio" id="` + group.service_code + `" name="infordeliveryproductdetail"
                                       data-courier_name="` + group.courier_name + `"
                                       data-service_name="` + group.service_name + `"
                                       data-total_fee="` + group.total_fee + `"
                                       value="` + group.service_code + `"
                                       >
                                     </label>
                                </div>`
                            i++;
                        });

                        html = `<form action="#"><div class="row" id="infordeliveryproductdetail" style="margin-bottom: 100px">` + html + `</div></form>`

                        $("#infordeliveryproductdetail").html(html);
                        $("#infordeliveryproductdetail").css("padding-top", "35px");
                        $("#infordeliveryproductdetail").resize();

                        $("#infordeliveryproductdetail").prop('checked', false);

                        console.log('send data ok ');

                    }
                });

            });

            $(document).on('change', $('input[type=radio][name=infordeliveryproductdetail]'), function() {
                if ($('input[name=infordeliveryproductdetail]:checked').length > 0)
                {
                    var service_id = $('input[name=infordeliveryproductdetail]:checked').val();
                    var courier_name = $('input[name=infordeliveryproductdetail]:checked').attr("data-courier_name");
                    var service_name = $('input[name=infordeliveryproductdetail]:checked').attr("data-service_name");
                    var total_fee = $('input[name=infordeliveryproductdetail]:checked').attr("data-total_fee");
                    console.log(total_fee);
                    $.ajax({
                        url: "index.php?option=com_ajax&plugin=UpdateCart&group=redshop_shipping&format=raw",
                        type: "POST",
                        data: {
                            service_id,
                            total_fee,
                            service_name,
                            courier_name
                        },
                        success: function (result) {

                            let jsonObject = JSON.parse(result);
                            var codehtml = `
                        <div class="order-result">
                            <ul>
                                <li class="result-total"><span class="opc-salesPrice-total">`+ jsonObject.billTotal_net+`</span></li>
                                <li class="result-discount"><span class="opc-discountAmount-total"></span></li>
                                <li class="result-tax"><span class="opc-taxAmount-total"></span></li>
                                <li class="result-title">Product prices result</li>
                            </ul>
                        </div>
                        <div class="order-result">
                            <ul>
                                <li class="result-total"><span class="opc-salesPriceShipment"> `+ total_fee +` </span></li>
                                <li class="result-discount"></li>
                                <li class="result-tax"><span class="opc-shipmentTax"></span></li>
                                <li class="result-title">Shipping Cost</li>
                            </ul>
                        </div>
                        <div class="order-result">
                        <ul>
                            <li class="result-total"><span class="opc-salesPricePayment"></span></li>
                            <li class="result-discount"></li>
                            <li class="result-tax"><span class="opc-paymentTax"></span></li>
                            <li class="result-title">Payment Cost</li>
                        </ul>
                        </div>
                        <div class="order-result">
                            <ul>
                                <li class="result-total total"><span class="opc-billTotal">`+ jsonObject.billTotal+`</span></li>
                                <li class="result-discount total"><span class="opc-billDiscountAmount"></span> </li>
                                <li class="result-tax total"><span class="opc-billTaxAmount">0 ₫</span></li>
                                <li class="result-title total">Total</li>
                            </ul>
                        </div>

                        `;
                            $("#orderinfordefault").html(codehtml);
                            console.log('update cart ok ');

                            var province = $('#province option:selected').val();
                            var first_name = $('#first_name_field').val();
                            var infordeliveryproductdetail = $('input[name="infordeliveryproductdetail"]:checked').val();
                            var ward = $('#ward').val();
                            var phone = $('#phone_2_field').val();
                            var address = $('#address_1_field').val();
                            var country = "VN";

                            $.ajax({
                                url: "index.php?option=com_ajax&plugin=GetData&group=redshop_shipping&format=raw",
                                type: "POST",
                                data: {
                                    first_name,
                                    phone,
                                    address,
                                    country,
                                    province,
                                    ward,
                                    infordeliveryproductdetail
                                },
                                success: function (result) {
                                    console.log('get data for order boxme ok ');

                                }
                            });

                        }
                    })


                }
            });


            
        });
        
    })(jQuery)
</script>