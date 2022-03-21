<style>
  .btn_edita_info,.btn_eliminar_info{
    color: #017AFD!important;
    cursor: pointer;
    margin-left: 15px;
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

</style>
<script>
  $(function(){

    /*****DATATABLE*****/  
      var tabla_inf = $('#tabla_inf').DataTable({
        "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
        "iDisplayLength":50, 
        "aaSorting" : [[2,'desc']],
         scrollY: "65vh",
        "scrollX": true,
        "responsive": false,
        "select": true,
        "ajax": {
            "url":"<?php echo base_url();?>listaInformaciones",
            "dataSrc": function (json) {
               return json;
            },       
            data: function(param){
            }
         },    

         "columns": [
            {
             "class":"","data": function(row,type,val,meta){
                btn='<center><a href="#!" title="Editar" data-hash="'+row.hash_id+'" class="btn_edita_info"><i class="fa fa-edit"></i> </a>';
                btn+='<span data-hash="'+row.hash_id+'" class="btn_eliminar_info" title="Eliminar imágen"><i class="fa fa-trash"></i></span>';
                return btn;
              }
            },
            { "data": "titulo" ,"class":"margen-td"},
            { "data": "fecha" ,"class":"margen-td"},
            { "data": "hora" ,"class":"margen-td"},
            
         ]
        }); 
        
      $(document).on('keyup paste', '#buscador', function() {
        tabla_inf.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var tabla_inf = $.fn.dataTable.fnTables(true);
        if ( tabla_inf.length > 0 ) {
            $(tabla_inf).dataTable().fnAdjustColumnSizing();
      }}, 1000 ); 

      $(document).off('click', '.btn_form_info').on('click', '.btn_form_info',function(event) {
        event.preventDefault();
        $("#hash").val("");       
        $("#titulo").focus();     
        $('#formInformaciones')[0].reset();
        $("#btn_guardar_informacion").attr("disabled", false);
        $("#btn_cerrar_informacion").attr("disabled", false);
        $("#formInformaciones").animate({"opacity": 1});
      });

      $(document).off('submit', '#formInformaciones').on('submit', '#formInformaciones',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formInformaciones");
        var formData = new FormData(formElement);

          $.ajax({
            url: $('.formInformaciones').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
             /* $("#btn_guardar_informacion").attr("disabled", true);
              $("#btn_cerrar_informacion").attr("disabled", true);*/
              //$(".contenedor_mensaje").html("Enviando...<br><center><img src='<?php echo base_url()?>assets/imagenes/loader.gif' height='10px' width='150px'></center>");
            },
            success: function (data) {
              if(data.res == "error"){
                 $("#btn_guardar_informacion").attr("disabled", false);
                 $("#btn_cerrar_informacion").attr("disabled", false);

                 $.notify(data.msg, {
                     type:'error',
                     globalPosition: 'top right'
                 });  

                 tabla_inf.ajax.reload();

              }else if(data.res == "ok"){

                  $.notify(data.msg, {
                     type:'success',
                     globalPosition: 'top right'
                 });  

                 setTimeout(function(){ 
                  $('#formInformaciones')[0].reset();
                  $('#modal_info').modal('toggle'); 
                  tabla_inf.ajax.reload();

                  setTimeout( function () {
                    var tabla_inf = $.fn.dataTable.fnTables(true);
                    if ( tabla_inf.length > 0 ) {
                        $(tabla_inf).dataTable().fnAdjustColumnSizing();
                  }}, 0 ); 

                 } ,2000);  
              }
            }
          });
        return false;
      });

      $(document).off('click', '.btn_edita_info').on('click', '.btn_edita_info',function(event) {
       $("#hash").val("");
       hash=$(this).attr("data-hash");
       $('#formInformaciones')[0].reset();
       $('#modal_info').modal("toggle");
       $("#btn_guardar_informacion").prop("disabled", false);
       $("#btn_cerrar_informacion").prop("disabled", false);
       $("#formInformaciones input,#formInformaciones select,#formInformaciones button,#formInformaciones").prop("disabled", true);
      	

        $.ajax({
          url: "getDataInformacion"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          data:{hash:hash},
          dataType:"json",
          beforeSend:function(){
           $("#btn_guardar_informacion").prop("disabled",true); 
           $("#btn_cerrar_informacion").prop("disabled",true); 
           $("#formInformaciones").animate({"opacity": .3});
          },
          success: function (data) {
            if(data.res=="ok"){
              for(dato in data.datos){
                hash=data.datos[dato].hash_id;
                $("#hash").val(hash);
                $("#titulo").val(data.datos[dato].titulo);
              }

              $("#formInformaciones input,#formInformaciones select,#formInformaciones button,#formInformaciones").prop("disabled", false);
              $("#btn_guardar_informacion").prop("disabled",false); 
              $("#btn_cerrar_informacion").prop("disabled",false); 
              $("#formInformaciones").animate({"opacity": 1});
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
                    $('#modal_info').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_info').modal("toggle");
            }
          },timeout:5000
        }); 
      });

      

     $(document).off('click', '.btn_eliminar_info').on('click', '.btn_eliminar_info',function(event) {
       hash=$(this).attr("data-hash");
       if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaInformacion'+"?"+$.now(),{"hash": hash}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
               tabla_inf.ajax.reload();
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
   
    <div class="col-4">
      <!-- <span class="text-uppercase page-subtitle">Noticias</span> -->
      <h3 class="page-title">Informaciones
       <button class="btn btn-primary btn_form_info" data-dismiss="modal" aria-label="Close"  data-toggle="modal" data-target="#modal_info">
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
              <table id="tabla_inf" class="table-hover table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead class="bg-light">
                  <tr>
                    <th scope="col" class="border-0">Acciones</th>
                    <th scope="col" class="border-0" width="50%">Título</th>
                    <th scope="col" class="border-0">Fecha</th>
                    <th scope="col" class="border-0">Hora</th>
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
  <div class="modal fade" id="modal_info" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nueva informaci&oacute;n</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <?php echo form_open_multipart('nuevaInformacion', array('id'=>'formInformaciones','class'=>'formInformaciones')); ?>

        <div class="modal-body">
  	        <div class="mb-4">
  	          <ul class="list-group list-group-flush">
  	            <li class="list-group-item p-3">
  	              <div class="row">
  	                <div class="col">
    	                <input type="hidden" value="" name="hash" id="hash">
                        <div class="form-row">
  	                     
                          <div class="form-group col-md-12">
  	                        <label for="feFirstName">T&iacute;tulo</label>
  	                        <input  id="titulo" name="titulo" size="200" maxlength="200" type="text" class="form-control" placeholder=""> 
                          </div>

  	                   
                        </div>
                    </div>
                  </div>
  	            </li>
  	          </ul>
  	        </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="btn_guardar_informacion"><i class="fa fa-save"></i> Guardar</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_informacion"><i class="fa fa-window-close"></i> Cerrar</button>
         </div>
        <?php echo form_close();?>  
      </div>
    </div>
  </div>





