<?php $this->load->view('templates/head'); ?>
<body>
<style>
        h4{font-size: 0.8rem}
        .cop>h4{font-size: 0.8rem}
        .cop>h3{font-size: 1.2rem}
        .cop>p{font-size: 0.7rem}
        .per{
        		font-size: 1rem;
        		color:#000; 
        		font-weight: bold;
        		text-decoration: underline;
        		text-align: center;
        	}
        .sb{
        		font-size: 0.8rem;
        		color:#000; 
        		font-weight: bold;
        		text-decoration: underline;	
        }
        .main{
            width: 100% !important;
            margin-top: 20px;
            padding: 0;
        }
        .cop{
            padding-bottom: 10px;
            text-align: center;
        }
        .cop>h4, .cop>h3, .cop>p{
            margin: 0;
            color:#000;
        }
        .row-cop{
            border-bottom: 2px;
            border-bottom-color: #000;
            border-bottom-style:groove;
        }
        .tbl{
            color:#000;
        }
        .tbl th, .tbl td{
            font-size: 0.7rem;
            padding: 0.15rem;
            border:none;
        }

        .tbl tr:last-child{
            /*border-bottom: 1px solid #e3e6f0;*/
        }

        .footer{
        	color:#000;
        	text-align: center;
        }

        @page {
          size: 21cm 29.7cm;
          margin: 0;
        }
    </style>
    <div class="container main">
        <div class="row row-cop">
            <div class="col-1">
                <img src="<?= base_url('assets/img/logo.png'); ?>" alt="Logo" width="80%" style="margin-bottom: 10px;">
                <br>
            </div>
            <div class="col-sm cop">
                <h3><strong>Lapak Original</strong></h3>
                <p>Rumah Bos Besar Jl. Rungkut Kidul I, Rungkut Kidul, Kec. Rungkut, Kota Surabaya, Jawa Timur</p>
            </div>
            <div class="col-1">
            </div>
        </div>
        <br>
        <h4 class="per">SLIP GAJI PEGAWAI</h4>
        <br>
        <table class="tbl">
            <?php
                foreach ($detail as $k => $v) {
                	if($k=="Nama" || $k=="Jabatan" || $k=="Tanggal"){
	                    echo '<tr>';
	                    echo "<th width='10%'>$k</th>";
	                    echo "<td width='1%'>:</td>";
	                    echo "<td>$v</td>";
	                    echo '</tr>';
                	}
                }
            ?>
        </table>
        <br>
        <h4 class="sb">PENGHASILAN</h4>
        <br>
        <table class="tbl">
        	<tbody>
            <?php
                foreach ($detail as $k => $v) {
                	if($k!="Nama" && $k!="Jabatan" && $k!="Tanggal"  && $k!="Total"){
	                    echo '<tr>';
	                    echo "<th width='10%'>$k</th>";
	                    echo "<td width='1%'>:</td>";
	                    echo "<td>$v</td>";
	                    echo '</tr>';
                	}
                }
            ?>
        	</tbody>
        </table>
        <br>
        <table class="table" style="background: #DDD; text-align:center; ">
            <tfoot>
            	<tr>
            		<th>Total :</th>
            		<th><?= $detail['Total']; ?></th>
            	</tr>
            	<tr>
            		<td colspan="2" ><i style="font-style: italic;">Terbilang : # <?= $terbilang; ?> #</i></td>
            	</tr>
            </tfoot>
        </table>
        <br>
        <div class="container footer">
        	<div class="row">
        		<div class="col-md-2 offset-md-10">
		        	<p>
		        		Surabaya, <?= $detail['Tanggal']; ?>
		        	</p>
		        	<br>
		        	<br>
		        	<p class="per">Bos Besar</p>	
        		</div>
        	</div>
        </div>
        <script type="text/javascript">
        	window.print();
        </script>
<?php $this->load->view('templates/foot'); ?>