<style type="text/css">
  .borrar_usuario{
    color: red;
    margin-left:10px;
  }

  @media (max-width: 768px){
    .modal_usuario{
      width: 95%!important;
    }
  }

  @media (min-width: 768px){
    .modal_usuario{
      width: 85%!important;
    }
  }

</style>

<script type="text/javascript">
  $(function(){

    const perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    const base = "<?php echo base_url() ?>";

    $('#rut').Rut({
      on_error: function(){ alert('Rut incorrecto'); },
      format_on: 'keyup'
    });

    $(document).off('change', '.file_cs').on('change', '.file_cs',function(event) {
      var myFormData = new FormData();
      myFormData.append('userfile', $('#userfile').prop('files')[0]);

      $.ajax({
          url: "formCargaMasiva"+"?"+$.now(),  
          type: 'POST',
          data: myFormData,
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          processData: false,
          contentType : false,
          dataType:"json",
          beforeSend:function(){
            $(".btn_file_cs").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...');
          },  
          success: function (data) {
             $(".btn_file_cs").html('<span class="glyphicon glyphicon-folder-open" style="margin-right:3px!important;"></span> CSV');
             //activarTodo();
              if(data.res=="ok"){
                $.notify(data.msg, {
                    className:data.tipo,
                    globalPosition: 'top right',
                    autoHideDelay: 20000,
                });

                table.ajax.reload();


              }else{
                $.notify(data.msg, {
                    className:data.tipo,
                    globalPosition: 'top right',
                    autoHideDelay: 5000,
                });
              }
          },
          error : function(xhr, textStatus, errorThrown ) {
            //activarTodo();
            if (textStatus == 'timeout') {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.notify("Reintentando...", {
                      className:'info',
                      globalPosition: 'top right'
                    });
                    $.ajax(this);
                    $(".btn_file_cs").attr('disabled', false);      
                    $(".btn_file_cs").html('<span class="glyphicon glyphicon-folder-open" style="margin-right:3px!important;"></span> Importar');
                    return;
                } else{
                   $.notify("Problemas cargando el archivo, intente nuevamente.", {
                      className:'warn',
                      globalPosition: 'top right'
                    });
                }    
                return;
            }

            if (xhr.status == 500) {
               $.notify("Problemas cargando el archivo, intente nuevamente.", {
                  className:'warn',
                  globalPosition: 'top right'
               });
            $(".btn_file_cs").attr('disabled', false); 
            $(".btn_file_cs").html('<span class="glyphicon glyphicon-folder-open" style="margin-right:3px!important;"></span> Importar series');
            }
        },timeout:120000
      });
    })

  /*****DATATABLE*****/   
    var indexLastColumn = $("#listaUsuarios").find('tr')[0].cells.length-1;

    var listaUsuarios = $('#listaUsuarios').DataTable({
       "order":[[indexLastColumn,'desc']],
       "scrollY": "65vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "responsive" : false,
       "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>listaUsuarios",
          "dataSrc": function (json) {
            $(".btn_filtro_usuarios").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_usuarios").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            val="";
            if ($('.check_estado').is(":checked")){
              estado=0;
            }else{
              estado=1;
            }
            param.estado = estado;
          }
        },    
       "columns": [
          {
           "class":"centered center margen-td","data": function(row,type,val,meta){
              btn='<center><a data-toggle="modal" href="#modal_usuario" data-hash_usuario="'+row.hash_usuario+'" data-placement="top" data-toggle="tooltip" title="Modificar" class="fa fa-edit btn_modificar_usuario"></a>';
              
              if(perfil==1 || perfil==2){
                btn+='<a href="#" data-placement="top" data-toggle="tooltip" title="Eliminar" class="fa fa-trash borrar_usuario" data-hash_usuario="'+row.hash_usuario+'"></a></center>';
              }
              
              return btn;
            }
          },
          { "data": "nombres" ,"class":"margen-td centered"},
          { "data": "apellidos" ,"class":"margen-td centered"},
          { "data": "empresa" ,"class":"margen-td centered"},
          {
            "class":"centered","data": function(row,type,val,meta){
              if(row.foto!=""){
                html='<center><a href="'+base+"fotos_usuarios/"+row.foto+'" class="fa fa-file" title="Foto" target="_blank"></a></center>';
              }else{
                html="";
              }
              return html;
           }
          },
          { "data": "rut" ,"class":"margen-td centered"},
          { "data": "sexo" ,"class":"margen-td centered"},
          { "data": "nacionalidad" ,"class":"margen-td centered"},
          { "data": "estado_civil" ,"class":"margen-td centered"},
          { "data": "cargo" ,"class":"margen-td centered"},
          { "data": "area" ,"class":"margen-td centered"},
          { "data": "subzona" ,"class":"margen-td centered"}, 
          { "data": "plaza" ,"class":"margen-td centered"},
          { "data": "proyecto" ,"class":"margen-td centered"},
          { "data": "jefe" ,"class":"margen-td centered"},
          { "data": "tipo_contrato" ,"class":"margen-td centered"},
          { "data": "codigo" ,"class":"margen-td centered"},
          { "data": "nivel_tecnico" ,"class":"margen-td centered"},
          { "data": "domicilio" ,"class":"margen-td centered"},
          { "data": "comuna" ,"class":"margen-td centered"},
          { "data": "ciudad" ,"class":"margen-td centered"},
          { "data": "celular_empresa","class":"margen-td centered"},
          { "data": "celular_personal","class":"margen-td centered"},
          { "data": "correo_empresa" ,"class":"margen-td centered"},
          { "data": "correo_personal" ,"class":"margen-td centered"},
          { "data": "fecha_nacimiento" ,"class":"margen-td centered"},
          { "data": "fecha_ingreso" ,"class":"margen-td centered"},
          { "data": "fecha_salida" ,"class":"margen-td centered"},
          { "data": "perfil" ,"class":"margen-td centered"},
          { "data": "talla_zapato" ,"class":"margen-td centered"},
          { "data": "talla_pantalon" ,"class":"margen-td centered"},
          { "data": "talla_polera" ,"class":"margen-td centered"},
          { "data": "talla_cazadora" ,"class":"margen-td centered"},
          { "data": "estado_str" ,"class":"margen-td centered"},
          { "data": "ultima_actualizacion" ,"class":"margen-td centered"},
        ]
      }); 
  

      $(document).on('keyup paste', '#buscador_ots', function() {
        listaUsuarios.search($(this).val().trim()).draw();
      });

      $(document).off('click', '.btn_filtro_usuarios').on('click', '.btn_filtro_usuarios',function(event) {
        event.preventDefault();
         $(this).prop("disabled" , true);
         $(".btn_filtro_usuarios").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
         listaUsuarios.ajax.reload();
      });


      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var listaUsuarios = $.fn.dataTable.fnTables(true);
        if ( listaUsuarios.length > 0 ) {
            $(listaUsuarios).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var listaUsuarios = $.fn.dataTable.fnTables(true);
        if ( listaUsuarios.length > 0 ) {
            $(listaUsuarios).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var listaUsuarios = $.fn.dataTable.fnTables(true);
        if ( listaUsuarios.length > 0 ) {
            $(listaUsuarios).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 


  /*********INGRESO************/

    $(document).off('click', '.btn_nuevo_ots').on('click', '.btn_nuevo_ots',function(event) {
        $('#modal_usuario').modal('toggle'); 
        $(".btn_guardar_usuario").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_guardar_usuario").attr("disabled", false);
        $(".cierra_modal_usuario").attr("disabled", false);
        $(".eliminar_modal_usuario").attr("disabled", false);
        $('#formUsuario')[0].reset();
        $("#hash_usuario").val("");
        $("#formUsuario input,#formUsuario select,#formUsuario button,#formUsuario").prop("disabled", false);
        $(".cont_edit").hide();
    });     

    $(document).off('submit', '#formUsuario').on('submit', '#formUsuario',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formUsuario");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formUsuario').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_usuario").attr("disabled", true);
              $(".cierra_modal_usuario").attr("disabled", true);
              $(".eliminar_modal_usuario").attr("disabled", true);
              $("#formUsuario input,#formUsuario select,#formUsuario button,#formUsuario").prop("disabled", true);
            },
            success: function (data) {
             if(data.res == "error"){

                $(".btn_guardar_usuario").attr("disabled", false);
                $(".cierra_modal_usuario").attr("disabled", false);
                $(".eliminar_modal_usuario").attr("disabled", false);

                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $("#formUsuario input,#formUsuario select,#formUsuario button,#formUsuario").prop("disabled", false);

              }else if(data.res == "ok"){
                  $(".btn_guardar_usuario").attr("disabled", false);
                  $(".cierra_modal_usuario").attr("disabled", false);
                  $(".eliminar_modal_usuario").attr("disabled", false);

                  $.notify("Datos ingresados correctamente.", {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                
                  $('#modal_usuario').modal("toggle");
                  listaUsuarios.ajax.reload();
            }

            $(".btn_guardar_usuario").attr("disabled", false);
            $(".cierra_modal_usuario").attr("disabled", false);
            $(".eliminar_modal_usuario").attr("disabled", false);
            $("#formUsuario input,#formUsuario select,#formUsuario button,#formUsuario").prop("disabled", false);
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
                    $('#modal_usuario').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_usuario').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

   $(document).off('click', '.btn_modificar_usuario').on('click', '.btn_modificar_usuario',function(event) {
      $(".cont_edit").show();
      $("#hash_usuario").val("");
      hash_usuario = $(this).attr("data-hash_usuario");
      $("#hash_usuario").val(hash_usuario);
        
      $.ajax({
        url: "getDataUsuarios"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash_usuario : hash_usuario},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardar_usuario").attr("disabled", true);
          $(".cierra_modal_usuario").attr("disabled", true);
          $(".eliminar_modal_usuario").attr("disabled", true);
          $("#formUsuario input,#formUsuario select,#formUsuario button,#formUsuario").prop("disabled", true);
        },
        success: function (data) {
          $(".btn_guardar_usuario").attr("disabled", false);
          $(".cierra_modal_usuario").attr("disabled", false);
          $(".eliminar_modal_usuario").attr("disabled", false);
          $("#formUsuario input,#formUsuario select,#formUsuario button,#formUsuario").prop("disabled", false);
        
          if(data.res=="ok"){
            for(dato in data.datos){
              $("#nombres").val(data.datos[dato].nombres);
              $("#apellidos").val(data.datos[dato].apellidos);
              $("#empresa").val(data.datos[dato].empresa);
              $("#rut").val(data.datos[dato].rut);
              $("#nacionalidad").val(data.datos[dato].nacionalidad);
              $("#domicilio").val(data.datos[dato].domicilio);
              $("#comuna").val(data.datos[dato].comuna);
              $("#ciudad").val(data.datos[dato].ciudad);
              $("#subzona").val(data.datos[dato].subzona);  
              $("#celular_empresa").val(data.datos[dato].celular_empresa);
              $("#celular_personal").val(data.datos[dato].celular_personal);
              $("#correo_empresa").val(data.datos[dato].correo_empresa);
              $("#correo_personal").val(data.datos[dato].correo_personal);
              $("#fecha_nacimiento").val(data.datos[dato].fecha_nacimiento);
              $("#fecha_ingreso").val(data.datos[dato].fecha_ingreso);
              $("#codigo").val(data.datos[dato].codigo);
              $("#sexo  option[value='"+data.datos[dato].sexo+"'").prop("selected", true);
              $("#estado_civil  option[value='"+data.datos[dato].estado_civil+"'").prop("selected", true);
              $("#perfil  option[value='"+data.datos[dato].id_perfil+"'").prop("selected", true);
              $("#nivel_tecnico  option[value='"+data.datos[dato].id_nivel_tecnico+"'").prop("selected", true);
              $("#cargo  option[value='"+data.datos[dato].id_cargo+"'").prop("selected", true);
              $("#area  option[value='"+data.datos[dato].id_area+"'").prop("selected", true);
              $("#plaza  option[value='"+data.datos[dato].id_plaza+"'").prop("selected", true);
              $("#proyecto  option[value='"+data.datos[dato].id_proyecto+"'").prop("selected", true);
              $("#jefe  option[value='"+data.datos[dato].id_jefe+"'").prop("selected", true);
              $("#estado  option[value='"+data.datos[dato].estado+"'").prop("selected", true);
              $("#zapato  option[value='"+data.datos[dato].talla_zapato+"'").prop("selected", true);
              $("#pantalon  option[value='"+data.datos[dato].talla_pantalon+"'").prop("selected", true);
              $("#polera  option[value='"+data.datos[dato].talla_polera+"'").prop("selected", true);
              $("#cazadora  option[value='"+data.datos[dato].talla_cazadora+"'").prop("selected", true);
              $("#tipo_contrato  option[value='"+data.datos[dato].id_tipo_contrato+"'").prop("selected", true);
              
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
                  $('#modal_usuario').modal("toggle");
              }    
              return;
          }

          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
              $('#modal_usuario').modal("toggle");
          }
        },timeout:25000
      }); 
    });

    /********OTROS**********/
    
    $(document).off('change', '#estado').on('change', '#estado',function(event) {
      if($(this).val()=="0"){
        $(".salida_cont").show()
      }else{
        $(".salida_cont").hide()
      }
    });

    $(document).off('change', '#check_estado').on('change', '#check_estado',function(event) {
      $(".btn_filtro_usuarios").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando').prop("disabled" , true);
      listaUsuarios.ajax.reload()
    });

    $(document).off('click', '.excel_usuarios').on('click', '.excel_usuarios',function(event) {
      event.preventDefault();
       
      if ($('.check_estado').is(":checked")){
        estado=0;
      }else{
        estado=1;
      }

      window.location="excelUsuarios/"+estado;
    });


    $(document).off('click', '.borrar_usuario').on('click', '.borrar_usuario',function(event) {
        var hash_usuario=$(this).attr("data-hash_usuario");
          if(confirm("¿Esta seguro que desea eliminar este registro?")){

            $.post('eliminaUsuario'+"?"+$.now(),{"hash_usuario": hash_usuario}, function(data) {

              if(data.res=="ok"){

                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });

                listaUsuarios.ajax.reload();

              }else{
                $.notify(data.msg, {
                  className:'danger',
                  globalPosition: 'top right'
                });
              }

            },"json");
        }



    });
    

  })

 

