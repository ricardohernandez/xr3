 
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
          paging:true,
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

         "oLanguage": { 
          "sProcessing":     "Procesando...",
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
        

        $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
        $(".menu_lista li").removeClass('menuActivo');
        $("#menu_checklist_ftth").addClass('menuActivo');  
     
        $.get("vistaChecklistFTTH", function( data ) {
          $(".contenedor_app").html(data);    
          $("#menu_checklist_ftth").removeClass('disabled_sub');
        });
        
      
      $(document).off('click', '#menu_checklist_ftth').on('click', '#menu_checklist_ftth',function(event) {
        event.preventDefault();
        $("#menu_checklist_ftth").addClass('disabled_sub');
        $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
        $(".menu_lista li").removeClass('menuActivo');
        $("#menu_checklist_ftth").addClass('menuActivo');  
       
        $.get("vistaChecklistFTTH", function( data ) {
          $(".contenedor_app").html(data);    
          $("#menu_checklist_ftth").removeClass('disabled_sub');
        });
      });

      $(document).off('click', '#menu_graficos').on('click', '#menu_graficos',function(event) {
        event.preventDefault();
        $("#menu_graficos").addClass('disabled_sub');
        $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
        $(".menu_lista li").removeClass('menuActivo');
        $("#menu_graficos").addClass('menuActivo');  

        $.get("vistaGraficosChecklistFTTH", function( data ) {
          $(".contenedor_app").html(data);    
          $("#menu_graficos").removeClass('disabled_sub');
        });
      });

      $(document).off('click', '#menu_fallos').on('click', '#menu_fallos',function(event) {
        event.preventDefault();
        $("#menu_fallos").addClass('disabled_sub');
        $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
        $(".menu_lista li").removeClass('menuActivo');
        $("#menu_fallos").addClass('menuActivo');  

        $.get("vistaFFTTH", function( data ) {
          $(".contenedor_app").html(data);    
          $("#menu_fallos").removeClass('disabled_sub');
        });
      });

      $(document).off('click', '#menu_graficos_fallos_ftth').on('click', '#menu_graficos_fallos_ftth',function(event) {
        event.preventDefault();
        $("#menu_graficos_fallos_ftth").addClass('disabled_sub');
        $(".contenedor_app").html("<center><i id='processingIcon' class='fa fa-cog fa-spin fa-4x' style='color:#233294;'></i></center>");
        $(".menu_lista li").removeClass('menuActivo'); 
        $("#menu_graficos_fallos_ftth").addClass('menuActivo');  
             
        $.get("vistaGraficosFallosFTTH", function( data ) {
          $(".contenedor_app").html(data);    
          $("#menu_graficos_fallos_ftth").removeClass('disabled_sub');
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
        <li id="menu_checklist_ftth" class="active"><a> <i class="fa fa-th-list"></i> Checklist FTTH</a></li>   

        <?php  
          if($this->session->userdata('id_perfil')<=3){
            ?>
              <li id="menu_graficos" class="active"><a> <i class="fa fa-chart-line"></i> Graficos </a></li>   
              <li id="menu_fallos" class="active"><a> <i class="fa fa-th-list"></i> Fallos </a></li>   
             <!--  <li id="menu_graficos_fallos_ftth" class="active"><a> <i class="fa fa-chart-line"></i> Graficos Fallos </a></li>    -->
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
