<?php if($this->session->userdata('level') >= 3):
		$attributes = array('class' => 'form-inline');
		echo form_open('admin/article_list', $attributes);
?>
	<!-- szűrési opciók -->
	<div class = "form-group">
		<label for = "subcategory" class="sr-only">Alkategória</label>
		<?php
			echo form_dropdown('subcategory', $subcategories, $subcategory, 'class="form-control"');
		?>
		
		<label for = "user" class="sr-only">Cikkszerző</label>
		<?php //3-as szintű felhasználó csak saját nevében írhat
			if($this->session->userdata('level') == 3):
				echo $this->session->userdata('name');
			else: // a többiek mások nevében is, így szűrhetnek is másokra 
				echo form_dropdown('user', $writers, $user, 'class="form-control"'); 
			endif;
		?>

		<label for = "published_cat" class="sr-only">Publikáció</label>
		<?php
			echo form_dropdown('published_cat', $published_cats, $published_cat, 'class="form-control"');
		?>

		<label for = "limit" class="sr-only">Találatok oldalanként</label>
		<input type="number" name="limit" class="form-control" value="<?php if($limit == 50): echo ''; else: echo $limit; endif; ?>" placeholder="Találatok oldalanként" step="100" max="10000">
		
		<label for = "filter" class="sr-only">A címben szerepel</label>
		<?php 	if($title == '0'):
					echo form_input('title', '', 'class="form-control" placeholder="Szűrés címre"');
				else:
					echo form_input('title', $title, 'class="form-control" placeholder="Szűrés címre"');
				endif;  ?>
	</div>
	<button type="submit" value="filter" name="save" class="btn btn-default">Szűrés</button>

</form>
	
<nav>
	<ul class="pager">
		<?php if($from != 0): ?> <!--csak akkor aktív, ha nem 0-nál állunk (visszafele nem mehetünk) -->
			<li class="previous">
		<?php else: ?>
			<li class="previous disabled">
		<?php endif; ?>
				<a href="<?php $prev = $from - $limit; 
							if ($prev < 0): //negatív tartományba ne lapozzunk
								echo site_url(array('admin', 'article_list', urlencode($title), $category, $subcategory, $published_cat, $user, $limit, '0')); 
							else: 
								echo site_url(array('admin', 'article_list', urlencode($title), $category, $subcategory, $published_cat, $user, $limit, $prev)); 
							endif; ?>">
					<span aria-hidden="true">&larr;</span> Előző oldal
				</a>
			</li>
		
		<?php if($from+$limit < $cnt): ?> <!--csak akkor lapozhatunk tovább, ha nem megyünk túl az összes lehetőségen -->
			<li class="next">
				<a href="<?php echo site_url(array('admin', 'article_list', urlencode($title), $category, $subcategory, $published_cat, $user, $limit, $from + $limit)); ?>">
		<?php else: ?>
			<li class="next disabled">
				<a href="<?php echo site_url(array('admin', 'article_list', urlencode($title), $category, $subcategory, $published_cat, $user, $limit, $from)); ?>">
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
				<th>Cikk címe</th>
				<th>Cikkíró</th>
				<th>Publikálás dátuma</th>
				<th>Szerkesztés</th>
				<?php if($this->session->userdata('level') >= 4) { echo '<th>Törlés</th>'; }?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($articles as $ac): ?>
				<tr>
				    <td><?php echo $ac['title']; ?></td>
					<td><?php echo $ac['user_link']; ?></td>
					<td><?php echo $ac['pub_time']; ?></td>
				    <td><a href="<?php echo site_url(array('admin', 'article_edit', $ac['id'])); ?>" class="btn btn-default">Szerkesztés</a></td>
					<?php if($this->session->userdata('level') >= 4) {
						echo '<td><button type="button" class="deleteArticle btn btn-danger" data-toggle="modal" data-target="#deleteModal" data-delhref="'
							. site_url(array('admin', 'article_delete', $ac['id']))
							. '">Törlés</button></td>';
					} ?>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>

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
				<a href="" name="todelete" class="btn btn-danger">Törlés</a>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<script type="text/javascript">
	//a cikktörlés eseménykezelője - mindig az adott cikket tudjuk törölni, ehhez kérjük le a megfelelő címet a data attribútumból
	$('.deleteArticle').click(function (e){
		var delhref = $(e.currentTarget).data('delhref');
		$('a[name="todelete"]').attr("href", delhref);
	});
</script>