<style type="text/css">

  #contenedor_imagen{
    display: none;
  }
  /*  .cr-boundary{
    width:100%!important;
  } */
  .btn_eliminar{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
  }
  .btn_editar{
      display: inline-block;
      text-align: center!important;
      margin:0 auto!important;
      font-size: 15px!important;
  }

  @media(min-width: 768px){
    .modal_ingreso{
      width: 65%!important;
    }
  }

  @media(max-width: 768px){
    .modal_ingreso{
      width: 95%!important;
    }
  }
</style>

<script type="text/javascript" charset="utf-8"> 
  $(function(){ 
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    
    window.addEventListener('modoOscuroActivado', function() {
      activarTymce("oscuro")
      console.log('Modo oscuro activado desde otro archivo');
    });

    window.addEventListener('modoClaroActivado', function() {
      activarTymce("claro")
      console.log('Modo claro activado desde otro archivo');
    });

    /*****DATATABLE*****/  

      base_url = "<?php echo base_url() ?>";
      tipo = "<?php echo $tipo ?>";
      const p = "<?php echo $this->session->userdata('id_perfil') ?>";

      var tb = $('#tb').DataTable({
         "responsive" :false,
         "aaSorting" : [[7,"desc"]],
         "scrollY": "65vh",
         "scrollX": true,
         "sAjaxDataProp": "result",        
         "bDeferRender": true,
         "select" : true,
         columnDefs: [
          { orderable: false, targets: 0 },
          { orderable: false, targets: 1 },
          { orderable: false, targets: 2 }
         ],
          "ajax": {
            "url":"<?php echo base_url();?>getListPrevencionModulos",
            "dataSrc": function (json) {
              $(".btn_filtro").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
              $(".btn_filtro").prop("disabled" , false);
              return json;
            },       
            data: function(param){
              param.tipo = tipo
            }
          },    
          "columns": [
            {
              "class":"centered margen-td","width" : "5%" , "data": function(row,type,val,meta){
                btn = "";
                if(p<=2){
                 btn+='<center><a href="#!" title="Modificar" data-hash="'+row.hash_id+'" class="btn_editar"><i class="fa fa-edit"></i></a>';
                 btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash_id+'" class="btn_eliminar rojo"><i class="fa fa-trash"></i></a></center>';
                }
                return btn;
              }
            },
            {
              "class":"centered margen-td",  "width" : "5%","data": function(row,type,val,meta){
                botonera="";
                if(row.archivo!=null && row.archivo!=""){
                  botonera+='<center><a title="Ver archivo" style="margin-left:10px;" target="_blank" href="'+base_url+'archivos/prevencion_modulos/'+row.archivo+'"><i class="fa fa-file"></i></a></center>';
                }else{
                  botonera="<center>-</center>";
                }
                return botonera;
              }
            },

            {
              "class":"centered margen-td",  "width" : "5%","data": function(row,type,val,meta){
                return Object.entries(row)
                .filter(([key, value]) => key.startsWith('link') && value)
                .map(([key, value], i) => `<a title="Ver archivo" style="margin-left:10px;" target="_blank" href="${base_url}archivos/prevencion_modulos/${value}"><i class="fa fa-file-pdf"></i></a>`)
                .join('');
              }
            },

            { "data": "titulo" ,"class":"margen-td centered"},
            { "data": "descripcion" ,"width" : "25%" ,"class":"margen-td centered"},
            { "data": "fecha" ,"class":"margen-td centered"},
            { "data": "autor" ,"class":"margen-td centered"},
            { "data": "ultima_actualizacion" ,"class":"margen-td centered"},

          ]
      }); 


      $(document).on('keyup paste', '#buscador', function() {
        tb.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var tb = $.fn.dataTable.fnTables(true);
        if ( tb.length > 0 ) {
            $(tb).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var tb = $.fn.dataTable.fnTables(true);
        if ( tb.length > 0 ) {
            $(tb).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var tb = $.fn.dataTable.fnTables(true);
        if ( tb.length > 0 ) {
            $(tb).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 

    /*****DATATABLE*****/  

      $(document).off('click', '.btn_filtro').on('click', '.btn_filtro',function(event) {
      event.preventDefault();
        $(this).prop("disabled" , true);
        $(".btn_filtro").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
        tb.ajax.reload();
      });

      /*****FORM*****/  

        $(document).off('click', '.btn_nuevo_prevencion').on('click', '.btn_nuevo_prevencion', function(event) {
            $('#formIngreso')[0].reset();
            $("#hash_id").val("");
            $('#modal_ingreso').modal('toggle'); 
            $("#formIngreso input,#formIngreso select,#formIngreso button,#formIngreso").prop("disabled", false);
            $(".btn_ingreso").attr("disabled", false);
            $(".cierra_modal").attr("disabled", false);
            $("#contenedor_imagen").hide();
            $("#link1_form").html("").hide()
            $("#link2_form").html("").hide()
            $("#link3_form").html("").hide()
            $("#principal_form").html("").hide()

            window.addEventListener('modoOscuroActivado', function() {
              activarTymce("oscuro")
              console.log('Modo oscuro activado desde otro archivo');
            });

            window.addEventListener('modoClaroActivado', function() {
              activarTymce("claro")
              console.log('Modo claro activado desde otro archivo');
            });
        });

        $(document).off('submit', '#formIngreso').on('submit', '#formIngreso',function(event) {
            var url="<?php echo base_url()?>";
            var formElement = document.querySelector("#formIngreso");
            var formData = new FormData(formElement);

            $image_crop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(response){

            formData.append("imagen", response);
              $.ajax({
                url: $('#formIngreso').attr('action')+"?"+$.now(),  
                type: 'POST',
                data: formData,
                cache: false,
                processData: false,
                dataType: "json",
                contentType : false,
                beforeSend:function(){
                  /*  $(".btn_ingreso").attr("disabled", true);
                  $(".cierra_modal").attr("disabled", true);
                  $("#formIngreso input,#formIngreso select,#formIngreso button,#formIngreso").prop("disabled", true);
                  $(".btn_ingreso").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...').prop("disabled",true); */
                },
                success: function (data) {
                  if(data.res == "sess"){
                    window.location="unlogin";

                  }else if(data.res=="ok"){
                    $('#modal_ingreso').modal('toggle'); 
                    $("#formIngreso input,#formIngreso select,#formIngreso button,#formIngreso").prop("disabled", false);
                    $(".btn_ingreso").attr("disabled", false);
                    $(".cierra_modal").attr("disabled", false);
                    $(".btn_ingreso").html(' <i class="fa fa-save"></i> Guardar').prop("disabled",true);

                    $.notify(data.msg, {  
                      className:'success',
                      globalPosition: 'top right',
                      autoHideDelay:5000,
                    });

                    $('#formIngreso')[0].reset();
                    tb.ajax.reload();
                  }else if(data.res=="error"){
                    $(".btn_ingreso").html(' <i class="fa fa-save"></i> Guardar').prop("disabled",true);
                    $(".btn_ingreso").attr("disabled", false);
                    $(".cierra_modal").attr("disabled", false);
                    $.notify(data.msg, {
                      className:'error',
                      globalPosition: 'top right',
                      autoHideDelay:5000,
                    });
                    $("#formIngreso input,#formIngreso select,#formIngreso button,#formIngreso").prop("disabled", false);

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
                          $('#modal_ingreso').modal("toggle");
                      }    
                      return;
                  }

                  if (xhr.status == 500) {
                      $.notify("Problemas en el servidor, intente más tarde.", {
                        className:'warn',
                        globalPosition: 'top right'
                      });
                      $('#modal_ingreso').modal("toggle");
                  }
              },timeout:235000
            }); 
          });
          return false; 
        });

        $(document).off('click', '.btn_editar').on('click', '.btn_editar',function(event) {
            event.preventDefault();
            hash_id=$(this).attr("data-hash");
            $('#formIngreso')[0].reset();
            $("#hash_id").val("");
            $("#hash_id").val(hash_id);
            $('#modal_ingreso').modal('toggle'); 
            $("#formIngreso input,#formIngreso select,#formIngreso button,#formIngreso").prop("disabled", true);
            $(".btn_ingreso").attr("disabled", true);
            $(".cierra_modal").attr("disabled", true);
            $("#contenedor_imagen").hide();

            $.ajax({
              url: base_url+"getDataPrevencionModulos"+"?"+$.now(),  
              type: 'POST',
              cache: false,
              tryCount : 0,
              retryLimit : 3,
              data:{hash_id:hash_id},
              dataType:"json",
              beforeSend:function(){
              $(".btn_ingreso").prop("disabled",true); 
              $(".cierra_modal").prop("disabled",true); 
              },
              success: function (data) {
                if(data.res=="ok"){
                  for(dato in data.datos){
                      $("#hash_id").val(data.datos[dato].hash_id);
                      $("#titulo").val(data.datos[dato].titulo);
                      tinymce.get('descripcion').setContent(data.datos[dato].descripcion);

                      if(data.datos[dato].archivo!=""){
                        $("#principal_form").html(`<a title="Ver archivo principal" style="margin-left:10px;" target="_blank" href="${base_url}archivos/prevencion_modulos/${data.datos[dato].archivo}"><i class="fa fa-file"></i></a>`).show()
                      }

                      if(data.datos[dato].link1!=""){
                        $("#link1_form").html(`<a title="Ver archivo 1" style="margin-left:10px;" target="_blank" href="${base_url}archivos/prevencion_modulos/${data.datos[dato].link1}"><i class="fa fa-file"></i></a>`).show()
                      }

                      if(data.datos[dato].link2!=""){
                        $("#link2_form").html(`<a title="Ver archivo 2" style="margin-left:10px;" target="_blank" href="${base_url}archivos/prevencion_modulos/${data.datos[dato].link2}"><i class="fa fa-file"></i></a>`).show()
                      }

                      if(data.datos[dato].link3!=""){
                        $("#link3_form").html(`<a title="Ver archivo 3" style="margin-left:10px;" target="_blank" href="${base_url}archivos/prevencion_modulos/${data.datos[dato].link3}"><i class="fa fa-file"></i></a>`).show()
                      }

                  }

                  $("#formIngreso input,#formIngreso select,#formIngreso button,#formIngreso").prop("disabled", false);
                  $(".cierra_modal").prop("disabled", false);
                  $(".btn_ingreso").prop("disabled", false);

                }else if(data.res == "sess"){
                  window.location="../";
                }

                $(".btn_ingreso").prop("disabled",false); 
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
                    $('#modal_ingreso').modal("toggle");
                }
            },timeout:135000
          }); 
        });

        $(document).off('click', '.btn_eliminar').on('click', '.btn_eliminar',function(event) {
            hash=$(this).data("hash");
            if(confirm("¿Esta seguro que desea eliminar este registro?")){
                $.post(base_url+'eliminarPrevencionModulos'+"?"+$.now(),{"hash": hash}, function(data) {
                  
                  if(data.res=="ok"){

                    $.notify(data.msg, {
                      className:'success',
                      globalPosition: 'top right',
                      autoHideDelay:5000
                    });
                  tb.ajax.reload();

                  }else{

                    $.notify(data.msg, {
                      className:'danger',
                      globalPosition: 'top right'
                    });
                    
                  }

                },"json");
              }
        });
    
    /*****OTROS*****/  

      async function activarTymce(tema) {
        // Eliminar la instancia actual de TinyMCE si existe
        if (tinymce.activeEditor) {
          tinymce.remove(tinymce.activeEditor);
        }

        // Definir el skin y content_css según el tema
        const skin = tema === 'oscuro' ? 'oxide-dark' : 'oxide';
        const contentCSS = tema === 'oscuro' ? 'dark' : 'default';

        // Inicializar el editor TinyMCE
        tinymce.init({
          selector: '#descripcion',
          skin: skin,
          content_css: contentCSS,
          plugins: 'searchreplace autolink directionality visualblocks visualchars image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap emoticons autosave',
          toolbar: 'undo redo print spellcheckdialog formatpainter | blocks fontfamily fontsize | bold italic underline forecolor backcolor | link image | alignleft aligncenter alignright alignjustify lineheight | checklist bullist numlist indent outdent | removeformat',
          height: "600",
          language: 'es'
        });
      }


        
      $image_crop = $('#contenedor_imagen').croppie({
        enableExif: true,
        viewport: {
          width:700,
          height:460,
          type:'square' //circle
        },
        boundary:{
          width:900,
          height:480
        }
      });

      $('#imagen').on('change', function(){
        const file = this.files[0];
        const  fileType = file['type'];
        const validImageTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/jpg'];
      
        if (validImageTypes.includes(fileType)) {   

          $("#contenedor_imagen").show();

          var reader = new FileReader();
          reader.onload = function (event) {
            $image_crop.croppie('bind', {
              url: event.target.result
            }).then(function(){
              console.log('jQuery bind complete');
            });
          }
          
          reader.readAsDataURL(this.files[0]);

        }else{
          $("#contenedor_imagen").hide();
          const validImageTypesVideo = ['video/mp4'];
          if (!validImageTypesVideo.includes(fileType)) {
              $.notify("Formato de archivo no soportado.", {
                    type:'error',
                    globalPosition: 'top right'
              });  
              return;
          }
        }
      });



  });
</script>
  
<!--FILTROS-->

  <div class="form-row">

    <div div class="col-6 col-lg-6"> 
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><a href="">Prevención de riesgos - <?php echo $tipo_str?></a></li>
        </ol>
      </nav>
    </div>

    <div class="col-6 col-lg-4">  
      <div class="form-group">
      <input type="text" placeholder="Ingrese su busqueda..." id="buscador" class="buscador form-control form-control-sm">
      </div>
    </div>

    
    <div class="col-6 col-lg-2"> 
      <div class="form-group">
         <button type="button" class="btn-block btn btn-sm btn-primary btn_nuevo_prevencion btn_xr3">
         <i class="fa fa-plus-circle"></i>  Nuevo archivo  
         </button>
      </div>
    </div>
    
  </div>

  <div class="row">
    <div class="col-12">
        <table id="tb" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%!important">
          <thead>
            <tr>
              <th class="centered">Acciones</th> 
              <th class="centered">Archivo Principal</th>    
              <th class="centered">Links</th>    
              <th class="centered">Nombre</th>    
              <th class="centered">Descripción</th>    
              <th class="centered">Fecha</th>    
              <th class="centered">Autor</th> 
              <th class="centered">Última actualización</th> 
            </tr>
          </thead>
        </table>
    </div>
  </div>

<!--  NUEVO -->

    <div id="modal_ingreso"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
	    <div class="modal-dialog modal_ingreso">
	      <div class="modal-content">
	        <?php echo form_open_multipart("formIngresoPrevencionModulos",array("id"=>"formIngreso","class"=>"formIngreso"))?>
            <input type="hidden" name="hash_id" id="hash_id">
            <input type="hidden" name="tipo" id="tipo" value ="<?php echo $tipo?>">

	           <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
	           <fieldset class="form-ing-cont">
	              <legend class="form-ing-border">Registro de archivos</legend>
	                
	                <div class="form-row">

                    <div class="col-lg-12">
	                    <div class="form-group"> 
	                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Título </label>
	                       <input  type="text" placeholder="Ingrese titulo" name="titulo" id="titulo" class="form-control form-control-sm" autocomplete="off"/>
	                    </div>
                    </div>


                    <div class="col-lg-12 mt-2">
	                    <div class="form-group"> 
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Descripción </label>
                      <textarea id="descripcion" name="descripcion" cols="50" rows="15"  maxlength="3400"></textarea>
                     </div>

                    
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="">Im&aacute;gen o video Principal <span id="principal_form"></span></label>
                        <input type="file" name="archivo_principal" id="imagen" class="form-control-file" >
                        <br />
                        <div id="uploaded_image"></div>
                        <div id="contenedor_imagen" style="width:100%; margin-top:10px"></div>
                      </div>
                    </div>

                    <div class="form-row">

                    <div class="col-lg-4"> 
                      <div class="form-group"> 
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Link1 <span id="link1_form"></span></label>
                        <input type="file" id="link1" name="link1">
                      </div>
                    </div>

                    <div class="col-lg-4"> 
                      <div class="form-group"> 
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Link2 <span id="link2_form"></span></label>
                        <input type="file" id="link2" name="link2">
                      </div>
                    </div>

                    <div class="col-lg-4"> 
                      <div class="form-group"> 
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Link3 <span id="link3_form"></span></label>
                        <input type="file" id="link3" name="link3">
                      </div>
                      </div>
                   </div>
                    
	                    
	               
	            </fieldset>

	            <br>

	            <div class="col-lg-8 offset-lg-2">
	              <div class="form-row">
	                
	                <!-- <div class="col-6 col-lg-4">
	                  <div class="form-group">  
	                    <div class="form-check">
	                      <input type="checkbox" checked name="checkcorreo" class="form-check-input" id="checkcorreo">
	                      <label class="form-check-label" for="checkcorreo">¿Enviar correo?</label>
	                    </div>
	                  </div>
	                </div> -->

	                <div class="col-6 col-lg-6">
	                  <div class="form-group">
	                    <button type="submit" class="btn-block btn btn-sm btn-primary btn_xr3 btn_ingreso">
	                     <i class="fa fa-save"></i> Guardar
	                    </button>
	                  </div>
	                </div>

	                <div class="col-6 col-lg-6">
	                  <button class="btn-block btn btn-sm btn-dark cierra_modal" data-dismiss="modal" aria-hidden="true">
	                   <i class="fa fa-window-close"></i> Cerrar
	                  </button>
	                </div>
	              </div>
	            </div>
	           </div>
	         <?php echo form_close(); ?>
	      </div>
	    </div>
    </div>


