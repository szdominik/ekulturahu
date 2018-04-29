<!DOCTYPE html>
<html lang='hu'>
<head>
	<title><?php echo $title ?> - ekultura.hu</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="title" content="ekultura.hu">
	<meta name="description" content="Online kulturális magazin - könyvek, filmek, zenék, színház.">
	<meta name="keywords" content="ekultura, könyv, film, zene, koncert, színház, hallgatnivaló, olvasnivaló, látnivaló, beszámoló, interjú, ajánló, életrajz, hír">
	<link type="text/css" href="<?php echo base_url('assets/bootstrap/css/bootstrap-cosmo.css');?>" rel="stylesheet">
	<link type="text/css" href="<?php echo base_url('css/style.css');?>" rel="stylesheet">
	<script type="text/javascript" src="<?php echo base_url('assets/jquery.js');?>"></script>
	<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js');?>"></script>
</head>
<body>

<nav class="navbar navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#collapseMenu">
			</button>
			<a class="navbar-brand" href="<?php echo site_url(); ?>">ekultura.hu</a>
    </div>

    <div class="collapse navbar-collapse" id="collapseMenu">
			<ul class="nav navbar-nav">
				<?php foreach ($subcategories as $sc): ?>
					<li><a href="<?php echo site_url($sc['slug']); ?>"><?php echo $sc['name']; ?></a></li>
				<?php endforeach; ?>
			</ul>
	  
			<ul class="nav navbar-nav navbar-right">
				<?php if($this->session->userdata('logged_in') === TRUE): ?> 
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Adminisztráció <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<?php if($this->session->userdata('level') >= 3): ?>
								<li><a href="<?php echo site_url(array('admin', 'article_new')); ?>">Új cikk felvitele</a></li>
								<li><a href="<?php echo site_url(array('admin', 'article_list')); ?>">Cikkek szerkesztése</a></li>
								<li class="divider"></li>
								<?php if($this->session->userdata('level') >= 4): ?>
									<li><a href="<?php echo site_url(array('admin', 'meta_list')); ?>">Címkék kezelése</a></li>
									<li><a href="<?php echo site_url(array('admin', 'static_list')); ?>">Statikus cikkek kezelése</a></li>
									<?php if($this->session->userdata('level') == 5): ?>
										<li><a href="<?php echo site_url(array('admin', 'user_list')); ?>">Felhasználók kezelése</a></li>
										<li><a href="<?php echo site_url(array('admin', 'category_list')); ?>">Kategóriák kezelése</a></li>
									<?php endif; ?>
								<?php endif; ?>
							<?php endif; ?>
							<li><a href="<?php echo site_url(array('admin', 'comment_list')); ?>">Hozzászólások kezelése</a></li>
						</ul>
					</li>
					<li><a href="<?php echo site_url(array('users', 'logout')); ?>" role="button">Kilépés</a></li>
				<?php endif; ?>
			</ul>
    </div>
  </div> <!--container-fluid -->
</nav>

<div class="search-container container-fluid">
  <?php echo form_open('search', array('role' => 'search'));
    echo form_input(array(
      'name'        => 'search',
      'maxlength'   => '200',
      'class'		    => 'form-control',
      'id'			    => 'search-field',
      'placeholder' => 'Mit keresel?'
    )); ?>
    <span class="search-icon">
      <img src="<?php echo base_url('assets/icon.png');?>" alt="search icon" />
    </span>
  </form>
</div>

<div class="container-fluid">
