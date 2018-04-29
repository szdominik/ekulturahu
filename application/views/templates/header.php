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

<nav class="header">
  <div class="header-main">
    <div class="header-icons">
      <img src="<?php echo base_url('assets/icons/facebook.png');?>" alt="facebook">
      <img src="<?php echo base_url('assets/icons/twitter.png');?>" alt="twitter">
      <img src="<?php echo base_url('assets/icons/googleplus.png');?>" alt="google+">
      <img src="<?php echo base_url('assets/icons/youtube.png');?>" alt="youtube">
      <img src="<?php echo base_url('assets/icons/feed.png');?>" alt="rss feed">
    </div>
    <a href="<?php echo site_url(); ?>">
      <img src="<?php echo base_url('assets/icons/ekultura.png');?>" alt="ekultura.hu">
    </a>
    <div class="menu-buttons">
      <button type="button" class="hamburger-btn">
        <img src="<?php echo base_url('assets/icons/hamburger.png');?>" alt="hamburger icon">
      </button>
      <span id="menu-text">Menü</span>
    </div>
  </div>

  <div class="navbar-links">
    <?php foreach ($subcategories as $sc): ?>
      <a href="<?php echo site_url($sc['slug']); ?>"><p><?php echo $sc['name']; ?></p></a>
    <?php endforeach; ?>
  </div>
</nav>

<div id="content-mask"></div>

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
      <img src="<?php echo base_url('assets/icons/search.png');?>" alt="search icon" />
    </span>
  </form>
</div>

<div class="container-fluid body-content">
