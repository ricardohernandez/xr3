<!doctype html>
<html class="no-js h-100" lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Administraci&oacute;n XR3</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" id="main-stylesheet" data-version="1.1.0" href="<?php echo base_url()?>assets3/admin/styles/shards-dashboards.1.1.0.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets3/admin/styles/extras.1.1.0.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets3/admin/styles/croppie.css">
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="<?php echo base_url();?>assets3/admin/scripts/jquery.min.js"></script>
    <script src="<?php echo base_url();?>assets3/admin/scripts/notify.min.js"></script>
    <script src="<?php echo base_url();?>assets3/admin/scripts/croppie.js"></script>
    <script src="<?php echo base_url();?>assets3/admin/scripts/tinymce.min.js"></script>
            
    <!-- <<script></script><script src="https://cdn.tiny.cloud/1/h09ctxv9is142va6afgod7xylnoxlwzqehby3ytstmy3s5t4/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> -->
   <!--  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> -->

  </head>
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


  <body class="h-100">
