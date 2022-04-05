<style type="text/css">
  .red{
    background-color: #DC3545;
    color: #fff;
  }
  .ver_obs_desp{
    cursor: pointer;
    display: inline;
    margin-left: 5px;
    font-size: 11px;
    color: #2780E3;
  }

  .modal-header{
    padding:0.1rem 0.1rem!important;
    border-bottom: none!important;
  }

  .form-control-sm {
    height: calc(1.9em + 0.5rem + 2px)!important;
  }
  .modal-body {
    position: relative;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding:0.1rem 0.1rem!important;
  }

  .btn_xr3{
    color: #fff!important;
    background-color: #32477C!important;
    border-color: #32477C!important;
  }

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
    .custom-select-sm {
     height: calc(1.90rem + 2px)!important; 
    }

    .input-xs {
      height: 22px!important;
      line-height: 1.5;
      padding: 1px 6px!important;
      font-size: 11px!important;
    }
    .form-group{
      margin-bottom:5px!important;
    }
    .centered{
      font-size: 11px;
      text-align: left;
      white-space:nowrap;
    }
    .borrar_ots{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
    }

    table.dataTable tbody th, table.dataTable tbody td {
      padding: 0px 7px!important;
      font-size: 12px;
    }

    table td, .table th {
      padding: 0.75rem;
      vertical-align: middle!important;
      border-top: 1px solid #dee2e6;
    }

    .btn_delete_linea:hover{
      cursor: pointer;
    }
    
    .btn_modificar_ots{
      display: block;
      text-align: center!important;
      margin:0 auto!important;
      font-size: 15px!important;
    }

    .modal_ingreso{
      width: 44%!important;
    }

    fieldset {
      padding: .15em .625em .15em!important;
    }


    .tabla_listado2 #tabla_listado2 > tbody > tr > td {
        padding: 1px!important;
    }
    .table_text{
      text-align: left!important;
      font-size: 10.5px!important;
      margin: 0px!important;
      padding-left: 3px;
      padding-right: 3px;
     }
    .observacion_chk:focus,.observacion_chk:active{
      background-color: #fff!important;
    }
    .table_head{
      font-size: 12px!important;
      text-align: center!important;
    }
   
    .full-w{
      width: 90%!important;
    }
    .tabla_listado2 #tabla_listado2 .header th {
       height: 22px;
       font-size: 12.5px;
    }

    .tabla_listado2 #tabla_listado2 thead tr th{
      font-size: 11px!important;
    }
    .tabla_listado2 #tabla_listado2 tbody tr td{
      font-size: 10px!important;
    }

    .tabla_listado2 .dataTables_filter {
     display: none;
    }

    .form-control {
      font-size: 12px!important;
      padding: .375rem .75rem!important;
    }

    .custom-select{
      font-size: 12px!important;
    }

  }

  @media(max-width: 768px){
    .input-xs {
      height: 32px!important;
      line-height: 1.5;
      padding: 1px 1px!important;
      font-size: 11px!important;
    }
    .custom-select-sm {
     height: calc(2.6rem + 2px)!important; 
    }
    table.dataTable tbody th, table.dataTable tbody td {
      padding: 1px 3px!important;
      font-size: 14px!important;
    }

    .form-group{
      margin-bottom:5px!important;
    }
    .centered{
      font-size: 13px;
      text-align: left;
      white-space:nowrap;
    }
    .custom-select{
      font-size: 14px!important;
    }
    .form-control {
        font-size: 14px!important;
        padding: 0.575rem 0.75rem!important;
    }

    .borrar_ots{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 20px;
      text-decoration: none!important;
    }

    table.dataTable tbody th, table.dataTable tbody td {
      padding: 0px 7px!important;
      font-size: 14px;
    }

    table td, .table th {
      padding: 1.75rem;
      vertical-align: middle!important;
      border-top: 1px solid #dee2e6;
    }

    .btn_delete_linea:hover{
      cursor: pointer;
    }
    
    .btn_modificar_ots{
      display: block;
      text-align: center!important;
      font-size: 18px!important;
    }

    fieldset {
      padding: .15em .625em .15em!important;
    }

    .tabla_listado2 #tabla_listado2 > tbody > tr > td {
        padding: 2px!important;
    }
    .table_text{
      font-size: 12px!important;
      margin: 0px!important;
      padding-left: 1px;
      padding-right: 1px;
     }
    .observacion_chk:focus,.observacion_chk:active{
      background-color: #fff!important;
    }
    .table_head{
      font-size: 14px!important;
      text-align: center!important;
    }

    .full-w{
      width: 90%!important;
    }
    .tabla_listado2 #tabla_listado2 .header th {
       height: 22px;
       font-size: 12.5px;
    }

    .tabla_listado2 #tabla_listado2 thead tr th{
      font-size: 13px!important;
    }
    .tabla_listado2 #tabla_listado2 tbody tr td{
      font-size: 13px!important;
    }

    .tabla_listado2 .dataTables_filter {
     display: none;
    }

  }
