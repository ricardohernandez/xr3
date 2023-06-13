
<script type="text/javascript">
  $(function(){
    $.extend(true,$.fn.dataTable.defaults,{
      dom: "<'row'<'col-12 'f>>" + // Filtro
      "<'row'<'col-12 '<'table-responsive't>>>" + 
      "<'row d-none d-sm-flex '<'col-sm-4'l><'col-sm-4 text-center'i><'col-sm-4'p>>" + 
      "<'row d-sm-none '<'col-12 text-center'p>>", 

      "iDisplayLength":50, 
      "paging":true,
      "lengthChange": true,
      "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
      info:true,
      ordering:true,
      searching:true,
      bSort: true,
      bFilter: true,
      bProcessing: true,
      pagingType: "simple" , 
      bAutoWidth: true,
      sAjaxDataProp: "result",        
      bDeferRender: true,
      select : true,
     "oLanguage": { 
      "sProcessing":     "<i id='processingIconTable' class='fa-solid fa-circle-notch fa-spin fa-2x'></i>",
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

    $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
    $("#menu_usuarios").addClass('disabled_sub');
    $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
    $(".menu_lista li").removeClass('menuActivo');       
    $("#menu_usuarios").addClass('menuActivo');  

    $.get("vistaUsuarios", function( data ) {
      $(".contenedor_app").html(data);    
      $("#menu_usuarios").removeClass('disabled_sub');
    });

   /* $("#menu_responsables_fallos").addClass('disabled_sub');
    $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
    $(".menu_lista li").removeClass('menuActivo');    
    $("#menu_responsables_fallos").addClass('menuActivo');  
   

    $.get("vistaResponsablesFallosHerramientas", function( data ) {
      $(".contenedor_app").html(data);    
      $("#menu_responsables_fallos").removeClass('disabled_sub');
    });*/
    

    $(document).off('click', '#menu_usuarios').on('click', '#menu_usuarios',function(event) {
      event.preventDefault();
      $("#menu_usuarios").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
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
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
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
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
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
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
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
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
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
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
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
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
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
    <div class="col-12">
    <div class="scrollable-menu">

       <ul class="nav nav-tabs navbar-left nav-tabs-int menu_lista">
        <li id="menu_usuarios" class="active"><a> <i class="fa fa-list-alt"></i> Mant. Usuarios </a></li>   
        <li id="menu_cargos" class="active"><a> <i class="fa fa-list-alt"></i> Mant. Cargos </a></li>   
        <li id="menu_proyectos" class="active"><a> <i class="fa fa-list-alt"></i> Mant. Proyectos </a></li>   
        <li id="menu_areas" class="active"><a> <i class="fa fa-list-alt"></i> Mant. Zonas </a></li>   

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
       
       <!--  <li id="menu_herramientas" class="active"><a> <i class="fa fa-th-list"></i> Mant. Herramientas</a></li>    -->

      </ul>  
      </div> 
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
