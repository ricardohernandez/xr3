<style type="text/css">
  .centered {
    text-align: left!important ; /* Cambia 'justify' por 'left' o 'right' según tu preferencia */
  }
  .margen-td {
  text-align: left!important ; /* Cambia 'justify' por 'left' o 'right' según lo que necesites */
  }
</style>

<script type="text/javascript">
  $(function(){

    var desde="<?php echo $desde; ?>";
    var hasta="<?php echo $hasta; ?>";
    var gps="<?php echo $gps; ?>";
    //$("#desde_t").val(desde);
    //$("#hasta_t").val(hasta);
    $("#gps").val(gps);

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      /************ACTUALIZACION*************/

      function Actualizar(){
        $.getJSON("<?php echo base_url();?>getActualizacionGPS", { gps : $("#gps").val() } , function(data) {
        response = data;
        }).done(function() {
          if(response[0]["conteo_infracciones"] != 0){
            $("#conteo_infracciones").val(response[0]["conteo_infracciones"]);
          }
          if(response[0]["vehiculos_infractores"] != 0){
            $("#conteo_vehiculos").val(response[0]["vehiculos_infractores"]);
          }
          if(response[0]["max_velocidad"] != 0) {
            $("#max_infractor").val(response[0]["max_velocidad"]);
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
              param.supervisor = $("#supervisor").val();
              param.patente = $("#patente").val();
              param.gps = $("#gps").val();
            },
          },    
        "columns": [
            { "data": "patente" ,"class":"margen-td centered"},
            { "data": "nombre_chofer" ,"class":"margen-td centered"},
            { "data": "nombre_supervisor" ,"class":"margen-td centered"},
            { "data": "comuna" ,"class":"margen-td centered"},
            { "data": "zona" ,"class":"margen-td centered"},
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
              param.supervisor = $("#supervisor").val();
              param.patente = $("#patente").val();
              param.gps = $("#gps").val();
            },
          },    
        "columns": [
            { "data": "patente" ,"class":"margen-td centered"},
            { "data": "velocidad" ,"class":"margen-td centered"},
            { "data": "fecha" ,"class":"margen-td centered"},
            { "data": "direccion" ,"class":"margen-td centered"},
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
                supervisor:$("#supervisor").val(),
                patente:$("#patente").val(),
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
                var chart = new google.visualization.ColumnChart(document.getElementById('listaTotal'));
                chart.draw(data, options);
              }
          });
        }

      $(document).off('change', '#desde_t,#hasta_t,#chofer,#supervisor,#patente,#comuna').on('change', '#desde_t,#hasta_t,#chofer,#supervisor,#patente,#comuna',function(event) {
        listaTotal();
        Actualizar();
        lista_infractor.ajax.reload();
        lista_detalle.ajax.reload();
      }); 

      Actualizar();
  })  
</script>

  <div class="form-row">

    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
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
        <select id="supervisor" name="supervisor" class="custom-select custom-select-sm">
          <option selected value="" >Seleccione supervisor | Todos</option>
          <?php  
            foreach($supervisores as $s){
              ?>
                <option value="<?php echo $s["nombre_supervisor"]?>" ><?php echo $s["nombre_supervisor"]?> </option>
              <?php
            }
          ?>
        </select>
      </div>
    </div>

    <div class="col-6 col-lg-2">
      <div class="form-group">
        <select id="chofer" name="chofer" class="custom-select custom-select-sm">
          <option selected value="" >Seleccione conductor | Todos</option>
          <?php  
            foreach($choferes as $c){
              ?>
                <option value="<?php echo $c["nombre_chofer"]?>" ><?php echo $c["nombre_chofer"]?> </option>
              <?php
            }
          ?>
        </select>
      </div>
    </div>

    <div class="col-6 col-lg-2">
      <div class="form-group">
        <select id="patente" name="patente" class="custom-select custom-select-sm">
        <option selected value="" >Seleccione vehículo | Todos</option>
          <?php  
            foreach($patentes as $p){
              ?>
                <option value="<?php echo $p["patente"]?>" ><?php echo $p["patente"]?> </option>
              <?php
            }
          ?>
        </select>
      </div>
    </div>

  </div>      

  <div class="body">
    <div class="form-row mt-2">
      <div class="col-12 col-lg-6 mt-2">
        <div class="card">
          <div class="col-12">
            <span class="title_section">Infractor</span>
            <table id="lista_infractor" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                <tr>    
                  <th class="centered">Patente</th> 
                  <th class="centered">Nombre conductor</th> 
                  <th class="centered">Nombre supervisor</th> 
                  <th class="centered">Comuna</th> 
                  <th class="centered">Zona</th> 
                  <th class="centered">Num infracciones</th> 
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6 mt-2">
        <div class="card">
          <div class="col-12">
              <span class="title_section">Detalle de infracciones</span>
              <table id="lista_detalle" class="table table-striped table-hover table-bordered dt-responsive" style="width:100%">
                <thead>
                  <tr>    
                    <th class="centered">Patente</th> 
                    <th class="centered">Velocidad</th> 
                    <th class="centered">Fecha</th> 
                    <th class="centered">Direccion</th> 
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






   