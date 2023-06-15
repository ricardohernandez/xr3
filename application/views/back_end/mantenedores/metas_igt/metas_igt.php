<style type="text/css">
 

  @media(min-width: 768px){
   
    .borrar_metas_igt{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
    }

    .btn_modificar_metas_igt{
      display: block;
      text-align: center!important;
      margin:0 auto!important;
      font-size: 15px!important;
    }

    .modal_metas_igt{
      width: 44%!important;
    }

  }

  @media(max-width: 768px){
  
    .borrar_metas_igt{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 20px;
      text-decoration: none!important;
    }

    .btn_modificar_metas_igt{
      display: block;
      text-align: center!important;
      font-size: 18px!important;
    }

    .modal_metas_igt{
      width: 94%!important;
    }
  }
</style>

<script type="text/javascript">
  $(function(){

    const perfil="<?php echo $this->session->userdata('perfil'); ?>";
    const base = "<?php echo base_url() ?>";

    $(document).off('keydown', '.numbersOnly').on('keydown', '.numbersOnly',function(e) {
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
          (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
          (e.keyCode >= 35 && e.keyCode <= 40)) {
               return;
      }
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
          e.preventDefault();
      }
    });

  /*****DATATABLE*****/   
    var tablaMetasIgt = $('#tablaMetasIgt').DataTable({
       "aaSorting" : [],
       "scrollY": "65vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "responsive" :false,
       "select" :true,
       columnDefs: [
          { orderable: false, targets: 0 }
       ],
       "ajax": {
          "url":"<?php echo base_url();?>listaMetasIgt",
          "dataSrc": function (json) {
            $(".btn_filtro_metas_igt").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_metas_igt").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            param.tipo = $("#tipo_f").val();
          }
        },    

       "columns": [
          {
           "class":"centered center margen-td","data": function(row,type,val,meta){
              btn='<center><a data-toggle="modal" href="#modal_metas_igt" data-hash_metas_igt="'+row.hash_metas_igt+'" data-placement="top" data-toggle="tooltip" title="Modificar" class="fa fa-edit btn_modificar_metas_igt"></a>';
              /*btn+='<a href="#" data-placement="top" data-toggle="tooltip" title="Eliminar" class="fa fa-trash borrar_metas_igt" data-hash_metas_igt="'+row.hash_metas_igt+'"></a></center>';*/
              return btn;
            }
          },
          { "data": "nivel" ,"class":"margen-td centered"},
          { "data": "indicador" ,"class":"margen-td centered"},
          { "data": "meta_actual" ,"class":"margen-td centered"},
          { "data": "meta_anterior" ,"class":"margen-td centered"},
        ]
      }); 

      $(document).on('keyup paste', '#buscador_metas_igt', function() {
        tablaMetasIgt.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var tablaMetasIgt = $.fn.dataTable.fnTables(true);
        if ( tablaMetasIgt.length > 0 ) {
            $(tablaMetasIgt).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var tablaMetasIgt = $.fn.dataTable.fnTables(true);
        if ( tablaMetasIgt.length > 0 ) {
            $(tablaMetasIgt).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var tablaMetasIgt = $.fn.dataTable.fnTables(true);
        if ( tablaMetasIgt.length > 0 ) {
            $(tablaMetasIgt).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 

      $(document).off('click', '.btn_filtro_metas_igt').on('click', '.btn_filtro_metas_igt',function(event) {
	      event.preventDefault();
	       $(this).prop("disabled" , true);
	       $(".btn_filtro_metas_igt").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
	       tablaMetasIgt.ajax.reload();
	  });




  /*********INGRESO************/

    $(document).off('click', '.btn_nuevo_metas_igt').on('click', '.btn_nuevo_metas_igt',function(event) {
        $('#modal_metas_igt').modal('toggle'); 
        $(".btn_guardar_metas_igt").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_guardar_metas_igt").attr("disabled", false);
        $(".cierra_modal_metas_igt").attr("disabled", false);
        $('#formMetasIgt')[0].reset();
        $("#hash_metas_igt").val("");
        $("#formMetasIgt input,#formMetasIgt select,#formMetasIgt button,#formMetasIgt").prop("disabled", false);
    });     


    $(document).off('submit', '#formMetasIgt').on('submit', '#formMetasIgt',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formMetasIgt");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formMetasIgt').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_metas_igt").attr("disabled", true);
              $(".cierra_modal_metas_igt").attr("disabled", true);
              $("#formMetasIgt input,#formMetasIgt select,#formMetasIgt button,#formMetasIgt").prop("disabled", true);
            },
            success: function (data) {
             if(data.res == "error"){

                $(".btn_guardar_metas_igt").attr("disabled", false);
                $(".cierra_modal_metas_igt").attr("disabled", false);

                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $("#formMetasIgt input,#formMetasIgt select,#formMetasIgt button,#formMetasIgt").prop("disabled", false);

              }else if(data.res == "ok"){
                  $(".btn_guardar_metas_igt").attr("disabled", false);
                  $(".cierra_modal_metas_igt").attr("disabled", false);

                  $.notify("Datos ingresados correctamente.", {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                
                  $('#modal_metas_igt').modal("toggle");
                  tablaMetasIgt.ajax.reload();
            }

            $(".btn_guardar_metas_igt").attr("disabled", false);
            $(".cierra_modal_metas_igt").attr("disabled", false);
            $("#formMetasIgt input,#formMetasIgt select,#formMetasIgt button,#formMetasIgt").prop("disabled", false);
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
                    $('#modal_metas_igt').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_metas_igt').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

   $(document).off('click', '.btn_modificar_metas_igt').on('click', '.btn_modificar_metas_igt',function(event) {
      $("#hash_metas_igt").val("");
      hash_metas_igt = $(this).attr("data-hash_metas_igt");
      $("#hash_metas_igt").val(hash_metas_igt);
        
      $.ajax({
        url: "getDataMetasIgt"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash_metas_igt : hash_metas_igt},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardar_metas_igt").attr("disabled", true);
          $(".cierra_modal_metas_igt").attr("disabled", true);
          $("#formMetasIgt input,#formMetasIgt select,#formMetasIgt button,#formMetasIgt").prop("disabled", true);
        },

        success: function (data) {
          $(".btn_guardar_metas_igt").attr("disabled", false);
          $(".cierra_modal_metas_igt").attr("disabled", false);
          $("#formMetasIgt input,#formMetasIgt select,#formMetasIgt button,#formMetasIgt").prop("disabled", false);
        
          if(data.res=="ok"){
            for(dato in data.datos){
              $("#nivel  option[value='"+data.datos[dato].id_nivel+"'").prop("selected", true);
              $("#indicador  option[value='"+data.datos[dato].id_indicador+"'").prop("selected", true);
              $("#meta_actual").val(data.datos[dato].meta_actual);
              $("#meta_anterior").val(data.datos[dato].meta_anterior);
            } 
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
                  $('#modal_metas_igt').modal("toggle");
              }    
              return;
          }

          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
              $('#modal_metas_igt').modal("toggle");
          }
        },timeout:25000
      }); 
    });

    
  })
</script>

<!-- FILTROS -->
  
  <div class="form-row">

    <div class="col-12 col-lg-4">  
      <div class="form-group">
      <input type="text" placeholder="Busqueda" id="buscador_metas_igt" class="buscador_metas_igt form-control form-control-sm">
      </div>
    </div>

  </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="tablaMetasIgt" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <th class="centered" style="width: 50px;">Acciones</th>   
            <th class="centered">Nivel</th>   
            <th class="centered">Indicador</th> 
            <th class="centered">Meta actual</th>   
            <th class="centered">Meta anterior</th>   
          </tr>
        </thead>
      </table>
    </div>
  </div>


<!--  FORMULARIO-->
  <div id="modal_metas_igt" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formMetasIgt",array("id"=>"formMetasIgt","class"=>"formMetasIgt"))?>

    <div class="modal-dialog modal_metas_igt modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header">
          <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
	          <div class="form-row">
	            <div class="col-9 col-lg-6">
	                <button type="submit" class="btn-block btn btn-sm btn-primary btn_guardar_metas_igt">
	                 <i class="fa fa-save"></i> Actualizar
	                </button>
	            </div>
	            <div class="col-3 col-lg-6">
	              <button class="btn-block btn btn-sm btn-secondary cierra_modal_metas_igt" data-dismiss="modal" aria-hidden="true">
	             <!--   <i class="fa fa-window-close"></i>  -->Cerrar
	              </button>
	            </div>
	          </div>
	        </div>
        </div>

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <input type="hidden" name="hash_metas_igt" id="hash_metas_igt">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Metas   </legend>

            <div class="form-row">
              
              <div class="col-lg-12">               
      			    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nivel</label>
      			    <div class="form-group">
      			     <div class="input-group mb-3">
      			        <select id="nivel" name="nivel" class="custom-select custom-select-sm">
      			        <option value="" selected>Seleccione...</option>
      			            <?php 
      			            foreach($niveles as $n){
      			              ?>
      			                <option value="<?php echo $n["id"]; ?>"><?php echo $n["nivel"]; ?></option>
      			              <?php
      			            }
      			          ?>
      			        </select>
      			      </div>
      			    </div>
      			  </div>

              <div class="col-lg-12">               
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Indicador</label>
                <div class="form-group">
                 <div class="input-group mb-3">
                    <select id="indicador" name="indicador" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione...</option>
                        <?php 
                        foreach($indicadores as $i){
                          ?>
                            <option value="<?php echo $i["id"]; ?>"><?php echo $i["indicador"]; ?></option>
                          <?php
                        }
                      ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-lg-12">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Meta actual </label>
                <input placeholder="Meta actual"  type="text" name="meta_actual"  id="meta_actual" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>


              <div class="col-lg-12">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Meta anterior </label>
                <input placeholder="Meta anterior "  type="text" name="meta_anterior"  id="meta_anterior" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

         

            </div>
          </fieldset> 
         
        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>



 

