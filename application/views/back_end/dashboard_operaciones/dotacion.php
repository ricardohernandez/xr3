 <style>
  .body{
    display: none;
  }
 </style>
<script>
  const base_url = "<?php echo base_url() ?>"
  const mes_inicio_dot = "<?php echo $mes_inicio ?>"
  $("#mes_inicio_dot").val(mes_inicio_dot) 
  const mes_termino_dot = "<?php echo $mes_termino ?>"
  $("#mes_termino_dot").val(mes_termino_dot) 

  cargarGraficoDotacion()

  function cargarGraficoDotacion () {
  var mes_inicio_dot = $("#mes_inicio_dot").val();
  var mes_termino_dot = $("#mes_termino_dot").val();
  var tipo_dot = $("#tipo_dot").val();

  $.ajax({
      url: base_url+'graficoDotacion',
      type: 'POST',
      data: {
          'mes_inicio_dot': mes_inicio_dot,
          'mes_termino_dot': mes_termino_dot,
          'tipo': tipo_dot
      },
      dataType: "json",
      beforeSend:function(){
        $("#load").show()
        $(".body").hide()  
      }, 
      success: function (response) {
        $("#load").hide()
        $(".body").fadeIn(500)
        crearGraficoDotacion(response);
      },
      error: function (error) {
          console.log(error);
      }
  });
  }
 
  function crearGraficoDotacion(data) {
    var numTotalColumnas = data[0].length; // Obtener el número total de columnas
    var data = google.visualization.arrayToDataTable(data);


    data.sort([{ column: numTotalColumnas-1, desc: false }]);

    const options = {
      fontName: 'ubuntu',
      curveType: 'function',
      fontColor: '#32477C',
      backgroundColor: { fill: 'transparent' },
      chartArea: {
        left: 80,
        right: 40,
        bottom: 40,
        top: 40,
      },
      height: 600,

      titleTextStyle: {
      color: 'red' // Cambia aquí el color del texto del título
    },

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

     vAxes : {
       0: 
        {
        title:'Promedio sur, Promedio norte y claro',
        textStyle:{color: '#808080',bold:false,fontSize: 12},
          gridlines: {color:'#808080', count:0},
          viewWindowMode:'explicit',
          viewWindow: {
            min: 0,
            max: 200
          },
        },
        1: 
        {
        textStyle:{color: '#808080',bold:false,fontSize: 12},
          gridlines: {color:'#808080', count:0},
          viewWindow: {
            min: 0,
            max: 200
          },
        },
  
      },

      /*  seriesType:'bars', */
      series: {
        0: {
          type: 'bars',
          color: '#414FB1',
      
          textStyle: {
            fontSize: 12,
            color: '#fff',
            strokeSize: 1,
            auraColor: 'transparent'
          },
          annotations: {
            stem:{
                  length: 4
            },   
          }
        },
        1: {
          type: 'bars',
          color: '#E66C37',
          annotations: {
          style: 'line',
          textStyle: {
            fontSize: 12,
            color: '#fff',
            strokeSize: 1,
            auraColor: 'transparent'
          },
          alwaysOutside: false,  
          stem:{
            color: 'transparent',
            length: 8
          },   
          }
        },
        2: {
          type: 'bars',
          color: 'grey',
          targetAxisIndex:1,
          annotations: {
          style: 'line',
          textStyle: {
            fontSize: 12,
            color: '#fff',
            strokeSize: 1,
            auraColor: 'transparent'
          },
          alwaysOutside: false,  
          stem:{
            color: 'transparent',
            length: 8
          },   
          }
        },

        3: {
          type: 'line',
          color: '#414FB1',
          lineDashStyle: [4, 4], 

          lineWidth: 2,
          pointSize: 5,
          pointShape: 'cirle',
          targetAxisIndex:0,
          
          annotations: {
          style: 'line',
          textStyle: {
            fontSize: 12,
            color: '#808080',
            strokeSize: 1,
            auraColor: 'transparent'
          },
          alwaysOutside: false,  
          stem:{
              color: 'transparent',
              length: 8
            },   
          }
        },

        4: {
          type: 'line',
          color: 'purple',//#FFFF60
          targetAxisIndex:1,

          lineWidth: 2,
          pointSize: 5,
          pointShape: 'cirle',
          targetAxisIndex:0,

          annotations: {
          style: 'line',
          textStyle: {
            fontSize: 12,
            color: '#808080',
            strokeSize: 1,
            auraColor: 'transparent'
          },
          alwaysOutside: false,  
          stem:{
              color: 'transparent',
              length: 8
            },   
          }
        },

        5: {
          type: 'line',
          lineDashStyle: [4, 4], 
          color: 'red',

          lineWidth: 2,
          pointSize: 5,
          pointShape: 'cirle',
          targetAxisIndex:0,
          
          annotations: {
          style: 'line',
          textStyle: {
            fontSize: 12,
            color: '808080',
            strokeSize: 1,
            auraColor: 'transparent'
          },
          alwaysOutside: false,  
          stem:{
              color: 'transparent',
              length: 8
            },   
          }
        },

        6: {
          type: 'line',
          lineDashStyle: [4, 4], 
          color: 'brown',//

          lineWidth: 2,
          pointSize: 5,
          pointShape: 'cirle',
          targetAxisIndex:0,

          targetAxisIndex:1,
          annotations: {
          style: 'line',
          textStyle: {
            fontSize: 12,
            color: '808080',
            strokeSize: 1,
            auraColor: 'transparent'
          },
          alwaysOutside: false,  
          stem:{
              color: 'transparent',
              length: 8
            },   
          }
        },

        7: {
          type: 'line',
          color: 'green',

          lineWidth: 2,
          pointSize: 5,
          pointShape: 'cirle',
          targetAxisIndex:0,

          targetAxisIndex:1,
          annotations: {
          style: 'line',
          textStyle: {
            fontSize: 12,
            color: '808080',
            strokeSize: 1,
            auraColor: 'transparent'
          },
          alwaysOutside: false,  
          stem:{
              color: 'transparent',
              length: 8
            },   
          }
        }


      }

    };
    

    var chart;
    chart = new google.visualization.ComboChart(document.getElementById("dotacion"));
    chart.draw(data, options);

   
  }

  $(document).off('change', '#mes_inicio_dot,#mes_termino_dot,#tipo_dot').on('change', '#mes_inicio_dot,#mes_termino_dot,#tipo_dot', function (event) {
    cargarGraficoDotacion ();
  });


