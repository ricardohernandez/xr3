<style type="text/css">

  .file_cs{
    display: none;
  }
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
    //$("#desde_t").val(desde);
    //$("#hasta_t").val(hasta);


      function Actualizar(){
        $.getJSON("<?php echo base_url();?>getActualizacionCombustible", {} , function(data) {
        response = data;
        }).done(function() {
          if(response[0]["ultima_actualizacion"] != 0){
            $("#ultima_actualizacion").val(response[0]["ultima_actualizacion"]);
          }
          if(response[0]["total"] != 0){
            $("#total").val(response[0]["total"]);
          }
        });
      }


      $(document).off('change', '.file_cs').on('change', '.file_cs',function(event) {
        var myFormData = new FormData();
        myFormData.append('userfile', $('#userfile').prop('files')[0]);
        $.ajax({
            url: "formCargaMasivaFlota"+"?"+$.now(),  
            type: 'POST',
            data: myFormData,
            cache: false,
            tryCount : 0,
            retryLimit : 3,
            processData: false,
            contentType : false,
            dataType:"json",
            beforeSend:function(){
              $(".btn_file_cs").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...').prop("disabled",true);
            },  
            success: function (data) {
               $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base ').prop("disabled",false);
                if(data.res=="ok"){
                  $.notify(data.msg, {
                      className:data.tipo,
                      globalPosition: 'top center',
                      autoHideDelay: 20000,
                  });
                  
                }else{
                  $.notify(data.msg, {
                      className:data.tipo,
                      globalPosition: 'top center',
                      autoHideDelay: 10000,
                  });
                }

                $("#userfile").val(null);

            },
            error : function(xhr, textStatus, errorThrown ) {
              $("#userfile").val(null);
              if (textStatus == 'timeout') {
                  this.tryCount++;
                  if (this.tryCount <= this.retryLimit) {
                      $.notify("Reintentando...", {
                        className:'info',
                        globalPosition: 'top center'
                      });
                      $.ajax(this);
                      $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base productividad').prop("disabled",false);
                      return;
                  } else{
                     $.notify("Problemas cargando el archivo, intente nuevamente.", {
                        className:'warn',
                        globalPosition: 'top center',
                        autoHideDelay: 10000,
                      });
                  }    
                  return;
              }

              if (xhr.status == 500) {
                 $.notify("Problemas cargando el archivo, intente nuevamente.", {
                    className:'warn',
                    globalPosition: 'top center',
                    autoHideDelay: 10000,
                 });
              $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base').prop("disabled",false);
              }
          },timeout:120000
        });
      })
      
      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      /******* LISTA FLOTA ********/

        var lista_flota = $('#lista_flota').DataTable({
        "responsive":false,
        "aaSorting" : [[1,"desc"]],
        "scrollY": "300px",
        "scrollX": true,
        "sAjaxDataProp": "result",        
        "bDeferRender": true,
        "bInfo" : false,
        "select" : true,
        "autoWidth": true,
        // "columnDefs": [{ orderable: false, targets: 0 }  ],
        "ajax": {
            "url":"<?php echo base_url();?>listaCombustible",    
            "dataSrc": function (json) {
              return json;
            },     
            data: function(param){
              param.desde = $("#desde_t").val();
              param.hasta = $("#hasta_t").val();
              param.chofer = $("#chofer").val();
              param.supervisor = $("#supervisor").val();
              param.patente = $("#patente").val();
              param.comuna = $("#comuna").val();
            },
          },    
        "columns": [
            { "data": "id" ,"class":"margen-td centered"},
            { "data": "patente" ,"class":"margen-td centered"},
            { "data": "rut_chofer" ,"class":"margen-td centered"},
            { "data": "nombre_chofer" ,"class":"margen-td centered"},
            { "data": "nombre_supervisor" ,"class":"margen-td centered"},
            { "data": "region" ,"class":"margen-td centered"},
            { "data": "meta_litros_mensual" ,"class":"margen-td centered"},
            { "data": "litros_cargados" ,"class":"margen-td centered"},
            { "data": "meta_kms_mensual" ,"class":"margen-td centered"},
            { "data": "kms_recorridos_total" ,"class":"margen-td centered"},
            { "data": "meta_monto" ,"class":"margen-td centered"},
            { "data": "monto_total" ,"class":"margen-td centered"},
          ],
        });
    
        setTimeout( function () {
          var lista_flota = $.fn.dataTable.fnTables(true);
          if (lista_flota.length > 0 ) {
              $(lista_flota).dataTable().fnAdjustColumnSizing();
        }}, 200 ); 

        setTimeout( function () {
          var lista_flota = $.fn.dataTable.fnTables(true);
          if (lista_flota.length > 0 ) {
              $(lista_flota).dataTable().fnAdjustColumnSizing();
        }}, 2000 ); 

        setTimeout( function () {
          var lista_flota = $.fn.dataTable.fnTables(true);
          if (lista_flota.length > 0 ) {
              $( lista_flota).dataTable().fnAdjustColumnSizing();
          }
        }, 4000 ); 

      
      /******* LISTA MAX ********/

        var lista_max = $('#lista_max').DataTable({
        "responsive":false,
        "aaSorting" : [[1,"desc"]],
        "scrollY": "240px",
        "scrollX": true,
        "sAjaxDataProp": "result",        
        "bDeferRender": true,
        "bInfo" : false,
        "select" : true,
        // "columnDefs": [{ orderable: false, targets: 0 }  ],
        "ajax": {
            "url":"<?php echo base_url();?>listaMax",    
            "dataSrc": function (json) {
              return json;
            },     
            data: function(param){
              param.desde = $("#desde_t").val();
              param.hasta = $("#hasta_t").val();
              param.chofer = $("#chofer").val();
              param.supervisor = $("#supervisor").val();
              param.patente = $("#patente").val();
              param.comuna = $("#comuna").val();
            },
          },    
        "columns": [
            { "data": "patente" ,"class":"margen-td centered"},
            { "data": "fecha" ,"class":"margen-td centered"},
            { "data": "hora" ,"class":"margen-td centered"},
            { "data": "max" ,"class":"margen-td centered"},
          ],
        });
    
        setTimeout( function () {
          var lista_max = $.fn.dataTable.fnTables(true);
          if (lista_max.length > 0 ) {
              $(lista_max).dataTable().fnAdjustColumnSizing();
        }}, 200 ); 

        setTimeout( function () {
          var lista_max = $.fn.dataTable.fnTables(true);
          if (lista_max.length > 0 ) {
              $(lista_max).dataTable().fnAdjustColumnSizing();
        }}, 2000 ); 

        setTimeout( function () {
          var lista_max = $.fn.dataTable.fnTables(true);
          if (lista_max.length > 0 ) {
              $( lista_max).dataTable().fnAdjustColumnSizing();
          }
        }, 4000 ); 

      /********** CARGAS *************/

        google.charts.setOnLoadCallback(ListaCarga);

        function ListaCarga() {
          $.ajax({
              url: "<?php echo base_url();?>listaCarga",  
              type: 'POST',
              data: {
                desde:$("#desde_t").val(),
                hasta:$("#hasta_t").val(),
                chofer:$("#chofer").val(),
                supervisor:$("#supervisor").val(),
                patente:$("#patente").val(),
                comuna:$("#comuna").val(),
              },
              dataType:"json",
              success: function (datos) {
                var data = google.visualization.arrayToDataTable(datos.data);
                var options = {
                  is3D:true,
                  width: "100%",
                  height: 300,
                };
                options.vAxes ={
                  0: 
                    {
                    textStyle:{color: '#808080',bold:false,fontSize: 12},
                      gridlines: {color:'#808080', count:0},
                      viewWindowMode:'explicit',
                      viewWindow: {
                        min: 0,
                      },
                    },
                  1: 
                    {
                      textStyle:{color: '#808080',bold:false,fontSize: 12},
                        gridlines: {color:'#808080', count:0},
                        viewWindow: {
                          min: 0,
                        },
                      }
                };
                options.seriesType = 'bars'; 
                options.series = {
                  1: {
                    type: 'line',
                    lineDashStyle: [4, 4], 
                    color: 'grey',
                    curveType: 'function',
                    lineWidth: 2,
                    pointSize: 5,
                    pointShape: 'square',
                    targetAxisIndex: 1,
                    annotations: {
                      stem: {
                        length: 4
                      },
                      
                    }
                  },
                  0: {
                    type: 'bars',
                    color: '#2F81F7',
                    targetAxisIndex: 0,
                    annotations: {
                      style: 'line',
                      textStyle: {
                        fontSize: 12,
                        color: 'black',
                        strokeSize: 1,
                        auraColor: 'transparent'
                      },
                      alwaysOutside: false,
                      stem: {
                        color: 'transparent',
                        length: 8
                      }
                    }
                  }
                };

                var chart = new google.visualization.ComboChart(document.getElementById('listaCarga'));
                chart.draw(data, options);
              }
          });
        }

      $(document).off('change', '#desde_t,#hasta_t,#chofer,#supervisor,#patente,#comuna').on('change', '#desde_t,#hasta_t,#chofer,#supervisor,#patente,#comuna',function(event) {
        ListaCarga();
        Actualizar();
        lista_flota.ajax.reload();
        lista_max.ajax.reload();
      }); 

      Actualizar();
  })  
