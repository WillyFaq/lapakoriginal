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
	

if(!function_exists('init_datatable_tooltips_ajax')){
	function init_datatable_tooltips_ajax(){
	    $ret = '<script>';
	    $ret .= 'if ($(window).width() < 768) {var table = $("#dttba").DataTable({"scrollX": true});}else{var table = $("#dttba").DataTable();}';
	    $ret .= '$(\'[data-toggle="tooltip"]\').tooltip();';
	    $ret .= '</script>';
		return $ret;
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



if(!function_exists('get_provinsi')){
	function get_provinsi($id='') {
		$prov = json_decode(file_get_contents('assets/provinsi.json'), true);
		$ret = [];
		if($id!=''){
			foreach ($prov as $k => $v) {
				if($v['id'] == $id){
					$ret = $v;
				}
			}
		}else{
			$ret = $prov;
		}
		return $ret;
	}
}
if(!function_exists('get_kota')){
	function get_kota($id='') {
		$prov = json_decode(file_get_contents('assets/kota.json'), true);
		$ret = [];
		if($id!=''){
			foreach ($prov as $k => $v) {
				if($v['id_provinsi'] == $id){
					$ret[] = $v;
				}
			}
			if(empty($ret)){
				foreach ($prov as $k => $v) {
					if($v['id'] == $id){
						$ret = $v;
					}
				}
			}
		}else{
			$ret = $prov;
		}
		return $ret;
	}
}
if(!function_exists('get_kecamatan')){
	function get_kecamatan($id='') {
		$prov = json_decode(file_get_contents('assets/kecamatan.json'), true);
		$ret = [];
		if($id!=''){
			foreach ($prov as $k => $v) {
				if($v['id_kota'] == $id){
					$ret[] = $v;
				}
			}
			if(empty($ret)){
				foreach ($prov as $k => $v) {
					if($v['id'] == $id){
						$ret = $v;
					}
				}
			}
		}else{
			$ret = $prov;
		}
		return $ret;
	}

if(!function_exists('get_type_feedback')){
	function get_type_feedback($bln="") {
		$bulan = [
					"Diproses",
					"Dikirm",
					"Diterima",
					"Ditolak",
					];
		return $bln==""?$bulan:$bulan[$bln];
	}
}
}

if(!function_exists('penyebut')){
	function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}
}

if(!function_exists('terbilang')){
	function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim(penyebut($nilai));
		} else {
			$hasil = trim(penyebut($nilai));
		}     		
		return $hasil;
	}
}

if(!function_exists('get_avatar')){
	function get_avatar($level) {
		return base_url('assets/img/user_'.$level.'.png');
	}
}