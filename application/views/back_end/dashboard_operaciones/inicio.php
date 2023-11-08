<script type="text/javascript">
  $(function(){
    const base_url = "<?php echo base_url() ?>"

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
      paging:true,
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
    
    vistaProductividadCalidadXr3()
    
    $(document).off('click', '#menu_prod_cal_xr3').on('click', '#menu_prod_cal_xr3',function(event) {
      event.preventDefault();
      vistaProductividadCalidadXr3()
    });

    $(document).off('click', '#menu_prod_cal_eps').on('click', '#menu_prod_cal_eps',function(event) {
      event.preventDefault();
      vistaProductividadCalidadEps()
    });

    $(document).off('click', '#menu_dotacion').on('click', '#menu_dotacion',function(event) {
      event.preventDefault();
      vistaDotacion()
    });

    $(document).off('click', '#menu_analisis_calidad').on('click', '#menu_analisis_calidad',function(event) {
      event.preventDefault();
      vistaAnalisisCalidad()
    });


    $(document).off('click', '#prod_cal_claro').on('click', '#prod_cal_claro',function(event) {
      event.preventDefault();
      vistaProdCalClaro()
    });

    $(document).off('click', '#prod_x_comuna').on('click', '#prod_x_comuna',function(event) {
      event.preventDefault();
      vistaProdXComuna()
    });

    $(document).off('click', '#cump_fact').on('click', '#cump_fact',function(event) {
      event.preventDefault();
      vistaCumpFact()
    });

    function vistaProductividadCalidadXr3(){
      $("#menu_prod_cal_xr3").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x' ></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_prod_cal_xr3").addClass('menuActivo');  

      $.get(base_url+"dashboard/produccion_calidad_xr3", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_prod_cal_xr3").removeClass('disabled_sub');
      });

    }

    
    function vistaProductividadCalidadEps(){
      $("#menu_prod_cal_eps").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x' ></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_prod_cal_eps").addClass('menuActivo');  

      $.get(base_url+"dashboard/produccion_calidad_EPS", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_prod_cal_eps").removeClass('disabled_sub');
      });

    }


    function vistaDotacion(){
      $("#menu_dotacion").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x' ></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_dotacion").addClass('menuActivo');  

      $.get(base_url+"dashboard/dotacion", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_dotacion").removeClass('disabled_sub');
      });

    }

    function vistaAnalisisCalidad(){
      $("#menu_analisis_calidad").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x' ></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#menu_analisis_calidad").addClass('menuActivo');  

      $.get(base_url+"dashboard/analisis_calidad", function( data ) {
        $(".contenedor_app").html(data);    
        $("#menu_analisis_calidad").removeClass('disabled_sub');
      });

    }

    function vistaProdCalClaro(){
      $("#prod_cal_claro").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x' ></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#prod_cal_claro").addClass('menuActivo');  

      $.get(base_url+"dashboard/prod_cal_claro", function( data ) {
        $(".contenedor_app").html(data);    
        $("#prod_cal_claro").removeClass('disabled_sub');
      });

    }

    function vistaProdXComuna(){
      $("#prod_x_comuna").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x' ></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#prod_x_comuna").addClass('menuActivo');  

      $.get(base_url+"dashboard/prod_x_comuna", function( data ) {
        $(".contenedor_app").html(data);    
        $("#prod_x_comuna").removeClass('disabled_sub');
      });

     /*  window.history.replaceState('statedata', 'title', 'dashboard_operaciones'); */
    }

    function vistaCumpFact(){
      $("#cump_fact").addClass('disabled_sub');
      $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x' ></i></center>");
      $(".menu_lista li").removeClass('menuActivo');       
      $("#cump_fact").addClass('menuActivo');  

      $.get(base_url+"dashboard/cumpl_factura", function( data ) {
        $(".contenedor_app").html(data);    
        $("#cump_fact").removeClass('disabled_sub');
      });

    }


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
       <li id="menu_prod_cal_xr3" class="active"><a> <i class="fa fa-chart-line"></i> Productividad y calidad XR3 </a></li>   
       <li id="menu_prod_cal_eps" class="active"><a> <i class="fa fa-chart-line"></i> Productividad y calidad EPS </a></li>   
       <li id="menu_dotacion" class="active"><a> <i class="fa fa-chart-line"></i> Dotacion FTE </a></li>   
       <li id="menu_analisis_calidad" class="active"><a> <i class="fa fa-chart-line"></i> Analisis Calidad </a></li>   
       <li id="prod_cal_claro" class="active"><a> <i class="fa fa-chart-line"></i> Producción y calidad CLARO  </a></li>   
       <li id="prod_x_comuna" class="active"><a> <i class="fa fa-chart-line"></i> Producción por comuna y empresa  </a></li>  
       <li id="cump_fact" class="active"><a> <i class="fa fa-chart-line"></i> Evolutivo técnico </a></li> 
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
