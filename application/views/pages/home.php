<div class="article-list">
  <?php for($j = 0; $j < count($articles); $j += 4):
    for($i = $j; $i < $j + 4; ++$i) :
      $ac = $articles[$i];
      if($ac['login'] == 0 || $this->session->userdata('logged_in') === TRUE): ?>
        <div class="article">
          <a href="<?php echo $ac['link']; ?>">
              <img src="<?php echo base_url(array('uploads', $ac['image_path'])); ?>" class="img-responsive article-img" alt="<?php echo $ac['title']; ?>">
            </a>
          <div class="article-text-section">
            <h4>Írta: <?php echo $ac['user_link'] . ', ' . $ac['pub_time']; ?></h4>
            <h2><?php echo $ac['title']; ?></h2>
            <p><?php echo $ac['short_body']; ?></p>
          </div>
          <div class="article-category">
            <?php for($k = 0; $k < count($ac['meta_category']); ++$k) :
              $mc = $ac['meta_category'][$k]; ?>
              <a href="<?php echo site_url(array('meta', 'kategoria', $mc['slug']));?>" target="_blank"><?php echo $mc['name'];?></a><?php if($k + 1 !== count($ac['meta_category'])) { echo ', '; } ?>
            <?php endfor; ?>
          </div>
        </div>
      <?php endif;
    endfor;
  endfor; ?>
</div>
