<?php 	$size = 50;
		$attributes = array('class' => 'form-horizontal');
		echo form_open('users/reg', $attributes);
	?>

	<?php if ($wasdata === TRUE && !isset($success)): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo validation_errors();	?>
		</div>
	<?php endif; ?>
	
	<?php if ($wasdata === TRUE && isset($success) && $success === FALSE): ?>
		<div class="alert alert-danger" role="alert">
			Sikertelen regisztráció! Kérem
		</div>
	<?php endif; ?>
	
	<?php if (isset($success) && $success === TRUE): ?>
		<div class="alert alert-success" role="alert">
			Sikeres regisztráció!
			<br />
			Most már beléphet az oldalunkra.
		</div>
	<?php endif; ?>
	
	<div class = "form-group">
		<label for = "name" class="col-md-2 control-label">Megjelenítendő név</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'name',
			              'value'       => set_value('name'),
			              'maxlength'   => '128',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Megjelenítendő név'
			            );
				echo form_input($data); ?>
		</div>
	</div>
	
	<div class = "form-group">
		<label for = "username" class="col-md-2 control-label">Felhasználónév</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'username',
			              'value'       => set_value('username'),
			              'maxlength'   => '128',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Felhasználónév (kötelező!)'
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
						  'placeholder' => 'Jelszó (kötelező!)'
			            );
				echo form_password($data); ?>
		</div>
	</div>
	
	<div class = "form-group">
		<label for = "passconf" class="col-md-2 control-label">Jelszó megerősítése</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'passconf',
			              'value'       => '',
			              'maxlength'   => '128',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Jelszó megerősítése (kötelező!)'
			            );
				echo form_password($data); ?>
		</div>
	</div>
	
	<div class = "form-group">
		<label for = "email" class="col-md-2 control-label">E-mail cím</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'email',
			              'value'       => set_value('email'),
			              'maxlength'   => '128',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'E-mail cím (kötelező!)'
			            );
				echo form_input($data); ?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-md-offset-2 col-xs-offset-1 col-md-6 col-xs-10">
			<div class="checkbox">
			    <label>
			      <?php echo form_checkbox('accept', '1', set_checkbox('accept', '1')); ?> Elfogadom a felhasználási feltételeket.
			    </label>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-offset-2 col-xs-offset-1 col-md-6 col-xs-10">
			<button type="submit" name="save" value="save" class="btn btn-default">Regisztráció</button>
		</div>
	</div>

</form>