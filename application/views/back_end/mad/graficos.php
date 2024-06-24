<style type="text/css">
  .ejemplo_planilla{
    display: inline;
    cursor: pointer;
    color: #17A2B8;
    margin-top:7px;
  }

  .ver_obs_desp{
    cursor: pointer;
    display: inline;
    margin-left: 5px;
    font-size: 11px;
    color: #2780E3;
  }

  .modal-ejemplo{
    width:60%!important;
  }
  .centered {
    text-align: left!important ;
  }

  .actualizacion_productividad{
      display: inline-block;
      font-size: 11px;
  }


</style>

<script type="text/javascript">
  $(function(){
    const base_url = "<?php echo base_url() ?>";
    var perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    var user="<?php echo $this->session->userdata('id'); ?>";
    const base = "<?php echo base_url() ?>";

    var desde="<?php echo $desde; ?>";
    var hasta="<?php echo $hasta; ?>";
    $("#desde_f").val(desde);
    $("#hasta_f").val(hasta);

    $("#supervisor_f").select2({
      placeholder: 'Seleccione Supervisor',
      data: <?php echo $supervisores; ?>,
      width: '100%',
      allowClear:true,
    });

    function dataGraficos(){
        var desde_f = $("#desde_f").val();
        var hasta_f = $("#hasta_f").val();
        var f_coordinador = $("#f_coordinador").val();
        var f_comuna = $("#f_comuna").val();
        var f_zona = $("#f_zona").val();
        var f_empresa = $("#f_empresa").val();
        var supervisor_f = $("#supervisor_f").val();

        $.ajax({
            url: base_url+"getDataGraficosMad"+"?"+$.now(),  
            type: 'POST',
            data:{
                desde:desde_f,
                hasta:hasta_f,
                coordinador:f_coordinador,
                comuna:f_comuna,
                zona:f_zona,
                empresa:f_empresa,
                supervisor:supervisor_f,
            },
            dataType:"json",
            beforeSend:function(){
            },
            success: function (json) {
                setTimeout( function () {
                    $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
                }, 500 );

                /**GraficoResultadosxZona**/

                var data = google.visualization.arrayToDataTable(json.GraficoResultadosxZona.data);
                var options = {
                    width: '100%',
                    height: 400
                };
                var chart = new google.visualization.PieChart(document.getElementById('GraficoResultadosxZona'));
                chart.draw(data, options);

                /**GraficoResultadosxComuna**/

                var data = google.visualization.arrayToDataTable(json.GraficoResultadosxComuna.data);
                var options = {
                    width: '100%',
                    height: 400
                };
                var chart = new google.visualization.PieChart(document.getElementById('GraficoResultadosxComuna'));
                chart.draw(data, options);

                /**GraficoResultadosxEmpresa**/

                var data = google.visualization.arrayToDataTable(json.GraficoResultadosxEmpresa.data);
                var options = {
                    width: '100%',
                    height: 400
                };
                var chart = new google.visualization.PieChart(document.getElementById('GraficoResultadosxEmpresa'));
                chart.draw(data, options);

                 /**GraficoResultadosxSupervisor**/

                var data = google.visualization.arrayToDataTable(json.GraficoResultadosxSupervisor.data);
                var options = {
                  width: '100%',
                  height: 400,
                  isStacked: true
                };
                var chart = new google.visualization.BarChart(document.getElementById('GraficoResultadosxSupervisor'));
                chart.draw(data, options);
                 /**GraficoResultadosxCoordinador**/

                var data = google.visualization.arrayToDataTable(json.GraficoResultadosxCoordinador.data);
                var options = {
                  width: '100%',
                  height: 400,
                  isStacked: true
                };
                var chart = new google.visualization.BarChart(document.getElementById('GraficoResultadosxCoordinador'));
                chart.draw(data, options);

            }
        })
    }

    $(document).off('change', '#desde_f,#hasta_f,#f_coordinador,#f_comuna,#f_zona,#f_empresa,#supervisor_f').on('change', '#desde_f,#hasta_f,#f_coordinador,#f_comuna,#f_zona,#f_empresa,#supervisor_f', function (event) {
      dataGraficos();
    });
    
    dataGraficos();
  })

