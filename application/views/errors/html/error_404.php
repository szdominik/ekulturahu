<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>404 Page Not Found</title>
  <meta name="robots" content="noindex" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <style type="text/css">
    html, body {
      background-color: #f8f4f3;
      margin: 0;
      font-family: 'Open Sans', sans-serif;
    }

    .header {
      width: 100%;
      height: 75px;
      border-bottom: 1px solid #c0bab2;
    }

    img {
      margin: 20px 0 20px 20px;
      width: 200px;
    }

    h1 {
      margin-top: 40px;
      margin-bottom: 55px;
      text-align: center;
      font-weight: 400;
      font-size: 30px;
    }
  </style>
</head>
<body>
  <div class="header">
    <a href="<?php echo site_url(); ?>">
      <img src="<?php echo base_url('assets/icons/ekultura.png');?>" alt="ekultura.hu">
    </a>
  </div>
  <h1>A keresett oldal sajnos nem található.</h1>
</body>
</html>