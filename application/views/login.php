<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="description" content="">
<meta name="author" content="">
<title><?php echo $titulo?></title>
<script src="<?php echo base_url();?>assets3/back_end/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets3/back_end/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>assets3/back_end/js/rut.min.js"></script>
<link href="<?php echo base_url();?>assets3/back_end/css/normalize.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets3/back_end/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets3/back_end/css/estilos.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets3/back_end/css/form_style2.css" rel="stylesheet">
<link rel="shortcut icon" href="<?php echo base_url();?>assets3/front_end/img/favicon2.jpg">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<script>
  // Obtener el valor del token CSRF desde la cookie
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

<style type="text/css" media="screen">
  @media (max-width: 768px){
    .log_tit{
      font-size: 16px;
    }
    .log_tit2{
       font-size: 13px;
       color: #000!important;
    }

    .logo_pto {
       width: 65px;
       margin-right:4px;
       margin-left:6px;
    }

    .llave{
      display: none;
    }
    .form-top-left{
      width: 100%!important;
    }

    .form-top {
      padding: 0 5px 15px 5px;
    }
    .icon {
      padding: 15px;
      color: #172969;
      min-width: 50px;
      margin-left: 60%;
      text-align: center;
    }

    .copy{
      font-size: 11px;
    }

  }
  
  @media (min-width: 768px){
    .icon {
      padding: 15px;
      color: #172969;
      min-width: 50px;
      margin-left: 70%;
      text-align: center;
    }

    .log_tit{
      font-size: 18px;
    }
    .log_tit2{
      font-size: 18px;
      color: #000!important;
    }

    .logo_pto {
       width: 80px;
       margin-right:20px;";
    }
    
    .copy{
      font-size: 13px;
    }

  }
  

  body{
	  background-image: url("./assets3/imagenes/fondolog.jpg");
	  background-size: cover;
  }

  .validacion{
    font-size:12px!important;

  }

  .alert-primary {
    color: #000;
    background-color: #CFE2FF;
    border-color: #CFE2FF;
  }

  .alert {
    padding: 3px 15px;
    margin-bottom: 0px;
    border: 1px solid transparent;
    border-radius: 4px;
    text-align:left;
  }
  .top-content{
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    -webkit-transform: translate(-50%, -50%);
  }
  .validacion-correo{
    margin-top:10px;
    margin-bottom: 10px;
  }

  .logo_empresa{
    width: 100px;
    margin-top: 9px;
  }
  .recuperar_contrasena{
    font-size: 14px;
    display: inline-block;
    margin: 4px;
    color: #172969;
  }
  button.btn {
    height: 40px;
    line-height: 40px;
  }

  #togglePassword{
    cursor: pointer;
  }

  .input-icons i {
      position: absolute;
  }
    
  .input-icons {
      width: 100%;
      margin-bottom: 10px;
  }
    

    
  .input-field {
      width: 100%;
      padding: 10px;
      text-align: left;
  }
    
  h2 {
      color: green;
  }

  .alert-dismissible .close {
    padding: 0.35rem 1.25rem!important;
}

