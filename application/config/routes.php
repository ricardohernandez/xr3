<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*******INICIO *******/
	
	$route['default_controller'] = 'inicio';
	$route['404_override'] = "";
	$route['nojs'] = "inicio/nojs";
	$route['validaLogin'] = "inicio/validaLogin";
	$route['login'] = "inicio/login";
	$route['unlogin'] = "inicio/unlogin";
	$route['inicio'] = "inicio/inicio";

	$route['cargaInicio'] = "inicio/cargaInicio";
	$route['cargaInformaciones'] = "inicio/cargaInformaciones";
	$route['cargaVistaNoticias'] = "inicio/cargaVistaNoticias";
	$route['cargaVistaNoticia'] = "inicio/cargaVistaNoticia";
	$route['cargaCategorias'] = "inicio/cargaCategorias";
	$route['cargaIngresos'] = "inicio/cargaIngresos";
	$route['cargaIngresos'] = "inicio/cargaIngresos";
	$route['infoUsuario'] = "inicio/infoUsuario";
	$route['cambiarPass'] = "inicio/cambiarPass";
	$route['verComo'] = "inicio/verComo";
	$route['recuperarPass'] = "inicio/recuperarPass";

/*******ADMIN BACK END*******/

	$route['admin_xr3'] = "admin";
	$route['cargaVistaNoticiasAdmin'] = "admin/cargaVistaNoticiasAdmin";
	$route['listaNoticiasAdmin'] = "admin/listaNoticiasAdmin";
	$route['nuevaNoticiaAdmin'] = "admin/nuevaNoticiaAdmin";
	$route['getDataNoticia'] = "admin/getDataNoticia";
	$route['eliminaNoticia'] = "admin/eliminaNoticia";
	$route['eliminaImagen'] = "admin/eliminaImagen";

	$route['cargaVistaInformaciones'] = "admin/cargaVistaInformaciones";
	$route['listaInformaciones'] = "admin/listaInformaciones";
	$route['nuevaInformacion'] = "admin/nuevaInformacion";
	$route['getDataInformacion'] = "admin/getDataInformacion";
	$route['eliminaInformacion'] = "admin/eliminaInformacion";

/*******DOCUMENTACION*******/
	$route['documentacion/capacitacion'] = "back_end/documentacion/index";
	$route['vistaCapacitacion'] = "back_end/documentacion/vistaCapacitacion";
	$route['getCapacitacionList'] = "back_end/documentacion/getCapacitacionList";
	$route['getDataRegistroCapacitacion'] = "back_end/documentacion/getDataRegistroCapacitacion";
	$route['formIngresoCapacitacion'] = "back_end/documentacion/formIngresoCapacitacion";
	$route['eliminaCapacitacion'] = "back_end/documentacion/eliminaCapacitacion";

/*******CHECKLIST*******/

	$route['checklist_herramientas'] = "back_end/checklist/checklist/index";
	$route['vistaChecklist'] = "back_end/checklist/checklist/vistaChecklist";
	$route['listaOTS'] = "back_end/checklist/checklist/listaOTS";
	$route['getDataOTS'] = "back_end/checklist/checklist/getDataOTS";
	$route['formOTS'] = "back_end/checklist/formOTS";
	$route['eliminaOTS'] = "back_end/checklist/checklist/eliminaOTS";
	$route['datosAuditor'] = "back_end/checklist/checklist/datosAuditor";
	$route['datosTecnico'] = "back_end/checklist/checklist/datosTecnico";
	$route['formCargaMasiva'] = "back_end/checklist/checklist/formCargaMasiva";
	$route['excel_checklist/(:any)/(:any)'] = "back_end/checklist/checklist/excel_checklist/$1/$2";
	$route['vistaGraficos'] = "back_end/checklist/checklist/vistaGraficos";
	$route['dataEstadosChecklist'] = "back_end/checklist/checklist/dataEstadosChecklist";
	$route['dataTecnicos'] = "back_end/checklist/checklist/dataTecnicos";

/*******CHECKLIST HFC*******/

	$route['checklistHFC'] = "back_end/checklist/ChecklistHFC/index";
	$route['vistaChecklistHFC'] = "back_end/checklist/ChecklistHFC/vistaChecklistHFC";
	$route['listaChecklistHFC'] = "back_end/checklist/ChecklistHFC/listaChecklistHFC";
	$route['getDataChecklistHFC'] = "back_end/checklist/ChecklistHFC/getDataChecklistHFC";
	$route['formChecklistHFC'] = "back_end/checklist/ChecklistHFC/formChecklistHFC";
	$route['eliminaChecklistHFC'] = "back_end/checklist/ChecklistHFC/eliminaChecklistHFC";
	$route['datosAuditorChecklistHFC'] = "back_end/checklist/ChecklistHFC/datosAuditorChecklistHFC";
	$route['datosTecnicoChecklistHFC'] = "back_end/checklist/ChecklistHFC/datosTecnicoChecklistHFC";
	$route['formCargaMasivaChecklistHFC'] = "back_end/checklist/ChecklistHFC/formCargaMasivaChecklistHFC";
	$route['excel_checklistHFC/(:any)/(:any)'] = "back_end/checklist/ChecklistHFC/excel_checklistHFC/$1/$2";
	$route['vistaGraficosChecklistHFC'] = "back_end/checklist/ChecklistHFC/vistaGraficosChecklistHFC";
	$route['dataEstadosChecklistHFC'] = "back_end/checklist/ChecklistHFC/dataEstadosChecklistHFC";
	$route['dataTecnicosChecklistHFC'] = "back_end/checklist/ChecklistHFC/dataTecnicosChecklistHFC";


