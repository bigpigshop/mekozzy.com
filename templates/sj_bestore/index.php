<?php
	/**
	 * @package Helix Ultimate Framework
	 * @author JoomShaper https://www.joomshaper.com
	 * @copyright Copyright (c) 2010 - 2018 JoomShaper
	 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
	 */
	
	defined('_JEXEC') or die();
	
	$doc = JFactory::getDocument();
	$app = JFactory::getApplication();
	
	use Joomla\CMS\Factory;
	
	// Output as HTML5
	$this->setHtml5(true);
	
	$helix_path = JPATH_PLUGINS . '/system/helixultimate/core/helixultimate.php';
	if (file_exists($helix_path)) {
		require_once($helix_path);
		$theme = new helixUltimate;
	} else {
		die('Install and activate <a target="_blank" href="https://www.joomshaper.com/helix">Helix Ultimate Framework</a>.');
	}
	
	//Coming Soon
	if ($this->params->get('comingsoon')) {
		header("Location: " . $this->baseUrl . "?tmpl=comingsoon");
	}
	
	$custom_style = $this->params->get('custom_style');
	$preset = $this->params->get('preset');
	
	if ($custom_style || !$preset) {
		$scssVars = array(
			'preset' => 'default',
			'css' => 'typography',
			'css' => 'style',
			'css' => 'responsive',
			'text_color' => $this->params->get('text_color'),
			'bg_color' => $this->params->get('bg_color'),
			'link_color' => $this->params->get('link_color'),
			'link_hover_color' => $this->params->get('link_hover_color'),
			'header_bg_color' => $this->params->get('header_bg_color'),
			'logo_text_color' => $this->params->get('logo_text_color'),
			'menu_text_color' => $this->params->get('menu_text_color'),
			'menu_text_hover_color' => $this->params->get('menu_text_hover_color'),
			'menu_text_active_color' => $this->params->get('menu_text_active_color'),
			'menu_dropdown_bg_color' => $this->params->get('menu_dropdown_bg_color'),
			'menu_dropdown_text_color' => $this->params->get('menu_dropdown_text_color'),
			'menu_dropdown_text_hover_color' => $this->params->get('menu_dropdown_text_hover_color'),
			'menu_dropdown_text_active_color' => $this->params->get('menu_dropdown_text_active_color'),
			'footer_bg_color' => $this->params->get('footer_bg_color'),
			'footer_text_color' => $this->params->get('footer_text_color'),
			'footer_link_color' => $this->params->get('footer_link_color'),
			'footer_link_hover_color' => $this->params->get('footer_link_hover_color'),
			'topbar_bg_color' => $this->params->get('topbar_bg_color'),
			'topbar_text_color' => $this->params->get('topbar_text_color')
		);
	} else {
		$scssVars = (array)json_decode($this->params->get('preset'));
	}
	
	$scssVars['header_height'] = $this->params->get('header_height', '60px');
	$scssVars['offcanvas_width'] = $this->params->get('offcanvas_width', '300') . 'px';
	
	
	//Body Background Image
	if ($bg_image = $this->params->get('body_bg_image')) {
		$body_style = 'background-image: url(' . JURI::base(true) . '/' . $bg_image . ');';
		$body_style .= 'background-repeat: ' . $this->params->get('body_bg_repeat') . ';';
		$body_style .= 'background-size: ' . $this->params->get('body_bg_size') . ';';
		$body_style .= 'background-attachment: ' . $this->params->get('body_bg_attachment') . ';';
		$body_style .= 'background-position: ' . $this->params->get('body_bg_position') . ';';
		$body_style = 'body.site {' . $body_style . '}';
		$doc->addStyledeclaration($body_style);
	}
	
	//Custom CSS
	if ($custom_css = $this->params->get('custom_css')) {
		$doc->addStyledeclaration($custom_css);
	}
	
	//Custom JS
	if ($custom_js = $this->params->get('custom_js')) {
		$doc->addScriptdeclaration($custom_js);
	}

?>

