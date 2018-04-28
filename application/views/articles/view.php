<?php if($ac_item['login'] == 0 || $this->session->userdata('logged_in') === TRUE): ?>
<ol class="breadcrumb">
	<li><a href="<?php echo site_url(); ?>">Főoldal</a></li>
	<li><a href="<?php echo site_url($ac_item['subcat_slug']); ?>"><?php echo $ac_item['subcat_name']; ?></a></li>
</ol>

	<div class="row">
		<div class="col-md-10">
		    <h1><?php echo $ac_item['title'] ?></h1>
			<h4><?php echo $ac_item['pub_time'] . ' / Írta: ' . $ac_item['user_link']; ?></h4>
		    <div class="article">
				<?php if($ac_item['image_path'] != NULL):
					if($ac_item['image_horizontal'] == 1): ?>
						<img src="<?php echo base_url(array('uploads', $ac_item['image_path'])); ?>" class="img-responsive img-article-center" alt="Főkép">
					<?php else: ?>
						<img src="<?php echo base_url(array('uploads', $ac_item['image_path'])); ?>" class="img-responsive img-article-main" align="left" alt="Főkép">
					<?php endif;
				endif; ?>
				<?php echo $ac_item['body'] ?>
		    </div>
		</div>
		<div class="col-md-2">
			<div class="btn-group-vertical">
				<?php if(($this->session->userdata('logged_in') === TRUE) && 
					($this->session->userdata('level') > 3 || $this->session->userdata('id') == $ac_item['user_id'])): ?>
					<a href="<?php echo site_url(array('admin', 'article_edit', $ac_item['id'])); ?>" class="btn btn-primary">Szerkesztés</a>
				<?php endif; ?>
				<?php if($ac_item['kedv_vasar'] != '' && $ac_item['kedv_vasar'] != '0'): ?>
					<a href="<?php echo $ac_item['kedv_vasar']; ?>" class="btn btn-info">Kedvezményes vásárlás</a>
				<?php endif; ?>
			</div>
			<?php if(count($metas) !== 0 || $ac_item['eredeti_cim'] != '' || $ac_item['ar'] != '' ||
			$ac_item['terjedelem'] != '' || $ac_item['forgatokonyviro'] != '' ||
			$ac_item['operator'] != '' || $ac_item['producer'] != ''): ?>
				<h2>Adatok</h2><!-- metaadatok megjelenítése -->
				<?php for($i = 0; $i < count($metas); ++$i) { ?>
					<h4><?php echo $metas[$i]['type_name']; ?>:</h4>
					<p>
						<a href="<?php echo site_url(array('meta', $metas[$i]['type_slug'], $metas[$i]['slug'])); ?>"><?php echo $metas[$i]['meta_name']; ?></a><?php for(;$i+1 < count($metas) && $metas[$i]['type_name'] == $metas[$i+1]['type_name']; ++$i) { ?><?php echo ', ' ?><a href="<?php echo site_url(array('meta', $metas[$i+1]['type_slug'], $metas[$i+1]['slug'])); ?>"><?php echo $metas[$i+1]['meta_name']; ?></a><?php } ?>
					</p>
				<?php } ?>
				<?php if ($ac_item['eredeti_cim'] != ''): ?>
					<h4>Eredeti cím:</h4>
					<p><?php echo $ac_item['eredeti_cim']; ?></p>
				<?php endif; ?>
				<?php if ($ac_item['ar'] != ''): ?>
					<h4>Ár:</h4>
					<p><?php echo $ac_item['ar']; ?></p>
				<?php endif; ?>
				<?php if ($ac_item['terjedelem'] != ''): ?>
					<?php if ($ac_item['category_id'] === '1'): ?><h4>Oldalszám:</h4>
					<?php elseif ($ac_item['category_id'] === '3' || $ac_item['category_id'] === '2'): ?><h4>Hossz:</h4>
					<?php else: ?><h4>Terjedelem:</h4>
					<?php endif; ?>
					<p><?php echo $ac_item['terjedelem']; ?></p>
				<?php endif;  ?>
				<?php if ($ac_item['forgatokonyviro'] != ''): ?>
					<h4>Forgatókönyvíró:</h4>
					<p><?php echo $ac_item['forgatokonyviro']; ?></p>
				<?php endif; ?>
				<?php if ($ac_item['operator'] != ''): ?>
					<h4>Operatőr:</h4>
					<p><?php echo $ac_item['operator']; ?></p>
				<?php endif; ?>
				<?php if ($ac_item['producer'] != ''): ?>
					<h4>Producer:</h4>
					<p><?php echo $ac_item['producer']; ?></p>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
	
	<br><br>
	
	<?php if(! empty($comments)): //kommentek megjelenítése
			foreach($comments as $c): ?>
				<div class="row">
					<div class="col-md-5">
						<div class="panel panel-default">
							<div class="panel-heading">
								<?php echo $c['date'] . ' &ndash; írta: ';
									if($c['user_name'] != NULL): 
										echo $c['user_name'];
									elseif($c['users_username'] != NULL):
										echo $c['users_username'];
									else:
										echo 'törölt felhasználó';
									endif;
									if($this->session->userdata('logged_in') == TRUE && $this->session->userdata('id') === $c['user_id'])
										echo ' (' . anchor("admin/comment_list", "szerkesztés / törlés") . ')';
								?>
							</div>
							<div class="panel-body">
								<?php echo $c['body']; ?>
							</div>
						</div>
					</div>
				</div>
	<?php endforeach; endif; ?>

<?php 
	if(FALSE && $this->session->userdata('logged_in') === TRUE && $ac_item['comment'] != 0): //bejelentkezett felhasználó írhat kommentet
		echo form_open(current_url());
?>

			<?php if (isset($success) && $success == FALSE): ?>
				<div class="row">
					<div class="col-md-5">
						<div class="alert alert-dismissible alert-danger" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							Sikertelen hozzászólás!
							<?php echo validation_errors();	?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<p class="form-control-static">
							Hozzászólás (
							<?php if($this->session->userdata('name') != ''):
								echo $this->session->userdata('name');
							else: 
								echo $this->session->userdata('username');
							endif; ?>
							)
						</p>
					    <?php $data = array(
									  'name'        => 'comment',
									  'value'       => '',
									  'maxlength'   => '5000',
									  'rows'		=> '3',
									  'class'		=> 'form-control',
									);
							echo form_textarea($data);?>
					</div>
				</div>
			</div>
			<button type="submit" value="comment_send" name="save" class="btn btn-default">Mentés</button>
		</form>
	<?php endif; ?>
<?php endif; ?>

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
								if(data[i].id != <?php echo $ac_item['id']; ?> && data[i].subcat_id != 1 && data[i].subcat_id != 4)
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