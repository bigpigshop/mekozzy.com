jQuery(function ($) {
    $(function () {
        $("ul.item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 flex-item>li").slice(0, 8).show();

        $("#loadMore").on('click', function (e) {
            e.preventDefault();
            $("ul.item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 flex-item>li:hidden").slice(0,8).slideDown();
            if ($("ul.item product col-3 col-md-3 col-sm-3 col-xs-3 col-lg-3 flex-item>li:hidden").length == 0) {
                $("#load").fadeOut('slow');
            }
            $('html,body').animate({
                scrollTop: $(this).offset().top
            }, 1500);
        });
    });

});