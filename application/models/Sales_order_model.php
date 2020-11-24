<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order_model extends CI_Model {

	public function __construct() 
	{ 
		parent::__construct(); 
	} 

	var $table = 'sales_order';
	var $join = 'pelanggan';
	var $join2 = 'barang';
	var $pk = 'id_transaksi';
	var $fk = 'no_pelanggan';
	var $fk2 = 'kode_barang';

	public function get_all_query()
	{
		$this->db->join($this->join, $this->table.'.'.$this->fk.' = '.$this->join.'.'.$this->fk);
		$this->db->join($this->join2, $this->table.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		$q =  $this->db->get_compiled_select();
		$q = str_replace('SELECT', "", $q);
		$q = str_replace('*', "", $q);
		return $q;
	}

	public function get_all()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join, $this->table.'.'.$this->fk.' = '.$this->join.'.'.$this->fk);
		$this->db->join($this->join2, $this->table.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		return $this->db->get();
	}

	public function get_data($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join, $this->table.'.'.$this->fk.' = '.$this->join.'.'.$this->fk);
		$this->db->join($this->join2, $this->table.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		$this->db->where(array($this->table.".".$this->pk => $id));
		return $this->db->get();
	}

	public function get_where($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join('user', 'user.id_user = sales_order.id_user');
		$this->db->join($this->join, $this->table.'.'.$this->fk.' = '.$this->join.'.'.$this->fk);
		$this->db->join($this->join2, $this->table.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		$this->db->where($id);
		$this->db->order_by("$this->table.tgl_order", 'desc');
		return $this->db->get();
	}

	public function gen_idtrans()
	{
		$d = date("Y-m-d");
		$da = date("Ymd");
		$this->db->where("DATE(tgl_order)", $d);
		$q = $this->db->get($this->table);
		$nr = $q->num_rows();
		$nr++;
		$no = "000000".$nr;
		$no = substr($no, strlen($no)-6, strlen($no));
		return "T$da$no"; 
	}

	public function add($pel, $ord)
	{
		$this->db->trans_begin();
		$this->db->insert($this->join, $pel);
		$this->db->insert($this->table, $ord);

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return false;
		}else{
		    $this->db->trans_commit();
		    return true;
		}
	}

	public function update($data, $_id)
	{
		/*$this->db->set($data);
		$this->db->where($this->pk, $_id);
		return $this->db->update($this->table);*/


		/*$kode_barang = $data['kode_barang'];
		$minimal = $data['minimal_sale'];
		unset($data['id_sales_barang']);
		unset($data['kode_barang']);
		unset($data['minimal_sale']);

		$this->db->trans_begin();
		
		$this->db->set($data);
		$this->db->where($this->pk, $_id);
		$this->db->update($this->table);

		$this->db->delete($this->join, array($this->pk => $_id));

		foreach ($kode_barang as $k => $v) {
			$dada = array(
						'id_user' => $_id,
						'kode_barang' => $v,
						'minimal_sale' => $minimal[$k]
						);
			$this->db->insert($this->join, $dada);
		}

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return false;
		}else{
		    $this->db->trans_commit();
		    return true;
		}*/
	}


	public function delete($id)
	{
		return $this->db->delete($this->table, array($this->pk => $id));
	}

	public function cek_laporan($id)
	{
		
		$sql = "SELECT * FROM pengiriman WHERE id_transaksi = '$id'";
		$q =  $this->db->query($sql);
		$res = $q->result();
		foreach ($res as $row) {
			return $row->status_pengiriman;
		}
	}

}

/* End of file Sales_order_model.php */
/* Location: ./application/models/Sales_order_model.php */