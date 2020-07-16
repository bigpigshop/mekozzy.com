(function($){
    function ini_cache(){
        $cache = {
            opcwapperElement: $('#opc-wapper'),
            userShipToElement: $('input[name=userShipTo]'),
            loginPopupElement: $('.LoginPopup'),
            inputTosElement: $('input.required[name=tos]'),
            createAccountElement: $('input[name=checkCreateAcount]'), 
            noticeElement: $('#notice-login').children('p'),
            checkminiumvalue:true
        }
    }
    //______ Obj get HTML cart with ajax ______________________________________________________________________________________________________
    OPCCMSMART = {
        get3form:function(){
            if(!jQuery("#opc-wapper").hasClass('ajax-running')){
               if(showajax) jQuery("#opc-wapper").addClass('ajax-running');
            };
            $.ajax({
                url: '?option=com_virtuemart&view=cart',
                type: 'GET',
                dataType: "json",
                data: "opctask=get3form",
                cache: false,
                success: function(obj) {
                    if(obj.error){

                    }else{
                        $('#opc-shipment').children('.opc-module-content').html(obj.msg.shipment);
                        $('#opc-payment').children('.opc-module-content').html(obj.msg.payment);
                        $('#opc-orderInfo').children('.opc-module-content').html(obj.msg.order);
                        /* check if advance delivery date time running */
                        if (!$('#delivery_date_block').length){
                            $('#opc-delivery').children('.opc-module-content').html(obj.msg.delivery);    
                        }
                        jQuery("#opc-wapper").removeClass('ajax-running');
                        var lengthproduct = jQuery("#opc-orderInfo .order-product ul").length;                    
                        if(lengthproduct==0){
                            location.reload();
                        }
                        getDesignOpc();
                        preventPaymentFormSubmit();
                    }
                    
                }
            });
        },
        selectShipment:function(){
            var form = $('form[name=shipmentForm]');
            if(showajax) jQuery("#opc-wapper").addClass('ajax-running');
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                dataType:'JSON',
                cache: false,
                data:form.serializeFormJSON(),
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
            var form = $('form[name=paymentForm]');
            if(showajax) jQuery("#opc-wapper").addClass('ajax-running');
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                dataType:'JSON',
                cache: false,
                data:form.serializeFormJSON(),
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
            var STcheck = $cache.userShipToElement.is(':checked');
            if(STcheck){
                var form = $('form[name=billToForm]');
                if(showajax) jQuery("#opc-wapper").addClass('ajax-running');
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    cache: false,
                    dataType:'JSON',
                    data:form.serializeFormJSON(),
                    success: function(obj) {
                        OPCCMSMART.get3form();
                    }
                });
            }
        },
        saveShipTo:function(){
            var STcheck = $cache.userShipToElement.is(':checked');
            if(!STcheck){
                var form = $('form[name=shipToForm]');
                if(showajax) jQuery("#opc-wapper").addClass('ajax-running');
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    dataType:'JSON',
                    cache: false,
                    data:form.serializeFormJSON(),
                    success: function(obj) {
                        OPCCMSMART.get3form();
                    }
                });
            }
        },
        useShipTo: function(use){
            if(use){
                use = 1;
            }else{
                use = 0;
            }
            if(showajax) jQuery("#opc-wapper").addClass('ajax-running');
            
            var data = {};
            var STcheck = $cache.userShipToElement.is(':checked');
            if(!STcheck){
                data['shipto'] = $('form[name=shipToForm]').serializeFormJSON();
            }
            data['billto'] = $('form[name=billToForm]').serializeFormJSON();
            data['use_shipto'] = use;
            
            $.ajax({
                url: '?option=com_virtuemart&view=cart&opc_task=useShipto',
                type: 'POST',
                dataType:'JSON',
                data: data,
                cache: false,
                success: function(obj) {
                    OPCCMSMART.get3form();
                }
            });
        },
        setAddress:function(){
            if(showajax) jQuery("#opc-wapper").addClass('ajax-running');
            var data = {};
            var STcheck = $cache.userShipToElement.is(':checked');
            if(!STcheck){
                data['shipto'] = $('form[name=shipToForm]').serializeFormJSON();
            }
            data['billto'] = $('form[name=billToForm]').serializeFormJSON();
            $.ajax({
                url: '?option=com_virtuemart&view=cart&opc_task=setAddress',
                type: 'POST',
                dataType:'JSON',
                data:data,
                async: false,
                cache: false,
                success: function(obj) {
                    jQuery("#opc-wapper").removeClass('ajax-running');
                    if(obj.error!=""){   
                        console.log('1');
                        $cache.checkminiumvalue = false; 
                        alert(obj.error);
                        return false;
                    }else{
                        console.log('2');
                        $cache.checkminiumvalue = true;    
                    }
                    
                    
                }
            });
        },
        updateProduct: function(){
           if(showajax) jQuery("#opc-wapper").addClass('ajax-running');
           var form = $('form[name=priceForm]');
            $.ajax({
                url: '?option=com_virtuemart&view=cart&opc_task=updateProduct',
                type: 'POST',
                dataType:'JSON',
                cache: false,
                data:form.serializeFormJSON(),
                success: function(obj) {
                    if(typeof reload !== 'undefined' && reload){
                        location.reload();
                    } 
                    if(obj == 'true'){
                        $cache.checkminiumvalue = true;  
                        OPCCMSMART.get3form();
                    }else{
                        alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_CANNOT_UPDATE_PRO'));
                        jQuery("#opc-wapper").removeClass('ajax-running');
                    }
                }
            });
        },
        deleteProduct:function(id){
            if(showajax) jQuery("#opc-wapper").addClass('ajax-running');
            var form = $('form[name=priceForm]');
            $.ajax({
                url: '?option=com_virtuemart&view=cart&opc_task=deleteProduct',
                type: 'GET',
                dataType:'JSON',
                cache: false,
                data:'pid='+id,
                success: function(obj) {
                    if(typeof reload !== 'undefined' && reload){
                        location.reload();
                    }
                    if(obj == 'true'){
                        OPCCMSMART.get3form();
                    }else{
                        alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_CANNOT_UPDATE_PRO'));
                        jQuery("#opc-wapper").removeClass('ajax-running');
                    }
                }
            });
        },
        saveCouponCode:function(couponCode){
            if(showajax) jQuery("#opc-wapper").addClass('ajax-running');
            $.ajax({
                url: '?option=com_virtuemart&view=cart&opc_task=saveCouponCode',
                type: 'GET',
                dataType:'JSON',
                cache: false,
                data:'coupon='+couponCode,
                success: function(obj) {
                    if(obj == 'true'){
                        OPCCMSMART.get3form();
                    }else{
                        alert(obj);
                        jQuery("#opc-wapper").removeClass('ajax-running');
                    }
                }
            });
        },
        loginForm:function(){
            var form = $('form[name=logInForm]');
            user = form.find('input[name=username]').val();
            pass = form.find('input[name=password]').val();
            if(user == ''){
                form.find('input[name=username]').focus();
                form.find('input[name=username]').css('border-color','#ff6262');
                return false;
            }else{
                form.find('input[name=username]').css('border-color','#ccc');
            }
            if(pass == ''){
                form.find('input[name=password]').focus();
                form.find('input[name=password]').css('border-color','#ff6262');
                return false;
            }else{
                form.find('input[name=password]').css('border-color','#ccc');
            }
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data:form.serializeFormJSON(),
                cache: false,
                dataType:'JSON',
                error: function(xhr, status){
                    if (status = 'parsererror'){
                        location.reload();
                    }
                },
                success: function(obj) {
                    if(obj.data == 'true'){
                        location.reload();
                    }else if(obj.data == 'notactive'){
                        $cache.noticeElement.html(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_LOGIN_ERROR_ACTIVE'));
                        $cache.noticeElement.fadeIn();
                        getDesignOpc();
                    }else{
                        $cache.noticeElement.html(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_LOGIN_ERROR'));
                        $cache.noticeElement.fadeIn();
                        getDesignOpc();
                    }
                }
            });
        },
        logoutForm:function(){
            var form = $('form[name=logInForm]');
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data:form.serializeFormJSON(),
                cache: false,
                dataType:'JSON',
                error: function(xhr, status){
                    if (status = 'parsererror'){
                        location.reload();
                    }
                },
                success: function(obj) {
                    if(obj.data == 'true'){
                        location.reload();
                    }else{
                        $cache.noticeElement.html(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_LOGOUT_ERROR'));
                    }
                }
            });
        },
        register: function(){
            var form = $('form[name=billToForm]'); 
            var checkRegister = false;           
            $.ajax({
                url: '?option=com_virtuemart&view=cart&opc_task=register',
                type: 'POST',                
                dataType:'JSON',
                data:form.serializeFormJSON(),
                async: false,
                cache: false,
                success: function(obj) {
                    if(obj == 'true'){
                        checkRegister = true;
                    }else{
                        checkRegister = false;
                        alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_RISGISTER_ERROR')+"\r\n"+obj.message);
                        getDesignOpc();
                    }
                }
            });    
            return checkRegister;  
        }
    }
    //_________________________________________________________________________________________________________________________________

    $(document).ready(function(){
        ini_cache();
       // $cache.inputTosElement.prop('checked', false);
        //Created design OPC --------------------------------------------------------------
        checkUseShipTo();
        $(window).resize(function(){
            getDesignOpc();
        });
        preventPaymentFormSubmit();
        //Use shipTo ----------------------------------------------------------------------
        $cache.userShipToElement.click(function(){
            checkUseShipTo();
            if($(this).is(':checked')==false){$('#STsameAsBT').val(0);}else{$('#STsameAsBT').val(1);}
            OPCCMSMART.useShipTo($(this).is(':checked'));
        });
        //Create account
        $cache.createAccountElement.click(function(){
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
            $('<div class="overlayLogin"></div>').appendTo('#opc-wapper');
            var window_width = $(window).width();
            var window_height = $(window).height();
            if(window_width < 320){
                $cache.loginPopupElement.css('left','5px');
            }else{
                $cache.loginPopupElement.css('left',(window_width - 310)/2);
            }
            
            if(window_height < 320){
                $cache.loginPopupElement.css('top','10px');
            }else{
                $cache.loginPopupElement.css('top','50px');
            }
            $cache.loginPopupElement.css('display','block');
        });
        //Close popup Login
        $(document).on( "click", ".close-logIn", function() {
            $('body .overlayLogin').remove();
            $cache.loginPopupElement.css('display','none');
        });
        $(document).on( "click", ".overlayLogin", function() {
            $(this).remove();
            $cache.loginPopupElement.css('display','none');
        });
        //Resize Popup
        $(window).resize(function(){
            var window_width = $(window).width();
            var window_height = $(window).height();
            if(window_width < 320){
                $cache.loginPopupElement.css('left','5px');
            }else{
                $cache.loginPopupElement.css('left',(window_width - 310)/2);
            }
            if(window_height < 350){
                $cache.loginPopupElement.css('top','10px');
            }else{
                $cache.loginPopupElement.css('top','50px');
            }
        });
        //Login ---------------------------------------------------------------------------
        $(document).on( "click", "#submit-login", function() {
            OPCCMSMART.loginForm();
        });
        $(document).on( "keyup", "#logInForm input[name=username]", function(event) {
            if(event.keyCode == 13){
                OPCCMSMART.loginForm();
            }
        });
        $(document).on( "keyup", "#logInForm input[name=password]", function(event) {
            if(event.keyCode == 13){
                OPCCMSMART.loginForm();
            }
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
        //enter card
        $(document).on( "change", "#opc-payment", function() {
            var isOK = true;
            selected = $(this).find('[name="virtuemart_paymentmethod_id"]:checked').parent('.opc-payment'); 
            selected.find('input[type!="hidden"]').each(function(){
                if (!$(this).val())
                    isOK = false;
            });
            ccMonth = selected.find('[name^="cc_expire_month"]');
            if (ccMonth.length){
                ccYearVal = selected.find('[name^="cc_expire_year"]').val();
                ccMonthVal = ccMonth.val();
                curDate = new Date();                
                if ((ccMonthVal <= curDate.getMonth()) && (ccYearVal <= curDate.getFullYear())){
                    isOK = false;
                }
            }
            if (isOK){
                if (jQuery('#wk_stripe_card_info').length){
                    jQuery('#wk_stripe_card_info').clone().appendTo('#opc-submit-button');                    
                    jQuery('#strp-pay-button').click();
                }else{
                    OPCCMSMART.selectPayment();
                }
            }
        });
        //Save BillTo ---------------------------------------------------------------------------
        $(document).on( "change", "input[name=zip]", function() {
            //if($(this).val().match(/^[0-9]+$/)){
                OPCCMSMART.saveBillTo();
            //}else{
                //alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_NOT_NUMBER'));
                //$(this).val('');
            //}
            
        });
        $(document).on( "change", "select[name=virtuemart_country_id]", function() {
            OPCCMSMART.saveBillTo();
        });
        $(document).on( "change", "select[name=virtuemart_state_id]", function() {
            OPCCMSMART.saveBillTo();
        });
        //Save ShipTo ---------------------------------------------------------------------------
        $(document).on( "change", "input[name=shipto_zip]", function() {
            OPCCMSMART.saveShipTo();
        });
        $(document).on( "change", "select[name=shipto_virtuemart_country_id]", function() {
            OPCCMSMART.saveShipTo();
        });
        $(document).on( "change", "select[name=shipto_virtuemart_state_id]", function() {
            OPCCMSMART.saveShipTo();
        });
        $(document).on( "change", "input[name=email]", function() {
            var check  =  validateEmail($(this).val());
            if(!check){
                alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_NOT_EMAIL'))
                $(this).val('');
            }
        });

        
        
        //Checkorder ---------------------------------------------------------------------------
        $(document).on( "click", "span#submit_order_done", function() {
            var th=jQuery(this);
            if (th.hasClass('processing')) return;
            th.addClass('processing');
            if (!checkFormCheckout()){
                th.removeClass('processing');
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
        
        if (typeof opc_country_id !== 'undefined' && opc_country_id > 0) {
            var temp = opc_country_id;
            
            var myCountry_bill = document.getElementById('virtuemart_country_id');
            var myCountry_ship = document.getElementById('shipto_virtuemart_country_id');
            if (myCountry_bill === null){
                myCountry_bill = document.getElementById('virtuemart_country_id_field');
            }            
            if (myCountry_ship === null){
                myCountry_ship = document.getElementById('shipto_virtuemart_country_id_field');
            }            
            if(myCountry_bill && myCountry_ship){
                for(var i, j = 0; i = myCountry_bill.options[j]; j++) {
                  if(i.value == temp) {
                    myCountry_bill.selectedIndex = j;
                    myCountry_ship.selectedIndex = j;
                    jQuery('#virtuemart_country_id_field').trigger('liszt:updated');
                    jQuery('#virtuemart_country_id').trigger('liszt:updated');
                    jQuery('#shipto_virtuemart_country_id_field').trigger('liszt:updated');
                    jQuery('#shipto_virtuemart_country_id').trigger('liszt:updated');
                    setTimeout(function(){
                        OPCCMSMART.saveBillTo();
                    },1000);
                    break;
                  }
                }
            }
        }
        if (typeof opc_country_id !== 'undefined' && opc_city !="") {
            $('#city_field').val(opc_city);           
            $('#shipto_city_field').val(opc_city);           
        }
     
        
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
        setTimeout(function(){ getDesignOpc(); }, 1);
        
    })

})(jQuery)

//____________________________________________________________________________________
//----Function
//Check form submit
function checkTerm(){
    //Check Terms______________________________________________________
    var msg = false;
    if(!$cache.inputTosElement.is(':checked')){
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
    //Check tos
    if($cache.inputTosElement.length){
        if(!$cache.inputTosElement.is(':checked')){
            var top = jQuery('#opc-confirm').offset().top;
            jQuery('html, body').animate({
                scrollTop: top
            }, 1000);
            alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_TERMS_ERROR'));
            return msg;
        }
    }
    //Check create account
    if($cache.createAccountElement.is(':checked')){
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
            }else{
                jQuery(this).css("border-color","#d0d0d0");
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
        }else{
            jQuery(this).css("border-color","#d0d0d0");
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
        }else{
            jQuery(this).css("border-color","#d0d0d0");
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
    }else{
        Country_billTo.parent().find('a.chzn-single').css("border-color","#d0d0d0")
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
    }else{
        State_billTo.parent().find('a.chzn-single').css("border-color","#d0d0d0")
    }
    //Check ShipTo____________________________________________________
    if(!$cache.userShipToElement.is(':checked')){
        var msg_shipto = false;
        jQuery('form[name=shipToForm]').find('input.required').each(function(){
            if(jQuery(this).val() == ''){
                var top = jQuery(this).offset().top;
                jQuery(this).css("border-color","rgb(172, 42, 42)");
                jQuery('html, body').animate({
                    scrollTop: top - 30
                }, 1000);
                alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_BILLTO_SELECT'));
                msg_shipto = true;
                return msg;
            }else{
                jQuery(this).css("border-color","#d0d0d0");
            }
        });
        if(msg_shipto){return msg;}
        var Country_shipTo = jQuery('select.required[name=shipto_virtuemart_country_id]');
        var State_shipTo = jQuery('select[name=shipto_virtuemart_state_id]')
        if(Country_billTo.find('option:selected').val() == ''){
            var top = Country_billTo.offset().top;
            Country_billTo.parent().find('a.chzn-single').css("border-color","rgb(172, 42, 42)");
            jQuery('html, body').animate({
                scrollTop: top - 30
            }, 1000);
            alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_COUNTRY_SELECT'));
            return msg;
        }else{
            Country_billTo.parent().find('a.chzn-single').css("border-color","#d0d0d0");
        }
        if(State_shipTo.find('optgroup').length){
            if(State_shipTo.find('option:selected').val() == ''){
                var top = State_shipTo.offset().top;
                State_shipTo.parent().find('a.chzn-single').css("border-color","rgb(172, 42, 42)");
                jQuery('html, body').animate({
                    scrollTop: top - 30
                }, 1000);
                alert(Joomla.JText._('SYSTEM_ONESTEPCHECKOUT_STATE_SELECT'));
            return msg;
            }
        }else{
            State_shipTo.parent().find('a.chzn-single').css("border-color","#d0d0d0");
        }
    }
    OPCCMSMART.setAddress();
    msg = true;    
    function submitForm(){
        if (jQuery('#wk_stripe_card_info').length){
            jQuery('#wk_stripe_card_info').detach().appendTo('#opc-submit-button');
            jQuery('#strp-pay-button').click();
        }else{
            jQuery('form[name=checkoutForm]').submit();
        }
    }
    if($cache.createAccountElement.is(':checked')){
        var checkRGT = OPCCMSMART.register();
        if(checkRGT){
            submitForm();
        } 
    }else{
        if($cache.checkminiumvalue==true){
            submitForm();
        }

    }
    return msg;
}
//User Shipto
function checkUseShipTo(){
    var notshipTo = $cache.userShipToElement.is(':checked');

    if(notshipTo){
        jQuery('form#shipToForm').find('ul.opc-listShipTo').hide();
    }else{
        jQuery('form#shipToForm').find('ul.opc-listShipTo').show();
    }
    getDesignOpc();
}
//validate Email
function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}
//render Layout OPC
function getDesignOpc(){
    
    var width_parent = jQuery("#opc-wapper").width();
    if(width_parent <= 480){
       jQuery("#opc-wapper").addClass('media-480'); 
    }else{
       jQuery("#opc-wapper").removeClass('media-480'); 
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
    jQuery("#opc-wapper").children('.opc-module').each(function(){     
        var opcChild = jQuery(this);
        var x = parseInt(opcChild.attr('data-x'));
        var y = parseInt(opcChild.attr('data-y'));
        var width_ = parseInt(opcChild.attr('data-width'));
        opcChild.width(width_cell*width_-20);
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
        height_ = opcChild.outerHeight()+20;
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
    jQuery("#opc-wapper").height(Math.max(z3,z4,z5,z6,z7,z8,z9,z10));
    jQuery("#opc-wapper").removeClass('render_');
}

//disable payment form submit
function preventPaymentFormSubmit(){
    var frmPayment = 'form[name=paymentForm]',
        jsSubmit = 'this.form.submit()';

    if (jQuery(frmPayment).length && jQuery(frmPayment).html().indexOf(jsSubmit)){
        jQuery(frmPayment + ' *').each(function(){
            jQuery.each(this.attributes, function() {
                if(this.specified && this.value==jsSubmit) {                                    
                  this.value = '';
                }
            });           
        });
    }
}
/*----------------------------------------------------------------------------------------------------------------------*/