</script>

<!-- FILTROS -->
  
    <div class="form-row">

      <div class="col-12 col-lg-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="margin-left:5px;font-size:13px;">Fecha <span></span> 
            </div>
            <input type="date" placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="desde_f" id="desde_f">
            <input type="date" placeholder="Hasta" class="fecha_normal form-control form-control-sm"  name="hasta_f" id="hasta_f">
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-1">  
        <div class="form-group">
          <select id="f_coordinador" name="f_coordinador" class="custom-select custom-select-sm" style="width:100%!important;">
              <option value="">Seleccione coordinador | Todos</option>
              <?php 
                foreach($usuarios as $u){
                  ?>
                    <option value="<?php echo $u["id"]; ?>"><?php echo $u["nombre_completo"]?></option>
                  <?php
                }
              ?>
          </select>
        </div>
      </div>

      <div class="col-12 col-lg-1">  
        <div class="form-group">
          <select id="f_comuna" name="f_comuna" class="custom-select custom-select-sm" style="width:100%!important;">
              <option value="">Seleccione comuna | Todos</option>
              <?php 
                foreach($comunas as $c){
                  ?>
                    <option value="<?php echo $c["id"]; ?>"><?php echo $c["titulo"]?></option>
                  <?php
                }
              ?>
          </select>
        </div>
      </div>

      <div class="col-12 col-lg-1">  
        <div class="form-group">
          <select id="f_zona" name="f_zona" class="custom-select custom-select-sm" style="width:100%!important;">
              <option value="">Seleccione zona | Todos</option>
              <?php 
                foreach($zonas as $z){
                  ?>
                    <option value="<?php echo $z["id"]; ?>"><?php echo $z["area"]?></option>
                  <?php
                }
              ?>
          </select>
        </div>
      </div>

      <div class="col-12 col-lg-1">  
        <div class="form-group">
          <select id="f_empresa" name="f_empresa" class="custom-select custom-select-sm" style="width:100%!important;">
              <option value="">Seleccione empresa | Todos</option>
              <?php 
                foreach($proyectos as $p){
                  ?>
                    <option value="<?php echo $p["id"]; ?>"><?php echo $p["proyecto"]?></option>
                  <?php
                }
              ?>
          </select>
        </div>
      </div>
      
      <div class="col-6 col-lg-2">  
        <div class="form-group">
          <select id="supervisor_f" name="supervisor_f" class="custom-select custom-select-sm" style="width:100%!important;">
          <option value="">Seleccione Supervisor | Todos</option>
          </select>
        </div>
      </div>
      
    </div>            


<!-- Graficos -->

<div class="body">
  <div class="form-row mt-2">
    <div class="col-6 col-lg-6">
      <div class="card">
        <div class="col-12">
          <span class="title_section text-center">Auditoría por Zona</span>
          <div id="GraficoResultadosxZona"></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-6">
      <div class="form-row">
        <div class="col-6 col-lg-6">
          <div class="card">
            <div class="col-12">
              <span class="title_section text-center">Auditoría por Comuna</span>
              <div id="GraficoResultadosxComuna"></div>
            </div>
          </div>
        </div>
        <div class="col-6 col-lg-6">
          <div class="card">
            <div class="col-12">
              <span class="title_section text-center">Auditoría por Empresa</span>
              <div id="GraficoResultadosxEmpresa"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="form-row mt-2">
    <div class="col-6 col-lg-6">
      <div class="card">
        <div class="col-12">
          <span class="title_section text-center">Auditoría por supervisor</span>
          <div id="GraficoResultadosxSupervisor"></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-6">
      <div class="card">
        <div class="col-12">
          <span class="title_section text-center">Auditoría por coordinador</span>
          <div id="GraficoResultadosxCoordinador"></div>
        </div>
      </div>
    </div>
  </div>
</div>