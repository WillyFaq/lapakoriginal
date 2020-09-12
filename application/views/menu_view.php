
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-list"></i> Menu</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Menu</h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">
                <?= isset($table)?$table:''; ?>
                <?php if(isset($form)):
                    $hidden = array('id_menu' => isset($id_menu)?$id_menu:'');
                    echo form_open($form, '', $hidden);
                ?>
                    <div class="form-group row">
                        <label for="nama_menu" class="col-sm-2 col-form-label">Nama Menu</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama_menu" id="nama_menu" placeholder="Nama Menu" <?= isset($nama_menu)?"value='$nama_menu'":''; ?> required >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="link_menu" class="col-sm-2 col-form-label">Link Menu</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="link_menu" id="link_menu" placeholder="Link Menu" <?= isset($link_menu)?"value='$link_menu'":''; ?> required >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="link_menu" class="col-sm-2 col-form-label">Icon Menu</label>
                        <div class="pil_icn">
                            <div class="col-sm-1">
                                <div class="icon_preview">
                                    <i class="fa <?= isset($icon_menu)?str_replace(".", "", $icon_menu):''; ?>"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <input type="hidden" id="icon_url" name="icon_menu" value="<?= isset($icon_menu)?str_replace(".", "", $icon_menu):''; ?>" required>
                            <button type="button" class="btn btn-primary btn-cari-icon">Pilih Icon</button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="parent_menu" class="col-sm-2 col-form-label">Parent Menu</label>
                        <div class="col-sm-10">
                            <input type="text" list="parent_list" class="form-control" name="parent_menu" id="parent_menu" placeholder="Parent Menu" <?= isset($parent_menu)?"value='$parent_menu'":''; ?> required >
                            <datalist id="parent_list">
                                <?php
                                foreach ($parent_list as $k) {
                                    echo "<option>$k->parent_menu</option>";
                                }
                                ?>
                            </datalist>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="order_menu" class="col-sm-2 col-form-label">Order Menu</label>
                        <div class="col-sm-10">
                            <input type="number" min="0" class="form-control" name="order_menu" id="order_menu" placeholder="Order Menu" <?= isset($order_menu)?"value='$order_menu'":''; ?> required >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-md-2">
                            <input type="submit" class="btn btn-primary" name="btnSimpan" value="Simpan">
                        </div>
                    </div>
                </form>
                <?php endif; ?>
                <?php if(isset($role)):
                    $hidden = array('id_menu' => isset($id_menu)?$id_menu:'');
                    echo form_open($role, '', '');
                ?>
                    <div class="form-group row">
                       <label for="jabatan" class="col-sm-6 col-form-label">Jabatan</label> 
                       <label for="menu" class="col-sm-6 col-form-label">Menu</label> 
                    </div>
                    <div class="form-group row">
                        <?php ///print_pre($menu_all); ?>
                        <div class="col-sm-6">
                            <div class="load_jabatan1">
                                <select class="form-control" name="jabatan" id="list-jabatan" multiple style="height: 250px;">
                                    <?php foreach($jabatan_all as $jrow): ?>
                                    <option value="<?= $jrow->id_jabatan; ?>"><?= $jrow->nama_jabatan; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <select name="menu" id="cb_menu" class="form-control mb-2">
                                <?php foreach($menu_all as $mrow): ?>
                                <option value="<?= $mrow->id_menu; ?>"><?= $mrow->nama_menu; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="role" class="col-sm-6 col-form-label">Jabatan</label>
                            <div class="load_jabatan">
                                <select class="form-control" name="role[]" id="cb_role" multiple style="height: 160px;">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <p>*) Double Click untuk memindahkan.</p>
                        </div>
                        <div class="col-sm-2">
                            <input type="submit" class="btn btn-primary" name="btnSimpan" value="Simpan">
                        </div>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-icon" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Icons</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form action="">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control txt-cari-icon" placeholder="Cari.." aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text icon-cari-icon" id="basic-addon2"><i class="fa fa-search"></i></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="icon_load_box">
                    <div class="container">
                        <div class="row load_icon">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $(".btn-hapus").click(function(){
            var id = $(this).attr("data-id");
            $('#hapusModal').modal('show');
            $(".btnhapus-link").attr("href", "<?= base_url('menu/delete/'); ?>"+id);
        });

        $(".btn-cari-icon").click(function(){
            $("#modal-icon").modal("show");
            $(".load_icon").load("<?= base_url("menu/fa_list") ?>");
        });
        $(".txt-cari-icon").keyup(function(){
            var v = $(this).val();
            $(".load_icon").load("<?= base_url("menu/fa_list") ?>/"+v);
        });

        load_combobox(1);
        //$(".load_jabatan").load('<?= base_url("menu/load_jabatan_role/1"); ?>');
        $("#cb_menu").change(function(){
            var v = $(this).val();
            load_combobox(v);
            //$(".load_jabatan").load('<?= base_url("menu/load_jabatan_role/"); ?>'+v);
        });

        $("#list-jabatan").dblclick(function(){
            var v = $(this).val();
            var txt = $("#list-jabatan>option[value='"+v+"']").text();
            var opt = "<option value='"+v+"' selected>"+txt+"</option>";
            if(txt!=""){
                $("#cb_role").append(opt);
                $("#list-jabatan>option[value='"+v+"']").remove();
            }
        });
        $("#cb_role").dblclick(function(){
            var v = $(this).val();
            var txt = $("#cb_role>option[value='"+v+"']").text();
            var opt = "<option value='"+v+"' selected>"+txt+"</option>";
            if(txt!=""){
                $("#list-jabatan").append(opt);
                $("#cb_role>option[value='"+v+"']").remove();
            }
        });
    });

    function hapus_jabatan(ini) {
        var v = $(ini).val();
        var txt = $("#list-jabatan>option[value='"+v+"']").text();
        var opt = "<option value='"+v+"' selected>"+txt+"</option>";
        if(txt!=""){
            $("#cb_role").append(opt);
            $("#list-jabatan>option[value='"+v+"']").remove();
        }
    }
    function hapus_role(ini) {
        var v = $(ini).val();
            var txt = $("#cb_role>option[value='"+v+"']").text();
            var opt = "<option value='"+v+"' selected>"+txt+"</option>";
            if(txt!=""){
                $("#list-jabatan").append(opt);
                $("#cb_role>option[value='"+v+"']").remove();
            }
    }

    function load_combobox(id) {
        $.ajax({
            url: "<?= base_url("menu/load_jabatan_role/"); ?>"+id, 
            success: function(result){
                //$("#div1").html(result);
                //console.log(result);
                var data = JSON.parse(result);
                //console.log(data);
                $(".load_jabatan").html(data.jabatan2);
                $(".load_jabatan1").html(data.jabatan1);
            }
        });
    }

    function pilih_icon(icn) {
        console.log(icn);
        var ic = icn.replace(".", "")
        var ret = '<div class="col-sm-1"><div class="icon_preview">';
        ret += '<i class="fa '+ic+'"></i>';
        ret += '</div></div>';
        $("#icon_url").val(ic);
        $(".pil_icn").html(ret);
        $("#modal-icon").modal("hide");
    }

</script>

<!--  -->