</script>

<!-- FILTROS -->
  
<div class="form-row">
  <?php
    if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
        ?>
        <div class="col-6 col-lg-1">  
        <input type="file" id="userfile" name="userfile" class="file_cs" style="display:none;" />
        <button type="button"  class="btn-block btn btn-sm btn-primary btn_file_cs btn_xr3" onclick="document.getElementById('userfile').click();">
        <i class="fa fa-file-import"></i> Cargar base  
        </div>
        <?php
    }
  ?>

  <div class="col-12 col-lg-3">
    <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="font-size:12px;margin-left:5px;"> Meses<span></span> 
        </div>
        <input type="month" placeholder="Desde" class=" form-control form-control-sm"  name="mes_inicio_dot" id="mes_inicio_dot">
        <input type="month" placeholder="Hasta" class=" form-control form-control-sm"  name="mes_termino_dot" id="mes_termino_dot">
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-2">
    <div class="form-group">
    <select id="tipo_dot" name="tipo_dot" class="custom-select custom-select-sm">
      <option value="" selected>Seleccione tipo | Todos</option>
      <option value="norte">Norte</option>
      <option value="sur">Sur</option>
      <option value="fte">FTE</option>
    </select>
    </div>
  </div>

</div>   

<div style="text-align: center;;">
  <i id="load" class="fa-solid fa-circle-notch fa-spin fa-8x" style="color: #1A56DB; opacity: .4;margin-top:250px"></i>
</div>

<div class="body">

<div class="row mt-2 contenedor_graficos">
  <div class="col-12">
    <div class="card">
      <div class="col-12">
          <p class="titulo_grafico">Q Móviles</p>
          <div id="dotacion"></div>
        </div>
      </div>
  </div>

</div>