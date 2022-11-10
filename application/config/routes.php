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
	$route['infoUsuario'] = "inicio/infoUsuario";
	$route['cambiarPass'] = "inicio/cambiarPass";
	$route['verComo'] = "inicio/verComo";
	$route['recuperarPass'] = "inicio/recuperarPass";

/*************CRON******************/
	
	$route['saludoCumpleanios'] = "inicio/saludoCumpleanios";


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

/*******IGT*******/

	$route['igt'] = "back_end/igt/index";
	$route['getIgtInicio'] = "back_end/igt/getIgtInicio";
	$route['listaDetalleOtsDrive'] = "back_end/igt/listaDetalleOtsDrive";
	$route['dataIgt'] = "back_end/igt/dataIgt";
	$route['graficoProductividad'] = "back_end/igt/graficoProductividad";
	$route['excel_detalle_ots_drive/(:any)/(:any)/(:any)'] = "back_end/igt/excel_detalle_ots_drive/$1/$2/$3";
	$route['listaTrabajadoresIGT'] = "back_end/igt/listaTrabajadoresIGT";
	$route['dataGraficosIgt'] = "back_end/igt/dataGraficosIgt";

/*******DOCUMENTACION*******/

	$route['documentacion/capacitacion'] = "back_end/documentacion/indexCapacitacion";
	$route['vistaCapacitacion'] = "back_end/documentacion/vistaCapacitacion";
	$route['getCapacitacionList'] = "back_end/documentacion/getCapacitacionList";
	$route['getDataRegistroCapacitacion'] = "back_end/documentacion/getDataRegistroCapacitacion";
	$route['formIngresoCapacitacion'] = "back_end/documentacion/formIngresoCapacitacion";
	$route['eliminaCapacitacion'] = "back_end/documentacion/eliminaCapacitacion";

	$route['documentacion/reportes'] = "back_end/documentacion/indexReportes";
	$route['vistaReportes'] = "back_end/documentacion/vistaReportes";
	$route['getReportesList'] = "back_end/documentacion/getReportesList";
	$route['getDataRegistroReportes'] = "back_end/documentacion/getDataRegistroReportes";
	$route['formIngresoReportes'] = "back_end/documentacion/formIngresoReportes";
	$route['eliminaReportes'] = "back_end/documentacion/eliminaReportes";

	$route['documentacion/prevencion_riesgos'] = "back_end/documentacion/indexPrevencion";
	$route['vistaPrevencion'] = "back_end/documentacion/vistaPrevencion";
	$route['getPrevencionList'] = "back_end/documentacion/getPrevencionList";
	$route['getDataRegistroPrevencion'] = "back_end/documentacion/getDataRegistroPrevencion";
	$route['formIngresoPrevencion'] = "back_end/documentacion/formIngresoPrevencion";
	$route['eliminaPrevencion'] = "back_end/documentacion/eliminaPrevencion";

	$route['documentacion/datas_mandante'] = "back_end/documentacion/indexDatas";
	$route['vistaDatas'] = "back_end/documentacion/vistaDatas";
	$route['getDatasList'] = "back_end/documentacion/getDatasList";
	$route['getDataRegistroDatas'] = "back_end/documentacion/getDataRegistroDatas";
	$route['formIngresoDatas'] = "back_end/documentacion/formIngresoDatas";
	$route['eliminaDatas'] = "back_end/documentacion/eliminaDatas";


/*******AST*******/

	$route['ast'] = "back_end/ast/index";
	$route['vistaAst'] = "back_end/ast/vistaAst";
	$route['listaAst'] = "back_end/ast/listaAst";
	$route['getDataAst'] = "back_end/ast/getDataAst";
	$route['formAst'] = "back_end/ast/formAst";
	$route['eliminaAst'] = "back_end/ast/eliminaAst";
	$route['excel_ast/(:any)/(:any)'] = "back_end/ast/excel_ast/$1/$2";
	$route['generaPdfAstURL'] = "back_end/ast/generaPdfAstURL";
	$route['formCargaMasivaAst'] = "back_end/ast/formCargaMasivaAst";

	$route['vistaMantActividades'] = "back_end/ast/vistaMantActividades";
	$route['listaMantActividades'] = "back_end/ast/listaMantActividades";
	$route['getDataMantActividades'] = "back_end/ast/getDataMantActividades";
	$route['formMantActividades'] = "back_end/ast/formMantActividades";
	$route['eliminaMantActividades'] = "back_end/ast/eliminaMantActividades";
	$route['getChecklistView'] = "back_end/ast/getChecklistView";
	$route['getUserChecklistView'] = "back_end/ast/getUserChecklistView";
	$route['llenarMant'] = "back_end/ast/llenarMant";

