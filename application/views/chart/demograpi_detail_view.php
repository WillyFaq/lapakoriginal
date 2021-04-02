<canvas id="detail_demo_bar"></canvas>
<?php

    $height = sizeof($detail['label']) * 100;
    $height = $height>1000?$height-200:$height;

?>
<script type="text/javascript">
function render_chart(){
    var ctx = document.getElementById("detail_demo_bar");
    ctx.height = <?= $height; ?>;

    // 14 * 100 = 1400 - 500
    var myLineChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: ["<?= join('", "', $detail['label']) ?>"],
            datasets: [{
                label:"Jumlah",
                backgroundColor:window.chartColors.green,
                borderColor:window.chartColors.green,
                data:[<?= join(", ", $detail['data']); ?>],
                fill:false,
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 5,
                    right: 50,
                    top: 0,
                    bottom: 0
                }
            },
            scales: {
                yAxes: [{
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                }],
                xAxes: [{
                    ticks: {
                        min:0
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
                display: false
            },
            animation:{
                duration:1,
                onComplete: function(){
                    var chartInstance = this.chart,
                        ctx = chartInstance.ctx;

                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, "bold", Chart.defaults.global.defaultFontFamily);
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';

                    this.data.datasets.forEach(function(dataset, i) {
                        var meta = chartInstance.controller.getDatasetMeta(i);
                        meta.data.forEach(function(bar, index) {
                            var data = dataset.data[index];
                            //console.log(data);
                            ctx.fillText(data, bar._model.x+10, bar._model.y+10);
                        });
                    });
                }
            }
        }
    });
}
</script>