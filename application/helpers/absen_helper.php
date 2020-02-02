<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

	function cek_radio_disable($id='', $eselon='', $eselon_id='',$type='',$hadir='',$absenupacara_id='')
	{
		$a = '';
		$user_id = encrypt_url($id,'user_id_upacara');
		$tag = "<input type='radio' disabled>";
		$tag_hadir 	= '';
		$tag_user 	= '';
		$tag_absenupacara_id ='';
		
		$checked = '';
		$type_id = '';
		$name_checked = '';
		$checked = '';
		$hadir_id =0;
		
		if ($eselon !=0) {
			$data_eselon_ex = explode("+",$eselon);
			foreach ($data_eselon_ex as $value) {
				  if ($value == $eselon_id) {
				  			$a = 1;
				  }
				  if ($value == 11 && $eselon_id == '') {
				  			$a = 1;
				  }
			}
		}

		if ($type == "yahadir") {
			if ($hadir == 1) {
				$checked = 'checked';
			}

			if ($hadir) {
				$hadir_id = 1;
				$absenupacara_id = encrypt_url($absenupacara_id,'absenupacara_id');
				$tag_absenupacara_id = "<input type='hidden' name='absenupacara_id[$id]' value='$absenupacara_id'>";
			}else {
				$checked = 'checked';
			}

			$tag_hadir = "<input type='hidden' name='hadir[$id]' value='$hadir_id'> $tag_absenupacara_id";
			$type_id = encrypt_url(1,'type_upacara');
			$name_checked = "name='absen[$id]' value='$type_id' $checked";
			$tag_user = "<input type='hidden' name='user[]' value='$user_id'>";
		}else if ($type == "thadir") {
			if ($hadir == 2) {
				$checked = 'checked';
			}
			$type_id = encrypt_url(2,'type_upacara');
			$name_checked = "name='absen[$id]' value='$type_id' $checked";
		}else if ($type == "cuti") {
			if ($hadir == 3) {
				$checked = 'checked';
			}
			$type_id = encrypt_url(3,'type_upacara');
			$name_checked = "name='absen[$id]' value='$type_id' $checked";
		}

		if ($a) {
			$tag = "$tag_user <input type='radio' $name_checked > $tag_hadir";
		}
		
		return $tag;
	}

	function Cek_upacara_hadir($id='',$hadir='')
	{
		$a ='';
		if ($id == $hadir) {
			$a = '<span><i class="icon-checkmark-circle2"></i></span>';	
		}

		return $a;
	}

	function upacara_ket($ket='')
	{
		$a = '';
		if ($ket == 1) {
			$a = "H";
		}elseif ($ket == 2) {
			$a = "A";;
		}elseif ($ket == 3) {
			$a = "C";
		}

		return $a;
	}

	function action_upacara_id($id='', $eselon='', $eselon_id='',$hadir='')
	{
		$a = '';
		$tag_del = '';
		$id = encrypt_url($id,'absenupacara_id');
		
		if ($eselon !=0) {
			$data_eselon_ex = explode("+",$eselon);
			foreach ($data_eselon_ex as $value) {
				  if ($value == $eselon_id) {
				  			$a = 1;
				  }
				  if ($value == 11 && $eselon_id == '') {
				  			$a = 1;
				  }
			}
		}

		if ($a && $hadir) {
			$tag_del = '<span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus data" style="cursor:pointer;" id="'.$id.'">
					              <i class="icon-bin"></i>
					              </span>';
		}

		
		
		return $tag_del;
	}

	function start_time_tabel($start_time='',$start_time_shift='', $start_time_notfixed='')
	{
		 if ($start_time && !$start_time_shift && $start_time != "00:00:00") {
		 			$resul = jm($start_time);
		 }elseif (!$start_time && $start_time_shift) {
		 			$resul = jm($start_time_shift);
		 }elseif ($start_time && $start_time_shift) {
		 			$resul = jm($start_time);
		 }elseif ($start_time == "00:00:00" || $start_time_shift == "00:00:00") {
		 			$resul = 'Libur';
		 }else{
		 		$resul = '<span tooltip="Tidak ada jadwal" flow="left"><i class="icon-help msclick"></i></span>';
		 }

		 if ($start_time_notfixed) {
		 		$resul = jm($start_time).'F';
		 }elseif ($start_time_notfixed == "00:00:00") {
		 		$resul = 'Libur';
		 }

		 return $resul;
	}

	function jam_masuk_tabel($jam_masuk='', $jam_masuk_shift='', $status_in='', $start_time_notfixed='', $jam_masuk_notfixed='')
	{
		$resul ='';
		if ($jam_masuk && !$jam_masuk_shift) {
				$resul = jm($jam_masuk);
		}elseif (!$jam_masuk && $jam_masuk_shift) {
				$resul = jm($jam_masuk_shift);
		}else {
				$resul = jm($jam_masuk);
		}

		if ($start_time_notfixed) {
				$resul = jm($jam_masuk_notfixed);
		}

		if ($status_in) {
				if ($status_in == 1) {
						$resul = 'HM';
				}elseif ($status_in == 3) {
						$resul = 'TKM';
				}else {
					$resul = 'HM';
				}
		}

		return $resul;
	}

	function jam_pulang_tabel($jam_masuk='', $jam_masuk_shift='', $status_out='',$end_time_notfixed='', $jam_pulang_notfixed='')
	{
		if ($jam_masuk && !$jam_masuk_shift) {
				$resul = jm($jam_masuk);
		}elseif (!$jam_masuk && $jam_masuk_shift) {
				$resul = jm($jam_masuk_shift);
		}else {
				$resul = jm($jam_masuk);
		}

		if ($end_time_notfixed) {
				$resul = jm($jam_pulang_notfixed);
		}

		if ($status_out) {
				if ($status_out == 1) {
						$resul = 'HM';
				}elseif ($status_out == 3) {
						$resul = 'TKM';
				}else {
					$resul = 'HM';
				}
		}

		return $resul;
	}

	function terlambat_tabel($start_time='',$start_time_shift='', $jam_masuk='', $jam_masuk_shift='', $status_in='',$start_time_notfixed='', $jam_masuk_notfixed='')
	{
		 $terlambat = '';
		 if ($start_time && $jam_masuk) {
		 		if(jm($jam_masuk) > jm($start_time)) {
                    $j_masuk = strtotime($jam_masuk);
                    $r_masuk = strtotime($start_time);
                    $terlambat = sisa_waktu($j_masuk-$r_masuk);
                }else{
                    $terlambat = '';
                }
		 }

		 if ($start_time_shift && $jam_masuk_shift) {
		 		if(jm($jam_masuk_shift) > jm($start_time_shift)) {
                    $j_masuk = strtotime($jam_masuk_shift);
                    $r_masuk = strtotime($start_time_shift);
                    $terlambat = sisa_waktu($j_masuk-$r_masuk);
                }else{
                    $terlambat = '';
                }
		 }

		 if ($start_time_notfixed && $jam_masuk_notfixed) {
		 		if(jm($jam_masuk_notfixed) > jm($start_time_notfixed)) {
                    $j_masuk = strtotime($jam_masuk_notfixed);
                    $r_masuk = strtotime($start_time_notfixed);
                    $terlambat = sisa_waktu($j_masuk-$r_masuk);
                }else{
                    $terlambat = '';
                }
		 }

		 if ($status_in == 2) {
		 		$terlambat = 'TMM';
		 }

		 return $terlambat;
	}

	 function pulang_cepat_tabel($end_time='',$end_time_shift='', $jam_pulang='', $jam_pulang_shift='', $status_out='',$end_time_notfixed='', $jam_pulang_notfixed='')
	{
		$cepat = '';
		if ($end_time && $jam_pulang) {
			if(jm($jam_pulang) < jm($end_time)) {
	              $j_pulang = strtotime($jam_pulang);
	              $r_pulang = strtotime($end_time);
	              $cepat = sisa_waktu($r_pulang-$j_pulang);
	          }else{
	              $cepat = '';
	          }
	     }

	     if ($end_time_shift && $jam_pulang_shift) {
			if(jm($jam_pulang_shift) < jm($end_time_shift)) {
	              $j_pulang = strtotime($jam_pulang_shift);
	              $r_pulang = strtotime($end_time_shift);
	              $cepat = sisa_waktu($r_pulang-$j_pulang);
	          }else{
	              $cepat = '';
	          }
	     }

	     if ($end_time_notfixed && $jam_pulang_notfixed) {
		 		if(jm($jam_pulang_notfixed) < jm($end_time_notfixed)) {
	                  $j_pulang = strtotime($jam_pulang_notfixed);
		              $r_pulang = strtotime($end_time_notfixed);
		              $cepat 	= sisa_waktu($r_pulang-$j_pulang);
                }else{
                    $terlambat = '';
                }
		 }

	     if ($status_out == 2) {
		 		$terlambat = 'PCM';
		 }

	      return $cepat;
	}

	function dinas_luar_tabel($lkhdl_id='', $dinasmanual_id='')
	{
		$dl = '';
		if ($lkhdl_id) {
				$dl = 'DL';
		}elseif ($dinasmanual_id) {
				$dl = 'DLM';
		}

		return $dl;
	}

	function absen_ket_tabel($daysoff_id='', $jam_masuk='', $jam_pulang='',$jam_masuk_shift='', $jam_pulang_shift='', $lkhdl_id='', $dinasmanual_id='', $kode_cuti='', $rentan_tanggal='', $start_time='', $start_time_shift='', $status_in='', $status_out='',$end_time ='',$end_time_shift='', $start_time_notfixed='', $jam_masuk_notfixed='', $end_time_notfixed='', $jam_pulang_notfixed='')
	{
		$ket ='';
		$hari_ini = date('Y-m-d');

		if ($rentan_tanggal <= $hari_ini && !$daysoff_id && !$jam_masuk && !$jam_pulang && !$jam_masuk_shift && !$jam_pulang_shift && !$lkhdl_id && !$dinasmanual_id && !$kode_cuti ) {
			$ket = 'TK'; 
		}

		if ($jam_masuk || $jam_pulang || $jam_masuk_shift || $jam_pulang_shift || $jam_masuk_notfixed || $jam_pulang_notfixed) {
			 $ket = 'H'; 
		}
		if ($lkhdl_id) {
			 $ket = 'DL'; 
		}

		if ($dinasmanual_id) {
			 $ket = 'DLM'; 
		}

		if ($kode_cuti) {
			 $ket = $kode_cuti; 
		}

		if ($start_time == "00:00:00"  || $start_time_shift == "00:00:00") {
			$ket = 'L';
		}

		if (!$start_time  && !$start_time_shift) {
			$ket = '?';
		}

		if ($status_in) {
				if ($status_in == 1) {
						$ket = 'HM';
				}elseif ($status_in == 2) {
						$ket = 'TMM';
				}elseif ($status_in == 3) {
						$ket = 'TKM';
				}else {
					$ket = 'HM';
				}
		}

		if ($status_out) {
				if ($status_out == 1) {
						$ket = 'HM';
				}elseif ($status_out == 2) {
						$ket = 'PCM';
				}elseif ($status_out == 3) {
						$ket = 'TKM';
				}else {
					$ket = 'HM';
				}
		}

		$terlambat =  terlambat_tabel($start_time,$start_time_shift, $jam_masuk, $jam_masuk_shift, $start_time_notfixed, $jam_masuk_notfixed);

		if ($terlambat) {
			$ket = 'TM';
		}

		$pulang_cepat = pulang_cepat_tabel($end_time,$end_time_shift, $jam_pulang, $jam_pulang_shift,$end_time_notfixed, $jam_pulang_notfixed);

		if ($pulang_cepat) {
			$ket = 'PC';
		}

		if ($terlambat && $pulang_cepat) {
			$ket = 'TC';
		}

		if ($daysoff_id) {
			 $ket = 'L'; 
		}

		return $ket;
	}

	function jumlah_lembur($jam_masuk='', $jam_pulang='', $start_time='', $end_time='', $daysoff_id='',$start_time_shift='', $end_time_shift='')
	{
		$jm_masuk  = strtotime($jam_masuk);
		$jm_pulang = strtotime($jam_pulang);

		if ($start_time_shift != "00:00:00" && !$daysoff_id && $start_time_shift) {
			$jm_masuk  = strtotime($end_time_shift);
		}

		if ($start_time != "00:00:00" && !$daysoff_id && $start_time) {
			$jm_masuk  = strtotime($end_time);
		}

		$jumlah = $jm_pulang-$jm_masuk;

		$sisa = sisa_waktu_lembur($jumlah);

		if ($jm_masuk >  $jm_pulang) {
			$sisa = '0m';
		}

		return $sisa;
	}

	function sisa_waktu_lembur($waktu='')
    {
      // $awal  = strtotime('2017-08-10 10:05:25');
      // $akhir = strtotime('2017-08-11 11:07:33');
      // $diff  = $akhir - $awal;

      $jam   = floor($waktu / (60 * 60));
      $menit = $waktu - $jam * (60 * 60);
      if ($jam==0) {
          return  floor( $menit / 60 ) . 'm';
        }else {
           return  $jam .  ':' . floor( $menit / 60 ) . 'm';
        }
     
    }

