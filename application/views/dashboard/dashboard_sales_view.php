<!-- Content Row -->
<?php
    /*$isWebView = false;
    if((strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile/') !== false) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari/') == false)) :
        $isWebView = true;
    elseif(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) :
        $isWebView = true;
    endif;

    if(!$isWebView) : 
        echo "ini android";
    else :
        echo "ini browser";
        // Normal Browser
    endif;

    if (strpos($_SERVER['HTTP_USER_AGENT'], 'wv') !== false){
        echo "<br> asli android";
    }else{
        echo "<br> asli browser";
    }*/
?>
<?php
    $card = [];
    $tot_ord = 0;
    $bln_ord = 0;
    $har_ord = 0;
    if(sizeof($semua)>1){
        foreach ($semua as $k => $v) {
            $card["$v[barang]"] = [
                                $v['jml'],
                                $bulan_ini[$k]['jml'],
                                $hari_ini[$k]['jml'],
                                $target[$k]['jml'],
                            ];

            $tot_ord += $target[$k]['jml']==""?0:$target[$k]['jml'];
            $bln_ord += $bulan_ini[$k]['jml']==""?0:$bulan_ini[$k]['jml'];
            $har_ord += $hari_ini[$k]['jml']==""?0:$hari_ini[$k]['jml'];
        }
    }else{
        $k = 0;
        $card[0] =  [
                                $semua[$k]['jml'],
                                $bulan_ini[$k]['jml'],
                                $hari_ini[$k]['jml'],
                                $target[$k]['jml'],
                            ];
        $tot_ord += $target[$k]['jml']==""?0:$target[$k]['jml'];
        $bln_ord += $bulan_ini[$k]['jml']==""?0:$bulan_ini[$k]['jml'];
        $har_ord += $hari_ini[$k]['jml']==""?0:$hari_ini[$k]['jml'];
    }
?>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Order</div>
                        <div class="h5 mb-0 font-weight-bold">
                        <?= number_format($tot_ord); ?>
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
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Order Bulan ini</div>
                        <div class="h5 mb-0 font-weight-bold">
                        <?= number_format($bln_ord); ?>
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
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Order Hari ini</div>
                        <div class="h5 mb-0 font-weight-bold">
                        <?= number_format($har_ord); ?>
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
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Penghasilan Bulan ini</div>
                        <div class="h5 mb-0 font-weight-bold">
                        Rp. <?= number_format($gaji_bulan_ini); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-thumbs-up fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Content Column -->
    <div class="col-lg-6 mb-4">
        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Target Harian</h6>
            </div>
            <div class="card-body">
                <?php foreach($card as $k => $v): 
                        $h = $v[2]==""?0:$v[2];
                        $t = $v[3]==""?0:$v[3];
                        $p = ($h/$t)*100;
                ?>
                    <h4 class="small font-weight-bold"><?= $k; ?> <span
                        class="float-right"><?= "$h/$t"; ?></span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $p; ?>%"
                            aria-valuenow="<?= $p; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php if($tbl_pending!=''):?>
<div class="row">
    <!-- Area Chart -->
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Order Pending</h6>
                <div class="dropdown no-arrow">
                </div>
            </div>
                <!-- Card Body -->
            <div class="card-body">
                <?= $tbl_pending; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if($tbl_kirim!=''):?>
<div class="row">
    <!-- Area Chart -->
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Order Sudah dikirim</h6>
                <div class="dropdown no-arrow">
                </div>
            </div>
                <!-- Card Body -->
            <div class="card-body">
                <?= $tbl_kirim; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <!-- Area Chart -->
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Order History</h6>
                <div class="dropdown no-arrow">
                </div>
            </div>
                <!-- Card Body -->
            <div class="card-body">
                <div class="form-group row cb_tgl_box">
                    <label for="filter" class="col-sm-2 col-form-label">Filter</label>
                    <div class="col">
                        <input type="date" class="form-control cb_tgl" id="tgl1" format="Y-m-d">
                    </div>
                    <div class="col">
                        <input type="date" class="form-control cb_tgl" id="tgl2" format="Y-m-d">
                    </div>
                </div>
                <div class="chart-area"  style="min-height: 500px;width: 100%;overflow-x: scroll;">
                    <div class="loading_box" id="omset_load">
                    <img  src="<?= base_url('assets/img/loading_barchart.svg'); ?>" alt="loading">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <!-- Area Chart -->
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Income History</h6>
                <div class="dropdown no-arrow">
                </div>
            </div>
                <!-- Card Body -->
            <div class="card-body">
                <div class="form-group row cb_tgl_box">
                    <label for="filter" class="col-sm-2 col-form-label">Filter</label>
                    <div class="col">
                        <input type="date" class="form-control cb_tgl_2" id="tgl1_2" format="Y-m-d">
                    </div>
                    <div class="col">
                        <input type="date" class="form-control cb_tgl_2" id="tgl2_2" format="Y-m-d">
                    </div>
                </div>
                <div class="chart-area-2"  style="min-height: 500px;width: 100%;overflow-x: scroll;">
                    <div class="loading_box" id="gaji_load">
                    <img  src="<?= base_url('assets/img/loading_barchart.svg'); ?>" alt="loading">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".chart-area").load('<?= base_url('dahsboard/load_history'); ?>');

        $(".cb_tgl").change(function(){
            var tgl1 = $("#tgl1").val();
            var tgl2 = $("#tgl2").val();
            var url  = '<?= base_url('dahsboard/load_history'); ?>/'+tgl1+"_"+tgl2;
            console.log(url);

            var loading_box = "";
            loading_box += '<div class="loading_box" id="omset_load">';
            loading_box += '<img  src="<?= base_url('assets/img/loading_barchart.svg'); ?>" alt="loading">';
            loading_box += '</div>';
            $(".chart-area").html(loading_box);


            $(".chart-area").load(url);
        });

        $(".chart-area-2").load('<?= base_url('dahsboard/load_income_history'); ?>');
        /*$(".cb_tgl_2").change(function(){
            var tgl1 = $("#tgl1_2").val();
            var tgl2 = $("#tgl2_2").val();
            var url  = '<?= base_url('dahsboard/load_income_history'); ?>/'+tgl1+"_"+tgl2;
            console.log(url);

            var loading_box = "";
            loading_box += '<div class="loading_box" id="gaji_load">';
            loading_box += '<img  src="<?= base_url('assets/img/loading_barchart.svg'); ?>" alt="loading">';
            loading_box += '</div>';
            $(".chart-area-2").html(loading_box);


            $(".chart-area-2").load(url);
        });*/
    });


    /*
var loading_box = "";
            loading_box += '<div class="loading_box" id="omset_load">';
            loading_box += '<img  src="<?= base_url('assets/img/loading_barchart.svg'); ?>" alt="loading">';
            loading_box += '</div>';
            $("#load_omset_chart").html(loading_box);
    */
</script>