</style>
<script type="text/javascript">
  $(function(){
    
    $('#usuario').Rut({
      on_error: function(){ alert('Rut incorrecto'); },
      format_on: 'keyup'
    });

    $('#usuario_recuperar').Rut({
      on_error: function(){ alert('Rut incorrecto'); },
      format_on: 'keyup'
    });


    function detectBrowser(){
      if (/MSIE 10/i.test(navigator.userAgent)) {
         // This is internet explorer 10
         alert('Favor usar navegador Google Chrome, Firefox o Safari, internet explorer puede provocar comportamientos inesperados en la aplicación.');
         return false;
      }

      if (/MSIE 9/i.test(navigator.userAgent) || /rv:11.0/i.test(navigator.userAgent)) {
          // This is internet explorer 9 or 11
         alert('Favor usar navegador Google Chrome, Firefox o Safari, internet explorer puede provocar comportamientos inesperados en la aplicación.');
         return false;
      }

      if (/Edge\/\d./i.test(navigator.userAgent)){
         alert('Favor usar navegador Google Chrome, Firefox o Safari, EDGE puede provocar comportamientos inesperados en la aplicación.');
         return false;
      }
      return true;
    }


    $(document).on('submit', '#formlog', function(event) {
       if(detectBrowser()){
        var formElement = document.querySelector("#formlog");
        var formData = new FormData(formElement);
        data: formData;

        $.ajax({
            url: $('#formlog').attr('action')+"?"+$.now(),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend: function(){
            	$('.btn_submit_b').prop("disabled",true);
            },
            success:function(data){ 
              if(data.res == "ok"){    
                $(".validacion").hide();       
                $(".validacion").html('<div class="alert alert-primary alert-dismissible fade show" role="alert"><strong>'+data.msg+'</strong></div>');
                $(".btn_submit").html('<button type="submit" class="btn_submit_b btn btn-primary" style="background-color: #172969"> Ingresando <i class="fa fa-cog fa-spin"></i></button>');
                $(".validacion").fadeIn(1);
                $("#btn_submit").html('<i class="fa fa-cog fa-spin fa-3x"></i>');  
                $('.btn_submit_b').prop("disabled",false);
                setTimeout( function () {
    		         window.location.replace("<?php echo base_url(); ?>inicio");
    		        }, 1500);   
              }else if(data.res == "error"){
              	$('.btn_submit_b').prop("disabled",false);
                $(".validacion").hide();
                $(".validacion").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.msg+'</div>');
                $(".validacion").fadeIn(1000);
              }

            },
            error:function(data){
            	$('.btn_submit_b').prop("disabled",false);
                $(".validacion").hide();
                $(".validacion").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Problemas accediendo a la base de datos, intente nuevamente.</div>');
                $(".validacion").fadeIn(1000);          
            }
        });
        return false;
        }else{
            return false;
        }
      });


      $('#formRecuperarPass').submit(function(){
        var formElement = document.querySelector("#formRecuperarPass");
        var formData = new FormData(formElement);
        $.ajax({
            url: $('.formRecuperarPass').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend : function (data){
              $(".btn_enviar_pass").html('<i class="fa fa-cog fa-spin"></i> Cargando...').prop("disabled",true);
              $(".btn_cierra_envia_c").prop("disabled",true);
            },
            success: function (data) {
              $(".cont_mensajes_pass").show();
              $(".btn_cierra_envia_c").prop("disabled",false);
              $(".btn_enviar_pass").html('<i class="fa fa-envelope"></i> Enviar').prop("disabled",false);

              if(data.res=="error"){
                 $(".cont_mensajes_pass").html('<div class="alert alert-danger alert-dismissible fade show">'+
                ''+data.msg+''+
                '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
              }else{
                 $(".cont_mensajes_pass").html('<div class="alert alert-success alert-dismissible fade show">'+
                ''+data.msg+''+
                '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
              }

           }
        });
        return false;     
      });


      $(".recuperar_contrasena").click(function(event) {
        usuario=$("#usuario").val();
        $("#usuario_pass").val(usuario);
        $("#modal_recuperar").modal('toggle'); 
        $(".cont_mensajes_pass").show();
        $(".btn_cierra_envia_c").prop("disabled",false);
        $(".btn_enviar_pass").prop("disabled",false);
      });

      const togglePassword = document.querySelector("#togglePassword");
      const password = document.querySelector("#pass");

      togglePassword.addEventListener("click", function () {
          // toggle the type attribute
          const type = password.getAttribute("type") === "password" ? "text" : "password";
          password.setAttribute("type", type);
          
          // toggle the icon
          this.classList.toggle("fa-eye-slash");
      });

      // prevent form submit
      const form = document.querySelector("form");
      form.addEventListener('submit', function (e) {
          e.preventDefault();
      });


  });
</script>
</head>
<body>

<!-- LOGIN -->
  <div class="top-content col-xs-12 col-sm-12">
    <div class="inner-bg">
        <div class="container">
          <div class="form-row">
              <div class="col-lg-6 offset-lg-3  form-box">

                  <div class="form-top">
                    <div class="form-top-left">
                      
                      <h4 class="log_tit2 mb-3">
                        <img class="logo_pto" src="<?php echo base_url();?>assets3/imagenes/pto.jpg" alt="logo">
                        <?php echo $subtitulo ?>
                      </h4>

                     <!--  <h3 class="log_tit">
                        <?php echo $titulo ?>
                      </h3>
                       -->
                     

                    </div>
                    <div class="form-top-right llave">
                      <img class="logo_empresa" src="<?php echo base_url();?>assets3/imagenes/logo.png" alt="logo">
                     <!--  <i class="fa fa-key"></i> -->
                    </div>
                  </div>

                  <div class="form-bottom">
                    <?php echo form_open(base_url()."validaLogin",array("id"=>"formlog","class" =>"formlog"));?>
                    
                    <div class="validacion mb-2">
                      <!--  <div class="alert alert-primary alert-dismissible fade show" role="alert">
                         Acceso de usuarios registrados
                       </div> -->
                    </div>

                    <div class="form-group">
                      <label class="sr-only" for="form-username">RUT</label>
                      <input type="text" name="usuario" id="usuario" placeholder="Ingrese RUT" class="form-username form-control" >
                    </div>

                    <div class="form-group">
                      <div class="input-icons">
                        <i class="fa fa-eye icon" aria-hidden="true" id="togglePassword"></i>
                        <input type="password" name="pass" id="pass" placeholder="Ingrese contrase&ntilde;a" class="form-password input-field form-control">
                      </div>
                    </div>

                      <div class="row">
                        <div class="col-lg-6">
                          <div class="btn_submit">
                            <button type="submit" class="btn_submit_b btn btn-primary" style="background-color: #172969">
                            <i class="fa fa-sign-in"></i> Ingresar</button>
                          </div>
                        </div>
                         <div class="col-lg-6">
                          <center><a href="javascript:;" class="recuperar_contrasena">Recuperar contraseña</a></center>
                        </div>
                      </div>
                  

                    <div class="col-lg-12 mt-4">
                       <h6 class="text-center copy">Desarrollado por KM Telecomunicaciones <?php echo date("Y") ?></h6>
                    </div>
                   
                   <?php echo form_close();?>
                  </div>

              </div>
          </div>
        </div>
    </div>
  </div>

<!-- MODAL CONTRASEÑA-->
  <div class="modal fade" id="modal_recuperar" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title">Recuperar contraseña</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <?php echo form_open_multipart('recuperarPass', array('id'=>'formRecuperarPass','class'=>'formRecuperarPass')); ?>

          <div class="modal-body">
     
                <div class="col">
                  <div class="cont_mensajes_pass"></div>
                  <div class="mt-2">
                    <div class="col-lg-12">
                      <div class="row">
                        <label class="col-lg-2 mt-2">RUT</label>
                        <input class="col-lg-10" type="text" autocomplete="off" placeholder="123456789" name="usuario_recuperar" id="usuario_recuperar" required>
                     </div>
                   </div>
                  </div>
                </div>

            <div class="modal-footer" style="justify-content:center;border-top: none;">
              <div class="col-12">
                <div class="row">
                  <div class="col-6">
                      <button class="btn btn-primary btn_enviar_pass w-100"  style="background-color: #172969">
                        <i class="fa fa-envelope"></i> Enviar
                      </button>
                  </div>
                  
                  <div class="col-6">
                      <button class="btn btn-secondary w-100" data-dismiss="modal">
                        <i class="fa fa-window-close"></i> Cerrar
                      </button>
                  </div>
                </div>
                    
              </div>
            </div>

        <?php echo form_close();?>  

      </div>
    </div>
  </div>
  </div>

</body>
</html>