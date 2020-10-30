
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-users-cog"></i> Team CS</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Team CS</h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">
                <?= isset($table)?$table:''; ?>
                <?php if(isset($form)):
                    $hidden = array(
                                    'id_sales_team' => isset($id_sales_team)?$id_sales_team:'',
                                    );
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
                        <label for="nama_barang" class="col-sm-2 col-form-label">Team</label>
                        <div class="col-sm-10">
                            <button type="button" class="btn btn-success btn_sales"><i class="fa fa-plus"></i> Tambah Sales</button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-md-2">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Sales</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody_load">
                                    
                                </tbody>
                            </table>
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

<div class="modal fade" id="modalSales" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Sales</h5>
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

        $(".btn_sales").click(function(){
            $(".load-modal").load('<?= base_url("team_cs/gen_table_sales"); ?>');
            $('#modalSales').modal('show');
        });

        <?php 
        if(isset($team)){
            foreach ($team as $k => $v) {
                echo "$('.tbody_load').append(add_sales($k, '$v'));";
            }
        } 
        ?>

    });

    function pilih_barang(kode_barang, nama_barang) {
        $('#modalBarang').modal('hide');
        $("#kode_barang").val(kode_barang);
        $("#nama_barang").val(nama_barang);
    }

    function pilih_sales(id_user, nama) {
        $('#modalSales').modal('hide');
       /* $("#id_user").val(id_user);
        $("#nama").val(nama);*/
        $(".tbody_load").append(add_sales(id_user, nama));
    }

    function add_sales(id, nama) {
        var ret = "";
        ret += "<tr id='S"+id+"'>";
            ret += "<input type='hidden' name='id_sales[]' value='"+id+"' >";
            ret += "<td>"+nama+"</td>";
            ret += '<td><button type="button" class="btn btn-sm btn-danger" onclick="hapus_sales(\'S'+id+'\')" title="Hapus Beban" data-toggle="tooltip"><i class="fa fa-trash"></i></button></td>';
        ret += "</tr>";
        return ret;
    }

    function hapus_sales(id){
        $("tr#"+id).remove();
    }

</script>

<!--  -->
