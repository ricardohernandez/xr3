<style type="text/css">

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
                  class : " ",
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
