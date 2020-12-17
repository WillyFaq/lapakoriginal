
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-box-open"></i> Restok</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Restok</h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">
                <?= isset($table)?$table:''; ?>
                <?php if(isset($form)):
                    $hidden = array(
                                        'id_gudang_user' => isset($id_gudang_user)?$id_gudang_user:'', 
                                        'id_gb' => isset($id_gb)?$id_gb:'',
                                    );
                    echo form_open($form, '', $hidden);
                ?>
                    <div class="form-group row">
                        <label for="nama_barang" class="col-sm-2 col-form-label">Nama Barang</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="Nama Barang" <?= isset($nama_barang)?"value='$nama_barang' disabled":''; ?> required >
                            <input type="hidden" name="kode_barang" id="kode_barang" <?= isset($kode_barang)?"value='$kode_barang'":''; ?> required >
                        </div>
                    </div>

                    <div class="form-group row" id="warna_barang_box" style="display: none;">
                        <label for="warna_barang" class="col-sm-2 col-form-label">Warna Barang</label>
                        <div class="col-sm-10">
                            <input type="text" list="warna_barang_list" class="form-control" name="warna_barang" id="warna_barang" placeholder="Warna Barang" <?= isset($warna_barang)?"value='$warna_barang'":''; ?> >
                            <datalist id="warna_barang_list">
                            </datalist>
                        </div>
                    </div> 
                    <div class="form-group row" id="ukuran_barang_box" style="display: none;">
                        <label for="ukuran_barang" class="col-sm-2 col-form-label">Ukuran Barang</label>
                        <div class="col-sm-10">
                            <input type="text" list="ukuran_barang_list" class="form-control" name="ukuran_barang" id="ukuran_barang" placeholder="Ukuran Barang" <?= isset($ukuran_barang)?"value='$ukuran_barang'":''; ?> >
                            <datalist id="ukuran_barang_list">
                            </datalist>
                        </div>
                    </div> 

                    <div class="form-group row">
                        <label for="tgl_gb" class="col-sm-2 col-form-label">Tgl Restok </label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" max="<?= date("Y-m-d"); ?>" name="tgl_gb" id="tgl_gb" placeholder="Tgl Restok" <?= isset($tgl_gb)?"value='".date("Y-m-d", strtotime($tgl_gb))."' ":'required'; ?>  >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="jumlah_gb" class="col-sm-2 col-form-label">Jumlah</label>
                        <div class="col-sm-10">
                            <input type="number" min="1" class="form-control" name="jumlah_gb" id="jumlah_gb" placeholder="Jumlah" <?= isset($jumlah_gb)?"value='$jumlah_gb'":'value="1"'; ?> required >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-md-2">
                            <input type="submit" class="btn btn-primary" name="btnSimpan" value="Simpan">
                        </div>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Barang</h5>
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



<script type="text/javascript">
    $(document).ready(function(){

        $("#nama_barang").focus(function(){
            $(".load-modal").load('<?= base_url("restok/gen_table_barang"); ?>');
            $('#modalBarang').modal('show');
        });

        <?php
            if(isset($warna_barang)){
                echo "add_warna('$warna_barang', true);";
                echo 'console.log("warna");';
            }
            if(isset($ukuran_barang)){
                echo "add_ukuran('$ukuran_barang', true);";
                echo 'console.log("ukuran");';
            }
        ?>

    });

    function pilih_barang(kode_barang, nama_barang, warna_barang, ukuran_barang) {
        $('#modalBarang').modal('hide');
        $("#kode_barang").val(kode_barang);
        $("#nama_barang").val(nama_barang);
        add_warna(warna_barang, false);
        add_ukuran(ukuran_barang, false);
    }

    function add_warna(warna_barang, ro) {
        if(warna_barang!=""){
            $("#warna_barang_box").show();
            $("#warna_barang").attr("readonly", ro);

            $("#warna_barang_list").html("");
            var war = warna_barang.split(",");
            war.forEach(function(t, i){
                $("#warna_barang_list").append("<option>"+t+"</option>");
            });
        }else{
            $("#warna_barang_box").hide();
            $("#warna_barang").attr("data-required",false);
        }
    }

    function add_ukuran(ukuran_barang, ro) {
        if(ukuran_barang!=''){
            $("#ukuran_barang_box").show();

            $("#ukuran_barang").attr("readonly", ro);
            $("#ukuran_barang_list").html("");
            var war = ukuran_barang.split(",");
            war.forEach(function(t, i){
                $("#ukuran_barang_list").append("<option>"+t+"</option>");
            });
        }else{
            $("#ukuran_barang_box").hide();
            $("#ukuran_barang").attr("data-required",false);
        }
    }

</script>

<!--  -->