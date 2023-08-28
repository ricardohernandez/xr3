 <style>
  .file_cs{
    display:none;
  }
 
  .body{
    display: none;
  }
 </style>
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
        crearGraficoEps('calidadnacional', response.calidadnacional, 'column');
        crearGraficoEps('calidadHFC', response.calidadHFC, 'column');
        crearGraficoEps('calidadFTTH', response.calidadFTTH, 'column');

        crearGraficoEps('productividadnor', response.productividadnor, 'line');
        crearGraficoEps('productividadHFCnor', response.productividadHFCnor, 'line');
        crearGraficoEps('productividadFTTHnor', response.productividadFTTHnor, 'line');
        crearGraficoEps('calidadnor', response.calidadnor, 'column');
        crearGraficoEps('calidadHFCnor', response.calidadHFCnor, 'column');
        crearGraficoEps('calidadFTTHnor', response.calidadFTTHnor, 'column');

        crearGraficoEps('productividadsur', response.productividadsur, 'line');
        crearGraficoEps('productividadHFCsur', response.productividadHFCsur, 'line');
        crearGraficoEps('productividadFTTHsur', response.productividadFTTHsur, 'line');
        crearGraficoEps('calidadsur', response.calidadsur, 'column');
        crearGraficoEps('calidadHFCsur', response.calidadHFCsur, 'column');
        crearGraficoEps('calidadFTTHsur', response.calidadFTTHsur, 'column');
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
      if (array[i].includes("productividad")) {
        return true;  
      }
    }
    return false;
  }


  function crearGraficoEps(divId, data, tipoGrafico) {
   /*  console.log(data) */
    var contieneMeta = contieneElementoMeta(data);
    var contieneCalidad = contieneElementoCalidad(data);
    var contieneProd = contieneElementoProd(data);

    var data = google.visualization.arrayToDataTable(data);
    data.sort([{ column: 8, desc: false }]);

    const options = {
      fontName: 'ubuntu',
      curveType: 'function',
      fontColor: '#32477C',
      backgroundColor: { fill: 'transparent' },
      colors: ['#118DFF','#12239E', '#F48432'],
      chartArea: {
        left: 40,
        right: 40,
        bottom: 40,
        top: 40,
      },
      height: 230,
      width:"100%",
      hAxis: {
        title: '',
        minValue: 0,
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
        alwaysOutside: false,
        textStyle: {
          fontSize: 13,
          color: '#808080',
          auraColor: 'none'
        }
      },
      avoidOverlappingGridLines: true,

      legend: {
        position: 'top',
        alignment: 'center',
        textStyle: {
          fontSize: 14,
          bold: true,
          color: '#808080'
        }
      },
      tooltip: {
        textStyle: {
          color: '#ffffff96',
          fontSize: 13
        }
      },
    };

    if(contieneMeta && contieneCalidad){
      
      options.vAxes ={
       0: 
        {
        textStyle:{color: '#808080',bold:false,fontSize: 12},
          gridlines: {color:'#808080', count:0},
          viewWindowMode:'explicit',
          viewWindow: {
            min: 0,
            max: 10
          },
        },
        1: 
        {
          textStyle:{color: '#808080',bold:false,fontSize: 12},
            gridlines: {color:'#808080', count:0},
            viewWindow: {
              min: 0,
            max: 10
            },
          }
     },


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
          targetAxisIndex: 0,
          annotations: {
            stem: {
              length: 4
            },
            
          }
        },
        0: {
          type: 'bars',
          color: '#2F81F7',
          targetAxisIndex: 1,
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

    }

    if(contieneMeta && contieneProd){
      
      options.series = {
        1: {
          type: 'line',
          lineDashStyle: [4, 4], 
          color: '#808080',
          curveType: 'function',
          lineWidth: 2,
          pointSize: 5,
          pointShape: 'square',
          targetAxisIndex: 0,
          annotations: {
            stem: {
              length: 4
            }
          }
        },
        0: {
          type: 'line',
          color: '#2F81F7',
          targetAxisIndex: 1,
          annotations: {
            style: 'line',
            textStyle: {
              fontSize: 12,
              color: '#808080',
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


    }

    var chart;
    
    if (tipoGrafico === 'line') {
      chart = new google.visualization.LineChart(document.getElementById(divId));
    } else if (tipoGrafico === 'column') {
      chart = new google.visualization.ColumnChart(document.getElementById(divId));
    }

    chart.draw(data, options);

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
              <div id="productividadnacional"></div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 contenedor_nacional">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Calidad nacional X EPS</p>
              <div id="calidadnacional"></div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_nacional">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Productividad HFC X EPS</p>
              <div id="productividadHFC"></div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_nacional">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Calidad HFC X EPS</p>
              <div id="calidadHFC"></div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_nacional">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Productividad FTTH X EPS</p>
              <div id="productividadFTTH"></div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_nacional">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Calidad FTTH X EPS</p>
              <div id="calidadFTTH"></div>
            </div>
          </div>
        </div>
 
        <div class="col-12 col-lg-6 contenedor_norte">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Productividad norte X EPS</p>
              <div id="productividadnor"></div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 contenedor_norte">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Calidad norte X EPS</p>
              <div id="calidadnor"></div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_norte">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Productividad HFC norte X EPS</p>
                <div id="productividadHFCnor"></div>
              </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_norte">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Calidad HFC norte X EPS</p>
                <div id="calidadHFCnor"></div>
              </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_norte">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Productividad FTTH norte X EPS</p>
                <div id="productividadFTTHnor"></div>
              </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_norte">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Calidad FTTH norte X EPS</p>
                <div id="calidadFTTHnor"></div>
              </div>
            </div>
        </div>
  
 
        <div class="col-12 col-lg-6 contenedor_sur">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Productividad  sur X EPS</p>
              <div id="productividadsur"></div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 contenedor_sur">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Calidad  sur X EPS</p>
                <div id="calidadsur"></div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_sur">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Productividad HFC sur X EPS</p>
                <div id="productividadHFCsur"></div>
              </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_sur">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Calidad HFC sur X EPS</p>
                <div id="calidadHFCsur"></div>
              </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_sur">
          <div class="card">
            <div class="col-12">
                <p class="titulo_grafico">Productividad FTTH sur X EPS</p>
                <div id="productividadFTTHsur"></div>
              </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-2 contenedor_sur">
          <div class="card">
            <div class="col-12">
              <p class="titulo_grafico">Calidad FTTH sur X EPS</p>
              <div id="calidadFTTHsur"></div>
            </div>
          </div>
        </div>
     
  </div>
</div>