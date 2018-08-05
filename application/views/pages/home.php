<div class="article-list">
  <?php foreach($articles as $ac):
      if($ac['login'] == 0 || $logged_in): ?>
        <div class="article-box">
          <div class="img-container">
            <a href="<?php echo $ac['link']; ?>">
              <img src="<?php echo base_url(array('uploads', $ac['image_path'])); ?>" onerror="this.src = '<?php echo base_url('assets/icons/default.jpg'); ?>';" class="article-img" alt="<?php echo $ac['title']; ?>">
            </a>
            <a class="category-label <?php echo $ac['subcat_slug'];?>" href="<?php echo site_url($ac['subcat_slug']);?>"><?php echo $ac['subcat_name'];?></a>
          </div>
          <div class="article-text-section">
            <h4>Írta: <?php echo $ac['user_link'] . ', ' . $ac['pub_time']; ?></h4>
            <h2><a href="<?php echo $ac['link']; ?>"><?php echo $ac['title']; ?></a></h2>
            <p><?php echo $ac['short_body']; ?></p>
          </div>
          <div class="article-category">
            <?php for($k = 0; $k < count($ac['meta_category']); ++$k) :
              $mc = $ac['meta_category'][$k]; ?>
              <a href="<?php echo site_url(array('meta', 'kategoria', $mc['slug']));?>"><?php echo $mc['name'];?></a><?php if($k + 1 !== count($ac['meta_category'])) { echo ', '; } ?>
            <?php endfor; ?>
          </div>
        </div>
      <?php endif;
    endforeach; ?>
</div>

<nav>
	<div class="article-list-pager">
    <?php
      $link = site_url();
			$db = ceil($cnt / $limit);
			$now = ceil($from / $limit) + 1;
			$start = $now - 3;
			$end = $now + 8;
			$prev = $from - $limit;
      
      $elem = '';
      if($from == 0) {
        $elem = '<a class="btn-pager btn-disabled';
      } else {
        if ($prev < 0) // 0 alá ne menjünk a lapozással
				  $elem = '<a href="' . site_url();
        else
          $elem = '<a href="' . site_url($prev);
        $elem .=  '" class="btn-pager';
      }
			echo $elem . '">Előző</a></button>';
			
			echo '<div>';
      if($start > 1) {
        echo '<span class="pager-link">...</span>';
      }
			for($i = $start; $i <= $end && $i <= $db; ++$i) {
				if($i > 0) {
					if($i === $now)
						echo '<span class="pager-link">' . $i . '</span>';
					else
					  echo '<a class="pager-link" href="' . site_url(($i - 1) * $limit) . '">' . $i . '</a>';
				}
      }
      if($db > $end) {
        echo '<span class="pager-link">...</span>';
      }
			echo '</div>';

			if($from+$limit < $cnt) //az összes elemszámot még nem érjük el
				echo '<a class="btn-pager" href="' . site_url($from + $limit) . '">';
			else // az összes elemszám fölé ne menjünk
				echo '<a class="btn-pager btn-disabled href="' . site_url($from) . '">';
			echo 'Következő</a>';
		?>
	</div>
</nav>