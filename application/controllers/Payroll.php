<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Payroll_model", "", TRUE);
		$this->load->model("User_model", "", TRUE);
	}

	public function gen_table()
	{
		$query=$this->Payroll_model->get_all_payroll();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Nama', 'Tanggal', 'Jumlah', 'Bonus', 'Total',  'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;
			foreach ($res as $row){
				$total = $row->jumlah_gaji + $row->bonus;
				$this->table->add_row(	++$i,
							$row->nama,
							date("d-m-Y", strtotime($row->tgl_gaji)),
							'Rp. '.number_format($row->jumlah_gaji),
							'Rp. '.number_format($row->bonus),
							'Rp. '.number_format($total),
							anchor('payroll/detail/'.e_url($row->id_payroll),'<span class="fa fa-eye"></span>',array( 'title' => 'Detail', 'class' => 'btn btn-warning btn-xs', 'data-toggle' => 'tooltip'))
						);
			}
		}
		return  $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "payroll_view",
						"ket" => "Data",
						"add" => anchor('payroll/tambah', '<i class="fa fa-plus"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Tambah Data")),
						"table" => $this->gen_table()
						);
		$this->load->view('index', $data);
	}

	public function tambah()
	{
		$data = array(
						"page" => "payroll_view",
						"ket" => "Tambah",
						"form" => "payroll/add"
						);
		$this->load->view('index', $data);
	}

	public function detail($id='', $cetak="")
	{
		$data = array(
						"page" => "payroll_view",
						"ket" => "Detail",
						"add" => anchor('#', '<i class="fa fa-file-pdf"></i>', array("class" => "btn btn-danger", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Cetak"
										,'data-id' => $id, 'onclick' => "return cetak('$id')")),
						);
		$id = d_url($id);
		$q = $this->Payroll_model->get_payroll_id($id);
		$res = $q->result();
		$lvl = '';
		foreach ($res as $row) {
			$total = $row->jumlah_gaji + $row->bonus;
			$lvl = $row->level;
			$data['detail']['Nama'] = $row->nama;
			$data['detail']['Jabatan'] = $row->nama_jabatan;
			$data['detail']['Tanggal'] = date("d-m-Y", strtotime($row->tgl_gaji));
			$data['detail']['Jumlah'] = 'Rp. '.number_format($row->jumlah_gaji);
			$data['detail']['Bonus'] = 'Rp. '.number_format($row->bonus);
			$data['detail']['Total'] = 'Rp. '.number_format($total);
		}
		$data['json_detail'] = $this->load_detail($id, '', $lvl);
		if($cetak!=""){
			$data['cetak'] = "cetak('".e_url($id)."')";
		}
		$this->load->view('index', $data);
	}

	public function cetak($id='')
	{
		$data = array(
						"page" => "payroll_view",
						"ket" => "Detail",
						//"add" => anchor('payroll/cetak/'.$id, '<i class="fa fa-file-pdf"></i>', array("class" => "btn btn-danger", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Cetak")),
						);
		$id = d_url($id);
		$q = $this->Payroll_model->get_payroll_id($id);
		$res = $q->result();
		$lvl = '';
		foreach ($res as $row) {
			$total = $row->jumlah_gaji + $row->bonus;
			$lvl = $row->level;
			$data['detail']['Nama'] = $row->nama;
			$data['detail']['Jabatan'] = $row->nama_jabatan;
			$data['detail']['Tanggal'] = date("d-m-Y", strtotime($row->tgl_gaji));
			$data['detail']['Jumlah'] = 'Rp. '.number_format($row->jumlah_gaji);
			$data['detail']['Bonus'] = 'Rp. '.number_format($row->bonus);
			$data['detail']['Total'] = 'Rp. '.number_format($total);
			$data['terbilang'] = terbilang($total);
		}
		$data['json_detail'] = $this->load_detail($id, '', $lvl);
		$this->load->view('cetak_payroll_view', $data);
	}



	public function add()
	{
		$data = $this->input->post();
		$data['id_payroll'] = $this->Payroll_model->gen_id();
		$id_trans = [];
		if($data['level']==2){
			$q = $this->Payroll_model->get_det_sales($data['id_user'], $data['tgl_gaji']);
			$id_trans = $this->gen_det_add($q, $data);
		}else if($data['level']==1){
			$q = $this->Payroll_model->get_det_admin($data['id_user'], $data['tgl_gaji']);
			$id_trans = $this->gen_det_add($q, $data);
		}else if($data['level']==4){
			$q = $this->Payroll_model->get_det_iklan($data['id_user'], $data['tgl_gaji']);
			$id_trans = $this->gen_det_add($q, $data);
		}
		
		unset($data['nama'], $data['level'], $data['btnSimpan']);
		if($this->Payroll_model->add($data, $id_trans)){
			alert_notif("success");
			redirect('payroll/detail/'.e_url($data['id_payroll']).'/cetak');
		}else{
			alert_notif("danger");
			redirect('payroll');
		}
	}

	public function gen_det_add($q, $data)
	{
		$id_trans = [];
		$res = $q->result();
		foreach ($res as $row) {
			$id_trans[] = array(
								'id_payroll' => $data['id_payroll'],
								'id_transaksi' => $row->id_transaksi
								);
		}
		return $id_trans;
	}

	public function gen_table_pegawai()
	{
		$query=$this->User_model->get_where("jabatan.level IN (1,2,4)");
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTableModal">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Nama', 'Jabatan', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$this->table->add_row(	++$i,
							$row->nama,
							$row->nama_jabatan,
							'<button type="button" onclick="pilih_pegawai(\''.$row->id_user.'\', \''.$row->nama.'\', \''.$row->level.'\')" class="btn btn-xs btn-success" data-toggle="tooltip" title="Pilih"><i class="fa fa-check"></i></button>'
						);
			}
		}
		echo  $this->table->generate();
		init_datatable_tooltips();
	}

	public function load_detail($id='', $tgl='', $level="")
	{
		$set = $this->Payroll_model->get_setting();
		$ret = [];
		if($level==2){
			$ret = $this->gen_table_detail_sales($id, $tgl);
			$ret['total'] = $set['gaji_sales_penjualan'] * $ret['total'];
		}else if($level==1){
			$ret = $this->gen_table_detail_admin($id, $tgl);
			$tolak = $set['gaji_admin_ditolak'] * $ret['total_tolak'];
			$terima = $set['gaji_admin_diterima'] * $ret['total_terima'];
			$ret['total'] = $terima + $tolak;
		}else if($level==4){
			$ret = $this->gen_table_detail_admin_iklan($id, $tgl);
			$terima = $set['gaji_admin_iklan'] * $ret['total_terima'];
			$ret['total'] = $terima;
		}
		if($tgl!=''){
			echo json_encode($ret);
		}else{
			return json_encode($ret);
		}

	}

	public function gen_table_detail_sales($id='', $tgl='')
	{
		if($tgl!=''){
			$query = $this->Payroll_model->get_det_sales($id, $tgl);
		}else{
			$query = $this->Payroll_model->get_det_sales_payroll($id);
		}
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTableModal" id="dttba">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Nama Barang', 'Tgl Penjualan', 'Jumlah');

			$tot = 0;
		if ($num_rows > 0)
		{
			$i = 0;
			foreach ($res as $row){
				$tot += $row->jumlah_order;
				$this->table->add_row(	++$i,
							$row->nama_barang,
							$row->tgl_order,
							$row->jumlah_order
						);
			}
		}

		$this->table->set_footer(
								array('data' => 'Total', "colspan" => 3),
								number_format($tot)
								);

		$table = $this->table->generate();
		$table = $tgl!=""?$table.init_datatable_tooltips_ajax():$table;
		$ret = array(
					"table" => $table,
					"total" => $tot
					);
		return $ret;
	}

	public function gen_table_detail_admin($id='', $tgl='')
	{
		if($tgl!=""){
			$query = $this->Payroll_model->get_det_admin($id, $tgl);
		}else{
			$query = $this->Payroll_model->get_det_admin_payroll($id);
		}
		$res = $query->result();
		$ssql = $this->db->last_query();
		//echo $ssql;
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTableModal" id="dttba">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Id Transaksi', 'Tgl Kirim', 'Status');

			$tot_terima = 0;
			$tot_tolak = 0;
		if ($num_rows > 0)
		{
			$i = 0;
			foreach ($res as $row){
				$sts = '';
				if($row->status_pengiriman==2){
					$sts = '<span class="badge badge-success">Sudah diterima</span>';
					$tot_terima++;
				}else if($row->status_pengiriman>=3){
					$sts = '<span class="badge badge-danger">Ditolak</span>';
					$tot_tolak++;
				}
				$this->table->add_row(	++$i,
							$row->id_transaksi,
							$row->tgl_kirim,
							$sts
						);
			}
		}

		$this->table->set_footer2(
								[array('data' => 'Total Diterima', "colspan" => 3), number_format($tot_terima)],
								[array('data' => 'Total Ditolak', "colspan" => 3), number_format($tot_tolak)]
								);

		$table = $this->table->generate();
		$table = $tgl!=""?$table.init_datatable_tooltips_ajax():$table;
		$ret = array(
					"table" => $table,
					"total_terima" => $tot_terima,
					"total_tolak" => $tot_tolak
					);
		return $ret;
	}

	public function gen_table_detail_admin_iklan($id='', $tgl='')
	{
		if($tgl!=""){
			$query = $this->Payroll_model->get_det_admin_iklan($id, $tgl);
		}else{
			$query = $this->Payroll_model->get_det_admin_iklan_payroll($id);
		}
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTableModal" id="dttba">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Id Transaksi', 'Tgl Kirim', 'Status');

			$tot_terima = 0;
			$tot_tolak = 0;
		if ($num_rows > 0)
		{
			$i = 0;
			foreach ($res as $row){
				$sts = '';
				if($row->status_pengiriman==2){
					$sts = '<span class="badge badge-success">Sudah diterima</span>';
					$tot_terima++;
				}else if($row->status_pengiriman==3){
					$sts = '<span class="badge badge-danger">Ditolak</span>';
					$tot_tolak++;
				}
				$this->table->add_row(	++$i,
							$row->id_transaksi,
							$row->tgl_kirim,
							$sts
						);
			}
		}

		$this->table->set_footer(
								array('data' => 'Total Diterima', "colspan" => 3), 
								number_format($tot_terima)
								);

		$table = $this->table->generate();
		$table = $tgl!=""?$table.init_datatable_tooltips_ajax():$table;
		$ret = array(
					"table" => $table,
					"total_terima" => $tot_terima
					);
		return $ret;
	}
}

/* End of file Payroll.php */
/* Location: ./application/controllers/Payroll.php */