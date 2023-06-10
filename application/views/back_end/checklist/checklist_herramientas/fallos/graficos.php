<style type="text/css">
	.titulo_grafico{
	  color: #32477C;
      font-size: 16px; 
      font-weight:bold;
      text-align: center;
    }
</style>
<script type="text/javascript">
	base_url = "<?php echo base_url() ?>"
	var perfil="<?php echo $this->session->userdata('id_perfil'); ?>";

	var desde="<?php echo $desde; ?>";
    var hasta="<?php echo $hasta; ?>";
    $("#desde_chf").val(desde);
    $("#hasta_chf").val(hasta);

	google.charts.setOnLoadCallback(graficoFallos);

	function graficoFallos(){
		var desde = $("#desde_chf").val();
		var hasta = $("#hasta_chf").val();
		var auditor = $("#auditor_chf").val();
	    var trabajador = perfil <= 3 ? $("#trabajadores_chf").val() : $("#trabajador_chf").val();

		$.ajax({
	        url: base_url+"graficoFallosH"+"?"+$.now(),  
	        type: 'POST',
	        data:{desde:desde,hasta:hasta,trabajador:trabajador,auditor:auditor},
	        dataType:"json",
	        success: function (json) {
			var graficoFallos = google.visualization.arrayToDataTable(json);
       		graficoFallos.sort([{column: 4, desc: false}])
       		var options = {
		        isStacked: true,
	            width: "100%",
	            height: 460,
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

			    var chart = new google.visualization.ComboChart(document.getElementById('graficoFallos'));
			    chart.draw(graficoFallos, options);
    	    }
        })
	  }

	
    $.getJSON(base_url + "listaTrabajadoresCH" , function(data) {
	      response = data;
		}).done(function() {
		    $("#trabajadores_chf").select2({
			     placeholder: 'Seleccione Trabajador | Todos',
			     data: response,
			     width: 'resolve',
		         allowClear:true,
		    });
	});
	
	$(document).off('change', '#desde_chf ,#hasta_chf , #trabajadores_chf ,#auditor_chf').on('change', '#desde_chf ,#hasta_chf , #trabajadores_chf ,#auditor_chf', function(event) {
       graficoFallos()
    }); 

</script>


<div class="form-row cont_graficos">
	
    <div class="col-lg-3">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id=""><i class="fa fa-calendar-alt" style="margin-right: 5px;"></i> Fecha </span> 
          </div>
          <input type="date" placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="desde_chf" id="desde_chf">
          <input type="date" placeholder="Hasta" class="fecha_normal form-control form-control-sm"  name="hasta_chf" id="hasta_chf">
        </div>
      </div>
    </div>

    <div class="col-lg-2">
    <div class="form-group">
      <select id="auditor_chf" name="auditor_chf" class="custom-select custom-select-sm">
        <option value="" selected>Seleccione Auditor | Todos </option>
        <?php  
        	foreach($auditores as $a){
        		?>
        			<option value="<?php echo $a["id"]?>" ><?php echo $a["nombre_completo"]?> </option>
        		<?php
        	}
        ?>
      </select>
    </div>
    </div>

 
	  <?php  
	    if($this->session->userdata('id_perfil')<=3){
	  ?>

	    <div class="col-lg-2">  
	      <div class="form-group">
	        <select id="trabajadores_chf" name="trabajadores_chf" style="width:100%!important;">
	            <option value="">Seleccione Trabajador | Todos</option>
	        </select>
	      </div>
	    </div>

	  <?php
	    }else{
	  ?>

    <div class="col-lg-2">  
      <div class="form-group">
        <select id="trabajador_chf" name="trabajador_chf" class="custom-select custom-select-sm" >
            <option selected value="<?php echo $this->session->userdata('rut'); ?>"><?php echo $this->session->userdata('nombre_completo'); ?></option>
        </select>
      </div>
    </div>

  <?php
    }
  ?>

</div>  

<div class="row">
    <div class="col mb-2">
        <div class="row">
            <div class="col-xs-12 col-lg-12">
				<div class="short-div">
	    			<h6 class="titulo_grafico">Fallos últimos Últimos 12 meses</h6>
					<div id="graficoFallos"></div>
	     		</div>
		    </div>
        </div>
    </div>
</div>

