<style type="text/css">
  html, body{
    min-height: calc(100vh - 110px)
  }

  .disabled_sub{
      pointer-events:none;
      opacity:0.4;
  }

  ::selection {
    background: #8AC007; 
    color:#fff;
  }

  .contenedor_app{
    border: 1px solid #dce4ec;
    background-color: #F8F8F8;
    padding: 10px 5px;
    margin-bottom: 40px;
    border-radius: 1px;
    min-height: calc(100vh - 110px)

  }
  .btn-top{
    margin-top: 1px;
  }
  .btn-xs {
    padding: 0px 5px!important;
    font-size: 12px;
  }
  hr{
    margin-top: 9px!important;
    margin-bottom: 3px!important;;
  }
  .loader{
    margin-top:150px;
    height:100px;
    width:100px;
  }
   

  .red{
    background-color: #DC3545;
    color: #fff;
  }
  .modal-header{
    padding:0.1rem 0.1rem!important;
    border-bottom: none!important;
  }

  .form-control-sm {
    height: calc(1.9em + 0.5rem + 2px)!important;
  }
  .modal-body {
    position: relative;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding:0.1rem 0.1rem!important;
  }

  .btn_xr3{
    color: #fff!important;
    background-color: #32477C!important;
    border-color: #32477C!important;
  }

  @media(min-width: 768px){
    .custom-select-sm {
     height: calc(1.90rem + 2px)!important; 
    }

    .input-xs {
      height: 22px!important;
      line-height: 1.5;
      padding: 1px 6px!important;
      font-size: 11px!important;
    }
    .form-group{
      margin-bottom:5px!important;
    }
    .centered{
      font-size: 11px;
      text-align: left;
      white-space:nowrap;
    }
    .borrar_registro{
      display: inline;
      font-size: 16px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
    }

    table.dataTable tbody th, table.dataTable tbody td {
      padding: 0px 7px!important;
      font-size: 12px;
    }

    table td, .table th {
      padding: 0.75rem;
      vertical-align: middle!important;
      border-top: 1px solid #dee2e6;
    }

    .btn_delete_linea:hover{
      cursor: pointer;
    }
    
    .btn_modificar{
      display: block;
      text-align: center!important;
      margin:0 auto!important;
      font-size: 15px!important;
    }

    .modal_usuario{
      width: 84%!important;
    }

    fieldset {
      padding: .15em .625em .15em!important;
    }


    .tabla_listado2 #tabla_listado2 > tbody > tr > td {
        padding: 1px!important;
    }
    .table_text{
      text-align: left!important;
      font-size: 10.5px!important;
      margin: 0px!important;
      padding-left: 3px;
      padding-right: 3px;
     }
    .observacion_chk:focus,.observacion_chk:active{
      background-color: #fff!important;
    }
    .table_head{
      font-size: 12px!important;
      text-align: center!important;
    }
   
    .full-w{
      width: 90%!important;
    }
    .tabla_listado2 #tabla_listado2 .header th {
       height: 22px;
       font-size: 12.5px;
    }

    .tabla_listado2 #tabla_listado2 thead tr th{
      font-size: 11px!important;
    }
    .tabla_listado2 #tabla_listado2 tbody tr td{
      font-size: 10px!important;
    }

    .tabla_listado2 .dataTables_filter {
     display: none;
    }

    .form-control {
      font-size: 12px!important;
      padding: .375rem .75rem!important;
    }

    .custom-select{
      font-size: 12px!important;
    }

  }

  @media(max-width: 768px){
    .input-xs {
      height: 32px!important;
      line-height: 1.5;
      padding: 1px 1px!important;
      font-size: 11px!important;
    }
    .custom-select-sm {
     height: calc(2.6rem + 2px)!important; 
    }
    table.dataTable tbody th, table.dataTable tbody td {
      padding: 1px 3px!important;
      font-size: 14px!important;
    }

    .form-group{
      margin-bottom:5px!important;
    }
    .centered{
      font-size: 13px;
      text-align: left;
      white-space:nowrap;
    }
    .custom-select{
      font-size: 14px!important;
    }
    .form-control {
        font-size: 14px!important;
        padding: 0.575rem 0.75rem!important;
    }

    .borrar_registro{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 20px;
      text-decoration: none!important;
    }

    table.dataTable tbody th, table.dataTable tbody td {
      padding: 0px 7px!important;
      font-size: 14px;
    }

    table td, .table th {
      padding: 1.75rem;
      vertical-align: middle!important;
      border-top: 1px solid #dee2e6;
    }

    .btn_delete_linea:hover{
      cursor: pointer;
    }
    
    .btn_modificar{
      display: block;
      text-align: center!important;
      font-size: 18px!important;
    }

    .modal_usuario{
      width: 94%!important;
    }

    fieldset {
      padding: .15em .625em .15em!important;
    }

    .tabla_listado2 #tabla_listado2 > tbody > tr > td {
        padding: 2px!important;
    }
    .table_text{
      font-size: 12px!important;
      margin: 0px!important;
      padding-left: 1px;
      padding-right: 1px;
     }
    .observacion_chk:focus,.observacion_chk:active{
      background-color: #fff!important;
    }
    .table_head{
      font-size: 14px!important;
      text-align: center!important;
    }

    .full-w{
      width: 90%!important;
    }
    .tabla_listado2 #tabla_listado2 .header th {
       height: 22px;
       font-size: 12.5px;
    }

    .tabla_listado2 #tabla_listado2 thead tr th{
      font-size: 13px!important;
    }
    .tabla_listado2 #tabla_listado2 tbody tr td{
      font-size: 13px!important;
    }

    .tabla_listado2 .dataTables_filter {
     display: none;
    }

  }
  

