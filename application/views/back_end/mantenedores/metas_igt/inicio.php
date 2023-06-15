 
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
          lengthChange: true,
          ordering:true,
          searching:true,
          bSort: true,
          bFilter: true,
          bProcessing: true,
          pagingType: "simple" , 
          bAutoWidth: true,
          sAjaxDataProp: "result",        
          bDeferRender: true,

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
          // language : {
          //   url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
          // },
        });
        
        $("#menu_metas_igt").addClass('disabled_sub');
        $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
        $(".menu_lista li").removeClass('menuActivo');    
        $("#menu_metas_igt").addClass('menuActivo');  
       

        $.get("vistaMetasIgt", function( data ) {
          $(".contenedor_app").html(data);    
          $("#menu_metas_igt").removeClass('disabled_sub');
        });


      $(document).off('click', '#menu_metas_igt').on('click', '#menu_metas_igt',function(event) {
        event.preventDefault();
        $("#menu_metas_igt").addClass('disabled_sub');
        $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
        $(".menu_lista li").removeClass('menuActivo');    
        $("#menu_metas_igt").addClass('menuActivo');  
       

        $.get("vistaMetasIgt", function( data ) {
          $(".contenedor_app").html(data);    
          $("#menu_metas_igt").removeClass('disabled_sub');
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
        <li id="menu_metas_igt" class="active"><a> <i class="fa fa-th-list"></i> Mant. Metas IGT</a></li>
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
