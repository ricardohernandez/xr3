<style>
  #contenedor_imagen{
    display: none;
  }
  .margen-td {
    padding-left: 10px!important;
    padding-right: 10px!important;
    text-align: left!important; 
  }

  .dataTables_paginate .paginate_button {
    margin-top: 20px!important;
    padding: 5px 11px!important;
    line-height: 1.42857143;
    text-decoration: none;
    font-size: 14px;
    color: #ffffff;
    background-color: #006fe6!important;
    border: 1px solid transparent;
    margin-left: -1px;
    cursor: pointer;
  }
  
  .ver_obs_desp{
    cursor: pointer;
    display: inline;
    margin-left: 5px;
    font-size: 11px;
    color: #2780E3;
  }

  .contenedor_tabla{
    padding: 20px!important;
  }
  .table.dataTable thead .sorting:before, table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:before, table.dataTable thead .sorting_desc_disabled:after {
    bottom: 2px!important;
  }
  .table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_desc_disabled:before {
    right: 1em;
    content: "\2191";
    bottom: 2px;
  }

  table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_desc_disabled:before {
    right: 1em;
    bottom: 1px!important;
    content: "\2191";
  }

  .dataTables_paginate .paginate_button {
    margin-top: 20px!important;
    padding: 5px 11px!important;
    line-height: 1.42857143;
    text-decoration: none;
    font-size: 14px;
    color: #ffffff;
    background-color: #006fe6!important;
    border: 1px solid transparent;
    margin-left: -1px;
    cursor: pointer;
  }


  .btn_edita,.btn_galeria,.btn_eliminar_imagen{
    color: #017AFD!important;
    cursor: pointer;
  }

  .btn_galeria,.btn_eliminar_imagen{
    display: inline;
    margin-left: 5px;
  }

  .pb-4, .py-4 {
    padding-bottom: 1.1rem!important;
  }
  .pt-4, .py-4 {
      padding-top: 1.1rem!important;
  }

  @media (min-width: 992px){
    .modal-lg {
        max-width: 1100px;
       /* margin-left: 250px;*/
    }
  }

  .modal-body {
      padding: .175rem 2.1875rem;
  }


  .img_galeria{
    margin-left: 10px;
    width: 100%;
    height: 40px;
  }
  
  .contenedor_fotos_galeria{
    margin-bottom: 10px;
  }

  .elimina_galeria{
   /* display: none;*/
    color: red;
    position: absolute;
    top: 0px;
    right: 0px;
    cursor: pointer;
  }
</style>
<script>
    tinymce.init({
      selector: 'textarea',
      plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
      ],       
     height: "400",

     toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link",
      advlist_bullet_styles: "circle",
      language : 'es'
    });
  </script>
