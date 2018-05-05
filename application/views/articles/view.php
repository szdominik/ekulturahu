<?php if(!$article['login'] || $this->session->userdata('logged_in')): ?>
<ol class="breadcrumb">
	<li><a href="<?php echo site_url(); ?>">Főoldal</a></li>
	<li><a href="<?php echo site_url($article['subcat_slug']); ?>"><?php echo $article['subcat_name']; ?></a></li>
</ol>

<div class="row">
  <div class="col-md-10">
      <h1><?php echo $article['title'] ?></h1>
    <h4><?php echo $article['pub_time'] . ' / Írta: ' . $article['user_link']; ?></h4>
      <div>
      <?php if($article['image_path'] != NULL):
        if($article['image_horizontal'] == 1): ?>
          <img src="<?php echo base_url(array('uploads', $article['image_path'])); ?>" class="img-responsive img-article-center" alt="Főkép">
        <?php else: ?>
          <img src="<?php echo base_url(array('uploads', $article['image_path'])); ?>" class="img-responsive img-article-main" align="left" alt="Főkép">
        <?php endif;
      endif; ?>
      <?php echo $article['body'] ?>
      </div>
  </div>
  <div class="col-md-2">
    <div class="btn-group-vertical">
      <?php if($article['kedv_vasar'] != '' && $article['kedv_vasar'] != '0'): ?>
        <a href="<?php echo $article['kedv_vasar']; ?>" class="btn btn-info">Kedvezményes vásárlás</a>
      <?php endif; ?>
    </div>
    <?php if(count($metas) !== 0 || $article['eredeti_cim'] != '' || $article['ar'] != '' ||
    $article['terjedelem'] != '' || $article['forgatokonyviro'] != '' ||
    $article['operator'] != '' || $article['producer'] != ''): ?>
      <h2>Adatok</h2><!-- metaadatok megjelenítése -->
      <?php for($i = 0; $i < count($metas); ++$i) { ?>
        <h4><?php echo $metas[$i]['type_name']; ?>:</h4>
        <p>
          <a href="<?php echo site_url(array('meta', $metas[$i]['type_slug'], $metas[$i]['slug'])); ?>"><?php echo $metas[$i]['meta_name']; ?></a><?php for(;$i+1 < count($metas) && $metas[$i]['type_name'] == $metas[$i+1]['type_name']; ++$i) { ?><?php echo ', ' ?><a href="<?php echo site_url(array('meta', $metas[$i+1]['type_slug'], $metas[$i+1]['slug'])); ?>"><?php echo $metas[$i+1]['meta_name']; ?></a><?php } ?>
        </p>
      <?php } ?>
      <?php if ($article['eredeti_cim'] != ''): ?>
        <h4>Eredeti cím:</h4>
        <p><?php echo $article['eredeti_cim']; ?></p>
      <?php endif; ?>
      <?php if ($article['ar'] != ''): ?>
        <h4>Ár:</h4>
        <p><?php echo $article['ar']; ?></p>
      <?php endif; ?>
      <?php if ($article['terjedelem'] != ''): ?>
        <?php if ($article['category_id'] === '1'): ?><h4>Oldalszám:</h4>
        <?php elseif ($article['category_id'] === '3' || $article['category_id'] === '2'): ?><h4>Hossz:</h4>
        <?php else: ?><h4>Terjedelem:</h4>
        <?php endif; ?>
        <p><?php echo $article['terjedelem']; ?></p>
      <?php endif;  ?>
      <?php if ($article['forgatokonyviro'] != ''): ?>
        <h4>Forgatókönyvíró:</h4>
        <p><?php echo $article['forgatokonyviro']; ?></p>
      <?php endif; ?>
      <?php if ($article['operator'] != ''): ?>
        <h4>Operatőr:</h4>
        <p><?php echo $article['operator']; ?></p>
      <?php endif; ?>
      <?php if ($article['producer'] != ''): ?>
        <h4>Producer:</h4>
        <p><?php echo $article['producer']; ?></p>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<div id="other_articles">
</div>

<script>
	function meta_load(meta_id, meta_name)
	{
		$.get( '<?php echo site_url(array('articles', 'get_other_articles_by_meta_id')); ?>' + '/' + meta_id, function(data) {
			var s = $('#other_articles').html()
			var count = 0;
			s += '<h4>' + meta_name + `</h4>
				<div class="row">
					<div class="col-md-10">
						<table class="table table-condensed">`
							for(var i = 0; i < data.length; ++i)
							{
								if(data[i].id != <?php echo $article['id']; ?> && data[i].subcat_id != 1 && data[i].subcat_id != 4)
								{
									s += `<tr>
											<td>
												<a href="` + data[i].link + '">' + data[i].title + `</a>
											</td>
										</tr>`
									++count;
								}
							}
							if(count === 0)
							{
								s += 'Nincs találat kapcsolódó tartalomra!';
							}
						s += '</table>' +
					'</div>' +
				'</div>';
			$('#other_articles').html(s);
		}, "json");
	}
	
	<?php if(count($metas) !== 0): ?>
		$('#other_articles').html('<h3>Kapcsolódó tartalmak</h3>');
		<?php foreach($metas as $m):
			if($m['type_id'] == 1 || $m['type_id'] == 2): ?>
				meta_load(<?php echo $m['meta_id']; ?>, '<?php echo $m['meta_name']; ?>');
			<?php endif;
		endforeach;
	endif; ?>
</script>
<?php endif; ?>
