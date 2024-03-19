<style type="text/css">
  .ejemplo_planilla{
    display: inline;
    cursor: pointer;
    color: #17A2B8;
    margin-top:7px;
  }

  .ver_obs_desp{
    cursor: pointer;
    display: inline;
    margin-left: 5px;
    font-size: 11px;
    color: #2780E3;
  }

  .modal-ejemplo{
    width:60%!important;
  }

  .actualizacion_productividad{
      display: inline-block;
      font-size: 11px;
  }
</style>

<script type="text/javascript">
  $(function(){
    var perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    var user="<?php echo $this->session->userdata('id'); ?>";
    const base = "<?php echo base_url() ?>";

    function Actualizar(){
        $.getJSON(base + "listaTiposMad", {} ,function(data) {
          $('#tipo_m').empty();
          $('#tipo_m').append($('<option>', {
              value: "",
              text: "Seleccione un tipo"
            }));
          $.each(data, function(index, item) {
            $('#tipo_m').append($('<option>', {
              value: item.id,
              text: item.tipo
            }));
          });
        });
      }
    
      Actualizar();


  /**** COMUNA ****/
    /*****DATATABLE*****/   
      var lista_comunas = $('#lista_comunas').DataTable({
        "aaSorting" : [[1,"desc"]],
        "scrollY": "65vh",
        "scrollX": true,
        "sAjaxDataProp": "result",        
        "bDeferRender": true,
        "select" : true,
        "responsive":false,
        // "columnDefs": [{ orderable: false, targets: 0 }  ],
        "ajax": {
            "url":"<?php echo base_url();?>getComunasMadList",
            "dataSrc": function (json) {
              $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
              $(".btn_filtro_detalle").prop("disabled" , false);
              return json;
            },       
            data: function(param){
            }
          },    
        "columns": [
            {
              "class":"centered margen-td","width" : "30px","data": function(row,type,val,meta){
                  btn = "";
                  btn  =`<center><a  href="#!"   data-hash="${row.hash}"  title="Estado" class="btn_editar_comuna" style="font-size:14px!important;"><i class="fas fa-edit"></i> </a>`;
                  if(perfil==1){
                    btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar_comuna rojo"><i class="fa fa-trash"></i></a>';
                  }
                  btn+='</center>';
                  return btn;
              }
            },
            { "data": "id" ,"class":"margen-td centered"},
            { "data": "titulo" ,"class":"margen-td centered"},
          ]
        }); 
    

        $(document).on('keyup paste', '#buscador', function() {
          lista_comunas.search($(this).val().trim()).draw();
        });

        $(document).off('click', '.btn_filtro_detalle').on('click', '.btn_filtro_detalle',function(event) {
          event.preventDefault();
          $(this).prop("disabled" , true);
          $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
          lista_comunas.ajax.reload();
        });


        String.prototype.capitalize = function() {
            return this.charAt(0).toUpperCase() + this.slice(1);
        }

        setTimeout( function () {
          var lista_comunas = $.fn.dataTable.fnTables(true);
          if ( lista_comunas.length > 0 ) {
              $(lista_comunas).dataTable().fnAdjustColumnSizing();
        }}, 200 ); 

        setTimeout( function () {
          var lista_comunas = $.fn.dataTable.fnTables(true);
          if ( lista_comunas.length > 0 ) {
              $(lista_comunas).dataTable().fnAdjustColumnSizing();
        }}, 2000 ); 

        setTimeout( function () {
          var lista_comunas = $.fn.dataTable.fnTables(true);
          if ( lista_comunas.length > 0 ) {
              $(lista_comunas).dataTable().fnAdjustColumnSizing();
          }
        }, 4000 ); 


      

    /*********INGRESO************/

      $(document).off('click', '.btn_nuevo_comuna').on('click', '.btn_nuevo_comuna',function(event) {
          $('#modal_comunas').modal('toggle'); 
          $(".btn_guardar_comuna").html('<i class="fa fa-save"></i> Guardar');
          $(".btn_guardar_comuna").attr("disabled", false);
          $(".cierra_modal_comunas").attr("disabled", false);
          $('#formComunasMad')[0].reset();
          $("#hash_comuna").val("");
          $("#formComunasMad input,#formComunasMad select,#formComunasMad button,#formComunasMad").prop("disabled", false);
      });     

      $(document).off('submit', '#formComunasMad').on('submit', '#formComunasMad',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formComunasMad");
        var formData = new FormData(formElement);
          $.ajax({
              url: $('#formComunasMad').attr('action')+"?"+$.now(),  
              type: 'POST',
              data: formData,
              cache: false,
              processData: false,
              dataType: "json",
              contentType : false,
              beforeSend:function(){
                $(".btn_guardar_comuna").attr("disabled", true);
                $(".cierra_modal_comunas").attr("disabled", true);
                $("#formComunasMad input,#formComunasMad select,#formComunasMad button,#formComunasMad").prop("disabled", true);
              },
              success: function (data) {
              if(data.res == "error"){

                  $(".btn_guardar_comuna").attr("disabled", false);
                  $(".cierra_modal_comunas").attr("disabled", false);

                  $.notify(data.msg, {
                    className:'error',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $("#formComunasMad input,#formComunasMad select,#formComunasMad button,#formComunasMad").prop("disabled", false);

                }else if(data.res == "ok"){
                    $(".btn_guardar_comuna").attr("disabled", false);
                    $(".cierra_modal_comunas").attr("disabled", false);

                    $.notify("Datos ingresados correctamente.", {
                      className:'success',
                      globalPosition: 'top right',
                      autoHideDelay:5000,
                    });
                  
                    $('#modal_comunas').modal("toggle");
                    lista_comunas.ajax.reload();
              }

              $(".btn_guardar_comuna").attr("disabled", false);
              $(".cierra_modal_comunas").attr("disabled", false);
              $("#formComunasMad input,#formComunasMad select,#formComunasMad button,#formComunasMad").prop("disabled", false);
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
                      $('#modal_comunas').modal("toggle");
                  }    
                  return;
              }

              if (xhr.status == 500) {
                  $.notify("Problemas en el servidor, intente más tarde.", {
                    className:'warn',
                    globalPosition: 'top right'
                  });
                  $('#modal_comunas').modal("toggle");
              }
            },timeout:25000
          });
        return false; 
      });

      $(document).off('click', '.btn_editar_comuna').on('click', '.btn_editar_comuna',function(event) {
        event.preventDefault();
        $("#hash_comuna").val("")
        hash=$(this).data("hash")
        $('#formComunasMad')[0].reset()
        $("#hash_comuna").val(hash)
        $('#modal_comunas').modal('toggle')
        $("#formComunasMad input,#formComunasMad select,#formComunasMad button,#formComunasMad").prop("disabled", true)
        $(".btn_guardar_comuna").attr("disabled", true)
        $(".cierra_modal").attr("disabled", true)

        $.ajax({
          url: base+"getDataComunasMad"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          data:{hash:hash},
          dataType:"json",
          beforeSend:function(){
            $(".btn_guardar_comuna").prop("disabled",true); 
            $(".cierra_modal").prop("disabled",true); 
          },
          success: function (data) {

            if(data.res=="ok"){

              for(dato in data.datos){
                $("#hash_comuna").val(data.datos[dato].hash);
                $("#comuna").val(data.datos[dato].titulo);
              }
            
              $("#formComunasMad input,#formComunasMad select,#formComunasMad button,#formComunasMad").prop("disabled", false);
              $(".cierra_modal").prop("disabled", false);
              $(".btn_guardar_comuna").prop("disabled", false);

            }else if(data.res == "sess"){
              window.location="../";
            }

            $(".btn_guardar_comuna").prop("disabled",false); 
            $(".cierra_modal").prop("disabled",false); 
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
                    $('#modal_nuevo_usuario').modal("toggle");
                }    
                return;
            }
            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_comunas').modal("toggle");
            }
          } , timeout:35000
        }) 
      })

      $(document).off('click', '.btn_eliminar_comuna').on('click', '.btn_eliminar_comuna',function(event) {
        hash=$(this).data("hash");
        if(confirm("¿Esta seguro que desea eliminar este registro?")){
          $.post('eliminaComunasMad'+"?"+$.now(),{"hash": hash}, function(data) {

            if(data.res=="ok"){
              $.notify(data.msg, {
                className:'success',
                globalPosition: 'top right'
              })
              lista_comunas.ajax.reload();

            }else{
              $.notify(data.msg, {
                className:'danger',
                globalPosition: 'top right'
              })
            }
          },"json")
        }
      })
      
  /**** TIPO ****/
    /*****DATATABLE*****/   
    var lista_tipos = $('#lista_tipos').DataTable({
       "aaSorting" : [[1,"desc"]],
       "scrollY": "65vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "responsive":false,
       // "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>getTiposMadList",
          "dataSrc": function (json) {
            $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_detalle").prop("disabled" , false);
            return json;
          },       
          data: function(param){
          }
        },    
       "columns": [
          {
            "class":"centered margen-td","width" : "30px","data": function(row,type,val,meta){
                btn = "";
                btn  =`<center><a  href="#!"   data-hash="${row.hash}"  title="Estado" class="btn_editar_tipo" style="font-size:14px!important;"><i class="fas fa-edit"></i> </a>`;
                if(perfil==1){
                  btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar_tipo rojo"><i class="fa fa-trash"></i></a>';
                }
                btn+='</center>';
                return btn;
            }
          },
          { "data": "id" ,"class":"margen-td centered"},
          { "data": "tipo" ,"class":"margen-td centered"},
        ]
      }); 
  

      $(document).on('keyup paste', '#buscador', function() {
        lista_tipos.search($(this).val().trim()).draw();
      });

      $(document).off('click', '.btn_filtro_detalle').on('click', '.btn_filtro_detalle',function(event) {
        event.preventDefault();
         $(this).prop("disabled" , true);
         $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
         lista_tipos.ajax.reload();
      });


      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var lista_tipos = $.fn.dataTable.fnTables(true);
        if ( lista_tipos.length > 0 ) {
            $(lista_tipos).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var lista_tipos = $.fn.dataTable.fnTables(true);
        if ( lista_tipos.length > 0 ) {
            $(lista_tipos).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var lista_tipos = $.fn.dataTable.fnTables(true);
        if ( lista_tipos.length > 0 ) {
            $(lista_tipos).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 


     

    /*********INGRESO************/

      $(document).off('click', '.btn_nuevo_tipo').on('click', '.btn_nuevo_tipo',function(event) {
          $('#modal_tipos').modal('toggle'); 
          $(".btn_guardar_tipo").html('<i class="fa fa-save"></i> Guardar');
          $(".btn_guardar_tipo").attr("disabled", false);
          $(".cierra_modal_tipos").attr("disabled", false);
          $('#formTiposMad')[0].reset();
          $("#hash_tipo").val("");
          $("#formTiposMad input,#formTiposMad select,#formTiposMad button,#formTiposMad").prop("disabled", false);
      });     

      $(document).off('submit', '#formTiposMad').on('submit', '#formTiposMad',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formTiposMad");
        var formData = new FormData(formElement);
          $.ajax({
              url: $('#formTiposMad').attr('action')+"?"+$.now(),  
              type: 'POST',
              data: formData,
              cache: false,
              processData: false,
              dataType: "json",
              contentType : false,
              beforeSend:function(){
                $(".btn_guardar_tipo").attr("disabled", true);
                $(".cierra_modal_tipos").attr("disabled", true);
                $("#formTiposMad input,#formTiposMad select,#formTiposMad button,#formTiposMad").prop("disabled", true);
              },
              success: function (data) {
              if(data.res == "error"){

                  $(".btn_guardar_tipo").attr("disabled", false);
                  $(".cierra_modal_tipos").attr("disabled", false);

                  $.notify(data.msg, {
                    className:'error',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $("#formTiposMad input,#formTiposMad select,#formTiposMad button,#formTiposMad").prop("disabled", false);

                }else if(data.res == "ok"){
                    $(".btn_guardar_tipo").attr("disabled", false);
                    $(".cierra_modal_tipos").attr("disabled", false);

                    $.notify("Datos ingresados correctamente.", {
                      className:'success',
                      globalPosition: 'top right',
                      autoHideDelay:5000,
                    });
                  
                    $('#modal_tipos').modal("toggle");
                    lista_tipos.ajax.reload();
                    Actualizar();
              }

              $(".btn_guardar_tipo").attr("disabled", false);
              $(".cierra_modal_tipos").attr("disabled", false);
              $("#formTiposMad input,#formTiposMad select,#formTiposMad button,#formTiposMad").prop("disabled", false);
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
                      $('#modal_tipos').modal("toggle");
                  }    
                  return;
              }

              if (xhr.status == 500) {
                  $.notify("Problemas en el servidor, intente más tarde.", {
                    className:'warn',
                    globalPosition: 'top right'
                  });
                  $('#modal_tipos').modal("toggle");
              }
            },timeout:25000
          });
        return false; 
      });

      $(document).off('click', '.btn_editar_tipo').on('click', '.btn_editar_tipo',function(event) {
        event.preventDefault();
        $("#hash_tipo").val("")
        hash=$(this).data("hash")
        $('#formTiposMad')[0].reset()
        $("#hash_tipo").val(hash)
        $('#modal_tipos').modal('toggle')
        $("#formTiposMad input,#formTiposMad select,#formTiposMad button,#formTiposMad").prop("disabled", true)
        $(".btn_guardar_tipo").attr("disabled", true)
        $(".cierra_modal").attr("disabled", true)

        $.ajax({
          url: base+"getDataTiposMad"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          data:{hash:hash},
          dataType:"json",
          beforeSend:function(){
            $(".btn_guardar_tipo").prop("disabled",true); 
            $(".cierra_modal").prop("disabled",true); 
          },
          success: function (data) {

            if(data.res=="ok"){

              for(dato in data.datos){
                $("#hash_tipo").val(data.datos[dato].hash);
                $("#tipo").val(data.datos[dato].tipo);
              }
            
              $("#formTiposMad input,#formTiposMad select,#formTiposMad button,#formTiposMad").prop("disabled", false);
              $(".cierra_modal").prop("disabled", false);
              $(".btn_guardar_tipo").prop("disabled", false);

            }else if(data.res == "sess"){
              window.location="../";
            }

            $(".btn_guardar_tipo").prop("disabled",false); 
            $(".cierra_modal").prop("disabled",false); 
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
                    $('#modal_nuevo_usuario').modal("toggle");
                }    
                return;
            }
            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_tipos').modal("toggle");
            }
          } , timeout:35000
        }) 
      })

      $(document).off('click', '.btn_eliminar_tipo').on('click', '.btn_eliminar_tipo',function(event) {
        hash=$(this).data("hash");
        if(confirm("¿Esta seguro que desea eliminar este registro?")){
          $.post('eliminaTiposMad'+"?"+$.now(),{"hash": hash}, function(data) {

            if(data.res=="ok"){
              $.notify(data.msg, {
                className:'success',
                globalPosition: 'top right'
              })
              lista_tipos.ajax.reload();

            }else{
              $.notify(data.msg, {
                className:'danger',
                globalPosition: 'top right'
              })
            }
          },"json")
        }
      })
    
  /**** MOTIVO ****/
    /*****DATATABLE*****/   
    var lista_motivos = $('#lista_motivos').DataTable({
       "aaSorting" : [[1,"desc"]],
       "scrollY": "65vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "responsive":false,
       // "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>getMotivosMadList",
          "dataSrc": function (json) {
            $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_detalle").prop("disabled" , false);
            return json;
          },       
          data: function(param){
          }
        },    
       "columns": [
          {
            "class":"centered margen-td","width" : "30px","data": function(row,type,val,meta){
                btn = "";
                btn  =`<center><a  href="#!"   data-hash="${row.hash}"  title="Estado" class="btn_editar_motivo" style="font-size:14px!important;"><i class="fas fa-edit"></i> </a>`;
                if(perfil==1){
                  btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar_motivo rojo"><i class="fa fa-trash"></i></a>';
                }
                btn+='</center>';
                return btn;
            }
          },
          { "data": "id" ,"class":"margen-td centered"},
          { "data": "tipo" ,"class":"margen-td centered"},
          { "data": "motivo" ,"class":"margen-td centered"},
        ]
      }); 
  

      $(document).on('keyup paste', '#buscador', function() {
        lista_motivos.search($(this).val().trim()).draw();
      });

      $(document).off('click', '.btn_filtro_detalle').on('click', '.btn_filtro_detalle',function(event) {
        event.preventDefault();
         $(this).prop("disabled" , true);
         $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
         lista_motivos.ajax.reload();
      });


      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var lista_motivos = $.fn.dataTable.fnTables(true);
        if ( lista_motivos.length > 0 ) {
            $(lista_motivos).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var lista_motivos = $.fn.dataTable.fnTables(true);
        if ( lista_motivos.length > 0 ) {
            $(lista_motivos).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var lista_motivos = $.fn.dataTable.fnTables(true);
        if ( lista_motivos.length > 0 ) {
            $(lista_motivos).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 


     

    /*********INGRESO************/

      $(document).off('click', '.btn_nuevo_motivo').on('click', '.btn_nuevo_motivo',function(event) {
          $('#modal_motivos').modal('toggle'); 
          $(".btn_guardar_motivo").html('<i class="fa fa-save"></i> Guardar');
          $(".btn_guardar_motivo").attr("disabled", false);
          $(".cierra_modal_motivos").attr("disabled", false);
          $('#formMotivosMad')[0].reset();
          $("#hash_motivo").val("");
          $("#formMotivosMad input,#formMotivosMad select,#formMotivosMad button,#formMotivosMad").prop("disabled", false);
      });     

      $(document).off('submit', '#formMotivosMad').on('submit', '#formMotivosMad',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formMotivosMad");
        var formData = new FormData(formElement);
          $.ajax({
              url: $('#formMotivosMad').attr('action')+"?"+$.now(),  
              type: 'POST',
              data: formData,
              cache: false,
              processData: false,
              dataType: "json",
              contentType : false,
              beforeSend:function(){
                $(".btn_guardar_motivo").attr("disabled", true);
                $(".cierra_modal_motivos").attr("disabled", true);
                $("#formMotivosMad input,#formMotivosMad select,#formMotivosMad button,#formMotivosMad").prop("disabled", true);
              },
              success: function (data) {
              if(data.res == "error"){

                  $(".btn_guardar_motivo").attr("disabled", false);
                  $(".cierra_modal_motivos").attr("disabled", false);

                  $.notify(data.msg, {
                    className:'error',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $("#formMotivosMad input,#formMotivosMad select,#formMotivosMad button,#formMotivosMad").prop("disabled", false);

                }else if(data.res == "ok"){
                    $(".btn_guardar_motivo").attr("disabled", false);
                    $(".cierra_modal_motivos").attr("disabled", false);

                    $.notify("Datos ingresados correctamente.", {
                      className:'success',
                      globalPosition: 'top right',
                      autoHideDelay:5000,
                    });
                  
                    $('#modal_motivos').modal("toggle");
                    lista_motivos.ajax.reload();
              }

              $(".btn_guardar_motivo").attr("disabled", false);
              $(".cierra_modal_motivos").attr("disabled", false);
              $("#formMotivosMad input,#formMotivosMad select,#formMotivosMad button,#formMotivosMad").prop("disabled", false);
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
                      $('#modal_motivos').modal("toggle");
                  }    
                  return;
              }

              if (xhr.status == 500) {
                  $.notify("Problemas en el servidor, intente más tarde.", {
                    className:'warn',
                    globalPosition: 'top right'
                  });
                  $('#modal_motivos').modal("toggle");
              }
            },timeout:25000
          });
        return false; 
      });

      $(document).off('click', '.btn_editar_motivo').on('click', '.btn_editar_motivo',function(event) {
        event.preventDefault();
        $("#hash_motivo").val("")
        hash=$(this).data("hash")
        $('#formMotivosMad')[0].reset()
        $("#hash_motivo").val(hash)
        $('#modal_motivos').modal('toggle')
        $("#formMotivosMad input,#formMotivosMad select,#formMotivosMad button,#formMotivosMad").prop("disabled", true)
        $(".btn_guardar_motivo").attr("disabled", true)
        $(".cierra_modal").attr("disabled", true)

        $.ajax({
          url: base+"getDataMotivosMad"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          data:{hash:hash},
          dataType:"json",
          beforeSend:function(){
            $(".btn_guardar_motivo").prop("disabled",true); 
            $(".cierra_modal").prop("disabled",true); 
          },
          success: function (data) {

            if(data.res=="ok"){

              for(dato in data.datos){
                $("#hash_motivo").val(data.datos[dato].hash);
                $("#tipo").val(data.datos[dato].tipo);
                $("#motivo").val(data.datos[dato].motivo);
              }
            
              $("#formMotivosMad input,#formMotivosMad select,#formMotivosMad button,#formMotivosMad").prop("disabled", false);
              $(".cierra_modal").prop("disabled", false);
              $(".btn_guardar_motivo").prop("disabled", false);

            }else if(data.res == "sess"){
              window.location="../";
            }

            $(".btn_guardar_motivo").prop("disabled",false); 
            $(".cierra_modal").prop("disabled",false); 
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
                    $('#modal_nuevo_usuario').modal("toggle");
                }    
                return;
            }
            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_motivos').modal("toggle");
            }
          } , timeout:35000
        }) 
      })

      $(document).off('click', '.btn_eliminar_motivo').on('click', '.btn_eliminar_motivo',function(event) {
        hash=$(this).data("hash");
        if(confirm("¿Esta seguro que desea eliminar este registro?")){
          $.post('eliminaMotivosMad'+"?"+$.now(),{"hash": hash}, function(data) {

            if(data.res=="ok"){
              $.notify(data.msg, {
                className:'success',
                globalPosition: 'top right'
              })
              lista_motivos.ajax.reload();

            }else{
              $.notify(data.msg, {
                className:'danger',
                globalPosition: 'top right'
              })
            }
          },"json")
        }
      })
    
  })
