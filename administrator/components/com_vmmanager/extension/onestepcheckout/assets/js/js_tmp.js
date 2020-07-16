(function($){
    function init_cache(){
       $cache = {
            opcWapper:$('#opc-wapper'),
			shipmentContent: $('#opc-shipment').children('.opc-module-content'),
            paymantContent: $('#opc-payment').children('.opc-module-content'),
            orderInfo: $('#opc-orderInfo').children('.opc-module-content'),
            formShipment: $('form[name=shipmentForm]'),
            formPayment:$('form[name=paymentForm]'),
            formBillTo:$('form[name=billToForm]'),
            useShipToCheckbox: $('input[name=userShipTo]'),
            shipToForm: $('form[name=shipToForm]'),
            priceForm:$('form[name=priceForm]'),
            logInForm: $('form[name=logInForm]'),
            noticeLogin:$('#notice-login').children('p'),
            checkTos:$('input[name=tos]')            
	    }
        
    }
    //______ Obj get HTML cart with ajax ______________________________________________________________________________________________________
    var OPCCMSMART = {
        get3form:function(){
            if(!$cache.opcWapper.hasClass('ajax-running')){
               if(showajax) $cache.opcWapper.addClass('ajax-running');
            };
            $.ajax({
                url: 'index.php?option=com_virtuemart&view=cart',
                type: 'GET',
                dataType: "json",
                data: "opctask=get3form",
                success: function(obj) {
                    if(obj.error){

                    }else{
                        $cache.shipmentContent.html(obj.msg.shipment);
                        $cache.paymantContent.html(obj.msg.payment);
                        $cache.orderInfo.html(obj.msg.order);
                        $cache.opcWapper.removeClass('ajax-running');
                        getDesignOpc();
                    }
                }
            });
        },
        selectShipment:function(){
            if(showajax) $cache.opcWapper.addClass('ajax-running');
            $.ajax({
                url: $cache.formShipment.attr('action'),
                type: 'POST',
                dataType:'JSON',
                data:$cache.formShipment.serializeFormJSON(),
                success: function(obj) {
                    if(obj){
                        OPCCMSMART.get3form();
                    }else{
                        alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_SETSHIPMENT_ERROR'));
                    }
                }
            });
        },
        selectPayment:function(){
            if(showajax) $cache.opcWapper.addClass('ajax-running');
            $.ajax({
                url: $cache.formPayment.attr('action'),
                type: 'POST',
                dataType:'JSON',
                data:$cache.formPayment.serializeFormJSON(),
                success: function(obj) {
                    if(obj){
                        alert(obj);
                    }else{
                        OPCCMSMART.get3form();
                    }
                }
            });
        },
        saveBillTo:function(){
            var STcheck = $cache.useShipToCheckbox.is(':checked');
            if(STcheck){
                if(showajax) $cache.opcWapper.addClass('ajax-running');
                $.ajax({
                    url: $cache.formBillTo.attr('action'),
                    type: 'POST',
                    dataType:'JSON',
                    data:$cache.formBillTo.serializeFormJSON(),
                    success: function(obj) {
                        OPCCMSMART.get3form();
                    }
                });
            }
        },
        saveShipTo:function(){
            var STcheck = $cache.useShipToCheckbox.is(':checked');
            if(!STcheck){
                if(showajax) $cache.opcWapper.addClass('ajax-running');
                $.ajax({
                    url: $cache.shipToForm.attr('action'),
                    type: 'POST',
                    dataType:'JSON',
                    data:$cache.shipToForm.serializeFormJSON(),
                    success: function(obj) {
                        OPCCMSMART.get3form();
                    }
                });
            }
        },
        useShipTo: function(use){
            if(showajax) $cache.opcWapper.addClass('ajax-running');
            $.ajax({
                url: 'index.php?option=com_virtuemart&view=cart&opc_task=useShipto',
                type: 'GET',
                dataType:'JSON',
                data:"shipto = " + use,
                success: function(obj) {
                    OPCCMSMART.get3form();
                }
            });
        },
        setAddress:function(){
            if(showajax) $cache.opcWapper.addClass('ajax-running');
            var data = {};
            var STcheck = $cache.useShipToCheckbox.is(':checked');
            if(!STcheck){
                data['shipto'] = $cache.shipToForm.serializeFormJSON();
            }
            data['billto'] = $cache.formBillTo.serializeFormJSON();
            $.ajax({
                url: 'index.php?option=com_virtuemart&view=cart&opc_task=setAddress',
                type: 'POST',
                data:data,
                success: function(obj) {
                    $cache.opcWapper.removeClass('ajax-running');
                }
            });
        },
        updateProduct: function(){
           if(showajax) $cache.opcWapper.addClass('ajax-running');
            $.ajax({
                url: 'index.php?option=com_virtuemart&view=cart&opc_task=updateProduct',
                type: 'POST',
                dataType:'JSON',
                data:$cache.priceForm.serializeFormJSON(),
                success: function(obj) {
                    if(obj == 'true'){
                        OPCCMSMART.get3form();
                    }else{
                        alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_CANNOT_UPDATE_PRO'));
                        $cache.opcWapper.removeClass('ajax-running');
                    }
                }
            });
        },
        deleteProduct:function(id){
            if(showajax) $cache.opcWapper.addClass('ajax-running');
            $.ajax({
                url: 'index.php?option=com_virtuemart&view=cart&opc_task=deleteProduct',
                type: 'GET',
                dataType:'JSON',
                data:'pid='+id,
                success: function(obj) {
                    if(obj == 'true'){
                        OPCCMSMART.get3form();
                    }else{
                        alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_CANNOT_UPDATE_PRO'));
                        $cache.opcWapper.removeClass('ajax-running');
                    }
                }
            });
        },
        saveCouponCode:function(couponCode){
            if(showajax) $cache.opcWapper.addClass('ajax-running');
            $.ajax({
                url: 'index.php?option=com_virtuemart&view=cart&opc_task=saveCouponCode',
                type: 'GET',
                dataType:'JSON',
                data:'coupon='+couponCode,
                success: function(obj) {
                    if(obj == 'true'){
                        OPCCMSMART.get3form();
                    }else{
                        alert(obj);
                        $cache.opcWapper.removeClass('ajax-running');
                    }
                }
            });
        },
        loginForm:function(){
            $.ajax({
                url: $cache.logInForm.attr('action'),
                type: 'POST',
                data:$cache.logInForm.serializeFormJSON(),
                success: function(obj) {
                    if(obj.data == 'true'){
                        location.reload();
                    }else{
                        $cache.noticeLogin.html(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_LOGIN_ERROR'));
                        $cache.noticeLogin.fadeIn();
                    }
                }
            });
        },
        logoutForm:function(){
            $.ajax({
                url: $cache.logInForm.attr('action'),
                type: 'POST',
                data:$cache.logInForm.serializeFormJSON(),
                success: function(obj) {
                    if(obj.data == 'true'){
                        location.reload();
                    }else{
                        $cache.noticeLogin.html(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_LOGOUT_ERROR'));
                    }
                }
            });
        },
        register: function(){
            var checkRegister = false;           
            $.ajax({
                url: 'index.php?option=com_virtuemart&view=cart&opc_task=register',
                type: 'POST',
                data:$cache.formBillTo.serializeFormJSON(),
                async: false,
                success: function(obj) {
                    if(obj == 'true'){
                        checkRegister = true;
                    }else{
                        checkRegister = false;
                        alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_RISGISTER_ERROR'))
                    }
                }
            });    
            return checkRegister;  
        }
    }
    //_________________________________________________________________________________________________________________________________

    $(document).ready(function(){
        init_cache();
        $cache.checkTos.prop('checked', false);
        //Created design OPC --------------------------------------------------------------
        checkUseShipTo();
        $(window).resize(function(){
            getDesignOpc();
        });
        //Use shipTo ----------------------------------------------------------------------
        $cache.useShipToCheckbox.click(function(){
            checkUseShipTo();
            OPCCMSMART.useShipTo($(this).is(':checked'));
        });
        //Create account
        $('#billToForm').find('input[name="checkCreateAcount"]').click(function(){
            var notcreate = jQuery(this).is(':checked');
            if(notcreate){
                jQuery('form#billToForm').find('div.user-info').show();
            }else{
                jQuery('form#billToForm').find('div.user-info').hide();
            }
            getDesignOpc();
        });
        //Popup logIn ---------------------------------------------------------------------------
        $(document).on( "click", ".logIn-popup", function() {
            $('<div class="overlayLogin"></div>').appendTo('body');
            var window_width = $(window).width();
            var window_height = $(window).height();
            if(window_width < 320){
                $('.LoginPopup').css('left','5px');
            }else{
                $('.LoginPopup').css('left',(window_width - 310)/2);
            }
            
            if(window_height < 320){
                $('.LoginPopup').css('top','10px');
            }else{
                $('.LoginPopup').css('top','50px');
            }
            $('.LoginPopup').css('display','block');
        });
        //Close popup Login
        $(document).on( "click", ".close-logIn", function() {
            $('body .overlayLogin').remove();
            $('.LoginPopup').css('display','none');
        });
        $(document).on( "click", ".overlayLogin", function() {
            $(this).remove();
            $('.LoginPopup').css('display','none');
        });
        //Resize Popup
        $(window).resize(function(){
            var window_width = $(window).width();
            var window_height = $(window).height();
            if(window_width < 320){
                $('.LoginPopup').css('left','5px');
            }else{
                $('.LoginPopup').css('left',(window_width - 310)/2);
            }
            if(window_height < 350){
                $('.LoginPopup').css('top','10px');
            }else{
                $('.LoginPopup').css('top','50px');
            }
        });
        //Login ---------------------------------------------------------------------------
        $(document).on( "click", "#submit-login", function() {
            OPCCMSMART.loginForm();
        });
        //Logout ---------------------------------------------------------------------------
        $(document).on( "click", "#submit-logout", function() {
            OPCCMSMART.logoutForm();
        });      
        
        //Set Shipment ---------------------------------------------------------------------------
        $(document).on( "change", "input[name=virtuemart_shipmentmethod_id]", function() {
            OPCCMSMART.selectShipment();
        });
        //Set Payment ---------------------------------------------------------------------------
        $(document).on( "change", "input[name=virtuemart_paymentmethod_id]", function() {
            OPCCMSMART.selectPayment();
        });
        //Save BillTo ---------------------------------------------------------------------------
        $(document).on( "change", "input[name=zip]", function() {
            OPCCMSMART.saveBillTo();
        });
        $(document).on( "change", "select[name=virtuemart_country_id]", function() {
            OPCCMSMART.saveBillTo();
        });
        //Save ShipTo ---------------------------------------------------------------------------
        $(document).on( "change", "input[name=shipto_zip]", function() {
            OPCCMSMART.saveShipTo();
        });
        $(document).on( "change", "select[name=shipto_virtuemart_country_id]", function() {
            OPCCMSMART.saveShipTo();
        });
        //Checkorder ---------------------------------------------------------------------------
        $(document).on( "click", "input[name=tos]", function() {
            if($(this).is(':checked')){
                var status = checkFormCheckout();
                if(!status){
                    $(this).prop('checked', false);
                }else{
                    OPCCMSMART.setAddress();
                }
            }
        });
        //check terms---------------------------------------------------------------------------------
        $(document).on( "click", "span#submit_order_done", function() {
            
            var status = checkTerm();
            if(status){
                if(jQuery('input[name=checkCreateAcount]').is(':checked')){
                    var checkRGT = OPCCMSMART.register();
                    if(checkRGT){
                        $('form[name=checkoutForm]').submit();
                    } 
                }else{
                    $('form[name=checkoutForm]').submit();
                }
                
            }
        });
        //change quantily ---------------------------------------------------------------------------------
        $(document).on( "change", "input.quantity_product", function() {
            var val = $(this).val();
            if(isNaN(val) || parseInt(val) == 0){
                $(this).val(1);
            }
        });
        //Update Product --------------------------------------------------------------------
        $(document).on( "click", "span.update-quantity", function() {
            OPCCMSMART.updateProduct();
        });
        $(document).on( "click", "span.delete-product-cart", function() {
            var id = $(this).attr('data-pid');
            OPCCMSMART.deleteProduct(id);
        });
        //save CouponCode --------------------------------------------------------------------
        $(document).on( "click", "span#addCouponCode", function() {
            var code = $('input[name=coupon_code]').val();
            OPCCMSMART.saveCouponCode(code);
        });
        /*--------------------------------------------------------------------------------------------------------------*/
        //Creat Event get serializeFormJSON
        $.fn.serializeFormJSON = function () {
            var o = {};
            var a = this.serializeArray();
            $.each(a, function () {
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };
    })

})(jQuery)

//____________________________________________________________________________________
//----Function
//Check form submit
function checkTerm(){
    //Check Terms______________________________________________________
    var msg = false;
    if(!jQuery('input[name=tos]').is(':checked')){
        var top = jQuery('#opc-confirm').offset().top;
        jQuery('html, body').animate({
            scrollTop: top
        }, 1000);
        alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_TERMS_ERROR'));
        return msg;
    }
    return true;
}

//Check Terms
function checkFormCheckout(){
    var msg = false;
    //Check create account
    if(jQuery('input[name=checkCreateAcount]').is(':checked')){
        var msg_account = false;
        jQuery('.user-info').find('input').each(function(){
            if(jQuery(this).val() == ''){
                var top = jQuery(this).offset().top;
                jQuery(this).css("border-color","rgb(172, 42, 42)")
                jQuery('html, body').animate({
                    scrollTop: top - 30
                }, 1000);
                alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_ACCOUNT_SELECT'));
                msg_account = true;
                return msg;
            }
        });
        if(msg_account){return msg;}
    }
    //Check Shipment____________________________________________________
    if(!jQuery('input[name=virtuemart_shipmentmethod_id]').is(':checked')){
        var top = jQuery('#opc-shipment').offset().top;
        jQuery('html, body').animate({
            scrollTop: top
        }, 1000);
        alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_SHIPMENT_SELECT'));
        return msg;
    }
    //Check Payment____________________________________________________
    if(!jQuery('input[name=virtuemart_paymentmethod_id]').is(':checked')){
        var top = jQuery('#opc-payment').offset().top;
        jQuery('html, body').animate({
            scrollTop: top
        }, 1000);
        alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_PAYMENT_SELECT'));
        return msg;
    }
    //Check Delivery____________________________________________________
    var msg_deli = false;
    jQuery('form[name=deliveryForm]').find('input.required').each(function(){
        if(jQuery(this).val() == ''){
            var top = jQuery('#opc-delivery').offset().top;
            jQuery(this).css("border-color","rgb(172, 42, 42)")
            jQuery('html, body').animate({
                scrollTop: top
            }, 1000);
            alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_DELIVERY_SELECT'));
            msg_deli = true;
            return msg;
        }
    });
    if(msg_deli){return msg;}

    //Check BillTo____________________________________________________
    var msg_billto = false;
    jQuery('form[name=billToForm]').find('input.required').each(function(){
        if(jQuery(this).val() == ''){
            var top = jQuery(this).offset().top;
            jQuery(this).css("border-color","rgb(172, 42, 42)")
            jQuery('html, body').animate({
                scrollTop: top - 30
            }, 1000);
            alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_BILLTO_SELECT'));
            msg_billto = true;
            return msg;
        }
    });
    if(msg_billto){return msg;}
    var Country_billTo = jQuery('select.required[name=virtuemart_country_id]');
    var State_billTo = jQuery('select[name=virtuemart_state_id]')
    if(Country_billTo.find('option:selected').val() == ''){
        var top = Country_billTo.offset().top;
        Country_billTo.parent().find('a.chzn-single').css("border-color","rgb(172, 42, 42)")
        jQuery('html, body').animate({
            scrollTop: top - 30
        }, 1000);
        alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_COUNTRY_SELECT'));
        return msg;
    }
    if(State_billTo.find('optgroup').length){
        if(State_billTo.find('option:selected').val() == ''){
            var top = State_billTo.offset().top;
            State_billTo.parent().find('a.chzn-single').css("border-color","rgb(172, 42, 42)")
            jQuery('html, body').animate({
                scrollTop: top - 30
            }, 1000);
            alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_STATE_SELECT'));
        return msg;
        }
    }
    //Check ShipTo____________________________________________________
    if(!jQuery('input[name=userShipTo]').is(':checked')){
        var msg_shipto = false;
        jQuery('form[name=shipToForm]').find('input.required').each(function(){
            if(jQuery(this).val() == ''){
                var top = jQuery(this).offset().top;
                jQuery(this).css("border-color","rgb(172, 42, 42)")
                jQuery('html, body').animate({
                    scrollTop: top - 30
                }, 1000);
                alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_BILLTO_SELECT'));
                msg_shipto = true;
                return msg;
            }
        });
        if(msg_shipto){return msg;}
        var Country_shipTo = jQuery('select.required[name=shipto_virtuemart_country_id]');
        var State_shipTo = jQuery('select[name=shipto_virtuemart_state_id]')
        if(Country_billTo.find('option:selected').val() == ''){
            var top = Country_billTo.offset().top;
            Country_billTo.parent().find('a.chzn-single').css("border-color","rgb(172, 42, 42)")
            jQuery('html, body').animate({
                scrollTop: top - 30
            }, 1000);
            alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_COUNTRY_SELECT'));
            return msg;
        }
        if(State_shipTo.find('optgroup').length){
            if(State_shipTo.find('option:selected').val() == ''){
                var top = State_shipTo.offset().top;
                State_shipTo.parent().find('a.chzn-single').css("border-color","rgb(172, 42, 42)")
                jQuery('html, body').animate({
                    scrollTop: top - 30
                }, 1000);
                alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_STATE_SELECT'));
            return msg;
            }
        }
    }
    msg = true;
    return msg;
}
//User Shipto
function checkUseShipTo(){
    var notshipTo = jQuery('form#shipToForm').find('input[name="userShipTo"]').is(':checked');

    if(notshipTo){
        jQuery('form#shipToForm').find('ul.opc-listShipTo').hide();
    }else{
        jQuery('form#shipToForm').find('ul.opc-listShipTo').show();
    }
    getDesignOpc();
}
//render Layout OPC
function getDesignOpc(){
    var width_parent = jQuery('#opc-wapper').width();
    if(width_parent <= 480){
       jQuery('#opc-wapper').addClass('media-480'); 
    }else{
        jQuery('#opc-wapper').removeClass('media-480'); 
    }
    var width_cell = parseInt(width_parent)/12;
    var height_ = 0;
    var z3 = 0;
    var z4 = 0;
    var z5 = 0;
    var z6 = 0;
    var z7 = 0;
    var z8 = 0;
    var z9 = 0;
    var z10 = 0;
    jQuery('#opc-wapper').children('.opc-module').each(function(){     
        var opcChild = jQuery(this);
        var x = parseInt(opcChild.attr('data-x'));
        var y = parseInt(opcChild.attr('data-y'));
        var width_ = parseInt(opcChild.attr('data-width'));
        opcChild.width(width_cell*width_-10);
        if(opcChild.width() < 319){
            opcChild.addClass('opc-media-319'); 
            opcChild.removeClass('opc-media-400');
            opcChild.removeClass('opc-media-480');
            opcChild.removeClass('opc-media-600');
            opcChild.removeClass('opc-media-768');
        }else if(opcChild.width() < 400){
            opcChild.addClass('opc-media-400'); 
            opcChild.removeClass('opc-media-319');
            opcChild.removeClass('opc-media-480');
            opcChild.removeClass('opc-media-600');
            opcChild.removeClass('opc-media-768');
        }else if(opcChild.width() < 480){
            opcChild.addClass('opc-media-480'); 
            opcChild.removeClass('opc-media-319');
            opcChild.removeClass('opc-media-400');
            opcChild.removeClass('opc-media-600');
            opcChild.removeClass('opc-media-768');
        }else if(opcChild.width() < 600){
            opcChild.addClass('opc-media-600');
            opcChild.removeClass('opc-media-319');
            opcChild.removeClass('opc-media-400');
            opcChild.removeClass('opc-media-480');
            opcChild.removeClass('opc-media-768');
        }else if(opcChild.width() < 768){
            opcChild.addClass('opc-media-768');
            opcChild.removeClass('opc-media-319');
            opcChild.removeClass('opc-media-400');
            opcChild.removeClass('opc-media-480');
            opcChild.removeClass('opc-media-600');
        } else{
            opcChild.removeClass('opc-media-319');
            opcChild.removeClass('opc-media-400');
            opcChild.removeClass('opc-media-480');
            opcChild.removeClass('opc-media-600');
            opcChild.removeClass('opc-media-768');
        }    
        //------------------------------------------------------------------------
        height_ = opcChild.outerHeight();
        if(y==0){
            opcChild.css('top','0');
        }

        opcChild.addClass('opc-width-'+width_);
        
        switch(x){
            case 0:
                opcChild.css('left','0');
                switch(width_){
                    case 3:
                        opcChild.css('top',z3);
                        z3 = z3+height_;
                        break;
                    case 4:
                        opcChild.css('top',Math.max(z3,z4));
                        z3 = z4 = Math.max(z3,z4) + height_;
                        break;
                    case 5:
                        opcChild.css('top',Math.max(z3,z4,z5));
                        z3 = z4 = z5 = Math.max(z3,z4,z5) + height_;
                        break;
                    case 6:
                        opcChild.css('top',Math.max(z3,z4,z5,z6));
                        z3 = z4 = z5 = z6 = Math.max(z3,z4,z5,z6) + height_;
                        break;
                    case 7:
                        opcChild.css('top',Math.max(z3,z4,z5,z6,z7));
                        z3 = z4 = z5 = z6 = z7 = Math.max(z3,z4,z5,z6,z7) + height_;
                        break;
                    case 8:
                        opcChild.css('top',Math.max(z3,z4,z5,z6,z7,z8));
                        z3 = z4 = z5 = z6 = z7 = z8 = Math.max(z3,z4,z5,z6,z7,z8) + height_;
                        break;
                    case 9:
                        opcChild.css('top',Math.max(z3,z4,z5,z6,z7,z8,z9));
                        z3 = z4 = z5 = z6 = z7 = z8 = z9 = Math.max(z3,z4,z5,z6,z7,z8,z9) + height_;
                        break;
                    case 10:
                    case 11:
                    case 12:
                        opcChild.css('top',Math.max(z3,z4,z5,z6,z7,z8,z9,z10));
                        z3 = z4 = z5 = z6 = z7 = z8 = z9 = z10 = Math.max(z3,z4,z5,z6,z7,z8,z9,z10) + height_;
                        break;
                }
                break;
            case 1:
                opcChild.css('left',width_cell*1);
                switch(width_){
                    case 3:
                        opcChild.css('top',Math.max(z3,z4));
                        z3 = z4 = Math.max(z3,z4) + height_;
                        break;
                    case 4:
                        opcChild.css('top',Math.max(z3,z4,z5));
                        z3 = z4 = z5 = Math.max(z3,z4,z5) + height_;
                        break;
                    case 5:
                        opcChild.css('top',Math.max(z3,z4,z5,z6));
                        break;
                    case 6:
                        opcChild.css('top',Math.max(z3,z4,z5,z6,z7));
                        z3 = z4 = z5 = z6 = z7 = Math.max(z3,z4,z5,z6,z7) + height_;
                        break;
                    case 7:
                        opcChild.css('top',Math.max(z3,z4,z5,z6,z7,z8));
                        z3 = z4 = z5 = z6 = z7 = z8 = Math.max(z3,z4,z5,z6,z7,z8) + height_;
                        break;
                    case 8:
                        opcChild.css('top',Math.max(z3,z4,z5,z6,z7,z8,z9));
                        z3 = z4 = z5 = z6 = z7 = z8 = z9 = Math.max(z3,z4,z5,z6,z7,z8,z9) + height_;
                        break;
                    case 9:
                    case 10:
                    case 11:
                        opcChild.css('top',Math.max(z3,z4,z5,z6,z7,z8,z9,z10));
                        z3 = z4 = z5 = z6 = z7 = z8 = z9 = z10 = Math.max(z3,z4,z5,z6,z7,z8,z9,z10) + height_;
                        break;
                }
                break;
            case 2:
                opcChild.css('left',width_cell*2);
                switch(width_){
                    case 3:
                        opcChild.css('top',Math.max(z3,z4,z5));
                        z3 = z4 = z5 = Math.max(z3,z4,z5) + height_;
                        break;
                    case 4:
                        opcChild.css('top',Math.max(z3,z4,z5,z6));
                        z3 = z4 = z5 = z6 = Math.max(z3,z4,z5,z6) + height_;
                        break;
                    case 5:
                        opcChild.css('top',Math.max(z3,z4,z5,z6,z7));
                        z3 = z4 = z5 = z6 = z7 = Math.max(z3,z4,z5,z6,z7) + height_;
                        break;
                    case 6:
                        opcChild.css('top',Math.max(z3,z4,z5,z6,z7,z8));
                        z3 = z4 = z5 = z6 = z7 = z8 = Math.max(z3,z4,z5,z6,z7,z8) + height_;
                        break;
                    case 7:
                        opcChild.css('top',Math.max(z3,z4,z5,z6,z7,z8,z9));
                        z3 = z4 = z5 = z6 = z7 = z8 = z9 = Math.max(z3,z4,z5,z6,z7,z8,z9) + height_;
                        break;
                    case 8:
                    case 9:
                    case 10:
                        opcChild.css('top',Math.max(z3,z4,z5,z6,z7,z8,z9,z10));
                        z3 = z4 = z5 = z6 = z7 = z8 = z9 = z10 = Math.max(z3,z4,z5,z6,z7,z8,z9,z10) + height_;
                        break;
                }
                break;
            case 3:
                opcChild.css('left',width_cell*3);
                switch(width_){
                    case 3:
                        opcChild.css('top',Math.max(z4,z5,z6));
                        z4 = z5 = z6 = Math.max(z4,z5,z6) + height_;
                        break;
                    case 4:
                        jopcChild.css('top',Math.max(z4,z5,z6,z7));
                        z4 = z5 = z6 = z7 = Math.max(z4,z5,z6,z7) + height_;
                        break;
                    case 5:
                        opcChild.css('top',Math.max(z4,z5,z6,z7,z8));
                        z4 = z5 = z6 = z7 = z8 = Math.max(z4,z5,z6,z7,z8) + height_;
                        break;
                    case 6:
                        opcChild.css('top',Math.max(z4,z5,z6,z7,z8,z9));
                        z4 = z5 = z6 = z7 = z8 = z9 = Math.max(z4,z5,z6,z7,z8,z9) + height_;
                        break;
                    case 7:
                    case 8:
                    case 9:
                        opcChild.css('top',Math.max(z4,z5,z6,z7,z8,z9,z10));
                        z4 = z5 = z6 = z7 = z8 = z9 = z10 = Math.max(z4,z5,z6,z7,z8,z9,z10) + height_;
                        break;
                }
                break;
            case 4:
                opcChild.css('left',width_cell*4);
                switch(width_){
                    case 3:
                        opcChild.css('top',Math.max(z5,z6,z7));
                        z5 = z6 = z7 = Math.max(z5,z6,z7) + height_;
                        break;
                    case 4:
                        opcChild.css('top',Math.max(z5,z6,z7,z8));
                        z5 = z6 = z7 = z8 = Math.max(z5,z6,z7,z8) + height_;
                        break
                    case 5:
                        opcChild.css('top',Math.max(z5,z6,z7,z8,z9));
                        z5 = z6 = z7 = z8 = z9 = Math.max(z5,z6,z7,z8,z9) + height_;
                        break
                    case 6:
                    case 7:
                    case 8:
                        opcChild.css('top',Math.max(z5,z6,z7,z8,z9,z10));
                        z5 = z6 = z7 = z8 = z9 = z10 = Math.max(z5,z6,z7,z8,z9,z10) + height_;
                        break
                }
                break;
            case 5:
                opcChild.css('left',width_cell*5);
                switch(width_){
                    case 3:
                        opcChild.css('top',Math.max(z6,z7,z8));
                        z6 = z7 = z8 = Math.max(z6,z7,z8) + height_;
                        break;
                    case 4:
                        opcChild.css('top',Math.max(z6,z7,z8,z9));
                        z6 = z7 = z8 = z9 = Math.max(z6,z7,z8,z9) + height_;
                        break;
                    case 5:
                    case 6:
                    case 7:
                        opcChild.css('top',Math.max(z6,z7,z8,z9,z10));
                        z6 = z7 = z8 = z9 = z10 = Math.max(z6,z7,z8,z9,z10) + height_;
                        break;
                }
                break;
            case 6:
                opcChild.css('left',width_cell*6);
                switch(width_){
                    case 3:
                        opcChild.css('top',Math.max(z7,z8,z9));
                        z7 = z8 = z9 = Math.max(z7,z8,z9) + height_;
                        break;
                    case 4:
                    case 5:
                    case 6:
                        opcChild.css('top',Math.max(z7,z8,z9,z10));
                        z7 = z8 = z9 = z10 = Math.max(z7,z8,z9,z10) + height_;
                        break;
                }
                break;
            case 7:
                opcChild.css('left',width_cell*7);
                switch(width_){
                    case 3:
                        opcChild.css('top',Math.max(z8,z9,z10));
                        z8 = z9 = z10 = Math.max(z8,z9,z10) + height_;
                        break;
                    case 4:
                    case 5:
                        opcChild.css('top',Math.max(z8,z9,z10));
                        z8 = z9 = z10 = Math.max(z8,z9,z10) + height_;
                        break;
                }
                break;
            case 8:
                opcChild.css('left',width_cell*8);
                switch(width_){
                    case 3:
                    case 4:
                        opcChild.css('top',Math.max(z9,z10));
                        z9 = z10 = Math.max(z9,z10) + height_;
                        break;
                }
                break;
            case 9:
                opcChild.css('left',width_cell*9);
                switch(width_){
                    case 3:
                        opcChild.css('top',z10);
                        z10 = z10 + height_;
                        break;
                }
                break;

        }
    });
    jQuery('#opc-wapper').height(Math.max(z3,z4,z5,z6,z7,z8,z9,z10));
}
/*----------------------------------------------------------------------------------------------------------------------*/
