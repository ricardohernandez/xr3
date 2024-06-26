<style type="text/css">
  .actualizacion_materiales{
      display: inline-block;
      font-size: 11px;
  }
</style>

<script type="text/javascript">
  $(function(){

    var perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    const base = "<?php echo base_url() ?>";

    $("#comunas_f").select2({
      placeholder: 'Seleccione Comuna',
      data: <?php echo $comunas; ?>,
      width: '100%',
      allowClear:true,
    });

  /*****DATATABLE*****/   
    var tabla_series_devolucion = $('#tabla_series_devolucion').DataTable({
       "aaSorting" : [[0,"asc"]],
       "scrollY": "65vh",
       "responsive":false,
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       // "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>listaSeriesDevolucion",
          "dataSrc": function (json) {
            $(".btn_filtro_series_devolucion").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_series_devolucion").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            
            if(perfil==4){
              param.trabajador = $("#trabajador").val();
            }else{
              param.trabajador = $("#trabajadores").val();
            }
            param.comuna = $("#comunas_f").val();  

          }
        },    
        "columns": [
          /* { "data": "tipo" ,"class":"margen-td centered"}, */
          { "data": "material_comuna" ,"class":"margen-td centered"},
          { "data": "serie" ,"class":"margen-td centered"},
          { "data": "tipo" ,"class":"margen-td centered"},
        ]
      }); 

      $(document).on('keyup paste', '#buscador_series_devolucion', function() {
        tabla_series_devolucion.search($(this).val().trim()).draw();
      });

    

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var tabla_series_devolucion = $.fn.dataTable.fnTables(true);
        if ( tabla_series_devolucion.length > 0 ) {
            $(tabla_series_devolucion).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var tabla_series_devolucion = $.fn.dataTable.fnTables(true);
        if ( tabla_series_devolucion.length > 0 ) {
            $(tabla_series_devolucion).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var tabla_series_devolucion = $.fn.dataTable.fnTables(true);
        if ( tabla_series_devolucion.length > 0 ) {
            $(tabla_series_devolucion).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 


  /********OTROS**********/
    
    $(document).off('click', '.excel_series_devolucion"').on('click', '.excel_series_devolucion',function(event) {
       event.preventDefault();
      // var desde = $("#desde_f").val();
      // var hasta = $("#hasta_f").val();  
      if(perfil==4){
        trabajador = $("#trabajador").val()
      }else{
        trabajador = $("#trabajadores").val();
      }
      comunas = $("#comunas_f").val();

      var jefe = perfil<=3 ? $("#jefe_det").val() : "-";
      jefe = jefe=="" ? "-" : jefe;

      if(trabajador==""){
         trabajador="-"
      }
      if(comunas==""){
         comunas="-"
      }

      window.location="excel_series_devolucion/"+trabajador+"/"+jefe+"/"+comunas;

    });


   /*****DATATABLE*****/   
    var tabla_series_operativos = $('#tabla_series_operativos').DataTable({
       "aaSorting" : [[0,"asc"]],
       "scrollY": "65vh",
       "scrollX": true,
       "responsive":false,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       // "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>listaSeriesOperativos",
          "dataSrc": function (json) {
            $(".btn_filtro_series").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_series").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            
            if(perfil==4){
              param.trabajador = $("#trabajador").val();
            }else{
              param.trabajador = $("#trabajadores").val();
            }
            param.comuna = $("#comunas_f").val();

          }
        },    
        "columns": [
          /* { "data": "tipo" ,"class":"margen-td centered"}, */
          { "data": "material_comuna" ,"class":"margen-td centered"},
          { "data": "serie" ,"class":"margen-td centered"},
        ]
      }); 

      $(document).on('keyup paste', '#buscador_series_operativos', function() {
        tabla_series_operativos.search($(this).val().trim()).draw();
      });


      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var tabla_series_operativos = $.fn.dataTable.fnTables(true);
        if ( tabla_series_operativos.length > 0 ) {
            $(tabla_series_operativos).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var tabla_series_operativos = $.fn.dataTable.fnTables(true);
        if ( tabla_series_operativos.length > 0 ) {
            $(tabla_series_operativos).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var tabla_series_operativos = $.fn.dataTable.fnTables(true);
        if ( tabla_series_operativos.length > 0 ) {
            $(tabla_series_operativos).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 


    $(document).off('click', '.btn_filtro_series').on('click', '.btn_filtro_series',function(event) {
        event.preventDefault();
         $(this).prop("disabled" , true);
         $(".btn_filtro_series").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
         tabla_series_devolucion.ajax.reload();
         tabla_series_operativos.ajax.reload();
    });

    $(document).off('click', '.excel_series_devolucion"').on('click', '.excel_series_devolucion',function(event) {
       event.preventDefault();
      // var desde = $("#desde_f").val();
      // var hasta = $("#hasta_f").val();  
      if(perfil==4){
        trabajador = $("#trabajador").val()
      }else{
        trabajador = $("#trabajadores").val();
      }
      comunas = $("#comunas_f").val();

      var jefe = perfil<=3 ? $("#jefe_det").val() : "-";
      jefe = jefe=="" ? "-" : jefe;

      if(trabajador==""){
         trabajador="-"
      }
      if(comunas==""){
         comunas="-"
      }

      window.location="excel_series_devolucion/"+trabajador+"/"+jefe+"/"+comunas;

    });

    actualizacionMateriales()

    function actualizacionMateriales(){
      $.ajax({
          url: "actualizacionMateriales"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          dataType:"json",
          beforeSend:function(){
          },
          success: function (data) {
            if(data.res=="ok"){
              $(".actualizacion_materiales").html("<b>Última actualización planilla : "+data.datos+"</b>");
            }
          },
          error : function(xhr, textStatus, errorThrown ) {
            if (textStatus == 'timeout') {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.notify("Reintentando...", {
                      className:'info',
                      globalPosition: 'top right'
                    });
                    $.ajax(this);
                    return;
                } else{
                    $.notify("Problemas en el servidor, intente nuevamente.", {
                      className:'warn',
                      globalPosition: 'top right'
                    });     
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
            }
          },timeout:5000
      }); 
    }
    
    
    $(document).off('click', '.excel_series_operativos"').on('click', '.excel_series_operativos',function(event) {
       event.preventDefault();
      // var desde = $("#desde_f").val();
      // var hasta = $("#hasta_f").val();  
      if(perfil==4){
        trabajador = $("#trabajador").val()
      }else{
        trabajador = $("#trabajadores").val();
      }
      comunas = $("#comunas_f").val();

      var jefe = perfil<=3 ? $("#jefe_det").val() : "-";
      jefe = jefe=="" ? "-" : jefe;

      if(trabajador==""){
         trabajador="-"
      }
      if(comunas==""){
         comunas="-"
      }

      window.location="excel_series_operativos/"+trabajador+"/"+jefe+"/"+comunas;

    });


    $.getJSON(base + "listaTrabajadoresMateriales", function(data) {
      response = data;
    }).done(function() {
        $("#trabajadores").select2({
         placeholder: 'Seleccione Trabajador | Todos',
         data: response,
         width: 'resolve',
         allowClear:true,
        });
    });
 
    $(document).off('change', '#periodo , #trabajadores ,#jefe_det, #comunas_f').on('change', '#periodo , #trabajadores ,#jefe_det, #comunas_f', function(event) {
      tabla_series_devolucion.ajax.reload()
      tabla_series_operativos.ajax.reload()
    }); 


  })
</script>

<!-- FILTROS -->
  
  <div class="form-row mb-2">
    <?php  
      if($this->session->userdata('id_perfil')<>4){
        ?>
          <div class="col-12 col-lg-6">  
          
            <div class="form-group">
              <select id="trabajadores" name="trabajadores" style="width:100%!important;">
                  <option value="">Seleccione Trabajador | Todos</option>
              </select>
            </div>
          </div>
        <?php
      }else{
      ?>
          <div class="col-12 col-lg-6">  
            <div class="form-group">
              <select id="trabajador" name="trabajador" class="custom-select custom-select-sm" >
                  <option selected value="<?php echo $this->session->userdata('rut'); ?>"><?php echo $this->session->userdata('nombre_completo'); ?></option>
              </select>
            </div>
          </div>
      <?php
      }
    ?>

      <div class="col-6 col-lg-2">  
        <div class="form-group">
          <select id="comunas_f" name="comunas_f" class="custom-select custom-select-sm" style="width:100%!important;">
          <option selected value=""></option>
          </select>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <center><span class="titulo_fecha_actualizacion_dias">
          <div class="alert alert-primary actualizacion_materiales" role="alert" style="padding: .15rem 1.25rem;margin-bottom: .1rem;">
          </div>
        </span></center>
      </div>

  </div>            

<!-- LISTADO -->



  <div class="row mt-2">
    <div class="col-lg-6">
      <div class="col-12">

        <div class="row">
          <div class="col-12 col-lg-4">
          <h6 class="text-center mt-2 title_section">Equipos para devolución (Retiro)</h6>
        </div>

        <div class="col-6  col-lg-4">
         <input type="text" placeholder="Busqueda" id="buscador_series_devolucion" class="buscador_series_devolucion form-control form-control-sm">
        </div>

        <div class="col-6  col-lg-4">
          <button type="button"  class="btn-block btn btn-sm btn-primary excel_series_devolucion btn_xr3">
          <i class="fa fa-save"></i> Excel
          </button>
        </div>

        </div>
      </div>
 
      <table id="tabla_series_devolucion" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <!-- <th class="centered">Tipo</th>  -->
            <th class="centered">Descripción</th> 
            <th class="centered">Serie</th> 
            <th class="centered">Tipo</th> 
          </tr>
        </thead>
      </table>
    </div>

    <div class="col-lg-6">

      <div class="row">
      <div class="col-12 col-lg-4">
          <h6  class="text-center mt-2 title_section">Equipos operativos</h6>
        </div>

        <div class="col-6  col-lg-4">
         <input type="text" placeholder="Busqueda" id="buscador_series_operativos" class="buscador_series_operativos form-control form-control-sm">
        </div>

        <div class="col-6  col-lg-4">
          <button type="button"  class="btn-block btn btn-sm btn-primary excel_series_operativos btn_xr3">
          <i class="fa fa-save"></i> Excel
          </button>
        </div>
      </div>

      <table id="tabla_series_operativos" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <!-- <th class="centered">Tipo</th>  -->
            <th class="centered">Descripción</th> 
            <th class="centered">Serie</th> 
          </tr>
        </thead>
      </table>
    </div>


  </div>