</script>

<!-- LISTADO -->
  <div class="row">
    <div class="col-lg-6">
      <center>
        <div class="col-lg-3">
          <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_comuna btn_xr3">
            <i class="fa fa-plus-circle"></i>  Crear
          </button>
        </div>
      </center>
      <table id="lista_comunas" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>  
            <th class="centered">Acciones</th>   
            <th class="centered">Id</th> 
            <th class="centered">comuna</th> 
          </tr>
        </thead>
      </table>
    </div>

    <div class="col-lg-6">
      <center>
        <div class="col-lg-3">
          <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_tipo btn_xr3">
            <i class="fa fa-plus-circle"></i>  Crear
          </button>
        </div>
      </center>
      <table id="lista_tipos" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>  
            <th class="centered">Acciones</th>   
            <th class="centered">Id</th> 
            <th class="centered">Tipo</th> 
          </tr>
        </thead>
      </table>
    </div>

    <!--
    <div class="col-lg-4">
      <center>
        <div class="col-lg-3">
          <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_motivo btn_xr3">
            <i class="fa fa-plus-circle"></i>  Crear
          </button>
        </div>
      </center>
      <table id="lista_motivos" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>  
            <th class="centered">Acciones</th>   
            <th class="centered">Id</th> 
            <th class="centered">Tipo</th> 
            <th class="centered">Motivo</th> 
          </tr>
        </thead>
      </table>
    </div>
    -->

  </div>

