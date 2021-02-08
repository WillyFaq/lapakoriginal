<canvas id="myAreaChart2"></canvas>
<?php

$sql1 = "SELECT DATE(tgl_gaji) as tgl_gaji, SUM(jumlah_gaji+bonus) as jml FROM payroll WHERE
        id_user = ".$this->session->userdata('user')->id_user."
        $sql
        GROUP BY DATE(tgl_gaji)
        ORDER BY DATE(tgl_gaji)";
//echo $sql1;
$q = $this->db->query($sql1);
//echo $this->db->last_query();
$res = $q->result();
$label = [];
$jml = [];
$tmp_kode = "";
foreach ($res as $row) {
    $tgl = date("d-m-Y", strtotime($row->tgl_gaji));
    $tgl2 = date("Y-m-d", strtotime($row->tgl_gaji));
    $label[$tgl] = $tgl;
    $jml[$tgl] = $row->jml;
}
/*
print_pre($label);
print_pre($jml);
print_pre($dada);
*/

$color = ['red','orange','yellow','green','blue','purple','grey', 'black'];
$datasets = [];
$i=0;
if(!empty($jml)){

    $ret = '{';
    $ret .= 'label:"Gaji",';
    $ret .= 'backgroundColor:window.chartColors.'.$color[$i].','."\n";
    $ret .= 'borderColor:window.chartColors.'.$color[$i].','."\n";
    $ret .= 'data:['.join(', ', $jml).'],'."\n";
    $ret .= 'fill:false,'."\n";
    $ret .= '}';
    $datasets[] = $ret;

    
}
/*print_pre($label);
print_pre($jml);
print_pre($datasets);*/
?>
<script type="text/javascript">
        

// Area Chart Example
var ctx = document.getElementById("myAreaChart2");
var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["<?= join('", "', $label) ?>"],
        datasets: [<?= join(", ", $datasets); ?>],
    },
    options: {
        maintainAspectRatio: false,
        title:{
            display: true,
            text: '<?= $judul; ?>',
            fontSize: 20
        },
        layout: {
            padding: {
                left: 10,
                right: 10,
                top: 0,
                bottom: 0
            }
        },
        scales: {
            xAxes: [{
                time: {
                    unit: 'date'
                },
                gridLines: {
                    display: true,
                    drawBorder: true
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
                    drawBorder: true,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                }
            }],
        },
        legend: {
            display: true,
            position: 'bottom'
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