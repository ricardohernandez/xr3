<style type="text/css">
  .actualizacion_calidad,.actualizacion_productividad{
      display: inline-block;
      font-size: 13px;
  }

  .titulo{
      display: inline-block;
      font-size: 13px;
  }
  .alert-primary{

  }
  .card{
    border: none!important;
    -webkit-box-shadow: 0 0 10px 0 rgb(183 192 206 / 20%);
  }
  .card-header{
     background-color: #fff!important;
  }
  .card_dash{
    /* background-color: #32477C!important;*/
    /* color:#fff!important;*/
    /* border-top: none!important;
     border-bottom: none!important;*/
     border: none!important;
     border-top: none!important;
     border-bottom: none!important
     background-color: #fff!important;
     color:#32477C!important;
     padding: 0.25rem 0.75rem!important;
     font-size: 16px;
  }
  .card-body{
    padding: 0.65rem!important;
  }
  hr {
    margin-top: 1rem!important;
    margin-bottom: 1rem!important;
    border: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
  }
  .titulo_seccion{
    color: #32477C;
    font-size: 16px; 
    font-weight:bold;
    text-align: left;
    padding:4px 2px;
  }
  .desc_seccion{
    color: #32477C;
    font-size: 12px; 
    font-weight:bold;
    text-align: left;
    padding:7px 2px;
  }

  .dataTables_paginate .paginate_button {
    margin-top: 3px!important;
    padding: 2px 4px!important;
    text-decoration: none;
    font-size: 12px!important; 
    color: #fff!important; 
    background-color: #32477C!important; 
    cursor: pointer;
  }

  .select2-container--default .select2-selection--single {
      border: 1px solid #ced4da!important;
  }

</style>

