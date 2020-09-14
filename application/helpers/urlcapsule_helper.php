<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('test_method')){
    function test_method($var = ''){
        return $var;
    }   
}

if(!function_exists('e_url')){
	function e_url( $s ) {
		return rtrim(strtr(base64_encode($s), '+/', '-_'), '='); 
	}
}
	 
if(!function_exists('d_url')){
	function d_url($s) {
		return base64_decode(str_pad(strtr($s, '-_', '+/'), strlen($s) % 4, '=', STR_PAD_RIGHT));
	}
}

if(!function_exists('print_pre')){
	function print_pre($array= array()){
	    echo "<pre>";
	    print_r($array);
	    echo "</pre>";
	}
}

if(!function_exists('init_datatable_tooltips')){
	function init_datatable_tooltips(){
	    echo '<script>';
	    echo 'if ($(window).width() < 768) {var table = $(".dataTableModal").DataTable({"scrollX": true});}else{var table = $(".dataTableModal").DataTable();}';
	    echo '$(\'[data-toggle="tooltip"]\').tooltip();';
	    echo '</script>';
	}
}
	 
if(!function_exists('e_password')){
	function e_password($s) {
		$hash = "";
	    for($i=0; $i < strlen($s); $i++){
	        $letterAscii = ord($s[$i]);
	        $letterAscii++;
	        $hash .= chr($letterAscii);
	    }
	    return base64_encode($hash);
	}
}
	 
if(!function_exists('d_password')){
	function d_password($s) {
	    $s = base64_decode($s);
	    $pass = "";
	    for($i=0; $i < strlen($s); $i++){
	        $letterAscii = ord($s[$i]);
	        $letterAscii--;
	        $pass .= chr($letterAscii);
	    }
	    return $pass;
	}
}

if(!function_exists('alert_notif')){
	function alert_notif($type) {
		$ci =& get_instance();
	    if($type=="success"){
	    	$ci->session->set_flashdata('msg_title', 'Sukses!');
			$ci->session->set_flashdata('msg_status', 'alert-success');
			$ci->session->set_flashdata('msg', 'Data berhasil disimpan! ');
	    }else if($type==="danger"){
	    	$ci->session->set_flashdata('msg_title', 'Terjadi Kesalahan!');
			$ci->session->set_flashdata('msg_status', 'alert-danger');
			$ci->session->set_flashdata('msg', 'Data gagal disimpan! ');
	    }
	}
}

if(!function_exists('get_bulan')){
	function get_bulan($bln="") {
		$bulan = [
					"",
					"Januari",
					"Februari",
					"Maret",
					"April",
					"Mei",
					"Juni",
					"Juli",
					"Agustus",
					"Septermber",
					"Oktober",
					"November",
					"Desember",
					];
		unset($bulan[0]);
		return $bln==""?$bulan:$bulan[$bln];
		/*$ci =& get_instance();
	    if($type=="success"){
	    	$ci->session->set_flashdata('msg_title', 'Sukses!');
			$ci->session->set_flashdata('msg_status', 'alert-success');
			$ci->session->set_flashdata('msg', 'Data berhasil disimpan! ');
	    }else if($type==="danger"){
	    	$ci->session->set_flashdata('msg_title', 'Terjadi Kesalahan!');
			$ci->session->set_flashdata('msg_status', 'alert-danger');
			$ci->session->set_flashdata('msg', 'Data gagal disimpan! ');
	    }*/
	}
}