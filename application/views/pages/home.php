<?php foreach ($articles as $ac) : ?>
	<?php if($ac['login'] == 0 || $this->session->userdata('logged_in') === TRUE): ?>
		<div class="article">
      <a href="<?php echo $ac['link']; ?>">
          <img src="<?php echo base_url(array('uploads', $ac['image_path'])); ?>" class="img-responsive img-mainpage" alt="<?php echo $ac['title']; ?>">
        </a>
      <h5>Írta: <?php echo $ac['user_link'] . ', ' . $ac['pub_time']; ?></h5>
			<h2><?php echo $ac['title']; ?></h2>
			<p><?php echo $ac['short_body']; ?></p>
		</div>
	<?php endif; ?>
<?php endforeach; ?>
<?php var_dump($articles[0]); ?>