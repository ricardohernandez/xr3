<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! empty($_SERVER['HTTPS'])){
    $dominio= 'https://'.$_SERVER['HTTP_HOST'];
}else{
    $dominio = 'http://'.$_SERVER['HTTP_HOST'];
}

$config['base_url'] = $dominio.preg_replace('@/+$@', '', dirname($_SERVER['SCRIPT_NAME'])).'/';
$config['index_page'] = '';
$config['uri_protocol']	= 'AUTO';
$config['url_suffix'] = '';
$config['language']	= 'spanish';
$config['charset'] = 'UTF-8';
$config['enable_hooks'] = FALSE;
$config['subclass_prefix'] = 'MY_';
$config['composer_autoload'] = 'vendor/autoload.php';
$config['permitted_uri_chars'] = 'a-z 0-9~%\.\:_\+-,?&=-';

$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';

$config['log_threshold'] = 0;
$config['log_path'] = '';
$config['log_file_extension'] = '';
$config['log_file_permissions'] = 0644;
$config['log_date_format'] = 'Y-m-d H:i:s';
$config['error_views_path'] = '';
$config['cache_path'] = '';
$config['cache_query_string'] = FALSE;

$config['encryption_key'] = 'fh4kdkkkaoe30njgoe92rkdkkobec333';
$config['sess_driver'] = 'database';
$config['sess_cookie_name'] = 'ci_session7';
$config['sess_expiration'] = 0;
$config['sess_save_path'] = 'ci_sessions';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= FALSE;
$config['standardize_newlines'] = FALSE;
$config['global_xss_filtering'] = FALSE;

$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_token';
$config['csrf_cookie_name'] = 'csrf_cookie';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();

$config['compress_output'] = FALSE;
$config['time_reference'] = 'local';
$config['rewrite_short_tags'] = FALSE;
$config['proxy_ips'] = '';


$config['mailtype'] = getenv('SMTP_MAILTYPE') ?: 'html';
$config['charset'] = getenv('SMTP_CHARSET') ?: 'utf-8';
$config['priority'] = getenv('SMTP_PRIORITY') ?: '1';
$config['wordwrap'] = getenv('SMTP_WORDWRAP') ?: TRUE;
$config['protocol'] = getenv('SMTP_PROTOCOL') ?: 'smtp';
$config['smtp_port'] = getenv('SMTP_PORT') ?: 587;
$config['smtp_host'] = getenv('smtp_host') ?: 'mail.xr3t.cl';
$config['smtp_user'] = getenv('SMTP_USER') ?: 'syr@xr3t.cl';
$config['smtp_pass'] = getenv('SMTP_PASS') ?: 'ZBg;EVwGcIY1';
