 
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
      "lengthMenu": [[5, 15, 50], [5, 15, 50]],
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
    
    $("#menu_ast").addClass('disabled_sub');
    $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
    $(".menu_lista li").removeClass('menuActivo');  
    $("#menu_ast").addClass('menuActivo');  
    
    $.get("vistaAst", function( data ) {
      $(".contenedor_app").html(data);    
      $("#menu_ast").removeClass('disabled_sub');
    });
        
    $(document).off('click', '#menu_ast').on('click', '#menu_ast',function(event) {
      event.preventDefault();
      $("#menu_ast").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');  
      $("#menu_ast").addClass('menuActivo');  
      
      $.get("vistaAst", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_ast").removeClass('disabled_sub');
      });
    });

    $(document).off('click', '#menu_grafico').on('click', '#menu_grafico',function(event) {
      event.preventDefault();
      $("#menu_grafico").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');  
      $("#menu_grafico").addClass('menuActivo');  
      
      $.get("vistaGraficosAst", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_grafico").removeClass('disabled_sub');
      });
    });

    $(document).off('click', '#menu_mantenedor_actividades').on('click', '#menu_mantenedor_actividades',function(event) {
      event.preventDefault();
      $("#menu_mantenedor_actividades").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
      $(".menu_lista li").removeClass('menuActivo');  
      $("#menu_mantenedor_actividades").addClass('menuActivo');  
      
      $.get("vistaMantActividades", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_mantenedor_actividades").removeClass('disabled_sub');
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
        <li id="menu_ast" class="active"><a> <i class="fa fa-th-list"></i> AST Análisis seguro de trabajo </a></li>   
          <?php  
          if($this->session->userdata('id_perfil')<=3){
          ?>
            <li id="menu_grafico" class="active"><a> <i class="fa fa-dashboard"></i> Graficos </a></li>   
            <li id="menu_mantenedor_actividades" class="active"><a> <i class="fa fa-th-list"></i> Mantenedor Subactividades </a></li>   
          <?php  
          }
          ?>
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
