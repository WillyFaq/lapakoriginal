<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("User_model", "", TRUE);
		$this->load->model("Sales_model", "", TRUE);
		$this->load->model("Barang_model", "", TRUE);
		$this->load->model("Sales_order_model", "", TRUE);
	}

	public function gen_table()
	{
		$query=$this->Sales_order_model->get_where(["id_user" => $this->session->userdata("user")->id_user]);
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Id Transaksi', 'Nama Pelanggan', 'Nama Barang', 'Jumlah', 'Total', 'Status', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$sts = '<span class="badge badge-danger">Belum dikirim</span>';
				if($sts==1){
					$sts = '<span class="badge badge-success">Sudah dikirim</span>';
				}
				$this->table->add_row(	++$i,
							$row->id_transaksi,
							$row->nama_pelanggan,
							$row->nama_barang,
							number_format($row->jumlah_order),
							'Rp. '.number_format($row->total_order),
							$sts,
							//anchor('Sales_order/ubah/'.e_url($row->kode_barang),'<span class="fa fa-pencil-alt"></span>',array( 'title' => 'Ubah', 'class' => 'btn btn-primary btn-xs', 'data-toggle' => 'tooltip')).'&nbsp;'.
							anchor('sales_order/detail/'.e_url($row->id_transaksi),'<span class="fa fa-eye"></span>',array( 'title' => 'Detail', 'class' => 'btn btn-warning btn-xs', 'data-toggle' => 'tooltip'))
						);
			}
		}
		return  $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "Sales_order_view",
						"ket" => "Data",
						"add" => anchor('sales_order/tambah', '<i class="fa fa-plus"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Tambah Data")),
						"table" => $this->gen_table()
						);
		$this->load->view('index', $data);
	}


	public function cb_barang($sel='')
	{
		$ret = '<div class="form-group row"><label for="nama" class="col-sm-2 col-form-label">Barang</label><div class="col-sm-10">';
		$id = $this->session->userdata("user")->id_user;
		$q = $this->Sales_model->get_data($id);
		$res = $q->result();
		foreach ($res as $row) {
			//$ret .= $row->nama_barang." (".$row->minimal_sale.")"."<br>";
			$opt[$row->kode_barang] = $row->nama_barang;
		}
		$js = 'class="form-control" id="kode_barang"';
		$ret= $ret.''.form_dropdown('kode_barang',$opt,$sel,$js);
		$ret= $ret.'</div></div>';
		return $ret;
	}

	public function tambah()
	{
		$data = array(
						"page" => "Sales_order_view",
						"ket" => "Tambah Data",
						"form" => "sales_order/add",
						"cb_barang" => $this->cb_barang("")
						);
		$this->load->view('index', $data);
	}

	public function detail($id_trans)
	{
		$id_trans = d_url($id_trans);
		$data = array(
						"page" => "Sales_order_view",
						"ket" => "Detail Data",
						);
		$q = $this->Sales_order_model->get_data($id_trans);
		$res = $q->result();
		$detail = [];
		foreach ($res as $row) {
			$detail["Id Transaksi"] = $row->id_transaksi;
			$detail["Nama Pelanggan"] = $row->nama_pelanggan;
			$detail["No Telp"] = $row->notelp;
			$detail["Alamat"] = $row->alamat;
			$detail["Nama Barang"] = $row->nama_barang;
			$detail["Harga Barang"] = "Rp. ".number_format($row->harga_order);
			$detail["Jumlah"] = number_format($row->jumlah_order);
			$detail["Total"] = "Rp. ".number_format($row->total_order);
			$detail["Status"] = $row->status_order==1?'<span class="badge badge-success">Sudah dikirim</span>':'<span class="badge badge-danger">Belum dikirim</span>';
		}
		$data["detail"] = $detail;
		$this->load->view('index', $data);
	}

	public function add()
	{
		print_pre($this->input->post());

		$id_transaksi = $this->Sales_order_model->gen_idtrans();
		$no_pelanggan = str_replace("T", "P", $id_transaksi);
		$pelaggan = array(
							"no_pelanggan" => $no_pelanggan,
							"nama_pelanggan" => $this->input->post("nama_pelanggan"),
							"notelp" => $this->input->post("notelp"),
							"alamat" => $this->input->post("alamat"),
							);
		$order = array(
						"id_transaksi" => $id_transaksi,
						"id_user" => $this->session->userdata("user")->id_user,
						"no_pelanggan" => $no_pelanggan,
						"kode_barang" => $this->input->post("kode_barang"),
						"harga_order" => $this->input->post("harga_barang"),
						"jumlah_order" => $this->input->post("jumlah_beli"),
						"total_order" => $this->input->post("harga_barang") * $this->input->post("jumlah_beli"),
						"tgl_order" => date("Y-m-d H:i:s"),
						);
		if($this->Sales_order_model->add($pelaggan, $order)){
			$this->session->set_flashdata('msg_title', 'Sukses!');
			$this->session->set_flashdata('msg_status', 'alert-success');
			$this->session->set_flashdata('msg', 'Data berhasil disimpan! ');
			redirect('sales_order/detail/'.e_url($id_transaksi));
		}else{
			$this->session->set_flashdata('msg_title', 'Terjadi Kesalahan!');
			$this->session->set_flashdata('msg_status', 'alert-danger');
			$this->session->set_flashdata('msg', 'Data gagal disimpan! ');
			redirect('sales_order');
		}
	}

}

/* End of file Sales_order.php */
/* Location: ./application/controllers/Sales_order.php */