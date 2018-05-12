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
  <link href="https://fonts.googleapis.com/css?family=Source+Serif+Pro&amp;subset=latin-ext" rel="stylesheet">
	<script type="text/javascript" src="<?php echo base_url('assets/jquery.js');?>"></script>
	<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js');?>"></script>
</head>
<body>

<nav class="header">
  <div class="header-main">
    <div class="header-icons">
      <a href="https://www.facebook.com/ekultura.hu/" target="_blank">
        <img src="<?php echo base_url('assets/icons/facebook.svg');?>" alt="facebook">
      </a>
      <a href="https://twitter.com/ekultura_hu" target="_blank">
        <img src="<?php echo base_url('assets/icons/twitter.svg');?>" alt="twitter">
      </a>
      <a href="https://plus.google.com/101205891015756708008/" target="_blank">
        <img src="<?php echo base_url('assets/icons/googleplus.svg');?>" alt="google+">
      </a>
      <a href="http://www.youtube.com/user/ekultura" target="_blank">
        <img src="<?php echo base_url('assets/icons/youtube.svg');?>" alt="youtube">
      </a>
      <a href="http://ekultura.hu/rss" target="_blank">
        <img src="<?php echo base_url('assets/icons/feed.svg');?>" alt="rss feed">
      </a>
    </div>
    <a href="<?php echo site_url(); ?>">
      <img src="<?php echo base_url('assets/icons/ekultura.png');?>" alt="ekultura.hu">
    </a>
    <div class="menu-buttons">
      <button type="button" class="hamburger-btn">
        <img src="<?php echo base_url('assets/icons/hamburger.svg');?>" alt="hamburger icon">
      </button>
      <span id="menu-text">Menü</span>
    </div>
  </div>

  <div class="navbar-links">
    <ul class="nav">
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
            <li class="divider"></li>
            <li><a href="<?php echo site_url(array('users', 'logout')); ?>" role="button">Kilépés</a></li>
          </ul>
        </li>
      <?php endif; ?>
    </ul>

    <?php foreach ($subcategories as $sc): ?>
      <a href="<?php echo site_url($sc['slug']); ?>"><p class="<?php echo $sc['slug']; ?>"><?php echo $sc['name']; ?></p></a>
    <?php endforeach; ?>
    <span class="divider"></span>
    <div class="other-menu">
      <?php foreach ($categories as $cat): ?>
      <a href="<?php echo site_url($cat['slug']); ?>"><p class="<?php echo $cat['slug']; ?>"><?php echo $cat['name']; ?></p></a>
      <?php endforeach; ?>
      <p class="divider"></p>
      <a href="<?php echo site_url('impresszum'); ?>"><p>Impresszum</p></a>
      <a href="<?php echo site_url('about'); ?>"><p>Rólunk</p></a>
    </div>
  </div>
</nav>

<div id="content-mask"></div>

<div class="search-container">
  <?php echo form_open('search', array('role' => 'search'));
    echo form_input(array(
      'name'        => 'search',
      'maxlength'   => '200',
      'class'		    => 'form-control',
      'id'			    => 'search-field',
      'placeholder' => 'Mit keresel?'
    )); ?>
    <span class="search-icon">
      <img src="<?php echo base_url('assets/icons/search.svg');?>" alt="search icon" />
    </span>
  </form>
</div>

<div class="body-content">
<?php
  if (strpos(current_url(), 'admin/') != FALSE) {
    echo '<div class="container-fluid">';
  }
?>