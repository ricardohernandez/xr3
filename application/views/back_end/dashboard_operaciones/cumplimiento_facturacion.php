<style type="text/css">
  .DTFC_LeftBodyLiner {
    overflow-x: hidden;
  }
  .avg{
    border-radius: 10px;
    margin: auto;
  }
  .centered2{
    text-align: center!important;
  }

  .left {
    text-align: left!important ;
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
  var anio_f = $("#anio_f").val()
  var mes = $("#mes").val()

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
      anio_f:anio_f,
      mes:mes,
    })
    .then(data => {
          if(data.data.length!=0){
              if(reload){
                $('#tabla_cumplimiento').DataTable().clear().destroy();
                $('#tabla_cumplimiento').empty();
                $("#tabla_cumplimiento").append('<thead class="centered2 thead_table"></thead>')
                $("#tabla_cumplimiento").append('<tbody class="centered2 tbody_table"></tbody>')
                $("#tabla_cumplimiento").append('<tfoot class="centered2 tfoot_table"></tfoot>')
              }else{
                $('#tabla_cumplimiento').empty();
                $("#tabla_cumplimiento").append('<thead class="centered2 thead_table"></thead>')
                $("#tabla_cumplimiento").append('<tbody class="centered2 tbody_table"></tbody>')
                $("#tabla_cumplimiento").append('<tfoot class="centered2 tfoot_table"></tfoot>')
              }

              header = ["% Producción","% Calidad","% Asistencia"]
              avg = ["avg_cm","avg_ca","avg_as"]
              
              columns = [];
              columns.push({
                  data: "tecnico",
                  class : " ",
                  title: "Usuario"
              })

              //generar los headers
              $(".thead_table").append('<tr class="header1"></tr>')
              $(".thead_table").append('<tr class="header2"></tr>')
              $(".thead_table").append('<tr class="header3"></tr>')
              $(".header1").append('<th rowspan="3"></th>') //tecnico

              for(var key in data.data2){
                
                columnNames = (data.data2[key]);
  
                $(".header1").append('<th colspan="' + (columnNames.length*header.length) + '" class="thead">' +key + '</th>'); //header de año
  
                for (var i in header) { //header de %
                    $(".header2").append('<th colspan="' + (columnNames.length) + '" class="thead">' + header[i] + '</th>');
                }
  
                for (var i in header) { //header de meses
                  for (var j in columnNames) {
                    $(".header3").append('<th>'+columnNames[j]+'</th>')
                  }
                }
  

                  for (var i in avg) { 
                    for (var j in columnNames) {
                      columns.push({
                          data: key+"_"+columnNames[j]+"_"+avg[i],
                          class :avg[i],
                          title: ""+columnNames[j]+""
                      })
                    }
                  }
                
              }

            var tabla_cumplimiento = $('#tabla_cumplimiento').DataTable({
                columns: columns,
                info:false, 
                destroy: true,
                processing: true,  
                aaSorting : [[0,"asc"]],
                scrollY: "60vh",
                scrollX: true,
                select:true,
                bSort: true,
                scrollCollapse: true,
                paging:false,
                responsive:false,
                fixedColumns: {
                  start: 1,
                },
                oLanguage: { 
                  sProcessing:"<i id='processingIcon' class='fa-solid fa-circle-notch fa-spin fa-2x'></i>",
                },
                "ajax": {
                  "url":"<?php echo base_url();?>graficoCumpFact",
                  "dataSrc": "data2",
                  data: function(param){
                    var jefe =$("#jefe").val()
                    var anio =$("#anio").val()
                    var mes =$("#mes").val()
                    var anio_f =$("#anio_f").val()
                    param.jefe = jefe;
                    param.anio = anio;
                    param.anio_f = anio_f;
                    param.mes = mes;
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
            $(".tbody_table").html("");
          }
    
    $(".btn_filtro_turnos").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar').prop("disabled",false);
  });
}

procesaDatatable(false)


$(document).off('change', '#jefe,#anio,#anio_f,#mes').on('change', '#jefe,#anio,#anio_f,#mes', function(event) {
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
        <select id="anio_f" name="anio_f" class="custom-select custom-select-sm">
          <?php 
            foreach($anios as $a){
                $selected = ($a["anio"] == 2024) ? "selected" : ""; // Verifica si el año es igual a 2024
                ?>
                <option value="<?php echo $a["anio"] ?>" <?php echo $selected ?>><?php echo $a["anio"]?></option>
                <?php
            }
            ?>

        </select>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-2">
    <div class="form-group">
    <select id="mes" name="mes" class="custom-select custom-select-sm">
      <option value="">Seleccione mes</option>
      <?php 
        foreach($meses as $m){
          ?>
           <option value="<?php echo $m["id"]?>"><?php echo $m["mes"]?></option>
          <?php
        }
      ?>
    </select>
    </div>
  </div>

  <div class="col-12 col-lg-2">
    <div class="form-group">
    <select id="jefe" name="jefe" class="custom-select custom-select-sm">
      <option value="">Seleccione jefe</option>
      <?php 
        foreach($jefes as $j){
            $selected = ($j["jefe"] == "ALVARO PAREDES") ? "selected" : ""; // Verifica si es igual a "ALVARO PAREDES"
            ?>
            <option value="<?php echo $j["jefe"]?>" <?php echo $selected ?>><?php echo $j["jefe"]?></option>
            <?php
        }
        ?>
    </select>
    </div>
  </div>

  <div class="col-3 col-lg-3">
    <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="font-size:12px;margin-left:5px;"> Última carga<span></span> 
        </div>  
        <input value="<?php echo $ultima_actualizacion?>" type="text" disabled placeholder="Desde" class=" form-control form-control-sm"  name="ultima_actualizacion" id="ultima_actualizacion">
      </div>
    </div>
  </div>
  
</div>   

<div class="row">
  <div class="col-lg-12">
    <div class="row">
      <div class="col-lg-12">
        <table id="tabla_cumplimiento" class="left table tabla_fixed table-bordered table-striped dt-responsive nowrap dataTable trow-border order-column" style="width:100%">
        </table>
      </div>
    </div>
  </div>
</div>