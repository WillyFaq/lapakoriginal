<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengiriman_model extends CI_Model {

	public function __construct() 
	{ 
		parent::__construct(); 
	} 

	var $table = 'pengiriman';
	var $join1 = 'sales_order';
	var $join2 = 'user';
	var $join3 = 'gudang';
	var $pk = 'id_pengiriman';
	var $fk1 = 'id_transaksi';
	var $fk2 = 'id_user';
	var $fk3 = 'id_gudang';


	public function get_all()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, $this->table.'.'.$this->fk1.' = '.$this->join1.'.'.$this->fk1);
		$q =  $this->db->get_compiled_select();
		$q .= " ".$this->Sales_order_model->get_all_query();
		$q .= " ORDER BY tgl_kirim DESC "; 
		//$this->db->order_by($this->table.'.tgl_kirim', 'desc');
		return $this->db->query($q);
	}

	public function get_data($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, $this->table.'.'.$this->fk1.' = '.$this->join1.'.'.$this->fk1);
		$this->db->join($this->join2, $this->table.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		$this->db->join($this->join3, $this->table.'.'.$this->fk3.' = '.$this->join3.'.'.$this->fk3);
		$this->db->where(array($this->table.".".$this->pk => $id));
		return $this->db->get();
	}

	public function get_where($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, $this->table.'.'.$this->fk1.' = '.$this->join1.'.'.$this->fk1);
		$this->db->join($this->join2, $this->table.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		$this->db->join($this->join3, $this->table.'.'.$this->fk3.' = '.$this->join3.'.'.$this->fk3);
		$q =  $this->db->get_compiled_select();
		$q .= " ".$this->Sales_order_model->get_all_query();
		$q .=  " WHERE ";
		foreach ($id as $k => $v) {
			$q .= "$k = $v";
		}
		$q .= " ORDER BY tgl_kirim DESC "; 
		return $this->db->query($q);
		/*
		$this->db->where($id);
		return $this->db->get();
		*/
	}

	public function add($da)
	{	
		$this->db->trans_begin();
		$this->db->insert($this->table, $da);

		$this->db->set(['status_order' => 1]);
		$this->db->where($this->fk1, $da['id_transaksi']);
		$this->db->update($this->join1);

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
		$this->db->set($data);
		$this->db->where($this->pk, $_id);
		return $this->db->update($this->table);
	}

	public function delete($id)
	{
		return $this->db->delete($this->table, array($this->pk => $id));
	}

}

/* End of file Pengiriman_model.php */
/* Location: ./application/models/Pengiriman_model.php */