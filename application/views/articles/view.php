<?php if(!$article['login'] || $logged_in):
$DEFAULT_IMAGE_PATH = base_url('assets/icons/default.jpg'); ?>

<div class="article">
  <div class="meta-content">
    <?php
    if ($article['kedv_vasar'] != '' && $article['kedv_vasar'] != '0') {
      echo '<a href="' . $article['kedv_vasar'] . '" class="btn-action">Kedvezményes vásárlás</a>';
    }
    if ($logged_in && 
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
    <div class="article-social">
      <iframe
        src="https://www.facebook.com/plugins/like.php?href=<?php echo urlencode(current_url());?>&width=151&layout=box_count&action=like&size=small&show_faces=false&share=true&height=65&appId"
        width="151" height="65"
        style="border:none;overflow:hidden" scrolling="no" frameborder="0"
        allowTransparency="true" allow="encrypted-media"></iframe>
    </div>
  </div>
  <div class="article-content">
      <?php
        echo '<a class="category-label '. $article['cat_slug'] .'" href="' . site_url($article['cat_slug']) . '">' . $article['cat_name'] . '</a>';
        echo '<a class="category-label '. $article['subcat_slug'] .'" href="' . site_url($article['subcat_slug']) . '">' . $article['subcat_name'] . '</a>';
        echo '<h1>' . $article['title'] . '</h1>';
        echo '<h3>Írta: ' . $article['user_link'] . ' | ' . $article['pub_time'] . '</h3>';
      ?>
      <div class="article-body">
      <?php
        if($article['image_path'] != NULL) {
          if($article['image_horizontal'] == 1) {
            echo '<img src="' . base_url(array('uploads', $article['image_path'])) . '" onerror="this.src = `'. $DEFAULT_IMAGE_PATH .'`;" class="img-article-horizontal" alt="Főkép">';
          } else {
            echo '<img src="' . base_url(array('uploads', $article['image_path'])) . '" onerror="this.src = `'. $DEFAULT_IMAGE_PATH .'`;" class="img-article" alt="Főkép">';
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
  let metas = {};
  const other_article_box = article => `
    <div class="other-articles-box">
      <a href="${article.link}">
        <div class="img-container" align="left">
          <img src="<?php echo base_url('uploads');?>/${article.image_path}" onerror="this.src = '<?php echo $DEFAULT_IMAGE_PATH;?>';">
        </div>
        ${article.title}
      </a>
    </div>
  `;
  const arrow_back_text = '<span>Előző</span>';
  const arrow_next_text = '<span>Következő</span>';
  
  const meta_load = (meta_id, meta_name, type_id, meta_link) => {
    $.get(`<?php echo site_url(array('articles', 'get_other_articles_by_meta_id')); ?>/${meta_id}`, data => {
      data = data.filter(ac => ac.id != <?php echo $article['id']; ?> && ac.subcat_id != 1 && ac.subcat_id != 4);
      metas[meta_id] = data;
      data = data.slice(0, 4);
      let s = $('#other-articles').html();

      s += `<h3 id="${meta_id}-pager">`;
      if (metas[meta_id].length > 4) {
        s += `<div class="btn-disabled"><button class="rotate next-icon m-r-5" />${arrow_back_text}</div>`;
      }

      s += '<span class="other-articles-title">';
      const name_link = `<a href='${meta_link}'>${meta_name}</a>`;
      s += type_id === 1 ? `${name_link} további művei` : `${name_link} sorozat`;
      s += '</span>';

      if (metas[meta_id].length > 4) {
        s += `<div onclick="meta_pager(${meta_id}, 'right', 4)">${arrow_next_text}<button class="m-l-5 next-icon" /></div>`;
      }
      s += '</h3>';

      s += `<div class="other-articles-list" id="${meta_id}">`;
      if(data.length === 0) {
        s += 'Nincs találat kapcsolódó tartalomra!';
      } else {
        data.forEach(article => {
          s += other_article_box(article);
        });
      }
      s += '</div>';

      $('#other-articles').html(s);
    }, "json");
  };

  const meta_pager = (meta_id, direction, start) => {
    const from = Math.max(direction === 'left' ? start - 4 : start, 0);
    const to = Math.min(direction === 'left' ? start : start + 4, metas[meta_id].length);
    const data = metas[meta_id].slice(from, to);
    let s = '';
    data.forEach(article => {
      s += other_article_box(article);
    });
    $(`#${meta_id}`).html(s);
    
    let heading = '';
    if (from !== 0) {
      heading += `<div onclick="meta_pager(${meta_id}, 'left', ${from})" >`;
    } else {
      heading += '<div class="btn-disabled">';
    }
    heading += `<button class="rotate next-icon m-r-5" />${arrow_back_text}</div>`;

    heading += $(`#${meta_id}-pager span`)[1].outerHTML;
    
    if (metas[meta_id].length > to) {
      heading += `<div onclick="meta_pager(${meta_id}, 'right', ${to})">`;
    } else {
      heading += `<div class="btn-disabled">`;
    }
    heading += `${arrow_next_text}<button class="m-l-5 next-icon" /></div>`;
    $(`#${meta_id}-pager`).html(heading);
  };

  <?php if(count($metas) !== 0): ?>
    $('#other-articles').html('<h2>Kapcsolódó tartalmak</h2>');
    $('#other-articles').addClass('other-articles');
    <?php foreach($metas as $m):
      if($m['type_id'] == 1 || $m['type_id'] == 2): ?>
        meta_load(<?php echo $m['meta_id']; ?>, '<?php echo $m['meta_name']; ?>', <?php echo $m['type_id']; ?>, '<?php echo site_url(array('meta', $m['type_slug'], $m['slug'])); ?>');
      <?php endif;
    endforeach;
  endif; ?>
</script>
<?php endif; ?>
