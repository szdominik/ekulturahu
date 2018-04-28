<?php if($this->session->userdata('level') >= 1):
			$attributes = array('class' => 'form-inline');
			echo form_open('admin/comment_list', $attributes);
?>
			<?php if (isset($wasdata) && $wasdata === TRUE && isset($success) && $success === FALSE): ?>
				<div class="alert alert-danger" role="alert">
					<?php echo validation_errors();	?>
				</div>
			<?php endif; ?>
			
			<?php if (isset($wasdata) && $wasdata === TRUE && isset($success) && $success === FALSE): ?>
				<div class="alert alert-danger" role="alert">
					Sikertelen mentés! Kérem, próbálja újra!
				</div>
			<?php endif; ?>
			
			<?php if (isset($success) && $success === TRUE): ?>
				<div class="alert alert-success" role="alert">
					Sikeres mentés!
				</div>
			<?php endif; ?>

			<div class = "form-group">
				<?php 
					echo form_input('comm_filt', $comm_filt, 'class="form-control" placeholder="Szűrés tartalomra"');
					if($comm_filt == ''):
						$comm_filt = 0;
					endif;
				?>
			</div>
			
			<button type="submit" value="filter" name="save" class="btn btn-default">Szűrés</button>
		</form>

<nav>
	<ul class="pager">
		<?php if($from != 0): ?> <!--csak akkor aktív, ha nem 0-nál állunk (visszafele nem mehetünk) -->
			<li class="previous">
		<?php else: ?>
			<li class="previous disabled">
		<?php endif; ?>
				<a href="<?php $prev = $from - $limit; 
							if ($prev < 0): //negatív tartományba ne lapozzunk
								echo site_url(array('admin', 'comment_list', urlencode($comm_filt), '0')); 
							else: 
								echo site_url(array('admin', 'comment_list', urlencode($comm_filt), $prev)); 
							endif; ?>">
					<span aria-hidden="true">&larr;</span> Előző oldal
				</a>
			</li>
		
		<?php if($from+$limit < $cnt): ?> <!--csak akkor lapozhatunk tovább, ha nem megyünk túl az összes lehetőségen -->
			<li class="next">
				<a href="<?php echo site_url(array('admin', 'comment_list', urlencode($comm_filt), $from + $limit)); ?>">
		<?php else: ?>
			<li class="next disabled">
				<a href="<?php echo site_url(array('admin', 'comment_list', urlencode($comm_filt), $from)); ?>">
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
				<th>Név</th>
				<th>Felhasználónév</th>
				<th>Dátum</th>
				<th>Cikk címe</th>
				<th>Hozzászólás</th>
				<th>Szerkesztés</th>
				<th>Törlés</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($comments as $c): ?>
				<tr>
				    <td><?php if($c['user_name'] != NULL): echo $c['user_name']; else: echo 'törölt felhasználó'; endif; ?></td>
					<td><?php if($c['user_username'] != NULL): echo $c['user_username']; else: echo 'törölt felhasználó'; endif; ?></td>
					<td><?php echo $c['date']; ?></td>
					<td><a href="<?php echo $c['link'] ?>"><?php echo $c['title']; ?></a></td>
					<td><?php echo substr($c['body'], 0, 200); ?></td>
				    <td><button type="button" data-toggle="modal" data-target="#editModal" data-commid="<?php echo $c['id']; ?>" class="editComm btn btn-default">Szerkesztés</button></td>
					<td><button type="button" class="deleteComm btn btn-danger" data-toggle="modal" data-target="#deleteModal" data-delhref="<?php echo site_url(array('admin', 'comment_delete', $c['id'])); ?>">Törlés</button></td>
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
				<h4 class="modal-title" aria-labelledby="">Hozzászólás szerkesztése</h4>
			</div>
			<div class="modal-body">
				<?php 
						$hiddens = array('comm_id' => '', 'comm_username' => '');
						echo form_open('admin/comment_list', '', $hiddens);
				?>
					
					<div class="form-group">
						<p class="form-control-static">Írta: <span name="username">valaki</span></p>
					    <?php $data = array(
									  'name'        => 'comm_body',
									  'value'       => '',
									  'maxlength'   => '1000',
									  'rows'		=> '3',
									  'class'		=> 'form-control',
									);
							echo form_textarea($data);?>
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
				<h4 class="modal-title" aria-labelledby="">Hozzászólás törlése</h4>
			</div>
			<div class="modal-body">
				Biztos, hogy törli a kiválasztott hozzászólást?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
				<a href="" name="todelete" class="btn btn-danger">Törlés</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	//hozzászólás törlésének eseménykezelője (hogy jót töröljünk, a link lekérése a data attribútumból
	$('.deleteComm').click(function (e){
		var delhref = $(e.currentTarget).data('delhref');
		$('a[name="todelete"]').attr("href", delhref);
	});
	
	//hozzászólás szerkesztésének eseménykezelője
	$('.editComm').click(function (e){
		commid = $(e.currentTarget).data('commid');
		//AJAX GET kérés a hozzászólás adatinak lekéréséhez
		$.get( '<?php echo site_url(array('admin', 'comment_get')); ?>' + '/' + commid, function(data) {
			$('input[name="comm_username"]').val(data.user_username);
			$('span[name="username"]').html(data.user_username);
			$('[name="comm_body"]').val(data.body);
			$('input[name="comm_id"]').val(data.id);
		}, "json");
	});
</script>