 <?php
 error_reporting(0);
session_start(); 
$m = date(m);$d = date(d);$y = date(Y);
$aksi="modul/911/aksi_911.php";
$y = date('Y');
$m = date('m');
$d = date('d');
 ?>
 <div class="container-fluid">
			<?php
switch($_GET[act]){
default:
			echo"
			<div class='block-header'>
                <div class='row'>
                    <div class='col-lg-5 col-md-8 col-sm-12'>                        
                        <h2><a href='javascript:void(0);' class='btn btn-xs btn-link btn-toggle-fullwidth'><i class='fa fa-arrow-left'></i></a> Report Maintenance</h2>
                        <ul class='breadcrumb'>
                            <li class='breadcrumb-item'><a href='?p=dashboard'><i class='icon-home'></i></a></li>                            
                            <li class='breadcrumb-item active'>Report</li>
                        </ul>
                    </div>         
                </div>
            </div>
				<div class='col-lg-12 col-md-12'>
                    <div class='card'>
                        <div class='body'>
                           <div class='row'>
						    <div class='col-lg-12 col-md-12'>
							<u><h6>Jadwal Pemeliharaan Mesin</h6></u><br />
							<form method='post' action='?p=report-jadwalmtc&act=list_reportjadwal'>
								<table border='0' width='100%'>
								<tr><td width='20%'>Bulan Pemeliharaan</td><td><input type='date' name='begda' class='form-control' value= '$y-$m-01'></td><td>&nbsp;</td></tr>
									<tr><td width='15%'>Mesin </td><td>
											<select class='form-control show-tick' name='mesin' id='mesin'>
												<option  value='' >---Select Mesin---</option>";
												$r = mysqli_query($conn, "select *from tmesin ");
												while($c = mysqli_fetch_array($r)){
														echo "<option value='$c[namaMesin]'>$c[namaMesin]</option>";
													}
												echo "
											</select>
											</td><td>&nbsp;</td><td width='15%'>&nbsp;</td><td>&nbsp;</td></tr>
								<tr><td width='15%'>Jenis Pekerjaan </td><td>
											<select class='form-control show-tick' name='jen_kerja' id='jen_kerja'>
												<option  value='' >---Select Jenis Pekerjaan---</option>";
												$r = mysqli_query($conn, "select *from mpekerjaan ");
												while($c = mysqli_fetch_array($r)){
														echo "<option value='$c[namaPekerjaan]'>$c[namaPekerjaan]</option>";
													}
												echo "
											</select>
											</td><td>&nbsp;</td><td width='15%'>&nbsp;</td><td>&nbsp;</td></tr>
									<tr><td width='15%'>Teknisi </td><td>
										<select class='default-select2 form-control' name='nm_teknisi'>
											<option value=''>---Select Teknisi---</option>";
											$q = mysqli_query($conn, "select FULLNAME,USERNAME from user where divisi='MAINTENANCE' and active=1 order by fullname asc");
											while($p = mysqli_fetch_array($q)){
													echo "<option value='$p[USERNAME]'>$p[FULLNAME]</option>";
											}
											echo "
										</select>
									</td><td>&nbsp;</td>
									<td width='15%'>&nbsp;</td><td>&nbsp;</td></tr>
									
								</table><br /><br />
								<p align='right'>";
								?>
								<button class="btn btn-success" type="submit"><b>Tampilkan</button>
								<?php
								echo"
							</form>
							
                        </div>
                    </div></div>
							";
	break;
	case "list_reportjadwal":
	echo"
	<div class='block-header'>
                <div class='row'>
                    <div class='col-lg-5 col-md-8 col-sm-12'>                        
                        <h2><a href='javascript:void(0);' class='btn btn-xs btn-link btn-toggle-fullwidth'><i class='fa fa-arrow-left'></i></a> Report Maintenance</h2>
                        <ul class='breadcrumb'>
                            <li class='breadcrumb-item'><a href='?p=dashboard'><i class='icon-home'></i></a></li>                            
                            <li class='breadcrumb-item active'>Report</li>
                        </ul>
                    </div>         
                </div>
            </div>
	<div class='col-lg-12 col-md-12'>
                    <div class='card'>
                        <div class='body'>
                           <div class='row'>
						    <div class='col-lg-12 col-md-12'>
							<div class='table-responsive'>
                                <table class='table table-striped table-hover dataTable js-exportable'>
                                    <thead>
                                        <tr>
											<th align='center' width='10%'>KODE</th>
											<th align='left' width='15%'>MESIN</th>
                                            <th align='center'>JENIS PEKERJAAN</th>
											<th width='10%'>TEKNISI</th>
											<th align='center'>JADWAL PADA</th>
											<th align='center'>DIKERJAKAN PADA</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
									$no=1;
										//$c = mysqli_query($conn, "select *from tpenjadwalan where status_tugas='PLAN' group by kode_tugas");
										$cari = "select *from tpenjadwalan where status_tugas='PLAN' AND MONTH(datetgs) = MONTH('$_POST[begda]')  ";
										if($_POST[nm_teknisi] != NULL){
											$cari .= "AND teknisi = '$_POST[nm_teknisi]'";
										}
										if($_POST[mesin] != NULL){
											$cari .="  AND mesin='$_POST[mesin]'";
										}
										if($_POST[jen_kerja] != NULL){
											$cari .= " AND title_only ='$_POST[jen_kerja]'";
										}
										$cari .= "group by kode_tugas";
											$hasil  = mysqli_query($conn,$cari);
										while($r = mysqli_fetch_array($hasil)){
											$k = mysqli_fetch_array(mysqli_query($conn, "select *from tpenjadwalan 
																							where status_tugas='DIKERJAKAN' and kode_tugas='$r[kode_tugas]'
																							group by kode_tugas"));
											$u = mysqli_fetch_array(mysqli_query($conn, "select *from user where username='$r[teknisi]'"));
											echo "
												<tr>
												<td>$r[kode_tugas]</td>
												<td>$r[mesin]</td>
												<td>$r[title_only]</td>
												<td>$u[fullname]</td>
												<td align='center'>$r[datetgs]</td>
												<td align='center'>$k[datetgs]</td>
												";
											$no++;
										}
										
                              echo"      </tbody>
                                </table>
                            </div>
							
                        </div>
                    </div></div>";
	break;
}
mysqli_close($conn);
?>
<script language='javascript'>
$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
} );
} );
</script>