/*******CHECKLIST FTTH*******/

	$route['checklistFTTH'] = "back_end/checklist/ChecklistFTTH/index";
	$route['vistaChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/vistaChecklistFTTH";
	$route['listaChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/listaChecklistFTTH";
	$route['getDataChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/getDataChecklistFTTH";
	$route['formChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/formChecklistFTTH";
	$route['eliminaChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/eliminaChecklistFTTH";
	$route['datosAuditorChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/datosAuditorChecklistFTTH";
	$route['datosTecnicoChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/datosTecnicoChecklistFTTH";
	$route['formCargaMasivaChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/formCargaMasivaChecklistFTTH";
	$route['excel_checklistFTTH/(:any)/(:any)'] = "back_end/checklist/ChecklistFTTH/excel_checklistFTTH/$1/$2";
	$route['vistaGraficosChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/vistaGraficosChecklistFTTH";
	$route['dataEstadosChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/dataEstadosChecklistFTTH";
	$route['dataTecnicosChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/dataTecnicosChecklistFTTH";

/*******USUARIOS*******/
	$route['mantenedor_usuarios'] = "back_end/usuarios/index";
	$route['vistaUsuarios'] = "back_end/usuarios/vistaUsuarios";
	$route['listaUsuarios'] = "back_end/usuarios/listaUsuarios";
	$route['getDataUsuarios'] = "back_end/usuarios/getDataUsuarios";
	$route['formUsuario'] = "back_end/usuarios/formUsuario";
	$route['formCargaMasiva'] = "back_end/usuarios/formCargaMasiva";
	$route['excelUsuarios/(:any)'] = "back_end/usuarios/excelUsuarios/$1";

	$route['vistaCargos'] = "back_end/usuarios/vistaCargos";
	$route['listaCargos'] = "back_end/usuarios/listaCargos";
	$route['getDataCargos'] = "back_end/usuarios/getDataCargos";
	$route['formCargos'] = "back_end/usuarios/formCargos";
	$route['eliminaCargo'] = "back_end/usuarios/eliminaCargo";

	$route['vistaProyectos'] = "back_end/usuarios/vistaProyectos";
	$route['listaProyectos'] = "back_end/usuarios/listaProyectos";
	$route['getDataProyectos'] = "back_end/usuarios/getDataProyectos";
	$route['formProyectos'] = "back_end/usuarios/formProyectos";
	$route['eliminaProyectos'] = "back_end/usuarios/eliminaProyectos";
	
	$route['vistaAreas'] = "back_end/usuarios/vistaAreas";
	$route['listaAreas'] = "back_end/usuarios/listaAreas";
	$route['getDataAreas'] = "back_end/usuarios/getDataAreas";
	$route['formAreas'] = "back_end/usuarios/formAreas";
	$route['eliminaAreas'] = "back_end/usuarios/eliminaAreas";


	$route['vistaJefes'] = "back_end/usuarios/vistaJefes";
	$route['listaJefes'] = "back_end/usuarios/listaJefes";
	$route['getDataJefes'] = "back_end/usuarios/getDataJefes";
	$route['formJefes'] = "back_end/usuarios/formJefes";
	$route['eliminaJefes'] = "back_end/usuarios/eliminaJefes";
	
	$route['vistaPerfiles'] = "back_end/usuarios/vistaPerfiles";
	$route['listaPerfiles'] = "back_end/usuarios/listaPerfiles";
	$route['getDataPerfiles'] = "back_end/usuarios/getDataPerfiles";
	$route['formPerfiles'] = "back_end/usuarios/formPerfiles";
	$route['eliminaPerfiles'] = "back_end/usuarios/eliminaPerfiles";

	
/*******HERRAMIENTAS*******/

	$route['vistaHerramientas'] = "back_end/usuarios/vistaHerramientas";
	$route['listaHerramientas'] = "back_end/usuarios/listaHerramientas";
	$route['getDataHerramientas'] = "back_end/usuarios/getDataHerramientas";
	$route['formHerramientas'] = "back_end/usuarios/formHerramientas";
		
/*******PRODUCTIVIDAD*******/
	$route['productividad'] = "back_end/productividad/index";
	$route['vistaDetalle'] = "back_end/productividad/vistaDetalle";
	$route['listaDetalle'] = "back_end/productividad/listaDetalle";
	$route['getDataDetalle'] = "back_end/productividad/getDataDetalle";
	$route['excel_detalle/(:any)/(:any)/(:any)'] = "back_end/productividad/excel_detalle/$1/$2/$3";
	$route['formCargaMasivaDetalle'] = "back_end/productividad/formCargaMasivaDetalle";
	$route['actualizacionProductividad'] = "back_end/productividad/actualizacionProductividad";
	$route['vistaGraficosProd'] = "back_end/productividad/vistaGraficosProd";
	$route['dataGraficos'] = "back_end/productividad/dataGraficos";
	$route['listaTrabajadores'] = "back_end/productividad/listaTrabajadores";

	$route['vistaResumen'] = "back_end/productividad/vistaResumen";
	$route['getCabeceras'] = "back_end/productividad/getCabeceras";
	$route['listaResumen'] = "back_end/productividad/listaResumen";

/******************CALIDAD*************************/

	$route['calidad'] = "back_end/calidad/index";
	$route['vistaCalidad'] = "back_end/calidad/vistaCalidad";
	$route['listaCalidad'] = "back_end/calidad/listaCalidad";
	$route['getDataCalidad'] = "back_end/calidad/getDataCalidad";
	$route['excel_calidad/(:any)/(:any)/(:any)'] = "back_end/calidad/excel_calidad/$1/$2/$3";
	$route['formCargaMasivaCalidad'] = "back_end/calidad/formCargaMasivaCalidad";
	$route['actualizacionCalidad'] = "back_end/calidad/actualizacionCalidad";
	$route['vistaGraficosCalidad'] = "back_end/calidad/vistaGraficosCalidad";
	$route['dataGraficosCalidad'] = "back_end/calidad/dataGraficosCalidad";
	$route['getCabecerasCalidad'] = "back_end/calidad/getCabecerasCalidad";
	$route['listaResumenCalidad'] = "back_end/calidad/listaResumenCalidad";
	$route['graficoHFC'] = "back_end/calidad/graficoHFC";
	$route['graficoFTTH'] = "back_end/calidad/graficoFTTH";
	$route['listaTrabajadoresCalidad'] = "back_end/calidad/listaTrabajadoresCalidad";

/******************TICKET*************************/
	$route['ticket'] = "back_end/ticket/index";
	$route['getTicketInicio'] = "back_end/ticket/getTicketInicio";
	$route['getTicketList'] = "back_end/ticket/getTicketList";
	$route['formTicket'] = "back_end/ticket/formTicket";
	$route['eliminaTicket'] = "back_end/ticket/eliminaTicket";
	$route['getDataTicket'] = "back_end/ticket/getDataTicket";

/*****************TURNOS*******/
	$route['cao'] = "back_end/cao/index";
	$route['vistaTurnos'] = "back_end/cao/vistaTurnos";
	$route['listaTrabajadoresTurnos'] = "back_end/cao/listaTrabajadoresTurnos";
	$route['getCabecerasTurnos'] = "back_end/cao/getCabecerasTurnos";
	$route['listaTurnos'] = "back_end/cao/listaTurnos";
	$route['formTurnos'] = "back_end/cao/formTurnos";
	$route['getDataTurnos'] = "back_end/cao/getDataTurnos";
	$route['eliminarTurnos'] = "back_end/cao/eliminarTurnos";
	$route['excel_turnos/(:any)/(:any)/(:any)/(:any)/(:any)'] = "back_end/cao/excel_turnos/$1/$2/$3/$4/$5";

/******************LICENCIAS*************************/
	$route['vistaLicencias'] = "back_end/cao/vistaLicencias";
	$route['getLicenciasList'] = "back_end/cao/getLicenciasList";
	$route['formIngresoLicencias'] = "back_end/cao/formIngresoLicencias";
	$route['eliminaLicencias'] = "back_end/cao/eliminaLicencias";
	$route['getDataRegistroLicencias'] = "back_end/cao/getDataRegistroLicencias";
	$route['listaUsuariosS2'] = "back_end/cao/listaUsuariosS2";

/******************VACACIONES*************************/
	$route['vistaVacaciones'] = "back_end/cao/vistaVacaciones";
	$route['getVacacionesList'] = "back_end/cao/getVacacionesList";
	$route['formIngresoVacaciones'] = "back_end/cao/formIngresoVacaciones";
	$route['eliminaVacaciones'] = "back_end/cao/eliminaVacaciones";
	$route['getDataRegistroVacaciones'] = "back_end/cao/getDataRegistroVacaciones";

/******************MANTENEDOR TURNOS*************************/
	$route['vistaMantenedorTurnos'] = "back_end/cao/vistaMantenedorTurnos";
	$route['getMantenedorTurnosList'] = "back_end/cao/getMantenedorTurnosList";
	$route['formMantenedorTurnos'] = "back_end/cao/formMantenedorTurnos";
	$route['eliminaMantenedorTurnos'] = "back_end/cao/eliminaMantenedorTurnos";
	$route['getDataMantenedorTurnos'] = "back_end/cao/getDataMantenedorTurnos";



/* End of file routes.php */
/* Location: ./application/config/routes.php */