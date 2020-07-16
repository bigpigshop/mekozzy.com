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
                            <div class="iq-card-body">
                                <div class="form-group">
                                    <label>Thành Phố</label>
                                    <select class="form-control form-control-lg">
                                        <option selected="">Chọn</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Quận/ Huyện </label>
                                    <select class="form-control form-control-lg">
                                        <option selected="">Chọn</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Phường / Xã </label>
                                    <select class="form-control form-control-lg">
                                        <option selected="">Chọn</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- END API CALL BOXME -->
                        <div class="row">
                            <div class="row iq-card" >
                                <div class="col-md-3 col-xs-6" style="    border-radius: 20px;margin: 20px;border: 2px #ffd200 solid;">
                                    <label>
                                        <a href="#" class="thumbnail">
                                            <img alt="Viettel Post -- Express SCOD" style="padding-top: 10px;height: 80px; width: 100%; display: block;" src="https://oms.boxme.asia/assets/images/courier/viettelpost.svg" data-holder-rendered="true">
                                        </a>
                                        <p class="text-center">3 - 3.5 Ngày<br><strong>Express SCOD<br></strong>Phi giao hang: <strong>43600</strong><br></p>
                                        <input type="radio" id="VTP_SCOD" name="infordelivery" data-courier_name="Viettel Post" data-service_name="Express SCOD" data-total_fee="43600" value="VTP_SCOD">
                                    </label>
                                </div>
                                <div class="col-md-3 col-xs-6" style="    border-radius: 20px;margin: 20px;border: 2px #ffd200 solid;">
                                    <label>
                                        <a href="#" class="thumbnail">
                                            <img alt="Viettel Post -- Express SCOD" style="padding-top: 10px;height: 80px; width: 100%; display: block;" src="https://oms.boxme.asia/assets/images/courier/viettelpost.svg" data-holder-rendered="true">
                                        </a>
                                        <p class="text-center">3 - 3.5 Ngày<br><strong>Express SCOD<br></strong>Phi giao hang: <strong>43600</strong><br></p>
                                        <input type="radio" id="VTP_SCOD" name="infordelivery" data-courier_name="Viettel Post" data-service_name="Express SCOD" data-total_fee="43600" value="VTP_SCOD">
                                    </label>
                                </div>
                                <div class="col-md-3 col-xs-6" style="    border-radius: 20px;margin: 20px;border: 2px #ffd200 solid;">
                                    <label>
                                        <a href="#" class="thumbnail">
                                            <img alt="Best Express -- Economy service" style="padding-top: 10px;height: 80px; width: 100%; display: block;" src="https://oms.boxme.asia/assets/images/courier/best-express.svg" data-holder-rendered="true">
                                        </a>
                                        <p class="text-center">1 - 2 Ngày<br><strong>Economy service<br></strong>Phi giao hang: <strong>40000</strong><br></p>
                                        <input type="radio" id="VNC_CPTK" name="infordelivery" data-courier_name="Best Express" data-service_name="Economy service" data-total_fee="40000" value="VNC_CPTK">
                                    </label>
                                </div>
                                <div class="col-md-3 col-xs-6" style="    border-radius: 20px;margin: 20px;border: 2px #ffd200 solid;">
                                    <label>
                                        <a href="#" class="thumbnail">
                                            <img alt="Best Express -- Economy service" style="padding-top: 10px;height: 80px; width: 100%; display: block;" src="https://oms.boxme.asia/assets/images/courier/best-express.svg" data-holder-rendered="true">
                                        </a>
                                        <p class="text-center">1 - 2 Ngày<br><strong>Economy service<br></strong>Phi giao hang: <strong>40000</strong><br></p>
                                        <input type="radio" id="VNC_CPTK" name="infordelivery" data-courier_name="Best Express" data-service_name="Economy service" data-total_fee="40000" value="VNC_CPTK">
                                    </label>
                                </div>
                                <div class="col-md-3 col-xs-6" style="    border-radius: 20px;margin: 20px;border: 2px #ffd200 solid;">
                                    <label>
                                        <a href="#" class="thumbnail">
                                            <img alt="Viettel Post -- Express service" style="padding-top: 10px;height: 80px; width: 100%; display: block;" src="https://oms.boxme.asia/assets/images/courier/viettelpost.svg" data-holder-rendered="true">
                                        </a>
                                        <p class="text-center">3 - 4.5 Ngày<br><strong>Express service<br></strong>Phi giao hang: <strong>35600</strong><br></p>
                                        <input type="radio" id="VTP_VCN" name="infordelivery" data-courier_name="Viettel Post" data-service_name="Express service" data-total_fee="35600" value="VTP_VCN">
                                    </label>
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
