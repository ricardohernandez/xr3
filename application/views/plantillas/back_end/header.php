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
<link rel="shortcut icon" href="<?php echo base_url();?>assets3/imagenes/favicon.ico" type="image/x-icon">
<link rel="icon" href="<?php echo base_url();?>assets3/imagenes/favicon.ico" type="image/x-icon">
<!-- Css -->
<link href='https://fonts.googleapis.com/css?family=Montserrat:400,600,700%7CSource+Sans+Pro:400,600,700' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="<?php echo base_url();?>assets3/front_end/css/font-icons.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets3/back_end/css/estilos_menu.css" />
<!-- <link rel="stylesheet" href="<?php echo base_url();?>assets3/back_end/css/loader.css" > -->
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart','table']});
</script>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="bg-light style-default style-rounded">

  <div class="loader-mask">
      <div class="loader">
        <div></div>
      </div>
  </div>

  <div class="content-overlay"></div>

<!-- SIDENAV -->    
  <header class="sidenav" id="sidenav">

    <!-- CLOSE -->
      <div class="sidenav__close">
        <button class="sidenav__close-button" id="sidenav__close-button" aria-label="close sidenav">
          <i class="ui-close sidenav__close-icon"></i>
        </button>
      </div>

    <!-- SIDENAV -->
        <nav class="sidenav__menu-container">
          <ul class="sidenav__menu" role="menubar" style="list-style: none;">
              <li>
                <a href="#" class="sidenav__menu-url">Aplicaciones</a>
                  <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
                  <ul class="sidenav__menu-dropdown">
                    <!-- <li class="">
                      <a class="sidenav__menu-url" href="#!">SUBMENU</a>
                      <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
                      <ul class="sidenav__menu-dropdown">
                         <li><a class="sidenav__menu-url" href="">APP</a></li>
                      </ul>
                    </li> -->
                      <li><a target="_blank" class="sidenav__menu-url" href="<?php echo base_url() ?>productividad"> Productividad</a></li>
                      <li><a target="_blank" class="sidenav__menu-url" href="<?php echo base_url() ?>checklist_ots"> Checklist herramientas</a></li>

                                          
                  </ul>
              </li>

                <?php  
            if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
              ?>
                      <li>
                      <a href="#" class="sidenav__menu-url">Editores</a>
                      <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
                      <ul class="sidenav__menu-dropdown">
                          <li><a target="_blank" class="sidenav__menu-url" href="<?php echo base_url() ?>admin_xr3#noticias"> Noticias</a></li>
                          <li><a target="_blank" class="sidenav__menu-url" href="<?php echo base_url() ?>admin_xr3#informaciones"> Informaciones</a></li>
                      </ul>
                    </li>
              <?php
            }
          ?>


                <?php  
            if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
              ?>
                      <li>
                      <a href="#" class="sidenav__menu-url">Mantenedores</a>
                      <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
                      <ul class="sidenav__menu-dropdown">
                          <li><a target="_blank" class="sidenav__menu-url" href="<?php echo base_url() ?>mantenedor_usuarios"> Mantenedor usuarios</a></li>
                      </ul>
                    </li>
              <?php
            }
          ?>


              <li>
                <a href="#" class="sidenav__menu-url"><?php echo $this->session->userdata("nombre_completo")?></a>
                  <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
                  <ul class="sidenav__menu-dropdown">
                    <li><a class="sidenav__menu-url" href="<?php echo base_url()?>unlogin">Cerrar Sesi&oacute;n</a></li>
                  </ul>
              </li>

          </ul> 
        </nav>
    <!-- END SIDENAV -->
  </header> 

