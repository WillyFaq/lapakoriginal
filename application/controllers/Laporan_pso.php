<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_pso extends CI_Controller {


	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("Barang_model", "", TRUE);
	}

	public function gen_table_bulanan($sal="", $thn="", $bln="")
	{

		$whr = "";
		if($bln!=""){
			$whr = " AND MONTH(a.tgl_order) = '$bln'";
		}
		$sql = "SELECT 
					b.nama,
					a.id_transaksi,
					f.kode_barang,
					c.nama_barang,
					f.harga_order,
					f.jumlah_order,
					a.total_order,
					a.tgl_order,
					a.no_pelanggan,
					d.nama_pelanggan,
					d.notelp,
					d.alamat,
					a.status_order,
					e.status_pengiriman
				FROM sales_order a
				JOIN sales_order_detail f ON a.id_transaksi = f.id_transaksi
				JOIN user b ON a.id_user = b.id_user
				JOIN barang c ON f.kode_barang = c.kode_barang
				JOIN pelanggan d ON a.no_pelanggan = d.no_pelanggan
				LEFT JOIN pengiriman e ON a.id_transaksi = e.id_transaksi
				WHERE YEAR(a.tgl_order) = '$thn' 
					AND a.id_user = '$sal' $whr
		";
		$q = $this->db->query($sql);
		$res = $q->result();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 
									'Nama Pelanggan', 
									'No Telp', 
									'Alamat', 
									'Nama Barang', 
									'Harga Jual', 
									'Jumlah Jual', 
									'Total', 
									'Tgl', 
									'Status'
								);

		$num_rows = $q->num_rows();
		$this->load->library('alamat');
		$alm = new Alamat();
		if ($num_rows > 0){
			$i=0;
			foreach ($res as $row){
				//print_pre($alm->get_provinsi());
				$sts = "";
				$res_alamat = "";

				
				switch ($row->status_pengiriman) {
					case '0':
						$sts = '<span class="badge badge-default">Sudah di acc</span>';
						break;
					case '1':
						$sts = '<span class="badge badge-info">Sudah di dikirim</span>';
						break;
					case '2':
						$sts = '<span class="badge badge-success">Sudah di diterima</span>';
						break;
					case '3':
						$sts = '<span class="badge badge-danger">Ditolak</span>';
						break;
					case '4':
						$sts = '<span class="badge badge-danger">Ditolak</span>';
						break;
					default:
						switch ($row->status_order) {
							case '0':
								$sts = '<span class="badge badge-warning">Belum di Proses</span>';
								break;
							case '1':
								$sts = '<span class="badge badge-info">Sedang di Proses</span>';
								break;
							case '2':
								$sts = '<span class="badge badge-danger">Dibatalkan</span>';
								break;
							default:
								$sts = '';
								break;
						}
						break;
				}
				
				$alamat = explode("|", $row->alamat);
				if(sizeof($alamat)==1){
					$res_alamat = $row->alamat;
				}else{
					$p = $alm->get_provinsi($alamat[0]);
					$prov = $p==""?"":$p['nama'];
					$kot = '';
					$kec = '';
					if(isset($alamat[1]) && strlen($alamat[1])==4){
						$ko = $alm->get_kota($alamat[1]);
						$kot = $ko==""?"":$ko['nama'];
					}
					if(isset($alamat[2]) && strlen($alamat[2])==7){
						$kc = $alm->get_kecamatan($alamat[2]);
						$kec = $kc==""?"":$kc['nama'];
					}

					
					$alms = isset($alamat[3])?$alamat[3]:$alamat[1];
					$res_alamat = $alms.", $kec, $kot, $prov";
				}
				$this->table->add_row(
										++$i,
										$row->nama_pelanggan,
										$row->notelp,
										$res_alamat,
										$row->nama_barang,
										"Rp. ".number_format($row->harga_order),
										$row->jumlah_order,
										"Rp. ".number_format($row->total_order),
										date("d-m-Y", strtotime($row->tgl_order)),
										$sts
									);
			}
		}
		echo  $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "laporan/lap_pso_view",
						"ket" => "Laporan",
						);
		$this->load->view('index', $data);
	}

	public function detail($bln, $id)
	{
		$data = array(
						"page" => "laporan/lap_pso_view",
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

	public function tes()
	{
		$this->load->library('alamat');
		$alm = new Alamat();
		print_pre($alm->get_provinsi(64));
		/*for($i=0; $i<10; $i++){
			print_pre($alm->get_provinsi());
		}*/
	}
}

/* End of file Laporan_pso.php */
/* Location: ./application/controllers/Laporan_pso.php */