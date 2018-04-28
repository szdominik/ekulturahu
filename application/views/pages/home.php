<style>@import url('<?php echo base_url('assets/owlcarousel/owl.carousel.css');?>')</style>
<style>@import url('<?php echo base_url('assets/owlcarousel/owl.theme.css');?>')</style>
<div class="owl-carousel">
	<?php foreach ($articles as $ac) : ?>
		<?php if($ac['login'] == 0 || $this->session->userdata('logged_in') === TRUE): ?>
			<div>
				<a href="<?php echo $ac['link']; ?>">
					<img src="<?php echo base_url(array('uploads', $ac['image_path'])); ?>" class="img-responsive img-mainpage" alt="<?php echo $ac['title']; ?>">
				</a>
				<h2><?php echo $ac['title']; ?></h2>
				<h5>Írta: <?php echo $ac['user_link']; ?></h5>
				<p><?php echo substr($ac['body'], 0, strpos($ac['body'], '</p>')); ?></p>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/owlcarousel/owl.carousel.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".owl-carousel").owlCarousel({
			items : 5, //alapértelmezetten öt elem jelenjen meg
		    itemsDesktop : [1000,3], //1000px és 901px között 3 elem
		    itemsDesktopSmall : [900,2], // 2 elem 900px és 601px között
		    itemsTablet: [600,1], //1 elem 600px és 0px között
		    itemsMobile : false, // itemsMobile-ra nincs külön feltétlenünk, örökli a fentit
		});
	});
</script>