<!-- MENU --> 
  <header class="nav">
    <div class="nav__holder nav--sticky">
      <div class="container-fluid relative">
        <div class="flex-parent">
          <!-- BOTON MENU  -->
            <button class="nav-icon-toggle" id="nav-icon-toggle" aria-label="Open side menu">
              <span class="nav-icon-toggle__box">
                <span class="nav-icon-toggle__inner"></span>
              </span>
            </button> 

          <!-- LOGO -->
            <a href="../" class="logo">
              <img class="logo__img" src="<?php echo base_url();?>assets3/imagenes/logo.png" alt="logo">
            </a>

          <!-- MENU IZQUIERDA -->
            <nav class="flex-child nav__wrap d-none d-lg-block">              
                <ul class="nav__menu">
                    <li class="nav__dropdown ">
                    <a href="#">Aplicaciones</a>
                        <ul class="nav__dropdown-menu"> 
                          <li><a target="_blank" class="menu_list" href="<?php echo base_url() ?>productividad"> Productividad</a></li>
                            <li><a target="_blank" class="menu_list" href="<?php echo base_url() ?>checklist_ots"> Checklist herramientas</a></li>
                        
                          <!-- <li class="nav__dropdown">
                            <a class="menu_list" href="#!">sub</a>
                            <ul class="nav__dropdown-menu">
                              <li><a target="_blank" class="menu_list" href="">app</a></li>
                            </ul>
                          </li> -->
                        </ul>
                    </li>

                    <?php  
              if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
                ?>
                        <li class="nav__dropdown ">
                        <a href="#">Editores</a>
                            <ul class="nav__dropdown-menu"> 
                              <li><a target="_blank" class="menu_list" href="<?php echo base_url() ?>admin_xr3#noticias"> Noticias</a></li>
                                <li><a target="_blank" class="menu_list" href="<?php echo base_url() ?>admin_xr3#informaciones"> Informaciones</a></li>
                            </ul>
                        </li>
                <?php
              }
            ?>

            <?php  
              if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
                ?>
                        <li class="nav__dropdown ">
                        <a href="#">Mantenedores</a>
                            <ul class="nav__dropdown-menu"> 
                      <li><a target="_blank" class="menu_list" href="<?php echo base_url() ?>mantenedor_usuarios"> Mantenedor usuarios</a></li>
                            </ul>
                        </li>
                <?php
              }
            ?>

                </ul> 
            </nav> 

        <!-- MENU DERECHA -->
          <div class="nav__right">
              <div class="nav__right-item nav__search">
             
                <ul class="nav__menu menu_derecho">
                  <li class="nav__dropdown">
                    <a href="#"> 
                      <?php  
                        if($this->session->userdata('foto')!=""){
                          ?>
                             <img style="width: 39px!important; height: 39px; margin: -8px 5px 0px 5px;border-radius: 50%;" class="" src="<?php echo base_url() ?>fotos_usuarios/<?php echo $this->session->userdata('foto')?>" alt="Foto">
                          <?php
                        }
                      ?>
                    <?php echo $this->session->userdata("nombre_completo")?>
                    </a>
                    <ul class="nav__dropdown-menu ">
                       <li class="nav__dropdown">
                         <!--  <a class="menu_list btn_modal_pass" href="#!" id="">Cambiar Contrase&ntilde;a</a> -->
                          <a class="menu_list" href="<?php echo base_url()?>unlogin">Cerrar Sesi&oacute;n</a>
                      </li>          
                    </ul>
                  </li>
                </ul>
              </div>   
          </div> 
        </div>
      </div>
    </div>
  </header> 

  
 <!--  <nav class="navbar navbar-expand-lg navbar-dark bg-light fixed-top nav-tabs-main">
     
    <a class="navbar-brand " href="../"> 
       <img class="logo_header" style="width: 160px!important;height: 58px!important;margin-top: -16px;" class="d-inline-block align-top" alt="" src="<?php echo base_url();?>assets3/imagenes/logo2.png"> 
    </a>

     <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav" style="margin-left: 100px;">
        
        <?php  
          if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
            ?>
            <li id="" class="nav-item <?php $clase = $page=="checklist_ots" ? 'active' : ''; echo $clase; ?>">
              <a class="nav-link" href="<?php echo base_url() ?>checklist_ots"><i class="fas fa-clipboard-list"></i> CheckList de Herramientas</a>
            </li>
            <?php
          }
        ?>

        <li id="" class="nav-item <?php $clase = $page=="productividad" ? 'active' : ''; echo $clase; ?>">
          <a class="nav-link" href="<?php echo base_url() ?>productividad"><i class="fas fa-list"></i> Productividad </a>
        </li>

        <?php  
          if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
            ?>
             <li id="" class="nav-item <?php $clase = $page=="mantenedor_usuarios" ? 'active' : ''; echo $clase; ?>">
              <a class="nav-link" href="<?php echo base_url() ?>mantenedor_usuarios"><i class="fas fa-users"></i> Mantenedores </a>
            </li>
            <?php
          }
        ?>
        
      
       </ul>

       <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-item nav-link dropdown-toggle mr-md-2" href="#" id="bd-versions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <?php echo $n[0];?>
          </a>

          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="bd-versions">
            <a href="<?php echo base_url() ?>unlogin" class="dropdown-item" href="#">Cerrar Sesi&oacute;n</a>
          </div>

        </li>
      </ul>
    </div>

  </nav> -->