</script>

<!-- FILTROS -->
  
  <div class="form-row">

    <!-- <div class="col-xs-6 col-sm-6 col-md-1 col-lg-1 no-padding">  
       <input type="file" id="userfile" name="userfile" class="file_cs" style="display:none;" />
       <button type="button" class="allwidth btn btn-danger btn-sm btn_file_cs" value="" onclick="document.getElementById('userfile').click();">
       <span class="glyphicon glyphicon-folder-open" style="margin-right:5px!important;"></span> CSV</button>
    </div> -->

    <div class="col-6 col-lg-1">  
      <div class="form-group">
         <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_ots btn_xr3">
         <i class="fa fa-plus-circle"></i>  Crear 
         </button>
      </div>
    </div>

    <div class="col-6 col-lg-3">  
     <div class="form-group">
      <input type="text" placeholder="Busqueda" id="buscador_ots" class="buscador_ots form-control form-control-sm">
     </div>
    </div>

    <div class="col-12 col-lg-2">  
      <div class="custom-control custom-checkbox" style="margin-top: 4px;">
        <input type="checkbox"  class="custom-control-input check_estado" id="check_estado" name="check_estado">
        <label class="custom-control-label" for="check_estado">Incluir inactivos</label>
      </div>
    </div>

    <div class="col-6 col-lg-1">
      <div class="form-group">
       <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_usuarios btn_xr3">
       <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
       </button>
     </div>
    </div>

    <div class="col-6 col-lg-1">  
      <div class="form-group">
       <button type="button"  class="btn-block btn btn-sm btn-primary excel_usuarios btn_xr3">
       <i class="fa fa-save"></i> Excel
       </button>
      </div>
    </div>
    
    </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="listaUsuarios" class="tablaUsuarios table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <th class="centered" style="width: 50px;">Acciones</th>    
            <th class="centered">Nombre</th> 
            <th class="centered">Apellidos</th> 
            <th class="centered">Empresa</th> 
            <th class="centered">Foto</th> 
            <th class="centered">Rut</th> 
            <th class="centered">Sexo</th> 
            <th class="centered">Nacionalidad</th> 
            <th class="centered">Estado civil</th> 
            <th class="centered">Cargo</th> 
            <th class="centered">Zona</th> 
            <th class="centered">Subzona</th> 
            <th class="centered">Plaza</th> 
            <th class="centered">Proyecto</th> 
            <th class="centered">Jefe</th> 
            <th class="centered">Tipo contrato</th> 
            <th class="centered">Código</th> 
            <th class="centered">Nivel Técnico</th> 
            <th class="centered">Domicilio</th> 
            <th class="centered">Comuna</th> 
            <th class="centered">Cuidad</th> 
            <th class="centered">Celular empresa</th> 
            <th class="centered">Celular personal</th> 
            <th class="centered">Correo empresa</th> 
            <th class="centered">Correo personal</th> 
            <th class="centered">Fecha nacimiento</th> 
            <th class="centered">Fecha ingreso</th> 
            <th class="centered">Fecha salida</th> 
            <th class="centered">Perfil</th> 
            <th class="centered">Talla zapato</th> 
            <th class="centered">Talla pantalon</th> 
            <th class="centered">Talla polera</th>   
            <th class="centered">Talla cazadora</th>   
            <th class="centered">Estado</th>   
            <th class="centered">Última actualización</th>   
          </tr>
        </thead>
      </table>
    </div>
  </div>

