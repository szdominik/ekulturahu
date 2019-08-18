<?php 	if($this->session->userdata('level') >= 3):
			$size = 50;
			$hiddens = array('id' => $article['id'], 'slug' => $article['slug']);
			$attributes = array('class' => 'form-horizontal');
			echo form_open_multipart('admin/article_edit/'.$article['id'], $attributes, $hiddens);
?>
	
	<?php if (isset($wasdata) && $wasdata === TRUE && $success == FALSE): ?>
		<div class="alert alert-danger" role="alert">
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
			              'value'       => $article['title'],
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
			<?php echo form_dropdown('category', $categories, $article['category_id'], 'class="form-control"'); ?>
		</div>
	</div>

	<div class = "form-group">
		<label for = "subcategory" class="col-md-2 control-label">Alkategória</label>
		<div class="col-md-6">
			<?php echo form_dropdown('subcategory', $subcategories, $article['subcategory_id'], 'class="form-control"'); ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-offset-2 col-xs-offset-1 col-md-6">
			<div class="checkbox">
				<?php
					if($this->session->userdata('level') <= 3): //3-as szintű fejlhasználó nem publikálhat
						echo form_hidden('published', $article['published']);
					else: //a többiek mások nevében is
						echo '<label>';
						echo form_checkbox('published', '1', $article['published']);
						echo 'Publikálva';
						echo '</label><br>';
					endif;
					echo form_hidden('comment', $article['comment']);
					echo form_hidden('login', $article['login']);
				?>
				<label>
					<?php echo form_checkbox('mainpage', '1', $article['mainpage']); ?> Főoldalra kerül
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
			              'value'       => $article['pub_time'],
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
				if($this->session->userdata('level') <= 3): //3-as szintű felhasználó csak saját nevében írhat
					echo '<p class="form-control-static">'.$this->session->userdata('name').'</p>';
					echo form_hidden('user', $this->session->userdata('id'));
				else:
					echo form_dropdown('user', $writers, $article['user_id'], 'class="form-control"');
				endif;
			?>
		</div>
	</div>

	<div class = "form-group">
		<label for = "kedv_vasar" class="col-md-2 control-label">Kedvezményes vásárlás</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'kedv_vasar',
			              'value'       => $article['kedv_vasar'],
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
			              'value'       => $article['eredeti_cim'],
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
			              'value'       => $article['ar'],
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
			              'value'       => $article['terjedelem'],
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
			              'value'       => $article['forgatokonyviro'],
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
			              'value'       => $article['operator'],
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
			              'value'       => $article['producer'],
			              'maxlength'   => '250',
			              'size'        => $size,
						  'class'		=> 'form-control',
						  'placeholder' => 'Producer'
			            );
				echo form_input($data); ?>
		</div>
	</div>

	
	<div class = "form-group">
		<label for = "metavalues" class="col-md-2 control-label">Címkék</label>
		<div class="col-md-6">
			<p id="metaError">
			</p>
			<p id="metaList">
			</p>

			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#metaEditModal">
				Új címke felvitele
			</button>
		</div>
	</div>
	
	<div class = "form-group">
		<label for = "body" class="col-md-2 control-label">Főszöveg</label>
		<div class="col-md-6">
			<?php $data = array(
			              'name'        => 'body',
			              'value'       => $article['body'],
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
			<?php if(isset($article['image_path']) && $article['image_path'] != NULL): ?> <!-- ha van kép, jelenítsük meg -->
				<a class="btn btn-warning" href="<?php echo site_url(array('admin', 'image_delete', $article['image_path'], $article['id'])); ?>" role="button">
					Törlés
				</a>
				<?php echo form_hidden('userfile', $article['image_path']); ?>
				<p>
					<img src="<?php echo base_url(array('uploads', $article['image_path'])); ?>" class="img-responsive" width="200" alt="Beszúrt főkép">
				</p>
			<?php else:
					echo form_upload('userfile'); 
				  endif; ?>
		</div>
	</div>
	
	<div class="form-group">
		<label for = "image_horizontal" class="col-md-2 control-label">Kép tájolása</label>
		<div class="col-md-6 col-xs-8">
			<div class="checkbox">
				<label>
					<?php echo form_checkbox('image_horizontal', '1', $article['image_horizontal']); ?> Fekvő kép
			    </label>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-md-offset-2 col-md-5">
			<button type="submit" value="save" name="save" class="btn btn-default">
				Mentés
			</button>
			<?php if($this->session->userdata('level') >= 4) {
				echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">';
				echo 'Cikk törlése';
				echo '</button>';
			} ?>
			<a class="btn btn-primary" target="_blank" href="<?php echo $article['link']; ?>" role="button">
				Előnézet
			</a>
		</div>
		<div class="col-md-1">
			<a class="btn btn-primary" href="/admin/article_list" role="button">
				Vissza a cikkek listájához
			</a>
		</div>
	</div>
	
</form>
<?php endif; ?>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" aria-labelledby="">Cikk törlése</h4>
			</div>
			<div class="modal-body">
				Biztos, hogy törli a kiválasztott cikket?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
				<a href="<?php echo site_url(array('admin', 'article_delete', $article['id'])); ?>" class="btn btn-danger">Törlés</a>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="metaEditModal" tabindex="-1" aria-labelledby="" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php $hiddens = array('article_id' => $article['id']);
				echo form_open('admin/meta_edit_with_article/', '', $hiddens); ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" aria-labelledby="">
						Metaadatok szerkesztése
						<button type="button" class="btn btn-sm label label-warning label-tooltip" data-toggle="tooltip" data-placement="bottom"
							title="Megjegyzés: ez az ablak az ALT + C billentyűkombinációval is előhozható; az ALT + V + ArrowDown pedig segít a típus kiválasztásánál.">
							i
						</button>
					</h4>
				</div>
				<div class="modal-body">
					<div id="metaCdd">
						<select class="step1 form-control" name="meta_type">
						</select>
						<select class="step2 form-control" name="meta_data" size="10">
						</select>
						<?php $data = array(
					              'name'        => 'meta_value',
					              'value'       => set_value('meta_value'),
					              'maxlength'   => '100',
					              'size'        => $size,
								  'class'		=> 'form-control',
								  'placeholder' => 'Ha a listában nem található.'
					            );
						echo form_input($data); ?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" value="save" name="save" id="metaSubmit" class="btn btn-primary">Mentés</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/cascading_dropdown/jquery.cascadingdropdown.js');?>"></script>
<script type="text/javascript">
	const metaSubmitHandler = (e) => {
		// AJAX POST kérés elindítása: a mentendő adatok elküldése
		$.post('<?php echo site_url(array('admin', 'meta_edit_with_article')); ?>', 
		{
			article_id: <?php echo $article['id']; ?>,
			meta_type: $('.step1 option:selected').val(),
			meta_data: $('.step2 option:selected').val(),
			meta_value: $('[name="meta_value"]').val()
		}, (result) => { // ha sikerült menteni (200 OK)
			$('[name="meta_value"]').val('');
			
			if(result != '') // ha a szerver írt ki errort, akkor jelenítsük meg
			{
				s = '<div class="alert alert-danger" role="alert">'
				s += result;
				s += '</div>';
				$('#metaError').html(s);
			}
			else
			{
				$('#metaError').html('');
			}
	        meta_load();
	    });
		e.preventDefault();
		$('#metaEditModal').modal('hide');
	};

	// Metaadat mentése eseménykezelője
	$('#metaSubmit').click(metaSubmitHandler);
	$('.step2').dblclick(metaSubmitHandler);
	
	//a meták listájának újratöltése
	const meta_load = () => {
		//AJAX GET kérés a meták listájára
		$.get( '<?php echo site_url(array('admin', 'get_metas_by_article', $article['id'])); ?>', function(data) {
			s = '<div class="table-responsive"><table class="table table-condensed">';
			for(var i = 0; i < data.length; ++i)
			{
				s += '<tr>';
					s += '<td>' + data[i].type_name + '</td>';
					s += '<td>' + data[i].name + '</td>';
					href = '<?php echo site_url(array('admin', 'meta_article_delete')); ?>' + '/' + data[i].meta_id;
					s += '<td><button type="button" class="deleteMeta btn btn-warning btn-sm" data-delhref="' + href + '" role="button">Törlés</button></td>';
				s += '</tr>';
			}
			s += '</table></div>';
			$('#metaList').html(s);
			meta_delete();
		}, "json");
	};
	
	//meták törlésekor lefuttatandó esemény (a törlés ajaxon keresztül menjen)
	meta_delete = () => {
		$('.deleteMeta').click(function (e){
			var delhref = $(e.currentTarget).data('delhref');
			$.ajax({ //AJAX kérés
				url: delhref,
				success: function(result, status, xhr){
					meta_load();
				}
			});
		});
	};
	
	$(document).ready(() => {
		meta_load(); //töltődjön be a meták listája is
		$('[data-toggle="tooltip"]').tooltip(); 

		document.addEventListener('keypress', (e) => {
			if (e.shiftKey && e.code === 'KeyC') { // shift + c
				$('#metaEditModal').modal('show');
			} else if (e.shiftKey && e.code === 'KeyV') { // shift + v
				$('.step1').focus();
			}
		});
		
		$('#datetimeinput').datetimepicker({ //dátumválasztó betöltése
			locale: 'hu',
		});
		
		$('#metaCdd').cascadingDropdown({ //a meták mentési mezője AJAX-on keresztül töltődjön mindig újra
			valueKey: 'id',
			textKey: 'name',
		    selectBoxes: [
		        {
		            selector: '.step1',
					source: (request, response) => {
						//metaadat típusok lekérése
						$.getJSON('<?php echo site_url(array('admin', 'get_metatypes')); ?>', request, (data) => {
					        response($.map(data, (item, index) => {
					            return {
					                label: item.name,
					                value: item.id
					            }
					        }));
					    });
					},
					selected: 'ures'
		        },
		        {
		            selector: '.step2',
		            requires: ['.step1'],
		            source: (request, response) => {
						val = $('.step1 option:selected').val();
						//adott típushoz tartozó metaadatok lekérése
						$.getJSON('<?php echo site_url(array('admin', 'get_metas_by_type')); ?>' + '/' + val, request, (data) => {
					        response($.map(data, (item, index) => {
					            return {
					                label: item.name,
					                value: item.id
					            }
					        }));
							$('.step2')[0].scrollTop = 0;
					    });
					}
				}
		    ]
		});
	});
</script>