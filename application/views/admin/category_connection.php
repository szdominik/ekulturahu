<?php if($this->session->userdata('level') >= 5): ?>

<?php foreach($categories as $cat) : ?>
	<div class="panel panel-default">
		<div class="panel-heading"><?php echo $cat['name']; ?></div>
		<div class="panel-body">
			<button type="button" data-toggle="modal" data-target="#newModal" data-catid="<?php echo $cat['id'];?>" class="newConn btn btn-primary">Új felvétele</button>
		</div>
		
		<p id="id-<?php echo $cat['id'];?>">
		</p>
		
	</div>
	
<?php endforeach; ?>

<div class="modal fade" id="newModal" tabindex="-1" aria-labelledby="" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" aria-labelledby="">Új összekapcsolás felvitele</h4>
			</div>
			<div class="modal-body">
				<?php 
						echo form_open('admin/category_list');
				?>
					<div class="form-group">
					    <div id="connCdd">
							<input type="hidden" name="category_id" value=""> 
							<select class="subcatlist form-control" name="subcategory">
							</select>
						</div>
					</div>
					<button type="submit" value="save" name="save" id="connSubmit" class="btn btn-primary">Mentés</button>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/cascading_dropdown/jquery.cascadingdropdown.js');?>"></script>
<script type="text/javascript">
	//új felvitel esetén berakjuk  a hidden mezőbe  a category_id-t.
	$('.newConn').click(function (e){
		var cat_id = $(e.currentTarget).data('catid');
		$('[name="category_id"]').val(cat_id);
	});

	//Új összekapcsolás mentése eseménykezelője
	$('#connSubmit').click(function (e){
		cat_id = $('[name="category_id"]').val();
		//AJAX POST kérés elindítása: a mentendő adatok elküldése
		$.post('<?php echo site_url(array('admin', 'category_conn_add')); ?>', 
		{
			category_id: cat_id,
			subcategory_id: $('.subcatlist option:selected').val()
		},function(result){ //ha sikerült menteni (200 OK)
			$('.subcatlist').val('ures').change();
			$('[name="category_id"]').val('');
			
	        conn_load(cat_id);
	    });
		e.preventDefault();
		$('#newModal').modal('hide')
	});

	//egy kategória összekapcsolások listájának újratöltése
	function conn_load(cat_id)
	{
		//AJAX GET kérés az összekapcsolások listájára
		$.get( '<?php echo site_url(array('admin', 'get_category_conns')); ?>' + '/' + cat_id, function(data) {
			s = '<div class="table-responsive"><table class="table table-hover">';
			for(var i = 0; i < data.length; ++i)
			{
				s += '<tr>';
					s += '<td>' + data[i].name + ' (összesen: ' + data[i].cnt + ' cikk tartozik hozzá) </td>';
					if (data[i].cnt == 0)
					{
						href = '<?php echo site_url(array('admin', 'category_conn_delete')); ?>' + '/' + data[i].cid + '/' + data[i].scid;
						s += '<td><button type="button" class="deleteConn btn btn-danger" data-catid="' + cat_id + '" data-delhref="' + href + '" role="button">Törlés</button></td>';
					}
					else
					{
						s += '<td>Nem törölhető!</td>';
					}
				s += '</tr>';
			}
			s += '</table></div>';
			$('#id-' + cat_id).html(s);
			conn_delete();
		}, "json");
	}
	
	//az összes összekapcsolás újratöltése
	function all_conn_load()
	{
		<?php foreach($categories as $cat) : ?>
			conn_load(<?php echo $cat['id']; ?>);
		<?php endforeach; ?>
	}
	
	//összekapcsolás törlésekor lefuttatandó esemény (a törlés ajaxon keresztül menjen)
	function conn_delete()
	{
		$('.deleteConn').click(function (e){
			var curr = $(e.currentTarget)
			var delhref = curr.data('delhref');
			$.ajax({ //AJAX kérés
				url: delhref,
				success: function(result, status, xhr){
					conn_load(curr.data('catid'));
				}
			});
		});
	}
	
	$(document).ready(function () {
		all_conn_load();
		
		$('#connCdd').cascadingDropdown({ //az összekapcsolások mentési mezője AJAX-on keresztül töltődjön mindig újra
			valueKey: 'id',
			textKey: 'name',
		    selectBoxes: [
		        {
		            selector: '.subcatlist',
					source: function(request, response) {
						//metaadat típusok lekérése
						$.getJSON('<?php echo site_url(array('admin', 'get_subcategories')); ?>', request, function(data) {
					        response($.map(data, function(item, index) {
					            return {
					                label: item.name,
					                value: item.id
					            }
					        }));
					    });
					},
					selected: 'ures'
		        }
		    ]
		});
	});
</script>

<?php endif; ?>