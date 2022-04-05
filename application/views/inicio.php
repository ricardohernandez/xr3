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

		<?php $this->load->view('menu',array("perfiles"=>$perfiles)); ?>

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
