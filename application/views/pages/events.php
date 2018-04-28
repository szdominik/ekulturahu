<div id="calendar"></div>

<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" aria-labelledby="">Esemény</h4>
			</div>
			<div class="modal-body">
				<p align="center"><img src="" name="event_image" class="img-event" alt="Esemény képe"></p>
				<h5>Kezdő időpont: <span name="event_start"></span></h5>
				<h5>Befejező időpont: <span name="event_end"></span></h5>
				<h5>Helyszín: <span name="event_location"></span></h5>
				<div name="event_body"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Vissza</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/fullcalendar/fullcalendar.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/fullcalendar/hu.js');?>"></script>

<script type="text/javascript">
	<link type="text/css" href="<?php echo base_url('assets/fullcalendar/fullcalendar.min.css');?>" rel="stylesheet">
	$(document).ready(function () {
		 $('#calendar').fullCalendar({ //a naptár létrehozása
			events: [
				<?php foreach ($events as $ev): ?> //események, amelyek az adatbázisból érkeznek
			        {
						id: '<?php echo $ev['id']; ?>',
			            title: '<?php echo $ev['title']; ?>',
						start: '<?php echo $ev['begin']?>',
						end:   '<?php echo $ev['end']?>'
			        },
				<?php endforeach; ?>
			],
			eventClick: function(calEvent, jsEvent, view) { //kattintás eseménykezelője
				eventid = calEvent.id;
				$.get( '<?php echo site_url(array('pages', 'get_event')); ?>' + '/' + eventid, function(data) {
				//AJAX GET kérés: az adott event id-vel lekérjük az adatokat az adatbázisból
					$('.modal-title').html(data.title);
					$('[name="event_start"]').html(data.begin);
					$('[name="event_end"]').html(data.end);
					$('[name="event_location"]').html(data.location);
					$('[name="event_body"]').html(data.body);
					//Ha nincs hozzá kép, akkor a megfelelő html rész elrejtése
					if(data.image_id == 0 || data.image_id == null)
					{
						$('[name="event_image"]').hide();
					}
					else //ha van kép: jelenjen meg a kép
					{
						$('[name="event_image"]').attr("src",'<?php echo base_url('uploads'); ?>' + '/' + data.image_name);
						$('[name="event_image"]').show();
					}
					$('#eventModal').modal('show');
				}, 'json');
			}
		})
	});
</script>