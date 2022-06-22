<style type="text/css">
  .red{
    background-color: #DC3545;
    color: #fff;
  }

  .grey{
    background-color: grey;
    color: #fff;
  }

  @media(min-width: 768px){
    .borrar_FH{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
    }
    .btn_modificar_FH{
      display: block;
      text-align: center!important;
      margin:0 auto!important;
      font-size: 15px!important;
    }
    .modal_FH{
      width: 34%!important;
      height: 500px;
    }
    .table_head{
      font-size: 12px!important;
    }

  }
  @media(max-width: 768px){
    .borrar_FH{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 20px;
      text-decoration: none!important;
    }
    .btn_modificar_FH{
      display: block;
      text-align: center!important;
      font-size: 18px!important;
    }
    .modal_FH{
      width: 94%!important;
      height:300px;
    }
    .table_head{
      font-size: 11px!important;
    }
  }
</style>

<script type="text/javascript">
  $(function(){
    var fecha_hoy="<?php echo $fecha_hoy; ?>";
    var fecha_anio_atras="<?php echo $fecha_anio_atras; ?>";
    $("#desde_fh").val(fecha_anio_atras);
    $("#hasta_fh").val(fecha_hoy);
    const id_perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    const base = "<?php echo base_url() ?>";

  /*****DATATABLE*****/   
    var listaFH = $('#listaFH').DataTable({
       "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
       "iDisplayLength":-1, 
       "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
       "bPaginate": false,
       "aaSorting" : [[9,"desc"]],
       "scrollY": "60vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "ajax": {
          "url":"<?php echo base_url();?>listaFH",
          "dataSrc": function (json) {
            $(".btn_filtro_FH").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_FH").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            param.desde = $("#desde_fh").val();
            param.hasta = $("#hasta_fh").val();
            param.solucion_estado = $("#solucion_estado_f").val();

          }
        },    
        columnDefs: [
          { width: "2%", targets: 0 , orderable : false},
        ],
       "columns": [
          {
           "class":"centered center margen-td","data": function(row,type,val,meta){
              if(row.solucion_estado=="Pendiente"){
                btn = '<center><a data-toggle="modal" href="#modal_FH" data-hash="'+row.hash+'" data-placement="top" data-toggle="tooltip" title="Modificar" class="fa fa-edit btn_modificar_FH"></a>';
              }else{
                btn = ""
              }
              return btn;
            }
          },
          { "data": "auditor" ,"class":"margen-td centered"},
          { "data": "tipo" ,"class":"margen-td centered"},
          { "data": "descripcion" ,"class":"margen-td centered"},
          { "data": "fecha" ,"class":"margen-td centered"},
          { "data": "estado" ,"class":"margen-td centered"},
          { "data": "solucion_estado" ,"class":"margen-td centered"},
          { "data": "solucion_fecha" ,"class":"margen-td centered"},
          { "data": "solucion_observacion" ,"class":"margen-td centered"},
          { "data": "ultima_actualizacion" ,"class":"margen-td centered"}
        ]
      }); 

      $(document).on('keyup paste', '#buscador_fh', function() {
        listaFH.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
		  $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
	  }, 500 );

    
  /*********INGRESO************/

    $(document).off('click', '.btn_nuevoFH').on('click', '.btn_nuevoFH',function(event) {
        $('#modal_FH').modal('toggle'); 
        $(".btn_guardarFH").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_guardarFH").attr("disabled", false);
        $(".cierra_modalFH").attr("disabled", false);
        $('#formFH')[0].reset();
        $("#hashFH").val("");
        $("#formFH input,#formFH select,#formFH button,#formFH").prop("disabled", false);
        $(".estadoFH").removeClass("red");
        $(".estadoFH").removeClass("grey");
    });     

    $(document).off('submit', '#formFH').on('submit', '#formFH',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formFH");
      var formData = new FormData(formElement);

        $.ajax({
            url: $('#formFH').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardarFH").attr("disabled", true);
              $(".cierra_modalFH").attr("disabled", true);
              $("#formFH input,#formFH select,#formFH button,#formFH").prop("disabled", true);
            },
            success: function (data) {
             if(data.res == "error"){

                $(".btn_guardarFH").attr("disabled", false);
                $(".cierra_modalFH").attr("disabled", false);

                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $("#formFH input,#formFH select,#formFH button,#formFH").prop("disabled", false);


              }else if(data.res == "ok"){
                  $(".btn_guardarFH").attr("disabled", false);
                  $(".cierra_modalFH").attr("disabled", false);

                  $.notify(data.msg, {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $('#modal_FH').modal('toggle'); 
                  listaFH.ajax.reload();
                 /* getDataChecklistHerramientas(data.hash)
                  $("#hashFH").val(data.hash);*/
              }

            $(".btn_guardarFH").attr("disabled", false);
            $(".cierra_modalFH").attr("disabled", false);
            $("#formFH input,#formFH select,#formFH button,#formFH").prop("disabled", false);
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
                    $('#modal_FH').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_FH').modal("toggle");
            }
          },timeout:105000
        });
      return false; 
    });

    $(document).off('click', '.btn_modificar_FH').on('click', '.btn_modificar_FH',function(event) {
      $("#hashFH").val("");
      hash = $(this).attr("data-hash");
      $("#hashFH").val(hash);
      $(".estadoFH").removeClass("red");
      $(".estadoFH").removeClass("grey");
      getDataFH(hash)        
    });

    function getDataFH(hash){
       $.ajax({
        url: base+"getDataFH"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash : hash},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardarFH").attr("disabled", true);
          $(".cierra_modalFH").attr("disabled", true);
          $("#formFH input,#formFH select,#formFH button,#formFH").prop("disabled", true);
        },
        success: function (data) {
          $(".btn_guardarFH").attr("disabled", false);
          $(".cierra_modalFH").attr("disabled", false);
          $("#formFH input,#formFH select,#formFH button,#formFH").prop("disabled", false);
          if(data.res=="ok"){
            for(dato in data.datos){
              $("#estado_fh").html(data.datos[dato].solucion_estado);
              $("#solucion_fecha").val(data.datos[dato].solucion_fecha);
              $("#solucion_observacion").val(data.datos[dato].solucion_observacion);
            } 
            listaFH.ajax.reload();
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
                  $('#modal_FH').modal("toggle");
              }    
              return;
          }

          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
              $('#modal_FH').modal("toggle");
          }
        },timeout:25000
        }); 
    }


  /********OTROS**********/

    
    $(document).off('input', '#desde_fh ,#hasta_fh , #solucion_estado_f').on('input', '#desde_fh ,#hasta_fh , #solucion_estado_f', function(event) {
      listaFH.ajax.reload()
    }); 

    $(document).off('click', '.btn_filtro_FH').on('click', '.btn_filtro_FH',function(event) {
      event.preventDefault();
       $(this).prop("disabled" , true);
       $(".btn_filtro_FH").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
       listaFH.ajax.reload();
    });



    $(".fecha_normal").datetimepicker({
      format: "DD-MM-YYYY",
      locale:"es",
      maxDate:"now"
    });
     
  })
