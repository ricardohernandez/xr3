<script type="text/javascript">
  $(function(){

  	const base_url = "<?php echo base_url() ?>"

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

    $("#condicion").addClass('disabled_sub');
	  $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
	  $(".menu_lista li").removeClass('menuActivo');       
	  $("#condicion").addClass('menuActivo');  

    $.get(base_url+"getEPPSInicio", function( data ) {
        $(".contenedor_app").html(data);    
        $("#condicion").removeClass('disabled_sub');
    });
 
    $(document).off('click', '#condicion').on('click', '#condicion',function(event) {
      event.preventDefault();
      $("#condicion").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#condicion").addClass('menuActivo');  

      $.get(base_url+"getEPPSInicio", function( data ) {
        $(".contenedor_app").html(data);    
        $("#condicion").removeClass('disabled_sub');
      });
    });

    $(document).off('click', '#investigacion').on('click', '#investigacion',function(event) {
      event.preventDefault();
      $("#investigacion").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#investigacion").addClass('menuActivo');  

      $.get(base_url+"getIAInicio", function( data ) {
        $(".contenedor_app").html(data);    
        $("#investigacion").removeClass('disabled_sub');
      });
    });

    $(document).off('click', '#reunion').on('click', '#reunion',function(event) {
      event.preventDefault();
      $("#reunion").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#reunion").addClass('menuActivo');  

      $.get(base_url+"getREInicio", function( data ) {
        $(".contenedor_app").html(data);    
        $("#reunion").removeClass('disabled_sub');
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
        <li id="condicion" class="active"><a> <i class="fa fa-list-alt"></i> EPPS y condiciones encontradas  </a></li>
        <li id="investigacion" class="active"><a> <i class="fa fa-list-alt"></i> Investigación de accidentes  </a></li>
        <li id="reunion" class="active"><a> <i class="fa fa-list-alt"></i> Reuniones de equipo  </a></li>
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
