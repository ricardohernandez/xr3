<style>
  .contenedor_tabla{
    padding: 20px!important;
  }
  .table.dataTable thead .sorting:before, table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:before, table.dataTable thead .sorting_desc_disabled:after {
    bottom: 2px!important;
  }
  .table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_desc_disabled:before {
    right: 1em;
    content: "\2191";
    bottom: 2px;
  }

  table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_desc_disabled:before {
    right: 1em;
    bottom: 1px!important;
    content: "\2191";
  }
  .pb-4, .py-4 {
    padding-bottom: 1.1rem!important;
  }
  .pt-4, .py-4 {
      padding-top: 1.1rem!important;
  }
  .modal-body {
      padding: .175rem 2.1875rem;
  }
</style>

<script type="text/javascript">
  $(function(){


    $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    var base="<?php echo base_url();?>";
    $.extend(true,$.fn.dataTable.defaults,{
      info:true,
      paging:false,
      ordering:true,
      searching:true,
      lengthChange: false,
      bSort: true,
      bFilter: true,
      bProcessing: true,
      pagingType: "simple" , 
      bAutoWidth: true,
      sAjaxDataProp: "result",        
      bDeferRender: true,
      language : {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      },
    });
  });
</script>
<div class="container-fluid">
  <div class="row">
    <!-- Main Sidebar -->
    <aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
      <div class="main-navbar">
        <nav class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap border-bottom p-0">
          <a class="navbar-brand w-100 mr-0" href="../" style="line-height: 25px;">
            <div class="d-table m-auto">
              <img id="main-logo" class="d-inline-block align-top mr-1" style="max-width: 105px;" src="<?php echo base_url();?>assets3/imagenes/logo.png" alt="">
            </div>
          </a>
          <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
            <i class="material-icons">&#xE5C4;</i>
          </a>
        </nav>
      </div>
      <form action="#" class="main-sidebar__search w-100 border-right d-sm-flex d-md-none d-lg-none">
        <div class="input-group input-group-seamless ml-3">
          <div class="input-group-prepend">
            <div class="input-group-text">
              <i class="fas fa-search"></i>
            </div>
          </div>
          <input class="navbar-search form-control" type="text" placeholder="" aria-label="Search"> </div>
      </form>

    <style type="text/css">
        .disabled{
          pointer-events:none;
          opacity:0.4;
       }
    </style>
<script>
  $(function(){

    var url2 = window.location.href;  
    part=url2.split("/");
    cont=(part.length)-1;
    /*alert(part[cont]);*/

    if(part[cont]=="admin_xr3#noticias"){
        vistaNoticiasInicio();
    }else if(part[cont]=="admin_xr3#informaciones"){
        vistaInformaciones();
    }else if(part[cont]=="admin_xr3#noticias2"){
        vistaNoticias();
    }else if(part[cont]=="admin_xr3#nacimientos"){
        vistaNacimientos();
    }else if(part[cont]=="admin_xr3#visitas"){
        vistaVisitas();
    }

    function vistaNoticiasInicio(){
      $("#noticias_btn").addClass('disabled');
      $(".contenedor_principal").html("<center><img src='<?php echo base_url()?>assets/imagenes/loader2.gif' class='loader'></center>");

      $("#visitas_btn").removeClass('active');   
      $("#noticias_btn").addClass('active');     
      $("#informaciones_btn").removeClass('active');     
      $("#nacimientos_btn").removeClass('active');    

      $.get("cargaVistaNoticiasAdmin", function( data ) {
        $(".main-content-container").html(data);    
        $("#noticias_btn").removeClass('disabled');
      });

      window.history.pushState('page2', 'Title', 'admin_xr3#noticias');

    }


    function vistaInformaciones(){
      $("#informaciones_btn").addClass('disabled');
      $(".contenedor_principal").html("<center><img src='<?php echo base_url()?>assets/imagenes/loader2.gif' class='loader'></center>");

      $("#visitas_btn").removeClass('active');   
      $("#informaciones_btn").addClass('active');    
      $("#noticias_btn").removeClass('active');     
      $("#nacimientos_btn").removeClass('active');    

      $.get("cargaVistaInformaciones", function( data ) {
        $(".main-content-container").html(data);    
        $("#informaciones_btn").removeClass('disabled');
      });     

      window.history.pushState('page2', 'Title', 'admin_xr3#informaciones');

    }

    function vistaNoticias(){
      $("#noticias_btn").addClass('disabled');
      $(".contenedor_principal").html("<center><img src='<?php echo base_url()?>assets/imagenes/loader2.gif' class='loader'></center>");

      $("#visitas_btn").removeClass('active');   
      $("#noticias_btn").addClass('active');    
      $("#nacimientos_btn").removeClass('active');    
      $("#informaciones_btn").removeClass('active');     
      window.history.pushState('page2', 'Title', 'admin_xr3#noticias');
      location.reload();

      /* $.get("cargaVistaNoticiasAdmin", function( data ) {
        $(".main-content-container").html(data);    
        $("#noticias_btn").removeClass('disabled');
      });*/
    }

    function vistaNacimientos(){
      $("#nacimientos_btn").addClass('disabled');
      $(".contenedor_principal").html("<center><img src='<?php echo base_url()?>assets/imagenes/loader2.gif' class='loader'></center>");

      $("#visitas_btn").removeClass('active');   
      $("#nacimientos_btn").addClass('active');    
      $("#informaciones_btn").removeClass('active');     
      $("#noticias_btn").removeClass('active');     

      $.get("cargaVistaNacimientos", function( data ) {
        $(".main-content-container").html(data);    
        $("#nacimientos_btn").removeClass('disabled');
      });
      window.history.pushState('page2', 'Title', 'admin_xr3#nacimientos');
  
    }

    function vistaVisitas(){
      $("#visitas_btn").addClass('disabled');
      $(".contenedor_principal").html("<center><img src='<?php echo base_url()?>assets/imagenes/loader2.gif' class='loader'></center>");

      $("#visitas_btn").addClass('active');    
      $("#nacimientos_btn").removeClass('active');    
      $("#informaciones_btn").removeClass('active');     
      $("#noticias_btn").removeClass('active');     

      $.get("cargaVistaVisitas", function( data ) {
        $(".main-content-container").html(data);    
        $("#visitas_btn").removeClass('disabled');
      });     

      window.history.pushState('page2', 'Title', 'admin_xr3#visitas');

    }


    $(document).off('click', '#informaciones_btn').on('click', '#informaciones_btn',function(event) {
      event.preventDefault();
      vistaInformaciones();
    });

    $(document).off('click', '#noticias_btn').on('click', '#noticias_btn',function(event) {
      event.preventDefault();
      vistaNoticias();
    });

    $(document).off('click', '#nacimientos_btn').on('click', '#nacimientos_btn',function(event) {
      event.preventDefault();
      vistaNacimientos();
    });

    $(document).off('click', '#visitas_btn').on('click', '#visitas_btn',function(event) {
      event.preventDefault();
      vistaVisitas();
    });


  })

