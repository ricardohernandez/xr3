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

    $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");

    $.get(base_url+"getIgtInicio", function( data ) {
        $(".contenedor_app").html(data);    
    });
 

    $(document).off('click', '#menu_ticket').on('click', '#menu_ticket',function(event) {
      event.preventDefault();
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       

      $.get(base_url+"getIgtInicio", function( data ) {
        $(".contenedor_app").html(data);    
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
