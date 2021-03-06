<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fa fa-file-alt"></i> Laporan Sales Order</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Sales Order
                </h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">
                <?php if(!isset($detail)): ?>
                <div class="container filter">
                    <div class="form-group row cb_bulan_box">
                        <label for="filter" class="col-sm-2 col-form-label">Filter</label>
                        <div class="col-sm-10">
                            <select name="filter" id="cb_sales" class="form-control">
                                <?php
                                    $sql = "SELECT *
                                            FROM sales_order a
                                            JOIN user b ON a.id_user = b.id_user
                                            GROUP BY a.id_user";
                                    $q = $this->db->query($sql);
                                    $res = $q->result();
                                    foreach ($res as $row) {
                                        $sel = '';
                                        if($row->tahun==date("Y")){
                                            $sel = 'selected';
                                        }
                                        echo '<option value="'.$row->id_user.'" '.$sel.'>'.$row->nama.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row cb_bulan_box">
                        <div class="offset-md-2 col-sm-10">
                            <select name="filter" id="cb_thn" class="form-control">
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
                    <div class="form-group row cb_bulan_box">
                        <div class="offset-md-2 col-sm-10">
                            <select name="filter" id="cb_bulan" class="form-control">
                                <option value="">Semua Bulan</option>
                                <?php
                                    foreach (get_bulan() as $k => $v) {
                                        echo '<option value="'.$k.'">'.$v.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row cb_tgl_box" style="display:none">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-5">
                            <input type="date" class="form-control cb_tgl" id="tgl1" format="Y-m-d">
                        </div>
                        <div class="col-sm-5">
                            <input type="date" class="form-control cb_tgl" id="tgl2" format="Y-m-d">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="load_table"></div>
                <?php else: ?>
                <table class="table">
                    <tbody>
                        <tr>
                            <th width="20%">Kode Barang</th>
                            <td width="1%">:</td>
                            <td><?= $kode_barang; ?></td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td>:</td>
                            <td><?= $nama_barang; ?></td>
                        </tr>

                        <tr>
                            <th>Harga Jual</th>
                            <td>:</td>
                            <td>Rp. <?= number_format($harga_jual); ?></td>
                        </tr>
                        <tr>
                            <th>Bulan </th>
                            <td>:</td>
                            <td><?= get_bulan($bulan); ?></td>
                        </tr>
                        <tr>
                            <th>Jumlah Penjualan </th>
                            <td>:</td>
                            <td><?= $jumlah_order; ?></td>
                        </tr>
                        <tr>
                            <th colspan="2">Pendapatan dari penjulan</th>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Penjualan Bersih </th>
                            <td>:</td>
                            <th class="text-center">Rp. <?= number_format($total_order); ?></th>
                        </tr>
                        <tr>
                            <th colspan="2">Beban Barang</th>
                            <td></td>
                        </tr>
                        <?php $tot=0; foreach ($beban as $k => $v): $tot+=$v['nominal']*$jumlah_order; ?>
                            <tr>
                                <td><?= $v['nama_beban']; ?></td>
                                <td>:</td>
                                <td>Rp. <?= number_format($v['nominal']*$jumlah_order); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th>Jumlah Beban Barang</th>
                            <td>:</td>
                            <th>Rp. <?= number_format($tot); ?> </th>
                        </tr>

                        <tr>
                            <th>Beban Iklan</th>
                            <td>:</td>
                            <th>Rp. <?= number_format($beban_iklan); ?></th>
                        </tr>
                        <tr>
                            <th>Jumlah Beban Penjualan</th>
                            <td>:</td>
                            <?php  $jml_beban_penjualan =  $tot - $beban_iklan; ?>
                            <th class="text-center">Rp. <?= number_format($jml_beban_penjualan); ?></th>
                        </tr>
                        <tr>
                            <th>Laba Bersih</th>
                            <td>:</td>
                            <?php $laba_bersih = $total_order - $jml_beban_penjualan ?>
                            <th class="text-center">Rp. <?= number_format($laba_bersih); ?></th>
                        </tr>
                    </tbody>
                </table>
                <a href="<?= base_url('laporan_penjualan'); ?>" class="btn btn-success">Kembali</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        var thn = $("#cb_thn").val(); 
        var sales = $("#cb_sales").val(); 
        load_bulanan(sales, "", thn);

       
        $("#cb_bulan").change(function(){
            var v = $(this).val(); 
            var thn = $("#cb_thn").val();
            var sales = $("#cb_sales").val();  
            load_bulanan(sales, v, thn);
        });

        $("#cb_thn").change(function(){
            var v = $("#cb_bulan").val(); 
            var thn = $(this).val(); 
            var sales = $("#cb_sales").val();  
            load_bulanan(sales, v, thn);
        });

        $("#cb_sales").change(function(){
            var v = $("#cb_bulan").val(); 
            var thn = $("#cb_thn").val();
            var sales = $(this).val();  
            load_bulanan(sales, v, thn);
        });

    });

    $(document).ajaxStart(function(){
        $(".ajax_loading_box").show();
    });
    $(document).ajaxComplete(function(){
        $(".ajax_loading_box").hide();
    });

    function load_bulanan(sales, v, thn){
        var url = '<?= base_url("laporan_pso/gen_table_bulanan/"); ?>'+sales+"/"+thn+"/"+v;
        $(".load_table").load(url, function(){
            $('[data-toggle="tooltip"]').tooltip();
            var table = $(".dataTable").DataTable({
                "scrollX": true,
                "pagingType": "full",
                "lengthMenu": [[100, 250, 500, -1], [100, 250, 500, "All"]]
            });
        });
    }

</script>

<!--  -->