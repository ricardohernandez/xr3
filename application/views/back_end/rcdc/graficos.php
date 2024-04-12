<style type="text/css">
    .select2-selection__clear {
      color: grey!important;
    }
    #graficoMotivos{
    overflow-x: scroll;
    overflow-y: hidden;
    width: 100%;
    }
    #graficoMotivosxTecnico{
      overflow-x: scroll;
      overflow-y: hidden;
      width: 100%;
    }
    #graficoMotivosxComuna{
      overflow-x: scroll;
      overflow-y: hidden;
      width: 100%;
    }
    #graficoMotivosxCoordinador{
      overflow-x: scroll;
      overflow-y: hidden;
      width: 100%;
    }
    #graficoMotivosxTecnicoDetalle{
      overflow-x: scroll;
      overflow-y: hidden;
      width: 100%;
    }

</style>

<script type="text/javascript">
  const base_url = "<?php echo base_url() ?>"

  $("#anio").select2({
    placeholder: 'Seleccione Año | Todas',
    data: <?php echo $anio; ?>,
    allowClear: true,
  });

  $("#mes").select2({
    placeholder: 'Seleccione Mes | Todas',
    data: <?php echo $mes; ?>,
    allowClear: true,
  });

  $(function(){
    google.charts.load('current', {'packages':['corechart']});

    var perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    var user="<?php echo $this->session->userdata('id'); ?>";
    const base = "<?php echo base_url() ?>";

    $(document).off('change', '#anio , #mes ,#f_coordinador , #f_comuna , #f_zona').on('change', '#anio , #mes ,#f_coordinador , #f_comuna , #f_zona', function(event) {
      cargarGraficos();
    }); 

    cargarGraficos()

    function cargarGraficos () {
      const anio = $("#anio").val()
      const mes = $("#mes").val()
      const comuna = $("#f_comuna").val()
      const zona = $("#f_zona").val()
      const encargado = $("#f_coordinador").val()
      
      $.ajax({
          url: base_url+'getDataGraficosRcdc',
          type: 'POST',
          data: {
            'anio': anio,
            'mes': mes,
            'comuna': comuna,
            'zona': zona,
            'encargado': encargado,
          },
          dataType: "json",
          beforeSend:function(){
            $("#load").show()
            $(".body").hide()  
          }, 
          success: function (response) {
            $("#load").hide()
            $(".body").fadeIn(500)

            if(response.res=="error"){
                $.notify(response.msg, {
                    className:response.res,
                    globalPosition: 'top center',
                    autoHideDelay: 20000,
                });
                $("#graficoXComuna").html('<p class="no_data_found">Sin datos encontrados</p>')

            }else{
                crearGraficosPie('graficoMotivosxZona', response.graficoMotivosxZona);
                crearGraficosColumn('graficoMotivos', response.graficoMotivos);
                crearGraficosColumn('graficoMotivosxTecnico', response.graficoMotivosxTecnico);
                crearGraficosColumn('graficoMotivosxComuna', response.graficoMotivosxComuna);
                crearGraficosBar('graficoMotivosxTecnicoDetalle', response.graficoMotivosxTecnicoDetalle);
                crearGraficosColumn('graficoMotivosxCoordinador', response.graficoMotivosxCoordinador);
            }
          
          },
          error: function (error) {
              console.log(error);
          }
      });
    }
    function crearGraficosPie(divId, data) {
      let size = Object.keys(data).length
      switch (true) {
        case size < 10:
          cant = 0;
          break;
        case size >= 11 && size <= 40:
          cant = 500;
          break;
        case size >= 41 && size <= 80:
          cant = 1000;
          break;
        case size >= 81 && size <= 120:
          cant = 2000;
          break;
        default:
          cant = 2500;
      }
      var data = google.visualization.arrayToDataTable(data)

      const options = {
        fontName: 'ubuntu',
        fontColor: '#32477C',
        backgroundColor: { fill: 'transparent' },
      };
      var chart = new google.visualization.PieChart(document.getElementById(divId));
      chart.draw(data, options);
    }
    function crearGraficosColumn(divId, data) {

      let size = Object.keys(data).length
      switch (true) {
        case size < 10:
          cant = 0;
          break;
        case size >= 11 && size <= 40:
          cant = 500;
          break;
        case size >= 41 && size <= 80:
          cant = 1000;
          break;
        case size >= 81 && size <= 120:
          cant = 2000;
          break;
        default:
          cant = 2500;
      }
      var data = google.visualization.arrayToDataTable(data)
      const cont = document.getElementById("contenedor_"+divId)
      const ancho = cont.offsetWidth+cant

      const options = {
        fontName: 'ubuntu',
        fontColor: '#32477C',
        backgroundColor: { fill: 'transparent' },
        curveType: 'function',
        legend: { position: 'top', maxLines: 999 }, 
        chartArea: {
        left: 60,
        right: 40,
        bottom: 100,
        top: 40,
      },
      width: ancho,
      height: 480,
      hAxis: {
        title: '',
        minValue: 0,
       /*  direction: -1,  */
        slantedText: true, 
        slantedTextAngle: 90 ,
        textStyle: {
          fontSize: 13,
          bold: false,
          color: '#808080'
        },
        gridlines: {
          color: '',
          count: 0
        }
      },
      vAxis: {
        title: '',
        titleTextStyle: {color: '#808080',italic:false} ,

        textStyle: {
          fontSize: 13,
          bold: false,
          color: '#808080'
          
        },
        gridlines: {
          color: '',
          count: 0
        },
      },
        annotations: {
            textStyle: {
                fontSize: 11,
                bold: true,
                color: '#000000',
                auraColor: 'none'
            }
        }
      };
      var chart = new google.visualization.ColumnChart(document.getElementById(divId));
      chart.draw(data, options);
    }
    function crearGraficosBar(divId, data) {
      let size = Object.keys(data).length
      switch (true) {
        case size < 10:
          cant = 0;
          break;
        case size >= 11 && size <= 40:
          cant = 500;
          break;
        case size >= 41 && size <= 80:
          cant = 1000;
          break;
        case size >= 81 && size <= 120:
          cant = 2000;
          break;
        default:
          cant = 2500;
      }
      var data = google.visualization.arrayToDataTable(data)
      const cont = document.getElementById("contenedor_"+divId)
      const ancho = cont.offsetWidth+cant

      const options = {
        fontName: 'ubuntu',
        fontColor: '#32477C',
        backgroundColor: { fill: 'transparent' },
        curveType: 'function',
        legend: { 
          position: 'top', 
          maxLines: 999,
          textStyle: {
          fontSize: 10,
          bold: false,
          color: '#808080'
        },
        }, 
        chartArea: {
        left: 120,
        right: 60,
        bottom: 10,
        top: 40,
      },
      width: cont.offsetWidth,
      height: cont.offsetHeight+cant,
      hAxis: {
        title: '',
        minValue: 0,
       /*  direction: -1,  */
        slantedText: true, 
        slantedTextAngle: 90 ,
        textStyle: {
          fontSize: 10,
          bold: false,
          color: '#808080'
        },
        gridlines: {
          color: '',
          count: 0
        }
      },
      vAxis: {
        title: '',
        titleTextStyle: {color: '#808080',italic:false} ,

        textStyle: {
          fontSize: 10,
          bold: false,
          color: '#808080'
          
        },
        gridlines: {
          color: '',
          count: 0
        },
      },
        isStacked: true,

      };

      var chart = new google.visualization.BarChart(document.getElementById(divId));
      chart.draw(data, options);
    }
  })
