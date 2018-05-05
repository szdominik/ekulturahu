<?php if(!$article['login'] || $this->session->userdata('logged_in')): ?>

<div class="article">
  <div class="meta-content">
    <?php
    if ($article['kedv_vasar'] != '' && $article['kedv_vasar'] != '0') {
      echo '<a href="' . $article['kedv_vasar'] . '" class="btn-action">Kedvezményes vásárlás</a>';
    }
    if ($this->session->userdata('logged_in') && 
      ($this->session->userdata('level') > 3 || $this->session->userdata('id') == $article['user_id'])) {
      echo '<a href="' . site_url(array('admin', 'article_edit', $article['id'])) . '" class="btn-action">Szerkesztés</a>';
    }
    if (count($metas) !== 0 || !empty($article['eredeti_cim']) || !empty($article['ar']) || !empty($article['terjedelem']) ||
        !empty($article['forgatokonyviro']) || !empty($article['operator']) || !empty($article['producer'])):
      echo '<h2>Adatok</h2>';
      for ($i = 0; $i < count($metas); ++$i) {
        echo '<h4>' . $metas[$i]['type_name'] . ':</h4>';
        echo '<a href="' . site_url(array('meta', $metas[$i]['type_slug'], $metas[$i]['slug'])) . '">';
          echo $metas[$i]['meta_name'];
        echo '</a>';
        for (;$i+1 < count($metas) && $metas[$i]['type_name'] == $metas[$i+1]['type_name']; ++$i) {
          echo ', ';
          echo '<a href="' . site_url(array('meta', $metas[$i+1]['type_slug'], $metas[$i+1]['slug'])) . '">';
            echo $metas[$i+1]['meta_name'];
          echo '</a>';
        }
      }
      if ($article['eredeti_cim'] != '') {
        echo '<h4>Eredeti cím:</h4>' . $article['eredeti_cim'];
      }
      if ($article['ar'] != '') {
        echo '<h4>Ár:</h4>' . $article['ar'];
      }
      if ($article['terjedelem'] != '') {
        if ($article['category_id'] === '1') {
          echo '<h4>Oldalszám:</h4>';
        } elseif ($article['category_id'] === '3' || $article['category_id'] === '2') {
          echo '<h4>Hossz:</h4>';
        } else {
          echo '<h4>Terjedelem:</h4>';
        }
        echo $article['terjedelem'];
      }
      if ($article['forgatokonyviro'] != '') {
        echo '<h4>Forgatókönyvíró:</h4>' . $article['forgatokonyviro'];
      }
      if ($article['operator'] != '') {
        echo '<h4>Operatőr:</h4>' . $article['operator'];
      }
      if ($article['producer'] != '') {
        echo '<h4>Producer:</h4>' . $article['producer'];
      }
    endif; ?>
  </div>
  <div class="article-content">
      <?php
        echo '<a class="category-label" href="' . site_url($article['cat_slug']) . '">' . $article['cat_name'] . '</a>';
        echo '<a class="category-label" href="' . site_url($article['subcat_slug']) . '">' . $article['subcat_name'] . '</a>';
        echo '<h1>' . $article['title'] . '</h1>';
        echo '<h3>írta: ' . $article['user_link'] . ' | ' . $article['pub_time'] . '</h3>';
      ?>
      <div class="article-body">
      <?php
        if($article['image_path'] != NULL) {
          if($article['image_horizontal'] == 1) {
            echo '<img src="' . base_url(array('uploads', $article['image_path'])) . '" class="img-article-center" alt="Főkép">';
          } else {
            echo '<img src="' . base_url(array('uploads', $article['image_path'])) . '" align="left" alt="Főkép">';
          }
        }
        echo $article['body'];
      ?>
      </div>
  </div>
</div>

<div id="other-articles">
</div>

<script>
	function meta_load(meta_id, meta_name, type_id, meta_link) {
		$.get('<?php echo site_url(array('articles', 'get_other_articles_by_meta_id')); ?>' + '/' + meta_id, function(data) {
			let s = $('#other-articles').html();
			let count = 0;
      const name_link = `<a href='${meta_link}'>${meta_name}</a>`;
      const title = type_id === 1 ? `${name_link} további művei` : `${name_link} sorozat`;
			s += `<h3>${title}</h3>`;
			s += '<div class="other-articles-list">'
        for (let i = 0; i < data.length; ++i) {
          if (data[i].id != <?php echo $article['id']; ?> && data[i].subcat_id != 1 && data[i].subcat_id != 4) {
            s += `<div class="other-articles-box">
                <div class="img-container" align="left">
                  <img src="<?php echo base_url('uploads');?>/${data[i].image_path}">
                </div>
                <a href="${data[i].link}">${data[i].title}</a>
              </div>`
            ++count;
          }
        }
        if(count === 0) {
          s += 'Nincs találat kapcsolódó tartalomra!';
        }
				s += '</div>';
			$('#other-articles').html(s);
      $('#other-articles').addClass('other-articles');
		}, "json");
	}
	
	<?php if(count($metas) !== 0): ?>
		$('#other-articles').html('<h2>Kapcsolódó tartalmak</h2>');
		<?php foreach($metas as $m):
			if($m['type_id'] == 1 || $m['type_id'] == 2): ?>
				meta_load(<?php echo $m['meta_id']; ?>, '<?php echo $m['meta_name']; ?>', <?php echo $m['type_id']; ?>, '<?php echo site_url(array('meta', $m['type_slug'], $m['slug'])); ?>');
			<?php endif;
		endforeach;
	endif; ?>
</script>
<?php endif; ?>
