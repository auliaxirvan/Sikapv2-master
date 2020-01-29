<?php
    $pdf = new Tpdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetFont('arial', '', 10, '', false);
    $pdf->setHeaderFont(Array('arial', 'sikap', 10));
    $pdf->SetTitle('Laporan kerja harian');
    //$pdf->SetTopMargin(10);
    $pdf->setFooterMargin(20);
    $pdf->SetAutoPageBreak(true, 22);
    $pdf->SetAuthor('Author');
    $pdf->SetDisplayMode('real', 'default');
    $pdf->SetMargins(7, 10, 7);
    
    $pdf->AddPage('L','A4');
    $pdf->SetFont('arial', '', 12);
    $pdf->SetY(15);
    $txt = <<<EOD
            LAPORAN KEHADIRAN PEGAWAI PER PERIODE
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(25);
    $pdf->SetFont('arial', '', 6, '', false);
    $html ='<hr style="height: 2px;">';
    $pdf->writeHTML($html, true, false, true, false, '');

    $html ='<table align="left" width="100%">
                <tr>
                    <td width="4%"><b>INSTANSI</b></td>
                    <td width="2%">:</td>
                    <td>'.$datainstansi->dept_name.'</td>
                </tr>
                <tr>
                    <td width="4%"><b>PERIODE</b></td>
                    <td width="2%">:</td>
                    <td>'.$priode.'</td>
                </tr>
            </table><br><br>';
    $pdf->writeHTML($html, true, false, true, false, '');
     // tabel 
    $tbl ='  
        <table cellpadding="2.5" border="1" width="100%" >
           <thead>
            <tr align="center"> 
                  <td width="2.5%" rowspan="2"><br><br><b>No</b></td>
                  <th width="15%" rowspan="2" ><br><br><b>Nama(NIP)</b></th>';
    for ($i=0; $i < 31; $i++) { 
          $tbl .='<th width="2.657%"><b>'.tanggal_format(tgl_plus($rank1, $i),'d').'/'.tanggal_format(tgl_plus($rank1, $i),'m').'</b></th>';
    }
     $tbl .='</tr>
     <tr align="center">';
            for ($i=0; $i < 31; $i++) { 
                  $tbl .='<th width="2.657%"><b>'.substr(hari_tgl(tgl_plus($rank1, $i)), 0,1).'</b></th>';
            }
        $tbl .='</tr>
            </thead>';
        
        $tbl .='<tr nobr="true">
                <td width="2.5%" align="center">1</td> 
                <td width="15%"><b>Fauzan Helmy Hutasuhut, AP, S.Sos, MAP (12312324324234)</b></td>';
        for ($i=0; $i < 31; $i++) {  
         $tbl .='<td width="2.657%">TL<br>07:16<br>16:16</td>'; 
            }      
     $tbl .='</tr>';

          
    $tbl .='</table><br><br>';
    $pdf->writeHTML($tbl, true, false, true, false, '');

    $ttd ='<div align="center">
            <table width="100%">
                <tr nobr="true">
                    <td width="70%" align="left"><b>Ket :</b>  <br>- H : Hadir Normal - TM : Telat Masuk - PC : Pulang Cepat - TC : Telat Masuk Pulang Cepat - C* : Cuti - DL : Dinas Luar
                    <br>- TK : Tanpa Ketetangan
                    <br>- L : Hari Libur Kerja

                                    
                    </td> 
                    <td width="30%"><b>'.$datainstansi->kecamatan.', '.tgl_ind_bulan(date('Y-m-d')).'</b><br>
                        '.$datainstansi->jabatan.'
                        <br><br><br><br>
                        <br><b><u>'.nama_gelar($datainstansi->nama, $datainstansi->gelar_dpn, $datainstansi->gelar_blk).'</u></b>
                        <br><b>'.$datainstansi->pangkat.'</b>
                        <br><b>NIP. '.konversi_nip($datainstansi->nip).'</b>
                        </td>
                </tr>';
              
                  
    $ttd .='</table>';
    $pdf->writeHTML($ttd, true, false, false, false, '');
  

    //$pdf->Output('LaporanAbsensi_'.$priode.'.pdf', 'I');
     $pdfString = $pdf->Output('', 'S');
     $pdfBase64 = base64_encode($pdfString);
?>
<html>
<body style="margin:0!important">
    <embed width="100%" height="100%" src="data:application/pdf;base64,<?php echo $pdfBase64 ?>" type="application/pdf" />
</body>
</html>