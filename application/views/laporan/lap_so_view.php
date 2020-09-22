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
                        <label for="filter" class="col-sm-2 col-form-label">Bulan</label>
                        <div class="col-sm-10">
                            <select name="filter" id="cb_bulan" class="form-control">
                                <option value="1">Semua Bulan</option>
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
                        </tbody>
                    </table>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $(".load_table").load('<?= base_url("laporan_so/gen_table/").date('n'); ?>',function(){
            init_datatable();
        });

        $("#cb_bulan").change(function(){
            var va = $(this).val();
            $(".load_table").load('<?= base_url("laporan_so/gen_table/"); ?>'+va,function(){
                init_datatable();
            });
        });

    });
</script>

<!--  -->