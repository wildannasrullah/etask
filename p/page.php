<?php
error_reporting(1);
session_start(); 
include('../config/koneksi.php');
include('../config/fungsi_indotgl.php');

	//cek apakah user sudah login 
	if(!isset($_SESSION['username'])){ 
	?><script language='javascript'>alert('You are not logged in. Please login first!');
	document.location='index.php'</script><?php
	    //jika belum login jangan lanjut.. 
	}
	$sql2 = mysqli_query($conn,"select *from user where username='$_SESSION[username]'");
	$u2 = mysqli_fetch_array($sql2);
	
	//OTOMATIS INSERT TUGAS MAINTENANCE
	
	$date = date('Y-m-d')." 00:00:00";
	
	date_default_timezone_set('Asia/Jakarta');
	
	$tgs = mysqli_query($conn, "select *from tpenjadwalantemp");
	while($tg = mysqli_fetch_array($tgs)){
		$dateawal = date('Y-m-d H:i:s');
		$days=+$_POST[est_day];
		$hour=+$_POST[est_hour];
		$minute=+$_POST[est_minute];
		$unik=uniqid();
		if($date >= $tg['assign_date']){
			mysqli_query($conn, "INSERT INTO tassign (NO_ASSIGN, NO_PELAPORAN,  PIC_HANDLING, EST_HANDLING, CREATED_BY, CREATED_DATE, EST_DAY, EST_HOUR, EST_MIN) values 		
				('$unik', '$tg[kode_tugas]', '$tg[teknisi]', NOW(), 'auto_assign', NOW(), 0, 0, 0)");
			mysqli_query($conn,"DELETE FROM tpenjadwalantemp WHERE kode_tugas = '$tg[kode_tugas]'");
			mysqli_query($conn,"INSERT INTO thandling VALUES(
										   NULL,
										   '$tg[kode_tugas]',
										   'ASSIGN TO',
										   'auto_assign',
										   NOW(),
										   NULL
										   )");
		}else{}
	}
?>

<!doctype html>
<html lang="en">
<head>
<title>:: E-Task :: <?php echo $u2[divisi]; ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="Lucid Bootstrap 4.1.1 Admin Template">
<meta name="author" content="WrapTheme, design by: ThemeMakker.com">

<link rel="icon" href="../assets/images/icon.jpg" type="image/x-icon">
<link rel="stylesheet" href="assets/css/blog.css">
<link rel="stylesheet" href="assets/css/main.css">
<!-- VENDOR CSS -->
<link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
<link href="../assets/fontawesome-free-5.1.0-web/css/all.css" rel="stylesheet" />

<link rel="stylesheet" href="../assets/vendor/chartist/css/chartist.min.css">
<link rel="stylesheet" href="../assets/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.css">
<link rel="stylesheet" href="../assets/vendor/summernote/dist/summernote.css"/>
<link rel="stylesheet" href="../assets/vendor/dropify/css/dropify.min.css">

<link rel="stylesheet" href="../assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="../assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css">
<link rel="stylesheet" href="../assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css">
<link rel="stylesheet" href="../assets/vendor/sweetalert/sweetalert.css"/>

<link rel="stylesheet" href="../assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css">
<link rel="stylesheet" href="../assets/vendor/parsleyjs/css/parsley.css">
<style>
    td.details-control {
    background: url('../assets/images/details_open.png') no-repeat center center;
    cursor: pointer;
}
    tr.shown td.details-control {
        background: url('../assets/images/details_close.png') no-repeat center center;
    }
    .demo-card label{ display: block; position: relative;}
    .demo-card .col-lg-4{ margin-bottom: 30px;}
</style>

<!-- MAIN CSS -->
<link rel="stylesheet" href="assets/css/main.css">
<link rel="stylesheet" href="assets/css/blog.css">
<link rel="stylesheet" href="assets/css/main.css">
<link rel="stylesheet" href="assets/css/color_skins.css">
</head>
<script type="text/javascript" src="jquery.js"></script>
  <script type="text/javascript">
   function ambilKomentar(){
   $.ajax({
      type: "POST",
      url: "page_aksi.php?aksi=select",
      dataType:'json',
      success: function(response){
       $("#jumlah").text(""+response+"");
       timer = setTimeout("ambilKomentar()",5000);
      }
     });  
  }
  $(document).ready(function(){
   ambilKomentar();
  });
  </script>
<body class="theme-orange">

<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img src="../assets/images/logo34.png" width="30%" alt="KOP"></div>
        <p><h3><font color='white'><i class="fas fa-spinner fa-pulse"></i> Please wait...</font></h3></p>        
    </div>
</div>
<!-- Overlay For Sidebars -->

<div id="wrapper">

    <nav class="navbar navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-btn">
                <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
            </div>

            <div class="navbar-brand">
                <a href="index.html"><img src="../assets/images/logo34.png" alt="KOP" width="100%"></a>                
            </div>
            
            <div class="navbar-right">
               

                <div id="navbar-menu">
                    <ul class="nav navbar-nav">  
					<font color='red'><B>E-Task Versi : V.20.0.8</B></font>
					<?php
						if($_SESSION[level]=='admin' || $_SESSION[level]=='superadmin'){
							
							$s2 = mysqli_query($conn,"select count(idProb) as tot from tproblems where status_problem='OPEN' ");
							$t2 = mysqli_fetch_array($s2);
					?>
						<li class="dropdown">
						<?php
							if($_SESSION[level]=='superadmin'){
						?>
                            <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                                <i class="icon-bell"></i>
								<span class="notification" ><b><?php echo $t2[tot];?></b></span>
                            </a>
						<?php
							}else{
						?>
								<i class="icon-bell"></i>
								<span class="notification" ><b><?php echo $t2[tot];?></b></span>
						<?php
							}
						?>
                            <ul class="dropdown-menu notifications animated">
                            <?php
							$s = mysqli_query($conn,"select *from tproblems where status_problem='OPEN'");
							$t = mysqli_num_rows($s);
							   echo"
							    <li class='header'><span id='jumlah'><strong >You have $t new Notifications</strong></span></li>";
								while($q = mysqli_fetch_array($s)){
                                echo"<li>
                                    <a href='page.php?p=assign'>
                                        <div class='media'>
                                            <div class='media-left'>
                                                <i class='icon-info text-warning'></i>
                                            </div>
                                            <div class='media-body'>
                                                <p class='text'><font color='white'>$q[namapelapor]</font><font color='yellow'> - $q[created_by]</font></p>
                                                <span class='timestamp'><font color='white'>".tgl_indo($q[dateprob])." - $q[timeprob]</font></span>
                                            </div>
                                        </div>
                                    </a>
                                </li>  ";
								}
                               echo" <li class='footer'><a href='page.php?p=assign' class='more'>See all notifications</a></li>
							  ";
							  echo"
                            </ul>
							
                        </li> ";
							
							}else{}
							   ?>
                       <!-- <li><a href="?p=message" class="icon-menu d-none d-sm-block"><i class="icon-bubbles"></i></a></li> -->
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i class="icon-equalizer"></i></a>
                            <ul class="dropdown-menu user-menu menu-icon animated bounceIn">
                                <li class="menu-heading">ACCOUNT SETTINGS</li>
                                <li><a href="?p=profile"><i class="icon-note"></i> <span>My Profile</span></a></li>
                            </ul>
                        </li>
                        <li><a href="logout.php" class="icon-menu" title='Log Out' onclick="return confirm('Are you sure to logout?');"><i class="icon-login"></i></a></li>                        
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div id="left-sidebar" class="sidebar">
        <div class="sidebar-scroll">
            <div class="user-account">
			<?php
				$sql = mysqli_query($conn,"select *from user where username='$_SESSION[username]'");
				$u = mysqli_fetch_array($sql);
				
				if($u[photo]==''){
					echo "<img class='rounded-circle user-photo' alt='User Profile Picture' src='modul/master/users/photo/no_image.jpg'>";
				}
				else{
					echo "<img class='rounded-circle user-photo' alt='User Profile Picture' src='modul/master/users/photo/$u[photo]'>";
				}
			?>
                <div class="dropdown">
                    <span>Welcome, <?php echo $_SESSION[divisi]; ?></span>
                    <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown"><strong><?php echo $u['fullname']; ?></strong></a>                    
                    <ul class="dropdown-menu dropdown-menu-right account animated flipInY">
                        <li><a href="?p=profile"><i class="icon-user"></i><font color='white'><b>My Profile</b></font></a></li>
                        <li class="divider"></li>
                        <li><a href="logout.php" onclick="return confirm('Are you sure to logout?');"><i class="icon-power"></i><font color='white'><b>Logout</b></font></a></li>
                    </ul>
                </div>
                <hr>
            </div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#hr_menu">Menu</a></li>
            </ul>
                
            <!-- Tab panes -->
            <div class="tab-content p-l-0 p-r-0">
                <div class="tab-pane animated fadeIn active" id="hr_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu">
                            <li><a href="?p=dashboard"><i class="icon-home"></i><span>Dashboard</span></a></li>
                           <!-- <li>
                                <a href="#Employees" class="has-arrow"><i class="fa fa-book"></i><span>Log Book</span></a>
                                <ul>
								<?php
								/* $a = mysqli_query($conn,"Select no_pelaporan from tlaporan where status='F' and divisi_pelapor='$_SESSION[divisi]'");
								$b = mysqli_num_rows($a);
								if($b==0){
                                    echo "<li><a href='?p=new-post'><i class='fa fa-edit'></i>Add Problem</a></li>";
								} else {
									echo "<li>"; ?><a href='#'  onclick="alert('Close Your 911 Problem !!!');window.location='?p=new-post&act=problem-list'"><i class='fa fa-edit'></i>Add Problem</a></li><?php
								} */
								?>
                                    <li><a href="?p=new-post&act=problem-list"><i class="fa fa-list-alt"></i> Problem List</a></li>
                                </ul>
                            </li>-->
							<?php
							if($_SESSION[level]=='admin' || $_SESSION[level]=='user_sparepart'){
							?>
							<li>
                                <a href="#Employees" class="has-arrow"><i class="fa fa-tasks"></i><span>List Task</span></a>
                                <ul>
                                    <li><a href="?p=input-problem"><i class="fa fa-edit"></i>Input Problem</a></li>
									<!-- <li><a href="?p=assign"><i class="fa fa-edit"></i>Assign Problem</a></li> -->
									<li><a href="?p=todolist"><i class="fa fa-edit"></i>To Do List</a></li>
									<li><a href="?p=planning"><i class="fa fa-calendar-alt"></i>Scheduling</a></li>
                                </ul>
                            </li>
							
							<!--<li><a href="?p=report"><i class="fa fa-laptop"></i><span>Report</span></a></li>-->
							<li>
								<a href="#Accounts" class="has-arrow"><i class="fa fa-briefcase"></i><span> Report</span></a>
                                <ul>
								<!--	<li><a href="?p=report"><i class="fa fa-user"></i> Report Masalah</a></li> -->
									
									<li><a href="?p=report-masalah"><i class="fa fa-user"></i> Report Masalah</a></li>
									
									<li><a href="?p=report-langkahkerja"><i class="fa fa-users"></i> Report Kerja</a></li>
									<li><a href="?p=report-jadwalmtc">Report Jadwal Pemeliharaan</a></li>
								</ul>
							</li>
							<?php
							}
							else if($_SESSION[level]=='superadmin'){
							?>
							<li>
                                <a href="#Employees" class="has-arrow"><i class="fa fa-tasks"></i><span>List Task</span></a>
                                <ul>
									<li><a href="?p=input-problem"><i class="fa fa-edit"></i>Input Problem</a></li>
									<li><a href="?p=assign"><i class="fa fa-edit"></i>Assign Problem</a></li>
                                    <li><a href="?p=todolist"><i class="fa fa-edit"></i>To Do List</a></li>
									<?php
										$app= mysqli_num_rows(mysqli_query($conn, "select * from mapproval a left join tuserapproval b on a.idApproval=b.idApproval 
																				   where idUser = '$u[iduser]'"));
										if($app > 0){
											?><li><a href="?p=finished"><i class="fa fa-edit"></i>Finished Problem</a></li>
											 <li><a href="?p=pendingproblem"><i class="fa fa-edit"></i>Pending Problem</a></li>
									<?php
										}else{}
									?>
									<li><a href="?p=sparepart"><i class="fa fa-edit"></i>Req. Sparepart</a></li>
									<li><a href="?p=planning"><i class="fa fa-calendar-alt"></i>Scheduling</a></li>
								</ul>
                            </li>
                            <li>
                                <a href="#Accounts" class="has-arrow"><i class="fa fa-briefcase"></i><span>Master</span></a>
                                <ul>
								<?php
								$l = mysqli_fetch_array(mysqli_query($conn,"select *from user where username = '$_SESSION[username]'"));
								if($l[divisi]=='PREPRESS' || $l[divisi]=='PPIC'){
									 ?><li><a href="?p=users"><i class="fa fa-user"></i> Users</a></li>
									<?php
								}
								else if($l[divisi]=='EDP'){
									?><li><a href="?p=users"><i class="fa fa-user"></i> Users</a></li>
                                    <li><a href="?p=masterapproval"><i class="fa fa-users"></i> Approval MTC</a></li>
									<li><a href="?p=mastershift"><i class="fa fa-users"></i> Shift MTC</a></li>
									<li><a href="?p=division"><i class="fa fa-user"></i> Division</a></li>
									<li><a href="?p=masterpending"><i class="fa fa-user"></i> User Pending</a></li>
                                    <li><a href="?p=machines"><i class="fa fa-ellipsis-h"></i>Machines</a></li>
									<li><a href="?p=machinesunit"><i class="fa fa-ellipsis-h"></i>Machines Unit</a></li>
									<li><a href="?p=checkdata"><i class="fa fa-ellipsis-h"></i> Check Data</a></li>
									<?php
								}
								else{
								?>
                                    <li><a href="?p=users"><i class="fa fa-user"></i> Users</a></li>
									<li><a href="?p=mastershift"><i class="fa fa-users"></i> Shift MTC</a></li>
                                    <li><a href="?p=machines"><i class="fa fa-ellipsis-h"></i>Machines</a></li>
									<li><a href="?p=machinesunit"><i class="fa fa-ellipsis-h"></i>Machines Unit</a></li>
									<?php
								}
									?>
                                </ul>
                            </li>
							
							<li>
								<a href="#Accounts" class="has-arrow"><i class="fa fa-briefcase"></i><span> Report</span></a>
                                <ul>
								<!--	<li><a href="?p=report"><i class="fa fa-user"></i> Report Masalah</a></li> -->
									
									<li><a href="?p=report-masalah"><i class="fa fa-file"></i> Report Masalah</a></li>
									<li><a href="?p=report-langkahkerja"><i class="fa fa-file"></i> Report Kerja</a></li>
									<li><a href="?p=report-reqsparepart"><i class="fa fa-file"></i> Report Sparepart</a></li>
									<li><a href="?p=report-jadwalmtc"><i class="fa fa-file"></i> Report Jadwal MTC</a></li>
								</ul>
							</li>
							<?php
							}
							else{
								?>
							<li>
                                <a href="#Employees" class="has-arrow"><i class="fa fa-tasks"></i><span>List Task</span></a>
                                <ul>
									<li><a href="?p=input-problem"><i class="fa fa-edit"></i>Input Problem</a></li>
									<li><a href="?p=planning"><i class="fa fa-calendar-alt"></i>Scheduling</a></li>
                                </ul>
                            </li>
							<!-- <li><a href="?p=report"><i class="fa fa-laptop"></i><span>Report</span></a></li> -->
							
							<?php
							}
							?>
							<!-- <li><a href="modul/manual/manual.php" target="_blank"><i class="fa fa-exclamation-triangle"></i><span>Help</span></a></li> -->

                        </ul>
                    </nav>
                </div>
                
            </div>          
        </div>
    </div>

    <div id="main-content">
        <div class="container-fluid"><br />
		<body background='black'>
		<marquee><font color='red'><b>INFO BARU ! - </b> </font><font color='blue'><b>Untuk TIM MAINTENANCE, </b> Saat ini telah ada fitur <b>Report Permintaan Sparepart di Menu Report - Report Sparepart. Selain itu di Menu Report Masalah, dapat dilakukan filter berdasarkan Kode Masalah yang diinginkan (No. TSK).</b></font></marquee> 
            <?php 
				include('content.php');
			?>
			</body>
        </div>
    </div>
    
</div>

<!-- Javascript -->
<script src="assets/bundles/libscripts.bundle.js"></script>
<script src="assets/bundles/vendorscripts.bundle.js"></script>

<script src="../assets/vendor/toastr/toastr.js"></script>
<script src="assets/bundles/chartist.bundle.js"></script>


<script src="assets/bundles/mainscripts.bundle.js"></script>
<script src="assets/js/index.js"></script>
<script src="../assets/vendor/summernote/dist/summernote.js"></script>
<script src="../assets/vendor/dropify/js/dropify.min.js"></script>
<script src="assets/js/pages/forms/dropify.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script src="assets/bundles/datatablescripts.bundle.js"></script>
<script src="../assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
<script src="../assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
<script src="../assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js"></script>
<script src="../assets/vendor/jquery-datatable/buttons/buttons.html5.min.js"></script>
<script src="../assets/vendor/jquery-datatable/buttons/buttons.print.min.js"></script>
<link rel="stylesheet" href="../assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css">
<link rel="stylesheet" href="../assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="../assets/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css" />
<link rel="stylesheet" href="../assets/vendor/multi-select/css/multi-select.css">
<link rel="stylesheet" href="../assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css">
<link rel="stylesheet" href="../assets/vendor/nouislider/nouislider.min.css" />

<script src="../assets/vendor/sweetalert/sweetalert.min.js"></script> <!-- SweetAlert Plugin Js --> 
<script src="../assets/vendor/parsleyjs/js/parsley.min.js"></script>
<script src="assets/js/pages/tables/jquery-datatable.js"></script>

<script src="../assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script> <!-- Bootstrap Colorpicker Js --> 
<script src="../assets/vendor/jquery-inputmask/jquery.inputmask.bundle.js"></script> <!-- Input Mask Plugin Js --> 
<script src="../assets/vendor/jquery.maskedinput/jquery.maskedinput.min.js"></script>
<script src="../assets/vendor/multi-select/js/jquery.multi-select.js"></script> <!-- Multi Select Plugin Js -->
<script src="../assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script src="../assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="../assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script> <!-- Bootstrap Tags Input Plugin Js --> 
<script src="../assets/vendor/nouislider/nouislider.js"></script> <!-- noUISlider Plugin Js --> 
<script src="assets/js/pages/ui/dialogs.js"></script>    
<script src="assets/js/pages/forms/advanced-form-elements.js"></script>
<script>
    jQuery(document).ready(function() {

        $('.summernote').summernote({
            height: 350, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false, // set focus to editable area after initializing summernote
            popover: { image: [], link: [], air: [] }
        });

        $('.inline-editor').summernote({
            airMode: true
        });

    });

    window.edit = function() {
            $(".click2edit").summernote()
        },
        
    window.save = function() {
        $(".click2edit").summernote('destroy');
    }
</script>
<script>
    $(function() {
        // validation needs name of the element
        $('#food').multiselect();

        // initialize after multiselect
        $('#basic-form').parsley();
    });
    </script>
    <script>
    $("#idmesin").change(function() {
        var mesin = $("#idmesin").val();
        $.ajax({url: "modul/911/get_unit.php?kode="+mesin,
					  "dataType": "json",
					   success: function(result){
                        var op = new Option("", "");
                        $(op).html("---select Unit---");
                        $(op).prop('disabled', true);
                        $(op).prop('selected', true);
                        $("#unit").html(op);
                        for(var i=0 ; i < result.length; i++)
                        {
                            op = new Option(result[i].namaUnit, result[i].idUnit);
                            $(op).html(result[i].namaUnit);
                            $("#unit").append(op);
                        }
                       }});
    });
    </script>
</body>
</html>
