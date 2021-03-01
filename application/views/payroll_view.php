
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-hand-holding-usd"></i> Penggajian</h1>
</div>
<div class="row row_angket">
	<div class="col-md-12 mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Penggajian</h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">
                <?= isset($table)?$table:''; ?>
                <?php if(isset($form)):
                    echo form_open($form, '', '');
                ?>
                    <div class="form-group row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama Pegawai</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Pegawai" <?= isset($nama)?"value='$nama'":''; ?> required >
                            <input type="hidden" name="id_user" id="id_user" <?= isset($id_user)?"value='$id_user'":''; ?> required >
                            <input type="hidden" name="level" id="level" <?= isset($level)?"value='$level'":''; ?> required >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tgl_gaji" class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tgl_gaji" id="tgl_gaji" placeholder="Tanggal" value='<?= isset($tgl_gaji)?$tgl_gaji:date("Y-m-d"); ?>' required >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="jumlah_gaji" class="col-sm-2 col-form-label">Gaji</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addon-rp">Rp.</span>
                                </div>
                                <input type="number" min="0" class="form-control" name="jumlah_gaji" id="jumlah_gaji" placeholder="Gaji" <?= isset($jumlah_gaji)?"value='$jumlah_gaji'":''; ?> aria-describedby="addon-rp" >
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="bonus" class="col-sm-2 col-form-label">Bonus</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addon-rp">Rp.</span>
                                </div>
                                <input type="number" min="0" class="form-control" name="bonus" id="bonus" placeholder="Bonus" <?= isset($bonus)?"value='$bonus'":'value="0"'; ?> aria-describedby="addon-rp" >
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-md-2">
                            <input type="submit" class="btn btn-primary" name="btnSimpan" value="Simpan">
                        </div>
                    </div>
                </form>
                <?php elseif(isset($detail)): ?>
                <table class="table">
                    <?php
                        foreach ($detail as $k => $v) {
                            echo '<tr>';
                            echo "<th width='10%'>$k</th>";
                            echo "<td width='1%'>:</td>";
                            echo "<td>$v</td>";
                            echo '</tr>';
                        }
                    ?>
                </table>    
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-4 detail_gaji_box" style="display: none;" >
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detail Transaksi</h6>
            </div>
            <div class="card-body load_detail">
            <?php 
            if(isset($json_detail)):
                $json = json_decode($json_detail, true);
                echo $json['table'];  
            endif; 
            ?>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalPegawai" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Pegawai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body load-modal">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
<iframe id="iframe_cetak" frameborder="0" width="0" height="0"></iframe>

<script type="text/javascript">

    $(document).ajaxStart(function(){
        $(".ajax_loading_box").show();
    });
    $(document).ajaxComplete(function(){
        $(".ajax_loading_box").hide();
    });

    $(document).ready(function(){
        $("#nama").focus(function(){
            $(".load-modal").load('<?= base_url("payroll/gen_table_pegawai"); ?>');
            $('#modalPegawai').modal('show');
            $(".load_detail").html(" ");
        });

        $("#tgl_gaji").change(function(){
            if($("#id_user").val()!=""){
                show_detail();
            }
        });

        <?php if(isset($json_detail)): ?>
            $(".detail_gaji_box").show();
            if ($(window).width() < 768) {
                var ttbl = $(".dataTableModal").DataTable({"scrollX": true});
            }else{
                var ttbl = $(".dataTableModal").DataTable();
            }
        <?php endif; ?>
        <?= isset($cetak)?$cetak:''; ?>
    });

    function pilih_pegawai(id, nama, level) {
        $('#modalPegawai').modal('hide');
        $(".load-modal").html(' ');
        $("#id_user").val(id);
        $("#nama").val(nama);
        $("#level").val(level);
        show_detail();
    }

    function show_detail() {
        $(".detail_gaji_box").show();
        var id = $("#id_user").val();
        var level = $("#level").val();
        var tgl = $("#tgl_gaji").val();
        var url = '<?= base_url("payroll/load_detail/") ?>'+id+"/"+tgl+"/"+level;
        //$(".load_detail").load();

        $.ajax({
            url: url, 
            success: function(result){
                //console.log(result);
                var obj = JSON.parse(result);
                //console.log(obj);
                $(".load_detail").html(obj.table);
                $("#jumlah_gaji").val(obj.total);
            }
        });
    }

    function cetak(id) {
        var src = '<?= base_url("payroll/cetak/"); ?>'+id;
        $("#iframe_cetak").attr("src", src);
        console.log(src);
        return false;
    }

</script>