</script>

  <div class="nav-wrapper">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link" href="#!" id="noticias_btn">
            <i class="material-icons">note_add</i>
            <span>Noticias</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#!" id="informaciones_btn">
            <i class="material-icons">description</i>
            <span>Informaciones</span>
          </a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link " href="#!" id="nacimientos_btn">
            <i class="material-icons">note</i>
            <span>Nacimientos</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="#!" id="visitas_btn">
            <i class="material-icons">supervised_user_circle</i>
            <span>Visitas</span>
          </a>
        </li> -->
      
        <li class="nav-item">
          <a class="nav-link " href="./">
            <i class="material-icons">home</i>
            <span>Volver al inicio</span>
          </a>
        </li>
      </ul>
  </div>
  </aside>
    
        
	<main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
	  <div class="main-navbar sticky-top bg-white">
	    <nav class="navbar align-items-stretch navbar-light flex-md-nowrap p-0">
	      <form action="#" class="main-navbar__search w-100 d-none d-md-flex d-lg-flex">
	        <div class="input-group input-group-seamless ml-3">
	          <div class="input-group-prepend">
	            <div class="input-group-text">
	              <i class="fas fa-search"></i>
	            </div>
	          </div>
	          <input class="navbar-search form-control" type="text" id="buscador"  placeholder="Buscador..." aria-label="Buscar"> </div>
	      </form>

	      <ul class="navbar-nav border-left flex-row ">
	      
	        <li class="nav-item dropdown">
	          <a class="nav-link dropdown-toggle text-nowrap px-3" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <?php  
                  if($this->session->userdata('foto')!=""){
                    ?>
                      <img style="width: 50px!important;height: 40px;" class="user-avatar rounded-circle mr-2" src="./fotos_usuarios/<?php echo $this->session->userdata('foto')?>" alt="Foto">
                    <?php
                  }
                ?>
	            <span class="d-none d-md-inline-block"><?php echo $this->session->userdata('nombre_completo'); ?></span>
	          </a>
				
	          <div class="dropdown-menu dropdown-menu-small">
	            <!-- <a class="dropdown-item" href="user-profile-lite.html">
	             <i class="material-icons"></i> Actualizar contrase&ntilde;a</a> -->

	            <div class="dropdown-divider"></div>
	            <a class="dropdown-item text-danger" href="./unlogin">
	            <i class="material-icons text-danger"></i> Cerrar sesi&oacute;n </a>

	          </div>
	        </li>
	      </ul>
	      <nav class="nav">
	        <a href="#" class="nav-link nav-link-icon toggle-sidebar d-md-inline d-lg-none text-center border-left" data-toggle="collapse" data-target=".header-navbar" aria-expanded="false" aria-controls="header-navbar">
	          <i class="material-icons"></i>
	        </a>
	      </nav>
	    </nav>
	  </div>

	  <!-- <div class="contenedor_mensaje alert alert-success alert-dismissible fade show mb-0" role="alert">
	    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	      <span aria-hidden="true">×</span>
	    </button>
	    <i class="fa fa-check mx-2"></i>
	    <strong>Success!</strong> 
	  </div> -->


	  <div class="main-content-container container-fluid px-4"></div>

	 
