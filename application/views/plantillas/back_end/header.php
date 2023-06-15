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
<!-- <link rel="stylesheet" href="<?php echo base_url();?>assets3/back_end/css/estilos_menu_oscuro.css" /> 
 <link rel="stylesheet" href="<?php echo base_url();?>assets3/back_end/css/estilos_menu_claro.css" />
 -->

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
<style>
	.modo_noche, .modo_dia{
    cursor:pointer;
    font-size:22px;
    margin-top:5px;
   }
</style>
<script>
  $(".loader").fadeIn('fast'); 
	$('.loader-mask').fadeIn('fast');

	$(function(){
		const url = "<?php echo base_url();?>";
		let modoActual = localStorage.getItem('modo');

		if (!modoActual) {
			if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
				modoActual = 'modo_noche';
			} else {
				modoActual = 'modo_dia';
			}
		}

		aplicarModo(modoActual);

		$('.modo_noche').click(function() {
			mostrarLoader();
			setTimeout(function() {
				aplicarModo('modo_noche');
				localStorage.setItem('modo', 'modo_noche');
				ocultarLoader();
			}, 1000); // Tiempo de espera antes de aplicar el modo y ocultar el loader (2 segundos)
		});

		$('.modo_dia').click(function() {
			mostrarLoader();
			setTimeout(function() {
				aplicarModo('modo_dia');
				localStorage.setItem('modo', 'modo_dia');
				ocultarLoader();
			}, 1000); // Tiempo de espera antes de aplicar el modo y ocultar el loader (2 segundos)
		});

		mostrarLoader();
		setTimeout(function() {
			aplicarModo(modoActual);
			ocultarLoader();
		}, 1000); // Tiempo de espera antes de aplicar el modo y ocultar el loader (2 segundos)

		function aplicarModo(modo) {
      restablecerEstilos();
			const modoNocheElements = document.querySelectorAll('.modo_noche');
			const modoDiaElements = document.querySelectorAll('.modo_dia');

      if (modo === 'modo_noche') {
        modoNocheElements.forEach(element => element.style.display = 'none');
        modoDiaElements.forEach(element => element.style.display = 'inline');
        
        loadCSS(url + 'assets3/back_end/css/estilos_menu_oscuro.css');
        loadCSS(url + 'assets3/back_end/css/bootstrap-night.css');
        loadCSS(url + 'assets3/back_end/css/estilos-oscuro.css');
        
        unloadCSS(url + 'assets3/back_end/css/estilos_menu_claro.css');
        unloadCSS(url + 'assets3/back_end/css/bootstrap.min.css');
        unloadCSS(url + 'assets3/back_end/css/estilos-claro.css');
        $("body").css("display", "");
        $(".logo_oscuro").show();
        $(".logo_claro").hide();
        
      } else if (modo === 'modo_dia') {
        modoNocheElements.forEach(element => element.style.display = 'inline');
        modoDiaElements.forEach(element => element.style.display = 'none');
        
        loadCSS(url + 'assets3/back_end/css/estilos_menu_claro.css');
        loadCSS(url + 'assets3/back_end/css/bootstrap.min.css');
        loadCSS(url + 'assets3/back_end/css/estilos-claro.css');
        
        unloadCSS(url + 'assets3/back_end/css/estilos_menu_oscuro.css');
        unloadCSS(url + 'assets3/back_end/css/bootstrap-night.css');
        unloadCSS(url + 'assets3/back_end/css/estilos-oscuro.css');
        $("body").css("display", "");
        $(".logo_claro").show();
        $(".logo_oscuro").hide();

      }
		}

		function loadCSS(url) {
			const link = document.createElement('link');
			link.href = url;
			link.rel = 'stylesheet';
			document.head.appendChild(link);
		}

		function unloadCSS(url) {
			const links = document.head.getElementsByTagName('link');
			for (let i = 0; i < links.length; i++) {
				if (links[i].href === url) {
					document.head.removeChild(links[i]);
					return;
				}
			}
		}

		function mostrarLoader() {
			$(".loader").fadeIn('fast'); // Mostrar el elemento con clase "loader"
			$('.loader-mask').fadeIn('fast'); // Mostrar el elemento con clase "loader-mask"
		}

		function ocultarLoader() {
			$(".loader").fadeOut(); // Ocultar el elemento con clase "loader"
			$('.loader-mask').fadeOut('fast'); // Ocultar el elemento con clase "loader-mask"
		}

    function restablecerEstilos() {
      const selectors = [
        '::-webkit-scrollbar', // Scrollbar del navegador
        'select', // Selectores
        'input[type="file"]' // Inputs de tipo file
      ];

      selectors.forEach(selector => {
        const elements = document.querySelectorAll(selector);
        elements.forEach(element => {
          // Elimina cualquier estilo específico aplicado a los elementos
          element.removeAttribute('style');
        });
      });
    }
	});



  function getCsrfToken() {
    var name = 'csrf_cookie=';
    var decodedCookie = decodeURIComponent(document.cookie);
    var cookieArray = decodedCookie.split(';');
    for (var i = 0; i < cookieArray.length; i++) {
      var cookie = cookieArray[i];
      while (cookie.charAt(0) === ' ') {
        cookie = cookie.substring(1);
      }
      if (cookie.indexOf(name) === 0) {
        return cookie.substring(name.length, cookie.length);
      }
    }
    return '';
  }

  // Agregar el token CSRF a todas las solicitudes AJAX
  $.ajaxSetup({
    data: {
      csrf_token: getCsrfToken()
    }
  });
</script>

<body style="display:none;">

  <div class="loader-mask">
      <div class="loader">
        <div></div>
      </div>
  </div>

  <div class="content-overlay"></div>

<?php $this->load->view('menu',array("perfiles"=>$perfiles)); ?>