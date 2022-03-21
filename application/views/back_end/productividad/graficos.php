<style type="text/css">
	.titulo_grafico{
	  color: #32477C;
      font-size: 16px; 
      font-weight:bold;
      text-align: left;
      padding:10px 2px;
    }
     #rankingTecnicos ,#puntosTipoOrden {
       max-height: 400px;
	   overflow-x: hidden;    
	   overflow-y: scroll;    
	}
	.alert {
	    position: relative;
	    padding: 0.2rem 2.25rem!important; 
	    margin-bottom: 0rem!important; 
	    border: 1px solid transparent;
	    border-radius: 0.25rem;
	}

	.alert-primary2 {
	    color: #fff;
	    background-color: #32477C;
	    border-color: #b8daff;
	}

	.alert-heading{
		font-size: 1rem!important;
		margin-bottom: 0.1rem!important;
	}

	.select2-container .select2-selection--single {
	    height: 32px!important;
	}

	.border-left-primary {
	    border-left: 0.25rem solid #32477C!important;
	}
	.puntos_cont{
		text-align: center;
	}

</style>
<script type="text/javascript">
	var base_url = "<?php echo base_url() ?>"
	var fecha_hoy="<?php echo $fecha_hoy; ?>";
    var fecha_anio_atras="<?php echo $fecha_anio_atras; ?>";
    var perfil="<?php echo $this->session->userdata('id_perfil'); ?>";

    $("#desde_fg").val(fecha_anio_atras);
    $("#hasta_fg").val(fecha_hoy);

    dataGraficos()
	function dataGraficos(){
		if(perfil==4){
          trabajador = $("#trabajador").val();
        }else{
          trabajador = $("#trabajadores").val();
        }

	    $.ajax({
        url: base_url+"dataGraficos"+"?"+$.now(),  
        type: 'POST',
        data:{desde:$("#desde_fg").val(),hasta:$("#hasta_fg").val(),trabajador:trabajador},
        dataType:"json",
        success: function (json) {
       		$(".puntos_cont").html(`Total puntos : ${json.totalpuntos}`)
       		
       		google.charts.setOnLoadCallback(rankingTecnicos);
       		google.charts.setOnLoadCallback(puntosPorFechas);
       		google.charts.setOnLoadCallback(puntosTipoOrden);
       		google.charts.setOnLoadCallback(distribucionTipos);
       		google.charts.setOnLoadCallback(distribucionOt);
       		
       		function rankingTecnicos(){
       			var ranking = google.visualization.arrayToDataTable(json.ranking);
	       		ranking.sort([{column: 1, desc: true}])

	       		if(trabajador!=""){
	       			var height = 400
	       		}else{
	       			var height = 3800
	       		}

	        	var options = {
	        	 	// isStacked: true,
	        	 	fontName: 'Nunito',
	        	 	fontColor:'#32477C',
	        	 	 fontSize: 12,
			        colors: ['#32477C', 'green', 'red'],
			        chartArea:{
			             left:130,
			             right:130,
			             bottom:30,
			             top:10,
		            },
			        height:height,
			        hAxis: {
			          title: '',
			          minValue: 0,
			            textStyle : {
				            // fontSize: 12 
				        }
			        },
			        vAxis: {
			          title: '',
			          textStyle: {
			            // fontSize: 8,
			            bold:true,
			            color:'#32477C'
		              }
			        }
			    };

	            var chart = new google.visualization.BarChart(document.getElementById('rankingTecnicos'));
				chart.draw(ranking, options);
				$(".btn_filtro_graficos").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar').prop("disabled" , false);
			}

			function puntosPorFechas(){

				var puntosPorFechas = google.visualization.arrayToDataTable(json.puntosPorFechas);
	       		puntosPorFechas.sort([{column: 0, desc: false}])

	        	var options = {
		            // isStacked: true,
		            title: '',
		            width: "100%",
		            height: 220,
		            is3D:true,
		            colors:["#32477C"],
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
		             top:10,
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
		             'position':'none',
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

		            tooltip:{textStyle:{fontSize:15},isHtml: true},
		        };

	            var chart = new google.visualization.ColumnChart(document.getElementById('puntosPorFechas'));
	     		chart.draw(puntosPorFechas, options);
			}
			
			function puntosTipoOrden() {
				var puntosTipoOrden = google.visualization.arrayToDataTable(json.puntosTipoOrden);
	       		puntosTipoOrden.sort([{column: 0, desc: false}])		   

	       		var options = {
	       			showRowNumber: false,
	       			width: '100%', 
	       			height: 400
	       		}
		        var table = new google.visualization.Table(document.getElementById('puntosTipoOrden'));
		        table.draw(puntosTipoOrden, options);
		    }

		    function distribucionTipos(){
				var data = google.visualization.arrayToDataTable(json.distribucionTipos);
		        var options = {
			          fontName: 'Nunito',
			          height:160,
			     	  sliceVisibilityThreshold:0,
			          format: 'short',
			          isStacked: 'percent',
			          pieSliceText: 'value',
			          legend: {
			            position: 'labeled',
			            'alignment':'center',
			            textStyle: {
				            fontSize: 11,
				            bold:true,
				            color:'#32477C'
			            }
			          }, 
			  		  titlePosition: 'none',
			          titleTextStyle: {

				        color: '#32477C',
				        fontSize: '14', 
				        fontWidth: 'normal',
				        bold:true
				      },

			          colors: ['#32477C', 'green', 'red'],
			          chartArea:{
			             left:10,
			             right:10,
			             bottom:5,
			             top:5,
			          },
		        };
		        var chart = new google.visualization.PieChart(document.getElementById('distribucionTipos'));
		        chart.draw(data, options);
			}

		    function distribucionOt(){
				var data = google.visualization.arrayToDataTable(json.distribucionOt);
		        var options = {
			          fontName: 'Nunito',
			          height:160,
			     	  sliceVisibilityThreshold:0,
			          format: 'short',
			          isStacked: 'percent',
			          pieSliceText: 'value',
			          legend: {
			            position: 'labeled',
			            'alignment':'center',
			            textStyle: {
				            fontSize: 11,
				            bold:true,
				            color:'#32477C'
			            }
			          }, 
			  		  titlePosition: 'none',
			          titleTextStyle: {

				        color: '#32477C',
				        fontSize: '14', 
				        fontWidth: 'normal',
				        bold:true
				      },

			          colors: ['green', 'red'],
			          chartArea:{
			             left:10,
			             right:10,
			             bottom:5,
			             top:5,
			          },
		        };
		        var chart = new google.visualization.PieChart(document.getElementById('distribucionOt'));
		        chart.draw(data, options);
			}
			
        }
	    })
	}


	$(document).off('click', '.btn_filtro_graficos').on('click', '.btn_filtro_graficos',function(event) {
      event.preventDefault();
       $(".btn_filtro_graficos").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando').prop("disabled" , true);
       dataGraficos()
	});

	$.getJSON("listaTrabajadores", function(data) {
      response = data;
	}).done(function() {
	    $("#trabajadores").select2({
	     placeholder: 'Seleccione Trabajador | Todos',
	     data: response,
	     width: 'resolve',
         allowClear:true,
	    });
	});

	$(document).off('click', '.btn_excel_graficos').on('click', '.btn_excel_graficos',function(event) {
      event.preventDefault();
      var desde = $("#desde_fg").val();
      var hasta = $("#hasta_fg").val();  
      
      if(perfil==4){
        trabajador = $("#trabajador").val()
      }else{
        trabajador = $("#trabajadores").val();
      }

      if(desde==""){
         $.notify("Debe seleccionar una fecha de inicio.", {
             className:'error',
             globalPosition: 'top right'
         });  
         return false;
       }
       if(hasta==""){
         $.notify("Debe seleccionar una fecha de término.", {
             className:'error',
             globalPosition: 'top right'
         });  
        return false;
       }
      
       if(trabajador==""){
         trabajador="-"
       }

       window.location="excel_detalle/"+desde+"/"+hasta+"/"+trabajador;
    });


