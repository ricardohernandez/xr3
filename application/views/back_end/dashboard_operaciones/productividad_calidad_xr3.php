 <style>
  .file_cs{
    display:none;
  }
 </style>
<script>
  const base_url = "<?php echo base_url() ?>"
  

  google.charts.setOnLoadCallback(productividadNacional);

  function productividadNacional(){

    var periodo = $("#periodo").val();
    var supervisor = $("#supervisor").val();
    var zona = $("#zona").val();

    $.ajax({
      url: base_url+"productividadNacional"+"?"+$.now(),  
      type: 'POST',
      data:{periodo:periodo,supervisor:supervisor,zona:zona},
      dataType:"json",
      beforeSend:function(){
          $("#load").show()
          $(".body").hide()
      },
      success: function (json) {
          $("#load").hide()
          $(".body").fadeIn(500)

          var data = google.visualization.arrayToDataTable(json);
          data.sort([{column: 5, desc: false}])

          const options = {
            fontName: 'ubuntu',
            curveType: 'function',
            fontColor:'#32477C',
            backgroundColor: { fill:'transparent' },

            colors: ['#F48432','#2f81f7'],
              chartArea:{
                  left:40,
                  right:40,
                  bottom:40,
                  top:40,
              },
              height:250,
              hAxis: {
                title: '',
                minValue: 0,
                textStyle: {
                    fontSize: 13,
                    bold:false,
                    color:'#808080'
                }, 
                gridlines: {
                  color: '',
                  count:0
                }
              },
              vAxis: {
                title: '',
                textStyle: {
                    fontSize: 13,
                    bold:false,
                    color:'#808080'
                },
                gridlines: {
                  color: '',
                  count:0
                }
              },
              annotations: {
                  alwaysOutside: false,
                  textStyle: {
                      fontSize: 13,
                      auraColor: 'none'
                  }
              },
              legend : {  
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
                      color:'#ffffff96', 
                      fontSize: 13
                  }
              },       

          }
      
          var chart = new google.visualization.LineChart(document.getElementById('productividadNacional'));
          chart.draw(data, options);
      }
    }) 
  }



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
</div>          


<div class="col-12 col-lg-6">
    <div class="card">

    <div class="short-div">
          <p class="titulo_grafico">Productividad nacional</p>
          <div id="productividadNacional"></div>
      </div>

    </div>
  </div>
