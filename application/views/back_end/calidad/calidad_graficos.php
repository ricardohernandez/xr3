<style type="text/css">
	.titulo_grafico{
    color: #32477C;
    font-size: 16px; 
    font-weight:bold;
    text-align: left;
    padding:10px 2px;
  }

  .tfoot_totales{
     background-color: #32477C;
     color:#fff;
  }
  .tfoot_totales th{
    font-size: 13px!important;
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
            height: 240,
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
                  gridlines: {color:'#ccc', count:10},
							    viewWindowMode:'explicit',
		           		/*viewWindow: {
						        min: 0,
						        max: 30000
						      },*/
								},
		  	  	    1: 
 			  	    	{
         		   	  textStyle:{color: '#32477C',bold:false,fontSize: 11},
 			  	        	gridlines: {color:'transparent', count:10},
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
            height: 240,
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
                  gridlines: {color:'#ccc', count:10},
							    viewWindowMode:'explicit',
		           		/*viewWindow: {
						        min: 0,
						        max: 30000
						      },*/
								},
		  	  	    1: 
 			  	    	{
         		   	  textStyle:{color: '#32477C',bold:false,fontSize: 11},
 			  	        	gridlines: {color:'transparent', count:10},
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
        }
      })
	  }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

  /*****DATATABLE*****/   
    var tabla_resumen_calidad = $('#tabla_resumen_calidad').DataTable({
       "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
       "iDisplayLength":100, 
       "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
       "bPaginate": false,
       /*"aaSorting" : [[0,"asc"]],*/
       "scrollY": "55vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "info":false,
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
            	$("#fecha_g").val(`${desde_actual.substring(0,5)} - ${hasta_actual.substring(0,5)}`);
            }else if(periodo=="anterior"){
            	$("#fecha_g").val(`${desde_anterior.substring(0,5)} - ${hasta_anterior.substring(0,5)}`);
            }else if(periodo=="sub_anterior"){
            	$("#fecha_g").val(`${desde_anterior2.substring(0,5)} - ${hasta_anterior2.substring(0,5)}`);
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
                
              if(i==4){
                v1 = api .column(2).data().reduce( function (a, b) {return intVal(a) + intVal(b); }, 0 );
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

              /*if(i==2 || i==3 || i==5 || i==6 || i==8){   

                var intVal = function ( i ) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
                };

                total = api .column( i )  .data()   .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                }, 0 );


                $( api.column( i ).footer() ).html(numberWithCommas(total));
              }*/

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


		$(document).off('click', '.btn_filtro_calidad').on('click', '.btn_filtro_calidad',function(event) {
	      event.preventDefault();
	       $(".btn_filtro_calidad").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando').prop("disabled" , true);
	       tabla_resumen_calidad.ajax.reload()
	       graficoHFC()
	       graficoFTTH()
		});


		$.getJSON("listaTrabajadoresCalidad", function(data) {
	      response = data;
		}).done(function() {
		    $("#trabajadores").select2({
		     placeholder: 'Seleccione Trabajador | Todos',
		     data: response,
		     width: 'resolve',
	         allowClear:true,
		    });
	  });

    $(document).off('change', '#periodo , #trabajadores ,#jefe , #tipo_red, #proyecto').on('change', '#periodo , #trabajadores ,#jefe , #tipo_red ,#proyecto', function(event) {
      tabla_resumen_calidad.ajax.reload()
      graficoHFC()
      graficoFTTH()
    }); 


</script>

<div class="form-row cont_graficos">
  <div class="col-lg-2">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="margin-left: 5px;margin-top: 2px;"> Periodo <span></span> 
          </div>
            <select id="periodo" name="periodo" class="custom-select custom-select-sm">
              <option value="actual">Actual </option>
              <option selected value="anterior">Anterior</option>
              <option value="sub_anterior">Sub anterior</option>
           </select>
        </div>
      </div>
  </div>

  <div class="col-lg-1">
    <div class="form-group">
        <input type="text" disabled placeholder="" class="fecha_normal form-control form-control-sm"  name="fecha_g" id="fecha_g">
    </div>
  </div>

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

  <div class="col-lg-1">
    <div class="form-group">
      <select id="tipo_red" name="tipo_red" class="custom-select custom-select-sm">
        <option value="" selected>Tipo red | Ambas </option>
        <option value="hfc" >HFC </option>
        <option value="ftth" >FTTH </option>
      </select>
    </div>
  </div>

  <div class="col-12 col-lg-2">  
    <div class="form-group">
        <input type="text" placeholder="Busqueda" id="buscador_detalle_calidad" class="buscador_detalle_calidad form-control form-control-sm">
    </div>
  </div>

  <!-- <div class="col-6 col-lg-1">
      <div class="form-group">
        <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_calidad btn_xr3">
            <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
        </button>
      </div>
  </div> -->

</div>     

<div class="row">
	<div class="col mb-2">
	  <div class="card border-left-primary shadow mb-2">
	    <div class="card-body" style="padding: .4rem;">
	      <div class="row">
					  <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
					  	<table id="tabla_resumen_calidad" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
				        <thead>
				        
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
	            		<h6 class="titulo_grafico">Calidad HFC Últimos 3 periodos</h6>
		    					<div id="graficoHFC"></div>
		          </div>
	            <div class="short-div">
	            		<h6 class="titulo_grafico">Calidad FTTH Últimos 3 periodos</h6>
	    			  	<div id="graficoFTTH"></div>
	            </div>
		        </div>

	      </div>
	    </div>
	  </div>
	</div>
</div>

