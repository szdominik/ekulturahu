<div class="article-list">
  <?php foreach($articles as $ac):
      if($ac['login'] == 0 || $logged_in): ?>
        <div class="article-box">
          <div class="img-container">
            <a href="<?php echo $ac['link']; ?>"<?php echo $ac['image_horizontal'] == 1 ? ' class="box-img-horizontal"' : ''; ?>>
              <img src="<?php echo base_url(array('uploads', $ac['image_path'])); ?>" onerror="this.src = '<?php echo base_url('assets/icons/default.jpg'); ?>';" class="article-img" alt="<?php echo $ac['title']; ?>">
            </a>
            <a class="category-label <?php echo $ac['subcat_slug'];?>" href="<?php echo site_url($ac['subcat_slug']);?>"><?php echo $ac['subcat_name'];?></a>
          </div>
          <div class="article-text-section">
            <h4><?php echo $ac['user_link'] . ' | ' . $ac['pub_time']; ?></h4>
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
      if(!isset($link)) {
        $link = substr(site_url(), 0, -1);
      }
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
				  $elem = '<a href="' . $link;
        else
          $elem = '<a href="' . $link . '/' . $prev;
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
					  echo '<a class="pager-link" href="' . $link . '/' . (($i - 1) * $limit) . '">' . $i . '</a>';
				}
      }
      if($db > $end) {
        echo '<span class="pager-link">...</span>';
      }
			echo '</div>';

			if($from+$limit < $cnt) //az összes elemszámot még nem érjük el
				echo '<a class="btn-pager" href="' . $link . '/' . ($from + $limit) . '">';
			else // az összes elemszám fölé ne menjünk
				echo '<a class="btn-pager btn-disabled href="' . $link . '/' . $from . '">';
			echo 'Következő</a>';
		?>
	</div>
</nav>