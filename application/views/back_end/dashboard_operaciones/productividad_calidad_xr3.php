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
  const mes_inicio = "<?php echo $mes_inicio ?>"
  $("#mes_inicio").val(mes_inicio) 
  const mes_termino = "<?php echo $mes_termino ?>"
  $("#mes_termino").val(mes_termino) 

  cargarGrafico()

  function cargarGrafico () {
  var mes_inicio = $("#mes_inicio").val();
  var mes_termino = $("#mes_termino").val();

  $.ajax({
      url: base_url+'graficosProductividadXR3',
      type: 'POST',
      data: {
          'mes_inicio': mes_inicio,
          'mes_termino': mes_termino
      },
      dataType: "json",
      beforeSend:function(){
        $("#load").show()
        $(".body").hide()  
      }, 
      success: function (response) {
        $("#load").hide()
        $(".body").fadeIn(500)
        crearGrafico('productividadnacional', response.productividadnacional, 'line');
        crearGrafico('productividadnorteHFC', response.productividadnorteHFC, 'line');
        crearGrafico('productividadnorteFTTH', response.productividadnorteFTTH, 'line');
        crearGrafico('productividadsurHFC', response.productividadsurHFC, 'line');
        crearGrafico('productividadsurFTTH', response.productividadsurFTTH, 'line');

        crearGrafico('calidadnacional', response.calidadnacional, 'column');
        crearGrafico('calidadnorteHFC', response.calidadnorteHFC, 'column');
        crearGrafico('calidadnorteFTTH', response.calidadnorteFTTH, 'column');
        crearGrafico('calidadsurHFC', response.calidadsurHFC, 'column');
        crearGrafico('calidadsurFTTH', response.calidadsurFTTH, 'column');

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


  function crearGrafico(divId, data, tipoGrafico) {
    console.log(data)
    var contieneMeta = contieneElementoMeta(data);
    var contieneCalidad = contieneElementoCalidad(data);
    var contieneProd = contieneElementoProd(data);

    var data = google.visualization.arrayToDataTable(data);
    data.sort([{ column: 5, desc: false }]);

    const options = {
      fontName: 'ubuntu',
      curveType: 'function',
      fontColor: '#32477C',
      backgroundColor: { fill: 'transparent' },
      colors: ['#F48432', '#2f81f7'],
      chartArea: {
        left: 40,
        right: 40,
        bottom: 40,
        top: 40,
      },
      height: 230,
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
      
      options.vAxes ={
        0: 
          {
          textStyle:{color: '#808080',bold:false,fontSize: 12},
            gridlines: {color:'#808080', count:0},
            viewWindowMode:'explicit',
            viewWindow: {
              min: 3,
              max: 6
            },
          },
          1: 
          {
            textStyle:{color: '#808080',bold:false,fontSize: 12},
              gridlines: {color:'#808080', count:0},
              viewWindow: {
                min: 3,
                max: 6
              },
            }
      },
      
      options.series = {
        1: {
          type: 'line',
          lineDashStyle: [4, 4], 
          color: '#808080',
           lineWidth: 1,
          pointSize: 1,
          pointShape: 'square',
          targetAxisIndex: 1,
          annotations: {
            stem: {
              color: 'transparent',
              length: -20
            }
          }
        },
        0: {
          type: 'line',
          color: '#2F81F7',
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
              color: '808080',
              length: 11
            }
          }
        }
        }


    }

    var chart;
    
    if (tipoGrafico === 'line') {
      chart = new google.visualization.LineChart(document.getElementById(divId));
    } else if (tipoGrafico === 'column') {
      chart = new google.visualization.ColumnChart(document.getElementById(divId));
    }

    chart.draw(data, options);

   
  }

  $(document).off('change', '#mes_inicio,#mes_termino').on('change', '#mes_inicio,#mes_termino', function (event) {
    cargarGrafico ();
  });

  $(document).off('change', '.file_cs').on('change', '.file_cs',function(event) {
    var myFormData = new FormData();
    myFormData.append('userfile', $('#userfile').prop('files')[0]);
    $.ajax({
        url: "cargaDashboardProductividadXR3"+"?"+$.now(),  
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

              productividadNacional()

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
        <input type="month" placeholder="Desde" class=" form-control form-control-sm"  name="mes_inicio" id="mes_inicio">
        <input type="month" placeholder="Hasta" class=" form-control form-control-sm"  name="mes_termino" id="mes_termino">
      </div>
    </div>
  </div>

</div>   

<div style="text-align: center;;">
  <i id="load" class="fa-solid fa-circle-notch fa-spin fa-8x" style="color: #1A56DB; opacity: .4;margin-top:250px"></i>
</div>

<div class="body">

<div class="form-row mt-2 contenedor_graficos">
  <div class="col-12 col-lg-6">
    <div class="card">
      <div class="col-12">
          <p class="titulo_grafico">Productividad nacional</p>
          <div id="productividadnacional"></div>
        </div>
      </div>
  </div>

  <div class="col-12 col-lg-6">
    <div class="card">
      <div class="col-12">
          <p class="titulo_grafico">Calidad nacional</p>
          <div id="calidadnacional"></div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-6 mt-2">
    <div class="card">
      <div class="col-12">
          <p class="titulo_grafico">Productividad zona norte HFC</p>
          <div id="productividadnorteHFC"></div>
        </div>
      </div>
  </div>

  <div class="col-12 col-lg-6 mt-2">
    <div class="card">
      <div class="col-12">
          <p class="titulo_grafico">Calidad zona norte HFC</p>
          <div id="calidadnorteHFC"></div>
        </div>
      </div>
  </div>

  <div class="col-12 col-lg-6 mt-2">
    <div class="card">
      <div class="col-12">
          <p class="titulo_grafico">Productividad zona norte FTTH</p>
          <div id="productividadnorteFTTH"></div>
        </div>
      </div>
  </div>

  <div class="col-12 col-lg-6 mt-2">
    <div class="card">
      <div class="col-12">
          <p class="titulo_grafico">Calidad zona norte FTTH</p>
          <div id="calidadnorteFTTH"></div>
        </div>
      </div>
  </div>

  <div class="col-12 col-lg-6 mt-2">
    <div class="card">
      <div class="col-12">
          <p class="titulo_grafico">Productividad zona sur HFC</p>
          <div id="productividadsurHFC"></div>
        </div>
      </div>
  </div>

  <div class="col-12 col-lg-6 mt-2">
    <div class="card">
      <div class="col-12">
          <p class="titulo_grafico">Calidad zona sur HFC</p>
          <div id="calidadsurHFC"></div>
        </div>
      </div>
  </div>

  <div class="col-12 col-lg-6 mt-2">
    <div class="card">
      <div class="col-12">
          <p class="titulo_grafico">Productividad zona sur FTTH</p>
          <div id="productividadsurFTTH"></div>
        </div>
      </div>
  </div>

  <div class="col-12 col-lg-6 mt-2">
    <div class="card">
      <div class="col-12">
          <p class="titulo_grafico">Calidad zona sur FTTH</p>
          <div id="calidadsurFTTH"></div>
        </div>
      </div>
  </div>
</div>