<?php


class Alamat{

	private $provinsi = []; 
	private $kota = []; 
	private $kecamatan = []; 
	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper('urlcapsule_helper');
		//$this->provinsi = get_provinsi();
		foreach (get_provinsi() as $k => $v) {
			$this->provinsi[$v['id']] = $v;
		}
		foreach (get_kota() as $k => $v) {
			$this->kota[$v["id"]] = $v;
		}
		foreach (get_kecamatan() as $k => $v) {
			$this->kecamatan[$v["id"]] = $v;
		}
	}


	public function get_provinsi($id='')
	{
		if($id!=""){
			return isset($this->provinsi[$id])?$this->provinsi[$id]:'';
		}
		return $this->provinsi;
	}

	public function get_kota($id='')
	{
		if($id!=""){
			return isset($this->kota[$id])?$this->kota[$id]:'';
		}
		return $this->kota;
	}

	public function get_kecamatan($id='')
	{
		if($id!=""){
			return isset($this->kecamatan[$id])?$this->kecamatan[$id]:'';
		}
		return $this->kecamatan;
	}

}
