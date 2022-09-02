<style type="text/css">
  html, body{
    min-height: calc(100vh - 110px)
  }

  .disabled_sub{
      pointer-events:none;
      opacity:0.4;
  }

  ::selection {
    background: #8AC007; 
    color:#fff;
  }

  .contenedor_app{
    border: 1px solid #dce4ec;
    background-color: #F8F8F8;
    padding: 10px 5px;
    margin-bottom: 40px;
    border-radius: 1px;
    min-height: calc(100vh - 110px)

  }
  .btn-top{
    margin-top: 1px;
  }
  .btn-xs {
    padding: 0px 5px!important;
    font-size: 12px;
  }
  hr{
    margin-top: 9px!important;
    margin-bottom: 3px!important;;
  }
  .loader{
    margin-top:150px;
    height:100px;
    width:100px;
  }
   

</style>

<script type="text/javascript">
  $(function(){

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
        
        $("#menu_checklistHFC").addClass('disabled_sub');
        $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
        $(".menu_lista li").removeClass('menuActivo');    
        $("#menu_checklistHFC").addClass('menuActivo');  
       

        $.get("vistaMantChecklistHFC", function( data ) {
          $(".contenedor_app").html(data);    
          $("#menu_checklistHFC").removeClass('disabled_sub');
        });


      $(document).off('click', '#menu_checklistHFC').on('click', '#menu_checklistHFC',function(event) {
        event.preventDefault();
        $("#menu_checklistHFC").addClass('disabled_sub');
        $(".contenedor_app").html("<center><i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i></center>");
        $(".menu_lista li").removeClass('menuActivo');    
        $("#menu_checklistHFC").addClass('menuActivo');  
       

        $.get("vistaMantChecklistHFC", function( data ) {
          $(".contenedor_app").html(data);    
          $("#menu_checklistHFC").removeClass('disabled_sub');
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
        <li id="menu_checklistHFC" class="active"><a> <i class="fa fa-th-list"></i> Mant. Checklist HFC</a></li>
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
