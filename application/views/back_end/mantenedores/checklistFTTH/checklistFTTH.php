<style type="text/css">
  @media(min-width: 768px){
   
    .borrar_herramienta{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
    }

    .btn_modificar_ftth{
      display: block;
      text-align: center!important;
      margin:0 auto!important;
      font-size: 15px!important;
    }

    .modal_ftth{
      width: 44%!important;
    }


  }

  @media(max-width: 768px){
   

    .borrar_herramienta{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 20px;
      text-decoration: none!important;
    }

    .btn_modificar_ftth{
      display: block;
      text-align: center!important;
      font-size: 18px!important;
    }

    .modal_ftth{
      width: 94%!important;
    }

  }
</style>


<script type="text/javascript">
  $(function(){

    const perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
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
    var tb_ftth = $('#tb_ftth').DataTable({
       "aaSorting" : [],
       "scrollY": "65vh",
       "scrollX": true,
       "responsive" :false,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" :true,
       columnDefs: [
          { orderable: false, targets: 0 }
       ],
       "ajax": {
          "url":"<?php echo base_url();?>listaMantChecklistFTTH",
          "dataSrc": function (json) {
            $(".btn_filtro_ftth").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_ftth").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            param.tipo = $("#tipo_f").val();
          }
        },    

       "columns": [
          {
           "class":"centered center margen-td","data": function(row,type,val,meta){
              btn='<center><a data-toggle="modal" href="#modal_ftth" data-hash_herramienta="'+row.hash_herramienta+'" data-placement="top" data-toggle="tooltip" title="Modificar" class="fa fa-edit btn_modificar_ftth"></a>';
              btn+='<a href="#" data-placement="top" data-toggle="tooltip" title="Eliminar" class="fa fa-trash borrar_herramienta" data-hash_herramienta="'+row.hash_herramienta+'"></a></center>';
              return btn;
            }
          },
          { "data": "descripcion" ,"class":"margen-td centered"},
          { "data": "tipo" ,"class":"margen-td centered"},
          {
            "class":"centered","data": function(row,type,val,meta){
              var base="<?php echo base_url()?>";
              if(row.imagen!=""){
                html='<center><a target="_blank" href="./archivos/fotos_checklist/ftth/'+row.imagen+'" class="foto_icon" title="Imagen"><i class="fa fa-camera"></i></a>';
              }else{
                html="";
              }
              return html;
            }
          },

          { "data": "valor" ,"class":"margen-td centered"},
          { "data": "unidad" ,"class":"margen-td centered"},
        ]
      }); 

      $(document).on('keyup paste', '#buscador_ftth', function() {
        tb_ftth.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var tb_ftth = $.fn.dataTable.fnTables(true);
        if ( tb_ftth.length > 0 ) {
            $(tb_ftth).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var tb_ftth = $.fn.dataTable.fnTables(true);
        if ( tb_ftth.length > 0 ) {
            $(tb_ftth).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var tb_ftth = $.fn.dataTable.fnTables(true);
        if ( tb_ftth.length > 0 ) {
            $(tb_ftth).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 

      $(document).off('click', '.btn_filtro_ftth').on('click', '.btn_filtro_ftth',function(event) {
	      event.preventDefault();
	       $(this).prop("disabled" , true);
	       $(".btn_filtro_ftth").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
	       tb_ftth.ajax.reload();
	  });




  /*********INGRESO************/

    $(document).off('click', '.btn_nuevo_ftth').on('click', '.btn_nuevo_ftth',function(event) {
        $('#modal_ftth').modal('toggle'); 
        $(".btn_guardar_ftth").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_guardar_ftth").attr("disabled", false);
        $(".cierra_modal_ftth").attr("disabled", false);
        $('#formMantChecklistFTTH')[0].reset();
        $("#hash_herramienta").val("");
        $("#formMantChecklistFTTH input,#formMantChecklistFTTH select,#formMantChecklistFTTH button,#formMantChecklistFTTH").prop("disabled", false);
    });     


    $(document).off('submit', '#formMantChecklistFTTH').on('submit', '#formMantChecklistFTTH',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formMantChecklistFTTH");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formMantChecklistFTTH').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_ftth").attr("disabled", true);
              $(".cierra_modal_ftth").attr("disabled", true);
              $("#formMantChecklistFTTH input,#formMantChecklistFTTH select,#formMantChecklistFTTH button,#formMantChecklistFTTH").prop("disabled", true);
            },
            success: function (data) {
             if(data.res == "error"){

                $(".btn_guardar_ftth").attr("disabled", false);
                $(".cierra_modal_ftth").attr("disabled", false);

                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $("#formMantChecklistFTTH input,#formMantChecklistFTTH select,#formMantChecklistFTTH button,#formMantChecklistFTTH").prop("disabled", false);

              }else if(data.res == "ok"){
                  $(".btn_guardar_ftth").attr("disabled", false);
                  $(".cierra_modal_ftth").attr("disabled", false);

                  $.notify("Datos ingresados correctamente.", {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                
                  $('#modal_ftth').modal("toggle");
                  tb_ftth.ajax.reload();
            }

            $(".btn_guardar_ftth").attr("disabled", false);
            $(".cierra_modal_ftth").attr("disabled", false);
            $("#formMantChecklistFTTH input,#formMantChecklistFTTH select,#formMantChecklistFTTH button,#formMantChecklistFTTH").prop("disabled", false);
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
                    $('#modal_ftth').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_ftth').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

   $(document).off('click', '.btn_modificar_ftth').on('click', '.btn_modificar_ftth',function(event) {
      $("#hash_herramienta").val("");
      hash_herramienta = $(this).attr("data-hash_herramienta");
      $("#hash_herramienta").val(hash_herramienta);
        
      $.ajax({
        url: "getDataMantChecklistFTTH"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash_herramienta : hash_herramienta},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardar_ftth").attr("disabled", true);
          $(".cierra_modal_ftth").attr("disabled", true);
          $("#formMantChecklistFTTH input,#formMantChecklistFTTH select,#formMantChecklistFTTH button,#formMantChecklistFTTH").prop("disabled", true);
        },

        success: function (data) {
          $(".btn_guardar_ftth").attr("disabled", false);
          $(".cierra_modal_ftth").attr("disabled", false);
          $("#formMantChecklistFTTH input,#formMantChecklistFTTH select,#formMantChecklistFTTH button,#formMantChecklistFTTH").prop("disabled", false);
        
          if(data.res=="ok"){
            for(dato in data.datos){
              $("#tipo  option[value='"+data.datos[dato].id_tipo+"'").prop("selected", true);
              $("#descripcion").val(data.datos[dato].descripcion);
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
                  $('#modal_ftth').modal("toggle");
              }    
              return;
          }

          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
              $('#modal_ftth').modal("toggle");
          }
        },timeout:25000
      }); 
    });


    $(document).off('click', '.borrar_herramienta').on('click', '.borrar_herramienta',function(event) {
        var hash_herramienta=$(this).attr("data-hash_herramienta");
          if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaMantChecklistFTTH'+"?"+$.now(),{"hash_herramienta": hash_herramienta}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
               tb_ftth.ajax.reload();
              }else{
                $.notify(data.msg, {
                  className:'danger',
                  globalPosition: 'top right'
                });
              }
            },"json");
        }
    });


    $(document).off('click', '.btn_excel_ftth').on('click', '.btn_excel_ftth',function(event) {
      event.preventDefault();
      var tipo = $("#tipo_f").val()
      tipo = tipo=="" ? "-" : tipo;
      window.location="excelMantChecklistFTTH/"+tipo;
    });
    

  })
