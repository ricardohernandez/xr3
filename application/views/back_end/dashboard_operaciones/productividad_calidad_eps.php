 <style>
  .file_cs{
    display:none;
  }
 
  .body{
    display: none;
  }

  .canvas {
    width: 100%;
    height: 100%;
    display: block;
  }


 </style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
  
  const base_url = "<?php echo base_url() ?>"
  const mes_inicio_eps = "<?php echo $mes_inicio ?>"
  $("#mes_inicio_eps").val(mes_inicio_eps) 
  const mes_termino_eps = "<?php echo $mes_termino ?>"
  $("#mes_termino_eps").val(mes_termino_eps) 

  cargarGraficoEps()

  function cargarGraficoEps () {
  var mes_inicio_eps = $("#mes_inicio_eps").val();
  var mes_termino_eps = $("#mes_termino_eps").val();
  var tecnologia = $("#tecnologia_prod_eps").val();
  var zona = $("#zona_prod_eps").val();

  $.ajax({
      url: base_url+'graficosProductividadEps',
      type: 'POST',
      data: {
          'mes_inicio_eps': mes_inicio_eps,
          'mes_termino_eps': mes_termino_eps,
          'tecnologia': tecnologia,
          'zona': zona
      },
      dataType: "json",
      beforeSend:function(){
        $("#load").show()
        $(".body").hide()  
      }, 
      success: function (response) {
        $("#load").hide()
        $(".body").fadeIn(500)

        crearGraficoEps('productividadnacional', response.productividadnacional, 'line');
        crearGraficoEps('productividadHFC', response.productividadHFC, 'line');
        crearGraficoEps('productividadFTTH', response.productividadFTTH, 'line');
        crearGraficoEps('calidadnacional', response.calidadnacional, 'bar');
        crearGraficoEps('calidadHFC', response.calidadHFC, 'bar');
        crearGraficoEps('calidadFTTH', response.calidadFTTH, 'bar');

        crearGraficoEps('productividadnor', response.productividadnor, 'line');
        crearGraficoEps('productividadHFCnor', response.productividadHFCnor, 'line');
        crearGraficoEps('productividadFTTHnor', response.productividadFTTHnor, 'line');
        crearGraficoEps('calidadnor', response.calidadnor, 'bar');
        crearGraficoEps('calidadHFCnor', response.calidadHFCnor, 'bar');
        crearGraficoEps('calidadFTTHnor', response.calidadFTTHnor, 'bar');

        crearGraficoEps('productividadsur', response.productividadsur, 'line');
        crearGraficoEps('productividadHFCsur', response.productividadHFCsur, 'line');
        crearGraficoEps('productividadFTTHsur', response.productividadFTTHsur, 'line');
        
        crearGraficoEps('calidadsur', response.calidadsur, 'bar');
        crearGraficoEps('calidadHFCsur', response.calidadHFCsur, 'bar');
        crearGraficoEps('calidadFTTHsur', response.calidadFTTHsur, 'bar');
      },
      error: function (error) {
          console.log(error);
      }
  });
  }

  function contieneElementoMeta(array) {
    for (let i = 0; i < array.length; i++) {
      if (array[i].includes("Meta")) {
        return true;  
      }
    }
    return false;
  }

  
  function contieneElementoCalidad(array) {
    for (let i = 0; i < array.length; i++) {
      if (array[i].includes("calidad")) {
        return true;  
      }
    }
    return false;
  }

  function contieneElementoProd(array) {
    for (let i = 0; i < array.length; i++) {
      if (array[i].includes("prod")) {
        return true;  
      }
    }
    return false;
  }

  function crearGraficoEps(divId, datos, tipoGrafico){

    var ctx = document.getElementById(divId);
    largo = datos[1].length
    tipo = datos[1][largo-1];

    const existingChart = Chart.getChart(ctx);
    if (existingChart) {
        existingChart.destroy();
    }
    const labels = datos.slice(1).map(row => row[0]);
    const datasets = [];


    for (let i = 1; i < datos[0].length; i++) {
        const datasetLabel = datos[0][i];
        const datasetData = datos.slice(1).map(row => row[i]);
      if(tipo == "prod"){
          datasets.push({
            label: datasetLabel,
            data: datasetData.map(parseFloat),
            borderWidth: 1,
            type: 'line',
          });
      }
      else{
        if(datasetLabel != "Meta"){
          datasets.push({
            label: datasetLabel,
            data: datasetData.map(parseFloat),
            borderWidth: 1,
            type: 'bar',
          });
        }
        else{
          datasets.push({
            label: datasetLabel,
            data: datasetData.map(parseFloat),
            borderWidth: 1,
            type: 'line',
          });
        }
      }
    }

    new Chart(ctx, {
      type: tipoGrafico,
      data: {
        labels: labels,
        datasets: datasets,
      },
      options: {
        scales: {
          y:{
            grid:{
              display: false
            },
          },
          x:{
            grid:{
              display: false
            },
          },
        },
      }
    });
  }

  $(document).off('change', '#mes_inicio_eps, #mes_termino_eps, #zona_prod_eps, #tecnologia_prod_eps').on('change', '#mes_inicio_eps, #mes_termino_eps, #zona_prod_eps, #tecnologia_prod_eps', function (event) {
    if ($(this).attr('id') === 'zona_prod_eps') {
      if ($(this).val() === "NORTE") {
        $(".contenedor_sur").hide();
        $(".contenedor_nacional").hide();
        $(".contenedor_norte").show();
      } else if ($(this).val() === "SUR") {
        $(".contenedor_norte").hide();
        $(".contenedor_nacional").hide();
        $(".contenedor_sur").show();
      } else {
        $(".contenedor_norte").show();
        $(".contenedor_sur").show();
        $(".contenedor_nacional").show();
      }
    }
  cargarGraficoEps();
});



