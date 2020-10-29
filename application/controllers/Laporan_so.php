<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_so extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("User_model", "", TRUE);
		$this->load->model("Sales_model", "", TRUE);
	}

	public function get_sales($id)
	{
		$q = $this->Sales_model->get_data($id);
		$res = $q->result();
		$ret = "";
		foreach ($res as $row) {
			$ret .= $row->nama_barang." (".$row->minimal_sale.")"."<br>";
		}
		return $ret;
	}

	public function gen_table($thn="", $bln="")
	{
		$query=$this->Sales_model->get_all();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Nama Sales', 'Barang (Minimal Penjualan)', 'Status', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$tot_penj = $this->Sales_model->get_tot_penjualan($row->id_user, $thn, $bln, $row->kode_barang);
				$d=cal_days_in_month(CAL_GREGORIAN,$bln,$thn);
				$target = $row->minimal_sale * $d;
				if($tot_penj>=$target){
					$sts = '<span class="badge badge-success">Target</span>';
				}else{
					$sts = '<span class="badge badge-danger">Tidak Target</span>';
				}
				$this->table->add_row(	++$i,
							$row->nama,
							$row->nama_barang.' ('.$row->minimal_sale.')',
							$sts,
							anchor('laporan_so/detail/'.e_url($bln)."/".e_url($row->id_user)."/".e_url($row->kode_barang)."/".e_url($thn), '<i class="fa fa-eye"></i>', array("class" => "btn btn-success btn-xs", "title" => "detail", 'data-toggle' => 'tooltip'))
						);
			}
		}

		echo $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "laporan/lap_so_view",
						"ket" => "Laporan",
						);
		$this->load->view('index', $data);
	}

	public function detail($bln, $id, $kode, $thn)
	{
		$data = array(
						"page" => "laporan/lap_so_view",
						"ket" => "Detail Laporan"
						);
		$bln = d_url($bln);
		$thn = d_url($thn);
		$id = d_url($id);
		$kode = d_url($kode);
		$d=cal_days_in_month(CAL_GREGORIAN,$bln,$thn);
		$data['bln'] = $bln;
		$query=$this->Sales_model->get_data($id);
		$res = $query->result();
		foreach ($res as $row) {
			$data['detail']['Nama'] = $row->nama;
			$data['detail']['target'] = $row->minimal_sale;
			$data['detail']['Barang (Minimal Penjualan)'] = $row->nama_barang.' ('.$row->minimal_sale.')';
			$data['detail']['Periode'] = get_bulan($bln)." ".$thn;
			$data['detail']['Target'] = $row->minimal_sale * $d;
		}
		$data['penjualan'] = $this->Sales_model->get_penjualan($id, $bln, $kode, $thn);
		$this->load->view('index', $data);
	}

}

/* End of file laporan_so.php */
/* Location: ./application/controllers/laporan_so.php */
