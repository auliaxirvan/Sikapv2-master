<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Cuti Pegawai Agam S</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
		<div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Unit Kerja <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group">
                       <select class="form-control select-search" name="instansi" >  
                              <option disabled="">Pilih Unit Kerja</option> 
                              <?php foreach ($instansi as $row) { ?>
                                <option value="<?php echo encrypt_url($row->id,'instansi') ?>"><?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?></option>
                              <?php } ?>
                      </select> 
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Pegawai <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="table-responsive">
                    <table id="datatable" class="table table-sm table-hover table-bordered">
                      <thead>
                        <tr class="table-active">
                          <th width="1%">No</th>
                          <th class="text-nowrap">Nama<hr class="m-0">NIP</th>
                          <th width="1%">Jumlah Cuti</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
              </div>
          </div>
         
        </div>
        <?php echo form_close() ?>  
	</div>
</div>
<input type="hidden" name="stag" value="0">
<script type="text/javascript">
// $('.advanced2AutoComplete').autoComplete({
// 	  resolver: 'custom',
// 	  formatResult: function (item) {
// 	    return {
// 	      value: item.id,
// 	      text: item.nama +"(" + item.nip + ")",
// 	      html: [ 
// 	          $('<img>').attr('src', item.icon).css("height", 18), ' ',
// 	          item.nama+'('+item.nip+')'
// 	        ] 
// 	    };
//   },
//   events: {
//     search: function (qry, callback) {
//       // let's do a custom ajax call
//       var user = $('[name="instansi"]').val();
//       $.ajax(
//         uri_dasar+'kepegawaian/cuti/AjaxGet',
//         {
//           data: {modul:"listuser", 'qry': qry, id:user},
//           dataType :"JSON",
//         }
//       ).done(function (res) {
//         callback(res.results);
//       });
//     }
//   }
// });

$('#formAjax').submit(function() {
  var result  = $('.result');
  var spinner = $('#spinner');
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType : "JSON",
        error:function(){
           result.attr("disabled", false);
           spinner.hide();
           bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
         beforeSend:function(){
            result.attr("disabled", true);
            spinner.show();
        },
        success: function(res) {
            if (res.status == true) {
                window.location.assign(uri_dasar+'kepegawaian/cuti/view/'+res.user);
            }else {
                bx_alert(res.message);
            }
            result.attr("disabled", false);
            spinner.hide();
        }
    });
    return false;
});

$(document).ready(function(){
     table = $('#datatable').DataTable({ 
      processing: true, 
      serverSide: true, 
      "ordering": false,
      stateSave: true,
      language: {
            search: '<span></span> _INPUT_',
            searchPlaceholder: 'Cari...',
            processing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
        },  
        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
      ajax: {
          url : uri_dasar+'kepegawaian/cuti/indexJson',
          type:"post",
          "data": function ( data ) { 
                data.csrf_sikap_token_name= csrf_value;
            if ($('[name="stag"]').val() == 1) {
              data.instansi=$('[name="instansi"]').val();
            }else {
                data.instansi= localStorage.index_instansi;
            } 
              },
      },
      "columns": [
          {"data": "id", searchable:false},
          {"data": "nama_nip", searchable:false},
          {"data": "jum", searchable:false},
      ],
      rowCallback: function(row, data, iDisplayIndex) {
          var info = this.fnPagingInfo();
          var page = info.iPage;
          var length = info.iLength;
          var index = page * length + (iDisplayIndex + 1);
          $('td:eq(0)', row).html(index);
      },
       createdRow: function(row, data, index) {
          // $('td', row).eq(5).addClass('text-center');
          $('td', row).eq(6).addClass('text-nowrap');
        },


  });

   // Initialize
   dt_componen();
   loadSettings();
});

$(window).on('unload', function(){
    saveSettings();
});

$('[name="instansi"]').change(function() {
    if ($('[name="stag"]').val() == 1) {
        table.ajax.reload();
    }else {
          $('[name="stag"]').val(1);
    }
})

function loadSettings() {
  if (localStorage.index_instansi) {
     $('[name="instansi"]').val(localStorage.index_instansi).trigger('change');
     if (!$('[name="instansi"]').val()) {
        $('[name="instansi"]').val($('[name="instansi"] option:first').val()).trigger('change');
     }
  }
}

function saveSettings() {
    var instansi = $('[name="instansi"]').val();
    if (instansi) {
      localStorage.index_instansi = instansi;
    }
    
}
</script>
