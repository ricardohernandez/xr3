<style type="text/css">
	.titulo_grafico{
    color: #32477C;
    font-size: 16px; 
    font-weight:bold;
    text-align: left;
    padding:10px 2px;
  }
  .tfoot_totales{
     /* background-color: #1A56DB; */
     color:#808080;
  }
  .tfoot_totales th{
    font-size: 13px!important;
  }
  .actualizacion_calidad{
      display: inline-block;
      font-size: 11px;
  }
</style>
<script type="text/javascript">
  var base_url = "<?php echo base_url() ?>"
	var desde_actual="<?php echo $desde_actual; ?>"
  var hasta_actual="<?php echo $hasta_actual; ?>"
  var desde_anterior="<?php echo $desde_anterior; ?>"
  var hasta_anterior="<?php echo $hasta_anterior; ?>"
  var periodo =$("#periodo").val()
  var perfil="<?php echo $this->session->userdata('id_perfil'); ?>";

  /*****GRAFICO HFC**********/

		google.charts.setOnLoadCallback(graficoHFC);

		function graficoHFC(){
			var periodo = $("#periodo").val();
			var jefe = $("#jefe").val();
      var proyecto = $("#proyecto").val();
      var trabajador = perfil <= 3 ? $("#trabajadores").val() : $("#trabajador").val();

			$.ajax({
        url: base_url+"graficoHFC"+"?"+$.now(),  
        type: 'POST',
        data:{periodo:periodo,trabajador:trabajador,jefe:jefe,proyecto:proyecto},
        dataType:"json",
        success: function (json) {
					var graficoHFC = google.visualization.arrayToDataTable(json);
       		graficoHFC.sort([{column: 4, desc: false}])

       		var options = {
		        isStacked: true,
            width: "100%",
            height: 260,
            is3D:true,
            colors:["#2f81f7","#DC3912"],
            fontName: 'ubuntu',
            bar: {groupWidth: "25%"},
            backgroundColor: { fill:'transparent' },
            annotations: {
                 textStyle: {
                  fontSize: 10,
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
		           		/*viewWindow: {
						        min: 0,
						        max: 30000
						      },*/
								},
		  	  	    1: 
 			  	    	{
         		   	  textStyle:{color: '#808080',bold:false,fontSize: 12},
 			  	        	gridlines: {color:'#808080', count:0},
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
        }
      })
	  }

	/*****GRAFICO FTTH*****/

		google.charts.setOnLoadCallback(graficoFTTH);

		function graficoFTTH(){
			var periodo = $("#periodo").val();
      var jefe = $("#jefe").val();
      var proyecto = $("#proyecto").val();
      var trabajador = perfil <= 3 ? $("#trabajadores").val() : $("#trabajador").val();

			$.ajax({
        url: base_url+"graficoFTTH"+"?"+$.now(),  
        type: 'POST',
        data:{periodo:periodo,trabajador:trabajador,jefe:jefe,proyecto:proyecto},
        dataType:"json",
        success: function (json) {
					var graficoFTTH = google.visualization.arrayToDataTable(json);
       		graficoFTTH.sort([{column: 4, desc: false}])

        	var options = {
		        isStacked: true,
            width: "100%",
            height: 260,
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

            backgroundColor: { fill:'transparent' },
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
		           		/*viewWindow: {
						        min: 0,
						        max: 30000
						      },*/
								},
		  	  	    1: 
 			  	    	{
         		   	  textStyle:{color: '#808080',bold:false,fontSize: 12},
 			  	        	gridlines: {color:'#808080', count:0},
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
        }
      })
	  }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

  /*****DATATABLE*****/   
    var tabla_resumen_calidad = $('#tabla_resumen_calidad').DataTable({
       /*"sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',*/
       "iDisplayLength":200, 
       "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
       "bPaginate": false,
       /*"aaSorting" : [[0,"asc"]],*/
       "scrollY": "55vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "info":true,
       /*"columnDefs": [{ orderable: false, targets: 0 }  ],*/
       "ajax": {
          "url":"<?php echo base_url();?>listaResumenCalidad",
          "dataSrc": function (json) {
            $(".btn_filtro_calidad").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_calidad").prop("disabled" , false);
            var desde_actual="<?php echo $desde_actual; ?>"
            var hasta_actual="<?php echo $hasta_actual; ?>"
            var desde_anterior="<?php echo $desde_anterior; ?>"
            var hasta_anterior="<?php echo $hasta_anterior; ?>"
            var desde_anterior2="<?php echo $desde_anterior2; ?>"
            var hasta_anterior2="<?php echo $hasta_anterior2; ?>"
            var periodo =$("#periodo").val()

            if(periodo=="actual"){
            	$("#fecha_g").val(`${desde_actual.substring(5,10)} - ${hasta_actual.substring(5,10)}`);
            }else if(periodo=="anterior"){
            	$("#fecha_g").val(`${desde_anterior.substring(5,10)} - ${hasta_anterior.substring(5,10)}`);
            }else if(periodo=="anterior_2"){
            	$("#fecha_g").val(`${desde_anterior2.substring(5,10)} - ${hasta_anterior2.substring(5,10)}`);
            }

            /*console.log(json)*/
            return json;
          },       
          data: function(param){
           
            param.periodo = $("#periodo").val();
            param.jefe = $("#jefe").val();
            param.tipo_red = $("#tipo_red").val();
            param.proyecto = $("#proyecto").val();

            if(perfil==4){
              param.trabajador = $("#trabajador").val();
            }else{
              param.trabajador = $("#trabajadores").val();
            }
          }
        },    

        "footerCallback": function ( row, data, start, end, display ) {
          var api = this.api(), data;
          var largo = api.columns(':visible').count();
           for (var i = 1; i <= (largo); i++) {


              if(i==2 || i==3 || i==5 || i==6/* || i==8*/){   
                var intVal = function ( i ) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
                };

                total = api .column( i )  .data()   .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                }, 0 );

                $( api.column( i ).footer() ).html(numberWithCommas(total));
              }
                
              /*if(i==4 || i==7){

                var numRows = api
                .column( i )
                .data()
                .filter( function ( value, index ) {
                    return value != null ? true : false;
                }).count();

                var intVal = function ( i ) {
                 
                      return typeof i === 'string' ? i.replace(/[\$,%]/g, '')*1 : typeof i === 'number' ? i : 0;
                };

                total = api .column( i )  .data()   .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                }, 0 );

                avg = total / numRows;
                avg = avg.toFixed(2)
                $( api.column( i ).footer() ).html(avg+"%")
              }*/

              if(i==4){
                v1 = api .column(2).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                v2 = api .column(3).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                avg = (v2 / v1)*100;
                if(typeof avg === 'number'){
                  $( api.column(4).footer() ).html(avg.toFixed(2)+"%")
                }else{
                  $( api.column(4).footer() ).html("-")
                }
              }

              if(i==7){
                v1 = api .column(5).data().reduce( function (a, b) {return intVal(a) + intVal(b); }, 0 );
                v2 = api .column(6).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                avg = (v2 / v1)*100;
                $( api.column(7).footer() ).html(avg.toFixed(2)+"%")
              }

          }
        },
     
       "columns": [
          { "data": "trabajador" , "width" : "20%" , "class":"margen-td centered"},
          { "data": "periodo" , "width" : "5%" , "class":"margen-td centered"},
          { "data": "q_HFC" ,"class":"margen-td centered"},
          { "data": "fallos_HFC" ,"class":"margen-td centered"},
          { "data": "calidad_HFC" ,"class":"margen-td centered"},
          { "data": "q_FTTH" ,"class":"margen-td centered"},
          { "data": "fallos_FTTH" ,"class":"margen-td centered"},
          { "data": "calidad_FTTH" ,"class":"margen-td centered"},
          /*{ "data": "productividad" ,"class":"margen-td centered"},*/
       
        ]
      }); 

   	$(document).on('keyup paste', '#buscador_detalle_calidad', function() {
      tabla_resumen_calidad.search($(this).val().trim()).draw();
    });

    $.getJSON(base_url + "listaTrabajadoresCalidad" , function(data) {
	      response = data;
		}).done(function() {
		    $("#trabajadores").select2({
		     placeholder: 'Seleccione Trabajador | Todos',
		     data: response,
		     width: 'resolve',
	         allowClear:true,
		    });
	  });

    $(document).off('change', '#periodo').on('change', '#periodo', function(event) {
      tabla_resumen_calidad.ajax.reload()
      graficoHFC()
      graficoFTTH()
    }); 

    $(document).off('change', '#jefe').on('change', '#jefe', function(event) {
      tabla_resumen_calidad.ajax.reload()
      graficoHFC()
      graficoFTTH()
    }); 

    $(document).off('change', '#jefe').on('change', '#jefe', function(event) {
      tabla_resumen_calidad.ajax.reload()
      graficoHFC()
      graficoFTTH()
    }); 

    $(document).off('change', '#trabajadores').on('change', '#trabajadores', function(event) {
      tabla_resumen_calidad.ajax.reload()
      graficoHFC()
      graficoFTTH()
    }); 

    $(document).off('change', '#proyecto').on('change', '#proyecto', function(event) {
      tabla_resumen_calidad.ajax.reload()
      graficoHFC()
      graficoFTTH()
    }); 

    $(document).off('change', '#tipo_red').on('change', '#tipo_red', function(event) {
      tabla_resumen_calidad.ajax.reload()
      graficoHFC()
      graficoFTTH()
    }); 

    actualizacionCalidad()

    function actualizacionCalidad(){
      $.ajax({
          url: "actualizacionCalidad"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          dataType:"json",
          beforeSend:function(){
          },
          success: function (data) {
            if(data.res=="ok"){
              $(".actualizacion_calidad").html("<b>Última actualización planilla : "+data.datos+"</b>");
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

</script>

<div class="form-row cont_graficos">
  <div class="col-lg-2">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="margin-left: 10px;font-size:13px!important;"> Periodo <span></span> 
          </div>
            <select id="periodo" name="periodo" class="custom-select custom-select-sm">
              <option selected value="actual"><?php echo $mes_actual ?></option>
              <option value="anterior"><?php echo $mes_anterior?></option>
              <option value="anterior_2"><?php echo $mes_anterior2 ?></option>
            <!--   <option value="anterior_3"><?php echo $mes_anterior3 ?></option>
              <option value="anterior_4"><?php echo $mes_anterior4 ?></option>
              <option value="anterior_5"><?php echo $mes_anterior5 ?></option> -->
           </select>
        </div>
      </div>
  </div>

  <!-- <div class="col-lg-1">
    <div class="form-group">
        <input type="text" disabled placeholder="" class="fecha_normal form-control form-control-sm"  name="fecha_g" id="fecha_g">
    </div>
  </div> -->

	<?php  
    if($this->session->userdata('id_perfil')<3){
  ?>

  <div class="col-lg-2">
    <div class="form-group">
      <select id="jefe" name="jefe" class="custom-select custom-select-sm">
        <option value="" selected>Seleccione Jefe | Todos </option>
        <?php  
        	foreach($jefes as $j){
        		?>
        			<option value="<?php echo $j["id_jefe"]?>" ><?php echo $j["nombre_jefe"]?> </option>
        		<?php
        	}
        ?>
      </select>
    </div>
  </div>

   <?php
    }elseif($this->session->userdata('id_perfil')==3){
      ?>
      <div class="col-lg-2">
        <div class="form-group">
          <select id="jefe" name="jefe" class="custom-select custom-select-sm">
            <?php  
              foreach($jefes as $j){
                ?>
                  <option selected value="<?php echo $j["id_jefe"]?>" ><?php echo $j["nombre_jefe"]?> </option>
                <?php
              }
            ?>
          </select>
        </div>
      </div>
      <?php
    }
  ?>

  <?php  
    if($this->session->userdata('id_perfil')<=3){
  ?>

    <div class="col-lg-2">  
      <div class="form-group">
        <select id="trabajadores" name="trabajadores" style="width:100%!important;">
            <option value="">Seleccione Trabajador | Todos</option>
        </select>
      </div>
    </div>

  <?php
    }else{
  ?>

    <div class="col-lg-2">  
      <div class="form-group">
        <select id="trabajador" name="trabajador" class="custom-select custom-select-sm" >
            <option selected value="<?php echo $this->session->userdata('rut'); ?>"><?php echo $this->session->userdata('nombre_completo'); ?></option>
        </select>
      </div>
    </div>

  <?php
    }
  ?>

  <div class="col-lg-2">
    <div class="form-group">
      <select id="proyecto" name="proyecto" class="custom-select custom-select-sm">
        <option value="" selected>Seleccione proyecto | Todos </option>
        <?php  
          foreach($proyectos as $p){
            ?>
              <option  value="<?php echo $p["id"]?>" ><?php echo $p["proyecto"]?> </option>
            <?php
          }
        ?>
      </select>
    </div>
  </div>

  <div class="col-lg-2">
    <div class="form-group">
      <select id="tipo_red" name="tipo_red" class="custom-select custom-select-sm">
        <option value="" selected>Segmento técnico </option>
        <option value="hfc" >HFC </option>
        <option value="ftth" >FTTH </option>
      </select>
    </div>
  </div>

  <!-- <div class="col-12 col-lg-2">  
    <div class="form-group">
        <input type="text" placeholder="Busqueda" id="buscador_detalle_calidad" class="buscador_detalle_calidad form-control form-control-sm">
    </div>
  </div> -->

  <!-- <div class="col-6 col-lg-1">
      <div class="form-group">
        <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_calidad btn_xr3">
            <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
        </button>
      </div>
  </div> -->

</div>     

<div class="row">
	<div class="col">
	  <div class="card  shadow">
	    <div class="card-body" style="padding: .4rem;">
	      <div class="row">
      	  <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">

           <div class="col-lg-12">
              <center><span class="titulo_fecha_actualizacion_dias">
              <div class="alert alert-primary actualizacion_calidad" role="alert" style="padding: .15rem 1.25rem;margin-bottom: .1rem;">
              </div>
           </div>

				  	<table id="tabla_resumen_calidad" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
			        <thead>
			          
                <tr>    
                  <th colspan="2" class="centered"></th> 
                  <th colspan="3" class="centered"><center>HFC</center></th> 
                  <th colspan="3" class="centered"><center>FTTH</center></th> 
                </tr>

			          <tr>    
			            <th class="centered">Técnico</th> 
			            <th class="centered">Periodo</th> 
			            <th class="centered">Ordenes</th> 
			            <th class="centered">Fallos </th> 
			            <th class="centered">Calidad </th> 
			            <th class="centered">Ordenes</th> 
			            <th class="centered">Fallos</th> 
			            <th class="centered">Calidad </th> 
			           <!--  <th class="centered">Puntos</th>  -->
			          </tr>
			        </thead>

              <tfoot class="tfoot_totales">
                <tr>
                    <th class="">Totales</th>
                    <th class=""></th>
                    <th class=""></th>
                    <th class=""></th>
                    <th class=""></th>
                    <th class=""></th>
                    <th class=""></th>
                    <th class=""></th>
                   <!--  <th class=""></th> -->
                </tr>
              </tfoot>

	          </table>
			  	</div>

	        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="short-div">
            		<p class="section_titulo">Calidad HFC Últimos 6 periodos</p>
	    					<div id="graficoHFC"></div>
	          </div>
            <div class="short-div">
            		<p class="section_titulo">Calidad FTTH Últimos 6 periodos</p>
    			  	<div id="graficoFTTH"></div>
            </div>
	        </div>

	      </div>
	    </div>
	  </div>
	</div>
</div>

