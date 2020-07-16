jQuery(function ($) {
    $(function () {
        $("ul.profile-img-gallary.d-flex.flex-wrap.p-0.m-0>li").slice(0, 6).show();

        $("#loadMore").on('click', function (e) {
            e.preventDefault();
            $("ul.profile-img-gallary.d-flex.flex-wrap.p-0.m-0>li:hidden").slice(0,6).slideDown();
            if ($("ul.profile-img-gallary.d-flex.flex-wrap.p-0.m-0>li:hidden").length == 0) {
                $("#load").fadeOut('slow');
            }
            $('html,body').animate({
                scrollTop: $(this).offset().top
            }, 1500);
        });
    });

});