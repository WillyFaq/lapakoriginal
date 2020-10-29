<canvas id="myAreaChart"></canvas>
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
        $sql
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