<script>

  $(function(){
      $.fn.modal.Constructor.prototype.enforceFocus = function() {};
      var base="<?php echo base_url();?>";
      
      /*  $("#titulo").val("titiulo");
      $("#categoria  option[value='1'").prop("selected", true);
      $("#descripcion").val("desc");*/

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

     $.extend(true,$.fn.dataTable.defaults,{
        info:true,
        paging:true,
        ordering:true,
        searching:true,
        lengthChange: false,
        bSort: true,
        bFilter: true,
        bProcessing: true,
        pagingType: "simple" , 
        bAutoWidth: true,
        sAjaxDataProp: "result",        
        bDeferRender: true,
        language : {
          url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
        },
      });

    /*****DATATABLE*****/  
      var tabla_not = $('#tabla_not').DataTable({
        "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
        "iDisplayLength":20, 
        "aaSorting" : [[3,'desc']],
        scrollY: "65vh",
        "scrollX": true,
        "responsive": false,
        "select": true,
        "ajax": {
            "url":"<?php echo base_url();?>listaNoticiasAdmin",
            "dataSrc": function (json) {
               return json;
            },       
            data: function(param){
            }
         },    

         "columns": [
            {
             "class":"margen-td","data": function(row,type,val,meta){
                btn='<center><a href="#!" title="Editar" data-hash="'+row.hash_id+'" class="btn_edita"><i class="fa fa-edit"></i> </a>';
                btn+='<span data-hash="'+row.hash_id+'" class="btn_eliminar_imagen" title="Eliminar imágen"><i class="fa fa-trash"></i></span>';
                return btn;
              }
            },
            { "data": "titulo" ,"class":"margen-td"},
            { "data": "categoria" ,"class":"margen-td"},
            { "data": "fecha" ,"class":"margen-td"},

            {
               "class":"margen-td","data": function(row,type,val,meta){
                desc=row.descripcion.replace(/<\/?[^>]+(>|$)/g, "");
                if(desc!="" && desc!=null){
                   if(desc.length > 20) {
                     str = desc.substring(0,20)+"...";
                     return "<span class='btndesp2'>"+str+"</span><span title='Ver texto completo' class='ver_obs_desp' data-tit="+desc.replace(/ /g,"_")+" data-title="+desc.replace(/ /g,"_")+">Ver más</span>";
                   }else{
                     return "<span class='btndesp2' data-title="+desc.replace(/ /g,"_")+">"+desc+"</span>";
                  }
                }else{
                  return "-";
                }

                }
            },  
              
            {
              "class":"margen-td","data": function(row,type,val,meta){
                if(row.imagen!=""){

                  ext= row.imagen.split('.');
                  if(ext[1]=="mp4"){
                     html='<center><a target="_blank" href="'+base+'noticias/videos/'+row.imagen+'" class="" title="Foto"><i class="fa fa-image"></i></a>';
                  }else{
                     html='<center><a target="_blank" href="'+base+'noticias/imagenes/'+row.imagen+'" class="" title="Foto"><i class="fa fa-image"></i></a>';
                  }

                }else{
                  html="";
                }
                return html;
              }
            },
           
         ]
        }); 

      $(document).on('click', '.ver_obs_desp', function(event) {
          event.preventDefault();
          val=$(this).attr("data-tit");
          elem=$(this);
          if(elem.text()=="Ver más"){
            elem.html("Ocultar");     
            elem.attr("title","Acortar texto");
            elem.prev(".btndesp2").text(val.replace(/_/g, ' '));
            var table = $.fn.dataTable.fnTables(true);
            if ( table.length > 0 ) {
                $(table).dataTable().fnAdjustColumnSizing();
            }
          }else if(elem.text()=="Ocultar"){
            val = val.substring(0,20)+"...";
            elem.prev(".btndesp2").text(val.replace(/_/g, ' '));     
            elem.html("Ver más");
            elem.attr("title","Ver texto completo");
            var table = $.fn.dataTable.fnTables(true);
            if ( table.length > 0 ) {
                $(table).dataTable().fnAdjustColumnSizing();
            }
          }
      });


        
      $(document).on('keyup paste', '#buscador', function() {
        tabla_not.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var tabla_not = $.fn.dataTable.fnTables(true);
        if ( tabla_not.length > 0 ) {
            $(tabla_not).dataTable().fnAdjustColumnSizing();
      }}, 1000 ); 

      $(document).off('click', '.btn_form_noticia').on('click', '.btn_form_noticia',function(event) {
        event.preventDefault();
        $("#hash").val("");       
        $("#titulo").focus();     
        $('#formNoticiaAdmin')[0].reset();
        $("#btn_guardar_noticia").attr("disabled", false);
        $("#btn_cerrar_noticia").attr("disabled", false);
        $("#formNoticiaAdmin").animate({"opacity": 1});
        $(".contenedor_galeria").html("").hide();
       /* $("#titulo").val("titiulo");
        $("#categoria  option[value='1'").prop("selected", true);
        $("#descripcion").val("desc");*/
        $("#contenedor_imagen").hide();
        $("#checkcorreo").prop('checked', true);
      });

      $(document).off('submit', '#formNoticiaAdmin').on('submit', '#formNoticiaAdmin',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formNoticiaAdmin");
        var formData = new FormData(formElement);

        var $archivos = $("#archivos_secundarios");
        if (parseInt($archivos.get(0).files.length) > 10){
           alert("El máximo permitido son 10 imágenes secundarias.");
           return false;
        }

        $image_crop.croppie('result', {
          type: 'canvas',
          size: 'viewport'
        }).then(function(response){
          formData.append("imagen", response);
          $.ajax({
            url: $('.formNoticiaAdmin').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $("#btn_guardar_noticia").attr("disabled", true);
              $("#btn_cerrar_noticia").attr("disabled", true);
              $(".contenedor_mensaje").html("Enviando...<br><center><img src='<?php echo base_url()?>assets/imagenes/loader.gif' height='10px' width='150px'></center>");
            },
            success: function (data) {
              if(data.res == "error"){
                 $("#btn_guardar_noticia").attr("disabled", false);
                 $("#btn_cerrar_noticia").attr("disabled", false);

                 $.notify(data.msg, {
                     type:'error',
                     globalPosition: 'top right'
                 });  

                 tabla_not.ajax.reload();

              }else if(data.res == "ok"){

                  $.notify(data.msg, {
                     type:'success',
                     globalPosition: 'top right'
                 });  

                 setTimeout(function(){ 
                  $('#formNoticiaAdmin')[0].reset();
                  $('#modal_noticia').modal('toggle'); 
                  tabla_not.ajax.reload();

                  setTimeout( function () {
                    var tabla_not = $.fn.dataTable.fnTables(true);
                    if ( tabla_not.length > 0 ) {
                        $(tabla_not).dataTable().fnAdjustColumnSizing();
                  }}, 0 ); 

                 } ,2000);  
              }
            }
        });
        });
        return false;
      });


      /*$(".contenedor_fotos_galeria").hover(function(){
        elem=$(this);
        console.log("visto");
         elem.parent().find(".elimina_galeria").show();
        }, function(){
           console.log("2visto");
         elem.parent().find(".elimina_galeria").hide();
      });*/

      $(document).off('click', '.btn_edita').on('click', '.btn_edita',function(event) {
       $("#hash").val("");
       hash=$(this).attr("data-hash");
       $('#formNoticiaAdmin')[0].reset();
       $('#modal_noticia').modal("toggle");
       $("#btn_guardar_noticia").prop("disabled", false);
       $("#btn_cerrar_noticia").prop("disabled", false);
       $("#formNoticiaAdmin input,#formNoticiaAdmin select,#formNoticiaAdmin button,#formNoticiaAdmin").prop("disabled", true);
       $("#contenedor_imagen").hide();
       $(".contenedor_galeria").html("");
        $.ajax({
          url: "getDataNoticia"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          data:{hash:hash},
          dataType:"json",
          beforeSend:function(){
           $("#btn_guardar_noticia").prop("disabled",true); 
           $("#btn_cerrar_noticia").prop("disabled",true); 
           $("#formNoticiaAdmin").animate({"opacity": .3});
          },
          success: function (data) {
            if(data.res=="ok"){
              for(dato in data.datos){
                hash=data.datos[dato].hash_id;
                $("#hash").val(hash);
                $("#titulo").val(data.datos[dato].titulo);
                $("#categoria  option[value='"+data.datos[dato].id_categoria+"'").prop("selected", true);
                /*$("#descripcion").val(data.datos[dato].descripcion);*/
                /*$("#descripcion").val("asdsadsadsad");*/
               /* tinymce.get('#descripcion').setContent(data.datos[dato].descripcion);*/

                tinymce.activeEditor.setContent(data.datos[dato].descripcion);
              }

              $("#formNoticiaAdmin input,#formNoticiaAdmin select,#formNoticiaAdmin button,#formNoticiaAdmin").prop("disabled", false);
              $("#btn_guardar_noticia").prop("disabled",false); 
              $("#btn_cerrar_noticia").prop("disabled",false); 
              $("#formNoticiaAdmin").animate({"opacity": 1});
               
               //"<img class='img-thumbnail img_galeria' src='"+base+"noticias/imagenes"+data.galeria[dato].imagen+"' width='100px'>"+

              for(dato in data.galeria){
               html="<div class='col-1 contenedor_fotos_galeria'>"+
          
               "<a target='_blank' href='./noticias/imagenes/"+data.galeria[dato].imagen+"'><img class='img-thumbnail img_galeria' src='./noticias/imagenes/"+data.galeria[dato].imagen+"' width='100px'></a>"+
               "</div>";
               $(".contenedor_galeria").append(html).show();
               html="";
              }
              
              $("#checkcorreo").prop('checked', false);

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
                    $('#modal_noticia').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_noticia').modal("toggle");
            }
          },timeout:5000
        }); 
      });

      

      $(document).off('click', '.elimina_galeria').on('click', '.elimina_galeria',function(event) {
       id=$(this).attr("data-id");
       if(confirm("¿Esta seguro que desea eliminar esta imágen?")){
            $.post('eliminaImagen'+"?"+$.now(),{"id": id}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
                $(".contenedor_galeria").html("");
                
                 $.ajax({
                  url: "getDataNoticia"+"?"+$.now(),  
                  type: 'POST',
                  cache: false,
                  tryCount : 0,
                  retryLimit : 3,
                  data:{hash:hash},
                  dataType:"json",
                  success: function (data) {
                    if(data.res=="ok"){
                      for(dato in data.galeria){
                       //html="<div class='col-1'><img data-id='"+data.galeria[dato].id_galeria+"' class='imagen_galeria img-thumbnail' src='"+base+"noticias/imagenes"+data.galeria[dato].imagen+"' width='100px'></div>";
                       
                        html="<div class='col-1 contenedor_fotos_galeria'>"+
                       "<span class='elimina_galeria fa fa-trash' data-id='"+data.galeria[dato].id_galeria+"'></span>"+
                       "<img class='img-thumbnail img_galeria' src='"+base+"noticias/imagenes/"+data.galeria[dato].imagen+"' width='100px'>"+
                       "</div>";
                       $(".contenedor_galeria").append(html).show();

                      }
                    }
                  },timeout:5000
                }); 

               tabla_not.ajax.reload();
              }else{
                $.notify(data.msg, {
                  className:'danger',
                  globalPosition: 'top right'
                });
              }
            },"json");
        }
     });

     $(document).off('click', '.btn_eliminar_imagen').on('click', '.btn_eliminar_imagen',function(event) {
       $("#hash").val("");
       hash=$(this).attr("data-hash");
       if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaNoticia'+"?"+$.now(),{"hash": hash}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
               tabla_not.ajax.reload();
              }else{
                $.notify(data.msg, {
                  className:'danger',
                  globalPosition: 'top right'
                });
              }
            },"json");
        }
     });


   });
