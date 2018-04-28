<?php   $attributes = array('class' => 'form-inline');
		echo form_open('users/passwd', $attributes); ?>
		
	<?php if ($wasdata === TRUE && !isset($success)): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo validation_errors();	?>
		</div>
	<?php endif; ?>
	
	<?php if(isset($success) && $success == FALSE): ?>
		<div class="alert alert-danger" role="alert">
			Sikertelen jelszó-generálás! Kérem, próbálja újra!
		</div>
	<?php endif; ?>
	
	<?php if(isset($success) && $success == TRUE): ?>
		<div class="alert alert-success" role="alert">
			A jelszó elküldése megtörtént a kért e-mail címre.
		</div>
	<?php endif; ?>
		
	<p class="help-block">Amennyiben elfelejtette a jelszavát, az alábbiakban kérheti új jelszó generálását.</p>
	<div class = "form-group">
		<label for = "email" class="sr-only">E-mail cím</label>
		<?php $data = array(
		              'name'        => 'email',
		              'maxlength'   => '128',
		              'size'        => '50',
					  'class'		=> 'form-control',
					  'placeholder' => 'E-mail cím (a regisztrációnál megadott)'
		            );
			echo form_input($data); ?>
	</div>
	
	<button type="submit" name="save" value="save" class="btn btn-default">Küldés</button>

</form>