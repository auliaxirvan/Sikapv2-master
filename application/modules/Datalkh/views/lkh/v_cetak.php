<?php
    $pdf = new Tpdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetFont('times', '', 10, '', false);
    $pdf->setHeaderFont(Array('times', 'sikap', 10));
    $pdf->SetTitle('Laporan kerja harian');
    //$pdf->SetTopMargin(10);
    $pdf->setFooterMargin(20);
    $pdf->SetAutoPageBreak(true, 22);
    $pdf->SetAuthor('Author');
    $pdf->SetDisplayMode('real', 'default');
    $pdf->SetMargins(7, 10, 7);
    
    $pdf->AddPage();
    $pdf->SetFont('times', 'B', 12);
    $pdf->SetY(20);
    $txt = <<<EOD
            LAPORAN KERJA HARIAN
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(30);
    $pdf->SetFont('times', '', 8, '', false);
    $html ='<hr style="height: 2px;">';
    $pdf->writeHTML($html, true, false, true, false, '');

    $html ='<table align="left" width="100%">
                <tr>
                    <td width="8%"><b>Priode</b></td>
                    <td width="2%">:</td>
                    <td>'.$priode.'</td>
                </tr>
            </table><br><br>';
    $pdf->writeHTML($html, true, false, true, false, '');
     // tabel 
    $tbl ='
        
        <table cellpadding="3" border="1" width="100%" >
            <tr align="center"> 
                  <td width="5%" rowspan="2"><br><br><b>No</b></td>
                  <th width="14%" rowspan="2" ><br><br><b>Tanggal</b></th>
                  <th width="18%" rowspan="1" colspan="2"><b>Jam</b></th>
                  <th width="35%" rowspan="2" ><br><br><b>Uraian Kegiatan</b></th>
                  <th width="28%" rowspan="2" ><br><br><b>Hasil</b></th>
            </tr>
            <tr align="center">
                  <th width="9%"><b>Mulai</b></th>
                  <th width="9%"><b>Selesai</b></th>
            </tr>
            <thead cellpadding="2">
                <tr align="center" >
                    <td width="5%">1</td>
                    <td width="14%">2</td>
                    <td width="9%">3</td>
                    <td width="9%">4</td>
                    <td width="35%">5</td>
                    <td width="28%">6</td>
                </tr>
            </thead>';


            $arrayForTable = [];
            foreach ($datalkh->result() as $databaseValue) {
                $temp = [];
                $temp['jam_mulai']      = $databaseValue->jam_mulai;
                $temp['jam_selesai']    = $databaseValue->jam_selesai;
                $temp['kegiatan']       = $databaseValue->kegiatan;
                $temp['hasil']          = $databaseValue->hasil;

                if(!isset($arrayForTable[$databaseValue->tgl_lkh])){
                    $arrayForTable[$databaseValue->tgl_lkh] = [];
                }
                    $arrayForTable[$databaseValue->tgl_lkh][] = $temp;

            }


        $no=1; foreach ($arrayForTable as $id=>$values) :
                    foreach ($values as $key=> $value) :

        $tbl .='<tr>';
                if($key == 0) :

        $tbl .='<td align="center" rowspan="'.count($values).'">'.$no++.'</td>
                <td rowspan="'.count($values).'">'.tglInd_hrtabel($id).'</td>';
              endif;
        $tbl .='
                <td align="center">'.substr($value['jam_mulai'],0,5).'</td> 
                <td align="center">'.substr($value['jam_selesai'],0,5).'</td> 
                <td>'.$value['kegiatan'].'</td> 
                <td>'.$value['hasil'].'</td> 
            </tr>';
                    endforeach;
            endforeach; 

          
    $tbl .='</table><br><br>';
    $pdf->writeHTML($tbl, true, false, true, false, '');
  
    // ttd
     $ttd ='<div align="center">
            <table width="100%" nobr="true">
                <tr>
                    <td width="55%"></td> 
                    <td width="45%"><b>'.$instansi->alamat.', '.tgl_ind_bulan(date('Y-m-d')).'</b><br></td>
                </tr>
                <tr>
                    <td width="50%"><b>Disahkan Oleh:</b></td> 
                    <td width="5%"></td>
                    <td width="45%"><b>Yang Membuat Laporan:</b></td>
                </tr>';
      $ttd .='<tr>
                    <td width="50%">'.$ttd_data->ver_jabatan.'<br><br><br><br></td> 
                    <td width="5%"></td>
                    <td width="45%">'.$ttd_data->jabatan.'<br><br><br><br></td>
              </tr>';

      $ttd .= '<tr>
                    <td width="50%"><b><u>'.nama_gelar($ttd_data->ver_nama, $ttd_data->ver_gelar_dpn, $ttd_data->ver_gelar_blk).'</u>
                            </b><br>
                            <b>'.$ttd_data->ver_pangkat.'</b><br>
                            <b>NIP. '.konversi_nip($ttd_data->ver_nip).'</b>
                    </td> 
                    <td width="5%"></td>
                    <td width="45%"><b><u>'.nama_gelar($ttd_data->nama, $ttd_data->gelar_dpn, $ttd_data->gelar_blk).'</u>
                        </b><br>
                        <b>'.$ttd_data->pangkat.'</b><br>
                        <b>NIP. '.konversi_nip($ttd_data->nip).'</b>
                    </td>
                </tr>';              
    $ttd .='</table>';
    $pdf->writeHTML($ttd, true, false, true, false, '');
    $pdf->Output('LaporanKerjaHarian_'.$ttd_data->nip.'_'.$priode.'.pdf', 'I');

?>