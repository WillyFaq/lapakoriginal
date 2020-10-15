<!-- Content Row -->
<div class="row">
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Order</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                            if(sizeof($semua)>1){
                                foreach ($semua as $k => $v) {
                                    echo "$v[barang] (".number_format($v['jml']).")<br>";
                                }
                            }else{
                                echo number_format($semua[0]['jml']);
                            }
                        ?>
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
                        <?php
                            if(sizeof($bulan_ini)>1){
                                foreach ($bulan_ini as $k => $v) {
                                    echo "$v[barang] (".number_format($v['jml']).")<br>";
                                }
                            }else{
                                echo number_format($bulan_ini[0]['jml']);
                            }
                        ?>
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
                        <?php
                            if(sizeof($hari_ini)>1){
                                foreach ($hari_ini as $k => $v) {
                                    echo "$v[barang] (".number_format($v['jml']).")<br>";
                                }
                            }else{
                                echo number_format($hari_ini[0]['jml']);
                            }
                        ?>
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
                        <?php
                            if(sizeof($target)>1){
                                foreach ($target as $k => $v) {
                                    echo "$v[barang] (".number_format($v['jml']).")<br>";
                                }
                            }else{
                                echo number_format($target[0]['jml']);
                            }
                        ?>    
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
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$sql = "SELECT 
           a.id_user,
           a.kode_barang,
           b.nama_barang,
           a.tgl_order,
           SUM(a.jumlah_order) AS jumlah_order
        FROM sales_order a
        JOIN barang b ON a.kode_barang = b.kode_barang
        WHERE a.id_user = ".$this->session->userdata('user')->id_user."
        AND YEAR(a.tgl_order) = ".date("Y")."
        GROUP BY a.kode_barang, DATE(a.tgl_order)";
$q = $this->db->query($sql);
$res = $q->result();
$label = [];
$jml = [];
$dada = [];
foreach ($res as $row) {
    $label[date("d-m-Y", strtotime($row->tgl_order))] = date("d-m-Y", strtotime($row->tgl_order));
    $jml[$row->kode_barang][] = $row->jumlah_order;
    $dada[$row->kode_barang] = $row->nama_barang;
}

$color = ['red','orange','yellow','green','blue','purple','grey'];
$data_set = [];
$i=0;
foreach ($dada as $key => $value) {
    $ret = '{';
    //print_pre($jml[$key]);
    $ret .= 'label:"'.$value.'",';
    $ret .= 'backgroundColor:window.chartColors.'.$color[$i].','."\n";
    $ret .= 'borderColor:window.chartColors.'.$color[$i].','."\n";
    $ret .= 'data:['.join(', ', $jml[$key]).'],'."\n";
    $ret .= 'fill:false,'."\n";

    $ret .= '}';
    $i++;
    $datasets[] = $ret;
}
?>
<script type="text/javascript">
        

// Area Chart Example
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["<?= join('", "', $label) ?>"],
        datasets: [<?= join(", ", $datasets); ?>],
    },
    options: {
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
        },
        scales: {
            xAxes: [{
                time: {
                    unit: 'date'
                },
                gridLines: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    maxTicksLimit: 7
                }
            }],
            yAxes: [{
                ticks: {
                    maxTicksLimit: 5,
                    padding: 10
                },
                gridLines: {
                    color: "rgb(234, 236, 244)",
                    zeroLineColor: "rgb(234, 236, 244)",
                    drawBorder: false,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                }
            }],
        },
        legend: {
            display: "bootom"
        },
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            titleMarginBottom: 10,
            titleFontColor: '#6e707e',
            titleFontSize: 14,
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: 'index',
            caretPadding: 10,
            callbacks: {
                label: function(tooltipItem, chart) {
                    var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                    return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                }
            }
        }
    }
});





</script>