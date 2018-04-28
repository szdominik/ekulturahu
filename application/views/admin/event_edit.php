<?php if($this->session->userdata('level') >= 2):
		$size = 50;
		$attributes = array('class' => 'form-horizontal');
		$hiddens = array('id' => $event['id']);
		echo form_open_multipart('admin/event_edit/'.$event['id'], $attributes, $hiddens);
?>

	<?php if (isset($wasdata) && $wasdata === TRUE && isset($success) && $success === FALSE): ?>
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
		<label for = "title" class="col-md-2 control-label">Név</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'title',
			              'value'       => $event['title'],
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
			              'value'       => $event['location'],
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
			              'value'       => $event['begin'],
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
			              'value'       => $event['end'],
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
			              'value'       => $event['body'],
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
			<?php if(isset($event['image_id']) && $event['image_id'] != 0): ?>
				<a class="btn btn-warning" href="<?php echo site_url(array('admin', 'image_delete', $event['image_id'], $event['id'], 1)); ?>" role="button">
					Törlés
				</a>
				<?php echo form_hidden('userfile', $event['image_id']); ?>
				<p>
					<img src="<?php echo base_url(array('uploads', $event['image_name'])); ?>" class="img-responsive" width="200" alt="Beszúrt főkép">
				</p>
			<?php else:
					echo form_upload('userfile'); 
				  endif; ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-offset-2 col-md-6">
			<button type="submit" value="save" name="save" class="btn btn-default">
				Mentés
			</button>
			<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
				Esemény törlése
			</button>
		</div>
	</div>

</form>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" aria-labelledby="">Esemény törlése</h4>
			</div>
			<div class="modal-body">
				Biztos, hogy törli a kiválasztott eseményt?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
				<a href="<?php echo site_url(array('admin', 'event_delete', $event['id'])); ?>" class="btn btn-danger">Törlés</a>
			</div>
		</div>
	</div>
</div>

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