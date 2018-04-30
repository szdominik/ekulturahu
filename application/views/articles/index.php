<?php
	if (isset($search)) { //ekkor keresésben vagyunk
		$link = site_url(array('search', urlencode($search)));
	}
	elseif (isset($meta)) {  //ekkor címkét jelenítünk meg
		$link = site_url(array('meta', $meta['type_slug'], $meta['slug']));
	}
	elseif (isset($author)) {  //ekkor cikkszerzőhöz tartozó cikkeket jelenítünk meg
		$link = site_url(array('author', urlencode($author)));
	}
	else { //(isset($subcategory)) alkategória van beállítva
		$link = site_url($subcategory['slug']);
	}
?>
<h1>
	<?php if (isset($search)): ?>
		Keresés erre: <?php echo $search; ?>
	<?php elseif (isset($meta)): ?>
		Címke: <?php echo $meta['name']; ?>
	<?php elseif (isset($author)): ?>
		Cikkszerző: <?php echo $author; ?>
	<?php else: ?>
		<a href="<?php echo site_url($subcategory['slug']); ?>"><?php echo $subcategory['name']; ?></a>
	<?php endif; ?>
</h1>

<div class="article-list">
  <?php foreach($articles as $ac):
      if($ac['login'] == 0 || $this->session->userdata('logged_in') === TRUE): ?>
        <div class="article">
          <div class="img-container">
            <a href="<?php echo $ac['link']; ?>">
                <img src="<?php echo base_url(array('uploads', $ac['image_path'])); ?>" class="article-img" alt="<?php echo $ac['title']; ?>">
              </a>
            <a class="category-label" href="<?php echo site_url($ac['subcat_slug']);?>"><?php echo $ac['subcat_name'];?></a>
          </div>
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
    endforeach; ?>
</div>


<nav>
	<div class="article-list-pager">
		<?php
			$db = ceil($cnt / $limit);
			$now = ceil($from / $limit) + 1;
			$start = $now - 3;
			$end = $now + 8;
			$prev = $from - $limit;
      
      $elem = '';
      if($from == 0) {
        $elem = '<a class="btn-pager disabled';
      } else {
        if ($prev < 0) // 0 alá ne menjünk a lapozással
				  $elem = '<a href="' . $link;
        else
          $elem = '<a href="' . $link . '/' . $prev;
        $elem .=  '" class="btn-pager';
      }
			echo $elem . '">Előző</a></button>';
      
      if($start > 1) {
        echo '<span class="pager-link disabled">...</span>';
      }
			for($i = $start; $i <= $end && $i <= $db; ++$i) {
				if($i > 0) {
					if($i === $now)
						echo '<span class="pager-link disabled">' . $i . '</span>';
					else
					  echo '<a class="pager-link" href="' . $link . '/' . (($i - 1) * $limit) . '">' . $i . '</a>';
				}
      }
      if($db > $end) {
        echo '<span class="pager-link disabled">...</span>';
      }
			
			if($from+$limit < $cnt) //az összes elemszámot még nem érjük el
				echo '<a class="btn-pager" href="' . $link . '/' . ($from + $limit) . '">';
			else // az összes elemszám fölé ne menjünk
				echo '<a class="btn-pager disabled href="' . $link . '/' . $from . '">';
			echo 'Következő</a>';
		?>
	</div>
</nav>