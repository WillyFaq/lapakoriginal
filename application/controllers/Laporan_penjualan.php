<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_penjualan extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("Barang_model", "", TRUE);
	}

	public function gen_table_harian($tgl1 = "", $tgl2 = "")
	{
		$query=$this->Barang_model->laporan_harian($tgl1, $tgl2);
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Kode Barang', 'Nama Barang', 'Tanggal', 'Total Penjualan', 'Laba Penjuaalan');

			$tot = 0;
			$laba = 0;
		if ($num_rows > 0)
		{
			$i = 0;
			foreach ($res as $row){
				$tot += $row->total_order;
				$laba += $row->laba_penjualan;
				$this->table->add_row(	++$i,
							$row->kode_barang,
							$row->nama_barang,
							date("d-m-Y", strtotime($row->tgl_order)),
							'Rp. '.number_format($row->total_order),
							'Rp. '.number_format($row->laba_penjualan)
						);
			}
		}

		$this->table->set_footer(
								array('data' => 'Total', "colspan" => 4),
								'Rp. '.number_format($tot),
								'Rp. '.number_format($laba)
								);

		echo $this->table->generate();
	}

	public function gen_table_bulanan($bln="")
	{
		$query=$this->Barang_model->laporan_bulanan($bln);
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Kode Barang', 'Nama Barang', 'Bulan', 'Total Penjualan', 'Laba Penjuaalan');

			$tot = 0;
			$laba = 0;
		if ($num_rows > 0)
		{
			$i = 0;
			foreach ($res as $row){
				$tot += $row->total_order;
				$laba += $row->laba_penjualan;
				$this->table->add_row(	++$i,
							$row->kode_barang,
							$row->nama_barang,
							get_bulan($row->bulan),
							'Rp. '.number_format($row->total_order),
							'Rp. '.number_format($row->laba_penjualan)
						);
			}
		}

		$this->table->set_footer(
								array('data' => 'Total', "colspan" => 4),
								'Rp. '.number_format($tot),
								'Rp. '.number_format($laba)
								);

		echo  $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "laporan/lap_penjualan_view",
						"ket" => "Laporan",
						);
		$this->load->view('index', $data);
	}

}

/* End of file Laporan_barang.php */
/* Location: ./application/controllers/Laporan_barang.php */