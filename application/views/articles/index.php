<?php
	if (isset($search)) { //ekkor keresésben vagyunk
		$link = site_url(array('search', urlencode($search)));
	}
	elseif (isset($meta)) {  //ekkor címkét jelenítünk meg
		$link = site_url(array('meta', $meta['type_slug'], $meta['slug']));
	}
	elseif (isset($author)) {  //ekkor cikkszerzőhöz tartozó cikkeket jelenítünk meg
		$link = site_url(array('author', urlencode($author)));
	}
	else { //(isset($subcategory)) alkategória van beállítva
		$link = site_url($subcategory['slug']);
	}
?>
<ol class="breadcrumb">
	<li><a href="<?php echo site_url(); ?>">Főoldal</a></li>
	<?php if (isset($search)): ?>
		<li>Keresés erre: <?php echo $search; ?></li>
	<?php elseif (isset($meta)): ?>
		<li>Címke: <?php echo $meta['name']; ?></li>
	<?php elseif (isset($author)): ?>
		<li>Cikkszerző: <?php echo $author; ?></li>
	<?php else: // (isset($subcategory)): ?>
		<li class="active"><a href="<?php echo site_url($subcategory['slug']); ?>"><?php echo $subcategory['name']; ?></a></li>
	<?php endif; ?>
</ol>

<nav>
	<ul class="pager">
		<?php if($from != 0): //csak akkor legyen aktív a lapozó-gomb, ha van "vissza" még elem ?>
			<li class="previous">
		<?php else: ?>
			<li class="previous disabled">
		<?php endif; ?>
				<a href="<?php $prev = $from - $limit; 
							if ($prev < 0): //0 alá ne menjünk a lapozással
								echo $link;
							else: 
								echo $link . '/' . $prev;
							endif; ?>">
					<span aria-hidden="true">&larr;</span> Előző oldal
				</a>
			</li>

		<?php if($from+$limit < $cnt): //az összes elemszámot még nem érjük el ?>
			<li class="next">
				<a href="<?php echo $link . '/' . ($from + $limit); ?>">
		<?php else: // az összes elemszám fölé ne menjünk ?>
			<li class="next disabled">
				<a href="<?php echo $link . '/' . $from; ?>">
		<?php endif; ?>
					Következő oldal <span aria-hidden="true">&rarr;</span>
				</a>
			</li>
	</ul>
</nav>

<?php foreach ($articles as $ac): ?>
	<?php if($ac['login'] == 0 || $this->session->userdata('logged_in') === TRUE): ?>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">
					<a href="<?php echo $ac['link']; ?>">
						<?php echo $ac['title']; ?> <span aria-hidden="true">&rarr;</span>
					</a>
				</h3>
			</div>
			<div class="panel-body">
				<?php if($ac['image_path'] != NULL): ?>
					<a href="<?php echo $ac['link']; ?>">
						<?php if($ac['image_horizontal'] == 1): ?>
							<img src="<?php echo base_url(array('uploads', $ac['image_path'])); ?>" class="img-responsive img-article-list-horizontal" align="left" alt="Főkép">
						<?php else: ?>
							<img src="<?php echo base_url(array('uploads', $ac['image_path'])); ?>" class="img-responsive img-article-list" align="left" alt="Főkép">
						<?php endif; ?>
					</a>
				<?php endif; ?>
				<?php 
					$pos_p = strpos($ac['body'], '</p>');
					$pos_br = strpos($ac['body'], '<br />');
					if($pos_p != FALSE && $pos_br != FALSE) {
						if($pos_p < $pos_br)
							$pos = $pos_p;
						else
							$pos = $pos_br;
					}
					elseif($pos_p != FALSE)
						$pos = $pos_p;
					else
						$pos = $pos_br; 
					echo substr($ac['body'], 0, $pos); 
				?>
			</div>
			<div class="panel-footer">
				<?php echo $ac['pub_time'] . ' / Írta: ' . $ac['user_link']; ?>
			</div>
		</div>
	<?php endif; ?>
<?php endforeach ?>

<nav>
	<ul class="pager">
		<?php
			$db = ceil($cnt / $limit);
			$now = ceil($from / $limit) + 1;
			$start = $now - 3;
			$end = $now + 8;
			$prev = $from - $limit;
			
			if($from == 0)
				echo '<li class="disabled">';
			else
				echo '<li>';
			echo '<a href="' . $link . '">Elejére</a></li>';
			
			if($from != 0) //csak akkor legyen aktív a lapozó-gomb, ha van "vissza" még elem
				echo '<li>'; //<li class="previous">
			else
				echo '<li class="disabled">';
			if ($prev < 0) //0 alá ne menjünk a lapozással
				echo '<a href="' . $link . '">';
			else
				echo '<a href="' . $link . '/' . $prev . '">';
			echo '<span aria-hidden="true">&larr;</span> Előző oldal</a></li>';
			
			
			for($i = $start; $i <= $end && $i <= $db; ++$i) {
				if($i > 0) {
					if($i === $now)
						echo '<li class="disabled">';
					else
						echo '<li>';
					echo '<a href="' . $link . '/' . (($i - 1) * $limit) . '">' . $i . '</a></li>';
				}
			}
			
			if($from+$limit < $cnt) //az összes elemszámot még nem érjük el
				echo '<li><a href="' . $link . '/' . ($from + $limit) . '">';
			else // az összes elemszám fölé ne menjünk
				echo '<li class="disabled"><a href="' . $link . '/' . $from . '">';
			echo 'Következő oldal <span aria-hidden="true">&rarr;</span></a></li>';
			
			$endNum = ($db - 1) * $limit;
			if($endNum < 0)
				echo '<li class="disabled"><a href="' . $link . '/0">Végére</a></li>';
			else
			{
				if($endNum == $from)
					echo '<li class="disabled">';
				else
					echo '<li>'; //<li class="next">
				//megjegyzés a páratlan számosságú esetnél: $endNum != $cnt - $limit
				echo '<a href="' . $link . '/' . $endNum . '">Végére</a></li>';
			}
		?>
	</ul>
</nav>