</script>

<!-- FILTROS -->
  
    <div class="form-row">
      <!--   <div class="col-xs-6 col-sm-6 col-md-1 col-lg-1 no-padding">  
         <input type="file" id="userfile" name="userfile" class="file_cs" style="display:none;" />
         <button type="button" class="allwidth btn btn-danger btn-sm btn_file_cs" value="" onclick="document.getElementById('userfile').click();">
         <span class="glyphicon glyphicon-folder-open" style="margin-right:5px!important;"></span> CSV</button>
      </div> -->
      <div class="col-6 col-lg-1">  
        <div class="form-group">
           <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_ftth btn_xr3">
           <i class="fa fa-plus-circle"></i>  Crear
           </button>
        </div>
      </div>

       <div class="col-lg-2">               
	    <div class="form-group">
	     <div class="input-group mb-3">
	        <select id="tipo_f" name="tipo_f" class="custom-select custom-select-sm">
	        <option value="" selected>Seleccione | Todos</option>
	            <?php 
	            foreach($tipos as $t){
	              ?>
	                <option value="<?php echo $t["id"]; ?>"><?php echo $t["tipo"]; ?></option>
	              <?php
	            }
	          ?>
	        </select>
	      </div>
	    </div>
	  </div>


      <div class="col-12 col-lg-4">  
       <div class="form-group">
        <input type="text" placeholder="Busqueda" id="buscador_ftth" class="buscador_ftth form-control form-control-sm">
       </div>
      </div>

      <div class="col-6 col-lg-1">
        <div class="form-group">
         <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_ftth btn_xr3">
         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
         </button>
       </div>
      </div>

      <div class="col-6 col-lg-1">  
        <div class="form-group">
         <button type="button"  class="btn-block btn btn-sm btn-primary btn_excel_ftth btn_xr3">
         <i class="fa fa-save"></i> Exportar Excel
         </button>
        </div>
      </div>
      
      </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="tb_ftth" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <th class="centered" style="width: 50px;">Acciones</th>   
            <th class="centered">Descripción</th>   
            <th class="centered">Tipo</th> 
            <th class="centered">Imagen</th>   
            <th class="centered">Valor</th>   
            <th class="centered">Unidad</th>   
          </tr>
        </thead>
      </table>
    </div>
  </div>