</script>

<div class="form-row">

  <div class="col-lg-9">  <!-- FILTROS -->
    <div class="row">

      <div class="col-lg-6">  
        <div class="form-group">
          <select id="anio" name="anio" class="custom-select custom-select-sm" style="width:100%!important;">
          </select>
        </div>
      </div>

      <div class="col-lg-6">  
        <div class="form-group">
          <select id="mes" name="mes" class="custom-select custom-select-sm" style="width:100%!important;">
          </select>
        </div>
      </div>

      <div class="col-lg-6">  
        <div class="form-group">
          <select id="f_coordinador" name="f_coordinador" class="custom-select custom-select-sm" style="width:100%!important;">
            <option value="">Seleccione coordinador | Todos</option>
            <?php 
              foreach($coordinador as $u){
            ?>
            <option value="<?php echo $u["id"]; ?>"><?php echo $u["nombre_completo"]?></option>
            <?php
              }
            ?>
          </select>
        </div>
      </div>

      <div class="col-lg-6">  
        <div class="form-group">
          <select id="f_comuna" name="f_comuna" class="custom-select custom-select-sm" style="width:100%!important;">
            <option value="">Seleccione comuna | Todos</option>
            <?php 
              foreach($comuna as $c){
            ?>
            <option value="<?php echo $c["id"]; ?>"><?php echo $c["titulo"]?></option>
            <?php
              }
            ?>
          </select>
        </div>
      </div>

      <div class="col-lg-6">  
        <div class="form-group">
          <select id="f_zona" name="f_zona" class="custom-select custom-select-sm" style="width:100%!important;">
            <option value="">Seleccione zona | Todos</option>
            <?php 
              foreach($zona as $z){
            ?>
            <option value="<?php echo $z["id"]; ?>"><?php echo $z["area"]?></option>
            <?php
              }
            ?>
          </select>
        </div>
      </div>

    </div>
  </div>

  <div class="col-md-3">  
  <p class="titulo_grafico">Motivos x zona</p>
    <div id="graficoMotivosxZona" style="width:100%!important; height: 100%!important;"></div>
  </div>

</div>

<div class="col-12 mt-2"  id="contenedor_graficoMotivos">
  <div class="card">
    <div class="col-lg-12 "> 
      <p class="titulo_grafico">motivos</p>
      <div id="graficoMotivos"style="width: 100%; height: 600px;"></div>
    </div>
  </div>
</div>

<div class="col-12 mt-2"  id="contenedor_graficoMotivosxTecnico">
  <div class="card">
    <div class="col-lg-12 ">
      <p class="titulo_grafico">motivos x técnico</p>
      <div id="graficoMotivosxTecnico"style="width: 100%; height: 600px;"></div>
    </div>
  </div>
</div>

<div class="col-12 mt-2"  id="contenedor_graficoMotivosxComuna">
  <div class="card">
    <div class="col-lg-12 ">
      <p class="titulo_grafico">motivos x comuna</p>
      <div id="graficoMotivosxComuna" style="width: 100%; height: 600px;"></div>
    </div>
  </div>
</div>


<div class="col-12 mt-2"  id="contenedor_graficoMotivosxCoordinador">
  <div class="card">
    <div class="col-lg-12" >
      <p class="titulo_grafico">motivos x coordinador</p>
      <div id="graficoMotivosxCoordinador"style="width: 100%; height: 600px;"></div>
    </div>
  </div>
</div>

<div class="col-12 mt-2"  id="contenedor_graficoMotivosxTecnicoDetalle">
  <div class="card">
    <div class="col-lg-12" >
      <p class="titulo_grafico">motivos x técnico (detalle)</p>
      <div id="graficoMotivosxTecnicoDetalle" style="height: 100%; width: 100%;"></div>
    </div>
  </div>
</div>