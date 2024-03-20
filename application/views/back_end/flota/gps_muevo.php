<style type="text/css">
  .centered {
    text-align: left!important ; /* Cambia 'justify' por 'left' o 'right' según tu preferencia */
  }
  .margen-td {
  text-align: left!important ; /* Cambia 'justify' por 'left' o 'right' según lo que necesites */
  }
  .select2-selection__clear {
    color: grey!important;
  }
</style>

<script type="text/javascript">
  $(function(){

    var desde="<?php echo $desde; ?>";
    var hasta="<?php echo $hasta; ?>";
    var gps="<?php echo $gps; ?>";
    $("#desde_t").val(desde);
    $("#hasta_t").val(hasta);
    $("#gps").val(gps);

      $("#patente").select2({
            placeholder: 'Seleccione Patente | Todas',
            data: <?php echo $patentes; ?>,
            allowClear: true,
            width: 'resolve',
      });

      $("#supervisor").select2({
            placeholder: 'Seleccione Supervisor | Todos',
            data: <?php echo $supervisores; ?>,
            allowClear: true,
            width: 'resolve',
      });

      $("#chofer").select2({
            placeholder: 'Seleccione Conductor | Todos',
            data: <?php echo $choferes; ?>,
            allowClear: true,
            width: 'resolve',
      });

      $("#region").select2({
            placeholder: 'Seleccione Región | Todas',
            data: <?php echo $regiones; ?>,
            allowClear: true,
            width: 'resolve',
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

    /************ACTUALIZACION*************/

      function Actualizar(){
        $.getJSON("<?php echo base_url();?>getActualizacionGPSMuevo", 
        {
          desde : $("#desde_t").val(),
          hasta : $("#hasta_t").val(),
          gps : $("#gps").val(),
          region : $("#region").val(),
        } , function(data) {
        response = data;
        }).done(function() {
          if(response[0]["ultima_actualizacion"] != 0 || response[0]["ultima_actualizacion"] != null){
            $("#ultima_actualizacion").val(response[0]["ultima_actualizacion"]);
          }
          else{
            $("#ultima_actualizacion").val(0);
          }
          if(response[0]["total"] != 0 || response[0]["ultima_actualizacion"] != null){
            $("#total").val(response[0]["total"]);
          }
          else{
            $("#total").val(0);
          }
        });
      }

      /******* LISTA GPS ********/

        var lista_gps = $('#lista_gps').DataTable({
        "responsive":false,
        "aaSorting" : [[1,"desc"]],
        "scrollY": "300px",
        "scrollX": true,
        "sAjaxDataProp": "result",        
        "bDeferRender": true,
        "bInfo" : false,
        "select" : true,
        // "columnDefs": [{ orderable: false, targets: 0 }  ],
        "ajax": {
            "url":"<?php echo base_url();?>listaGPSMuevo",    
            "dataSrc": function (json) {
              return json;
            },     
            data: function(param){
              param.desde = $("#desde_t").val();
              param.hasta = $("#hasta_t").val();
              param.chofer = $("#chofer").val();
              param.supervisor = $("#supervisor").val();
              param.patente = $("#patente").val();
              param.region = $("#region").val();
              param.gps = $("#gps").val();
            },
          },    
        "columns": [
            { "data": "patente" ,"class":"margen-td centered"},
            { "data": "rut_chofer" ,"class":"margen-td centered"},
            { "data": "nombre_chofer" ,"class":"margen-td centered"},
            { "data": "nombre_supervisor" ,"class":"margen-td centered"},
            { "data": "zona" ,"class":"margen-td centered"},
            { "data": "region" ,"class":"margen-td centered"},
            { "data": "direccion" ,"class":"margen-td centered"},

            { "data": "metodo_pago" ,"class":"margen-td centered"},
            { "data": "num_folio" ,"class":"margen-td centered"},
            { "data": "factura" ,"class":"margen-td centered"},
            { "data": "monto" ,"class":"margen-td centered"},

            { "data": "meta_litros_mensual" ,"class":"margen-td centered"},
            { "data": "odometro" ,"class":"margen-td centered"},
            { "data": "fecha" ,"class":"margen-td centered"},
          ],
        });
    
        setTimeout( function () {
          var lista_gps = $.fn.dataTable.fnTables(true);
          if (lista_gps.length > 0 ) {
              $(lista_gps).dataTable().fnAdjustColumnSizing();
        }}, 200 ); 

        setTimeout( function () {
          var lista_gps = $.fn.dataTable.fnTables(true);
          if (lista_gps.length > 0 ) {
              $(lista_gps).dataTable().fnAdjustColumnSizing();
        }}, 2000 ); 

        setTimeout( function () {
          var lista_gps = $.fn.dataTable.fnTables(true);
          if (lista_gps.length > 0 ) {
              $( lista_gps).dataTable().fnAdjustColumnSizing();
          }
        }, 4000 ); 

      /***** MONTO ****/

        google.charts.setOnLoadCallback(listaMontoMuevo);
        function listaMontoMuevo() {
          $.ajax({
              url: "<?php echo base_url();?>listaMontoMuevo",  
              type: 'POST',
              data: {
                desde:$("#desde_t").val(),
                hasta:$("#hasta_t").val(),
                chofer:$("#chofer").val(),
                supervisor:$("#supervisor").val(),
                patente:$("#patente").val(),
                region:$("#region").val(),
                gps:$("#gps").val(),
              },
              dataType:"json",
              success: function (datos) {
                var data = google.visualization.arrayToDataTable(datos.data);
                var options = {
                  is3D:true,
                  width: "100%",
                  height: 300,
                };
                var chart = new google.visualization.ColumnChart(document.getElementById('listaMontoMuevo'));
                chart.draw(data, options);
              }
          });
        }

      /***** ODOMETRO ****/

        google.charts.setOnLoadCallback(listaOdometroMuevo);
        function listaOdometroMuevo() {
          $.ajax({
              url: "<?php echo base_url();?>listaOdometroMuevo",  
              type: 'POST',
              data: {
                desde:$("#desde_t").val(),
                hasta:$("#hasta_t").val(),
                chofer:$("#chofer").val(),
                supervisor:$("#supervisor").val(),
                patente:$("#patente").val(),
                region:$("#region").val(),
                gps:$("#gps").val(),
              },
              dataType:"json",
              success: function (datos) {
                var data = google.visualization.arrayToDataTable(datos.data);
                var options = {
                  is3D:true,
                  width: "100%",
                  height: 300,
                };
                var chart = new google.visualization.ColumnChart(document.getElementById('listaOdometroMuevo'));
                chart.draw(data, options);
              }
          });
        }

      /******** ********/

      
        google.charts.setOnLoadCallback(GastoRegion);

        function GastoRegion() {
          $.ajax({
              url: "<?php echo base_url();?>GastoRegionMuevo",  
              type: 'POST',
              data: {
                desde:$("#desde_t").val(),
                hasta:$("#hasta_t").val(),
                chofer:$("#chofer").val(),
                supervisor:$("#supervisor").val(),
                patente:$("#patente").val(),
                region:$("#region").val(),
              },
              dataType:"json",
              success: function (datos) {
                var data = google.visualization.arrayToDataTable(datos.data);
                var options = {
                  is3D:true,
                  width: "100%",
                  height: 300,
                };

                var chart = new google.visualization.PieChart(document.getElementById('GastoRegion'));
                chart.draw(data, options);
              }
          });
        }
        google.charts.setOnLoadCallback(GastoSemana);

        function GastoSemana() {
          $.ajax({
              url: "<?php echo base_url();?>GastoSemanaMuevo",  
              type: 'POST',
              data: {
                desde:$("#desde_t").val(),
                hasta:$("#hasta_t").val(),
                chofer:$("#chofer").val(),
                supervisor:$("#supervisor").val(),
                patente:$("#patente").val(),
                region:$("#region").val(),
              },
              dataType:"json",
              success: function (datos) {
                var data = google.visualization.arrayToDataTable(datos.data);
                var options = {
                  is3D:true,
                  width: "100%",
                  height: 300,
                };

                var chart = new google.visualization.LineChart(document.getElementById('GastoSemana'));
                chart.draw(data, options);
              }
          });
        }


      
      
      $(document).off('change', '#desde_t,#hasta_t,#chofer,#supervisor,#patente,#region').on('change', '#desde_t,#hasta_t,#chofer,#supervisor,#patente,#region',function(event) {
        GastoRegion();
        GastoSemana();
        listaMontoMuevo();
        listaOdometroMuevo();
        Actualizar();
        lista_gps.ajax.reload();
      }); 

      Actualizar();
  })  
</script>

  <div class="form-row">

    <div class="col-3 col-sm-3 col-md-3 col-lg-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page" ><a href="">Informaciones Muevo</a></li>
        </ol>
      </nav>
    </div>

    <input hidden type="text" disabled value="" class="form-control form-control-sm col-12 col-lg-4"  name="gps" id="gps">

    <div class="col-12 col-lg-3">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fa fa-calendar-alt"></i> <span style="margin-left:5px;font-size:13px;">Fecha actualizado <span></span> 
          </div>
          <input type="text" disabled value="" class=" form-control form-control-sm col-12 col-lg-4"  name="ultima_actualizacion" id="ultima_actualizacion">
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-3">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fa fa-dollar-sign"></i> <span style="margin-left:5px;font-size:13px;">Monto total<span></span> 
          </div>
          <input type="text" disabled value="" class=" form-control form-control-sm"  name="total" id="total">
        </div>
      </div>
    </div>


    <div class="col-12 col-lg-3">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="margin-left:5px;font-size:13px;">Fecha <span></span> 
          </div>
          <input type="date" placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="desde_t" id="desde_t">
          <input type="date" placeholder="Hasta" class="fecha_normal form-control form-control-sm"  name="hasta_t" id="hasta_t">
        </div>
      </div>

    </div>

    <div class="col-6 col-lg-2">
      <div class="form-group">
        <select id="supervisor" name="supervisor" class="custom-select custom-select-sm">
          <option value="">Seleccione Supervisor | Todos</option>
        </select>
      </div>
    </div>

    <div class="col-6 col-lg-2">
      <div class="form-group">
        <select id="chofer" name="chofer" class="custom-select custom-select-sm">
        <option value="">Seleccione Conductor | Todos</option>
        </select>
      </div>
    </div>

    <div class="col-6 col-lg-2">
      <div class="form-group">
        <select id="patente" name="patente" class="custom-select custom-select-sm">
          <option value="">Seleccione Patente | Todas</option>
        </select>
      </div>
    </div>

    <div class="col-6 col-lg-2">
      <div class="form-group">
        <select id="region" name="region" class="custom-select custom-select-sm"style="width:100%!important;">
        <option></option>
        </select>
      </div>
    </div>

  </div>      

  <div class="body">
    <div class="form-row mt-2">
      <div class="col-12 col-lg-12 mt-2">
        <div class="card">
          <div class="col-12">
            <span class="title_section">Detalle</span>
            <table id="lista_gps" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                <tr>    
                  <th class="centered">Patente</th> 
                  <th class="centered">Rut chofer</th> 
                  <th class="centered">Nombre chofer</th> 
                  <th class="centered">Nombre supervisor</th> 
                  <th class="centered">Zona</th> 
                  <th class="centered">Región</th> 
                  <th class="centered">Dirección</th> 
                  <th class="centered">Metodo de pago</th> 
                  <th class="centered">Num folio</th> 
                  <th class="centered">Factura</th> 
                  <th class="centered">Monto $CLP</th> 
                  <th class="centered">Meta litros semanal</th> 
                  <th class="centered">Odometro</th> 
                  <th class="centered">Fecha</th> 

                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-6 mt-2">
        <div class="card">
          <div class="col-12">
              <span class="title_section">Monto $CLP por patente</span>
              <div id="listaMontoMuevo"></div>
            </div>
          </div>
      </div>

      <div class="col-12 col-lg-6 mt-2">
        <div class="card">
          <div class="col-12">
              <span class="title_section">Max Odómetro por patente</span>
              <div id="listaOdometroMuevo"></div>
            </div>
          </div>
      </div>

      <div class="col-12 col-lg-6 mt-2">
        <div class="card">
          <div class="col-12">
            <span class="title_section">Gastos $CLP por Semana</span>
            <div id="GastoSemana"></div>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-6 mt-2">
        <div class="card">
          <div class="col-12">
            <span class="title_section">Gastos $CLP por Región</span>
            <div id="GastoRegion"></div>
          </div>
        </div>
      </div>
    </div>
  </div>