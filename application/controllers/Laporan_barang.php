<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_barang extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("Gudang_model", "", TRUE);
		$this->load->model("Gudang_barang_model", "", TRUE);
	}

	public function gen_table()
	{
		$da = [];
		if($this->session->userdata("user")->level==3){
			$ids = $this->session->userdata('user')->id_user;
			$sql = "SELECT * FROM gudang_user WHERE id_user = '$ids'";
			$q = $this->db->query($sql);
			$res = $q->result();
			$id_gudang = $res[0]->id_gudang;
			$da = array(
						"c.id_gudang" => $id_gudang
						);
		}	

		$query = $this->Gudang_barang_model->get_laporan($da);
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable2">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Nama Gudang', 'Kode Barang', 'Nama Barang', 'Tgl', 'Keterangan', 'Jumlah');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$ket = '<span class="badge badge-warning">Restok</span>';
				if($row->ket_gb == 2){
					$ket = '<span class="badge badge-success">Keluar</span>';
				}elseif($row->ket_gb == 3){
					$ket = '<span class="badge badge-danger">Ditolak</span>';
				}
				$kd = explode(".", $row->kode_brg);
				$nama_barang = $row->nama_barang;
				if(sizeof($kd)>1){
					$nama_barang .= isset($kd[1])?"<br>($kd[1]":"";
					$nama_barang .= isset($kd[2])?" - $kd[2]":"";
					$nama_barang .= ")";
				}
				$this->table->add_row(	++$i,
							$row->nama_gudang,
							$row->kode_barang,
							$nama_barang,
							date("d-m-Y", strtotime($row->tgl_gb)),
							$ket,
							number_format($row->jumlah_gb)
				);
			}
		}
		return $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "laporan/lap_barang_view",
						"ket" => "Laporan",
						'table' => $this->gen_table()
						);
		$this->load->view('index', $data);
	}

}

/* End of file Laporan_barang.php */
/* Location: ./application/controllers/Laporan_barang.php */