<!--  FORMULARIO-->
  <div id="modal_ftth" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formMantChecklistFTTH",array("id"=>"formMantChecklistFTTH","class"=>"formMantChecklistFTTH"))?>

    <div class="modal-dialog modal_ftth modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header">
          <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
	          <div class="form-row">
	            <div class="col-9 col-lg-6">
	                <button type="submit" class="btn-block btn btn-sm btn-primary btn_guardar_ftth">
	                 <i class="fa fa-save"></i> Guardar
	                </button>
	            </div>
	            <div class="col-3 col-lg-6">
	              <button class="btn-block btn btn-sm btn-secondary cierra_modal_ftth" data-dismiss="modal" aria-hidden="true">
	             <!--   <i class="fa fa-window-close"></i>  -->Cerrar
	              </button>
	            </div>
	          </div>
	        </div>
        </div>

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <input type="hidden" name="hash_herramienta" id="hash_herramienta">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro checklist ftth </legend>

            <div class="form-row">
              
              <div class="col-lg-12">               
      			    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tipo</label>
      			    <div class="form-group">
      			     <div class="input-group mb-3">
      			        <select id="tipo" name="tipo" class="custom-select custom-select-sm">
      			        <option value="" selected>Seleccione...</option>
      			            <?php 
      			            foreach($tipos as $t){
      			              ?>
      			                <option value="<?php echo $t["id"]; ?>"><?php echo $t["tipo"]; ?></option>
      			              <?php
      			            }
      			          ?>
      			        </select>
      			      </div>
      			    </div>
      			  </div>

              <div class="col-lg-12">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Descripción </label>
                <input placeholder="Descripción"  type="text" name="descripcion"  id="descripcion" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-12">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Valor </label>
                <input placeholder="Valor"  type="text" name="valor"  id="valor" class="numbersOnly form-control form-control-sm" autocomplete="off" />
                </div>
              </div>


              <div class="col-lg-12">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Unidad </label>
                <input placeholder="Unidad"  type="text" name="unidad"  id="unidad" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

               <div class="col-lg-12">    
                <div class="form-group">    
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Imagen </label>
                  <input type="file" id="imagen" name="imagen" class="form-control-file">
                </div>
              </div> 

            </div>
          </fieldset> 
         
        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>



 