<!--  FORMULARIO-->
  <div id="modal_usuario" data-backdrop="static"  data-keyboard="false"   class="modal fade">
    <?php echo form_open_multipart("formUsuario",array("id"=>"formUsuario","class"=>"formUsuario"))?>

    <div class="modal-dialog modal_usuario modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header">
          <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
            <div class="form-row">
              <div class="col-9 col-lg-6">
                  <button type="submit" class="btn-block btn btn-sm btn-primary btn_guardar_usuario">
                   <i class="fa fa-save"></i> Guardar
                  </button>
              </div>
              <div class="col-3 col-lg-6">
                <button class="btn-block btn btn-sm btn-secondary cierra_modal_usuario" data-dismiss="modal" aria-hidden="true">
               <!--   <i class="fa fa-window-close"></i>  -->Cerrar
                </button>
              </div>
              <!--
              <div class="col-3 col-lg-6 cont_edit">
                <button class="btn-block btn btn-sm btn-danger eliminar_modal_usuario" data-dismiss="modal" aria-hidden="true">Eliminar</button>
              </div>
              -->
            </div>
          </div>
        </div>

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <input type="hidden" name="hash_usuario" id="hash_usuario">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro Usuarios </legend>

            <div class="form-row">
              
              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nombres </label>
                <input placeholder="Nombres"  type="text" name="nombres"  id="nombres" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Apellidos </label>
                <input placeholder="Apellidos"  type="text" name="apellidos"  id="apellidos" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Empresa </label>
                <input placeholder="Empresa"  type="text" name="empresa"  id="empresa" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>


              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">RUT </label>
                <input placeholder="RUT"  type="text" name="rut"  id="rut" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Sexo </label>
                  <select id="sexo" name="sexo" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                  </select>
                <!-- <input placeholder="Sexo"  type="text" name="sexo"  id="sexo" class="form-control form-control-sm" autocomplete="off" /> -->
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nacionalidad </label>
                <input placeholder="Nacionalidad"  type="text" name="nacionalidad"  id="nacionalidad" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Estado civil </label>
                <!-- <input placeholder="Estado civil"  type="text" name="estado_civil"  id="estado_civil" class="form-control form-control-sm" autocomplete="off" /> -->
                  <select id="estado_civil" name="estado_civil" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <option value="Casado(a)">Casado(a)</option>
                    <option value="Soltero(a)">Soltero(a)</option>
                    <option value="Divorciado(a)">Divorciado(a)</option>
                    <option value="Unión Civil">Unión Civil</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">               
                <div class="form-group">
                 <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Cargo  </label>
                  <select id="cargo" name="cargo" class="custom-select custom-select-sm">
                  <option value="" selected>Seleccione </option>
                      <?php 
                      foreach($cargos as $c){
                        ?>
                          <option value="<?php echo $c["id"]; ?>"><?php echo $c["cargo"]; ?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">               
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Zona </label>
                  <select id="area" name="area" class="custom-select custom-select-sm">
                  <option value="" selected>Seleccione </option>
                      <?php 
                      foreach($areas as $a){
                        ?>
                          <option value="<?php echo $a["id"]; ?>"><?php echo $a["area"]; ?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>
              
              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Subzona  </label>
                <input placeholder="Subzona"  type="text" name="subzona"  id="subzona" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-3">               
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Plaza</label>
                  <select id="plaza" name="plaza" class="custom-select custom-select-sm">
                  <option value="" selected>Seleccione </option>
                      <?php 
                      foreach($plazas as $pl){
                        ?>
                          <option value="<?php echo $pl["id"]; ?>"><?php echo $pl["plaza"]; ?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">               
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Proyecto </label>
                  <select id="proyecto" name="proyecto" class="custom-select custom-select-sm">
                  <option value="" selected>Seleccione </option>
                      <?php 
                      foreach($proyectos as $p){
                        ?>
                          <option value="<?php echo $p["id"]; ?>"><?php echo $p["proyecto"]; ?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">               
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Jefe </label>
                  <select id="jefe" name="jefe" class="custom-select custom-select-sm">
                  <option value="" selected>Seleccione </option>
                      <?php 
                      foreach($jefes as $j){
                        ?>
                          <option value="<?php echo $j["id_jefe"]; ?>"><?php echo $j["nombre_jefe"]; ?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">               
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tipo contrato </label>
                  <select id="tipo_contrato" name="tipo_contrato" class="custom-select custom-select-sm">
                  <option value="" selected>Seleccione </option>
                      <?php 
                      foreach($tipos_contrato as $tc){
                        ?>
                        <option value="<?php echo $tc["id"]; ?>"><?php echo $tc["tipo"]; ?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">               
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nivel técnico</label>
                  <select id="nivel_tecnico" name="nivel_tecnico" class="custom-select custom-select-sm">
                  <option value="" selected>Seleccione </option>
                      <?php 
                      foreach($nivelesTecnicos as $n){
                        ?>
                          <option value="<?php echo $n["id"]; ?>"><?php echo $n["nivel"]; ?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>


              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Código </label>
                <input placeholder="Código"  type="text" name="codigo"  id="codigo" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Domicilio </label>
                <input placeholder="Domicilio"  type="text" name="domicilio"  id="domicilio" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>
              

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Comuna  </label>
                <input placeholder="Comuna"  type="text" name="comuna"  id="comuna" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Ciudad </label>
                <input placeholder="Ciudad"  type="text" name="ciudad"  id="ciudad" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>


              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Celular empresa </label>
                <input placeholder="Celular empresa"  type="text" name="celular_empresa"  id="celular_empresa" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Celular personal</label>
                <input placeholder="Celular personal"  type="text" name="celular_personal"  id="celular_personal" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Correo empresa</label>
                <input placeholder="Correo empresa"  type="email" name="correo_empresa"  id="correo_empresa" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Correo personal</label>
                <input placeholder="Correo personal"  type="email" name="correo_personal"  id="correo_personal" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha nacimiento </label>
                <input placeholder="Fecha nacimiento"  type="date" name="fecha_nacimiento"  id="fecha_nacimiento" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha ingreso </label>
                <input placeholder="Fecha ingreso"  type="date" name="fecha_ingreso"  id="fecha_ingreso" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>


              <div class="col-lg-3">               
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Perfil </label>
                  <select id="perfil" name="perfil" class="custom-select custom-select-sm">
                  <option value="" selected>Seleccione </option>
                      <?php 
                      foreach($perfiles as $pe){

                        if($this->session->userdata('id_perfil')<>1 and $pe["id"]==1){
                          ?>
                            <option style="display:none;" value="<?php echo $pe["id"]; ?>"><?php echo $pe["perfil"]; ?></option>
                          <?php
                        }else{
                          ?>
                            <option value="<?php echo $pe["id"]; ?>"><?php echo $pe["perfil"]; ?></option>
                          <?php
                        }
                        ?>
                          
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>

           

              <div class="col-lg-3">
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Talla Zapato</label>
                <select id="zapato" name="zapato" class="custom-select custom-select-sm">
                  <option value="">Seleccione </option>
                   <?php 
                    for($i=34; $i<=48;$i++){
                      ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                      <?php
                    }
                   ?>
                </select>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Talla Pantalón</label>
                 <select id="pantalon" name="pantalon" class="custom-select custom-select-sm">
                  <option value="">Seleccione </option>
                    <?php 
                      for($i=36; $i<=56;$i+=2){
                        ?>
                          <option value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php
                      }
                     ?>
                </select>
                </div>
              </div>

              <div class="col-lg-2">
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Talla Polera</label>
                <select id="polera" name="polera" class="custom-select custom-select-sm">
                  <option value="">Seleccione </option>
                  <option value="xs">XS</option>
                  <option value="s">S</option>
                  <option value="m">M</option>
                  <option value="l">L</option>
                  <option value="xl">XL</option>
                  <option value="xxl">XXL</option>
                  <option value="xxxl">XXXL</option>
                </select>
                </div>
              </div>

              <div class="col-lg-2">
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Talla cazadora</label>
                <select id="cazadora" name="cazadora" class="custom-select custom-select-sm">
                  <option value="">Seleccione </option>
                  <option value="xs">XS</option>
                  <option value="s">S</option>
                  <option value="m">M</option>
                  <option value="l">L</option>
                  <option value="xl">XL</option>
                  <option value="xxl">XXL</option>
                  <option value="xxxl">XXXL</option>
                </select>
                </div>
              </div>

              <div class="col-lg-2">
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Estado</label>
                <select id="estado" name="estado" class="custom-select custom-select-sm">
                  <option value="">Seleccione</option>
                  <option value="1" selected>Activo</option>
                  <option value="0">No activo</option>
                </select>
                </div>
              </div>


              <div class="col-lg-2 salida_cont" style="display: none">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha salida </label>
                <input placeholder="Fecha salida"  type="date" name="fecha_salida"  id="fecha_salida" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>


              <div class="col-lg-6">    
                <div class="form-group">    
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Foto </label>
                  <input type="file" id="foto" name="foto" class="form-control-file">
                </div>
              </div> 

            </div>
          </fieldset> 
        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>



 

