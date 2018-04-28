<?php if($this->session->userdata('level') >= 4):
		$size = 50;
		$attributes = array('class' => 'form-horizontal');
		echo form_open('admin/static_new', $attributes);
?>

	<?php if (isset($wasdata) && $wasdata === TRUE): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo validation_errors();	?>
		</div>
	<?php endif; ?>
	
	<div class = "form-group">
		<label for = "title" class="col-md-2 control-label">Cím</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'title',
			              'value'       => set_value('title'),
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
			              'value'       => set_value('path'),
			              'maxlength'   => '100',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Az URL-ben megjelenő szöveg (pl. index.php/about)'
			            );
				echo form_input($data); ?>
		</div>
	</div>
	
	<div class = "form-group">
		<label for = "body" class="col-md-2 control-label">Főszöveg</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'body',
			              'value'       => set_value('body'),
			              'maxlength'   => '65000',
						  'class'		=> 'form-control',
						  'id'			=> 'editor',
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
		</div>
	</div>

</form>
<?php endif ?>