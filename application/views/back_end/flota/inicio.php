<script type="text/javascript">
  $(function(){

    const base_url = "<?php echo base_url() ?>"

    $.extend(true,$.fn.dataTable.defaults,{
     /*  dom: "<'row '<'col-sm-12'f>>" +
      "<'row'<'col-sm-12'tr>> <'bottom' <'row  mt-3' <'col-4' l><'col-4 text-center' i>  <'col-4' p>> >", */
      "iDisplayLength":50, 
      "paging":false,
      "lengthChange": false,
      "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
      info:true,
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

    $.get(base_url+"getFlotaInicio", function( data ) {
        $(".contenedor_app").html(data);    
        $(".menu_lista li").removeClass('menuActivo');       
        $("#menu_combustible").addClass('menuActivo');  
    });
 

    $(document).off('click', '#menu_combustible').on('click', '#menu_combustible',function(event) {
      event.preventDefault();
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       

      $.get(base_url+"getFlotaInicio", function( data ) {
        $(".contenedor_app").html(data);  
        $("#menu_combustible").addClass('menuActivo');  
      });
    });

    $(document).off('click', '#menu_gps_muevo').on('click', '#menu_gps_muevo',function(event) {
      event.preventDefault();
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       

      $.get(base_url+"getGPSInicioMuevo", { gps : "MUEVO" }, function( data ) {
        $(".contenedor_app").html(data);  
        $("#menu_gps_muevo").addClass('menuActivo');  
      });
    });

    $(document).off('click', '#menu_gps_salfa').on('click', '#menu_gps_salfa',function(event) {
      event.preventDefault();
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       

      $.get(base_url+"getGPSInicio", { gps : "SALFA" } , function( data ) {
        $(".contenedor_app").html(data);   
        $("#menu_gps_salfa").addClass('menuActivo');  
      });
    });

    $(document).off('click', '#menu_gps_black').on('click', '#menu_gps_black',function(event) {
      event.preventDefault();
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       

      $.get(base_url+"getGPSInicio", { gps : "BLACK" } , function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_gps_black").addClass('menuActivo');  
      });
    });

    $(document).off('click', '#menu_gps').on('click', '#menu_gps',function(event) {
      event.preventDefault();
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       

      $.get(base_url+"getGPSInicio", { gps : "WEBFLEET" } , function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_gps").addClass('menuActivo');  
      });
    });

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
       <li id="menu_combustible" class="active"><a> <i class="fa fa-droplet"></i> TCT </a></li> 
       <li id="menu_gps_muevo" class="active"><a> <i class="fa fa-droplet"></i> MUEVO </a></li>     
       <li id="menu_gps" class="active"><a> <i class="fa fa-droplet"></i> GPS - WEBFLEET </a></li>   
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