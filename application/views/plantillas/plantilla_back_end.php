<?php
$this->load->view('plantillas/back_end/header');
$this->load->view('back_end/' . $contenido, isset($tipo) ? ['tipo' => $tipo] : []);
$this->load->view('plantillas/back_end/footer');

