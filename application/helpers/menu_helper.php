<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('test_method')){
    function menu(){
      $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $end = end((explode('/', rtrim($url, '/'))));
      if(preg_match("/inicio\b/i",$end)){
        ?>
        <li class="active"><a href="<?php echo base_url()?>inicio"><span class="glyphicon glyphicon-book"></span> Facturas</a></li>
        <li style="margin-left:15px;"><a href="<?php echo base_url()?>reportes"><span class="glyphicon glyphicon-stats"></span> Reportes</a></li>
        <?php
      }
      elseif (preg_match("/reportes\b/i",$end)) {
      ?>
        <li><a href="<?php echo base_url()?>inicio"><span class="glyphicon glyphicon-book"></span> Facturas</a></li>
        <li class="active" style="margin-left:15px;"><a href="<?php echo base_url()?>reportes"><span class="glyphicon glyphicon-stats"></span> Reportes</a></li>
      <?php
      }
    }
}