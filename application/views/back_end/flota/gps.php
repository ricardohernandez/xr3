<style type="text/css">
  .centered {
    text-align: left!important ; 
  }
  .margen-td {
  text-align: left!important ; 
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


      $("#chofer").select2({
            placeholder: 'Seleccione Conductor | Todos',
            data: <?php echo $choferes; ?>,
            allowClear: true,
            width: 'resolve',
      });


      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      /************ACTUALIZACION*************/

      function Actualizar(){
        $.getJSON("<?php echo base_url();?>getActualizacionGPS", { 
          desde : $("#desde_t").val(),
          hasta : $("#hasta_t").val(),
          gps : $("#gps").val() ,
        } , function(data) {
        response = data;
        }).done(function() {
          if(response[0]["conteo_infracciones"] != 0 || response[0]["conteo_infracciones"] != null){
            $("#conteo_infracciones").val(response[0]["conteo_infracciones"]);
          }
          else{
            $("#conteo_infracciones").val(0);
          }
          if(response[0]["vehiculos_infractores"] != 0 || response[0]["vehiculos_infractores"] != null){
            $("#conteo_vehiculos").val(response[0]["vehiculos_infractores"]);
          }
          else{
            $("#conteo_vehiculos").val(0);
          }
          if(response[0]["max_velocidad"] != 0 || response[0]["max_velocidad"] != null) {
            $("#max_infractor").val(response[0]["max_velocidad"]);
          }
          else{
            $("#max_infractor").val(0);
          }
        });
      }

      /******* LISTA INFRACTOR ********/

        var lista_infractor = $('#lista_infractor').DataTable({
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
            "url":"<?php echo base_url();?>listaGPS",    
            "dataSrc": function (json) {
              return json;
            },     
            data: function(param){
              param.desde = $("#desde_t").val();
              param.hasta = $("#hasta_t").val();
              param.chofer = $("#chofer").val();
              param.patente = $("#patente").val();
              param.gps = $("#gps").val();
            },
          },    
        "columns": [
            { "data": "patente" ,"class":"margen-td centered"},
            { "data": "nombre_chofer" ,"class":"margen-td centered"},
            { "data": "infracciones" ,"class":"margen-td centered"},
          ],
        });
    
        setTimeout( function () {
          var lista_infractor = $.fn.dataTable.fnTables(true);
          if (lista_infractor.length > 0 ) {
              $(lista_infractor).dataTable().fnAdjustColumnSizing();
        }}, 200 ); 

        setTimeout( function () {
          var lista_infractor = $.fn.dataTable.fnTables(true);
          if (lista_infractor.length > 0 ) {
              $(lista_infractor).dataTable().fnAdjustColumnSizing();
        }}, 2000 ); 

        setTimeout( function () {
          var lista_infractor = $.fn.dataTable.fnTables(true);
          if (lista_infractor.length > 0 ) {
              $( lista_infractor).dataTable().fnAdjustColumnSizing();
          }
        }, 4000 ); 

      
      /******* LISTA DETALLE ********/

        var lista_detalle = $('#lista_detalle').DataTable({
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
            "url":"<?php echo base_url();?>listaDetalleFlota",    
            "dataSrc": function (json) {
              return json;
            },     
            data: function(param){
              param.desde = $("#desde_t").val();
              param.hasta = $("#hasta_t").val();
              param.chofer = $("#chofer").val();
              param.patente = $("#patente").val();
              param.gps = $("#gps").val();
            },
          },    
        "columns": [
            { "data": "fecha" ,"class":"margen-td centered"},
            { "data": "patente" ,"class":"margen-td centered"},
            { "data": "v_max" ,"class":"margen-td centered"},
            { "data": "lim_v" ,"class":"margen-td centered"},
            { "data": "hora_inicio" ,"class":"margen-td centered"},
            { "data": "hora_fin" ,"class":"margen-td centered"},
            { "data": "rut" ,"class":"margen-td centered"},
            { "data": "nombre_chofer" ,"class":"margen-td centered"},
            { "data": "direccion_inicio" ,"class":"margen-td centered"},
            { "data": "direccion_fin" ,"class":"margen-td centered"},
            { "data": "duracion" ,"class":"margen-td centered"},
          ],
        });
    
        setTimeout( function () {
          var lista_detalle = $.fn.dataTable.fnTables(true);
          if (lista_detalle.length > 0 ) {
              $(lista_detalle).dataTable().fnAdjustColumnSizing();
        }}, 200 ); 

        setTimeout( function () {
          var lista_detalle = $.fn.dataTable.fnTables(true);
          if (lista_detalle.length > 0 ) {
              $(lista_detalle).dataTable().fnAdjustColumnSizing();
        }}, 2000 ); 

        setTimeout( function () {
          var lista_detalle = $.fn.dataTable.fnTables(true);
          if (lista_detalle.length > 0 ) {
              $( lista_detalle).dataTable().fnAdjustColumnSizing();
          }
        }, 4000 ); 

      
      
      /********** TOTAL *************/

        google.charts.setOnLoadCallback(listaTotal);
        function listaTotal() {
          $.ajax({
              url: "<?php echo base_url();?>listaTotal",  
              type: 'POST',
              data: {
                desde:$("#desde_t").val(),
                hasta:$("#hasta_t").val(),
                chofer:$("#chofer").val(),
                patente:$("#patente").val(),
                gps:$("#gps").val(),
              },
              dataType:"json",
              success: function (datos) {
                var data = google.visualization.arrayToDataTable(datos.data);
                var options = {
                  is3D:true,
                  width: "100%",
                  height: 400,
                  hAxis: { 
                  slantedText: true, 
                  slantedTextAngle: 45 // Ángulo de rotación
                }
                };
                var chart = new google.visualization.ColumnChart(document.getElementById('listaTotal'));
                chart.draw(data, options);
              }
          });
        }

      $(document).off('change', '#desde_t,#hasta_t,#chofer,#patente').on('change', '#desde_t,#hasta_t,#chofer,#patente',function(event) {
        listaTotal();
        Actualizar();
        lista_infractor.ajax.reload();
        lista_detalle.ajax.reload();
      }); 

      Actualizar();
  })  
