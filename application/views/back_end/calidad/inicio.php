
<script type="text/javascript">
  $(function(){
    const perfil = "<?php echo $this->session->userdata('id_perfil'); ?>"

    $.extend(true,$.fn.dataTable.defaults,{
      dom: "<'row '<'col-sm-12'f>>" +
            "<'row'<'col-sm-12'tr>> <'bottom' <'row  mt-3' <'col-4' l><'col-4 text-center' i>  <'col-4' p>> >",
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


    $("#menu_graficos").addClass('disabled_sub');
    $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
    $(".menu_lista li").removeClass('menuActivo');       
    $("#menu_graficos").addClass('menuActivo');  

    $.get("vistaGraficosCalidad", function( data ) {
      $(".contenedor_app").html(data);    
      $("#menu_graficos").removeClass('disabled_sub');
    });
  
    $(document).off('click', '#menu_calidad').on('click', '#menu_calidad',function(event) {
      event.preventDefault();
      $("#menu_calidad").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_calidad").addClass('menuActivo');  

      $.get("vistaCalidad", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_calidad").removeClass('disabled_sub');
      });
    });

    $(document).off('click', '#menu_graficos').on('click', '#menu_graficos',function(event) {
      event.preventDefault();
      $("#menu_graficos").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_graficos").addClass('menuActivo');  

      $.get("vistaGraficosCalidad", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_graficos").removeClass('disabled_sub');
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
        <li id="menu_graficos" class="active"><a> <i class="fa fa-list-alt"></i> Graficos Calidad </a></li>   
        <li id="menu_calidad" class="active"><a> <i class="fa fa-list-alt"></i> Detalle Calidad </a></li>   
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
