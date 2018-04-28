<?php if($this->session->userdata('level') >= 4): ?>

	<?php if (isset($success) && $success === TRUE): ?>
		<div class="alert alert-success" role="alert">
			Sikeres mentés!
		</div>
	<?php endif; ?>

	<div class="row">
		<a href="<?php echo site_url(array('admin', 'static_new')); ?>" class="btn btn-primary">
			Új statikus cikk létrehozása
		</a>
	</div>

<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Cím</th>
				<th>URL-cím</th>
				<th>Szerkesztés</th>
				<th>Törlés</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($statics as $st): ?>
				<tr>
				    <td><?php echo $st['title'] ?></td>
					<td><?php echo $st['path'] ?></td>
				    <td><a href="<?php echo site_url(array('admin', 'static_edit', $st['id'])); ?>" class="btn btn-default">Szerkesztés</a></td>
					<td><button type="button" class="deleteStatic btn btn-danger" data-toggle="modal" data-target="#deleteModal" data-delhref="<?php echo site_url(array('admin', 'static_delete', $st['id'])); ?>">Törlés</button></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>

<?php endif; ?>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" aria-labelledby="">Statikus cikk törlése</h4>
			</div>
			<div class="modal-body">
				Biztos, hogy törli a kiválasztott statikus cikket?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
				<a href="" name="todelete" class="btn btn-danger">Törlés</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	//statikus cikk törléséhez szükséges eseménykezelő (a megfelelő link a data-attribútumból)
	$('.deleteStatic').click(function (e){
		var delhref = $(e.currentTarget).data('delhref');
		$('a[name="todelete"]').attr("href", delhref);
	});
</script>