</script>

<div class="form-row">

    <div class="col-lg-3">
        <div class="form-group">
        	<div class="alert alert-primary2" role="alert">
			  <h5 class="alert-heading puntos_cont"></h5>
			</div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id=""> <span> Fecha <span></span> 
            </div>
              <input type="date" placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="desde_fg" id="desde_fg">
              <input type="date" placeholder="Hasta" class="fecha_normal form-control form-control-sm"  name="hasta_fg" id="hasta_fg">
          </div>
        </div>
    </div>

	<?php  
	   if($this->session->userdata('id_perfil')<>4){
	      ?>

	        <div class="col-lg-2">  
	          <div class="form-group">
	            <select id="trabajadores" name="trabajadores" style="width:100%!important;">
	                <option value="">Seleccione Trabajador | Todos</option>
	            </select>
	          </div>
	        </div>

	        <!--  <div class="col-lg-2">  
	          <div class="form-group">
	            <select id="jefes" name="jefes" class="custom-select custom-select-sm">
	                <option value="">Seleccione Jefe | Todos</option>
	            </select>
	          </div>
	        </div> -->

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

    <div class="col-6 col-lg-1">
        <div class="form-group">
	        <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_graficos btn_xr3">
	            <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
	        </button>
        </div>
    </div>

    <div class="col-6 col-lg-1">
        <div class="form-group">
	        <button type="button" class="btn-block btn btn-sm btn-primary btn_excel_graficos btn_xr3">
	            <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span>  Excel
	        </button>
        </div>
    </div>
    
</div>     

<div class="row">
	<div class="col mb-2">
	    <div class="card border-left-primary shadow mb-2">
	        <div class="card-body" style="padding: .4rem;">
	            <div class="row">
	              
	              <div class="col-xs-12 col-lg-6">
					<h6 class="titulo_grafico">Productividad de técnicos en periodo </h6>
			    	<div id="rankingTecnicos"></div>
				  </div>

				  <div class="col-xs-12 col-lg-3">
					<h6 class="titulo_grafico">Puntos por tipo órden</h6>
			    	<div id="puntosTipoOrden"></div>
				  </div>

				  <div class="col-xs-12 col-lg-3">
					<h6 class="titulo_grafico">Distribución por tipo</h6>
			    	<div id="distribucionTipos"></div>
			    	<h6 class="titulo_grafico">Estado OT</h6>
			    	<div id="distribucionOt"></div>
				  </div>

	            </div>

	            <div class="row">
	                <div class="col-lg-12">
						<h6 class="titulo_grafico">Puntos por fecha  </h6>
				    	<div id="puntosPorFechas"></div>
					</div>
	            </div>

	        </div>
	    </div>
	</div>
</div>

