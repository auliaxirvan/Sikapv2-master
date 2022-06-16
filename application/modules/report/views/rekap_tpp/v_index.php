<style type="text/css">
.gradient_1 {
    background-image: linear-gradient(to right, #f6d365 0%, #fda085 51%, #f6d365 100%);
}

.gradient_2 {
    background-image: linear-gradient(to right, #fbc2eb 0%, #a6c1ee 51%, #fbc2eb 100%);
}

.gradient_3 {
    background-image: linear-gradient(to right, #84fab0 0%, #8fd3f4 51%, #84fab0 100%);
}

.gradient_4 {
    background-image: linear-gradient(to right, #a1c4fd 0%, #c2e9fb 51%, #a1c4fd 100%);
}

.gradient_5 {
    background-image: linear-gradient(to right, #ffecd2 0%, #fcb69f 51%, #ffecd2 100%);
}
</style>

<!-- Basic table -->
<div class="card">
    <div class="card-header bg-white header-elements-inline py-2">
        <h5 class="card-title">Rekapitulasi Pemotongan TPP</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>
    <?php echo form_open('report/rekap-tpp/cetak','class="form-horizontal" target="popup" id="formAjax"'); ?>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Unit Kerja <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="form-control select-search result" name="instansi">
                        <?php foreach ($instansi as $row) { ?>
                        <option value="<?php echo encrypt_url($row->id,'instansi') ?>">
                            <?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Kategori Pengguna <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="form-control select-nosearch result" name="kategori">
                        <option value="0">Semua..</option>
                        <option value="1">PNS</option>
                        <option value="2">NON PNS</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group row" id="tpp">
            <label class="col-form-label col-lg-2">Katagori Lainnya </label>
            <div class="col-lg-10">
                <label class="pure-material-checkbox mt-2">
                    <input type="checkbox" class="result" name="tpp" checked /> <span>Pernerima TPP</span>
                </label>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Pegawai <span class="text-danger">*</span>
                <i class="icon-spinner2 spinner" style="display: none" id="spinner_pegawai"></i>
            </label>
            <div class="col-lg-10">
                <div class="form-group">
                    <div id="pegawai">
                        <select class="form-control multiselect-clickable-groups" name="pegawai[]" multiple="multiple"
                            data-fouc>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2"> Rentang Waktu <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-group">
                        <select class="form-control select-nosearch result" name="tahun">
                            <option disabled="">Pilih Tahun..</option>
                            <?php foreach ($laporan_tahun as $row) {  ?>
                            <option value="<?php echo $row->tahun ?>"
                                <?php if ($row->tahun == date('Y')) { echo "selected";} ?>><?php echo $row->tahun ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-group">
                        <select class="form-control select-nosearch result" name="bulan">
                            <option disabled="">Pilih Bulan..</option>
                            <?php for ($i=1; $i < 13; $i++) { ?>
                            <option value="<?php echo $i ?>" <?php if ($i == date('m')) { echo "selected";} ?>>
                                <?php echo _bulan($i) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-left offset-lg-2">
            <span class="btn btn-sm btn-info result" id="kalkulasi">Tampilkan <i class="icon-search4 ml-2"></i></span>
            <button type="submit" class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1 result" id="cetak">
                <span><i class="icon-printer mr-2"></i> Cetak</span>
            </button>
            <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>
        </div>
        <?php echo form_close() ?>
        <div class="table-responsive">
            <table id="datatable" class="table table-sm table-hover table-bordered">
                <thead>
                    <tr class="text-center table-active">
                        <th width="1%" rowspan="3">No</th>
                        <th width="1%" rowspan="3">Nama/NIP</th>
                        <th rowspan="1" colspan="11">Pengurangan Aspek Displin Kerja</th>
                        <th rowspan="1" colspan="3">Pengurangan Aspek Produktivitas Kerja</th>

                    </tr>
                    <tr class=" text-center table-active">
                        <th width="1%" class="p-1" colspan="2">Terlambat Masuk Kerja</th>
                        <th width="1%" class="p-1" colspan="2">Tidak Apel Pagi</th>
                        <th width="1%" class="p-1" colspan="2">Pulang Kerja Lebih Awal</th>
                        <th width="1%" class="p-1" colspan="2">Tidak Hadir Tanpa Keterangan</th>
                        <th width="1%" class="p-1" colspan="2">Tidak Mengikuti Upacara</th>
                        <th width="1%" class="p-1" colspan="1">Total % Pengurangan Aspek Disiplin Kerja</th>
                        <th width="1%" class="p-1" colspan="2">Tidak Membuat LKH</th>
                        <th width="1%" class="p-1" colspan="1">Total % Pengurangan Aspek Produktivitas Kerja</th>
                    </tr>
                    <tr class=" text-center table-active">
                        <th class="p-1">jml</th>
                        <th class="p-1">Nilai(%)</th>
                        <th class="p-1">jml</th>
                        <th class="p-1">Nilai(%)</th>
                        <th class="p-1">jml</th>
                        <th class="p-1">Nilai(%)</th>
                        <th class="p-1">jml</th>
                        <th class="p-1">Nilai(%)</th>
                        <th class="p-1">jml</th>
                        <th class="p-1">Nilai(%)</th>
                        <th class="p-1">Nilai(%)</th>
                        <th class="p-1">jml</th>
                        <th class="p-1">Nilai(%)</th>
                        <th class="p-1">Nilai(%)</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
$('.multiselect-clickable-groups').multiselect({
    includeSelectAllOption: true,
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true,
    placeholder: 'Pilih Pegawai',
});

$(document).ready(function() {
    DataPegawai();
});

$('[name="instansi"]').change(function() {
    DataPegawai();
})

$('[name="kategori"]').change(function() {
    DataPegawai();
})

$('[name="tpp"]').change(function() {
    DataPegawai();
})



var result = $('.result');
var spinner = $('#spinner');

$('#kalkulasi').click(function() {
    result.attr("disabled", true);
    spinner.show();
    $('#kalkulasi').hide();
    table.ajax.reload();
})
$(document).ready(function() {
    table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        "ordering": false,
        "searching": false,
        language: {
            search: '<span></span> _INPUT_',
            searchPlaceholder: 'Cari...',
            processing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
        },
        "lengthMenu": [
            [10, 25, 50, 100, 200],
            [10, 25, 50, 100, 200]
        ],
        ajax: {
            url: uri_dasar + 'report/rekap-tpp/indexJson',
            type: "post",
            "data": function(data) {
                data.csrf_sikap_token_name = csrf_value;
                data.pegawai = $('[name="pegawai[]"]').val();
                data.tahun = $('[name="tahun"]').val();
                data.bulan = $('[name="bulan"]').val();
            },
            beforeSend: function() {
                result.attr("disabled", true);
                $('#kalkulasi').hide();
                spinner.show();
            },
            "dataSrc": function(json) {
                //Make your callback here.
                result.attr("disabled", false);
                spinner.hide();
                $('#kalkulasi').show();
                return json.data;
            }
        },
        "columns": [{
                "data": "id",
                searchable: false
            },
            {
                "data": "nama_nip",
                searchable: false
            },
            {
                "data": "jum_terlambar_rekap",
                searchable: false
            },
            {
                "data": "persen_terlambar_rekap",
                searchable: false
            },
            {
                "data": "jum_tidak_apel",
                searchable: false
            },
            {
                "data": "persen_tidak_apel",
                searchable: false
            },
            {
                "data": "jum_pulang_cepat_rekap",
                searchable: false
            },
            {
                "data": "persen_pulang_cepat",
                searchable: false
            },
            {
                "data": "jum_tk_rekap",
                searchable: false
            },
            {
                "data": "persen_tk",
                searchable: false
            },
            {
                "data": "jum_tidak_upacara_rekap",
                searchable: false
            },
            {
                "data": "persen_tidak_upacara",
                searchable: false
            },
            {
                "data": "total_persen_aspek_disiplin",
                searchable: false
            },
            {
                "data": "jum_tidak_buat_lkh",
                searchable: false
            },
            {
                "data": "persen_tidak_buat_lkh",
                searchable: false
            },
            {
                "data": "persen_tidak_buat_lkh",
                searchable: false
            },

        ],
        rowCallback: function(row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            $('td:eq(0)', row).html(index);

        },
        createdRow: function(row, data, index) {
            $('td', row).eq(1).addClass('text-nowrap p-1');
            $('td', row).eq(2).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(3).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(4).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(5).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(6).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(7).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(8).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(9).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(10).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(11).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(12).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(13).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(14).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(15).addClass('text-nowrap p-1 text-center');
        },
    });
    // Initialize
    dt_componen();

});

function DataPegawai() {
    var dept_id = $('[name="instansi"]').val();
    var pns = $('[name="kategori"]').val();
    var tpp = $('[name="tpp"]').is(':checked');
    var result = $('.result');
    var spinner = $('#spinner_pegawai');
    $.ajax({
        type: 'get',
        url: uri_dasar + 'report/rekap-kehadiran/AjaxGet',
        data: {
            mod: 'DataPegawai',
            id: dept_id,
            pns: pns,
            tpp: tpp
        },
        dataType: "html",
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
            $('#pegawai').html(res);
            result.attr("disabled", false);
            spinner.hide();
        }
    });

}

$('#cetak').click(function() {
    window.open('about:blank', 'popup', 'width=1000,height=600')
    $('#formID').submit();


})
</script>