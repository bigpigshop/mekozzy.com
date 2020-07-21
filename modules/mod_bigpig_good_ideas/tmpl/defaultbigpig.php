<?php
	
	use Joomla\CMS\Factory;
	$document = Factory::getDocument();
	$document->addStyleSheet('modules/mod_bigpig_good_ideas/assets/css/theme.css');
?>
<main role="main">
	<section class="mt-4 mb-5">
		<div class="container-fluid">
			<div class="row">
				<div class="card-columns">
					<?php
						foreach ($list as $item) {
							foreach ($item->img as $ite) {
								if (is_file($ite['src'])) {
									?>
									<a href="<?php echo $item->link ?>" target="_blank">
									<div class="card card-pin good-ideas">
										<img class="card-img"
											 src="<?php echo $ite['src'] ?>"
											 alt="Card image">
										<div class="overlay">
											<h2 class="card-title title"><?php echo $item->title ?></h2>
											<!-- <div class="more">
												<a href="<?php// echo $item->link ?>" target="_blank">
													<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> chi
													tiết bài viết </a>
											</div> -->
											<p class="card-title title"><?php echo $item->introtext ?></p>
										</div>
									
									</div>
									</a>
									<?php
								}
							}
						}
					?>

				</div>
			</div>
		</div>

		<div class=" text-center">
			<button type="submit" id="loadmoregoodideas" class="btn btn-primary">Load More</button>
		</div>
	</section>
 
</main>

<style>
	.card.card-pin.good-ideas {
		display: none;
	}
    .overlay>p{
		display: block;
		width: 150px;
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	#loadmoregoodideas {
		padding: 10px;
		text-align: center;
		box-shadow: 0 1px 1px #ccc;
		transition: all 600ms ease-in-out;
		-webkit-transition: all 600ms ease-in-out;
		-moz-transition: all 600ms ease-in-out;
		-o-transition: all 600ms ease-in-out;
	}

</style>
<script>
	jQuery(function ($) {
		$(function () {
			$(".card.card-pin.good-ideas").slice(0, 16).show();

			$("#loadmoregoodideas").on('click', function (e) {
				e.preventDefault();
				$(".card.card-pin.good-ideas:hidden").slice(0, 16).slideDown();
				if ($(".card.card-pin.good-ideas:hidden").length == 0) {
					$("#load").fadeOut('slow');
				}
				$('html,body').animate({
					scrollTop: $(this).offset().top
				}, 1500);
			});
		});

	});
</script>