</script>

<!-- LISTADO -->
  <div class="page-header row no-gutters py-4">
   
    <div class="col-3">
      <!-- <span class="text-uppercase page-subtitle">Noticias</span> -->
      <h3 class="page-title">Noticias
       <button class="btn btn-primary btn_form_noticia" data-dismiss="modal" aria-label="Close"  data-toggle="modal" data-target="#modal_noticia">
        <i class="fa fa-newspaper"></i> Crear 
      </button></h3>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <div class="card card-small mb-4">
      <!--   <div class="card-header border-bottom">
          <h6 class="m-0">Active Users</h6>
        </div> -->
        <div class="card-body p-0 pb-3 text-center contenedor_tabla">
          <div class="row">
            <div class="col">
              <table id="tabla_not" class="table-hover table-bordered table-striped dt-responsive nowrap" style="width:100%">
                <thead class="bg-light">
                  <tr>
                    <th scope="col" class="border-0">Acciones</th>
                    <th scope="col" width="50%" class="border-0">Título</th>
                    <th scope="col" class="border-0">Categor&iacute;a</th>
                    <th scope="col" class="border-0">Fecha</th>
                    <th scope="col" class="border-0">Descripci&oacute;n</th>
                    <th scope="col" class="border-0">Imágen/Video Principal</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