<!--  FORMULARIO-->
  <div id="modal_comunas" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formComunasMad",array("id"=>"formComunasMad","class"=>"formComunasMad"))?>

    <div class="modal-dialog modal_comunas modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-body">
          <input type="hidden" name="hash_comuna" id="hash_comuna">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro de Comunas</legend>
            <div class="form-row">
              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nombre de comuna</label>         
                <input type="text"  placeholder="Ingrese información adicional" class="form-control form-control-sm"  name="comuna" id="comuna">
                </div>
              </div>
            </div>
          </fieldset> 
        </div>

        <div class="modal-footer" style="border-top: none;">
            <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
              <div class="form-row">

                <div class="col-4 col-lg-3">
                  <button type="submit" class="btn-block btn btn-sm btn-primary btn_guardar_comuna">
                   <i class="fa fa-save"></i> Guardar
                  </button>
                </div>

                <div class="col-4 col-lg-3">
                  <button class="btn-block btn btn-sm btn-secondary cierra_modal_comunas" data-dismiss="modal">
                   <i class="fa fa-window-close"></i> Cerrar
                  </button>
                </div>
              </div>
            </div>
          </div>

      </div>
    </div>
    <?php echo form_close(); ?>
  </div>

  <div id="modal_tipos" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formTiposMad",array("id"=>"formTiposMad","class"=>"formTiposMad"))?>

    <div class="modal-dialog modal_tipos modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-body">
          <input type="hidden" name="hash_tipo" id="hash_tipo">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro de tipos</legend>
            <div class="form-row">
              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nombre de tipo</label>         
                <input type="text"  placeholder="Ingrese información adicional" class="form-control form-control-sm"  name="tipo" id="tipo">
                </div>
              </div>
            </div>
          </fieldset> 
        </div>

        <div class="modal-footer" style="border-top: none;">
            <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
              <div class="form-row">

                <div class="col-4 col-lg-3">
                  <button type="submit" class="btn-block btn btn-sm btn-primary btn_guardar_tipo">
                   <i class="fa fa-save"></i> Guardar
                  </button>
                </div>

                <div class="col-4 col-lg-3">
                  <button class="btn-block btn btn-sm btn-secondary cierra_modal_tipos" data-dismiss="modal">
                   <i class="fa fa-window-close"></i> Cerrar
                  </button>
                </div>
              </div>
            </div>
          </div>

      </div>
    </div>
    <?php echo form_close(); ?>
  </div>

  <div id="modal_motivos" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formMotivosMad",array("id"=>"formMotivosMad","class"=>"formMotivosMad"))?>

    <div class="modal-dialog modal_motivos modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-body">
          <input type="hidden" name="hash_motivo" id="hash_motivo">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro de motivos</legend>
            <div class="form-row">
              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tipo</label>         
                  <select id="tipo_m" name="tipo_m" class="custom-select custom-select-sm" style="width:100%!important;">
                  </select>
                </div>
              </div>
              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nombre de motivo</label>         
                <input type="text"  placeholder="Ingrese información adicional" class="form-control form-control-sm"  name="motivo" id="motivo">
                </div>
              </div>
            </div>
          </fieldset> 
        </div>

        <div class="modal-footer" style="border-top: none;">
            <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
              <div class="form-row">

                <div class="col-4 col-lg-3">
                  <button type="submit" class="btn-block btn btn-sm btn-primary btn_guardar_motivo">
                   <i class="fa fa-save"></i> Guardar
                  </button>
                </div>

                <div class="col-4 col-lg-3">
                  <button class="btn-block btn btn-sm btn-secondary cierra_modal_motivos" data-dismiss="modal">
                   <i class="fa fa-window-close"></i> Cerrar
                  </button>
                </div>
              </div>
            </div>
          </div>

      </div>
    </div>
    <?php echo form_close(); ?>
  </div>