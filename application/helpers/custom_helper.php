<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function trace($var = '')
{
    echo("[" . $var . "]");
}

function echoln($str)
{
    echo($str . "\n");
}

function convert_identifier($str)
{
    $str = strtolower($str);
    $str = preg_replace("/[^a-z0-9_\s-]/", "", $str);
    $str = preg_replace("/[\s-]+/", " ", $str);
    $str = preg_replace("/[\s_]/", "-", $str);
    return $str;
}

function printObject($obj)
{
    echo("<pre>");
    print_r($obj);
    echo("</pre>");
}

function randomHash()
{
    return md5(microtime() * microtime());
}

function moneyFloat($val)
{
    return(number_format($val, 2));
}

function generateLangOptions()
{
    $CI =& get_instance();
    $languages = $CI->config->item('available_language');
    $current_language = $CI->session->Userdata('language');

    foreach ($languages as $key=>$language) {
        if ($key==$current_language) {
            echoln("<option selected value='" . $key . "'>" . $language . "</option>");
        } else {
            echoln("<option value='" . $key . "'>" . $language . "</option>");
        }
    }
}

function langPH($ph, $val)
{
    echo sprintf($ph, $val);
}

function displayTF($bool)
{
    if ($bool) {
        echo(lang("yes"));
    } else {
        echo(lang("no"));
    }
}

function displayCheck($checkItem)
{
    if ($checkItem) {
        echo(" CHECKED");
    }
}

function forward_email($from, $from_email, $to_name, $to_email, $subject, $body, $cc="", $bcc="")
{
    $headers = "From: " . $from . " <" . $from_email . ">\r\nReply-To: " . $from. " <" . $from_email . ">\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8\r\n';
    $headers .= 'MIME-Version: 1.0\r\n' ;
    $headers .= 'Bcc: ' . $bcc . "\r\n";

    $to = "\"" . $to_name . "\" <" . $to_email .  ">";

    if ($cc !="") {
        $headers .= 'Cc: ' . $cc . "\r\n";
    }
    if ($bcc != "") {
        $headers .= 'Bcc: ' . $bcc . "\r\n";
    }

    ini_set("sendmail_from", $from_email);
    $status = mail($to_email, sprintf('=?UTF-8?B?%s?=', base64_encode($subject)), $body, $headers);
    trace($status);
}


function restoredb($file)
{
    $CI =& get_instance();
    if (file_exists($file))
    {
        $lines = file($file);
        $statement = '';
        foreach ($lines as $line)
        {
            $statement .= $line;
            if (substr(trim($line), -1) === ';')
            {
                $CI->db->simple_query($statement);
                $statement = '';
            }
        }
    }
}

function processTime($timeString, $IsLower=true)
{
    $time_array = explode(":", $timeString);
    $min = intval($time_array[1]);

    if ($IsLower) {
        $min = floor($min/5) * 5;
        $min = substr("00" . $min, -2);
        $timeString = $time_array[0] . ":" . $min;
    } else {
        $min = ceil($min/5) * 5;
        $min = substr("00" . $min, -2);
        $timeString = $time_array[0] . ":" . $min;
    }

    return $timeString;
}

function CSVText($value)
{
    $value = str_replace('"', '""', $value);
    if (preg_match('/,/', $value) or preg_match("/\n/", $value) or preg_match('/"/', $value)) {
        return '"'.$value.'"';
    } else {
        return $value;
    }
}

function formatDate($date, $format = 'g:ia d/m/y')
{
    if(strtotime($date)) {
        return date($format, strtotime($date));
    }
    return null;
}

function execInBackground($cmd) {
    if (substr(php_uname(), 0, 7) == "Windows") {
        $cmd = "start /B ". $cmd;
        pclose(popen($cmd, "w"));
    } else {
        exec($cmd . " > /dev/null &");
    }
}

function getTimeDifferenceInMinutes($d1, $d2) {
    $d1 = new DateTime($d1);
    $d2 = new DateTime($d2);
    return abs(floor(($d2->getTimestamp() - $d1->getTimestamp()) / 60));
}

function addGlobalErrorMessage($msg) {
    if(isset($_SESSION['globalErrorMessages']) && count($_SESSION['globalErrorMessages'])) {
        $_SESSION['globalErrorMessages'][] = $msg;
    } else {
        $_SESSION['globalErrorMessages'] = array($msg);
    }
}

/**
 * Flash a message once on another page
 * @param message message to display
 * @param type style of the alert box. can be success, info, warning, or danger.
 */
function flashGlobalMessage($message, $header = '', $type = 'success')
{
	$CI =& get_instance();
    $CI->session->set_flashdata('globalMessage', [
        'type' => $type,
        'header' => $header,
        'message' => $message,
    ]);
}

/**
 * Generate message template
 */
function printGlobalMessage()
{
	$CI =& get_instance();
    $globalMessage = $CI->session->flashdata('globalMessage');

    if (!is_array($globalMessage)) {
        return;
    }

    $header = element('header', $globalMessage) ? '<strong>' . $globalMessage['header'] . '</strong><br>' : '';
    $message = element('message', $globalMessage, '');
    $type = element('type', $globalMessage, 'success');

    echo "<div class='alert alert-$type'>
        $header
        $message
    </div>";
}

function checkAdmin()
{
	$CI =& get_instance();
	$login = $CI->session->Userdata('login');
	if(!$login){
		redirect("");
	}
}

function cdn_url($str = '') {
    $CI =& get_instance();
    $cdnUrl = $CI->config->item('cdn_url');
    return rtrim($cdnUrl, '/') . '/' . ($str ? ltrim($str, '/') : '');
}

function is_valid_url($str) {
    return filter_var($str, FILTER_VALIDATE_URL);
}

function baseUrl($url) {
    return filter_var($url, FILTER_VALIDATE_URL) ? $url : base_url($url);
}

function debug_dev($var) {
    print "<pre>";
    print_r($var);
    print "</pre>";
    die();
}

function convertToSlug($str) {
    // Convert string to lowercase
    $str = strtolower($str);
    
    // Replace all special characters with spaces
    $str = preg_replace('/[^a-z0-9\s]/', ' ', $str);
    
    // Replace spaces with underscores
    $str = str_replace(' ', '_', $str);
    
    return $str;
}


function add_log($subject,$content){
	$CI =& get_instance();
    $CI->load->model('Logs_model');
    $settings = $CI->Logs_model->add_log($subject, $content);
}

function getSettings(){
	$CI =& get_instance();
    $CI->load->model('Settings_model');
	$settings = $CI->Settings_model->getall();
	$return = array();

	foreach($settings as $setting){
	
		$return[$setting->SeName] = $setting->SeSettings;
	}
	return $return;
}

	function getDataOnline($url){

		$ch = curl_init();
		// if($_SERVER['SERVER_NAME'] == "localhost"){
			// $url = "https://stonehavensys.com/ajax/checkUnprintedOnline";
		// }else{
			// $url = base_url('ajax/checkUnprintedOnline');
		// }

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);

		if(curl_errno($ch)) {

			return 'curl error: ' . curl_error($ch);
		}
		
		return $response;
	
		
	}
