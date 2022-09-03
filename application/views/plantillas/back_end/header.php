<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<noscript>
    <div class="noscriptmsg">
    <meta http-equiv="refresh" content="0;URL=<?php echo base_url()?>nojs">
    </div>
</noscript>
<title><?php echo $titulo?></title>
<script src="<?php echo base_url();?>assets3/back_end/js/jquery.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets3/back_end/css/loader.css" >
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
<link rel="shortcut icon" href="<?php echo base_url();?>assets3/front_end/img/favicon2.jpg">
<!-- Css -->
<link href='https://fonts.googleapis.com/css?family=Montserrat:400,600,700%7CSource+Sans+Pro:400,600,700' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="<?php echo base_url();?>assets3/front_end/css/font-icons.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets3/back_end/css/estilos_menu22.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets3/back_end/css/estilos11.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets3/back_end/css/estiloszzz.css">

<!-- <link rel="stylesheet" href="<?php echo base_url();?>assets3/back_end/css/loader.css" > -->
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart','table','gauge']});
</script>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body style="background-color: #F9FAFB;"><!-- class="bg-light style-default style-rounded" -->

  <div class="loader-mask">
      <div class="loader">
        <div></div>
      </div>
  </div>

  <div class="content-overlay"></div>

<?php $this->load->view('menu',array("perfiles"=>$perfiles)); ?>