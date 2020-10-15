<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback_model extends CI_Model {

	
	public function __construct() 
	{ 
		parent::__construct(); 
	} 

	var $table = 'feedback';
	var $pk = 'id_feedback';

	public function get_all()
	{
		return $this->db->get($this->table);
	}

	public function get_data($id)
	{
		$this->db->group_by($this->table.'.'.$this->pk);
		return $this->db->get($this->table);
	}

	public function get_where($id)
	{			
		$this->db->where($id);
		return $this->db->get($this->table);
	}

	public function add($da)
	{
		return $this->db->insert($this->table, $da);
	}

	public function update($da, $_id)
	{
		$this->db->where($this->pk, $_id);
		return $this->db->update($this->table, $da);
	}

	public function delete($id)
	{
		return $this->db->delete($this->table, array($this->pk => $id));
	}

}

/* End of file Feedback_model.php */
/* Location: ./application/models/Feedback_model.php */