
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-user-tag"></i> Sales</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Sales</h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">
                <?= isset($table)?$table:''; ?>
                <?php if(isset($form)):
                    $hidden = array(
                                        'id_user' => isset($id_user)?$id_user:'', 
                                        'id_jabatan' => isset($id_jabatan)?$id_jabatan:'',
                                    );
                    echo form_open($form, '', $hidden);
                ?>
                    <div class="form-group row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama Sales</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Sales" <?= isset($nama)?"value='$nama'":''; ?> required >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="username" id="username" placeholder="Username" <?= isset($username)?"value='$username' readonly":''; ?> required >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="password" id="password" placeholder="Password" <?= isset($password)?"value='$password'":''; ?> required >
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label for="barang" class="col-sm-2 col-form-label">Barang </label>
                        <div class="col-sm-10">
                            <button type="button" class="btn btn-success mb-4 btntambahbarang">Tambah Barang</button>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Minimal Penjualan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody_barang">
                                    <input type="hidden" name="kode_barang[]" required >
                                    <input type="hidden" name="minimal_sale[]" required >

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

<div class="modal fade" id="modalMinimal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Minimal Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="nama_barang" class="col-sm-4 col-form-label">Nama Barang</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="txt_nama_barang" placeholder="Nama Barang"  >
                        <input type="hidden" id="txt_kode_barang"  >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="minimal" class="col-sm-4 col-form-label">Minimal Penjualan</label>
                    <div class="col-sm-8">
                        <input type="number" min="1" class="form-control" id="txt_minimal" placeholder="Minimal Penjualan" >
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btnsimpanso">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var barang = [];
    $(document).ready(function(){
        $(".btntambahbarang").click(function(){
            $(".load-modal").load('<?= base_url("iklan/gen_table_barang"); ?>');
            $('#modalBarang').modal('show');
        });        
        $(".btnsimpanso").click(function(){
            if($("#txt_minimal").val()=="" || $("#txt_minimal").val()==0){

            }else{
                $('#modalMinimal').modal('hide');
                var brg = {
                            id: 0,
                            kode_barang: $("#txt_kode_barang").val(),
                            nama_barang: $("#txt_nama_barang").val(),
                            minimal: $("#txt_minimal").val(),
                        };
                var cek = cek_barang(brg.kode_barang, barang); 
                if(cek>=0){
                    barang[cek] = brg;
                }else{
                    barang.push(brg);
                }
                $("#txt_minimal").val("");
                $(".tbody_barang").html(generate_tbl(barang));
            }
        });      

        <?php
            if(isset($barang)){
                foreach ($barang as $k => $v) {
                    echo "barang.push({id:$v[id_sales_barang],kode_barang:'$v[kode_barang]', nama_barang:'$v[nama_barang]', minimal:$v[minimal]});";
                }
                echo '$(".tbody_barang").html(generate_tbl(barang));';
            }
        ?>  
    });

    function generate_tbl(barang){
        var ret = "";
        barang.forEach(function(item, index){
            
            ret += "<tr id=\"tr_"+index+"\">";
                ret += "<td>"+item.nama_barang+"</td>";
                ret += "<td>"+item.minimal+"</td>";
                ret += '<td><button class="btn btn-danger btn-xs btnhapus" onclick="hapus_barang('+index+')" title="hapus" data-toggle="tooltip" ><i class="fa fa-trash"></i></button></td>';
                ret += '<input type="hidden" name="id_sales_barang[]" value="'+item.id+'">';
                ret += '<input type="hidden" name="kode_barang[]" value="'+item.kode_barang+'">';
                ret += '<input type="hidden" name="minimal_sale[]" value="'+item.minimal+'">';
            ret += "</tr>";

        });
        return ret;
    }

    function pilih_barang(kode_barang, nama_barang) {
        $('#modalBarang').modal('hide');
        $('#modalMinimal').modal('show');
        $("#txt_kode_barang").val(kode_barang);
        $("#txt_nama_barang").val(nama_barang);
    }

    function cek_barang(kode, barang){
        var ret = -1;
        barang.forEach(function(item, index){
            if(item.kode_barang == kode){
                ret =  index;
            }
        });
        return ret;
    }

    function hapus_barang(id) {
        delete barang[id];
        $(".tbody_barang").html(generate_tbl(barang));
    }
</script>

<!--  -->