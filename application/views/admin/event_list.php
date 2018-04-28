<?php if($this->session->userdata('level') >= 2): ?>

	<?php
			$attributes = array('class' => 'form-inline');
			echo form_open('admin/event_list', $attributes);
	?>

	<?php if (isset($success) && $success === TRUE): ?>
		<div class="alert alert-success" role="alert">
			Sikeres mentés!
		</div>
	<?php endif; ?>
	
	<!--Szűrési lehetőségek -->
	<div class="form-group">
		<div class="input-group date" id="datetimeinput">
			<?php 	if($bgn == '0'): $bgn = ''; endif;
					$data = array(
					  'name'        => 'filter_begin',
					  'value'       => $bgn,
					  'class'		=> 'form-control',
					  'placeholder' => 'Kezdő időpont'
					);
					echo form_input($data);
					if($bgn == ''): $bgn = '0'; endif; ?>
			<span class="input-group-addon">
				<span class="glyphicon glyphicon-calendar"></span>
			</span>
		</div>
		<div class="input-group date" id="datetimeinput2">
			<?php 	if($end == '0'): $end = ''; endif;
					$data = array(
					  'name'        => 'filter_end',
					  'value'       => $end,
					  'class'		=> 'form-control',
					  'placeholder' => 'Befejező időpont'
					);
					echo form_input($data);
					if($end == ''): $end = '0'; endif; ?>
			<span class="input-group-addon">
				<span class="glyphicon glyphicon-calendar"></span>
			</span>
		</div>
	</div>
	<button type="submit" value="filter" name="save" class="btn btn-default">Szűrés</button>
	<a href="<?php echo site_url(array('admin', 'event_new')); ?>" class="btn btn-primary">Új esemény létrehozása</a>

<nav>
	<ul class="pager">
		<?php if($from != 0): ?> <!--csak akkor aktív, ha nem 0-nál állunk (visszafele nem mehetünk) -->
			<li class="previous">
		<?php else: ?>
			<li class="previous disabled">
		<?php endif; ?>
				<a href="<?php $prev = $from - $limit; 
							if ($prev < 0):  //negatív tartományba ne lapozzunk
								echo site_url(array('admin', 'event_list', urlencode($bgn), urlencode($end), '0')); 
							else: 
								echo site_url(array('admin', 'event_list', urlencode($bgn), urlencode($end), $prev)); 
							endif; ?>">
					<span aria-hidden="true">&larr;</span> Előző oldal
				</a>
			</li>
		
		<?php if($from+$limit < $cnt): ?> <!--csak akkor lapozhatunk tovább, ha nem megyünk túl az összes lehetőségen -->
			<li class="next">
				<a href="<?php echo site_url(array('admin', 'event_list', urlencode($bgn), urlencode($end), $from + $limit)); ?>">
		<?php else: ?>
			<li class="next disabled">
				<a href="<?php echo site_url(array('admin', 'event_list', urlencode($bgn), urlencode($end), $from)); ?>">
		<?php endif; ?>
					Következő oldal <span aria-hidden="true">&rarr;</span>
				</a>
			</li>
	</ul>
</nav>

<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Név</th>
				<th>Helyszín</th>
				<th>Kezdő időpont</th>
				<th>Befejező időpont</th>
				<th>Szerkesztés</th>
				<th>Törlés</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($events as $ev): ?>
				<tr>
				    <td><?php echo $ev['title'] ?></td>
					<td><?php echo $ev['location'] ?></td>
					<td><?php echo $ev['begin'] ?></td>
					<td><?php echo $ev['end'] ?></td>
				    <td><a href="<?php echo site_url(array('admin', 'event_edit', $ev['id'])); ?>" class="btn btn-default">Szerkesztés</a></td>
					<td><button type="button" class="deleteEvent btn btn-danger" data-toggle="modal" data-target="#deleteModal" data-delhref="<?php echo site_url(array('admin', 'event_delete', $ev['id'])); ?>">Törlés</button></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>

<?php endif; ?>

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
				<a href="" name="todelete" class="btn btn-danger">Törlés</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		//a dátumválasztók betöltése
		$('#datetimeinput').datetimepicker({
			locale: 'hu',
		});
		
		$('#datetimeinput2').datetimepicker({
			locale: 'hu',
		});
	});
	//a megfelelő esemény törléséhez szükséges eseménykezelő (a link lekérése a data-attribútumból)
	$('.deleteEvent').click(function (e){
		var delhref = $(e.currentTarget).data('delhref');
		$('a[name="todelete"]').attr("href", delhref);
	});
</script>