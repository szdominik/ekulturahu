<?php if($this->session->userdata('level') >= 5): ?>
	
	<?php if (isset($success) && $success === FALSE): ?>
		<div class="alert alert-danger" role="alert">
			Sikertelen mentés! Kérem, próbálja újra!
		</div>
	<?php endif; ?>
	
	<?php if (isset($success) && $success === TRUE): ?>
		<div class="alert alert-success" role="alert">
			Sikeres mentés!
		</div>
	<?php endif; ?>
	
<div class="panel panel-primary">
	<div class="panel-heading">Kategóriák</div>
	<div class="panel-body">
		<button type="button" data-toggle="modal" data-target="#newModal" data-type="1" class="newCat btn btn-primary">Új kategória</button>
	</div>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Név</th>
					<th>Cikkek száma</th>
					<th>Szerkesztés</th>
					<th>Törlés</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($categories as $c): ?>
					<tr>
					    <td><?php echo $c['name']; ?></td>
						<td><?php echo $c['cnt']; ?></td>
					    <td><button type="button" data-toggle="modal" data-target="#editModal" data-id="<?php echo $c['id']; ?>" data-type="1" data-name="<?php echo $c['name']; ?>" class="editCat btn btn-default">Szerkesztés</button></td>
						<td>
						<?php if ($c['cnt'] == 0) : ?>
						<button type="button" class="deleteCat btn btn-danger" data-toggle="modal" data-target="#deleteModal" data-delhref="<?php echo site_url(array('admin', 'category_delete', $c['id'], '1')); ?>">Törlés</button>
						<?php endif; ?>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>

<div class="panel panel-info">
	<div class="panel-heading">Alkategóriák</div>
	<div class="panel-body">
		<button type="button" data-toggle="modal" data-target="#newModal" data-type="2" class="newCat btn btn-info">Új alkategória</button>
	</div>
	
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Név</th>
					<th>Cikkek száma</th>
					<th>Szerkesztés</th>
					<th>Törlés</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($subcategories as $sc): ?>
					<tr>
					    <td><?php echo $sc['name']; ?></td>
						<td><?php echo $sc['cnt']; ?></td>
					    <td><button type="button" data-toggle="modal" data-target="#editModal" data-id="<?php echo $sc['id']; ?>" data-type="2" data-name="<?php echo $sc['name']; ?>" class="editCat btn btn-default">Szerkesztés</button></td>
						<td>
						<?php if ($sc['cnt'] == 0) : ?>
						<button type="button" class="deleteCat btn btn-danger" data-toggle="modal" data-target="#deleteModal" data-delhref="<?php echo site_url(array('admin', 'category_delete', $sc['id'], '2')); ?>">Törlés</button>
						<?php endif; ?>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>

<div class="panel panel-success">
	<div class="panel-heading">Metaadat típusok</div>
	<div class="panel-body">
		<button type="button" data-toggle="modal" data-target="#newModal" data-type="3" class="newCat btn btn-success">Új metaadat típus</button>
	</div>
	
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Név</th>
					<!--<th>Cikkek száma</th>-->
					<th>Szerkesztés</th>
					<th>Törlés</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($metatypes as $m): ?>
					<tr>
					    <td><?php echo $m['name']; ?></td>
						<!--<td><?php echo $m['cnt']; ?></td>-->
					    <td><button type="button" data-toggle="modal" data-target="#editModal" data-id="<?php echo $m['id']; ?>" data-type="3" data-name="<?php echo $m['name']; ?>" class="editCat btn btn-default">Szerkesztés</button></td>
						<td>
						<?php //if ($m['cnt'] == 0) : ?>
						<button type="button" class="deleteCat btn btn-danger" data-toggle="modal" data-target="#deleteModal" data-delhref="<?php echo site_url(array('admin', 'category_delete', $m['id'], '3')); ?>">Törlés</button>
						<?php //endif; ?>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>

<?php endif; ?>

<div class="modal fade" id="newModal" tabindex="-1" aria-labelledby="" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" aria-labelledby="">Új kategória felvitele</h4>
			</div>
			<div class="modal-body">
				<?php 
						$hiddens = array('type' => 0);
						echo form_open('admin/category_list', '', $hiddens);
				?>
					<span name="type_name">név</span>
					<div class="form-group">
					    <?php $data = array(
									  'name'        => 'name',
									  'maxlength'   => '100',
									  'class'		=> 'form-control',
									  'placeholder' => 'Név'
									);
							echo form_input($data);?>
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

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" aria-labelledby="">Kategória nevének szerkesztése</h4>
			</div>
			<div class="modal-body">
				<?php 
						$hiddens = array('id' => 0, 'type' => 0);
						echo form_open('admin/category_list', '', $hiddens);
				?>
					<div class="form-group">
					    <?php $data = array(
									  'name'        => 'name',
									  'value'       => 'name',
									  'maxlength'   => '100',
									  'class'		=> 'form-control',
									);
							echo form_input($data);?>
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

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" aria-labelledby="">Kategória törlése</h4>
			</div>
			<div class="modal-body">
				Biztos, hogy törli a kiválasztott kategóriát?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
				<a href="" name="todelete" class="btn btn-danger">Törlés</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	//kategória törlésének eseménykezelője (hogy jót töröljünk, a link lekérése a data attribútumból)
	$('.deleteCat').click(function (e){
		var delhref = $(e.currentTarget).data('delhref');
		$('a[name="todelete"]').attr("href", delhref);
	});
	
	//a megfelleő kategória "új" létrehozása eseménykezelője
	$('.newCat').click(function (e){
		var type = $(e.currentTarget).data('type'); //típus szerint döntjük el, hogy mit akarunk majd menteni
		if(type == 1)
			$('span[name="type_name"]').html('Főkategória');
		else if(type == 2)
			$('span[name="type_name"]').html('Alkategória');
		else
			$('span[name="type_name"]').html('Metaadat típus');
		$('input[name="type"]').val(type);
	});

	//kategórianév-szerkesztő eseménykezelő
	$('.editCat').click(function (e){
		//a megfelelő data-attribútumokból kérjük le a nekünk kellő adatokat
		var name = $(e.currentTarget).data('name');
		var id = $(e.currentTarget).data('id');
		var type = $(e.currentTarget).data('type');
		$('input[name="id"]').val(id);
		$('input[name="name"]').val(name);
		$('input[name="type"]').val(type);
	});
</script>