<!doctype html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-153597825-6"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}

		gtag('js', new Date());

		gtag('config', 'UA-153597825-6');
	</script>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<link rel="canonical" href="<?php echo JUri::getInstance()->toString(); ?>">
	<?php
		
		$theme->head();
		
		$theme->add_css('font-awesome.min.css,owl.carousel.css');
		
		$theme->add_js('jquery.sticky.js,owl.carousel.js, main.js');
		
		$theme->add_scss('master', $scssVars, 'template');
		
		if ($this->direction == 'rtl') {
			$theme->add_scss('rtl', $scssVars, 'rtl');
		}
		
		$theme->add_scss('presets', $scssVars, 'presets/' . $scssVars['preset']);
		$theme->add_css('custom');
		
		
		$document = Factory::getDocument();
		$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/typography.css');
		$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/style.css');
		$document->addStyleSheet('modules/mod_bigpig_category_product_detail/assets/css/responsive.css');
		$document->addScript('templates/sj_bestore/js/countdown.min.js');
		$document->addScript('templates/sj_bestore/js/jquery.magnific-popup.min.js');
		$document->addScript('templates/sj_bestore/js/custom.js');
		$document->addScript('templates/sj_bestore/js/select2.min.js');
		$document->addScript('templates/sj_bestore/js/jquery.counterup.min.js');
		$document->addScript('templates/sj_bestore/js/slick.min.js');
		$document->addScript('templates/sj_bestore/js/wow.min.js');
		$document->addScript('templates/sj_bestore/js/lottie.js');
		$document->addScript('media\jui\js\chosen.jquery.js');
		
		//Before Head
		if ($before_head = $this->params->get('before_head')) {
			echo $before_head . "\n";
		}
	?>
	<!-- Google Tag Manager -->
	<script>(function (w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({
				'gtm.start':
					new Date().getTime(), event: 'gtm.js'
			});
			var f = d.getElementsByTagName(s)[0],
				j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src =
				'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', 'GTM-KWZL9QX');</script>
	<!-- End Google Tag Manager -->
</head>
<body class="<?php echo $theme->bodyClass(); ?>">
<!-- Google Tag Manager (noscript) -->
<noscript>
	<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KWZL9QX"
			height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<div ng-app="myApp">
	<?php if ($this->params->get('preloader')) : ?>
		<div class="sp-preloader">
			<div></div>
		</div>
	<?php endif; ?>

	<div class="body-wrapper">
		<div class="body-innerwrapper">
			<?php echo $theme->getHeaderStyle(); ?>
			<?php $theme->render_layout(); ?>
		</div>
	</div>

	<!-- Off Canvas Menu -->
	<div class="offcanvas-overlay"></div>
	<div class="offcanvas-menu">
		<a href="#" class="close-offcanvas"><span class="fa fa-remove"></span></a>
		<div class="offcanvas-inner">
			<?php if ($this->countModules('offcanvas')) : ?>
				<jdoc:include type="modules" name="offcanvas" style="sp_xhtml"/>
			<?php else: ?>
				<p class="alert alert-warning">
					<?php echo JText::_('HELIX_ULTIMATE_NO_MODULE_OFFCANVAS'); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>

	<!-- Load Facebook SDK for JavaScript -->
	<div id="fb-root"></div>
	<script>
		window.fbAsyncInit = function () {
			FB.init({
				xfbml: true,
				version: 'v7.0'
			});
		};

		(function (d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s);
			js.id = id;
			js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

	<!-- Your Chat Plugin code -->
	<div class="fb-customerchat"
		 attribution=setup_tool
		 page_id="102346797973099"
		 theme_color="#0084ff"
		 logged_in_greeting="Chào mừng bạn đến với Mekozzy"
		 logged_out_greeting="Chào mừng bạn đến với Mekozzy">
	</div>
	
	
	<?php $theme->after_body(); ?>

	<jdoc:include type="modules" name="debug" style="none"/>

	<!-- Go to top -->
	<?php if ($this->params->get('goto_top', 0)) : ?>
		<a href="#" class="sp-scroll-up" aria-label="Scroll Up"><span class="fa fa-chevron-up"
																	  aria-hidden="true"></span></a>
	<?php endif; ?>
</div>
</body>
</html>