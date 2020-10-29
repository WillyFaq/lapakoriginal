<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bonus extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("Team_model", "", TRUE);
		$this->load->model("Bonus_model", "", TRUE);
		$this->load->model("Barang_model", "", TRUE);
		$this->load->model("User_model", "", TRUE);
	}

	public function gen_table()
	{
		$query=$this->Bonus_model->get_all();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    	=> '<table class="table table-striped table-hover dataTable">',
						'row_alt_start'  	=> '<tr>',
						'row_alt_end'    	=> '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Nama Team', 'Bulan', 'Bonus', 'Target', 'Status', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$sts = '<span class="badge badge-success">Open</span>';
				$btn = anchor('bonus/ubah/'.e_url($row->id_bonus),'<span class="fa fa-pencil-alt"></span>',array( 'title' => 'Ubah', 'class' => 'btn btn-primary btn-xs', 'data-toggle' => 'tooltip'));
				$btn .= '&nbsp;';
				$btn .= anchor('bonus/closing/'.e_url($row->id_bonus),'&nbsp;<span class="fa fa-dollar-sign"></span>&nbsp;',array( 'title' => 'Closing', 'class' => 'btn btn-danger btn-xs', 'data-toggle' => 'tooltip'));
				if($row->sts_bonus==1){
					$sts = '<span class="badge badge-danger">Close</span>';
					$btn = anchor('bonus/detail/'.e_url($row->id_bonus),'<span class="fa fa-eye"></span>',array( 'title' => 'Detail', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'));
				}
				$this->table->add_row(	++$i,
							$row->nama_barang,
							get_bulan(date("n", strtotime($row->tgl_bonus))),
							"Rp. ".number_format($row->bonus),
							number_format($row->target),
							$sts,
							$btn
						);
			}
		}
		return  $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "bonus_view",
						"ket" => "Data",
						"add" => anchor('bonus/tambah', '<i class="fa fa-plus"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Tambah Data")),
						"table" => $this->gen_table()
					);
		$this->load->view('index', $data);
	}

	public function cb_bulan($sel='')
	{
		$ret = '<div class="form-group row"><label for="tgl_bonus" class="col-sm-2 col-form-label">Bulan</label><div class="col-sm-10">';
		
		$res = get_bulan();

		foreach ($res as $k => $row) {
			$opt[$k] = $row;
		}
		$js = 'class="form-control" id="tgl_bonus"';
		$ret= $ret.''.form_dropdown('tgl_bonus',$opt,$sel,$js);
		$ret= $ret.'</div></div>';
		return $ret;
	}


	public function gen_table_sales()
	{
		$query=$this->Team_model->get_all();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Barang', 'Anggota', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;
			foreach ($res as $row){
				$t = explode("|", $row->team);
				$anggota = "";
				foreach ($t as $k => $v) {
					$anggota .= "- ".$this->Team_model->get_user($v)." <br>";
				}
				$this->table->add_row(	++$i,
							$row->nama_barang,
							$anggota,
							'<button type="button" onclick="pilih_sales(\''.$row->id_sales_team.'\', \''.$row->nama_barang.'\')" class="btn btn-xs btn-success" data-toggle="tooltip" title="Pilih"><i class="fa fa-check"></i></button>'
						);
			}
		}
		echo $this->table->generate();
		init_datatable_tooltips();
							//
	}

	public function tambah()
	{
		$data = array(
						"page" => "bonus_view",
						"ket" => "tambah",
						"form" => "bonus/add",
						"cb_bulan" => $this->cb_bulan(date("n")),
						);
		$this->load->view('index', $data);
	}

	public function add()
	{
		$data = $this->input->post();
		//unset($data['id_sales_team']);
		unset($data['nama']);
		//unset($data['id_bonus']);
		unset($data['btnSimpan']);
		$data['tgl_bonus'] = date("Y")."-".$data['tgl_bonus']."-01"; 
		$data["id_bonus"] = "B".date("Ymd", strtotime($data['tgl_bonus'])).$data['id_sales_team'];
		$data['sts_bonus'] = 0;
		//print_pre($data);
		if($this->Bonus_model->add($data)){
			alert_notif("success");
			redirect('bonus');
		}else{
			alert_notif("danger");
			redirect('bonus/tambah');
		}
	}

	public function ubah($id)
	{
		$id = d_url($id);
		$data = array(
						"page" => "bonus_view",
						"ket" => "tambah",
						"form" => "bonus/update",
						);
		$q = $this->Bonus_model->get_data($id);
		$res = $q->result();
		foreach ($res as $row) {
			$data['id_bonus'] = $row->id_bonus;
			$data['nama'] = $row->nama_barang;
			$data['id_sales_team'] = $row->id_sales_team;
			$data['cb_bulan'] = $this->cb_bulan(date("n", strtotime($row->tgl_bonus)));
			$data['bonus'] = $row->bonus;
			$data['target'] = $row->target;
		}
		$this->load->view('index', $data);
	}

	public function update()
	{
		$data = $this->input->post();
		$id = $data['id_bonus'];
		unset($data['nama']);
		unset($data['id_bonus']);
		unset($data['btnSimpan']);
		$data['tgl_bonus'] = date("Y")."-".$data['tgl_bonus']."-01"; 
		//print_pre($data);
		if($this->Bonus_model->update($data, $id)){
			alert_notif("success");
			redirect('bonus');
		}else{
			alert_notif("danger");
			redirect('bonus/ubah/'.e_url($id));
		}
	}

	public function closing($id){
		$id = d_url($id);
		$q = $this->Bonus_model->get_data($id);
		$res = $q->result();
		/*foreach ($res as $row) {
			print_pre($row);
		}*/
		$kde = $res[0]->kode_barang;
		$bln = date('n', strtotime($res[0]->tgl_bonus));
		$bb = $bln-1;
		$sql = "SELECT 
						a.id_transaksi
					FROM sales_order a
					JOIN pengiriman d ON a.id_transaksi = d.id_transaksi
					WHERE MONTH(a.tgl_order) = $bb AND a.kode_barang = '$kde' AND d.status_pengiriman = 2
					AND a.id_transaksi NOT IN (SELECT c.id_transaksi FROM bonus_so c JOIN bonus e ON c.id_bonus = e.id_bonus WHERE MONTH(e.tgl_bonus) = $bb )";

		$sql .= "UNION SELECT 
					aa.id_transaksi
				FROM sales_order aa
				JOIN pengiriman dd ON aa.id_transaksi = dd.id_transaksi
				WHERE MONTH(aa.tgl_order) = $bln AND aa.kode_barang = '$kde' AND dd.status_pengiriman = 2";
		//echo $sql;
		$qq = $this->db->query($sql);
		$ress = $qq->result();
		$da = [];
		foreach ($ress as $row) {
			$da[] = array(
						"id_bonus" => $id,
						"id_transaksi" => $row->id_transaksi,
						"sts_bonus_so" => 1
						);
		}
		if($this->Bonus_model->add_detail($da, $id)){
			alert_notif("success");
			redirect('bonus/detail/'.e_url($id));
		}else{
			alert_notif("danger");
			redirect('bonus');
		}
	}

	public function detail($id)
	{
		$id = d_url($id);
		$data = array(
						"page" => "bonus_cs_view",
						"ket"  => "Closing",
						"form" => "bonus/update",
						);
		$q = $this->Bonus_model->get_data($id);
		$res = $q->result();
		foreach ($res as $row) {
			$data['main']["Nama Team"] = $row->nama_barang;
			$data['main']["Bulan"] = get_bulan(date("n", strtotime($row->tgl_bonus)));
			$data['main']["Bonus"] = "Rp. ".number_format($row->bonus);
			$data['main']["Target"] = number_format($row->target);
			$bln = date('n', strtotime($row->tgl_bonus));
			$kde = $row->id_bonus;
			$sql = "SELECT 
						SUM(a.jumlah_order) AS jumlah_order
					FROM bonus_so e
					JOIN sales_order a ON a.id_transaksi = e.id_transaksi
					JOIN pengiriman d ON a.id_transaksi = d.id_transaksi
					WHERE e.id_bonus = '$kde' AND d.status_pengiriman = 2";
			$qq = $this->db->query($sql);
			$ress = $qq->result();

			$data['main']["Tercapai"] = number_format($ress[0]->jumlah_order);
			$bonus = ($ress[0]->jumlah_order / $row->target  ) * $row->bonus;
			$bonus = $bonus>$row->bonus?$row->bonus:$bonus;

			$data['main']["Total Bonus"] = "<strong>Rp. ".number_format($bonus)."</strong>";
			$data['main'][''] = '';

		}
		$data['transaksi'] = $this->gen_table_transaksi($row->id_bonus);
		$this->load->view('index', $data);
	}

	public function gen_table_transaksi($id="")
	{
		$query=$this->Bonus_model->get_transaksi($id);
		//echo $this->db->last_query();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Id Transaksi', 'Nama Sales', 'Nama Barang', 'Jumlah Penjualan', 'Tgl Order');

		if ($num_rows > 0)
		{
			$i = 0;
			foreach ($res as $row){
				
				$this->table->add_row(	++$i,
							$row->id_transaksi,
							$row->nama,
							$row->nama_barang,
							$row->jumlah_order,
							date("d-m-Y", strtotime($row->tgl_order))
						);
			}
		}


		return $this->table->generate();
	}

}

/* End of file Bonus.php */
/* Location: ./application/controllers/Bonus.php */