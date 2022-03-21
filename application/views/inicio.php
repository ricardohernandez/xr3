<!DOCTYPE html>
<html lang="es">
<head>
<title>Inicio</title>
<meta charset="utf-8">
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<meta name="description" content="">
<!-- Google Fonts -->
<link href='https://fonts.googleapis.com/css?family=Montserrat:400,600,700%7CSource+Sans+Pro:400,600,700' rel='stylesheet'>
<!-- Css -->
<link rel="stylesheet" href="<?php echo base_url();?>assets3/front_end/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets3/front_end/css/font-icons.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets3/front_end/css/style2222.css" />
<link href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" rel="stylesheet">
<link rel="shortcut icon" href="<?php echo base_url();?>assets3/front_end/img/favicon.ico">
<link rel="apple-touch-icon" href="<?php echo base_url();?>assets3/front_end/img/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url();?>assets3/front_end/img/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url();?>assets3/front_end/img/apple-touch-icon-114x114.png">
<script src="<?php echo base_url();?>assets3/front_end/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets3/front_end/js/lazysizes.min.js"></script>
<script src="<?php echo base_url();?>assets3/front_end/js/loader.js" charset="UTF-8"></script>

<script type="text/javascript">
  $(function(){
   url="<?php echo base_url(); ?>";

   cargaInicio();
   // cargaNoticias("");
   // cargaCategorias("");
   // cargaIngresos();
   // cargaInformaciones();

   $(document).off('click', '.tabs__trigger').on('click', '.tabs__trigger', function(event) {
    cat = $(this).attr("href").split("-");
    cat=cat[1];
    cargaNoticias(cat);
   });

    function cargaInicio(){
      $(".cont_noticias").hide();
      $.post(url+"cargaInicio", function( data ) {
        $(".cont_noticias").html(data.noticias).fadeIn('500');    
        $(".carga_categorias").html(data.categorias).fadeIn('500');    
        $(".cont_ingresos").html(data.ingresos).fadeIn('500');    
        $(".cont_cumpleanios").html(data.cumpleanios).fadeIn('500');    
        $(".cont_informaciones").html(data.informaciones).fadeIn('500');    
      },"json");
    }

    function cargaNoticias(cat){
      $(".cont_noticias").hide();
      $.get(url+"cargaVistaNoticias", {cat:cat} ,function( data ) {
        $(".cont_noticias").html(data).fadeIn('500');    
      });
    }

   // function cargaCategorias(){
   //    $.get(url+"cargaCategorias" , function( data ) {
   //      $(".carga_categorias").html(data);    
   //    });
   // }

   // function cargaIngresos(){
   //    setTimeout( function () {
   //      $.get(url+"cargaIngresos" , function( data ) {
   //        $(".cont_ingresos").html(data).fadeIn('500');    
   //      });
   //    }, 1000 );
   // }

   // function cargaInformaciones(){
   //    $.get(url+"cargaInformaciones" , function( data ) {
   //      $(".cont_informaciones").html(data).fadeIn('500');    
   //    });
   // }

  })

</script>


<body class="bg-light style-default style-rounded">

    <!-- PRELOADER -->
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
		            <ul class="sidenav__menu" role="menubar">
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

	<!-- MAIN -->  

	    <main class="main oh" id="main">
		    <header class="nav">
			    <div class="nav__holder nav--sticky">
			        <div class="container relative">
				        <div class="flex-parent">

				           <!-- BOTON MENU  -->
					            <button class="nav-icon-toggle" id="nav-icon-toggle" aria-label="Open side menu">
					              <span class="nav-icon-toggle__box">
					                <span class="nav-icon-toggle__inner"></span>
					              </span>
					            </button> 

				            <!-- LOGO -->
					            <a href="#!" class="logo">
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

    <!-- INFORMACIONES-->

	    <div class="container">
	      <div class="trending-now">
	        <span class="trending-now__label">
	          <i class="ui-flash"></i>
	          <span class="trending-now__text d-lg-inline-block d-none">Informaciones</span>
	        </span>
	        <div class="newsticker">
	          <ul class="newsticker__list cont_informaciones"></ul>
	        </div>
	        <div class="newsticker-buttons">
	          <button class="newsticker-button newsticker-button--prev" id="newsticker-button--prev" aria-label="next article"><i class="ui-arrow-left"></i></button>
	          <button class="newsticker-button newsticker-button--next" id="newsticker-button--next" aria-label="previous article"><i class="ui-arrow-right"></i></button>
	        </div>
	      </div>
	    </div>
	  
