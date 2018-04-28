<?php 	if($this->session->userdata('level') >= 4):
			$size = 50;
			$attributes = array('class' => 'form-horizontal');
			$hiddens = array('id' => $static['id']);
			echo form_open('admin/static_edit/'.$static['id'], $attributes, $hiddens);
?>
	
	<?php if (isset($wasdata) && $wasdata === TRUE && $success === FALSE): ?>
		<div class="alert alert-danger" role="alert">
			Sikertelen mentés!
			<?php echo validation_errors();	?>
		</div>
	<?php endif; ?>
	
	<?php if (isset($success) && $success === TRUE): ?>
		<div class="alert alert-success" role="alert">
			Sikeres mentés!
		</div>
	<?php endif; ?>

	<div class = "form-group">
		<label for = "title" class="col-md-2 control-label">Cím</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'title',
			              'value'       => $static['title'],
			              'maxlength'   => '100',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Cím'
			            );
				echo form_input($data); ?>
		</div>
	</div>

	<div class = "form-group">
		<label for = "path" class="col-md-2 control-label">Útvonal</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'path',
			              'value'       => $static['path'],
			              'maxlength'   => '100',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Az URL-ben megjelenő szöveg (pl. index.php/about)'
			            );
				echo form_input($data); ?>
		</div>
	</div>
	
	<div class = "form-group">
		<label for = "body" class="col-md-2 control-label">Tartalom</label>
		<div class="col-md-6">
				<?php $data = array(
							'name'        => 'body',
							'value'       => $static['body'],
							'maxlength'   => '65000',
							'class'		  => 'form-control',
							'id'		  => 'editor',
							'placeholder' => 'főszöveg'
							);
					echo form_textarea($data); ?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-md-offset-2 col-md-6">
			<button type="submit" value="save" name="save" class="btn btn-default">
				Mentés
			</button>
			<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
				Statikus cikk törlése
			</button>
		</div>
	</div>
	
</form>
<?php endif; ?>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" aria-labelledby="">Statikus cikk törlése</h4>
			</div>
			<div class="modal-body">
				Biztos, hogy törli a kiválasztott cikket?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
				<a href="<?php echo site_url(array('admin', 'static_delete', $static['id'])); ?>" class="btn btn-danger">Törlés</a>
			</div>
		</div>
	</div>
</div>