<script type="text/javascript">
  $(function(){
    const perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    const base = "<?php echo base_url() ?>";
    const base_url = "<?php echo base_url() ?>";

    $.getJSON(base + "listaTrabajadoresIGT", { jefe : $("#jefe_det").val() } , function(data) {
       response = data;
       }).done(function() {

      $("#trabajadores").select2({
         placeholder: 'Seleccione Trabajador | Todos',
         data: response,
         width: 'resolve',
      });

      $("#trabajadores").select2().val("173397666").trigger("change");

      setTimeout( function () {
          google.charts.setOnLoadCallback(dataGraficosIgt);
      }, 500 ); 
    });

   
    function dataGraficosIgt(){
      var periodo = $("#periodo_detalle").val();
      var trabajador = perfil <= 3 ? $("#trabajadores").val() : $("#trabajador").val();
      var proyecto = ""

      $.ajax({
          url: base_url+"dataGraficosIgt"+"?"+$.now(),  
          type: 'POST',
          data:{periodo:periodo,trabajador:trabajador},
          dataType:"json",
          beforeSend:function(){
            $("#load").show()
            $(".body").hide()
          },
          success: function (json) {
            $("#load").hide()
            $(".body").fadeIn(500)

          // GRAFICO PROMEDIO FTTH

            if(json.hasOwnProperty("data_prom_ftth") && json["data_prom_ftth"]!=false){

              $("#prom_ftth").text(json.data_prom_ftth.data.puntos).show()
              $(".prom_ftth").show()

              var data_prom_ftth = google.visualization.arrayToDataTable(json.data_prom_ftth.data);
              var options = {
                height: 160,
                min: 0,
                max: 5,
                redFrom:1,
                redTo:2,
                yellowFrom:2,
                yellowTo: json.data_prom_ftth.meta,
                greenFrom: json.data_prom_ftth.meta,
                greenTo:6,
              };

              var chart = new google.visualization.Gauge(document.getElementById('grafico_prom_ftth'));
              chart.draw(data_prom_ftth, options);

            }else{
              $(".prom_ftth").hide()
              $("#prom_ftth").text("").hide()
            }

          // GRAFICO PROD HFC FTTH
            if(json.hasOwnProperty("data_prod_hfc_ftth")){

              $("#prod_hfc_ftth").text(json.data_prod_hfc_ftth.data.puntos).show()
              $(".prod_hfc_ftth").show()

              var data_prod_periodo = google.visualization.arrayToDataTable(json.data_prod_hfc_ftth.data);
              var options = {
                height: 160,
                min: 0,
                max: 12000,
                redFrom:2000,
                redTo:5000,
                yellowFrom:5000,
                yellowTo: json.data_prod_hfc_ftth.meta,
                greenFrom: json.data_prod_hfc_ftth.meta,
                greenTo:12000,
               /* minorTicks: 5,*/
               /* majorTicks: ['2000', '4000', '6000', '8000', '10000'],*/
              };

              var chart = new google.visualization.Gauge(document.getElementById('grafico_prod_hfc_ftth'));
              /* var formatnumbers = new google.visualization.NumberFormat({
                    suffix: '%',
                 fractionDigits: 2
              });
              formatnumbers.format(data_prod_periodo, 1);*/
              chart.draw(data_prod_periodo, options);

            }else{
              $(".prod_hfc_ftth").hide()
              $("#prod_hfc_ftth").text("").hide()
            }
         
          // GRAFICO PROMEDIO PUNTOS HFC

            if(json.hasOwnProperty("data_prom_hfc")){

              $("#prom_hfc").text(json.data_prom_hfc.data.promedio).show()
              $(".prom_hfc").show()

              var data_prom_periodo = google.visualization.arrayToDataTable(json.data_prom_hfc.data);
              var options = {
                height: 160,
                min: 0,
                max: 350,
                redFrom:100,
                redTo:150,
                yellowFrom:150,
                yellowTo: json.data_prom_hfc.meta,
                greenFrom: json.data_prom_hfc.meta,
                greenTo:350,
                minorTicks: 5,
              };

              var chart = new google.visualization.Gauge(document.getElementById('grafico_prom_hfc'));
              chart.draw(data_prom_periodo, options);

            }else{
              $(".prom_hfc").hide()
              $("#prom_hfc").text("").hide()
            }

          // GRAFICO DIAS TRABAJADOS

            if(json.hasOwnProperty("data_dias")){

              $("#dias_trabajados").text(json.data_dias.data.dias).show()
              $(".dias_trabajados").show()

              var data_dias = google.visualization.arrayToDataTable(json.data_dias.data);
              var options = {
                height: 160,
                min: 0,
                max: 28,
                redFrom:15,
                redTo:18,
                yellowFrom:18,
                yellowTo: json.data_dias.meta,
                greenFrom: json.data_dias.meta,
                greenTo:28,
                minorTicks: 5,
              };

              var chart = new google.visualization.Gauge(document.getElementById('grafico_dias_trabajados'));
              chart.draw(data_dias, options);

            }else{
              $(".dias_trabajados").hide()
              $("#dias_trabajados").text("").hide()
            }

          // GRAFICO CALIDAD HFC 

            if(json.hasOwnProperty("data_calidad_hfc")){

              $("#calidad_hfc").text(json.data_calidad_hfc.data.calidad).show()
              $(".calidad_hfc").show()

              var calidad_hfc = google.visualization.arrayToDataTable(json.data_calidad_hfc.data);
              var options = {
                height: 160,
                min: 0,
                max: 12,
                redFrom:0,
                redTo:6,
                yellowFrom:6,
                yellowTo: json.data_calidad_hfc.meta,
                greenFrom: json.data_calidad_hfc.meta,
                greenTo:12,
                minorTicks: 5,
              };

              var chart = new google.visualization.Gauge(document.getElementById('grafico_calidad_hfc'));
              chart.draw(calidad_hfc, options);

            }else{
              $(".calidad_hfc").hide()
              $("#calidad_hfc").text("").hide()
            }

          // GRAFICO CALIDAD FTTH 

            if(json.hasOwnProperty("data_calidad_ftth")){

              $("#calidad_ftth").text(json.data_calidad_ftth.data.calidad).show()
              $(".calidad_ftth").show()

              var calidad_ftth = google.visualization.arrayToDataTable(json.data_calidad_ftth.data);
              var options = {
                height: 160,
                min: 0,
                max: 12,
                redFrom:0,
                redTo:6,
                yellowFrom:6,
                yellowTo: json.data_calidad_ftth.meta,
                greenFrom: json.data_calidad_ftth.meta,
                greenTo:12,
                minorTicks: 5,
              };

              var chart = new google.visualization.Gauge(document.getElementById('grafico_calidad_ftth'));
              chart.draw(calidad_ftth, options);

            }else{
              $(".calidad_ftth").hide()
              $("#calidad_ftth").text("").hide()
            }

          // GRAFICO  DECLARACION OT 

            if(json.hasOwnProperty("data_declaracion_ot")){

              $("#declaracion_ot").text(json.data_calidad_ftth.data.declaracion).show()
              $(".declaracion_ot").show()

              var declaracion_ot = google.visualization.arrayToDataTable(json.data_declaracion_ot.data);
              var options = {
                height: 160,
                min: 0,
                max: 100,
                redFrom:50,
                redTo:75,
                yellowFrom:75,
                yellowTo: json.data_declaracion_ot.meta,
                greenFrom: json.data_declaracion_ot.meta,
                greenTo:101,
                minorTicks: 5,
              };

              var chart = new google.visualization.Gauge(document.getElementById('grafico_declaracion_ot'));
              chart.draw(declaracion_ot, options);

            }else{
              $(".declaracion_ot").hide()
              $("#declaracion_ot").text("").hide()
            }

          // FOTO

            if(json.hasOwnProperty("foto")){
              $("#foto_tecnico").attr("src", `./fotos_usuarios/${json.foto}`).height(160).width(160);
              $(".foto_tecnico").show()
            }else{
              $("#foto_tecnico").attr("src", `./assets3/imagenes/logo.png`).show().height(40).width(90);
              $(".foto_tecnico").hide()
            }

            $(".actualizacion_calidad").html("<b>Última actualización planilla : "+json.actualizacion_calidad+"</b>");
            $(".actualizacion_productividad").html("<b>Última actualización planilla : "+json.actualizacion_productividad+"</b>");


          // CALIDAD HFC

            if(json.hasOwnProperty("data_calidad_hfc_periodos")){

              $("#graficoHFC").show()
              $(".graficoHFC").show()

              var graficoHFC = google.visualization.arrayToDataTable(json.data_calidad_hfc_periodos.data);
              graficoHFC.sort([{column: 4, desc: false}])

              var options = {
                isStacked: true,
                width: "100%",
                height: 270,
                is3D:true,
                colors:["#32477C","#DC3912"],
                fontName: 'Nunito',
                bar: {groupWidth: "25%"},
                annotations: {
                     textStyle: {
                      fontSize: 10,
                      color: '#32477C',
                      auraColor: 'transparent'
                    },
                    alwaysOutside: false,  
                      stem:{
                        color: 'transparent',
                        length: 8
                      },   
                },
                chartArea:{
                 left:40,
                 right:40,
                 bottom:40,
                 top:50,
                 width:"100%",
                 height:"100%",
                },

                backgroundColor: '#fff',
                titleTextStyle: {
                 color: '#32477C',
                 fontSize: 13, 
                 fontWidth: 'normal',
                 bold:true
                },

                legend: {
                 'position':'top',
                 'alignment':'center',
                  textStyle: {
                    fontSize: 12,
                    bold:true,
                    color:'#32477C'
                  }
                }, 

                hAxis: {
                  textStyle:{
                    color: '#32477C', 
                    fontSize: 10,
                    bold:true,
                  },
                },
                vAxis: {
                  textStyle:{
                    color: '#32477C',
                    bold:true,
                    fontSize: 10
                  },
                },

                tooltip:{textStyle:{fontSize:12},isHtml: true},

                vAxes: {
                  0: 
                    {
                    textStyle:{color: '#32477C',bold:false,fontSize: 11},
                      gridlines: {color:'#ccc', count:5},
                      viewWindowMode:'explicit',
                      /*viewWindow: {
                        min: 0,
                        max: 30000
                      },*/
                    },
                    1: 
                    {
                      textStyle:{color: '#32477C',bold:false,fontSize: 11},
                        gridlines: {color:'transparent', count:0},
                        /*viewWindow: {
                            min: 0,
                            max: 100
                        },*/
                      }
                },

                isStacked: true,        
                seriesType:'bars',
                series: {
                  0: {
                    type: 'line',
                    color: 'grey',
                    curveType: 'function',
                    lineWidth: 2,
                    pointSize: 5,
                    pointShape: 'square',
                    targetAxisIndex:0,
                    annotations: {
                      stem:{
                           length: 4
                      },   
                    }
                  },
                  1: {
                    type: 'bars',
                    color: '#32477C',
                    targetAxisIndex:1,
                    annotations: {
                    style: 'line',
                    textStyle: {
                      fontSize: 10,
                      color: 'black',
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
                    color: '#ED7D31',
                    targetAxisIndex:1,
                    annotations: {
                    style: 'line',
                    textStyle: {
                      fontSize: 10,
                      color: 'black',
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
                },
              };

              var chart = new google.visualization.ComboChart(document.getElementById('graficoHFC'));
              chart.draw(graficoHFC, options);

            }else{
                $(".graficoHFC").hide()
                $("#graficoHFC").text("").hide()
            }


          // CALIDAD FTTH

            if(json.hasOwnProperty("data_calidad_ftth_periodos")){

              $("#graficoFTTH").show()
              $(".graficoFTTH").show()

              var graficoFTTH = google.visualization.arrayToDataTable(json.data_calidad_ftth_periodos.data);
              graficoFTTH.sort([{column: 4, desc: false}])

              var options = {
                isStacked: true,
                width: "100%",
                height: 270,
                is3D:true,
                colors:["#32477C","#DC3912"],
                fontName: 'Nunito',
                bar: {groupWidth: "25%"},
                annotations: {
                     textStyle: {
                      fontSize: 10,
                      color: '#32477C',
                      auraColor: 'transparent'
                    },
                    alwaysOutside: false,  
                      stem:{
                        color: 'transparent',
                        length: 8
                      },   
                },
                chartArea:{
                 left:40,
                 right:40,
                 bottom:40,
                 top:50,
                 width:"100%",
                 height:"100%",
                },

                backgroundColor: '#fff',
                titleTextStyle: {
                 color: '#32477C',
                 fontSize: 13, 
                 fontWidth: 'normal',
                 bold:true
                },

                legend: {
                 'position':'top',
                 'alignment':'center',
                  textStyle: {
                    fontSize: 12,
                    bold:true,
                    color:'#32477C'
                  }
                }, 

                hAxis: {
                  textStyle:{
                    color: '#32477C', 
                    fontSize: 10,
                    bold:true,
                  },
                },
                vAxis: {
                  textStyle:{
                    color: '#32477C',
                    bold:true,
                    fontSize: 10
                  },
                },

                tooltip:{textStyle:{fontSize:12},isHtml: true},

                vAxes: {
                  0: 
                    {
                    textStyle:{color: '#32477C',bold:false,fontSize: 11},
                      gridlines: {color:'#ccc', count:5},
                      viewWindowMode:'explicit',
                      /*viewWindow: {
                        min: 0,
                        max: 30000
                      },*/
                    },
                    1: 
                    {
                      textStyle:{color: '#32477C',bold:false,fontSize: 11},
                        gridlines: {color:'transparent', count:0},
                        /*viewWindow: {
                            min: 0,
                            max: 100
                        },*/
                      }
                },

                isStacked: true,        
                seriesType:'bars',
                series: {
                  0: {
                    type: 'line',
                    color: 'grey',
                    curveType: 'function',
                    lineWidth: 2,
                    pointSize: 5,
                    pointShape: 'square',
                    targetAxisIndex:0,
                    annotations: {
                      stem:{
                           length: 4
                      },   
                    }
                  },
                  1: {
                    type: 'bars',
                    color: '#32477C',
                    targetAxisIndex:1,
                    annotations: {
                    style: 'line',
                    textStyle: {
                      fontSize: 10,
                      color: 'black',
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
                    color: '#ED7D31',
                    targetAxisIndex:1,
                    annotations: {
                    style: 'line',
                    textStyle: {
                      fontSize: 10,
                      color: 'black',
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
                },
              };

              var chart = new google.visualization.ComboChart(document.getElementById('graficoFTTH'));
              chart.draw(graficoFTTH, options);

            }else{
                $(".graficoFTTH").hide()
                $("#graficoFTTH").text("").hide()
            }

          // PRODUCTIVIDAD DIARIO

            if(json.hasOwnProperty("data_puntos_prod_diario")){

              $("#graficoPuntosProductividadDiario").show()
              $(".graficoPuntosProductividadDiario").show()

              var graficoPuntosProductividadDiario = google.visualization.arrayToDataTable(json.data_puntos_prod_diario.data);
              graficoPuntosProductividadDiario.sort([{column: 3, desc: true}])
              var options = {
                title: '',
                width: "100%",
                height: 300,
                is3D:true,
                colors:["#32477C"],
                fontName: 'Nunito',
                bar: {groupWidth: "50%"},

                annotations: {
                  textStyle: {
                    fontSize: 11,
                    /*bold:true,*/
                    color: '#32477C',
                    auraColor: 'transparent'
                  },
                  alwaysOutside: false,  
                  stem:{
                    color: 'transparent',
                    length: 28
                  },   
                },

                chartArea:{
                 left:50,
                 right:10,
                 bottom:50,
                 top:20,
                 width:"100%",
                 height:"100%",
                },

                backgroundColor: '#fff',

                titleTextStyle: {
                 color: '#32477C',
                 fontSize: 12, 
                 fontWidth: 'normal',
                 bold:true
                },

                legend: {
                 'position':'right',
                }, 

                hAxis: {
                  direction: -1, 
                  slantedText: true, 
                  slantedTextAngle: 90,

                  textStyle:{
                    color: '#32477C', 
                    fontSize: 10,
                    bold:true,
                  },

                  gridlines: {
                   count:0
                  },

                },

                vAxis: {
                  textStyle:{
                    color: '#32477C',
                    bold:true,
                    fontSize: 10
                  },
                  gridlines: {
                   count:9,
                   color:"#ccc"
                 },
                  
                },

                tooltip:{textStyle:{fontSize:12},isHtml: false},
              };

              var chart = new google.visualization.ColumnChart(document.getElementById('graficoPuntosProductividadDiario'));
              chart.draw(graficoPuntosProductividadDiario, options);

            }else{
                $(".graficoPuntosProductividadDiario").hide()
                $("#graficoPuntosProductividadDiario").text("").hide()
            }
          }
      })
    }
    
    $(document).off('change', '#periodo_detalle').on('change', '#periodo_detalle', function(event) {
      if($("#trabajadores").val()!=""){
        lista_detalle_calidad.ajax.reload()
        lista_detalle_productividad.ajax.reload()
        lista_detalle_ots_drive.ajax.reload()
        dataGraficosIgt()
      }
      
    }); 

    $(document).off('change', '#trabajadores').on('change', '#trabajadores', function(event) {
      if($("#trabajadores").val()!=""){
        lista_detalle_calidad.ajax.reload()
        lista_detalle_productividad.ajax.reload()
        lista_detalle_ots_drive.ajax.reload()
        dataGraficosIgt()
      }
    }); 

    /*****CALIDAD*****/   
        var lista_detalle_calidad = $('#lista_detalle_calidad').DataTable({
         /*"sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',*/
         "iDisplayLength":25, 
         "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
         "bPaginate": true,
         "info":false,
         "aaSorting" : [[4,"desc"]],
         "scrollY": "150",
         "scrollX": true,
         "sAjaxDataProp": "result",        
         "bDeferRender": true,
         "select" : true,
         // "columnDefs": [{ orderable: false, targets: 0 }  ],
         "ajax": {
            "url":"<?php echo base_url();?>listaCalidad",
            "dataSrc": function (json) {
              $(".btn_filtro_calidad").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
              $(".btn_filtro_calidad").prop("disabled" , false);

              var desde_actual="<?php echo $desde_actual_calidad; ?>"
              var hasta_actual="<?php echo $hasta_actual_calidad; ?>"
              var desde_anterior="<?php echo $desde_anterior_calidad; ?>"
              var hasta_anterior="<?php echo $hasta_anterior_calidad; ?>"
              var periodo =$("#periodo_detalle").val()

              if(periodo=="actual"){
                $("#fecha_f").val(`${desde_actual.substring(0,5)} - ${hasta_actual.substring(0,5)}`);
              }else if(periodo=="anterior"){
                $("#fecha_f").val(`${desde_anterior.substring(0,5)} - ${hasta_anterior.substring(0,5)}`);
              }

              if($("#trabajadores").val()!=""){
                return json
              }else{
                return false
              }
              
            },       
            data: function(param){
              param.periodo = $("#periodo_detalle").val();
              param.jefe = $("#jefe_det").val();

              if(perfil==4){
                param.trabajador = $("#trabajador").val();
              }else{
                param.trabajador = $("#trabajadores").val();
              }
            }
          },    

         
          "columns": [
            { "data": "tecnico" ,"class":"margen-td centered"},
            { "data": "rut_tecnico" ,"class":"margen-td centered"},
            { "data": "comuna" ,"class":"margen-td centered"},
            { "data": "ot" ,"class":"margen-td centered"},
            { "data": "fecha" ,"class":"margen-td centered"},
            { "data": "descripcion" ,"class":"margen-td centered"},
            { "data": "cierre" ,"class":"margen-td centered"},
            { "data": "ot_2davisita" ,"class":"margen-td centered"},
            { "data": "fecha_2davisita" ,"class":"margen-td centered"},
            { "data": "descripcion_2davisita" ,"class":"margen-td centered"},
            { "data": "cierre_2davisita" ,"class":"margen-td centered"},
            { "data": "diferencia_dias" ,"class":"margen-td centered"},
            { "data": "tipo_red" ,"class":"margen-td centered"},
            { "data": "falla" ,"class":"margen-td centered"},
            { "data": "ultima_actualizacion" ,"class":"margen-td centered"}
          ]
        }); 
    
        setTimeout( function () {
          var lista_detalle_calidad = $.fn.dataTable.fnTables(true);
          if ( lista_detalle_calidad.length > 0 ) {
              $(lista_detalle_calidad).dataTable().fnAdjustColumnSizing();
        }}, 200 ); 

        setTimeout( function () {
          var lista_detalle_calidad = $.fn.dataTable.fnTables(true);
          if ( lista_detalle_calidad.length > 0 ) {
              $(lista_detalle_calidad).dataTable().fnAdjustColumnSizing();
        }}, 2000 ); 

        setTimeout( function () {
          var lista_detalle_calidad = $.fn.dataTable.fnTables(true);
          if ( lista_detalle_calidad.length > 0 ) {
              $(lista_detalle_calidad).dataTable().fnAdjustColumnSizing();
          }
        }, 4000 ); 

        $(document).on('keyup paste', '#buscador_calidad', function() {
          lista_detalle_calidad.search($(this).val().trim()).draw();
        });


        $(document).off('click', '.excel_calidad').on('click', '.excel_calidad',function(event) {
          event.preventDefault();

          if(perfil<=3){
            trabajador = $("#trabajadores").val()
          }else{
            trabajador = $("#trabajador").val();
          }

          var jefe = perfil<=3 ? $("#jefe_det").val() : "-";
          jefe = jefe=="" ? "-" : jefe;

          if(trabajador=="" || trabajador==undefined){
             trabajador="-"
          }

          var periodo = $("#periodo_detalle").val()=="" ? "actual" : $("#periodo_detalle").val()
          window.location="excel_calidad/"+periodo+"/"+trabajador+"/-";
        });
   
    /*****PRODUCTIVIDAD*****/   

      var lista_detalle_productividad = $('#lista_detalle_productividad').DataTable({
       "iDisplayLength":25, 
       "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
       "bPaginate": true,
       "info":false,
       "aaSorting" : [[1,"desc"]],
       "scrollY": "190px",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       // "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>listaDetalle",
          "dataSrc": function (json) {

            var desde_actual="<?php echo $desde_actual_prod; ?>"
            var hasta_actual="<?php echo $hasta_actual_prod; ?>"
            var desde_anterior="<?php echo $desde_anterior_prod; ?>"
            var hasta_anterior="<?php echo $hasta_anterior_prod; ?>"
            var periodo =$("#periodo_detalle").val()

            if(periodo=="actual"){
              $("#fecha_f").val(`${desde_actual.substring(0,5)} - ${hasta_actual.substring(0,5)}`);
            }else if(periodo=="anterior"){
              $("#fecha_f").val(`${desde_actual.substring(0,5)} - ${hasta_actual.substring(0,5)}`);
            }

            if($("#trabajadores").val()!=""){
              return json
            }else{
              return false
            }
           
          },       
          data: function(param){
            
            param.periodo = $("#periodo_detalle").val();
            param.jefe = $("#jefe_det").val();

            if(perfil==4){
              param.trabajador = $("#trabajador").val();
            }else{
              param.trabajador = $("#trabajadores").val();
            }
          }
        },    
       
       "columns": [
          { "data": "tecnico" ,"class":"margen-td centered"},
          { "data": "fecha" ,"class":"margen-td centered"},
          { "data": "direccion" ,"class":"margen-td centered"},
          { "data": "tipo_actividad" ,"class":"margen-td centered"},
          { "data": "comuna" ,"class":"margen-td centered"},
          { "data": "estado" ,"class":"margen-td centered"},
          { "data": "derivado" ,"class":"margen-td centered"},
          { "data": "puntaje" ,"class":"margen-td centered"},
          { "data": "ot" ,"class":"margen-td centered"},
          { "data": "estado_ot" ,"class":"margen-td centered"},
          { "data": "categoria" ,"class":"margen-td centered"},
          { "data": "equivalente" ,"class":"margen-td centered"},
          { "data": "tecnologia" ,"class":"margen-td centered"},
        ]
      });

      $(document).on('keyup paste', '#buscador_productividad', function() {
        lista_detalle_productividad.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var lista_detalle_productividad = $.fn.dataTable.fnTables(true);
        if ( lista_detalle_productividad.length > 0 ) {
            $(lista_detalle_productividad).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var lista_detalle_productividad = $.fn.dataTable.fnTables(true);
        if ( lista_detalle_productividad.length > 0 ) {
            $(lista_detalle_productividad).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var lista_detalle_productividad = $.fn.dataTable.fnTables(true);
        if ( lista_detalle_productividad.length > 0 ) {
            $(lista_detalle_productividad).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 

      $(document).off('click', '.excel_productividad').on('click', '.excel_productividad',function(event) {
         event.preventDefault();
        // var hasta = $("#hasta_f").val();  
        if(perfil==4){
          trabajador = $("#trabajador").val()
        }else{
          trabajador = $("#trabajadores").val();
        }

        var jefe = perfil<=3 ? $("#jefe_det").val() : "-";
        jefe = jefe=="" ? "-" : jefe;

        if(trabajador==""){
           trabajador="-"
        }

        var periodo = $("#periodo_detalle").val();  

        if(periodo==""){
           periodo="actual"
        }

        // window.location="excel_detalle/"+desde+"/"+hasta+"/"+trabajador;
        window.location="excel_detalle/"+periodo+"/"+trabajador+"/-";

      });

    /*****DETALLE OTS DRIVE*****/   

      var lista_detalle_ots_drive = $('#lista_detalle_ots_drive').DataTable({
       "iDisplayLength":100, 
       "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
       "bPaginate": true,
       "info":false,
       "aaSorting" : [[1,"desc"]],
       "scrollY": "190px",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       // "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>listaDetalleOtsDrive",
          "dataSrc": function (json) {
            var desde_actual="<?php echo $desde_actual_prod; ?>"
            var hasta_actual="<?php echo $hasta_actual_prod; ?>"
            var desde_anterior="<?php echo $desde_anterior_prod; ?>"
            var hasta_anterior="<?php echo $hasta_anterior_prod; ?>"
            var periodo =$("#periodo_detalle").val()

            if(periodo=="actual"){
              $("#fecha_f").val(`${desde_actual.substring(0,5)} - ${hasta_actual.substring(0,5)}`);
            }else if(periodo=="anterior"){
              $("#fecha_f").val(`${desde_actual.substring(0,5)} - ${hasta_actual.substring(0,5)}`);
            }

            if($("#trabajadores").val()!=""){
              return json
            }else{
              return false
            }
          },       
          data: function(param){
            
            param.periodo = $("#periodo_detalle").val();
            param.jefe = $("#jefe_det").val();

            if(perfil==4){
              param.trabajador = $("#trabajador").val();
            }else{
              param.trabajador = $("#trabajadores").val();
            }
          }
        },    
       
       "columns": [
          { "data": "tecnico" ,"class":"margen-td centered"},
          { "data": "fecha" ,"class":"margen-td centered"},
          { "data": "direccion" ,"class":"margen-td centered"},
          { "data": "tipo_actividad" ,"class":"margen-td centered"},
          { "data": "comuna" ,"class":"margen-td centered"},
          { "data": "estado" ,"class":"margen-td centered"},
          { "data": "derivado" ,"class":"margen-td centered"},
          { "data": "puntaje" ,"class":"margen-td centered"},
          { "data": "ot" ,"class":"margen-td centered"},
          { "data": "estado_ot" ,"class":"margen-td centered"},
        ]
      });

      $(document).on('keyup paste', '#buscador_ots_drive', function() {
        lista_detalle_ots_drive.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var lista_detalle_ots_drive = $.fn.dataTable.fnTables(true);
        if ( lista_detalle_ots_drive.length > 0 ) {
            $(lista_detalle_ots_drive).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var lista_detalle_ots_drive = $.fn.dataTable.fnTables(true);
        if ( lista_detalle_ots_drive.length > 0 ) {
            $(lista_detalle_ots_drive).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var lista_detalle_ots_drive = $.fn.dataTable.fnTables(true);
        if ( lista_detalle_ots_drive.length > 0 ) {
            $(lista_detalle_ots_drive).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 

      $(document).off('click', '.excel_drive').on('click', '.excel_drive',function(event) {
         event.preventDefault();
        // var hasta = $("#hasta_f").val();  
        if(perfil==4){
          trabajador = $("#trabajador").val()
        }else{
          trabajador = $("#trabajadores").val();
        }

        var jefe = perfil<=3 ? $("#jefe_det").val() : "-";
        jefe = jefe=="" ? "-" : jefe;

        if(trabajador==""){
           trabajador="-"
        }

        var periodo = $("#periodo_detalle").val();  

        if(periodo==""){
           periodo="actual"
        }

        // window.location="excel_detalle/"+desde+"/"+hasta+"/"+trabajador;
        window.location="excel_detalle_ots_drive/"+periodo+"/"+trabajador+"/-";

      });


  })  
</script>

<div class="form-row">

  <div class="col-12 col-lg-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb" style="padding: 0.15rem 1rem!important;">
        <li class="breadcrumb-item active" aria-current="page" style="padding: 0.1rem 1rem!important;"><a href="" style="color:#32477C;">IGT - Indicadores de gestión del técnico</a></li>
      </ol>
    </nav>
  </div>

  <div class="col-6 col-lg-2">
    <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="margin-left: 5px;margin-top: 2px;"> Periodo </span> </span> 
        </div>
          <select id="periodo_detalle" name="periodo" class="custom-select custom-select-sm">
            <option value="actual" >Actual </option>
            <option value="anterior" selected>Anterior</option>
         </select>
      </div>
    </div>
  </div>

  <div class="col-6 col-lg-1">
    <div class="form-group">
      <div class="input-group">
          <input type="text" disabled placeholder="" class="fecha_normal form-control form-control-sm"  name="fecha_f" id="fecha_f">
      </div>
    </div>
  </div>

  <?php  
    if($this->session->userdata('id_perfil')<3){
  ?>
    <!-- 
    <div class="col-6  col-lg-2">
      <div class="form-group">
        <select id="jefe_det" name="jefe_det" class="custom-select custom-select-sm">
          <option value="" selected>Seleccione Jefe | Todos</option>
          <?php  
            foreach($jefes as $j){
              ?>
                <option value="<?php echo $j["id_jefe"]?>" ><?php echo $j["nombre_jefe"]?> </option>
              <?php
            }
          ?>
        </select>
      </div>
    </div> -->

  <?php
    }elseif($this->session->userdata('id_perfil')==3){
      ?>
     <!--  <div class="col-6 col-lg-2">
        <div class="form-group">
          <select id="jefe_det" name="jefe_det" class="custom-select custom-select-sm">
            <?php  
              foreach($jefes as $j){
                ?>
                  <option selected value="<?php echo $j["id_jefe"]?>" ><?php echo $j["nombre_jefe"]?> </option>
                <?php
              }
            ?>
          </select>
        </div>
      </div> -->
      <?php
    }
  ?>

  <?php  
   if($this->session->userdata('id_perfil')<=3){
      ?>
      <div class="col-6 col-lg-2">  
        <div class="form-group">
          <select id="trabajadores" name="trabajadores" style="width:100%!important;">
              <option value="">Seleccione Trabajador | Todos</option>
          </select>
        </div>
      </div>
      <?php
   }else{
    ?>
     <div class="col-6 col-lg-2">  
        <div class="form-group">
          <select id="trabajador" name="trabajador" class="custom-select custom-select-sm" >
              <option selected value="<?php echo $this->session->userdata('rut'); ?>"><?php echo $this->session->userdata('nombre_completo'); ?></option>
          </select>
        </div>
      </div>
    <?php
   }
  ?>
</div>       

<center><i id='load' class='fa-solid fa-circle-notch fa-spin fa-8x text-center'  style='margin-top:300px;color:#32477C;'></i></center>
<div class="form-row">
  <div class="col body" style="display: none;min-height: 500px;">

    <div class="col-12">
      <div class="form-row">
        
        <div class="col-lg-6">
          <div class="form-row">
            <div class="col-6 col-lg-3 prom_ftth" style="display:none;">
              <div class="card text-center">
                <div class="card-header card_dash">
                  Productividad FTTH (Actividad Promedio)  <span id="prom_ftth"></span>
                </div>
                <div class="card-body">
                  <center><div id="grafico_prom_ftth"></div></center>
                </div>
                <div class="card-footer card_dash">
                </div>
              </div>
            </div>

            <div class="col-6 col-lg-3 prod_hfc_ftth" style="display:none;">
              <div class="card text-center">
                <div class="card-header card_dash">
                  Productividad FTTH+HFC (Puntos  acumulados)  <span id="prod_hfc_ftth"></span>
                </div>
                <div class="card-body">
                  <center><div id="grafico_prod_hfc_ftth"></div></center>
                </div>
                <div class="card-footer card_dash">
                </div>
              </div>
            </div>

            <div class="col-6 col-lg-3 prom_hfc" style="display:none;">
              <div class="card text-center">
                <div class="card-header card_dash">
                  Productividad HFC (Puntos Promedio) <span id="prom_hfc"></span>
                </div>
                <div class="card-body">
                  <center><div id="grafico_prom_hfc"></div></center>
                </div>
                <div class="card-footer card_dash">
                </div>
              </div>
            </div>

            <div class="col-6 col-lg-3 dias_trabajados" style="display:none;">
              <div class="card text-center">
                <div class="card-header card_dash">
                  Días hábiles trabajados <span id="dias_trabajados"></span>
                </div>
                <div class="card-body">
                  <center><div id="grafico_dias_trabajados"></div></center>
                </div>
                <div class="card-footer card_dash">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="form-row">
            <div class="col-6 col-lg-3 calidad_hfc" style="display:none;">
              <div class="card text-center">
                <div class="card-header card_dash">
                 Calidad HFC  <span id="calidad_hfc"></span>
                </div>
                <div class="card-body">
                  <center><div id="grafico_calidad_hfc"></div></center>
                </div>
                <div class="card-footer card_dash">
                </div>
              </div>
            </div>

            <div class="col-6 col-lg-3 calidad_ftth" style="display:none;">
              <div class="card text-center">
                <div class="card-header card_dash">
                  Calidad FTTH <span id="calidad_ftth"></span>
                </div>
                <div class="card-body">
                  <center><div id="grafico_calidad_ftth"></div></center>
                </div>
                <div class="card-footer card_dash">
                </div>
              </div>
            </div>

            <div class="col-6 col-lg-3 declaracion_ot" style="display:none;">
              <div class="card text-center">
                <div class="card-header card_dash">
                 Declaración OT  <span id="declaracion_ot"></span>
                </div>
                <div class="card-body">
                  <center><div id="grafico_declaracion_ot"></div></center>
                </div>
                <div class="card-footer card_dash">
                </div>
              </div>
            </div>

            <div class="col-6 col-lg-3 foto_tecnico" style="display:none;">
              <div class="card text-center">
                <div class="card-body">
                  <center>              
                    <img src="./assets3/imagenes/logo.png" id="foto_tecnico" class="img-thumbnail" height="40px" width="90px">
                  </center>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <hr/>

    <div class="row">
      <div class="col-12 col-lg-6 mt-2">
        <div class="row">
          <div class="col-12 col-lg-4">
             <h6 class="titulo_seccion">Detalle calidad</h6>
          </div>

          <div class="col-8 col-lg-6">  
            <input type="text" placeholder="Busqueda" id="buscador_calidad" class="buscador_calidad form-control form-control-sm">
          </div>

          <div class="col-4 col-lg-2">  
             <button type="button"  class="btn-block btn btn-sm btn-primary excel_calidad btn_xr3">
             <i class="fa fa-save"></i> Excel
             </button>
          </div>
        </div>

        <div class="row">
          <div class="col-12 text-center mt-2">
             <span class="titulo_fecha_actualizacion_dias">
              <div class="alert alert-primary desc_seccion actualizacion_calidad" role="alert" style="padding: .15rem 1.25rem;margin-bottom: .1rem;"></div>
            </span>
          </div>

          <div class="col-lg-12">
            <table id="lista_detalle_calidad" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                <tr>    
                  <th class="centered">Técnico</th> 
                  <th class="centered">RUT</th> 
                  <th class="centered">Comuna</th> 
                  <th class="centered">Orden</th> 
                  <th class="centered">Fecha</th> 
                  <th class="centered">Descripción</th> 
                  <th class="centered">Cierre</th> 
                  <th class="centered">Orden 2da vis.</th> 
                  <th class="centered">Fecha 2da vis.</th> 
                  <th class="centered">Descripción 2da vis.</th> 
                  <th class="centered">Cierre 2da vis.</th> 
                  <th class="centered">Diferencia Días</th> 
                  <th class="centered">Tipo red</th> 
                  <th class="centered">Falla</th> 
                  <th class="centered">Última actualización</th>   
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>   

      <div class="col-12 col-lg-6 mt-2">
        <div class="row">
          <div class="col-12 col-lg-6 graficoHFC">
              <h6 class="titulo_seccion">Calidad HFC Últimos 3 periodos</h6>
              <div id="graficoHFC"></div>
          </div>
          <div class="col-12 col-lg-6 graficoFTTH">
              <h6 class="titulo_seccion">Calidad FTTH Últimos 3 periodos</h6>
              <div id="graficoFTTH"></div>
          </div>
        </div>
      </div>
    </div>

    <hr/>

    <div class="row">
      <div class="col-12 col-lg-6 mt-2">
        <div class="row">
          <div class="col-12 col-lg-4">
             <h6 class="titulo_seccion">Detalle productividad</h6>
          </div>

          <div class="col-8 col-lg-6">  
            <input type="text" placeholder="Busqueda" id="buscador_productividad" class="buscador_productividad form-control form-control-sm">
          </div>

          <div class="col-4 col-lg-2">  
             <button type="button"  class="btn-block btn btn-sm btn-primary excel_productividad btn_xr3">
             <i class="fa fa-save"></i> Excel
             </button>
          </div>
        </div>

        <div class="row">

          <div class="col-12 text-center mt-2">
             <span class="titulo_fecha_actualizacion_dias">
              <div class="alert alert-primary desc_seccion actualizacion_calidad" role="alert" style="padding: .15rem 1.25rem;margin-bottom: .1rem;"></div>
            </span>
          </div>

          <div class="col-lg-12">
            <table id="lista_detalle_productividad" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                <tr>    
                  <th class="centered">Técnico</th> 
                  <th class="centered">Fecha</th> 
                  <th class="centered">Dirección</th> 
                  <th class="centered">Tipo actividad</th> 
                  <th class="centered">Comuna</th> 
                  <th class="centered">Estado</th> 
                  <th class="centered">Derivado</th> 
                  <th class="centered">Puntaje</th> 
                  <th class="centered">Orden de Trabajo</th> 
                  <th class="centered">Digitalizacion OT</th>   
                  <th class="centered">Categoría</th> 
                  <th class="centered">Equivalente</th> 
                  <th class="centered">Técnologia</th> 
                </tr>
              </thead>
            </table>
          </div>


        </div>
      </div>   

      <div class="col-12 col-lg-6 mt-2">
        <div class="row">
          <div class="col-12">
             <h6 class="titulo_seccion">Productividad diario</h6>
            <div id="graficoPuntosProductividadDiario" class="mt-2"></div>
          </div>
        </div>
      </div>

    </div>

    <hr/>

    <div class="row">
      <div class="col-12 mt-1">
        <div class="row">
          <div class="col-12 col-lg-4">
             <h6 class="titulo_seccion">Detalle OTS no detectadas en drive</h6>
          </div>

          <div class="col-8 col-lg-6">  
            <input type="text" placeholder="Busqueda" id="buscador_ots_drive" class="buscador_ots_drive form-control form-control-sm">
          </div>

          <div class="col-4 col-lg-2">  
             <button type="button"  class="btn-block btn btn-sm btn-primary excel_drive btn_xr3">
             <i class="fa fa-save"></i> Excel
             </button>
          </div>
        </div>

        <div class="row">

          <div class="col-12 text-center mt-2">
             <span class="titulo_fecha_actualizacion_dias">
              <div class="alert alert-primary desc_seccion actualizacion_productividad" role="alert" style="padding: .15rem 1.25rem;margin-bottom: .1rem;"></div>
            </span>
          </div>

          <div class="col-lg-12">
            <table id="lista_detalle_ots_drive" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                <tr>    
                  <th class="centered">Técnico</th> 
                  <th class="centered">Fecha</th> 
                  <th class="centered">Dirección</th> 
                  <th class="centered">Tipo actividad</th> 
                  <th class="centered">Comuna</th> 
                  <th class="centered">Estado</th> 
                  <th class="centered">Derivado</th> 
                  <th class="centered">Puntaje</th> 
                  <th class="centered">Orden de Trabajo</th> 
                  <th class="centered">Digitalizacion OT</th>   
                </tr>
              </thead>
            </table>
          </div>

        </div>
      </div>   
    </div>

  </div>   
</div>   