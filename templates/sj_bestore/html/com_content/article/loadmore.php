<?php
	defined('_JEXEC') or die;
	
		use Joomla\CMS\Factory;
		$document = Factory::getDocument();
		$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/typography.css');
		$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/style.css');
		$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/responsive.css');
//		$document->addScript(  'modules/mod_bigpig_category_product_detail/assets/js/bigpig.js');
?>
<!-- Wrapper Start -->
<div class="wrapper">
    <div class="iq-comingsoon pt-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-sm-8 text-center">
                    <div class="iq-comingsoon-info">
                        <a href="https://mekozzy.com">
                            <img style="border-radius: 25px;" src="images/62fe965948e8b2b6ebf9.jpg" class="img-fluid w-25" alt="">
                        </a>
                        <h2 class="mt-4 mb-1">Stay tunned, we're launching very soon</h2>
                        <p>We are working very hard to give you the best experience possible!</p>
                        
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-4">
                        <div class="form-group text-center">
                            <button type="submit" onclick="location.href = 'https://mekozzy.com';" class="btn btn-primary">Home Page</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper END -->