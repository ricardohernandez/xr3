<style type="text/css">
  .DTFC_LeftBodyLiner {
    overflow-x: hidden;
  }
  .avg{
    border-radius: 10px;
    margin: auto;
  }
  .avg_cm{
    background-color: #395A7F;
    color:white;
  }
  .avg_ca{
    background-color: #6E9FC1;
    color:white;
  }
  .avg_as{
    background-color: #A3CAE9;
    color:white;
  }
  .centered2{
      text-align: center!important;
  }

  .table thead th , .table tfoot th  {
    font-size: 12px!important;
  }

  .table tbody td {
    font-size: 11px!important;
  }

  .table thead th ,.table tbody td , .table tfoot th  {
    padding-left: 1rem!important;
    padding-right: 1rem!important;
  }

  table.dataTable thead .sorting:before, table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:before, table.dataTable thead .sorting_desc_disabled:after {
    bottom: 2px;
    display: none!important;
  }

  table.dataTable thead>tr>th.sorting_asc, table.dataTable thead>tr>th.sorting_desc, table.dataTable thead>tr>th.sorting, table.dataTable thead>tr>td.sorting_asc, table.dataTable thead>tr>td.sorting_desc, table.dataTable thead>tr>td.sorting {
    padding-right: 5px!important;
  }
  
  .dataTables_wrapper {
      clear: both;
      min-height: 302px;
      position: relative;
  }

  .fecha2_cont{
    display: none;
  }
</style>

<script>
  $(function(){
  const p = "<?php echo $this->session->userdata('id_perfil'); ?>";
  const base = "<?php echo base_url() ?>";

/*****DATATABLE*****/   

const procesaDatatable = (reload) => {
  var jefe = $("#jefe").val()
  var anio = $("#anio").val()

  async function enviaDatos(url = '', data = {}) {
      const response = await fetch(url, {
        method: 'POST', 
        mode: 'cors', 
        cache: 'no-cache',
        credentials: 'same-origin', 
        headers: {
          'Content-Type': 'application/json'
        },
        redirect: 'follow',
        referrerPolicy: 'strict-origin-when-cross-origin',
        body: JSON.stringify(data)
      });

      return response.json(); 
  }

  enviaDatos(base+"getCabecerasCumplimientoFacturacion"+"?"+$.now(), 
    {

      jefe:jefe,
      anio:anio,
    })
    .then(data => {
      $(".btn_filtro_turnos").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar').prop("disabled",false);
      if(data.data.length!=0){
         if(reload){
            $('#tabla_cumplimiento').html("");
            $('#tabla_cumplimiento').DataTable().clear().destroy();
            $("#tabla_cumplimiento tbody").html("");
            $("#tabla_cumplimiento thead").html("");
            $("#tabla_cumplimiento tfoot").html("");
            $("#tabla_cumplimiento tfoot").html('<tr class="tfoot_table"></tr>')
          }else{
            $("#tabla_cumplimiento").append('<tfoot><tr class="tfoot_table"></tr></tfoot>')
          }
          
          columns = [];
          columnNames = (data.data);

          $(".tfoot_table").append('<th class="tfoot"></th>')
            columns.push({
                data: "tecnico",
                class : " ",
                title: "Usuario"
          })

          header = ["% Producción","% Calidad","% Asistencia"]
          avg = ["avg_cm","avg_ca","avg_as"]
          for (var i in avg) { 
            for (var j in columnNames) {
              $(".tfoot_table").append('<th class="tfoot"></th>')
              columns.push({
                  data: columnNames[j]+"_"+avg[i],
                  class : avg[i],
                  title: ""+columnNames[j]+""
              })
            }
          }

         var tabla_cumplimiento = $('#tabla_cumplimiento').DataTable({
            columns: columns,
            info:false, 
            destroy: true,
            processing: true,  
            aaSorting : [[0,"asc"]],
            scrollY: "65vh",
            scrollX: true,
            select:true,
            bSort: true,
            scrollCollapse: true,
            paging:false,
            responsive:false,
            oLanguage: { 
              sProcessing:"<i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i>",
            },
            "ajax": {
              "url":"<?php echo base_url();?>graficoCumpFact",
              "dataSrc": "data",
               data: function(param){
                var jefe =$("#jefe").val()
                var anio =$("#anio").val()
                param.jefe = jefe;
                param.anio = anio;
             }
          },    
        });

       setTimeout( function () {
          var tabla_cumplimiento = $.fn.dataTable.fnTables(true);
          if ( tabla_cumplimiento.length > 0 ) {
              $(tabla_cumplimiento).dataTable().fnAdjustColumnSizing();
        }}, 200 ); 

        setTimeout( function () {
          var tabla_cumplimiento = $.fn.dataTable.fnTables(true);
          if ( tabla_cumplimiento.length > 0 ) {
              $(tabla_cumplimiento).dataTable().fnAdjustColumnSizing();
        }}, 2000 ); 

        setTimeout( function () {
          var tabla_cumplimiento = $.fn.dataTable.fnTables(true);
          if ( tabla_cumplimiento.length > 0 ) {
              $(tabla_cumplimiento).dataTable().fnAdjustColumnSizing();
          }
        }, 4000 ); 

      }else{
        $("#tabla_cumplimiento").DataTable().clear().draw()
        $(".tfoot_table").html("");
        // $("#tabla_cumplimiento tfoot").html('')
      }
    
    $(".btn_filtro_turnos").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar').prop("disabled",false);
  });
}

procesaDatatable(false)


$(document).off('change', '#jefe,#anio').on('change', '#jefe,#anio', function(event) {
   procesaDatatable(true)
}); 

})
</script>

<!-- FILTROS -->
  
<div class="form-row" id="contenedor_filtros_c">

  <div class="col-12 col-lg-3">
    <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="font-size:12px;margin-left:5px;"> Año<span></span> 
        </div>
        <select id="anio" name="anio" class="custom-select custom-select-sm">
          <?php 
            foreach($anios as $a){
              ?>
              <option value="<?php echo $a["anio"] ?>"><?php echo $a["anio"]?></option>
              <?php
            }
          ?>
        </select>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-2">
    <div class="form-group">
    <select id="jefe" name="jefe" class="custom-select custom-select-sm">
      <option value="">Seleccione jefe</option>
      <?php 
        foreach($jefes as $j){
          ?>
           <option selected value="<?php echo $j["jefe"]?>"><?php echo $j["jefe"]?></option>
          <?php
        }
      ?>
    </select>
    </div>
  </div>

    <div class="col-3 col-lg-1 avg avg_cm"> % producción</div>
    <div class="col-3 col-lg-1 avg avg_ca"> % calidad</div>
    <div class="col-3 col-lg-1 avg avg_as"> % asistencia </div>





</div>   

<div class="row">
  <div class="col-lg-12">
    <div class="row">
      <div class="col-lg-12">
        <table id="tabla_cumplimiento" class="table tabla_fixed table-bordered table-striped dt-responsive nowrap dataTable trow-border order-column" style="width:100%">
        </table>
      </div>
    </div>
  </div>
</div>