</script>

<!-- FILTROS -->
  
    <div class="form-row">

      <div class="col-lg-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span>Fecha <span></span> 
            </div>
              <input type="text" placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="desde_fh" id="desde_fh">
              <input type="text" placeholder="Hasta" class="fecha_normal form-control form-control-sm"  name="hasta_fh" id="hasta_fh">
          </div>
        </div>
      </div>

      <div class="col-lg-2">               
        <div class="form-group">
         <div class="input-group mb-3">
            <select id="solucion_estado_f" name="solucion_estado_f" class="custom-select custom-select-sm">
            <option value="">Estado fallo | Todos</option>
            <option value="0" selected>Pendiente</option>
            <option value="1">Finalizado</option>
            </select>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4">  
       <div class="form-group">
        <input type="text" placeholder="Busqueda" id="buscador_fh" class="buscador_fh form-control form-control-sm">
       </div>
      </div>

      <div class="col-6 col-lg-1">
        <div class="form-group">
         <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_FH btn_xr3">
         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
         </button>
       </div>
      </div>

     <!--  <div class="col-6 col-lg-1">  
        <div class="form-group">
         <button type="button"  class="btn-block btn btn-sm btn-primary btn_excel btn_xr3">
         <i class="fa fa-save"></i>  Excel
         </button>
        </div>
      </div> -->
      
      </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="listaFH" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <th class="centered" style="width: 50px;"></th>    
            <th class="centered">Auditor</th> 
            <th class="centered">Tipo </th>   
            <th class="centered">Descripción</th>   
            <th class="centered">Fecha</th>   
            <th class="centered">Resultado </th>   
            <th class="centered">Estado Solución</th>   
            <th class="centered">Fecha Solución</th>   
            <th class="centered">Observación Solución</th>   
            <th class="centered">Última actualización Solución</th>   
          </tr>
        </thead>
      </table>
    </div>
  </div>

<!--  FORMULARIO-->
  <div id="modal_FH" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formFH",array("id"=>"formFH","class"=>"formFH"))?>
    <input type="hidden" name="hash" id="hashFH">
    <div class="modal-dialog modal_FH modal-dialog-scrollable">
      <div class="modal-content">

       <div class="modal-header">
        <div class="col-xs-12 col-sm-12 col-lg-6 offset-lg-3 mt-0">
          <div class="form-row">
            <div class="col-9 col-lg-6">
                <button type="submit" class="btn-block btn btn-sm btn-success btn_guardarFH">
                 <i class="fa fa-save"></i> Guardar
                </button>
            </div>
            <div class="col-3 col-lg-6">
              <button class="btn-block btn btn-sm btn-danger cierra_modalFH" data-dismiss="modal" aria-hidden="true">
             <!--   <i class="fa fa-window-close"></i>  -->Cerrar
              </button>
            </div>
          </div>
        </div>

       </div>

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Fallos herramientas </legend>

            <div class="form-row">
              
            	<div class="col-lg-12">  
	                <div class="form-group">
	                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Estado solución (<span id="estado_fh"></span>)</label>
	                </div>
	            </div>

	            <div class="col-lg-12">  
	                <div class="form-group">
	                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha solución</label>
	                <input placeholder="Fecha solución" type="text" name="solucion_fecha"  id="solucion_fecha" class="fecha_normal form-control form-control-sm" autocomplete="off"  />
	                </div>
	            </div>

             	<div class="col-lg-12">  
	                <div class="form-group">
	                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Observación solución</label>
	                <input placeholder="Observación" type="text" name="solucion_observacion"  id="solucion_observacion" class="form-control form-control-sm" autocomplete="off"  />
	                </div>
	            </div>

            </div>
          </fieldset> 

        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>


