<?php $size = 50;
	$attributes = array('class' => 'form-horizontal');
	echo form_open('users/login', $attributes); ?>
	
	<?php if ($wasdata === TRUE): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo validation_errors();	?>
			<?php if(isset($wrong_user)): echo 'A felhasználónév/jelszó páros nem egyezik!'; endif; ?>
		</div>
	<?php endif; ?>
	
	<div class = "form-group">
		<label for = "username" class="col-md-2 control-label">Felhasználónév</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'username',
			              'value'       => set_value('username'),
			              'maxlength'   => '128',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Felhasználónév'
			            );
				echo form_input($data); ?>
		</div>
	</div>

	<div class = "form-group">
		<label for = "password" class="col-md-2 control-label">Jelszó</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'password',
			              'value'       => '',
			              'maxlength'   => '128',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Jelszó'
			            );
				echo form_password($data); ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-offset-2 col-md-6">
			<button type="submit" name="save" value="save" class="btn btn-default">Belépés</button>
		</div>
	</div>
</form>