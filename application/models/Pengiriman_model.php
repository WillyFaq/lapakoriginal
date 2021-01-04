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
	var $join4 = 'pelanggan';
	var $pk = 'id_pengiriman';
	var $fk1 = 'id_transaksi';
	var $fk2 = 'id_user';
	var $fk3 = 'id_gudang';
	var $fk4 = 'no_pelanggan';


	public function get_all()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, $this->table.'.'.$this->fk1.' = '.$this->join1.'.'.$this->fk1);
		$this->db->join($this->join4, $this->join1.'.'.$this->fk4.' = '.$this->join4.'.'.$this->fk4);
		$this->db->join($this->join2, $this->table.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		$this->db->order_by($this->table.".id_pengiriman", 'desc');
		return $this->db->get();
		/*$q =  $this->db->get_compiled_select();
		$q .= " ".$this->Sales_order_model->get_all_query();
		$q .= " ORDER BY pengiriman.id_transaksi DESC "; 
		//$this->db->order_by($this->table.'.tgl_kirim', 'desc');
		return $this->db->query($q);*/
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
		$this->db->join($this->join4, $this->join1.'.'.$this->fk4.' = '.$this->join4.'.'.$this->fk4);
		$this->db->join($this->join2, $this->table.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		$this->db->where($id);
		$this->db->order_by($this->table.".id_pengiriman", 'desc');
		return $this->db->get();
	}

	public function get_where2($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, $this->table.'.'.$this->fk1.' = '.$this->join1.'.'.$this->fk1);
		$this->db->join($this->join4, $this->join1.'.'.$this->fk4.' = '.$this->join4.'.'.$this->fk4);
		$this->db->join($this->join2, $this->join1.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		$this->db->where($id);
		$this->db->order_by($this->table.".id_pengiriman", 'desc');
		return $this->db->get();
	}

	public function get_where_like($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, $this->table.'.'.$this->fk1.' = '.$this->join1.'.'.$this->fk1);
		$this->db->join($this->join4, $this->join1.'.'.$this->fk4.' = '.$this->join4.'.'.$this->fk4);
		$this->db->like($id);
		$this->db->order_by($this->table.".id_pengiriman", 'desc');
		return $this->db->get();
	}

	public function get_where_like2($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, $this->table.'.'.$this->fk1.' = '.$this->join1.'.'.$this->fk1);
		$this->db->join($this->join4, $this->join1.'.'.$this->fk4.' = '.$this->join4.'.'.$this->fk4);
		$this->db->join($this->join2, $this->join1.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		$this->db->like($id);
		$this->db->order_by($this->table.".id_pengiriman", 'desc');
		return $this->db->get();
	}

	/*public function get_where($id)
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
	}

	public function get_where_like($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, $this->table.'.'.$this->fk1.' = '.$this->join1.'.'.$this->fk1);
		$this->db->join($this->join2, $this->table.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		$this->db->join($this->join3, $this->table.'.'.$this->fk3.' = '.$this->join3.'.'.$this->fk3);
		$q =  $this->db->get_compiled_select();
		$q .= " ".$this->Sales_order_model->get_all_query();
		$q .=  " WHERE ";
		$j=0;
		foreach ($id as $k => $v) {
			if($j!=0){
				$q .= " OR ";
			}
			$q .= "$k LIKE '%$v%' ";
			$j++;
		}
		$q .= " ORDER BY tgl_kirim DESC "; 
		return $this->db->query($q);
	}*/

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

	public function add_kirim($da)
	{	
		$idp = $da['id_pengiriman'];
		$gb = array(
					'id_gudang_user' => $da['id_gudang_user'],
					'kode_barang' => $da['kode_barang'],
					'jumlah_gb' => $da['jumlah'],
					'tgl_gb' => $da['tgl_kirim'],
					'ket_gb' => 2
					);
		unset($da['id_pengiriman'], $da['id_gudang_user'], $da['kode_barang'], $da['jumlah']);
		$this->db->trans_begin();
		$this->db->insert('gudang_barang', $gb);

		$this->db->where($this->pk, $idp);
		$this->db->update($this->table, $da);
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


	public function ubah_transaksi($idp, $pelanggan, $idt, $transaksi)
	{
		$this->db->trans_begin();

		$this->db->where("no_pelanggan", $idp);
		$this->db->update("pelanggan", $pelanggan);

		$this->db->where("id_transaksi", $idt);
		$this->db->update("sales_order", $transaksi);

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return false;
		}else{
		    $this->db->trans_commit();
		    return true;
		}
	}

	public function kirimkan($kirim, $gudang)
	{
		$this->db->trans_begin();
		$this->db->insert($this->table, $kirim);

		$this->db->insert_batch('gudang_barang', $gudang);

		$this->db->set('status_order', '1');
		$this->db->where($this->fk1, $kirim['id_transaksi']);
		$this->db->update($this->join1);

		$this->db->where('id_transaksi', $kirim['id_transaksi']);
		$this->db->delete('pending');

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return false;
		}else{
		    $this->db->trans_commit();
		    return true;
		}
	}

	public function pending_add($id)
	{
		return $this->db->insert('pending', ["id_transaksi" => $id]);
	}

	public function get_pending_id()
	{
		$q = $this->db->get('pending');
		$res = $q->result();
		$id = [];
		foreach ($res as $row) {
			$id[] = $row->id_transaksi; 
		}
		return "'".join("','", $id)."'";
	}

	public function get_pending()
	{
		$this->db->select('*');
		$this->db->from('pending');
		$this->db->join('sales_order', 'pending.id_transaksi = sales_order.id_transaksi');
		$this->db->join('pelanggan', 'sales_order.no_pelanggan = pelanggan.no_pelanggan');
		$this->db->join('user', 'sales_order.id_user = user.id_user');
		return $this->db->get();
	}

	public function get_pending_sales($id)
	{
		$this->db->select('*');
		$this->db->from('pending');
		$this->db->join('sales_order', 'pending.id_transaksi = sales_order.id_transaksi');
		$this->db->join('pelanggan', 'sales_order.no_pelanggan = pelanggan.no_pelanggan');
		$this->db->join('user', 'sales_order.id_user = user.id_user');
		$this->db->where('sales_order.id_user', $id);
		return $this->db->get();
	}

	public function delete_pending($id='')
	{
		$this->db->where('id_transaksi', $id);
		return $this->db->delete('pending');
	}
}

/* End of file Pengiriman_model.php */
/* Location: ./application/models/Pengiriman_model.php */