</style>

<script type="text/javascript" charset="utf-8"> 
  $(function(){ 

    /*****DATATABLE*****/  
      const base = "<?php echo base_url() ?>";
      const p ="<?php echo $this->session->userdata('id_perfil'); ?>";

      var tb_ticket = $('#tb_ticket').DataTable({
         "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
         "iDisplayLength":-1, 
         "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
         "bPaginate": false,
         "aaSorting" : [[8,"desc"]],
         "scrollY": "60vh",
         "scrollX": true,
         "sAjaxDataProp": "result",        
         "bDeferRender": true,
         "select" : true,
         columnDefs: [
            { orderable: false, targets: 0 }
         ],
          "ajax": {
            "url":"<?php echo base_url();?>getTicketList",
            "dataSrc": function (json) {
              $(".btn_filtro_ticket").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
              $(".btn_filtro_ticket").prop("disabled" , false);

              return json;
            },       
            data: function(param){
               param.estado = $("#estado_f").val();
            }
          },    
          "columns": [
            {
              "class":"centered margen-td","data": function(row,type,val,meta){
                var color = row.estado=="pendiente" ? "#FF0000" : "#32477C";
           
              	if(p==1){
                    btn  =`<center><a  href="#!"   data-hash="${row.hash}"  title="Estado" class="btn_editar" style="color:${color};font-size:14px!important;"><i class="fas fa-edit"></i> ${row.estado}</a></center>`;
             	     /*btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar rojo"><i class="fa fa-trash"></i></a></center>';*/
              	}else{

              		if(row.estado=="pendiente"){
              			btn='<center><a href="#!" title="Modificar" data-hash="'+row.hash+'" class="btn_editar"><i class="fa fa-edit"></i></a>';
             	 	 	  /* btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar rojo"><i class="fa fa-trash"></i></a></center>';*/
                	}else{
                		btn=""
                	}
              	}

                btn+="</center>"

                return btn;
              }
            },

            {
              "class":"centered margen-td","data": function(row,type,val,meta){
                botonera="";
                if(row.adjunto!=null && row.adjunto!=""){
                  botonera+='<center><a title="Ver archivo" style="margin-left:10px;" target="_blank" href="'+base+'archivos/ticket/'+row.adjunto+'"><i class="fa fa-file verde"></i></a></center>';
                }else{
                  botonera="<center>-</center>";
                }
                return botonera;
              }
            },

            {
             "class":"centered margen-td","data": function(row,type,val,meta){
                if(row.titulo!="" && row.titulo!=null){
                   if(row.titulo.length > 60) {
                     str = row.titulo.substring(0,60)+"...";
                     return "<span class='btndesp2'>"+str+"</span><span title='Ver texto completo' class='ver_obs_desp' data-tit="+row.titulo.replace(/ /g,"_")+" data-title="+row.titulo.replace(/ /g,"_")+">Ver más</span>";
                   }else{
                     return "<span class='btndesp2' data-title="+row.titulo.replace(/ /g,"_")+">"+row.titulo+"</span>";
                  }
                }else{
                  return "-";
                }
              }
            },  
            {
             "class":"centered margen-td","data": function(row,type,val,meta){
                if(row.descripcion!="" && row.descripcion!=null){
                   if(row.descripcion.length > 60) {
                     str = row.descripcion.substring(0,60)+"...";
                     return "<span class='btndesp2'>"+str+"</span><span title='Ver texto completo' class='ver_obs_desp' data-tit="+row.descripcion.replace(/ /g,"_")+" data-title="+row.descripcion.replace(/ /g,"_")+">Ver más</span>";
                   }else{
                     return "<span class='btndesp2' data-title="+row.descripcion.replace(/ /g,"_")+">"+row.descripcion+"</span>";
                  }
                }else{
                  return "-";
                }
              }
            },  

            { "data": "tipo" ,"class":"margen-td centered"},
            { "data": "fecha_ingreso" ,"class":"margen-td centered"},
            { "data": "fecha_respuesta" ,"class":"margen-td centered"},
            { "data": "digitador" ,"class":"margen-td centered"},
            { "data": "ultima_actualizacion" ,"class":"margen-td centered"},
          
          ]
    }); 

    $(document).on('keyup paste', '#buscador', function() {
      tb_ticket.search($(this).val().trim()).draw();
    });

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    setTimeout( function () {
      var tb_ticket = $.fn.dataTable.fnTables(true);
      if ( tb_ticket.length > 0 ) {
          $(tb_ticket).dataTable().fnAdjustColumnSizing();
    }}, 200 ); 

    setTimeout( function () {
      var tb_ticket = $.fn.dataTable.fnTables(true);
      if ( tb_ticket.length > 0 ) {
          $(tb_ticket).dataTable().fnAdjustColumnSizing();
    }}, 2000 ); 

    setTimeout( function () {
      var tb_ticket = $.fn.dataTable.fnTables(true);
      if ( tb_ticket.length > 0 ) {
          $(tb_ticket).dataTable().fnAdjustColumnSizing();
      }
    }, 4000 ); 

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
        val = val.substring(0,60)+"...";
        elem.prev(".btndesp2").text(val.replace(/_/g, ' '));     
        elem.html("Ver más");
        elem.attr("title","Ver texto completo");
        var table = $.fn.dataTable.fnTables(true);
        if ( table.length > 0 ) {
            $(table).dataTable().fnAdjustColumnSizing();
        }
      }
    });

    $(document).off('click', '.btn_filtro_ticket').on('click', '.btn_filtro_ticket',function(event) {
     event.preventDefault();
      $(this).prop("disabled" , true);
      $(".btn_filtro_ticket").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
       tb_ticket.ajax.reload();
    });

    $(document).off('click', '.btn_nuevo_usuario').on('click', '.btn_nuevo_usuario', function(event) {
        $('#formTicket')[0].reset();
        $("#hash_ticket").val("");
        $('#modal_ingreso').modal('toggle'); 
        $("#formTicket input,#formTicket select,#formTicket button,#formTicket").prop("disabled", false);
        $(".btn_ingreso").attr("disabled", false);
        $(".cierra_modal").attr("disabled", false);
    });

    $(document).off('submit', '#formTicket').on('submit', '#formTicket',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formTicket");
        var formData = new FormData(formElement);
          $.ajax({
              url: $('#formTicket').attr('action')+"?"+$.now(),  
              type: 'POST',
              data: formData,
              cache: false,
              processData: false,
              dataType: "json",
              contentType : false,
              beforeSend:function(){
                $(".btn_ingreso").attr("disabled", true);
                $(".cierra_modal").attr("disabled", true);
                $("#formTicket input,#formTicket select,#formTicket button,#formTicket").prop("disabled", true);
              },

              success: function (data) {
                if(data.res == "sess"){
                  window.location="unlogin";

                }else if(data.res=="ok"){
                  $('#modal_ingreso').modal('toggle'); 
                  $("#formTicket input,#formTicket select,#formTicket button,#formTicket").prop("disabled", false);
                  $(".btn_ingreso").attr("disabled", false);
                  $(".cierra_modal").attr("disabled", false);

                  $.notify(data.msg, {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $('#formTicket')[0].reset();
                  tb_ticket.ajax.reload();
                }else if(data.res=="error"){

                  $(".btn_ingreso").attr("disabled", false);
                  $(".cierra_modal").attr("disabled", false);
                  $.notify(data.msg, {
                    className:'error',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                  $("#formTicket input,#formTicket select,#formTicket button,#formTicket").prop("disabled", false);

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

            },timeout:35000
          }); 
        return false; 
    });


    $(document).off('click', '.btn_editar').on('click', '.btn_editar',function(event) {
        event.preventDefault();
        $("#hash_ticket").val("");
        hash=$(this).data("hash");
        $('#formTicket')[0].reset();
        $("#hash_ticket").val(hash);
        $('#modal_ingreso').modal('toggle'); 
        $("#formTicket input,#formTicket select,#formTicket button,#formTicket").prop("disabled", true);
        $(".btn_ingreso").attr("disabled", true);
        $(".cierra_modal").attr("disabled", true);

        $.ajax({
          url: base+"getDataTicket"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          data:{hash:hash},
          dataType:"json",
          beforeSend:function(){
           $(".btn_ingreso").prop("disabled",true); 
           $(".cierra_modal").prop("disabled",true); 
          },
          success: function (data) {
            if(data.res=="ok"){
              for(dato in data.datos){

                  $("#hash_ticket").val(data.datos[dato].hash);
                  $("#titulo").val(data.datos[dato].titulo);
                  $("#descripcion").val(data.datos[dato].descripcion);
                  $("#tipo option[value='"+data.datos[dato].id_tipo+"'").prop("selected", true);
                  $("#estado option[value='"+data.datos[dato].estado+"'").prop("selected", true);
              }

              $("#formTicket input,#formTicket select,#formTicket button,#formTicket").prop("disabled", false);
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
        },timeout:35000
      }); 
    });

    $(document).off('click', '.btn_eliminar').on('click', '.btn_eliminar',function(event) {
        hash=$(this).data("hash");
        if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaTicket'+"?"+$.now(),{"hash": hash}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
               tb_ticket.ajax.reload();
              }else{
                $.notify(data.msg, {
                  className:'danger',
                  globalPosition: 'top right'
                });
              }
            },"json");
          }
    });

    $(document).off('change', '#estado_f').on('change', '#estado_f',function(event) {
      tb_ticket.ajax.reload()
   
    }); 

  });
