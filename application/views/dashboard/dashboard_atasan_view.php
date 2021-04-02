<!-- Content Row -->
<div class="row">
	<!-- Earnings (Monthly) Card Example -->
	<div class="col-xl-3 col-md-6 mb-4">
		<div class="card border-left-primary shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Order</div>
						<div class="h5 mb-0 font-weight-bold">
						Rp. <?= number_format($semua); ?>
						</div>
					</div>
					<div class="col-auto">
						<i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-md-6 mb-4">
		<div class="card border-left-success shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Order Bulan ini</div>
						<div class="h5 mb-0 font-weight-bold">
						Rp. <?= number_format($bulan_ini); ?>
						</div>
					</div>
					<div class="col-auto">
						<i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-3 col-md-6 mb-4">
		<div class="card border-left-warning shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Order Hari ini</div>
						<div class="h5 mb-0 font-weight-bold">
						<?= number_format($hari_ini); ?>
						</div>
					</div>
					<div class="col-auto">
						<i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-3 col-md-6 mb-4">
		<div class="card border-left-danger shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Barang</div>
						<div class="h5 mb-0 font-weight-bold">
						<?= number_format($barang); ?>   
						</div>
					</div>
					<div class="col-auto">
						<i class="fas fa-box-open fa-2x text-gray-300"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xl-12 col-lg-12">
		<div class="card shadow mb-4">
			<!-- Card Header - Dropdown -->
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">Demograpi Penjualan</h6>
				<div class="dropdown no-arrow">
					
				</div>
			</div>
				<!-- Card Body -->

			<script src="https://code.highcharts.com/maps/highmaps.js"></script>
			<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
			<script src="<?= base_url("assets/js/id_map.js"); ?>"></script>
			<!-- <script src="https://code.highcharts.com/mapdata/countries/id/id-all.js"></script> -->
			<div class="card-body">
				<div class="container filter">
					<div class="form-group row">
						<label for="filter" class="col-sm-2 col-form-label">Filter</label>
						<div class="col-sm-10">
							<select name="filter" id="cb_filter" class="form-control">
								<option value="1">7 Hari</option>
								<option value="2">14 Hari</option>
								<option value="3">30 Hari</option>
								<option value="4">Custom</option>
							</select>
						</div>
					</div>
					<div class="form-group row cb_tgl_box" style="display: none;">
						<div class="col-sm-2"></div>
						<div class="col">
							<input type="date" class="form-control cb_tgl" id="tgl1" format="Y-m-d">
						</div>
						<div class="col">
							<input type="date" class="form-control cb_tgl" id="tgl2" format="Y-m-d">
						</div>
					</div>

					<div class="form-group row cb_product_box">
						<label for="filter" class="col-sm-2 col-form-label">Barang</label>
						<div class="col-sm-10">
							<select name="filter" id="cb_jenis_brg" class="form-control">
								<option value="">Semua Barang</option>
								<?php
									/*$sql = "SELECT 
												DISTINCT SUBSTRING_INDEX(kode_barang, '.', 1) AS kode_barang
											FROM barang ";*/
									$sql = "SELECT 
												kode_barang,
												nama_barang
											FROM barang ";
									$q = $this->db->query($sql);
									$res = $q->result();
									foreach ($res as $row) {
										$sel = '';
										echo '<option value="'.$row->kode_barang.'" '.$sel.'>'.$row->nama_barang.'</option>';
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group row cb_product_box">
						<label for="filter" class="col-sm-2 col-form-label"></label>
						<div class="col-sm-10 row cb_brg_box">
							<div class="col-sm-6">
								<select name="filter" id="cb_brg_warna" class="form-control cb_det_filter_barang">
									<option value="">Semua Warna</option>
									<?php
										$sql = "SELECT *
												FROM barang
												GROUP BY kode_barang";
										$q = $this->db->query($sql);
										$res = $q->result();
										foreach ($res as $row) {
											$sel = '';
											echo '<option value="'.$row->kode_barang.'" '.$sel.'>'.$row->nama_barang.'</option>';
										}
									?>
								</select>
							</div>
							<div class="col-sm-6">
								<select name="filter" id="cb_brg_ukuran" class="form-control cb_det_filter_barang">
									<option value="">Semua Warna</option>
									<?php
										$sql = "SELECT *
												FROM barang
												GROUP BY kode_barang";
										$q = $this->db->query($sql);
										$res = $q->result();
										foreach ($res as $row) {
											$sel = '';
											echo '<option value="'.$row->kode_barang.'" '.$sel.'>'.$row->nama_barang.'</option>';
										}
									?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div id="container">
					<div class="loading_box" id="demograpi_load">
						<img class="img_demo_load" src="<?= base_url('assets/img/loading_world.svg'); ?>" alt="loading">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-12 col-lg-12">
		<div class="card shadow mb-4">
			<!-- Card Header - Dropdown -->
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">Profit History</h6>
				<div class="dropdown no-arrow">
					
				</div>
			</div>
			<!-- Card Body -->
			<div class="card-body">
				<div class="form-group row cb_thn_profit_box">
					<label for="filter" class="col-sm-2 col-form-label">Filter</label>
					<div class="col-sm-10">
						<select name="filter" id="cb_thn_profit" class="form-control">
							<?php
								$sql = "SELECT YEAR(tgl_order) AS tahun
										FROM sales_order
										GROUP BY YEAR(tgl_order)";
								$q = $this->db->query($sql);
								$res = $q->result();
								foreach ($res as $row) {
									$sel = '';
									if($row->tahun==date("Y")){
										$sel = 'selected';
									}
									echo '<option value="'.$row->tahun.'" '.$sel.'>'.$row->tahun.'</option>';
								}
							?>
						</select>
					</div>
				</div>
				<div class="form-group row cb_product_box">
					<label for="filter" class="col-sm-2 col-form-label">Barang</label>
					<div class="col-sm-10">
						<select name="filter" id="cb_jenis_brg_profit" class="form-control">
							<option value="">Semua Barang</option>
							<?php
								$sql = "SELECT 
											kode_barang,
											nama_barang
										FROM barang ";
								$q = $this->db->query($sql);
								$res = $q->result();
								foreach ($res as $row) {
									$sel = '';
									echo '<option value="'.$row->kode_barang.'" '.$sel.'>'.$row->nama_barang.'</option>';
								}
							?>
						</select>
					</div>
				</div>
				<hr>
				<div class="chart-area" id="load_profit_chart">
					<div class="loading_box" id="profit_load">
						<img  src="<?= base_url('assets/img/loading_barchart.svg'); ?>" alt="loading">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-12 col-lg-12">
		<div class="card shadow mb-4">
			<!-- Card Header - Dropdown -->
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">Omset History</h6>
				<div class="dropdown no-arrow">
					
				</div>
			</div>
				<!-- Card Body -->
			<div class="card-body">
				<div class="form-group row cb_thn_profit_box">
					<label for="filter" class="col-sm-2 col-form-label">Filter</label>
					<div class="col-sm-10">
						<select name="filter" id="cb_thn_omset" class="form-control">
							<?php
								$sql = "SELECT YEAR(tgl_order) AS tahun
										FROM sales_order
										GROUP BY YEAR(tgl_order)";
								$q = $this->db->query($sql);
								$res = $q->result();
								foreach ($res as $row) {
									$sel = '';
									if($row->tahun==date("Y")){
										$sel = 'selected';
									}
									echo '<option value="'.$row->tahun.'" '.$sel.'>'.$row->tahun.'</option>';
								}
							?>
						</select>
					</div>
				</div>
				<div class="form-group row cb_product_box">
					<label for="filter" class="col-sm-2 col-form-label">Barang</label>
					<div class="col-sm-10">
						<select name="filter" id="cb_jenis_brg_omset" class="form-control">
							<option value="">Semua Barang</option>
							<?php
								$sql = "SELECT 
											kode_barang,
											nama_barang
										FROM barang ";
								$q = $this->db->query($sql);
								$res = $q->result();
								foreach ($res as $row) {
									$sel = '';
									echo '<option value="'.$row->kode_barang.'" '.$sel.'>'.$row->nama_barang.'</option>';
								}
							?>
						</select>
					</div>
				</div>
				<hr>
				<div class="chart-area" id="load_omset_chart">
					<div class="loading_box" id="omset_load">
						<img  src="<?= base_url('assets/img/loading_barchart.svg'); ?>" alt="loading">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	
	

?>

<div class="modal fade" id="modalDemoDetail" tabindex="-1" aria-labelledby="modalDemoDetailLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		  	<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Detail Demograpi</h5>
				<!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
		  		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  	</div>
			<div class="modal-body" id="" style="height: 500px; overflow-y: scroll;">
				<div id="body-modalDemoDetail">
					<canvas id="detail_demo_bar"></canvas>
				</div>
		  	</div>
		  	<div class="modal-footer">
				<!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button> -->
		  	</div>
		</div>
  	</div>
</div>



<script type="text/javascript">
	$(document).ready(function(){
		$("#demograpi_load").load('<?= base_url("dahsboard/load_demografi/1"); ?>');
		$(".cb_brg_box").load('<?= base_url("dahsboard/cb_barang_filter"); ?>');
		$("#cb_filter").change(function(){
			filter_demo();
		});

		$("#cb_jenis_brg").change(function(){
			var val = $(this).val();
			$(".cb_brg_box").load('<?= base_url("dahsboard/cb_barang_filter/"); ?>'+val, function(){
				filter_demo();
			});

			//filter_demo();
		});

		$(".cb_tgl").change(function(){
			filter_demo();
		});

		$(".cb_det_filter_barang").change(function(){
			filter_demo();
		});


		$("#load_profit_chart").load('<?= base_url("dahsboard/load_profit/"); ?>'+$("#cb_thn_profit").val());
		$("#cb_thn_profit").change(function(){
			filter_profit();
		});
		$("#cb_jenis_brg_profit").change(function(){
			filter_profit();
		});

		$("#load_omset_chart").load('<?= base_url("dahsboard/load_omset/"); ?>'+$("#cb_thn_omset").val());
		$("#cb_thn_omset").change(function(){
			filter_omset();
		});
		$("#cb_jenis_brg_omset").change(function(){
			filter_omset();
		});

			//$("#modalDemoDetail").modal("show");
		/*$("#body-modalDemoDetail").load('<?= base_url("dahsboard/load_detail_demografi/"); ?>', function(){
			$("#modalDemoDetail").modal("show");
		});*/
		$('#modalDemoDetail').on('shown.bs.modal', function (e) {
			render_chart();
		});
	});

	function filter_demo() {
		var v = $("#cb_filter").val();
		var brg = $("#cb_jenis_brg").val();
		var warna = $("#cb_brg_warna").val();
		var ukuran = $("#cb_brg_ukuran").val();
		var kd_brg = "";
		if(warna!="" || ukuran!=""){
			kd_brg = brg+(warna!=undefined?"."+warna:".")+(ukuran!=undefined?"."+ukuran:".");
			//console.log(kd_brg);
		}

		var loading_box = "";
		loading_box += '<div class="loading_box" id="demograpi_load">';
		loading_box += '<img class="img_demo_load" src="<?= base_url('assets/img/loading_world.svg'); ?>" alt="loading">';
		loading_box += '</div>';
		if(v==4){
			$(".cb_tgl_box").show();
			var tgl1 = $("#tgl1").val();
			var tgl2 = $("#tgl2").val();
			var tmbh = "4_"+tgl1+"_"+tgl2;
			$("#demograpi_load").html(loading_box);
			$("#demograpi_load").load('<?= base_url("dahsboard/load_demografi/"); ?>'+tmbh+"/"+brg+"/"+kd_brg);
		}else{
			$(".cb_tgl_box").hide();
			$("#demograpi_load").html(loading_box);
			$("#demograpi_load").load('<?= base_url("dahsboard/load_demografi/"); ?>'+v+"/"+brg+"/"+kd_brg);
		}
	}

	function filter_profit() {
		var v = $("#cb_thn_profit").val();
		var br = $("#cb_jenis_brg_profit").val();
		var loading_box = "";
		loading_box += '<div class="loading_box" id="omset_load">';
		loading_box += '<img  src="<?= base_url('assets/img/loading_barchart.svg'); ?>" alt="loading">';
		loading_box += '</div>';
		$("#load_profit_chart").html(loading_box);
		$("#load_profit_chart").load('<?= base_url("dahsboard/load_profit/"); ?>'+v+"/"+br);
	}

	function filter_omset() {
		var v = $("#cb_thn_omset").val();
		var br = $("#cb_jenis_brg_omset").val();
		var loading_box = "";
		loading_box += '<div class="loading_box" id="omset_load">';
		loading_box += '<img  src="<?= base_url('assets/img/loading_barchart.svg'); ?>" alt="loading">';
		loading_box += '</div>';
		$("#load_omset_chart").html(loading_box);
		$("#load_omset_chart").load('<?= base_url("dahsboard/load_omset/"); ?>'+v+"/"+br);
	}
</script>