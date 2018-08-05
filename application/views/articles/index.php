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
	elseif (isset($subcategory)) { // alkategória van beállítva
		$link = site_url($subcategory['slug']);
	}
	else { 
		$link = site_url($category['slug']);
	}
?>
<h1>
	<?php if (isset($search)): ?>
		Keresés erre: <?php echo $search; ?>
	<?php elseif (isset($meta)): ?>
		Címke: <?php echo $meta['name']; ?>
	<?php elseif (isset($author)): ?>
		Cikkszerző: <?php echo $author; ?>
	<?php elseif (isset($subcategory)): ?>
		<?php echo $subcategory['name']; ?>
	<?php else: ?>
		<?php echo $category['name']; ?>
	<?php endif; ?>
</h1>

<?php require(dirname(__FILE__) . '/../pages/home.php'); ?>