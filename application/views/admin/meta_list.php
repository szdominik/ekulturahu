<?php if($this->session->userdata('level') >= 4): ?>
<div class="row">
	<?php
			$attributes = array('class' => 'form-inline');
			echo form_open('admin/meta_list', $attributes);
	?>

		<?php if (isset($wasdata) && $wasdata === TRUE && ! isset($success)): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo validation_errors();	?>
			</div>
		<?php endif; ?>
		
		<?php if (isset($success) && $success === FALSE): ?>
			<div class="alert alert-danger" role="alert">
				Adatbázishiba történt. A hiba valószínűsíthető oka: már létező címke!
			</div>
		<?php endif; ?>
		
		<?php if (isset($success) && $success === TRUE): ?>
			<div class="alert alert-success" role="alert">
				Sikeres mentés!
			</div>
		<?php endif; ?>

		<!--Szűrési lehetőségek -->
		<div class = "form-group">
			<?php
				echo form_dropdown('meta_filter', $metatype, $meta_filter, 'class="form-control"');
				if($meta_filter == ''):
					$meta_filter = 0;
				endif;
			?>
			<?php if($meta_name == '0'):
					echo form_input('meta_name', '', 'class="form-control" placeholder="Szűrés névre"');
				else:
					echo form_input('meta_name', $meta_name, 'class="form-control" placeholder="Szűrés névre"');
				endif;  ?>
		</div>
		<button type="submit" value="filter" name="save" class="btn btn-default">Szűrés</button>
		<button type="button" data-target="#newModal" data-toggle="modal" class="btn btn-primary">Új címke felvitele</button>
	</form>
</div>

<nav>
	<ul class="pager">
		<?php if($from != 0): ?> <!--csak akkor aktív, ha nem 0-nál állunk (visszafele nem mehetünk) -->
			<li class="previous">
		<?php else: ?>
			<li class="previous disabled">
		<?php endif; ?>
				<a href="<?php $prev = $from - $limit; 
							if ($prev < 0):  //negatív tartományba ne lapozzunk
								echo site_url(array('admin', 'meta_list', $meta_name, $meta_filter, '0')); 
							else: 
								echo site_url(array('admin', 'meta_list', $meta_name, $meta_filter, $prev)); 
							endif; ?>">
					<span aria-hidden="true">&larr;</span> Előző oldal
				</a>
			</li>
		
		<?php if($from+$limit < $cnt): ?> <!--csak akkor lapozhatunk tovább, ha nem megyünk túl az összes lehetőségen -->
			<li class="next">
				<a href="<?php echo site_url(array('admin', 'meta_list', $meta_name, $meta_filter, $from + $limit)); ?>">
		<?php else: ?>
			<li class="next disabled">
				<a href="<?php echo site_url(array('admin', 'meta_list', $meta_name, $meta_filter, $from)); ?>">
		<?php endif; ?>
					Következő oldal <span aria-hidden="true">&rarr;</span>
				</a>
			</li>
	</ul>
</nav>

<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Meta név</th>
				<th>Meta típus</th>
				<th>Szerkesztés</th>
				<th>Törlés</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($metas as $meta): ?>
				<tr>
				    <td><a href="<?php echo site_url(array('meta', $meta['type_slug'], $meta['slug']))?>"><?php echo $meta['name'] ?></a></td>
					<td><?php echo $meta['type_name'] ?></td>
				    <td><button type="button" class="editMeta btn btn-default" data-toggle="modal" data-metaid="<?php echo $meta['id']; ?>" data-target="#editModal">Szerkesztés</button></td>
					<td><button type="button" class="deleteMeta btn btn-danger" data-toggle="modal" data-metaid="<?php echo $meta['id']; ?>" data-target="#deleteModal">Törlés</button></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>

<?php endif; ?>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" aria-labelledby="">Címke szerkesztése</h4>
			</div>
			<div class="modal-body">
				<?php 
					$attributes = array('class' => 'form-inline');
					$hiddens = array('metaid' => '');
					echo form_open('admin/meta_list', $attributes, $hiddens);
				?>

				<?php if (isset($wasdata) && $wasdata === TRUE && ! isset($success)): ?>
					<div class="alert alert-danger" role="alert">
						<?php echo validation_errors();	?>
					</div>
				<?php endif; ?>
				
				<div class = "form-group">
					<?php
						echo form_dropdown('metatype', $metatype, '', 'class="form-control"');
						echo form_input('metaname', '', 'class="form-control" placeholder="Címke név"');
					?>
				</div>
			
				<button type="submit" value="save" name="save" class="btn btn-primary">Mentés</button>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="newModal" tabindex="-1" aria-labelledby="" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" aria-labelledby="">Címke felvétele</h4>
			</div>
			<div class="modal-body">
				<?php 
					$attributes = array('class' => 'form-inline');
					$hiddens = array('metaid' => '-1');
					echo form_open('admin/meta_list', $attributes, $hiddens);
				?>

				<?php if (isset($wasdata) && $wasdata === TRUE && ! isset($success)): ?>
					<div class="alert alert-danger" role="alert">
						<?php echo validation_errors();	?>
					</div>
				<?php endif; ?>
				
				<div class = "form-group">
					<?php
						echo form_dropdown('metatype', $metatype, '', 'class="form-control"');
						echo form_input('metaname', '', 'class="form-control" placeholder="Címke név"');
					?>
				</div>
			
				<button type="submit" value="save" name="save" class="btn btn-primary">Új mentése</button>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" aria-labelledby="">Címke törlése</h4>
			</div>
			<div class="modal-body">
				Biztos, hogy törli a kiválasztott címkét?<br />
				Figyelem! A törléssel minden cikkhez való kapcsolódódást is törli!<br />
				<span id='metaCountForDelete'></span> cikket érint ez a törlés.
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
				<a href="" name="todelete" class="btn btn-danger">Törlés</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	//metaadat törléséhez szükséges eseménykezelő (a megfelelő link a data-attribútumból történik)
	$('.deleteMeta').click((e) => {
		const baseUrl = "<?php echo site_url(array('admin', 'meta_delete')); ?>";
		const metaid = $(e.currentTarget).data('metaid');
		$('a[name="todelete"]').attr("href", `${baseUrl}/${metaid}`);
		$.get('<?php echo site_url(array('admin', 'count_meta_by_id')); ?>' + '/' + metaid, (data) => {
			$('#metaCountForDelete').text(data);
		});
	});
	
	//metaadat szerkesztéséhez szükséges eseménykezelő
	$('.editMeta').click((e) => {
		metaid = $(e.currentTarget).data('metaid');
		//AJAX GET kérés a metaadatok adatainak betöltéséhez
		$.get('<?php echo site_url(array('admin', 'meta_get')); ?>' + '/' + metaid, (data) => {
			$('[name="metatype"]').val(data.type);
			$('input[name="metaname"]').val(data.name);
			$('input[name="metaid"]').val(data.id);
		}, "json");
	});
</script>