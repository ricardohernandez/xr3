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

    $(".contenedor_app2").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");

    $.get(base_url+"getIgtInicio", function( data ) {
        $(".contenedor_app2").html(data);    
    });
 

    $(document).off('click', '#menu_ticket').on('click', '#menu_ticket',function(event) {
      event.preventDefault();
      $(".contenedor_app2").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       

      $.get(base_url+"getIgtInicio", function( data ) {
        $(".contenedor_app2").html(data);    
      });
    });




  })
</script>

<!-- MENU -->

<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="contenedor_app2"></div>
  </div>
</div>