<!--PRINCIPAL -->

    <section class="featured-posts-grid">
      <div class="container">
        <div class="row row-8">

            <!-- NOTICIAS -->
	            <div class="col-lg-8">
	              <div class="blog__content">
	                <section class="section tab-post mb-16">

	                  <div class="title-wrap title-wrap--line">
	                  <!--   <h3 class="section-title">Noticias</h3> -->

	                    <div class="tabs tab-post__tabs"> 
	                      <ul class="carga_categorias">
	                      </ul> 
	                    </div>
	                  </div>

	                  <div class="tabs__content">
	                    <div class="row card-row cont_noticias">
	                    </div>
	                  </div> 

	                </section> 
	              </div> 
	            </div>

            <!-- WIDGETS DERECHA -->

	            <aside class="col-lg-4 sidebar sidebar--right">

	         		<aside class="widget widget-popular-posts"> 
		                <h4 class="widget-title">Cumpleaños</h4>
		                <hr class="separador_titulo">
		                <ul class="post-list-small post-list-small--1">
		                  <li class="post-list-small__item cont_cumpleanios">
		                  </li>
		                </ul>
	                </aside> 

	                <aside class="widget widget-popular-posts"> 
		                <h4 class="widget-title">&Uacute;ltimos ingresos</h4>
		                <hr class="separador_titulo">
		                <ul class="post-list-small post-list-small--1">
		                  <li class="post-list-small__item cont_ingresos">
		                  </li>
		                </ul>
	                </aside> 

	                

	            </aside>

        </div>
      </div>
    </section>


<style type="text/css">
	.logo__img{
		width:100px!important;
		height: 40px!important;
	}

	 @media (min-width: 768px) {
       .modal_datos_personales{
          min-width: 70%;
       }
    }

    @media (max-width: 768px) {
       .modal_datos_personales{
           min-width: 90%;
       }
    }

    .label_sm {
      font-size: 12px;
    }

    .btn_solicitud_datos{
      margin-left: 20px;
    }
    .txt_solicitud_datos, .cont_mensajes_solicitud{
      font-size: 13px;
      margin:0px;
    }
    #btn_enviar_solicitud{
      color: #fff;
      background-color: #28a745;
      border-color: #28a745;
    }

    .input_xs{
      padding: .1rem .2rem!important;
      font-size: 13px!important;
      margin-bottom: 4px!important;
    }

    .form-group-xs{
      margin-bottom: .1rem!important;
    }

    .menu_app .nav__dropdown-menu li a{
      padding: 4px 10px!important;
    }
   /* .modificar_datos{
      font-weight: bold;
    }*/
</style>

<!-- Footer -->
<footer class="footer footer--dark">
  <div class="container">
    <div class="footer__widgets">
      <div class="row">
        <div class="col-lg-12">
          <aside class="widget widget-logo">
            <p class="copyright">
              © <?php echo date("Y")?> 
              KMO-XR3 
            </p>
          </aside>
        </div>
      </div>
    </div>    
  </div> <!-- end container -->
</footer> <!-- end footer -->

<div id="back-to-top">
  <a href="#top" aria-label="Go to top"><i class="ui-arrow-up"></i></a>
</div>

</main> <!-- end main-wrapper -->


<!-- jQuery Scripts -->
<link href="<?php echo base_url();?>assets3/front_end/css/featherlight.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets3/front_end/css/featherlight.gallery.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets3/front_end/css/themes/default/default.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets3/front_end/css/nivo-lightbox.min.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets3/front_end/js/featherlight.min.js"></script>
<script src="<?php echo base_url();?>assets3/front_end/js/featherlight.gallery.min.js"></script>
<script src="<?php echo base_url();?>assets3/front_end/js/nivo-lightbox.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets3/front_end/css/select2.min.css">
<script src="<?php echo base_url();?>assets3/front_end/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>assets3/front_end/js/easing.min.js"></script>
<script src="<?php echo base_url();?>assets3/front_end/js/owl-carousel.min.js"></script>
<script src="<?php echo base_url();?>assets3/front_end/js/flickity.pkgd.min.js"></script>
<script src="<?php echo base_url();?>assets3/front_end/js/twitterFetcher_min.js"></script>
<script src="<?php echo base_url();?>assets3/front_end/js/jquery.newsTicker.min.js"></script>  
<script src="<?php echo base_url();?>assets3/front_end/js/modernizr.min.js"></script>
<script src="<?php echo base_url();?>assets3/front_end/js/scripts1.js"></script>
<script src="<?php echo base_url();?>assets3/front_end/js/select2.min.js" charset="UTF-8"></script>

</body>
</html>
