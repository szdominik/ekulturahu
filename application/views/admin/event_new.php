<?php if($this->session->userdata('level') >= 2):
		$size = 50;
		$attributes = array('class' => 'form-horizontal');
		echo form_open_multipart('admin/event_new', $attributes);
?>

	<?php if (isset($wasdata) && $wasdata === TRUE): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo validation_errors();	?>
		</div>
	<?php endif; ?>
	
	<div class = "form-group">
		<label for = "title" class="col-md-2 control-label">Név</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'title',
			              'value'       => set_value('title'),
			              'maxlength'   => '250',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Az esemény neve'
			            );
				echo form_input($data); ?>
		</div>
	</div>

	<div class = "form-group">
		<label for = "location" class="col-md-2 control-label">Helyszín</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'location',
			              'value'       => set_value('location'),
			              'maxlength'   => '250',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Az esemény helyszíne'
			            );
				echo form_input($data); ?>
		</div>
	</div>
	
	<div class = "form-group">
		<label for = "begin" class="col-md-2 control-label">Kezdő időpont</label>
		<div class="col-md-6">
			<div class="input-group date" id="datetimeinput">
				<?php $data = array(
			              'name'        => 'begin',
			              'value'       => set_value('begin'),
						  'class'		=> 'form-control',
			            );
						echo form_input($data); ?>
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</span>
			</div>
		</div>
	</div>
	
	<div class = "form-group">
		<label for = "end" class="col-md-2 control-label">Befejező időpont</label>
		<div class="col-md-6">
			<div class="input-group date" id="datetimeinput2">
				<?php $data = array(
			              'name'        => 'end',
			              'value'       => set_value('end'),
						  'class'		=> 'form-control',
			            );
						echo form_input($data); ?>
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</span>
			</div>
		</div>
	</div>
	
	<div class = "form-group">
		<label for = "body" class="col-md-2 control-label">Leírás</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'body',
			              'value'       => set_value('body'),
			              'maxlength'   => '65000',
						  'class'		=> 'form-control',
						  'id'			=> 'editor',
						  'placeholder' => 'Az esemény leírása'
			            );
				echo form_textarea($data); ?>
		</div>
	</div>

	<div class = "form-group">
		<label for = "userfile" class="col-md-2 control-label">Kép</label>
		<div class="col-md-6">
			<?php echo form_upload('userfile'); ?>
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

<script type="text/javascript">
	$(document).ready(function () {
		//dátumválasztók betöltése
		$('#datetimeinput').datetimepicker({
			locale: 'hu',
		});
		
		$('#datetimeinput2').datetimepicker({
			locale: 'hu',
		});
	});
</script>

<?php endif ?>