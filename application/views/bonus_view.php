
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-hand-holding-usd"></i> Bonus</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Bonus</h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">
                <?= isset($table)?$table:''; ?>
                <?php if(isset($form)):
                    $hidden = array(
                                        'id_bonus' => isset($id_bonus)?$id_bonus:'',
                                    );
                    echo form_open($form, '', $hidden);
                ?>
                    <div class="form-group row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama Team</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Team" <?= isset($nama)?"value='$nama'":''; ?> required >
                            <input type="hidden" name="id_sales_team" id="id_user" <?= isset($id_sales_team)?"value='$id_sales_team'":''; ?> required >
                        </div>
                    </div>
                    <?= isset($cb_bulan)?$cb_bulan:""; ?>
                    <div class="form-group row">
                        <label for="bonus" class="col-sm-2 col-form-label">Bonus</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addon-rp">Rp.</span>
                                </div>
                                <input type="number" min="0" class="form-control" name="bonus" id="bonus" placeholder="Bonus" <?= isset($bonus)?"value='$bonus'":''; ?> required aria-describedby="addon-rp" >
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="target" class="col-sm-2 col-form-label">Target</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="number" min="0" class="form-control" name="target" id="target" placeholder="Target" <?= isset($target)?"value='$target'":''; ?> required >
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

<div class="modal fade" id="modalSales" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Team CS</h5>
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
        /*$(".btn-hapus").click(function(){
            var id = $(this).attr("data-id");
            $('#hapusModal').modal('show');
            $(".btnhapus-link").attr("href", "<?= base_url('iklan/delete/'); ?>"+id);
        });*/

        $("#nama").focus(function(){
            $(".load-modal").load('<?= base_url("bonus/gen_table_sales"); ?>');
            $('#modalSales').modal('show');
        });

    });

    function pilih_sales(id_user, nama) {
        $('#modalSales').modal('hide');
        $("#id_user").val(id_user);
        $("#nama").val(nama);
    }
</script>

<!--  -->