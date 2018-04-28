<?php if($this->session->userdata('level') >= 5):
		$attributes = array('class' => 'form-inline');
		echo form_open('admin/user_list', $attributes);
?>

	<?php if (isset($wasdata) && $wasdata === TRUE && isset($success) && $success === FALSE): ?>
		<div class="alert alert-danger" role="alert">
			Sikertelen mentés! Kérem, próbálja újra!
			<?php echo validation_errors();	?>
		</div>
	<?php endif; ?>
	
	<?php if (isset($success) && $success === TRUE): ?>
		<div class="alert alert-success" role="alert">
			Sikeres mentés!
		</div>
	<?php endif; ?>
	
	<div class = "form-group">
		<?php
			echo form_dropdown('level_filt', $level, $level_filt, 'class="form-control"');
			if($level_filt == ''):
				$level_filt = 'ures';
			endif;
		?>
		<button type="submit" value="filter" name="save" class="btn btn-default">Szűrés</button>
		<a href="<?php echo site_url(array('users', 'reg')); ?>" class="btn btn-primary">
			Új felhasználó regisztrálása
		</a>
	</div>
</form>
	
<nav>
	<ul class="pager">
		<?php if($from != 0): ?> <!--csak akkor aktív, ha nem 0-nál állunk (visszafele nem mehetünk) -->
			<li class="previous">
		<?php else: ?>
			<li class="previous disabled">
		<?php endif; ?>
				<a href="<?php $prev = $from - $limit; 
							if ($prev < 0):  //negatív tartományba ne lapozzunk
								echo site_url(array('admin', 'user_list', $level_filt, '0')); 
							else: 
								echo site_url(array('admin', 'user_list', $level_filt, $prev)); 
							endif; ?>">
					<span aria-hidden="true">&larr;</span> Előző oldal
				</a>
			</li>
		
		<?php if($from+$limit < $cnt): ?> <!--csak akkor lapozhatunk tovább, ha nem megyünk túl az összes lehetőségen -->
			<li class="next">
				<a href="<?php echo site_url(array('admin', 'user_list', $level_filt, $from + $limit)); ?>">
		<?php else: ?>
			<li class="next disabled">
				<a href="<?php echo site_url(array('admin', 'user_list', $level_filt, $from)); ?>">
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
				<th>Felhasználónév</th>
				<th>Megjelenítendő név</th>
				<th>E-mail cím</th>
				<th>Szint</th>
				<th>Szerkesztés</th>
				<th>Törlés</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($users as $user): ?>
				<tr>
				    <td><?php echo $user['username'] ?></td>
					<td><?php echo $user['name'] ?></td>
					<td><?php echo $user['email'] ?></td>
					<td><?php echo $user['level_name'] ?></td>
				    <td><button type="button" data-toggle="modal" data-target="#editModal" data-userid="<?php echo $user['id']; ?>" class="editUser btn btn-default">Szerkesztés</button></td>
					<td><button type="button" class="deleteUser btn btn-danger" data-toggle="modal" data-target="#deleteModal" data-delhref="<?php echo site_url(array('admin', 'user_delete', $user['id'])); ?>">Törlés</button></td>
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
				<h4 class="modal-title" aria-labelledby="">Felhasználó szintjének szerkesztése</h4>
			</div>
			<div class="modal-body">
				<?php 
						$hiddens = array('username' => '');
						$attributes = array('class' => 'form-inline');
						echo form_open('admin/user_list', $attributes, $hiddens);
				?>
					
					<div class = "input-group">
						<span class="input-group-addon" name="name_show"></span>
						<?php
							echo form_dropdown('level', $level, '', 'class="form-control"');
						?>
					</div>
					<button type="submit" value="save" name="save" class="btn btn-default">Mentés</button>
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
				<h4 class="modal-title" aria-labelledby="">Felhasználó törlése</h4>
			</div>
			<div class="modal-body">
				Biztos, hogy törli a kiválasztott felhasználót?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
				<a href="" name="todelete" class="btn btn-danger">Törlés</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	// felhasználó törléséhez szükséges eseménykezelő (a megfelelő link a felhasználó törléséhez a data-attribútumból érkezik)
	$('.deleteUser').click(function (e){
		var delhref = $(e.currentTarget).data('delhref');
		$('a[name="todelete"]').attr("href", delhref);
	});
	
	//felhasználó szerkesztésének eseménykezelője
	$('.editUser').click(function (e){
		userid = $(e.currentTarget).data('userid');
		//AJAX GET kérés a hozzászólás adatinak lekéréséhez
		$.get( '<?php echo site_url(array('admin', 'user_get')); ?>' + '/' + userid, function(data) {
			$('input[name="username"]').val(data.username);
			$('span[name="name_show"]').html(data.name + ' / ' + data.username);
			$('[name="level"]').val(data.level);
		}, "json");
	});
</script>