/*******CHECKLIST*******/

	$route['checklist_herramientas'] = "back_end/checklist/checklist/index";
	$route['vistaChecklist'] = "back_end/checklist/checklist/vistaChecklist";
	$route['listaOTS'] = "back_end/checklist/checklist/listaOTS";
	$route['getDataChecklistHerramientas'] = "back_end/checklist/checklist/getDataChecklistHerramientas";
	$route['formOTS'] = "back_end/checklist/checklist/formOTS";
	$route['eliminaOTS'] = "back_end/checklist/checklist/eliminaOTS";
	$route['datosAuditor'] = "back_end/checklist/checklist/datosAuditor";
	$route['datosTecnico'] = "back_end/checklist/checklist/datosTecnico";
	$route['formCargaMasiva'] = "back_end/checklist/checklist/formCargaMasiva";
	$route['excel_checklist/(:any)/(:any)'] = "back_end/checklist/checklist/excel_checklist/$1/$2";
	$route['vistaGraficos'] = "back_end/checklist/checklist/vistaGraficos";
	$route['dataEstadosChecklist'] = "back_end/checklist/checklist/dataEstadosChecklist";
	$route['dataTecnicos'] = "back_end/checklist/checklist/dataTecnicos";
	$route['eliminaImagenChecklist'] = "back_end/checklist/checklist/eliminaImagenChecklist";
	$route['vistaGraficosH'] = "back_end/checklist/checklist/vistaGraficosH";
	$route['graficoFallosH'] = "back_end/checklist/checklist/graficoFallosH";
	$route['listaTrabajadoresCH'] = "back_end/checklist/checklist/listaTrabajadoresCH";
	$route['generaPdfChecklistHerramientasURL'] = "back_end/checklist/checklist/generaPdfChecklistHerramientasURL";

	/*******FALLOS*******/
	
		$route['vistaFH'] = "back_end/checklist/checklist/vistaFH";
		$route['listaFH'] = "back_end/checklist/checklist/listaFH";
		$route['getDataFH'] = "back_end/checklist/checklist/getDataFH";
		$route['formFH'] = "back_end/checklist/checklist/formFH";


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
	$route['eliminaImagenChecklistHFC'] = "back_end/checklist/ChecklistHFC/eliminaImagenChecklistHFC";
	$route['vistaGraficosFallosHFC'] = "back_end/checklist/ChecklistHFC/vistaGraficosFallosHFC";
	$route['graficoFallosHFC'] = "back_end/checklist/ChecklistHFC/graficoFallosHFC";
	$route['listaTrabajadoresHFC'] = "back_end/checklist/ChecklistHFC/listaTrabajadoresHFC";
	$route['generaPdfChecklistHFCURL'] = "back_end/checklist/ChecklistHFC/generaPdfChecklistHFCURL";

	$route['vistaFHFC'] = "back_end/checklist/ChecklistHFC/vistaFHFC";
	$route['listaFHFC'] = "back_end/checklist/ChecklistHFC/listaFHFC";
	$route['getDataFHFC'] = "back_end/checklist/ChecklistHFC/getDataFHFC";
	$route['formFHFC'] = "back_end/checklist/ChecklistHFC/formFHFC";

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
	$route['eliminaImagenChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/eliminaImagenChecklistFTTH";
	$route['vistaGraficosFallosFTTH'] = "back_end/checklist/ChecklistFTTH/vistaGraficosFallosFTTH";
	$route['graficoFallosFTTH'] = "back_end/checklist/ChecklistFTTH/graficoFallosFTTH";
	$route['listaTrabajadoresFTTH'] = "back_end/checklist/ChecklistFTTH/listaTrabajadoresFTTH";
	$route['generaPdfChecklistFTTHURL'] = "back_end/checklist/ChecklistFTTH/generaPdfChecklistFTTHURL";

	$route['vistaFFTTH'] = "back_end/checklist/ChecklistFTTH/vistaFFTTH";
	$route['listaFFTTH'] = "back_end/checklist/ChecklistFTTH/listaFFTTH";
	$route['getDataFFTTH'] = "back_end/checklist/ChecklistFTTH/getDataFFTTH";
	$route['formFFTTH'] = "back_end/checklist/ChecklistFTTH/formFFTTH";

		
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
	$route['listaResumen2'] = "back_end/productividad/listaResumen2";

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


/*******MANTENEDORES*******/
	$route['mantenedor_usuarios'] = "back_end/mantenedores/usuarios/index";
	$route['vistaUsuarios'] = "back_end/mantenedores/usuarios/vistaUsuarios";
	$route['listaUsuarios'] = "back_end/mantenedores/usuarios/listaUsuarios";
	$route['getDataUsuarios'] = "back_end/mantenedores/usuarios/getDataUsuarios";
	$route['formUsuario'] = "back_end/mantenedores/usuarios/formUsuario";
	$route['formCargaMasiva'] = "back_end/mantenedores/usuarios/formCargaMasiva";
	$route['excelUsuarios/(:any)'] = "back_end/mantenedores/usuarios/excelUsuarios/$1";

	$route['vistaCargos'] = "back_end/mantenedores/usuarios/vistaCargos";
	$route['listaCargos'] = "back_end/mantenedores/usuarios/listaCargos";
	$route['getDataCargos'] = "back_end/mantenedores/usuarios/getDataCargos";
	$route['formCargos'] = "back_end/mantenedores/usuarios/formCargos";
	$route['eliminaCargo'] = "back_end/mantenedores/usuarios/eliminaCargo";

	$route['vistaProyectos'] = "back_end/mantenedores/usuarios/vistaProyectos";
	$route['listaProyectos'] = "back_end/mantenedores/usuarios/listaProyectos";
	$route['getDataProyectos'] = "back_end/mantenedores/usuarios/getDataProyectos";
	$route['formProyectos'] = "back_end/mantenedores/usuarios/formProyectos";
	$route['eliminaProyectos'] = "back_end/mantenedores/usuarios/eliminaProyectos";

	$route['vistaAreas'] = "back_end/mantenedores/usuarios/vistaAreas";
	$route['listaAreas'] = "back_end/mantenedores/usuarios/listaAreas";
	$route['getDataAreas'] = "back_end/mantenedores/usuarios/getDataAreas";
	$route['formAreas'] = "back_end/mantenedores/usuarios/formAreas";
	$route['eliminaAreas'] = "back_end/mantenedores/usuarios/eliminaAreas";


	$route['vistaJefes'] = "back_end/mantenedores/usuarios/vistaJefes";
	$route['listaJefes'] = "back_end/mantenedores/usuarios/listaJefes";
	$route['getDataJefes'] = "back_end/mantenedores/usuarios/getDataJefes";
	$route['formJefes'] = "back_end/mantenedores/usuarios/formJefes";
	$route['eliminaJefes'] = "back_end/mantenedores/usuarios/eliminaJefes";

	$route['vistaPerfiles'] = "back_end/mantenedores/usuarios/vistaPerfiles";
	$route['listaPerfiles'] = "back_end/mantenedores/usuarios/listaPerfiles";
	$route['getDataPerfiles'] = "back_end/mantenedores/usuarios/getDataPerfiles";
	$route['formPerfiles'] = "back_end/mantenedores/usuarios/formPerfiles";
	$route['eliminaPerfiles'] = "back_end/mantenedores/usuarios/eliminaPerfiles";

/*MANTENEDORES*/

	$route['mantenedor_herramientas'] = "back_end/mantenedores/herramientas/index";
	$route['vistaHerramientas'] = "back_end/mantenedores/herramientas/vistaHerramientas";
	$route['listaHerramientas'] = "back_end/mantenedores/herramientas/listaHerramientas";
	$route['getDataHerramientas'] = "back_end/mantenedores/herramientas/getDataHerramientas";
	$route['formHerramientas'] = "back_end/mantenedores/herramientas/formHerramientas";
	$route['eliminaHerramienta'] = "back_end/mantenedores/herramientas/eliminaHerramienta";
	$route['excelHerramientas/(:any)'] = "back_end/mantenedores/herramientas/excelHerramientas/$1";
	
	$route['mantenedor_responsables_fallos'] = "back_end/mantenedores/responsable_fallos/index";
	$route['vistaResponsablesFallosHerramientas'] = "back_end/mantenedores/responsable_fallos/vistaResponsablesFallosHerramientas";
	$route['listaResponsablesFallosHerramientas'] = "back_end/mantenedores/responsable_fallos/listaResponsablesFallosHerramientas";
	$route['formResponsablesFallosHerramientas'] = "back_end/mantenedores/responsable_fallos/formResponsablesFallosHerramientas";
	$route['getDataResponsableFallosHerramientas'] = "back_end/mantenedores/responsable_fallos/getDataResponsableFallosHerramientas";
	$route['eliminaResponsableFallosHerramientas'] = "back_end/mantenedores/responsable_fallos/eliminaResponsableFallosHerramientas";
	
	$route['mantenedor_checklist_hfc'] = "back_end/mantenedores/checklistHFC/index";
	$route['vistaMantChecklistHFC'] = "back_end/mantenedores/checklistHFC/vistaMantChecklistHFC/index";
	$route['listaMantChecklistHFC'] = "back_end/mantenedores/checklistHFC/listaMantChecklistHFC";
	$route['getDataMantChecklistHFC'] = "back_end/mantenedores/checklistHFC/getDataMantChecklistHFC";
	$route['formMantChecklistHFC'] = "back_end/mantenedores/checklistHFC/formMantChecklistHFC";
	$route['eliminaMantChecklistHFC'] = "back_end/mantenedores/checklistHFC/eliminaMantChecklistHFC";
	$route['excelMantChecklistHFC/(:any)'] = "back_end/mantenedores/checklistHFC/excelMantChecklistHFC/$1";

    $route['mantenedor_checklist_ftth'] = "back_end/mantenedores/checklistFTTH/index";
	$route['vistaMantChecklistFTTH'] = "back_end/mantenedores/checklistFTTH/vistaMantChecklistFTTH/index";
	$route['listaMantChecklistFTTH'] = "back_end/mantenedores/checklistFTTH/listaMantChecklistFTTH";
	$route['getDataMantChecklistFTTH'] = "back_end/mantenedores/checklistFTTH/getDataMantChecklistFTTH";
	$route['formMantChecklistFTTH'] = "back_end/mantenedores/checklistFTTH/formMantChecklistFTTH";
	$route['eliminaMantChecklistFTTH'] = "back_end/mantenedores/checklistFTTH/eliminaMantChecklistFTTH";
	$route['excelMantChecklistFTTH/(:any)'] = "back_end/mantenedores/checklistFTTH/excelMantChecklistFTTH/$1";
	
	$route['mantenedor_metas_igt'] = "back_end/mantenedores/metasIgt/index";
	$route['vistaMetasIgt'] = "back_end/mantenedores/metasIgt/vistaMetasIgt";
	$route['listaMetasIgt'] = "back_end/mantenedores/metasIgt/listaMetasIgt";
	$route['getDataMetasIgt'] = "back_end/mantenedores/metasIgt/getDataMetasIgt";
	$route['formMetasIgt'] = "back_end/mantenedores/metasIgt/formMetasIgt";
	$route['eliminaMetasIgt'] = "back_end/mantenedores/metasIgt/eliminaMetasIgt";
	$route['actualizarMetaActual'] = "back_end/mantenedores/metasIgt/actualizarMetaActual";


/* End of file routes.php */
/* Location: ./application/config/routes.php */