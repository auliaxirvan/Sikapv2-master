<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Irvan Aulia
* 2023
*/

class Rekap_absen extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Rekapitulasi Kehadiran', 'absen/rekap-absen');
		$this->data['title'] = "Kehadiran";
		$this->load->model(['m_instansi','m_pejabat_instansi','m_absen','m_sch_run']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
    	$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
	}

	public function index()
	{
		$this->data['sub_title']  = "Rekapitulasi Kehadiran";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->data['laporan_tahun'] = $this->m_sch_run->GetTahun()->result();
		$this->load->view('rekap_absen/v_index', $this->data);
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('mod');
		if ($this->mod == "DataPegawai") {
			$dept_id = decrypt_url($this->input->get('id'),'instansi');
			$pns 	 = $this->input->get('pns');
			$tpp 	 = $this->input->get('tpp');
				$this->db->select('*')
						->from('v_users_all')
						->where('key > 0')
        				->where('att_status',1)
        				->where('dept_id', $dept_id);
        		if ($pns) {
        			$this->db->where('pns',$pns);
        		}
        		if ($tpp != 'false' && $tpp) {
        			$this->db->where('tpp',1);
        		}

				$user_id = $this->session->userdata('tpp_user_id');
				$this->db->where('id', $user_id);
				
        		$data_pegawai = $this->db->get()->result();

        		$res='<select class="form-control multiselect-clickable-groups" name="pegawai[]" multiple="multiple" data-fouc>';
        		$no = 1;
        		foreach ($data_pegawai as $row ){
		          		$res.= "<option selected value='".$row->id."'>($no) $row->nama/$row->nip</option>";
		          		$no++;
		          }
		        $res .='</select>
		        		<script type="text/javascript" language="javascript" > 
		        			$(".multiselect-clickable-groups").multiselect({
							    includeSelectAllOption: false,
							    enableFiltering: false,
							    enableCaseInsensitiveFiltering: false,
							    placeholder: "Pilih Pegawai",
							});
		        		</script>';

		        $this->output->set_output($res);
		}elseif ($this->mod == "a") {
			
			
		}
		
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$user_id  	= $this->input->post('pegawai');
		$user_id_in =array();
		if ($user_id) {
			foreach ($user_id as $r_v ) {
				$user_id_in[] = $r_v;
			}
		}
		
		$tahun  	= $this->input->post('tahun');
		$bulan  	= $this->input->post('bulan');

		if ($tahun && $bulan) {
			$hari_ini 		= "$tahun-$bulan-01";
 			$rank1 			= date('Y-m-01', strtotime($hari_ini));
 			$rank2 			= date('Y-m-t', strtotime($hari_ini));
			
		}else {
			$rank1  	   = date('Y-m-d');
			$rank2  	   = date('Y-m-d');
		}
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.dept_id,	a.nama, a.status_pegawai, a.nip, a.gelar_dpn, a.gelar_blk, b.json_absen, a.agama_id')
        	->from("v_users_all a")
        	->order_by('no_urut');
        	 $this->db->join("(select a.id,
								json_build_object(
										'data_absen',json_agg(
										(	rentan_tanggal, 
											schrun_id,
											start_date, 
											end_date, 
											start_time, 
											end_time,
											jam_masuk,
											jam_pulang,
											kd_shift,
											start_time_shift,
											end_time_shift,
											jam_masuk_shift,
											jam_pulang_shift,
											kode_cuti,
											lkhdl_id,
											dinasmanual_id,
											status_in,
											status_out,
											daysoff_id,
											start_time_notfixed,
											end_time_notfixed,
											jam_masuk_notfixed,
											jam_pulang_notfixed,
											count_day_shift,
											jumtidak_upacara,
											ibadah_id
										) ORDER BY rentan_tanggal)
								) as json_absen
							from mf_users a
							left join (
											select a.id, 
											rentan_tanggal,
											b.schrun_id, 
											b.start_date, 
											b.end_date, 
											b.start_time, 
											b.end_time,
											min((c.checktime)::time without time zone) AS jam_masuk,
											max((d.checktime)::time without time zone) AS jam_pulang,
											e.kd_shift,
											e.start_time as start_time_shift,
											e.end_time as end_time_shift,
											min((f.checktime)::time without time zone) AS jam_masuk_shift,
											max((g.checktime)::time without time zone) AS jam_pulang_shift,
											i.kode as kode_cuti,
											j.tgl_lkh as lkhdl_id,
											k.user_id as dinasmanual_id,
											l.status_in,
											l.status_out,
											m.id as daysoff_id,
											n.start_time as start_time_notfixed, 
											n.end_time as end_time_notfixed,
											min((o.checktime)::time) AS jam_masuk_notfixed,
											max((p.checktime)::time) AS jam_pulang_notfixed,
											e.count_day as count_day_shift,
											q.jum as jumtidak_upacara,
											r.ibadah_id
											from 
											(select a.id, a.dept_id, rentan_tanggal from mf_users a, (select * from rentan_tanggal('$rank1','$rank2')) as tanggal) as a
											left join v_jadwal_kerja_users_2 b on ((rentan_tanggal >= b.start_date and rentan_tanggal <= b.end_date and extract('isodow' from a.rentan_tanggal) = b.s_day)and b.user_id=a.id)
											left join mf_checkinout c on ((a.id = c.user_id) AND (a.rentan_tanggal = date(c.checktime)) AND ((c.checktime)::time without time zone >= b.check_in_time1) AND ((c.checktime)::time without time zone <= b.check_in_time2))
											left join mf_checkinout d on ((a.id = d.user_id) AND (a.rentan_tanggal = date(d.checktime)) AND ((d.checktime)::time without time zone >= b.check_out_time1) AND ((d.checktime)::time without time zone <= b.check_out_time2))
											left join v_jadwal_kerja_users_shift_2 e on (a.id = e.user_id and e.start_shift=a.rentan_tanggal)
											left join mf_checkinout f on ((a.id = f.user_id) AND (e.start_shift = date(f.checktime)) AND ((f.checktime)::time without time zone >= e.check_in_time1) AND ((f.checktime)::time without time zone <= e.check_in_time2))
											left join mf_checkinout g on ((a.id = g.user_id) AND (e.end_shift = date(g.checktime)) AND ((g.checktime)::time without time zone >= e.check_out_time1) AND ((g.checktime)::time without time zone <= e.check_out_time2))
											left join data_cuti h on (a.id = h.user_id and h.deleted =1 and (rentan_tanggal >= h.start_date and rentan_tanggal <= h.end_date)) 
											left join _cuti i on h.cuti_id=i.id
											left join data_lkh j on (a.id = j.user_id and a.rentan_tanggal=j.tgl_lkh and j.status=1 and j.jenis=3)
											left join v_dinas_manual k on (a.id = k.user_id and k.tanggal=a.rentan_tanggal)
											left join v_absenmanual_data l on (a.id = l.user_id and l.tanggal=a.rentan_tanggal)
											left join days_off m on (rentan_tanggal >= m.start_date and rentan_tanggal <= m.end_date)
											left join v_jadwal_kerja_users_notfixed_2 n on ((rentan_tanggal >= n.start_date and rentan_tanggal <= n.end_date and extract('isodow' from a.rentan_tanggal) = n.day_id)and n.user_id=a.id)
											left join mf_checkinout o on ((a.id = o.user_id) AND (a.rentan_tanggal = date(o.checktime)) AND ((o.checktime)::time without time zone >= n.check_in_time1) AND ((o.checktime)::time without time zone <= n.check_in_time2))
											left join mf_checkinout p on ((a.id = p.user_id) AND (a.rentan_tanggal = date(p.checktime)) AND ((p.checktime)::time without time zone >= n.check_out_time1) AND ((p.checktime)::time without time zone <= n.check_out_time2))
											left join v_tidak_hadir_upacara q on (a.id=q.user_id and a.rentan_tanggal=q.tanggal)
											left join ibadah_muslim r on (a.id=r.user_id and a.rentan_tanggal=r.tgl_ibadah)
											group by 1,2,3,4,5,6,7,10,11,12,15,16,17,18,19,20,21,22,25,26,27
							) as b on a.id=b.id
							group by 1
							) as b",'a.id=b.id','left',false);

        	$this->datatables->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip)');
        	$this->datatables->add_column('jum_hari_kerja','$1','jum_hari_kerja_rekap(json_absen,status_pegawai)');
        	$this->datatables->add_column('jum_hadir_kerja_rekap','$1','jum_hadir_kerja_rekap(json_absen,status_pegawai)');
        	$this->datatables->add_column('jum_terlambar_rekap','$1','jum_terlambar_rekap(json_absen,status_pegawai)');
        	$this->datatables->add_column('jum_pulang_cepat_rekap','$1','jum_pulang_cepat_rekap(json_absen,status_pegawai)');
        	$this->datatables->add_column('jum_tk_rekap','$1','jum_tk_rekap(json_absen,status_pegawai)');
        	$this->datatables->add_column('jum_tidak_upacara_rekap','$1','jum_tidak_upacara_rekap(json_absen,status_pegawai)');
        	$this->datatables->add_column('jum_tidak_sholatza_rekap','$1','jum_tidak_sholatza_rekap(json_absen, agama_id, id,status_pegawai)');
        	$this->datatables->add_column('jum_tidak_apel_pagi','$1','jum_tidak_apel_pagi(json_absen, id,status_pegawai)');
        	$this->datatables->add_column('jum_dinas_luar_rekap','$1','jum_dinas_luar_rekap(json_absen,status_pegawai)');
        	$this->datatables->add_column('jum_cuti_rekap','$1','jum_cuti_rekap(json_absen,status_pegawai)');

        	 if ($user_id_in) {
        	 	if (!$tahun || !$bulan) {
			     		$this->datatables->where_in('a.id','0');
			     }else {
			     	$this->datatables->where_in('a.id', $user_id_in);
			     }
		     }else {
		     	 $this->datatables->where_in('a.id','0');
		     }
		     
        return $this->output->set_output($this->datatables->generate());
	}

	public function cetak()
	{
		$this->output->unset_template();
		$dept_id = decrypt_url($this->input->post('instansi'),'instansi');
		

		$this->form_validation->set_rules('instansi', 'nama instansi', 'required')
							  ->set_rules('pegawai[]', 'pegawai', 'required')
							  ->set_rules('tahun', 'tahun', 'required')
							  ->set_rules('bulan', 'bulan', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		if ($this->form_validation->run() == TRUE) {
			$tahun  	= $this->input->post('tahun');
			$bulan  	= $this->input->post('bulan');

			$hari_ini 		= "$tahun-$bulan-01";
 			$rank1 			= date('Y-m-01', strtotime($hari_ini));
 			$rank2 			= date('Y-m-t', strtotime($hari_ini));
			$jum_hari = jumlah_hari_rank($rank1, $rank2);
			if ($jum_hari > 31) {
				echo 'maksimat tanggal yang diizinkan 31 hari';
			}else{
				$user_id  	= $this->input->post('pegawai');
				$user_id_in =array();
				if ($user_id) {
					foreach ($user_id as $r_v ) {
						$user_id_in[] = $r_v;
					}
				}
				$this->data['jum_hari']	= $jum_hari;
				$this->data['rank1'] 	= $rank1;
				$this->data['pegawai_absen'] = $this->m_absen->PegawaiAbsenQueryRekapitulasi($user_id_in, $rank1, $rank2)->result();
				$this->data['priode']		 = tgl_ind_bulan($rank1).' s/d '.tgl_ind_bulan($rank2);
				$this->data['datainstansi']  		= $this->m_pejabat_instansi->GetPajabatByInstansi($dept_id, 7)->row();
				$this->data['datainstansi_kepala']  = $this->m_pejabat_instansi->GetPajabatByInstansi($dept_id, 3)->row();
				$this->load->library('Tpdf');
				$this->load->view('rekap_absen/v_cetak', $this->data);
			}
		}else {
			echo  validation_errors();
		}
	}



}

/* End of file Rekap_kehadiran.php */
/* Location: ./application/modules/report/controllers/Rekap_kehadiran.php */