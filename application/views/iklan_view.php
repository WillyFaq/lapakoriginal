
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-comments-dollar"></i> Iklan</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Iklan</h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">
                <?= isset($table)?$table:''; ?>
                <?php if(isset($form)):
                    $hidden = array('id_iklan' => isset($id_iklan)?$id_iklan:'');
                    echo form_open($form, '', $hidden);
                ?>
                    <div class="form-group row">
                        <label for="nama_barang" class="col-sm-2 col-form-label">Nama Barang</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="Nama Barang" <?= isset($nama_barang)?"value='$nama_barang'":''; ?> required >
                            <input type="hidden" name="kode_barang" id="kode_barang" <?= isset($kode_barang)?"value='$kode_barang'":''; ?> required >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tgl_iklan" class="col-sm-2 col-form-label">Tgl Iklan</label>
                        <div class="col-sm-10">
                            <input type="datetime-local" class="form-control" name="tgl_iklan" id="tgl_iklan" placeholder="Tgl Iklan" <?= isset($tgl_iklan)?"value='$tgl_iklan'":''; ?> required >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="biaya_iklan" class="col-sm-2 col-form-label">Biaya Iklan</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addon-rp">Rp.</span>
                                </div>
                                <input type="number" min="0" class="form-control" name="biaya_iklan" id="biaya_iklan" placeholder="Biaya Iklan" <?= isset($biaya_iklan)?"value='$biaya_iklan'":''; ?> required aria-describedby="addon-rp" >
                            </div>
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
        $(".btn-hapus").click(function(){
            var id = $(this).attr("data-id");
            $('#hapusModal').modal('show');
            $(".btnhapus-link").attr("href", "<?= base_url('iklan/delete/'); ?>"+id);
        });

        $("#nama_barang").focus(function(){
            $(".load-modal").load('<?= base_url("iklan/gen_table_barang"); ?>');
            $('#modalBarang').modal('show');
        });

    });

    function pilih_barang(kode_barang, nama_barang) {
        $('#modalBarang').modal('hide');
        $("#kode_barang").val(kode_barang);
        $("#nama_barang").val(nama_barang);
    }

</script>

<!--  -->