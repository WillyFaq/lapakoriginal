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
		$this->load->model("Pengiriman_model", "", TRUE);
		$this->load->model("Feedback_model", "", TRUE);
	}

	public function gen_table()
	{
		$query=$this->Sales_order_model->get_where2(["user.id_user" => $this->session->userdata("user")->id_user]);
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Id Transaksi', 'Nama Pelanggan', 'Tgl Order', 'Total', 'Status', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$sts = '<span class="badge badge-danger">Belum dikirim</span>';
				if($row->status_order==1){
					$sts = '<span class="badge badge-warning">Sudah diproses</span>';
				}else if($row->status_order==2){
					$sts = '<span class="badge badge-danger">Dibatalkan</span>';
				}else if($row->status_order==3){
					$sts = '<span class="badge badge-secondary">Dibatalkan Sales</span>';
				}else if($row->status_order==4){
					$sts = '<span class="badge badge-secondary">Dibatalkan Admin</span>';
				}
				$png = $this->Sales_order_model->cek_laporan($row->id_transaksi);
				if($png==1){
					$sts = '<span class="badge badge-info">Sudah dikirim</span>';
				}else if($png==2){
					$sts = '<span class="badge badge-success">Sudah diterima</span>';
				}else if($png>=3){
					$sts = '<span class="badge badge-danger">ditolak</span>';
				}
				$this->table->add_row(	++$i,
							$row->id_transaksi,
							$row->nama_pelanggan,
							date("d-m-Y", strtotime($row->tgl_order)),
							'Rp. '.number_format($row->total_order),
							$sts,
							anchor('sales_order/detail/'.e_url($row->id_transaksi),'<span class="fa fa-eye"></span>',array( 'title' => 'Detail', 'class' => 'btn btn-warning btn-xs', 'data-toggle' => 'tooltip'))
						);
				if($i>=1000){break;}
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
			$opt[$row->kode_barang] = $row->nama_barang;
		}
		$js = 'class="form-control" id="kode_barang"';
		$ret= $ret.''.form_dropdown('kode_barang',$opt,$sel,$js);
		$ret= $ret.'</div></div>';
		return $ret;
	}

	public function cb_provinsi($sel='')
	{
		$ret = '<div class="form-group row"><label for="provinsi" class="col-sm-2 col-form-label">Provinsi</label><div class="col-sm-10">';
		$res = get_provinsi();
		foreach ($res as $k => $row) {
			$opt[$row['id']] = $row['nama'];
		}
		$js = 'class="form-control cb_provinsi" id="provinsi"';
		$ret= $ret.''.form_dropdown('provinsi',$opt,$sel,$js);
		$ret= $ret.'</div></div>';
		return $ret;
	}

	public function cb_kota($id='', $sel='')
	{
		$ret = '<div class="form-group row"><label for="kota" class="col-sm-2 col-form-label">Kabupaten/Kota</label><div class="col-sm-10">';
		$res = get_kota($id);
		foreach ($res as $k => $row) {
			$opt[$row['id']] = $row['nama'];
		}
		$js = 'class="form-control cb_kota" id="kota"';
		$ret= $ret.''.form_dropdown('kota',$opt,$sel,$js);
		$ret= $ret.'</div></div>';
		echo $ret;
	}

	public function cb_kecamatan($id='', $sel='')
	{
		$ret = '<div class="form-group row"><label for="kecamatan" class="col-sm-2 col-form-label">Kecamatan/Kota</label><div class="col-sm-10">';
		$res = get_kecamatan($id);
		foreach ($res as $k => $row) {
			$opt[$row['id']] = $row['nama'];
		}
		$js = 'class="form-control cb_kecamatan" id="kecamatan"';
		$ret= $ret.''.form_dropdown('kecamatan',$opt,$sel,$js);
		$ret= $ret.'</div></div>';
		echo $ret;
	}

	public function tambah()
	{
		$data = array(
						"page" => "Sales_order_view",
						"ket" => "Tambah Data",
						"form" => "sales_order/add",
						"cb_barang" => $this->cb_barang(""),
						"cb_provinsi" => $this->cb_provinsi(""),
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
		$fb = '-1';
		$q = $this->Sales_order_model->get_data($id_trans);
		$res = $q->result();
		$detail = [];
		$not = "";
		foreach ($res as $row) {
			$detail["Id Transaksi"] = $row->id_transaksi;
			$detail["Nama Pelanggan"] = $row->nama_pelanggan;
			$detail["No Telp"] = $row->notelp;
			$not = $row->notelp;
			//echo strpos($row->alamat,"|");
			$alamat = explode("|", $row->alamat);
			if(sizeof($alamat)==1){
				$detail["Alamat"] = $row->alamat;
			}else{
				$prov = get_provinsi($alamat[0])['nama'];
				$kot = get_kota($alamat[1])['nama'];
				$kec = get_kecamatan($alamat[2])['nama'];
				$detail["Alamat"] = $alamat[3].", $kec, $kot, $prov";
			}
			
			$detail["jasa Pengiriman"] = $row->jasa_pengiriman;
			//$detail["Keterangan"] = $row->keterangan;
			if($row->status_order==1){
				$detail['Status'] = '<span class="badge badge-warning">Sudah diporses</span>';	
			}else if($row->status_order==2){
				$detail['Status'] = '<span class="badge badge-danger">Dibatalkan</span>';	
			}else if($row->status_order==3){
				$detail['Status'] = '<span class="badge badge-secondary">Dibatalkan Sales</span>';
			}else if($row->status_order==4){
				$detail['Status'] = '<span class="badge badge-secondary">Dibatalkan Admin</span>';
			}else{
				$detail['Status'] = '<span class="badge badge-danger">Belum diproses</span>';
				$idddd = $this->session->userdata('user')->id_user;
				$query2 = $this->Pengiriman_model->get_pending_sales($idddd);
				$res2 = $query2->result();
				$num_rows2 = $query2->num_rows();
				if($num_rows2>0){
					foreach ($res2 as $rrrow) {
						if($rrrow->id_transaksi == $row->id_transaksi){
							$detail['Status'] .= '<br><span class="badge badge-warning">Pending!</span>';
						}
					}
				}
				$data["add"] = anchor('sales_order/ubah/'.e_url($row->id_transaksi), '<i class="fa fa-pencil-alt"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Ubah Data"));
				$data["add"] .= "&nbsp;".anchor('sales_order/batal/'.e_url($row->id_transaksi), '<i class="fa fa-ban"></i>', array("class" => "btn btn-danger", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Batalkan Order"));		
			}
			$fb = $row->status_order==1?0:'-1';

			$detail["total_order"] = "Rp. ".number_format($row->total_order);
			$nama_barang = $row->nama_barang;
			$kd = explode(".", $row->kode_brg);
			if(sizeof($kd)>1){
				$nama_barang .= isset($kd[1])?"<br>($kd[1]":"";
				$nama_barang .= isset($kd[2])?" - $kd[2]":"";
				$nama_barang .= ")";
			}
			$detail["order"][] = array(
										"barang" => $nama_barang,
										"harga_barang" => "Rp. ".number_format($row->harga_order),
										"potongan" => "Rp. ".number_format($row->potongan_order),
										"jumlah_order" => number_format($row->jumlah_order),
										"subtotal" => "Rp. ".number_format($row->subtotal_order),
										);

		}
		$data["detail"] = $detail;
		$q = $this->Pengiriman_model->get_where(['pengiriman.id_transaksi' => "$id_trans"]);
		//echo "<h1>".$this->db->last_query()."</h1>";;
		if($q->num_rows()>0){
			$res = $q->result();
			$det = [];
			if($q->num_rows()>1){
				foreach ($res as $row) {
					if($row->status_pengiriman!=0 && $row->status_pengiriman!=4){
						unset($data['detail']['Status']);
						$det['Jasa Pengiriman'] = $row->jasa_pengiriman;
						$det['No Resi'] = $row->no_resi;
						$det['Tgl Kirim'] = date("d-m-Y", strtotime($row->tgl_kirim));
						$det['Pengirim'] = $row->nama;
						$sts = '<span class="badge badge-warning">Sudah di acc</span>';
				
						if($row->status_pengiriman==1){
							$sts = '<span class="badge badge-info">Sudah dikirim</span>';
							$fb = 1;
						}else if($row->status_pengiriman==2){
							$sts = '<span class="badge badge-info">Sudah diterima</span>';
							$fb = 2;
						}else if($row->status_pengiriman>=3){
							$sts = '<span class="badge badge-danger">Ditolak</span>';
							$fb = 3;
						}
						$det["Status Pengiriman"] = $sts;
					}
				}
			}else{
				foreach ($res as $row) {
					if($row->status_pengiriman!=0){
						unset($data['detail']['Status']);
						$det['Jasa Pengiriman'] = $row->jasa_pengiriman;
						$det['No Resi'] = $row->no_resi;
						$det['Tgl Kirim'] = date("d-m-Y", strtotime($row->tgl_kirim));
						$det['Pengirim'] = $row->nama;
						$sts = '<span class="badge badge-warning">Sudah di acc</span>';
				
						if($row->status_pengiriman==1){
							$sts = '<span class="badge badge-info">Sudah dikirim</span>';
							$fb = 1;
						}else if($row->status_pengiriman==2){
							$sts = '<span class="badge badge-info">Sudah diterima</span>';
							$fb = 2;
						}else if($row->status_pengiriman>=3){
							$sts = '<span class="badge badge-danger">Ditolak</span>';
							$fb = 3;
						}
						$det["Status Pengiriman"] = $sts;
					}
				}
			}
			
			$data['pengiriman'] = $det;
		}
		//echo "<h1>$fb</h1>";
		if($fb!='-1'){
			
			$q = $this->Feedback_model->get_where(array("type" => $fb));
			$res = $q->result();
			$pesan = $res[0]->pesan;
			if (strpos($pesan, '<transaksi>') !== false) {
				$datt = "\r\n";
				if($fb>0){
					$datt .= "\r\nNo Resi : *".$det['No Resi']."*";
					$datt .= "\r\nJasa Pengiriman : *".$det['Jasa Pengiriman']."*";
					$datt .= "\r\nTgl dikirim : *".$det['Tgl Kirim']."*";
				}

				$datt .= "\r\nNama : *".$detail["Nama Pelanggan"]."*";
				$datt .= "\r\nAlamat : *".$detail["Alamat"]."*";
				$datt .= "\r\n--------------------";
				$datt .= "\r\nDetail Transaksi";
				$datt .= "\r\n--------------------";
				foreach ($detail["order"] as $k => $v) {
					$datt .= "\r\nNama Barang : *".str_replace("<br>"," ", $v["barang"])."*";
					$datt .= "\r\nJumlah : *".$v["jumlah_order"]."*";
					$datt .= "\r\n--------------------";
				}
				$datt .= "\r\nTotal : *".$detail["total_order"]."*";
				$datt .= "\r\n";
			    $pesan = str_replace("<transaksi>", $datt, $pesan);
			}
			$msg = urlencode($pesan);
			if(substr($not, 0, 1)=="0"){
				$not = "62".substr($not, 1, strlen($not));
			}
			$url = 'whatsapp://send?phone='.$not.'&text='.$msg;
			if (strpos($_SERVER['HTTP_USER_AGENT'], 'wv') !== false){
		        //echo "<br> asli android";
				$data['feedback'] =  anchor($url,'<span class="fab fa-whatsapp"></span> &nbsp; Feedback',array( 'title' => 'Feedback', 'class' => 'btn btn-success', 'data-toggle' => 'tooltip', "data-action" => "share/whatsapp/share"));
		    }else{
		        //echo "<br> asli browser";
				$data['feedback'] =  anchor($url,'<span class="fab fa-whatsapp"></span> &nbsp; Feedback',array( 'title' => 'Feedback', 'class' => 'btn btn-success', 'data-toggle' => 'tooltip', 'target' => 'blank', "data-action" => "share/whatsapp/share"));
		    }
			
		}
		$this->load->view('index', $data);
	}

	public function add()
	{
		$provinsi = $this->input->post("provinsi");
		$kota = $this->input->post("kota");
		$kecamatan = $this->input->post("kecamatan");
		$alamat = "$provinsi|$kota|$kecamatan|".$this->input->post("alamat");

		$id_transaksi = $this->Sales_order_model->gen_idtrans();
		$no_pelanggan = str_replace("T", "P", $id_transaksi);
		$pelaggan = array(
							"no_pelanggan" => $no_pelanggan,
							"nama_pelanggan" => $this->input->post("nama_pelanggan"),
							"notelp" => $this->input->post("notelp"),
							"alamat" => $alamat,
							);
		$order = array(
						"id_transaksi" => $id_transaksi,
						"id_user" => $this->session->userdata("user")->id_user,
						"no_pelanggan" => $no_pelanggan,
						"tgl_order" => date("Y-m-d H:i:s"),
						"jasa_pengiriman" => $this->input->post("jasa_pengiriman"),
						"keterangan" => $this->input->post("keterangan"),
						);
		$tot = 0;
		$kdbbrg = $this->input->post('kode_barang');
		$hrrgg = $this->input->post('harga_barang');
		if(is_array($hrrgg)){
			foreach($this->input->post('kode_barang') as $k => $v){
				$sbtr = $this->input->post('subtotal_order')[$k];
				$tot += $sbtr;
				$warna = $this->input->post('warna_barang')[$k]!=""?".".$this->input->post('warna_barang')[$k]:'';
				$ukuran = $this->input->post('ukuran_barang')[$k]!=""?".".$this->input->post('ukuran_barang')[$k]:'';
				$kode_brg = $v.$warna.$ukuran;
				$order['detail'][] = array(
											'id_transaksi' => $id_transaksi,
											'kode_barang' => $v,
											'kode_brg' => $kode_brg,
											'harga_order' => $this->input->post('harga_barang')[$k],
											'potongan_order' => $this->input->post('potongan')[$k],
											'jumlah_order' => $this->input->post('jumlah_beli')[$k],
											'subtotal_order' => $sbtr,
											);
			}
		}
		$order['total_order'] = $tot;
		if(!empty($order['detail'])){

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
		else{
			$this->session->set_flashdata('msg_title', 'Terjadi Kesalahan!');
			$this->session->set_flashdata('msg_status', 'alert-danger');
			$this->session->set_flashdata('msg', 'Data gagal disimpan! ');
			redirect('sales_order');
		}
	}

	public function ubah($id_trans='')
	{
		$id_trans = d_url($id_trans); 
		$data = array(
						"page" => "Sales_order_view",
						"ket" => "Ubah Data",
						"form" => "sales_order/update",
						"cb_barang" => $this->cb_barang(""),
						);

		$q = $this->Sales_order_model->get_data($id_trans);
		$res = $q->result();
		foreach ($res as $row) {
			$data['id_transaksi'] = $row->id_transaksi;
			$data['no_pelanggan'] = $row->no_pelanggan;
			$data['nama_pelanggan'] = $row->nama_pelanggan;
			$data['notelp'] = $row->notelp;
			$data['jasa_pengiriman'] = $row->jasa_pengiriman;
			$data["cb_provinsi"] = $this->cb_provinsi("");
			$alamat = explode("|", $row->alamat);
			if(sizeof($alamat)==1){
				$data["Alamat"] = $row->alamat;
			}else{
				/*$prov = get_provinsi($alamat[0])['nama'];
				$kot = get_kota($alamat[1])['nama'];
				$kec = get_kecamatan($alamat[2])['nama'];*/

				$data["id_provinsi"] = $alamat[0];
				$data["cb_provinsi"] = $this->cb_provinsi($alamat[0]);
				$data["cb_kota"] = $alamat[1];
				$data["cb_kecamatan"] = $alamat[2];
				$data["alamat"] = $alamat[3];
			}

			$nama_barang = $row->nama_barang;
			$kd = explode(".", $row->kode_brg);
			if(sizeof($kd)>1){
				$nama_barang .= isset($kd[1])?"<br>($kd[1]":"";
				$nama_barang .= isset($kd[2])?" - $kd[2]":"";
				$nama_barang .= ")";
				$warna = isset($kd[1])?$kd[1]:"";
				$ukuran = isset($kd[2])?$kd[2]:"";
			}
			$data["barang"][] = array(
										"kode_barang" => $row->kode_barang,
										"kode_brg" => $row->kode_brg,
										"barang" => $row->nama_barang,
										"warna" => $warna,
										"ukuran" => $ukuran,
										"harga_barang" => $row->harga_order,
										"potongan" => $row->potongan_order,
										"jumlah_order" => $row->jumlah_order,
										"subtotal" => $row->subtotal_order,
										);
		}

		$this->load->view('index', $data);

	}

	public function update()
	{
		$provinsi = $this->input->post("provinsi");
		$kota = $this->input->post("kota");
		$kecamatan = $this->input->post("kecamatan");
		$alamat = "$provinsi|$kota|$kecamatan|".$this->input->post("alamat");

		$id_transaksi = $this->input->post("id_transaksi");
		$no_pelanggan = $this->input->post("no_pelanggan");

		$pelaggan = array(
							"no_pelanggan" => $no_pelanggan,
							"nama_pelanggan" => $this->input->post("nama_pelanggan"),
							"notelp" => $this->input->post("notelp"),
							"alamat" => $alamat,
							);
		$order = array(
						"id_transaksi" => $id_transaksi,
						"id_user" => $this->session->userdata("user")->id_user,
						"no_pelanggan" => $no_pelanggan,
						"tgl_order" => date("Y-m-d H:i:s"),
						"jasa_pengiriman" => $this->input->post("jasa_pengiriman"),
						"keterangan" => $this->input->post("keterangan"),
						);
		$tot = 0;
		foreach($this->input->post('kode_barang') as $k => $v){
			$sbtr = $this->input->post('subtotal_order')[$k];
			$tot += $sbtr;
			$warna = $this->input->post('warna_barang')[$k]!=""?".".$this->input->post('warna_barang')[$k]:'';
			$ukuran = $this->input->post('ukuran_barang')[$k]!=""?".".$this->input->post('ukuran_barang')[$k]:'';
			$kode_brg = $v.$warna.$ukuran;
			$order['detail'][] = array(
										'id_transaksi' => $id_transaksi,
										'kode_barang' => $v,
										'kode_brg' => $kode_brg,
										'harga_order' => $this->input->post('harga_barang')[$k],
										'potongan_order' => $this->input->post('potongan')[$k],
										'jumlah_order' => $this->input->post('jumlah_beli')[$k],
										'subtotal_order' => $sbtr,
										);
		}
		$order['total_order'] = $tot;
		print_pre($pelaggan);
		print_pre($order);
		if($this->Sales_order_model->update2($pelaggan, $order)){
			$this->session->set_flashdata('msg_title', 'Sukses!');
			$this->session->set_flashdata('msg_status', 'alert-success');
			$this->session->set_flashdata('msg', 'Data berhasil disimpan! ');
			redirect('sales_order/detail/'.e_url($id_transaksi));
		}else{
			$this->session->set_flashdata('msg_title', 'Terjadi Kesalahan!');
			$this->session->set_flashdata('msg_status', 'alert-danger');
			$this->session->set_flashdata('msg', 'Data gagal disimpan! ');
			redirect('sales_order/ubah/'.e_url($id_transaksi));
		}
	}

	

	public function get_barang()
	{
		$kode = $this->input->post('kode');
		$q = $this->Barang_model->get_data($kode)->row();
		$data = array(
						'warna_barang' => $q->warna_barang,
						'ukuran_barang' => $q->ukuran_barang,
						'harga_jual' => $q->harga_jual,
						'setting_harga' => $q->setting_harga,
						);
		echo json_encode($data);
	}

	public function batal($id_transaksi = "")
	{
		$id_transaksi = d_url($id_transaksi);
		$this->db->set("status_order", 3);
		$this->db->where("id_transaksi", $id_transaksi);
		$re = $this->db->update("sales_order");
		if($re){
			$this->session->set_flashdata('msg_title', 'Sukses!');
			$this->session->set_flashdata('msg_status', 'alert-success');
			$this->session->set_flashdata('msg', 'Data berhasil disimpan! ');
			redirect('sales_order/detail/'.e_url($id_transaksi));
		}else{
			$this->session->set_flashdata('msg_title', 'Terjadi Kesalahan!');
			$this->session->set_flashdata('msg_status', 'alert-danger');
			$this->session->set_flashdata('msg', 'Data gagal disimpan! ');
			redirect('sales_order/detail/'.e_url($id_transaksi));
		}
	}


}

/* End of file Sales_order.php */
/* Location: ./application/controllers/Sales_order.php */