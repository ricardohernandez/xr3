
<script type="text/javascript">
  $(function(){
    const perfil = "<?php echo $this->session->userdata('id_perfil'); ?>"

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
      lengthChange: true,
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

    $("#menu_materiales").addClass('disabled_sub');
    $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
    $(".menu_lista li").removeClass('menuActivo');       
    $("#menu_materiales").addClass('menuActivo');  

    $.get("vistaMaterialesDetalle", function( data ) {
      $(".contenedor_app").html(data);    
      $("#menu_materiales").removeClass('disabled_sub');
    });

    $(document).off('click', '#menu_materiales').on('click', '#menu_materiales',function(event) {
      event.preventDefault();
      $("#menu_materiales").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_materiales").addClass('menuActivo');  

      $.get("vistaMaterialesDetalle", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_materiales").removeClass('disabled_sub');
      });
    });

    $(document).off('click', '#menu_tecnico').on('click', '#menu_tecnico',function(event) {
      event.preventDefault();
      $("#menu_tecnico").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_tecnico").addClass('menuActivo');  

      $.get("vistaMaterialesPorTecnico", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_tecnico").removeClass('disabled_sub');
      });
    });

    $(document).off('click', '#menu_material').on('click', '#menu_material',function(event) {
      event.preventDefault();
      $("#menu_material").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_material").addClass('menuActivo');  

      $.get("vistaMaterialesPorMaterial", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_material").removeClass('disabled_sub');
      });
    });

    $(document).off('click', '#menu_series').on('click', '#menu_series',function(event) {
      event.preventDefault();
      $("#menu_series").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_series").addClass('menuActivo');  

      $.get("vistaSeriesPorTecnico", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_series").removeClass('disabled_sub');
      });
    });

    // $(document).off('click', '#menu_calidad').on('click', '#menu_calidad',function(event) {
    //   event.preventDefault();
    //   $("#menu_calidad").addClass('disabled_sub');
    //   $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
    //   $(".menu_lista li").removeClass('menuActivo');       
    //   $("#menu_calidad").addClass('menuActivo');  

    //   $.get("vistaCalidad", function( data ) {
    //     $(".contenedor_app").html(data);    
    //     $("#menu_calidad").removeClass('disabled_sub');
    //   });
    // });


  })
</script>

<!-- MENU -->

<div class="contenido" style="display:none;">
<div class="container-fluid">
<section>
<article class="content">

  <div class="row">
    <div class="col-12">
    <div class="scrollable-menu">

       <ul class="nav nav-tabs navbar-left nav-tabs-int menu_lista">
       <li id="menu_materiales" class="active"><a> <i class="fa fa-list-alt"></i> Detalle Materiales </a></li>   
       <!-- <li id="menu_tecnico" class="active"><a> <i class="fa fa-list-alt"></i> Por técnico </a></li>   
       <li id="menu_material" class="active"><a> <i class="fa fa-list-alt"></i> Por material </a></li>    -->
       <li id="menu_series" class="active"><a> <i class="fa fa-list-alt"></i> Series por técnico </a></li>   
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
