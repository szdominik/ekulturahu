<?php if($this->session->userdata('level') >= 3):
		$size = 50;
		$attributes = array('class' => 'form-horizontal');
		echo form_open_multipart('admin/article_new', $attributes);
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
			              'maxlength'   => '200',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Cím'
			            );
				echo form_input($data); ?>
		</div>
	</div>

	<div class = "form-group">
		<label for = "category" class="col-md-2 control-label">Főkategória</label>
		<div class="col-md-6">
			<?php
				$selected = array();
				foreach($categories as $id=>$cat): //volt-e kiválasztva főkategória
					if(set_select('category', $id) != ''): $selected[] = $id; endif;
				endforeach;
				if(empty($selected)): $selected = array('ures'); endif;
				echo form_dropdown('category', $categories, $selected, 'class="form-control"');
			?>
		</div>
	</div>

	<div class = "form-group">
		<label for = "subcategory" class="col-md-2 control-label">Alkategória</label>
		<div class="col-md-6">
			<?php
				$selected = array();
				foreach($subcategories as $id=>$sc): //volt-e kiválasztva alkategória
					if(set_select('subcategory', $id) != ''): $selected[] = $id; endif;
				endforeach;
				if(empty($selected)): $selected = array('ures'); endif;
				echo form_dropdown('subcategory', $subcategories, $selected, 'class="form-control"');
			?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-offset-2 col-xs-offset-1 col-md-6 col-xs-8">
			<div class="checkbox">
				<?php
					if($this->session->userdata('level') <= 3): //3-as szintű fejlhasználó nem publikálhat
						echo form_hidden('published', '0');
					else: //a többiek mások nevében is
						echo '<label>';
						echo form_checkbox('published', '1', set_checkbox('published','1', FALSE));
						echo 'Publikálva';
						echo '</label><br>';
					endif;
					echo form_hidden('comment', '1');
					echo form_hidden('login', '0');
				?>
				<label>
					<?php echo form_checkbox('mainpage', '1', set_checkbox('mainpage','1', TRUE)); ?> Főoldalra kerül
			    </label>
			</div>
		</div>
	</div>
	
	<div class = "form-group">
		<label for = "date" class="col-md-2 control-label">Dátum</label>
		<div class="col-md-6">
			<div class="input-group date" id="datetimeinput">
				<?php $data = array(
			              'name'        => 'pub_time',
			              'value'       => set_value('pub_time'),
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
		<label for = "user" class="col-md-2 control-label">Cikkszerző</label>
		<div class="col-md-6">
			<?php
				if($this->session->userdata('level') <= 3): //3-as szintű fejlhasználó csak a saját nevében írhat
					echo '<p class="form-control-static">'.$this->session->userdata('name').'</p>';
					echo form_hidden('user', $this->session->userdata('id'));
				else: //a többiek mások nevében is
					$selected = array();
					foreach($writers as $id=>$wr):
						if(set_select('user', $id) != ''): $selected[] = $id; endif;
					endforeach;
					if(empty($selected)): $selected = $this->session->userdata('id'); endif;
					echo form_dropdown('user', $writers, $selected, 'class="form-control"'); 
				endif;
			?>
		</div>
	</div>

	<div class = "form-group">
		<label for = "kedv_vasar" class="col-md-2 control-label">Kedvezményes vásárlás</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'kedv_vasar',
			              'value'       => set_value('kedv_vasar'),
			              'maxlength'   => '255',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Kedvezményes vásárlás (nem szükséges)'
			            );
				echo form_input($data); ?>
		</div>
	</div>

	<div class = "form-group">
		<label for = "eredeti_cim" class="col-md-2 control-label">Eredeti cím</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'eredeti_cim',
			              'value'       => set_value('eredeti_cim'),
			              'maxlength'   => '100',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Eredeti cím'
			            );
				echo form_input($data); ?>
		</div>
	</div>

	<div class = "form-group">
		<label for = "ar" class="col-md-2 control-label">Ár</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'ar',
			              'value'       => set_value('ar'),
			              'maxlength'   => '100',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Ár (pl. 2990 Ft)'
			            );
				echo form_input($data); ?>
		</div>
	</div>

	<div class = "form-group">
		<label for = "terjedelem" class="col-md-2 control-label">Terjedelem (hossz / oldalszám)</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'terjedelem',
			              'value'       => set_value('terjedelem'),
			              'maxlength'   => '100',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Hossz (pl. 141 perc) vagy oldalszám (pl. 350)'
			            );
				echo form_input($data); ?>
		</div>
	</div>

	<div class = "form-group">
		<label for = "forgatokonyviro" class="col-md-2 control-label">Forgatókönyvíró</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'forgatokonyviro',
			              'value'       => set_value('forgatokonyviro'),
			              'maxlength'   => '250',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Forgatókönyvíró'
			            );
				echo form_input($data); ?>
		</div>
	</div>
	
	<div class = "form-group">
		<label for = "operator" class="col-md-2 control-label">Operatőr</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'operator',
			              'value'       => set_value('operator'),
			              'maxlength'   => '250',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Operatőr'
			            );
				echo form_input($data); ?>
		</div>
	</div>

	<div class = "form-group">
		<label for = "producer" class="col-md-2 control-label">Producer</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'producer',
			              'value'       => set_value('producer'),
			              'maxlength'   => '250',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Producer'
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
	
	<div class = "form-group">
		<label for = "userfile" class="col-md-2 control-label">Kép</label>
		<div class="col-md-6">
			<?php
				if($image_upload === NULL): echo form_upload('userfile');
				else: echo form_hidden('userfile', $image_upload) . $image_upload;
				endif; ?>
		</div>
	</div>
	
	<div class="form-group">
		<label for = "image_horizontal" class="col-md-2 control-label">Kép tájolása</label>
		<div class="col-md-6 col-xs-8">
			<div class="checkbox">
				<label>
					<?php echo form_checkbox('image_horizontal', '1', set_checkbox('image_horizontal','1', FALSE)); ?> Fekvő kép
			    </label>
			</div>
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
		$('#datetimeinput').datetimepicker({ //dátumválasztó betöltése
			locale: 'hu',
		});
	});
</script>

<?php endif ?>