<!-- MODAL NOTICIA-->
<!-- modal fade -->
  <div class="modal fade" id="modal_noticia" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nueva noticia</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <?php echo form_open_multipart('nuevaNoticiaAdmin', array('id'=>'formNoticiaAdmin','class'=>'formNoticiaAdmin')); ?>

        <div class="modal-body">
  	        <div class="mb-4">
  	          <ul class="list-group list-group-flush">
  	            <li class="list-group-item p-3">
  	              <div class="row">
  	                <div class="col">
    	                 <input type="hidden" value="" name="hash" id="hash">
                        <div class="form-row">
  	                     
                          <div class="form-group col-md-9">
  	                        <label for="feFirstName">T&iacute;tulo</label>
  	                        <input  id="titulo" name="titulo" size="200" maxlength="200" type="text" class="form-control" placeholder=""> 
                          </div>

                          <div class="form-group col-md-3">
                            <label for="">Categor&iacute;a</label>
                            <select id="categoria" name="categoria" class="form-control">
                              <option value="" selected>Seleccione categor&iacute;a</option>
                              <?php  
                                foreach($categorias as $c){
                                  ?>
                                    <option value="<?php echo $c["id"] ?>"><?php echo $c["categoria"] ?></option>
                                  <?php
                                }
                              ?>
                            </select>
                          </div>

                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="">Im&aacute;gen o video Principal</label>
                            <input type="file" name="archivo_principal" id="imagen" class="form-control-file" >
                            <br />
                            <div id="uploaded_image"></div>
                            <div id="contenedor_imagen" style="width:100%; margin-top:10px"></div>
                          </div>

                          <div class="form-group col-md-6">
                              <label for="">Imágenes secundarias (10 max)</label>
                              <input type="file" id="archivos_secundarios" name="archivos_secundarios[]" multiple class="form-control-file"/>
                          </div>
                        </div>

                        <div class="form-row contenedor_galeria"> </div>

                        <!-- <div class="form-row">
    	                    <div class="form-group col-md-12">
    	                        <label for="fePassword">Descripci&oacute;n</label>
                              <textarea id="descripcion" name="descripcion" rows="3" size="400" maxlength="400" class="form-control"></textarea>
                          </div>
                        </div> -->

                        <div class="form-row">
                          <div class="form-group col-md-12">
                              <label for="fePassword">Descripci&oacute;n</label>
                              <textarea id="descripcion" name="descripcion" cols="50" rows="15"  maxlength="3400"></textarea>
                          </div>
                        </div>


                    </div>
                  </div>
  	            </li>
  	          </ul>
  	        </div>
        </div>
        
        <div class="modal-footer">
           <!--  <div class="form-check">
              <input type="checkbox" checked name="checkcorreo" class="form-check-input" id="checkcorreo">
              <label class="form-check-label" for="checkcorreo">¿Enviar correo?</label>
            </div> -->
            <button type="submit" class="btn btn-primary" id="btn_guardar_noticia"><i class="fa fa-save"></i> Guardar</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_noticia"><i class="fa fa-window-close"></i> Cerrar</button>
          </div>
        <?php echo form_close();?>  

      </div>
    </div>
  </div>