</style>

<script type="text/javascript">
  $(function(){
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
      select : true,
     "oLanguage": { 
      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar: _MENU_ ",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Registros del _START_ al _END_ de un total de _TOTAL_ ",
      "sInfoEmpty":      "Sin registros",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix":    "",
      "sSearch":         "",     
      "sSearchPlaceholder": "Busqueda",
      "sUrl":            "",
      "sInfoThousands":  ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
      },
      "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
     },
    });

    $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
    $("#menu_usuarios").addClass('disabled_sub');
    $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
    $(".menu_lista li").removeClass('menuActivo');       
    $("#menu_usuarios").addClass('menuActivo');  

    $.get("vistaUsuarios", function( data ) {
      $(".contenedor_app").html(data);    
      $("#menu_usuarios").removeClass('disabled_sub');
    });
      

    $(document).off('click', '#menu_usuarios').on('click', '#menu_usuarios',function(event) {
      event.preventDefault();
      $("#menu_usuarios").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_usuarios").addClass('menuActivo');  

      $.get("vistaUsuarios", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_usuarios").removeClass('disabled_sub');
      });
    });



    $(document).off('click', '#menu_cargos').on('click', '#menu_cargos',function(event) {
      event.preventDefault();
      $("#menu_cargos").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_cargos").addClass('menuActivo');  

      $.get("vistaCargos", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_cargos").removeClass('disabled_sub');
      });
    });

    $(document).off('click', '#menu_proyectos').on('click', '#menu_proyectos',function(event) {
      event.preventDefault();
      $("#menu_proyectos").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_proyectos").addClass('menuActivo');  

      $.get("vistaProyectos", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_proyectos").removeClass('disabled_sub');
      });
    });

    $(document).off('click', '#menu_areas').on('click', '#menu_areas',function(event) {
      event.preventDefault();
      $("#menu_areas").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_areas").addClass('menuActivo');  

      $.get("vistaAreas", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_areas").removeClass('disabled_sub');
      });
    });

    $(document).off('click', '#menu_jefes').on('click', '#menu_jefes',function(event) {
      event.preventDefault();
      $("#menu_jefes").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_jefes").addClass('menuActivo');  

      $.get("vistaJefes", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_jefes").removeClass('disabled_sub');
      });
    });

    $(document).off('click', '#menu_perfiles').on('click', '#menu_perfiles',function(event) {
      event.preventDefault();
      $("#menu_perfiles").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_perfiles").addClass('menuActivo');  

      $.get("vistaPerfiles", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_perfiles").removeClass('disabled_sub');
      });
    });


    $(document).off('click', '#menu_herramientas').on('click', '#menu_herramientas',function(event) {
      event.preventDefault();
      $("#menu_herramientas").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
       $(".menu_lista li").removeClass('menuActivo');    
      $("#menu_herramientas").addClass('menuActivo');  
     

      $.get("vistaHerramientas", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_herramientas").removeClass('disabled_sub');
      });
    });


  })
</script>

<!-- MENU -->

<div class="contenido">
<div class="container-fluid">
<section>
<article class="content">

  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
       <ul class="nav nav-tabs navbar-left nav-tabs-int menu_lista">
        <li id="menu_usuarios" class="active"><a> <i class="fa fa-list-alt"></i> Mant. Usuarios </a></li>   
        <li id="menu_cargos" class="active"><a> <i class="fa fa-list-alt"></i> Mant. Cargos </a></li>   
        <li id="menu_proyectos" class="active"><a> <i class="fa fa-list-alt"></i> Mant. Proyectos </a></li>   
        <li id="menu_areas" class="active"><a> <i class="fa fa-list-alt"></i> Mant. Áreas </a></li>   
          

        <?php  
          if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
            ?>
              <li id="menu_jefes" class="active"><a> <i class="fa fa-list-alt"></i> Mant. Jefes </a></li>
            <?php
          }
        ?>

        <?php  
          if($this->session->userdata('id_perfil')==1){
            ?>
              <li id="menu_perfiles" class="active"><a> <i class="fa fa-list-alt"></i> Mant. Perfiles </a></li>   
            <?php
          }
        ?>
        
       
        <li id="menu_herramientas" class="active"><a> <i class="fa fa-th-list"></i> Mant. Herramientas</a></li>   

      </ul>  
    </div> 
  </div>

  <div class="row">
   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="contenedor_principal">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="contenedor_app"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

</article>  
</section>
</div>
</div>
