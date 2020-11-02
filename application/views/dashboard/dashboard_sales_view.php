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
    if(sizeof($semua)>1){
        foreach ($semua as $k => $v) {
            $card["$v[barang]"] = [
                                $v['jml'],
                                $bulan_ini[$k]['jml'],
                                $hari_ini[$k]['jml'],
                                $target[$k]['jml'],
                            ];
        }
    }else{
        $k = 0;
        $card[0] =  [
                                $semua[$k]['jml'],
                                $bulan_ini[$k]['jml'],
                                $hari_ini[$k]['jml'],
                                $target[$k]['jml'],
                            ];
    }
?>
<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
        <h6 class="m-0 font-weight-bold text-primary">Summary</h6>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse show" id="collapseCardExample" style="">
        <div class="card-body">

<?php foreach($card as $k => $v): ?>
<div class="row">
    <?php if($k!="0"): ?>
    <div class="col-xl-12 col-md-12">
        <p><?= $k; ?></p>
    </div>
    
    <?php endif; ?>
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Order</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= number_format($v[0]); ?>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= number_format($v[1]); ?>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= number_format($v[2]); ?>
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
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Target Harian</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= number_format($v[3]); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-bullseye fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
            
        </div>
    </div>
</div>
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
                <div class="chart-area"  style="min-height: 500px;">
                    <div class="loading_box" id="omset_load">
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
    });


    /*
var loading_box = "";
            loading_box += '<div class="loading_box" id="omset_load">';
            loading_box += '<img  src="<?= base_url('assets/img/loading_barchart.svg'); ?>" alt="loading">';
            loading_box += '</div>';
            $("#load_omset_chart").html(loading_box);
    */
</script>