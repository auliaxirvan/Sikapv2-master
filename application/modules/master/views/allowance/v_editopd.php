<!-- Basic table -->
<div class="card">
    <div class="card-header bg-white header-elements-inline py-2">
        <h5 class="card-title">Tambah Instansi Penerima</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>

    <div class="card-body">

        <?php echo form_open('master/allowance/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Nama Uraian <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                    </div>
                    <input type="text" class="form-control" name="nama_jabatan" placeholder="Nama Jabatan"
                        value="<?php echo $tpp_induk->name ?>" disabled>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Unit Kerja <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="form-control multiselect-clickable-groups" name="instansi[]" multiple="multiple"
                        id="filter_list_dropdwn" data-fouc>
                        <?php foreach ($instansi as $row) { ?>
                        <option class="tes" value="<?php echo $row->id ?>" parent_id="<?php echo $row->parent_id ?>">
                            <?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Status</label>
            <div class="col-lg-10">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text">
                            <input type="checkbox" name="status" class="form-control-switchery" checked data-fouc>
                        </span>
                    </span>
                </div>
            </div>
        </div>
        <input type="hidden" name="mod" value="add">
        <input type="hidden" name="allowance_id" value="<?php echo encrypt_url($tpp_induk->id,'allowance');?>">
        <div class="text-left offset-lg-2">
            <button type="reset" class="btn btn-sm bg-orange-300 result">Batal <i class="icon-cross3 ml-2"></i></button>
            <button type="submit" class="btn btn-sm btn-info result">Simpan <i
                    class="icon-checkmark4 ml-2"></i></button>
            <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>
        </div>
    </div>
    <?php echo form_close() ?>
</div>

<script type="text/javascript">
$('.multiselect-clickable-groups').multiselect({
    includeSelectAllOption: true,
    enableClickableOptGroups: true,
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true,
    placeholder: 'Pilih Data',
});

$('.multiselect-item').on('click', function(event) {
    // logic created by  rian reski
    var a = $(this).find('[type="checkbox"]:checked');
    var vala = a.val();
    var t = $('#textbox' + vala).val();

    if (vala !== undefined && t !== undefined) {
        var selected = $('#filter_list_dropdwn').attr('selected', 'selected').val();
        //alert(selected)
        var ya;
        var len = selected.length;
        for (i = 0; i < len; i++) {
            if (selected[i] == vala) {
                ya = true;
            }
        }
        //alert(ya)
        $options = $('#filter_list_dropdwn option');
        if (t == vala && ya != true || t == 0 && ya != true) {
            if (t == vala) {
                //$("#filter_list_dropdwn").val(vala);
                $options.filter('[value="' + vala + '"]').prop('selected', true);
                $('#filter_list_dropdwn option[parent_id=' + vala + ']').each(function() {
                    $(this).prop('selected', true);

                });

                for (i = 0; i < len; i++) {
                    // $("#filter_list_dropdwn").val(selected[i]);
                    $options.filter('[value="' + selected[i] + '"]').prop('selected', true);
                }

                $('#textbox' + vala).val(0);
                $('#filter_list_dropdwn').multiselect("refresh");
            }

            if (t == 0) {
                //alert(ya)
                $('#filter_list_dropdwn option[parent_id=' + vala + ']').each(function() {
                    $(this).prop('selected', false);
                });
                $('#textbox' + vala).val(vala);
                $('#filter_list_dropdwn').multiselect("refresh");
            }
        }
    }

});

$('#formAjax').submit(function() {
    var result = $('.result');
    var spinner = $('#spinner');
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "JSON",
        error: function() {
            result.attr("disabled", false);
            spinner.hide();
            bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
        beforeSend: function() {
            result.attr("disabled", true);
            spinner.show();
        },
        success: function(res) {
            if (res.status == true) {
                bx_alert_success(res.message, 'master/allowance');
            } else {
                bx_alert(res.message);
            }
            result.attr("disabled", false);
            spinner.hide();
        }
    });
    return false;
});
</script>