</script>

<div class="content" style="padding: 0px 10px;">

  <div class="form-row">

    <div class="col-6 col-sm-6 col-md-6 col-lg-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page" ><a href="">Flota - Consumo de combustible</a></li>
        </ol>
      </nav>
    </div>

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

    <?php
        if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
        ?>
        <div class=" col-xs-3 col-sm-3  col-md-3  col-lg-2 d-none d-sm-block">  
          <div class="form-group">
            <input type="file" id="userfile" name="userfile" class="file_cs" />
            <button type="button"  class="btn-block btn btn-sm btn-primary btn_file_cs btn_xr3" onclick="document.getElementById('userfile').click();">
            <i class="fa fa-file-import"></i> Cargar base Flota
            </div>
        </div>
        <!-- <i class="fa-solid fa-circle-info ejemplo_planilla" title="Ver ejemplo" ></i> -->
        <?php
      }
    ?>

  </div>

  <div class="form-row">
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
        <select id="comuna" name="comuna" class="custom-select custom-select-sm">
          <option selected value="" >Seleccione region | Todos</option>
          <?php  
            foreach($comunas as $c){
              ?>
                <option value="<?php echo $c["region"]?>" ><?php echo $c["region"]?> </option>
              <?php
            }
          ?>
        </select>
      </div>
    </div>
  </div>       

  <div class="body">
    <div class="form-row mt-2">
      <div class="col-12">
        <div class="card">
          <div class="col-12">
            <span class="title_section">TOP 10 de consumo máximo</span>
            <table id="lista_flota" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                <tr>    
                  <th class="centered">id</th> 
                  <th class="centered">Patente</th> 
                  <th class="centered">Rut conductor</th> 
                  <th class="centered">Nombre conductor</th> 
                  <th class="centered">Nombre supervisor</th> 
                  <th class="centered">Región</th> 
                  <th class="centered">Meta litros mensual</th> 
                  <th class="centered">Litros cargados</th> 
                  <th class="centered">Meta kms mes</th> 
                  <th class="centered">Kms recorridos</th> 
                  <th class="centered">$ Meta</th> 
                  <th class="centered">$ Cargado</th> 
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6 mt-2">
        <div class="card">
          <div class="col-12">
              <span class="title_section">Cargas por mes</span>
              <div id="listaCarga"></div>
            </div>
          </div>
      </div>
      <div class="col-12 col-lg-6 mt-2">
        <div class="card">
          <div class="col-12">
              <span class="title_section">Detalle Max</span>
              <table id="lista_max" class="table table-striped table-hover table-bordered dt-responsive" style="width:100%">
                <thead>
                  <tr>    
                    <th class="centered">Patente</th> 
                    <th class="centered">Fecha de carga</th> 
                    <th class="centered">Hora de carga</th> 
                    <th class="centered">Odómetro</th> 
                  </tr>
                </thead>
              </table>
            </div>
          </div>
      </div>
    </div>
  </div>



  