</script>

  <div class="form-row">

    <div class="col-3 col-sm-3 col-md-3 col-lg-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page" ><a href="">Velocidades mayores a 120 kms x Hora</a></li>
        </ol>
      </nav>
    </div>

    <input hidden type="text" disabled value="" class="form-control form-control-sm col-12 col-lg-4"  name="gps" id="gps">

      <div class="col-12 col-lg-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-car"></i> <span style="margin-left:5px;font-size:13px;">Vehículos infractores<span></span> 
            </div>
            <input type="text" disabled value="" class=" form-control form-control-sm col-12 col-lg-4"  name="conteo_vehiculos" id="conteo_vehiculos">
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-circle-exclamation"></i> <span style="margin-left:5px;font-size:13px;">Conteo infracciones<span></span> 
            </div>
            <input type="text" disabled value="" class=" form-control form-control-sm col-12 col-lg-4"  name="conteo_infracciones" id="conteo_infracciones">
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-gauge"></i> <span style="margin-left:5px;font-size:13px;">Máximo infractor kms/hora<span></span> 
            </div>
            <input type="text" disabled value="" class=" form-control form-control-sm col-12 col-lg-4"  name="max_infractor" id="max_infractor">
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


  </div>      

  <div class="body">
    <div class="form-row mt-2">
      <div class="col-12 col-lg-3 mt-2">
        <div class="card">
          <div class="col-12">
            <span class="title_section">Infractor</span>
            <table id="lista_infractor" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                <tr>    
                  <th class="centered">Patente</th> 
                  <th class="centered">Nombre conductor</th> 
                  <th class="centered">Num infracciones</th> 
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-9 mt-2">
        <div class="card">
          <div class="col-12">
              <span class="title_section">Detalle de infracciones</span>
              <table id="lista_detalle" class="table table-striped table-hover table-bordered dt-responsive" style="width:100%">
                <thead>
                  <tr>    
                    <th class="centered">Fecha</th> 
                    <th class="centered">Patente</th> 
                    <th class="centered">Velocidad máxima</th> 
                    <th class="centered">Velocidad límite</th> 
                    <th class="centered">Hora inicio</th> 
                    <th class="centered">Hora fin</th> 
                    <th class="centered">Rut</th> 
                    <th class="centered">Conductor</th> 
                    <th class="centered">Direccion inicio</th> 
                    <th class="centered">Direccion fin</th> 
                    <th class="centered">Duración</th> 
                  </tr>
                </thead>
              </table>
            </div>
          </div>
      </div>
      <div class="col-12 col-lg-12 mt-2">
        <div class="card">
          <div class="col-12">
              <span class="title_section">Infracciones por patente</span>
              <div id="listaTotal"></div>
            </div>
          </div>
      </div>

    </div>
  </div>