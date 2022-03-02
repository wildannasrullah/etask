<?php
 // Sisipkan File Koneksi
error_reporting(0);
session_start(); 
include('../config/koneksi.php');
include('../config/fungsi_indotgl.php');
 // Ambil Aksi
 $act = $_GET['aksi'];
 // Jikan Aksi = Input
 switch($act){
 case "input":
 
 break; 
 // Jika Tidak Ada Aksi = Select
 case "select":
   $sql = mysql_query("SELECT * FROM tlaporan WHERE status='O'");
   $array = mysql_num_rows($sql);
   echo json_encode($array);
 break;
}
?>