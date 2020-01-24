<?php
    $pdf = new Tpdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetFont('times', '', 10, '', false);
    $pdf->setHeaderFont(Array('times', 'sikap', 10));
    $pdf->SetTitle('Laporan Data Pimpinan');
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
            LAPORAN DATA KEPALA OPD
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(30);
    $pdf->SetFont('times', '', 8, '', false);
    $html ='<hr style="height: 2px;">';
    $pdf->writeHTML($html, true, false, true, false, '');

    $html ='<table align="left" width="100%">
                <tr>
                    <td width="8%"><b>INSTANSI</b></td>
                    <td width="2%">:</td>
                    <td>'.$instansi->dept_name.'</td>
                </tr>
            </table><br><br>';

    $html .='<table cellpadding="3" border="1" width="100%">
                <tr align="center"> 
                      <td width="4%" ><b>No</b></td>
                      <th width="20%"><b>NAMA</b></th>
                      <th width="15%"><b>NIP</b></th>
                      <th width="30%"><b>JABATAN</b></th>
                      <th width="31%"><b>INSTANSI</b></th>
                </tr>';
    $no=1;
          foreach ($user as $row) {
                $html .='<tr nobr="true">
                        <td align="center">'.$no++.'</td> 
                        <td>'.name_degree(_name($row->nama),$row->gelar_dpn,$row->gelar_blk).'</td> 
                        <td>'.$row->nip.'</td>
                        <td>'.$row->jabatan.'</td>  
                        <td>'.strtoupper($row->dept_name).'</td> 
                        
                    </tr>';
        }
    $html .='</table><br><br>';
  
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('Laporan_DataPengguna_'.$instansi->dept_name.'.pdf', 'I');

?>