</script>
  

<!--FILTROS-->

  <div class="form-row">
	  <div class="col-1 col-lg-2"> 
	      <div class="form-group">
	         <button type="button" class="btn-block btn btn-sm btn-outline-primary btn_nuevo_usuario btn_xr3">
	         <i class="fa fa-plus-circle"></i>  Nuevo 
	         </button>
	      </div>
	    </div>

      <div class="col-lg-2">  
        <div class="form-group">
          <select id="estado_f" name="estado_f" class="custom-select custom-select-sm">
               <option value="" selected>Estado | Todos</option>
               <option value="pendiente" selected>Pendiente</option>
               <option value="en proceso">En proceso</option>
               <option value="cancelado">Cancelado</option>
               <option value="finalizado">Finalizado</option>
          </select>
        </div>
      </div>

	    <div class="col-2 col-lg-4">  
	      <div class="form-group">
	      <input type="text" placeholder="Ingrese su busqueda..." id="buscador" class="buscador form-control form-control-sm">
	      </div>
	    </div>



	    <!-- <div class="col-2 col-lg-2">
	       <div class="form-group">
	          <button type="button" class="btn-block btn btn-sm btn-outline-primary btn-primary btn_filtro_ticket btn_xr3">
	       <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
	       </button>
	       </div>
	    </div> -->
	</div>

  <div class="row">
    <div class="col-12">
      <table id="tb_ticket" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%!important">
          <thead>
            <tr>
              <th class="centered">Acciones</th> 
              <th class="centered">Adjunto</th>    
              <th class="centered">Título</th>    
              <th class="centered">Descripción</th>    
              <th class="centered">Tipo</th> 
              <th class="centered">Fecha ingreso</th> 
              <th class="centered">Fecha respuesta</th> 
              <th class="centered">Digitador</th> 
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
	        <?php echo form_open_multipart("formTicket",array("id"=>"formTicket","class"=>"formTicket"))?>
	           <input type="hidden" name="hash" id="hash_ticket">

	           <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
	           <fieldset class="form-ing-cont">
	               <legend class="form-ing-border">Datos de ingreso</legend>
	                
	                <div class="form-row">

	                    <div class="col-lg-12">
		                    <div class="form-group"> 
		                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Título </label>
		                       <input  type="text" placeholder="Título" name="titulo" id="titulo" class="form-control form-control-sm" autocomplete="off"/>
		                    </div>
	                    </div>

	                    <div class="col-lg-12">  
	                      <div class="form-group">
	                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tipo </label>
	                      <select id="tipo" name="tipo" class="custom-select custom-select-sm">
	                      	   <option value="" selected>Seleccione</option>
                             <?php  
                              foreach($tipos as $t){
                                ?>
                                    <option value="<?php echo $t["id"] ?>"><?php echo $t["tipo"] ?></option>

                                <?php
                              }
                             ?>
	                      </select>
	                      </div>
	                    </div>

	                    <div class="col-lg-12">  
	                     <div class="form-group">
        						    <label for="">Descripción</label>
        						    <textarea class="form-control" id="descripcion" name="descripcion" rows="5"></textarea>
        						  </div>
	                    </div>
	                      
	                    <div class="col-lg-12"> 
	                      <div class="form-group"> 
	                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Adjunto</label>
	                        <input type="file" id="userfile" name="userfile">
	                      </div>
	                    </div>

	                    <?php 
	                    	if($this->session->userdata('id_perfil')==1){
	                    		?>
	                    			<div class="col-lg-12">  
				                      <div class="form-group">
					                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Estado </label>
					                      <select id="estado" name="estado" class="custom-select custom-select-sm">
					                      	   <option value="" selected>Seleccione</option>
					                      	   <?php  
					                      	   	if($this->session->userdata('id_perfil')==1){
					                      	   		?>
					                      	   		 <option value="pendiente" selected>Pendiente</option>
							                      	   <option value="en proceso">En proceso</option>
							                      	   <option value="cancelado">Cancelado</option>
							                      	   <option value="finalizado">Finalizado</option>
							                      	   		<?php
					                      	   	}else{
						                      	   	?>
						                      	   	   <option value="pendiente">Pendiente</option>
						                      	   	<?php
						                      	   	}
					                      	    ?>
					                      	
					                      </select>
				                      </div>
				                    </div>
	                    		<?php
	                    	}

	                    ?>

	                    
	                </div>
	            </fieldset>

	            <br>

	            <div class="col-lg-8 offset-lg-2">
	              <div class="form-row">
	               
                  <div class="col-6 col-lg-4">
                    <div class="form-group">  
                      <div class="form-check">
                        <input type="checkbox" checked name="checkcorreo" class="form-check-input" id="checkcorreo">
                        <label class="form-check-label" for="checkcorreo">¿Enviar correo?</label>
                      </div>
                    </div>
                  </div>

	                <div class="col-6 col-lg-4">
	                  <div class="form-group">
	                    <button type="submit" class="btn-block btn btn-sm btn-primary btn_ingreso">
	                     <i class="fa fa-save"></i> Guardar
	                    </button>
	                  </div>
	                </div>

	                <div class="col-6 col-lg-4">
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
