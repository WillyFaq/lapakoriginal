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

		$this->table->set_heading('No', 'Kode Barang', 'Nama Barang', 'Tanggal', 'Jumlah Penjualan', 'Total Penjualan', 'Laba Penjuaalan');

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
							$row->jumlah_order,
							'Rp. '.number_format($row->total_order),
							'Rp. '.number_format($row->laba_penjualan)
						);
			}
		}

		$this->table->set_footer(
								array('data' => 'Total', "colspan" => 5),
								'Rp. '.number_format($tot),
								'Rp. '.number_format($laba)
								);

		echo $this->table->generate();
	}

	public function gen_table_bulanan($thn="", $bln="")
	{
		$query=$this->Barang_model->laporan_bulanan($bln, $thn, "");
		//echo $query;
		//echo $this->db->last_query();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Kode Barang', 'Nama Barang', 'Bulan', 'Jumlah Penjualan', 'Total Penjualan', 'Laba Penjuaalan', 'Aksi');

		$tot = 0;
		$laba = 0;
		if ($num_rows > 0)
		{
			$i = 0;
			foreach ($res as $row){
				$tot += $row->total_order;
				$laba_penjualan = (int)$row->laba_penjualan - (int)$this->Barang_model->get_iklan($row->kode_barang, $row->bulan);
				$laba += (int)$laba_penjualan;
				$this->table->add_row(	++$i,
							$row->kode_barang,
							$row->nama_barang,
							get_bulan($row->bulan),
							$row->jumlah_order,
							'Rp. '.number_format($row->total_order),
							'Rp. '.number_format($laba_penjualan),
							anchor('laporan_penjualan/detail/'.e_url($row->bulan).'/'.e_url($row->kode_barang), '<i class="fa fa-eye"></i>', array("class" => "btn btn-success btn-xs", "title" => "detail", 'data-toggle' => 'tooltip'))
						);
			}
		}

		$this->table->set_footer(
								array('data' => 'Total', "colspan" => 5),
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

	public function detail($bln, $id)
	{
		$data = array(
						"page" => "laporan/lap_penjualan_view",
						"ket" => "Detail Laporan",
						"detail" => 'det'
						);
		$bln = d_url($bln);
		$id = d_url($id);
		$q = $this->Barang_model->laporan_bulanan_id($bln, $id);
		//echo $this->db->last_query();
		$res = $q->result();
		foreach ($res as $row) {
			$data['kode_barang'] = $row->kode_barang;
			$data['nama_barang'] = $row->nama_barang;
			$data['harga_jual'] = $row->harga_jual;
			$data['laba_barang'] = $row->laba_barang;
			$data['bulan'] = $row->bulan;
			$data['jumlah_order'] = $row->jumlah_order;
			$data['total_order'] = $row->total_order;
			$data['beban_iklan'] = $this->Barang_model->get_iklan($row->kode_barang, $row->bulan);
		}

		$q = $this->Barang_model->get_update($id);
		$res = $q->result();
		foreach ($res as $row) {
			$data['beban'][$row->id_beban]['id'] = $row->id_beban;
			$data['beban'][$row->id_beban]['nama_beban'] = $row->nama_beban;
			$data['beban'][$row->id_beban]['nominal'] = $row->nominal;
		}

		//print_pre($data);
		$this->load->view('index', $data);
	}

}

/* End of file Laporan_barang.php */
/* Location: ./application/controllers/Laporan_barang.php */