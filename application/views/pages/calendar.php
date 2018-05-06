<div class="container-fluid">
<blockquote>
	<p><?php echo $quote->quote; ?></p>
	<small><?php echo $quote->author; ?></small>
</blockquote>

<h3>Napi évfordulók - <?php setlocale(LC_TIME, 'hungarian'); echo  utf8_encode(strftime("%B %d.")); ?></h3>

<div class="col-md-5">
	<ul class="nav nav-tabs">
		<li class="active"><a aria-expanded="true" href="#birth" data-toggle="tab">Megszületett</a></li>
		<li class=""><a aria-expanded="false" href="#death" data-toggle="tab">Meghalt</a></li>
		<?php if(count($else) > 0): ?>
			<li class=""><a aria-expanded="false" href="#else" data-toggle="tab">
		<?php else: ?>
			<li class="disabled"><a>
		<?php endif; ?>
		Egyéb esemény</a></li>
	</ul>
	<div id="calendarContent" class="tab-content">
		<div class="tab-pane fade active in" id="birth">
			<ul class="list-group">
			<?php foreach($birth as $b) : ?>
				<li class="list-group-item"><?php echo $b['year'] . ' - ' . $b['who']; ?></li>
			<?php endforeach; ?>
			</ul>
		</div>
		<div class="tab-pane fade" id="death">
			<ul class="list-group">
			<?php foreach($death as $d) : ?>
				<li class="list-group-item"><?php echo $d['year'] . ' - ' . $d['who']; ?></li>
			<?php endforeach; ?>
			</ul>
		</div>
		<div class="tab-pane fade" id="else">
			<ul class="list-group">
			<?php foreach($else as $e) : ?>
				<li class="list-group-item"><?php echo $e['year'] . ' - ' . $e['who']; ?></li>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
</div>