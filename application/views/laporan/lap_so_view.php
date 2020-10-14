<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fa fa-file-alt"></i> Laporan Kinerja SO</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Kinerja SO
                </h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">

                <?php if(!isset($detail)):?>
                <div class="container filter">
                    <div class="form-group row cb_bulan_box">
                        <label for="filter" class="col-sm-2 col-form-label">Tahun</label>
                        <div class="col-sm-10">
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
                        <label for="filter" class="col-sm-2 col-form-label">Bulan</label>
                        <div class="col-sm-10">
                            <select name="filter" id="cb_bulan" class="form-control">
                                <!-- <option value="">Semua Bulan</option> -->
                                <?php
                                    $bln = date("n");
                                    foreach (get_bulan() as $k => $v) {
                                        $sel = $bln==$k?'selected':'';
                                        echo '<option value="'.$k.'" '.$sel.'>'.$v.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="load_table"></div>
                <?php else:?>
                    <table class="table">
                        <tbody>
                        <?php foreach ($detail as $k => $v): ?>
                            <tr>
                                <th width="25%"><?= $k; ?></th>
                                <td width="1%">:</td>
                                <td><?= $v; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th>Detail Penjualan</th>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table dataTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Ketrangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $date_arr = [];
                                $d = date("Y")."-".$bln."-01";
                                $today = date('d-m-Y', strtotime($d)); 
                                $date_arr[$today] = 0;
                                $d=cal_days_in_month(CAL_GREGORIAN,$bln,date("Y"));
                                for($i=1; $i<$d; $i++){
                                    $repeat = strtotime("+1 day",strtotime($today));
                                    $today = date('d-m-Y',$repeat);
                                    $date_arr[$today] = 0;
                                }


                                $res = $penjualan->result();
                                $nama_barang = '';
                                foreach ($res as $row) {
                                    $date_arr[date("d-m-Y", strtotime($row->tgl_order))] = array(
                                                        "nama_barang" => $row->nama_barang,
                                                        "jumlah_order" => $row->jumlah_order,
                                                        );
                                    
                                    $nama_barang = $row->nama_barang;
                                }
                                foreach ($date_arr as $k => $row){
                                    if(!isset($row['nama_barang'])){
                                        $date_arr[$k] = array(
                                                        "nama_barang" => $nama_barang,
                                                        "jumlah_order" => 0,
                                                        );
                                    }
                                }
                                //print_pre($date_arr);

                                $i=0;
                                $jml = 0;
                                foreach ($date_arr as $k => $row):
                                   
                                $jml += $row['jumlah_order'];
                            ?>
                            <tr>
                                <td><?= ++$i; ?></td>
                                <td><?= $k; ?></td>
                                <td><?= $row['nama_barang']; ?></td>
                                <td><?= $row['jumlah_order']; ?></td>
                                <td>
                                    <?php
                                        if($row['jumlah_order']>= $detail['target']){
                                            echo '<span class="badge badge-success">Target</span>';
                                        }else{
                                            echo '<span class="badge badge-danger">Tidak Target</span>';
                                        }
                                    ?>        
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>    
                        <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th><?= $jml; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $(".load_table").load('<?= base_url("laporan_so/gen_table/").date('Y').'/'.date('n'); ?>',function(){
            init_datatable();
        });

        $("#cb_bulan").change(function(){
            var va = $(this).val();
            var thn = $("#cb_thn").val();
            $(".load_table").load('<?= base_url("laporan_so/gen_table/"); ?>'+thn+'/'+va,function(){
                init_datatable();
            });
        });

        $("#cb_thn").change(function(){
            var va = $("#cb_bulan").val();
            var thn = $(this).val();
            $(".load_table").load('<?= base_url("laporan_so/gen_table/"); ?>'+thn+'/'+va,function(){
                init_datatable();
            });
        });

    });
</script>

<!-- 