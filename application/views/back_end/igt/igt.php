<style type="text/css">
  .actualizacion_calidad,.actualizacion_productividad{
      display: inline-block;
      font-size: 13px;
  }

  div.dataTables_info {
    padding-top: 0.05em!important;
  }

  div.dataTables_paginate {
    margin-top:1px!important;
    margin-bottom:10px!important;
  }

  .titulo{
      display: inline-block;
      font-size: 13px;
  }
  .red2{
    color: #F05252;
  }
  .green{
    color: #0E9F6E;
  }
  
  .s2{
    font-size:1rem!important;
  }
    /* for gauge indicators text */
  .gauge svg g text {
    font-size: 12px;
  }
  /* for middle text */
  .gauge svg g g text {
    font-size: 14px;
    font-weight: bold!important;
    color: red!important;
  }
  /* .card-header{
    font-size: 14px; 
    color:#32477C!important;
    background-color: #E9ECEF!important;
  }

  .card-footer{
    font-size: 14px; 
    color:#32477C!important;
    background-color: #E9ECEF!important;
  } */

  .card_dash{
    /* background-color: #32477C!important;*/
    /* color:#fff!important;*/
    /* border-top: none!important;
     border-bottom: none!important;*/
     border: none!important;
     border-top: none!important;
     border-bottom: none!important
   /*  background-color: #fff!important;*/
     color:#32477C!important;
     padding: 0.25rem 0.75rem!important;
     font-size: 14px;
     font-weight: bold;
  }

  .card_dash.title_section{
   /*  margin-top:10px!important; */
    font-size:0.9rem;
  }

  .card-body{
   /* background-color: #F7F7F7!important;*/
    padding: 0.15rem!important;
  }
  hr {
    margin-top: 1rem!important;
    margin-bottom: 1rem!important;
    border: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
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

 
  .body{
    display: none;
  }
</style>

<script type="text/javascript">
  $(function(){
    const perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    const r="<?php echo $this->session->userdata('rut'); ?>";
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

      if(perfil==4){
        $("#trabajadores").select2().val(r).trigger("change");
      }else{
        $("#trabajadores").select2().val("123053206").trigger("change");
        /*$("#trabajadores").select2().val("8352622K").trigger("change");*/
      }

      /*setTimeout( function () {
          google.charts.setOnLoadCallback(dataGraficosIgt);
      }, 500 ); */
    });

    if(perfil==4){
      dataGraficosIgt()
     
      setTimeout( function () {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
      }, 500 );

    }
   
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

            setTimeout( function () {
              $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
            }, 500 );


          // GRAFICO PROMEDIO FTTH
          
            if(json.hasOwnProperty("data_prom_ftth") && json.data_prom_ftth.meta!="0"){
              
              $("#prom_ftth").text(json.data_prom_ftth.data.puntos).show()
              $(".prom_ftth").show()

              var data_prom_ftth = google.visualization.arrayToDataTable(json.data_prom_ftth.data);
              var options = {
                height: 130,
                min: 0,
                max: json.data_prom_ftth.meta,
                redFrom:1,
                redTo:2,
                yellowFrom:2,
                yellowTo: json.data_prom_ftth.meta,
                greenFrom: json.data_prom_ftth.meta,
                greenTo:json.data_prom_ftth.meta+1,
              };

              var chart = new google.visualization.Gauge(document.getElementById('grafico_prom_ftth'));
              chart.draw(data_prom_ftth, options);

              const value = json.data_prom_ftth.data[1][1]
              const meta = json.data_prom_ftth.meta

              const cumplimiento = json.data_prom_ftth.cumplimiento

              /* $(".meta_prom_ftth").html(` Meta : ${json.data_prom_ftth.meta}`)   */
              $(".meta_prom_ftth_green").html(`${cumplimiento} <i class="fa-solid"></i>%`).show()


             /* const diff = Math.abs(meta-value)

              $(".meta_prom_ftth").html(`Meta : ${json.data_prom_ftth.meta}`)
              if(value>=meta){
                $(".meta_prom_ftth_green").html(`+${diff.toFixed(1)} <i class="fa-solid fa-up-long"></i>`).show()
                $(".meta_prom_ftth_red").html("").hide()
              }else{
                $(".meta_prom_ftth_red").html(`-${diff.toFixed(1)} <i class="fa-solid fa-down-long"></i>`).show()
                $(".meta_prom_ftth_green").html("").hide()
              }*/

              $(".prom_ftth").show()
            }else{

              $(".prom_ftth").hide()
           
              /* $("#grafico_prom_ftth").html("<span class='title_section'><span class='title_section'>No aplica</span></span>")
              $(".meta_prom_ftth_red").html("")
              $(".meta_prom_ftth_green ").html("")*/
            }

          // GRAFICO PROD HFC FTTH
            /*if(json.hasOwnProperty("data_prod_hfc_ftth")){

              $("#prod_hfc_ftth").text(json.data_prod_hfc_ftth.data.puntos).show()
              $(".prod_hfc_ftth").show()

              var data_prod_periodo = google.visualization.arrayToDataTable(json.data_prod_hfc_ftth.data);
              var options = {
                height: 130,
                min: 0,
                max: json.data_prod_hfc_ftth.meta,
                redFrom:2000,
                redTo:5000,
                yellowFrom:5000,
                yellowTo: json.data_prod_hfc_ftth.meta,
                greenFrom: json.data_prod_hfc_ftth.meta,
                greenTo:json.data_prod_hfc_ftth.meta+1000,
              
              };

              var chart = new google.visualization.Gauge(document.getElementById('grafico_prod_hfc_ftth'));
            
              chart.draw(data_prod_periodo, options);
              
              const value = json.data_prod_hfc_ftth.data[1][1]
              const meta = json.data_prod_hfc_ftth.meta
              const diff = Math.abs(meta-value)

              $(".meta_prod_hfc_ftth").html(` Meta : ${json.data_prod_hfc_ftth.meta}`)
              if(value>=meta){
                $(".meta_prod_hfc_ftth_green").html(`+${diff.toFixed(1)} <i class="fa-solid fa-up-long"></i>`).show()
                $(".meta_prod_hfc_ftth_red").html("").hide()
              }else{
                $(".meta_prod_hfc_ftth_red").html(`-${diff.toFixed(1)} <i class="fa-solid fa-down-long"></i>`).show()
                $(".meta_prod_hfc_ftth_green").html("").hide()
              }

              $(".prod_hfc_ftth").show()
            }else{

              $(".prod_hfc_ftth").hide()
              
            }*/
         
          // GRAFICO PROMEDIO PUNTOS HFC

            if(json.hasOwnProperty("data_prom_hfc") && json.data_prom_hfc.meta!="0"){

              $("#prom_hfc").text(json.data_prom_hfc.data.promedio).show()
              $(".prom_hfc").show()

              var data_prom_periodo = google.visualization.arrayToDataTable(json.data_prom_hfc.data);
              var options = {
                height: 130,
                min: 0,
                max: json.data_prom_hfc.meta,
                redFrom:100,
                redTo:150,
                yellowFrom:150,
                yellowTo: json.data_prom_hfc.meta,
                greenFrom: json.data_prom_hfc.meta,
                greenTo:json.data_prom_hfc.meta+50,
                minorTicks: 5,
              };

              const value = json.data_prom_hfc.data[1][1]
              const meta = json.data_prom_hfc.meta
              const cumplimiento = json.data_prom_hfc.cumplimiento

              /* $(".meta_prom_hfc").html(` Meta : ${json.data_prom_hfc.meta}`)   */
              $(".meta_prom_hfc_green").html(`${cumplimiento} <i class="fa-solid"></i>%`).show()

             /* const diff = Math.abs(meta-value)

              $(".meta_prom_hfc").html(` Meta : ${json.data_prom_hfc.meta}`)
              if(value>=meta){
                $(".meta_prom_hfc_green").html(`+${diff.toFixed(1)} <i class="fa-solid fa-up-long"></i>`).show()
                $(".meta_prom_hfc_red").html("").hide()
              }else{
                $(".meta_prom_hfc_red").html(`-${diff.toFixed(1)} <i class="fa-solid fa-down-long"></i>`).show()
                $(".meta_prom_hfc_green").html("").hide()
              }*/

              var chart = new google.visualization.Gauge(document.getElementById('grafico_prom_hfc'));
              chart.draw(data_prom_periodo, options);
              $(".prom_hfc").show()
            }else{
              $(".prom_hfc").hide()
              
             /* $("#grafico_prom_hfc").html("<span class='title_section'><span class='title_section'>No aplica</span></span>")
              $(".meta_prom_hfc_red").html("")
              $(".meta_prom_hfc_green ").html("")*/
            }

          // GRAFICO DIAS TRABAJADOS


            if(json.hasOwnProperty("data_dias_hfc")  && json.data_dias_hfc.data!=false){

              $("#dias_trabajados_hfc").text(json.data_dias_hfc.data).show()
              $(".dias_trabajados_hfc").show()

              var data_dias = google.visualization.arrayToDataTable(json.data_dias_hfc.data);
           
              var options = {
                height: 130,
                min: 0,
                max: json.data_dias_hfc.meta,
                redFrom:15,
                redTo:18,
                yellowFrom:18,
                yellowTo: json.data_dias_hfc.meta,
                greenFrom: json.data_dias_hfc.meta,
                greenTo:json.data_dias_hfc.meta+1,
                minorTicks: 5,
              };

              const value = json.data_dias_hfc.data[1][1]
              const meta = json.data_dias_hfc.meta
              
             /*  $(".meta_dias_trabajados_hfc").html(` Meta : ${json.data_dias_hfc.meta}`)   */
             /* $(".meta_dias_trabajados_hfc_green").html(`${value} <i class="fa-solid "></i>%`).show()*/

              var chart = new google.visualization.Gauge(document.getElementById('grafico_dias_trabajados_hfc'));
              chart.draw(data_dias, options);

              $(".dias_trabajados_hfc").show()
              
            }else{
              $(".dias_trabajados_hfc").hide()
            }


            if(json.hasOwnProperty("data_dias_ftth") && json.data_dias_ftth.data!=false){

              $("#dias_trabajados_ftth").text(json.data_dias_ftth.data.dias).show()
              $(".dias_trabajados_ftth").show()

              var data_dias = google.visualization.arrayToDataTable(json.data_dias_ftth.data);
              var options = {
                height: 130,
                min: 0,
                max: json.data_dias_ftth.meta,
                redFrom:15,
                redTo:18,
                yellowFrom:18,
                yellowTo: json.data_dias_ftth.meta,
                greenFrom: json.data_dias_ftth.meta,
                greenTo:json.data_dias_ftth.meta+1,
                minorTicks: 5,
              };

              const value = json.data_dias_ftth.data[1][1]
              const meta = json.data_dias_ftth.meta

             /*  $(".meta_dias_trabajados_ftth").html(` Meta : ${json.data_dias_ftth.meta}`)   */
             /* $(".meta_prom_hfc_green").html(`${cumplimiento} <i class="fa-solid"></i>%`).show()*/

              /* $(".meta_dias_trabajados_ftth_green").html(`${value} <i class="fa-solid "></i>%`).show()*/

              var chart = new google.visualization.Gauge(document.getElementById('grafico_dias_trabajados_ftth'));
              chart.draw(data_dias, options);

              $(".dias_trabajados_ftth").show()
              
            }else{
              $(".dias_trabajados_ftth").hide()
            }


            if(json.hasOwnProperty("data_asistencia") && json.data_asistencia.data!=false){

              $("#asistencia").text(json.data_dias_hfc.data).show()
              $(".asistencia").show()

              var data_dias = google.visualization.arrayToDataTable(json.data_asistencia.data);
           
              var options = {
                height: 130,
                min: 0,
                max: json.data_asistencia.meta,
                redFrom:15,
                redTo:18,
                yellowFrom:18,
                yellowTo: json.data_asistencia.meta,
                greenFrom: json.data_asistencia.meta,
                greenTo:json.data_asistencia.meta+1,
                minorTicks: 5,
              };

              const value = json.data_asistencia.data[1][1]
              const meta = json.data_asistencia.meta
              
             /*  $(".meta_asistencia").html(` Meta : ${json.data_asistencia.meta}`)   */
              $(".meta_asistencia_green").html(`${value} <i class="fa-solid "></i>%`).show()

              var chart = new google.visualization.Gauge(document.getElementById('grafico_asistencia'));
              chart.draw(data_dias, options);

              $(".asistencia").show()
              
            }else{
              $(".asistencia").hide()
            }



          // GRAFICO CALIDAD HFC 

            if(json.hasOwnProperty("data_calidad_hfc") && json.data_calidad_hfc.meta!="0"){

              $("#calidad_hfc").text(json.data_calidad_hfc.data.calidad).show()
              $(".calidad_hfc").show()

              var calidad_hfc = google.visualization.arrayToDataTable(json.data_calidad_hfc.data);
              var options = {
                height: 130,
                min: 0,
                max: parseFloat(json.data_calidad_hfc.meta)+parseFloat(5),
                redFrom:parseFloat(json.data_calidad_hfc.meta),
                redTo:parseFloat(json.data_calidad_hfc.meta)+parseFloat(5),
                greenFrom: 0,
                greenTo:parseFloat(json.data_calidad_hfc.meta),
                minorTicks: 5,
              };

              const value = json.data_calidad_hfc.data[1][1]
              const meta = json.data_calidad_hfc.meta
              const cumplimiento = json.data_calidad_hfc.cumplimiento


              /* $(".meta_calidad_hfc").html(` Meta : ${json.data_calidad_hfc.meta}`)   */
              $(".meta_calidad_hfc_green").html(`${cumplimiento} <i class="fa-solid"></i>%`).show()

              /*const diff = Math.abs(meta-value)

              $(".meta_calidad_hfc").html(` Meta : ${json.data_calidad_hfc.meta}%`)
            
              if(value<=meta){
                
                $(".meta_calidad_hfc_green").html(`${diff.toFixed(1)} % <i class="fa-solid fa-up-long"></i>`).show()
                $(".meta_calidad_hfc_red").html("").hide()
              }else{
                $(".meta_calidad_hfc_red").html(`${diff.toFixed(1)} % <i class="fa-solid fa-down-long"></i>`).show()
                $(".meta_calidad_hfc_green").html("").hide()
              }*/

              var chart = new google.visualization.Gauge(document.getElementById('grafico_calidad_hfc'));
              chart.draw(calidad_hfc, options);

              $(".calidad_hfc").show()
              
            }else{
              $(".calidad_hfc").hide()
              /*$("#grafico_calidad_hfc").html("<span class='title_section'>No aplica</span>")
              $(".meta_calidad_hfc_green").html("")
              $(".meta_calidad_hfc_red ").html("")*/
            }

          // GRAFICO CALIDAD FTTH 

            if(json.hasOwnProperty("data_calidad_ftth") && json.data_calidad_ftth.meta!="0"){

              $("#calidad_ftth").text(json.data_calidad_ftth.data.calidad).show()
              $(".calidad_ftth").show()

              var calidad_ftth = google.visualization.arrayToDataTable(json.data_calidad_ftth.data);
              var options = {

                height: 130,
                min: 0,
                max: parseFloat(json.data_calidad_ftth.meta)+parseFloat(5),
                redFrom:parseFloat(json.data_calidad_ftth.meta),
                redTo:parseFloat(json.data_calidad_ftth.meta)+parseFloat(5),
                greenFrom: 0,
                greenTo:parseFloat(json.data_calidad_ftth.meta),
                minorTicks: 5,
              };

              const value = json.data_calidad_ftth.data[1][1]
              const meta = json.data_calidad_ftth.meta

              const cumplimiento = json.data_calidad_ftth.cumplimiento

              /* $(".meta_calidad_ftth").html(` Meta : ${json.data_calidad_ftth.meta}`)   */
              $(".meta_calidad_ftth_green").html(`${cumplimiento} <i class="fa-solid"></i>%`).show()


              /*const diff = Math.abs(meta-value)

              $(".meta_calidad_ftth").html(` Meta : ${json.data_calidad_ftth.meta}%`)

              if(value<=meta){
                $(".meta_calidad_ftth_green").html(`+${diff.toFixed(1)} % <i class="fa-solid fa-up-long"></i>`).show()
                $(".meta_calidad_ftth_red").html("").hide()
              }else{
                $(".meta_calidad_ftth_red").html(`-${diff.toFixed(1)} % <i class="fa-solid fa-down-long"></i>`).show()
                $(".meta_calidad_ftth_green").html("").hide()
              }*/

              var chart = new google.visualization.Gauge(document.getElementById('grafico_calidad_ftth'));
              chart.draw(calidad_ftth, options);

              $(".calidad_ftth").show()
      
            }else{
              $(".calidad_ftth").hide()

              /*$("#grafico_calidad_ftth").html("<span class='title_section'>No aplica</span>")
              $(".meta_calidad_ftth_green").html("")
              $(".meta_calidad_ftth_red ").html("")*/
            }

          // GRAFICO  DECLARACION OT 

            if(json.hasOwnProperty("data_declaracion_ot") && json.data_declaracion_ot.data!=false){

              $("#declaracion_ot").text(json.data_declaracion_ot.data.declaracion).show()
              $(".declaracion_ot").show()

              var declaracion_ot = google.visualization.arrayToDataTable(json.data_declaracion_ot.data);
              var options = {
                height: 130,
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

              const value = json.data_declaracion_ot.data[1][1]
              const meta = json.data_declaracion_ot.meta

              $(".meta_declaracion_ot_green").html(`${value}% <i class="fa-solid "></i>`).show()

             /* const diff = Math.abs(meta-value).toFixed(1)

              $(".meta_declaracion_ot").html(` Meta : ${json.data_declaracion_ot.meta}%`)
             
              if(value>=meta){
                $(".meta_declaracion_ot_green").html(`${diff} <i class="fa-solid "></i>`).show()
                $(".meta_declaracion_ot_red").html("").hide()
              }else{
                $(".meta_declaracion_ot_red").html(`-${diff} <i class="fa-solid "></i>`).show()
                $(".meta_declaracion_ot_green").html("").hide()
              }*/

              var chart = new google.visualization.Gauge(document.getElementById('grafico_declaracion_ot'));
              chart.draw(declaracion_ot, options);

              $(".declaracion_ot").show()
              
            }else{
              $(".declaracion_ot").hide()
              /*$("#grafico_declaracion_ot").html("<span class='title_section'>No aplica</span>")
              $(".meta_declaracion_ot_red").html("")
              $(".meta_declaracion_ot_green ").html("")*/
            }


          // AST
 
            if(json.hasOwnProperty("data_ast") && json.data_ast.data!=false){

            $("#ast").text(json.data_ast.data.declaracion).show()
            $(".ast").show()

            var declaracion_ot = google.visualization.arrayToDataTable(json.data_ast.data);
            var options = {
              height: 130,
                min: 0,
                max: json.data_ast.data[1],
              /*    greenFrom: 1,
                greenTo:json.data_ast.data[1], */
                minorTicks: 5,
            };

            const value = json.data_ast.data[1]
            const meta = json.data_ast.meta
            const porcentaje = json.data_ast.porcentaje

            $(".meta_ast_green").html(`${porcentaje} <i class="fa-solid"></i>%`).show()

            var chart = new google.visualization.Gauge(document.getElementById('grafico_ast'));
            chart.draw(declaracion_ot, options);

            $(".ast").show()

            }else{
              $(".ast").hide()
            }
            
          // FOTO


            if(json.hasOwnProperty("foto")){
              $("#foto_tecnico").attr("src", `./fotos_usuarios/${json.foto}`).height(130).width(130);
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
                height: 280,
                is3D:true,
                colors:["#2f81f7","#DC3912"],
                fontName: 'ubuntu',
                bar: {groupWidth: "25%"},
                annotations: {
                     textStyle: {
                      fontSize: 12,
                      color: '#808080',
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

                backgroundColor: 'transparent',
                titleTextStyle: {
                 color: '#808080',
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
                    color:'#808080'
                  }
                }, 

                hAxis: {
                  textStyle:{
                    color: '#808080', 
                    fontSize: 12,
                    bold:false,
                  },
                },
                vAxis: {
                  textStyle:{
                    color: '#808080',
                    bold:false,
                    fontSize: 12
                  },
                },

                tooltip:{textStyle:{fontSize:12},isHtml: true},

                vAxes: {
                  0: 
                    {
                    textStyle:{color: '#808080',bold:false,fontSize: 12},
                      gridlines: {color:'#808080', count:0},
                      viewWindowMode:'explicit',
                  
                    },
                    1: 
                    {
                      textStyle:{color: '#808080',bold:false,fontSize: 12},
                        gridlines: {color:'transparent', count:0},
                     
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
                    color: '#1A56DB',
                    targetAxisIndex:1,
                    annotations: {
                    style: 'line',
                    textStyle: {
                      fontSize: 12,
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
                      fontSize: 12,
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
              $(".graficoHFC").show()
              $("#graficoHFC").html("<span class='title_section'>No aplica</span>").show()
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
                colors:["#1A56DB","#DC3912"],
                fontName: 'ubuntu',
                bar: {groupWidth: "25%"},
                annotations: {
                     textStyle: {
                      fontSize: 12,
                      color: '#808080',
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

                backgroundColor: 'transparent',
                titleTextStyle: {
                 color: '#808080',
                 fontSize: 13, 
                 fontWidth: 'normal',
                 bold:false
                },

                legend: {
                 'position':'top',
                 'alignment':'center',
                  textStyle: {
                    fontSize: 12,
                    bold:false,
                    color:'#808080'
                  }
                }, 

                hAxis: {
                  textStyle:{
                    color: '#808080', 
                    fontSize: 12,
                    bold:false,
                  },
                },
                vAxis: {
                  textStyle:{
                    color: '#808080',
                    bold:false,
                    fontSize: 12
                  },
                },

                tooltip:{textStyle:{fontSize:12},isHtml: true},

                vAxes: {
                  0: 
                    {
                    textStyle:{color: '#808080',bold:false,fontSize: 12},
                      gridlines: {color:'#808080', count:0},
                      viewWindowMode:'explicit',
               
                    },
                    1: 
                    {
                      textStyle:{color: '#808080',bold:false,fontSize: 12},
                        gridlines: {color:'transparent', count:0},
                      
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
                    color: '#1A56DB',
                    targetAxisIndex:1,
                    annotations: {
                    style: 'line',
                    textStyle: {
                      fontSize: 12,
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
                      fontSize: 12,
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
              $(".graficoFTTH").show()
              $("#graficoFTTH").html("<span class='title_section'>No aplica</span>").show()
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
                height: 310,
                is3D:true,
                colors:["#1A56DB"],
                fontName: 'ubuntu',
                bar: {groupWidth: "50%"},

                annotations: {
                  textStyle: {
                    fontSize: 12,
                    color: '#808080',
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

                backgroundColor: 'transparent',

                titleTextStyle: {
                 color: '#808080',
                 fontSize: 12, 
                 fontWidth: 'normal',
                 bold:false
                },

                legend: {
                 'position':'right',
                }, 

                hAxis: {
                  direction: -1, 
                  slantedText: false, 
                  slantedTextAngle: 90,

                  textStyle:{
                    color: '#808080', 
                    fontSize: 12,
                    bold:false,
                  },

                  gridlines: {
                   count:0
                  },

                },

                vAxis: {
                  textStyle:{
                    color: '#808080',
                    bold:false,
                    fontSize: 12
                  },
                  gridlines: {
                   count:0,
                   color:"#808080"
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
         "aaSorting" : [[4,"desc"]],
         "responsive":false,
         "scrollY": "162",
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

              var desde_actual="<?php echo $desde_actual_calidad; ?>"
              var hasta_actual="<?php echo $hasta_actual_calidad; ?>"
              var desde_anterior="<?php echo $desde_anterior_calidad; ?>"
              var hasta_anterior="<?php echo $hasta_anterior_calidad; ?>"

              var desde_actual_relojes ="<?php echo $desde_actual_relojes; ?>"
              var hasta_actual_relojes ="<?php echo $hasta_actual_relojes; ?>"
              var desde_anterior_relojes ="<?php echo $desde_anterior_relojes; ?>"
              var hasta_anterior_relojes ="<?php echo $hasta_anterior_relojes; ?>"


              var periodo =$("#periodo_detalle").val()

              if(periodo=="actual"){
                $("#fecha_f").val(`${desde_actual_relojes.substring(0,5)} - ${hasta_actual_relojes.substring(0,5)}` );
              }else if(periodo=="anterior"){
                $("#fecha_f").val(`${desde_anterior_relojes.substring(0,5)} - ${hasta_anterior_relojes.substring(0,5)}`);
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
       "aaSorting" : [[1,"desc"]],
       "responsive":false,
       "scrollY": "200",
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

            /*if(periodo=="actual"){
              $("#fecha_f").val(`${desde_actual.substring(0,5)} - ${hasta_actual.substring(0,5)}`);
            }else if(periodo=="anterior"){
              $("#fecha_f").val(`${desde_actual.substring(0,5)} - ${hasta_actual.substring(0,5)}`);
            }*/

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
        "responsive":false,
       "aaSorting" : [[1,"desc"]],
       "scrollY": "168px",
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

           /* if(periodo=="actual"){
              $("#fecha_f").val(`${desde_actual.substring(0,5)} - ${hasta_actual.substring(0,5)}`);
            }else if(periodo=="anterior"){
              $("#fecha_f").val(`${desde_actual.substring(0,5)} - ${hasta_actual.substring(0,5)}`);
            }*/

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


      $(document).off('change', '.file_cs').on('change', '.file_cs',function(event) {
        var myFormData = new FormData();
        myFormData.append('userfile', $('#userfile').prop('files')[0]);
        $.ajax({
            url: "formCargaMasivaIgt"+"?"+$.now(),  
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

      actualizacionProductividad()

      function actualizacionProductividad(){
        $.ajax({
            url: "actualizacionProductividad"+"?"+$.now(),  
            type: 'POST',
            cache: false,
            tryCount : 0,
            retryLimit : 3,
            dataType:"json",
            beforeSend:function(){
            },
            success: function (data) {
              if(data.res=="ok"){
                $(".actualizacion_productividad").html("<b>Última actualización planilla : "+data.datos+"</b>");
              }
            },
            error : function(xhr, textStatus, errorThrown ) {
              if (textStatus == 'timeout') {
                  this.tryCount++;
                  if (this.tryCount <= this.retryLimit) {
                      $.notify("Reintentando...", {
                        className:'info',
                        globalPosition: 'top right'
                      });
                      $.ajax(this);
                      return;
                  } else{
                     $.notify("Problemas en el servidor, intente nuevamente.", {
                        className:'warn',
                        globalPosition: 'top right'
                      });     
                  }    
                  return;
              }

              if (xhr.status == 500) {
                  $.notify("Problemas en el servidor, intente más tarde.", {
                    className:'warn',
                    globalPosition: 'top right'
                  });
              }
            },timeout:5000
        }); 
      }



  })  
</script>

<div class="content" style="padding: 0px 10px;">

  <div class="form-row">

    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page" ><a href=""">IGT - Indicadores de gestión del técnico</a></li>
        </ol>
      </nav>
    </div>

    <?php
        if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
        ?>
        <div class=" col-xs-6 col-sm-6  col-md-6  col-lg-2 d-none d-sm-block">  
          <div class="form-group">
            <input type="file" id="userfile" name="userfile" class="file_cs" style="display:none;" />
            <button type="button"  class="btn-block btn btn-sm btn-primary btn_file_cs btn_xr3" onclick="document.getElementById('userfile').click();">
            <i class="fa fa-file-import"></i> Cargar base IGT
            </div>
        </div>
        <!-- <i class="fa-solid fa-circle-info ejemplo_planilla" title="Ver ejemplo" ></i> -->
        <?php
      }
    ?>


    <div class="col-8 col-lg-2">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="margin-left: 5px;margin-top: 2px;font-size: 1rem!important"> Periodo </span> </span> 
          </div>
          <select id="periodo_detalle" name="periodo" class="custom-select custom-select-sm" style="font-size: 1rem!important;">
            <option value="actual" selected><?php echo $mes_actual ?></option>
            <option value="anterior"><?php echo $mes_anterior ?></option>
          </select>
        </div>
      </div>
    </div>

    <div class="col-4 col-lg-2">
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
        <div class="col-12 col-lg-3">  
          <div class="form-group fooSelect">
            <select id="trabajadores" name="trabajadores"  style="width:100%!important;">
              <!--   <option value="">Seleccione Trabajador | Todos</option> -->
            </select>
          </div>
        </div>
        <?php
    }else{
      ?>
      <div class="col-12 col-lg-3">  
          <div class="form-group">
            <select id="trabajador" name="trabajador" class="custom-select custom-select-sm" style="font-size: 1rem!important;">
                <option selected value="<?php echo $this->session->userdata('rut'); ?>"><?php echo $this->session->userdata('nombre_completo'); ?></option>
            </select>
          </div>
        </div>
      <?php
    }
    ?>

  </div>       

<center><i id='load' class='fa-solid fa-circle-notch fa-spin fa-8x text-center'  style='margin-top:170px;color:#32477C;opacity: .8;margin-bottom: 800px;'></i></center>
  <!-- body style="display: none;" -->
  <div class="body">
    <div class="form-row">
      
      <div class="col-6 col-lg prom_ftth mb-2">
        <div class="card text-center">
          <div class="card-header card_dash">
            Prod. FTTH <span class="meta_prom_ftth"></span>
          </div>
          <div class="card-body">
            <center><div id="grafico_prom_ftth" class="gauge"></div></center>
          </div>
          <div class="card-footer card_dash">
            <span class="meta_prom_ftth_green green" style="display: none;"></span> 
            <span class="meta_prom_ftth_red red2" style="display: none;"> </span> 
          </div>
        </div>
      </div>

      <div class="col-6 col-lg calidad_ftth mb-2">
        <div class="card text-center">
          <div class="card-header card_dash">
            Calidad FTTH <span class="meta_calidad_ftth"></span>
          </div>
          <div class="card-body">
            <center><div id="grafico_calidad_ftth" class="gauge"></div></center>
          </div>
          <div class="card-footer card_dash">
            <span class="meta_calidad_ftth_green green" style="display: none;"></span> 
            <span class="meta_calidad_ftth_red red2" style="display: none;"> </span> 
          </div>
        </div>
      </div>

      <!-- <div class="col-6 col-lg prod_hfc_ftth">
        <div class="card text-center">
          <div class="card-header card_dash">
            Prod. FTTH+HFC  <span class="meta_prod_hfc_ftth"></span>
          </div>
          <div class="card-body">
            <center><div id="grafico_prod_hfc_ftth" class="gauge"></div></center>
          </div>
          <div class="card-footer card_dash">
           <span class="meta_prod_hfc_ftth_green green" style="display: none;"></span> 
           <span class="meta_prod_hfc_ftth_red red2" style="display: none;"> </span> 
          </div>
        </div>
      </div> -->

      <div class="col-6 col-lg prom_hfc mb-2">
        <div class="card text-center">
          <div class="card-header card_dash">
            Prod. HFC <span class="meta_prom_hfc"></span>
          </div>
          <div class="card-body">
            <center><div id="grafico_prom_hfc" class="gauge"></div></center>
          </div>
          <div class="card-footer card_dash">
            <span class="meta_prom_hfc_green " style="display: none;"></span> <!-- green -->
            <span class="meta_prom_hfc_red " style="display: none;"> </span><!--  red2 -->
          </div>
        </div>
      </div>

      <div class="col-6 col-lg calidad_hfc mb-2">
        <div class="card text-center">
          <div class="card-header card_dash">
           Calidad HFC  <span class="meta_calidad_hfc"></span>
          </div>
          <div class="card-body">
            <center><div id="grafico_calidad_hfc" class="gauge"></div></center>
          </div>
          <div class="card-footer card_dash">
            <span class="meta_calidad_hfc_green " style="display: none;"></span> 
            <span class="meta_calidad_hfc_red red2" style="display: none;"> </span> 
          </div>
        </div>
      </div>

      <div class="col-6 col-lg dias_trabajados_ftth mb-2">
        <div class="card text-center">
          <div class="card-header card_dash">
            Días háb. trabajados FTTH <span class="meta_dias_trabajados_ftth"></span>
          </div>
          <div class="card-body">
            <center><div id="grafico_dias_trabajados_ftth" class="gauge"></div></center>
          </div>
          <div class="card-footer card_dash">
            <span class="meta_dias_trabajados_ftth_green " style="display: none;"></span> 
            <span class="meta_dias_trabajados_red red2" style="display: none;"> </span> 
          </div>
        </div>
      </div>


       <div class="col-6 col-lg dias_trabajados_hfc mb-2">
        <div class="card text-center">
          <div class="card-header card_dash">
            Días háb. trabajados HFC<span class="meta_dias_trabajados_hfc"></span>
          </div>
          <div class="card-body">
            <center><div id="grafico_dias_trabajados_hfc" class="gauge"></div></center>
          </div>
          <div class="card-footer card_dash">
            <span class="meta_dias_trabajados_hfc_green" style="display: none;"></span> 
            <span class="meta_dias_trabajados_red red2" style="display: none;"> </span> 
          </div>
        </div>
      </div>

      <div class="col-6 col-lg asistencia mb-2">
        <div class="card text-center">
          <div class="card-header card_dash">
            % Asistencia <span class="meta_asistencia"></span>
          </div>
          <div class="card-body">
            <center><div id="grafico_asistencia" class="gauge"></div></center>
          </div>
          <div class="card-footer card_dash">
            <span class="meta_asistencia_green " style="display: none;"></span> 
            <span class="meta_asistencia_red red2" style="display: none;"> </span> 
          </div>
        </div>
      </div>


      <div class="col-6 col-lg declaracion_ot mb-2">
        <div class="card text-center">
          <div class="card-header card_dash">
           Declaración OT  <span class="meta_declaracion_ot"></span>
          </div>
          <div class="card-body">
            <center><div id="grafico_declaracion_ot" class="gauge"></div></center>
          </div>
          <div class="card-footer card_dash">
            <span class="meta_declaracion_ot_green " style="display: none;"></span> 
            <span class="meta_declaracion_ot_red red2" style="display: none;"> </span> 
          </div>
        </div>
      </div>

      <div class="col-6 col-lg ast mb-2">
        <div class="card text-center">
          <div class="card-header card_dash">
           AST Realizados  <span class="meta_ast"></span>
          </div>
          <div class="card-body">
            <center><div id="grafico_ast" class="gauge"></div></center>
          </div>
          <div class="card-footer card_dash">
            <span class="meta_ast_green"></span> 
            <span class="meta_ast_red red2" style="display: none;"> </span> 
          </div>
        </div>
      </div>

      <!-- <div class="col-6 col-lg foto_tecnico" style="display:none;">
        <div class="card text-center">
          <div class="card-body">
            <center>              
              <img src="./assets3/imagenes/logo.png" id="foto_tecnico" class="img-thumbnail" height="40px" width="90px">
            </center>
          </div>
        </div>
      </div> -->
    </div>
 

    <div class="row p-1">
      <div class="col-12 col-lg-6">
        <div class="card">
          <div class="card-header card_dash">
            <div class="form-row">
              <div class="col-12 col-lg-4">
                 <span class="title_section">Detalle calidad</span>
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
          </div>

          <div class="form-row">
            <div class="col-12 text-center d-none d-sm-block">
               <span class="titulo_fecha_actualizacion_dias">
                <div class="alert alert-primary  actualizacion_calidad" role="alert" style="padding: .15rem 1.25rem;margin-bottom: .1rem;"></div>
              </span>
            </div>

            <div class="col-lg-12 px-3">
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
      </div>   

      <div class="col-12 col-lg-6">
        <div class="card">
          <div class="form-row">
            
            <div class="col-lg-6 graficoHFC">
              <div class="card-header card_dash">
                <span class="title_section">Calidad HFC Últimos 6 periodos</span>
              </div>
              <div id="graficoHFC"></div>
            </div>

            <div class="col-lg-6 graficoFTTH">
              <div class="card-header card_dash">
                <span class="title_section">Calidad FTTH Últimos 6 periodos</span>
              </div>
              <div id="graficoFTTH"></div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="row p-1">

      <div class="col-12 col-lg-6">
        <div class="card">
          <div class="card-header card_dash">
            <div class="form-row">
              <div class="col-12 col-lg-4">
                 <span class="title_section">Detalle productividad</span>
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
          </div>

          <div class="form-row">
            <div class="col-12 text-center d-none d-sm-block">
               <span class="titulo_fecha_actualizacion_dias">
                <div class="alert alert-primary  actualizacion_productividad" role="alert" style="padding: .15rem 1.25rem;margin-bottom: .1rem;"></div>
              </span>
            </div>

            <div class="col-lg-12 px-3">
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
      </div>   
      
      <div class="col-12 col-lg-6">
        <div class="card">
          <div class="form-row">
            <div class="col-12">
              <div class="card-header card_dash">
               <span class="title_section">Productividad diario</span>
              </div>
              <div id="graficoPuntosProductividadDiario" class="mt-2"></div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="row p-1">
      <div class="col-12 col-lg-12">
        <div class="card">
          <div class="card-header card_dash">
            <div class="form-row">
              <div class="col-12 col-lg-4">
                 <span class="title_section">Detalle OTS no detectadas en drive</span>
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
          </div>

          <div class="form-row">

            <div class="col-12 text-center d-none d-sm-block">
               <span class="titulo_fecha_actualizacion_dias">
                <div class="alert alert-primary  actualizacion_productividad" role="alert" style="padding: .15rem 1.25rem;margin-bottom: .1rem;"></div>
              </span>
            </div>

            <div class="col-lg-12 px-3">
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