</script>

<!-- FILTROS -->
  
<div class="form-row">

  <div class="col-12 col-lg-3">
    <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="font-size:12px;margin-left:5px;"> Meses<span></span> 
        </div>
        <input type="month" placeholder="Desde" class=" form-control form-control-sm"  name="mes_inicio_eps" id="mes_inicio_eps">
        <input type="month" placeholder="Hasta" class=" form-control form-control-sm"  name="mes_termino_eps" id="mes_termino_eps">
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-2">
    <div class="form-group">
    <select id="zona_prod_eps" name="zona_prod_eps" class="custom-select custom-select-sm">
      <option value="" selected>Zona | Todas</option>
      <?php 
        foreach($zonas as $z){
          ?>
           <option value="<?php echo $z["zona"]?>"><?php echo $z["zona"]?></option>
          <?php
        }
      ?>
    </select>
    </div>
  </div>
   
</div>   

<div style="text-align: center;;">
  <i id="load" class="fa-solid fa-circle-notch fa-spin fa-8x" style="color: #1A56DB; opacity: .4;margin-top:250px"></i>
</div>

<div class="body">
  <div class="form-row mt-2 " id="contenedor_graficos">

   <!--  <div class="contenedor_nacional">
      <div class="form-row"> -->
       
        <div class="col-12 col-lg-6 contenedor_nacional">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Productividad nacional X EPS</p>
               <canvas class="canvas "  id="productividadnacional"></canvas>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 contenedor_nacional">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Calidad nacional X EPS</p>
              <canvas id="calidadnacional"></canvas>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_nacional">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Productividad HFC X EPS</p>
               <canvas class="canvas "  id="productividadHFC"></canvas>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_nacional">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Calidad HFC X EPS</p>
              <canvas id="calidadHFC"></canvas>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_nacional">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Productividad FTTH X EPS</p>
               <canvas class="canvas "  id="productividadFTTH"></canvas>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_nacional">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Calidad FTTH X EPS</p>
              <canvas id="calidadFTTH"></canvas>
            </div>
          </div>
        </div>
 
        <div class="col-12 col-lg-6 contenedor_norte">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Productividad norte X EPS</p>
               <canvas class="canvas "  id="productividadnor"></canvas>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 contenedor_norte">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Calidad norte X EPS</p>
              <canvas id="calidadnor"></canvas>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_norte">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Productividad HFC norte X EPS</p>
                 <canvas class="canvas "  id="productividadHFCnor"></canvas>
              </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_norte">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Calidad HFC norte X EPS</p>
                <canvas id="calidadHFCnor"></canvas>
              </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_norte">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Productividad FTTH norte X EPS</p>
                 <canvas class="canvas "  id="productividadFTTHnor"></canvas>
              </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_norte">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Calidad FTTH norte X EPS</p>
                <canvas id="calidadFTTHnor"></canvas>
              </div>
            </div>
        </div>
  
 
        <div class="col-12 col-lg-6 contenedor_sur">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Productividad  sur X EPS</p>
               <canvas class="canvas "  id="productividadsur"></canvas>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 contenedor_sur">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Calidad  sur X EPS</p>
                <canvas id="calidadsur"></canvas>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_sur">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Productividad HFC sur X EPS</p>
                 <canvas class="canvas "  id="productividadHFCsur"></canvas>
              </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_sur">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Calidad HFC sur X EPS</p>
                <canvas id="calidadHFCsur"></canvas>
              </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_sur">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Productividad FTTH sur X EPS</p>
                 <canvas class="canvas "  id="productividadFTTHsur"></canvas>
              </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_sur">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Calidad FTTH sur X EPS</p>
              <canvas id="calidadFTTHsur"></canvas>
            </div>
          </div>
        </div>
     
  </div>
</div>