<?php require_once('Connections/koneksi.php'); ?>
<?php require_once('akses.php'); ?>
<?php $total=0; ?>
<?php 
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tbl_mhs (nama, npm, kelas, jk, telp, email, alamat, point, kali, jumlah, admin) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['nama'], "text"),
                       GetSQLValueString($_POST['npm'], "text"),
                       GetSQLValueString($_POST['kelas'], "text"),
                       GetSQLValueString($_POST['jk'], "text"),
                       GetSQLValueString($_POST['telp'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['alamat'], "text"),
					   GetSQLValueString($_POST['point'], "int"),
					   GetSQLValueString($_POST['kali'], "int"),
					   GetSQLValueString(($_POST['point']*$_POST['kali']), "int"),
					   GetSQLValueString($_POST['admin'], "text"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($insertSQL, $koneksi) or die(mysql_error());

  if($insertSQL) {
	  echo "<script type='text/javascript'>alert('Data Berhasil Disimpan!');location.href=\"alldata.php?farida=\";</script>";
	}
}

$maxRows_mahasiswa = 20;
$pageNum_mahasiswa = 0;
if (isset($_GET['pageNum_mahasiswa'])) {
  $pageNum_mahasiswa = $_GET['pageNum_mahasiswa'];
}
$startRow_mahasiswa = $pageNum_mahasiswa * $maxRows_mahasiswa;

mysql_select_db($database_koneksi, $koneksi);
$query_mahasiswa = "SELECT * FROM tbl_mhs ORDER BY kelas ASC";
$query_limit_mahasiswa = sprintf("%s LIMIT %d, %d", $query_mahasiswa, $startRow_mahasiswa, $maxRows_mahasiswa);
$mahasiswa = mysql_query($query_limit_mahasiswa, $koneksi) or die(mysql_error());
$row_mahasiswa = mysql_fetch_assoc($mahasiswa);

if (isset($_GET['totalRows_mahasiswa'])) {
  $totalRows_mahasiswa = $_GET['totalRows_mahasiswa'];
} else {
  $all_mahasiswa = mysql_query($query_mahasiswa);
  $totalRows_mahasiswa = mysql_num_rows($all_mahasiswa);
}

$totalPages_mahasiswa = ceil($totalRows_mahasiswa/$maxRows_mahasiswa)-1;

mysql_select_db($database_koneksi, $koneksi);
$query_kelas = "SELECT * FROM tbl_kls";
$kelas = mysql_query($query_kelas, $koneksi) or die(mysql_error());
$row_kelas = mysql_fetch_assoc($kelas);
$totalRows_kelas = mysql_num_rows($kelas);

$maxRows_cri = 10;
$pageNum_cri = 0;
if (isset($_GET['pageNum_cri'])) {
  $pageNum_cri = $_GET['pageNum_cri'];
}
$startRow_cri = $pageNum_cri * $maxRows_cri;

$colname_cri = "-1";
if (isset($_GET['carinama'])) {
  $colname_cri = '%' . $_GET['carinama'] . '%';
}
mysql_select_db($database_koneksi, $koneksi);
$query_cri = sprintf("SELECT * FROM tbl_mhs WHERE nama LIKE %s ORDER BY nama ASC", GetSQLValueString($colname_cri, "text"));
$query_limit_cri = sprintf("%s LIMIT %d, %d", $query_cri, $startRow_cri, $maxRows_cri);
$cri = mysql_query($query_limit_cri, $koneksi) or die(mysql_error());
$row_cri = mysql_fetch_assoc($cri);

if (isset($_GET['totalRows_cri'])) {
  $totalRows_cri = $_GET['totalRows_cri'];
} else {
  $all_cri = mysql_query($query_cri);
  $totalRows_cri = mysql_num_rows($all_cri);
}
$totalPages_cri = ceil($totalRows_cri/$maxRows_cri)-1;

mysql_select_db($database_koneksi, $koneksi);
$query_mahasiswa = "SELECT * FROM tbl_mhs ORDER BY kelas ASC";
$mahasiswa = mysql_query($query_mahasiswa, $koneksi) or die(mysql_error());
$row_mahasiswa = mysql_fetch_assoc($mahasiswa);
$totalRows_mahasiswa = mysql_num_rows($mahasiswa);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aplikasi Data Mahasiswa</title>
<script src="js/SpryValidationTextField.js" type="text/javascript"></script>
<script src="js/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="js/SpryValidationSelect.js" type="text/javascript"></script>
<link href="js/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="js/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="js/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<style type="text/css">
a:link {
	color: #000;
}
a:visited {
	color: #000;
}
a:hover {
	color: #000;
}
a:active {
	color: #000;
}
body,td,th {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	color: #033;
}
body {
	background-color: #CCC;
	
}
</style>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="css/custom.css" rel="stylesheet" type="text/css" />
<link href="css/font-awesome.css" rel="stylesheet" type="text/css" />
</head>

<body>

  <div id="wrapper">
         <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="adjust-nav">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand">
                    
    <script>
	var tod = new Date();
	var weekday = new
	Array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
	var monthname = new
	Array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
	var y = tod.getFullYear();
	var m = tod.getMonth();
	var d = tod.getDate();
	var dow = tod.getDay();
	var dispTime = " "+ weekday[dow]+","+d+" "+monthname[m]+" "+y+" ";
	
	if (dow==0) dispTime="<font color=black>" + dispTime +"</font>"; 
	else if (dow===5)
	dispTime="<font color=green>"+dispTime+"</font>";
	
	else dispTime="<font color=black>"+dispTime+"</font>";
	document.write(dispTime);
	</script>   
    
    
<script type="text/javascript">
// 1 detik = 1000

window.setTimeout("waktu()",1000);
function waktu() {
	var tanggal = new Date();
	
	setTimeout("waktu()",1000);
	document.getElementById("output").innerHTML=
	tanggal.getHours()+" : "+tanggal.getMinutes()+" : "+tanggal.getSeconds();
}
</script>
<style type="text/css">
.tulis {
	font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
	font-size: 14px;
	color: #000000;
	
}
</style>

<p>
<body class="tulis">
<body bgcolor="#000000" text="#000000" onload="waktu()">
<div id="output">
</div>
                        
                    </a>
                </div>
              
                 <span class="logout-spn" >
                  <a href="logout.php" style="color:#fff;">LOGOUT</a>  

                </span>
            </div>
        </div>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                 

 <li >
                        <a href="home.php" ><i class="fa fa-desktop "></i>HOME <span class="badge"></span></a>
                    </li>
                    <li>
                        <a href="cari.php?id="><i class="fa fa-table "></i>SEARCH  <span class="badge"></span></a>
                    </li>
                    <li>
                        <a href="tambah.php?id="><i class="fa fa-edit "></i>INSERT  <span class="badge"></span></a>
                    </li>
  
                    <li>
                        <a href="print.php?id="><i class="fa fa-table "></i>REPORT</a>
                    </li>
                    
                     <li>
                     <li class="active-link">
                        <a href="alldata.php?id="><i class="fa fa-bar-chart-o"></i>ALL DATA</a>
                    </li>
                </ul>
                            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2>DATA MAHASISWA</h2>   
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
                  
                  

<h1 align="center"><h1 align="center">&nbsp;</h1>
<div class="table-container">
<table width="100%" border="1">
  <tr align = "center">
    <td width="40" height="30" bgcolor="#006699"><a href="alldata.php?farida=<?php echo $row_mahasiswa['id_mhs']; ?>">No</a></td>
    <td width="100" height="30" bgcolor="#006699"><a href="home_nama.php?farida=">Nama</a></td>
    <td width="110" height="30" bgcolor="#006699"><a href="home_npm.php?farida=">NPM</a></td>
    <td width="115" height="30" bgcolor="#006699"><a href="home_kelas.php?farida=">Kelas</a></td>
    <td width="96" height="30" bgcolor="#006699"><a href="home_jk.php?farida=">Jenis Kelamin</a></td>
    <td width="106" height="30" bgcolor="#006699">Telpon</td>
    <td width="100" height="30" bgcolor="#006699">Email</td>
    <td width="150" height="30" bgcolor="#006699">Alamat</td>
    <td width="100" bgcolor="#006699">Jumlah Point</td>
    <td height="30" colspan="2" bgcolor="#006699">Action</td>
  </tr>
  <?php $no=1; do { ?>
    <tr align = "center">
      <td bgcolor="#FFFFFF"><?php echo $no; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_mahasiswa['nama']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_mahasiswa['npm']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_mahasiswa['kelas']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_mahasiswa['jk']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_mahasiswa['telp']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_mahasiswa['email']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_mahasiswa['alamat']; ?></td>
      <td bgcolor="#FFFFFF"><div align="right"><?php echo 'Rp. ' . number_format($row_mahasiswa['jumlah'], 0, ',', '.') .',-'; ?></div></td>
      <td width="32" bgcolor="#FFFFFF"><a href="edit.php?farida=<?php echo $row_mahasiswa['id_mhs']; ?>">Edit</a></td>
      <td width="43" bgcolor="#FFFFFF"><a href="hapus.php?farida=<?php echo $row_mahasiswa['id_mhs']; ?>" onClick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Hapus</a></td>
    </tr>
    <?php $total ?>
    <?php $no++; $total += ($row_mahasiswa['jumlah']); } while ($row_mahasiswa = mysql_fetch_assoc($mahasiswa)); ?>
  <tr align="center">
      <td colspan="8" align="right"><strong>TOTAL POINT KESELURUHAN</strong></td>
    <td><div align="right"><?php echo 'Rp. ' . number_format($total, 0, ',', '.') .',-'; ?></div></td>
      <td colspan="2">&nbsp;</td>
    </tr>
</table>
</div>
              
                 <!-- /. ROW  -->           
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
    <div class="footer">
      
    
             <div class="row">
                <div class="col-lg-12" >
                    &copy;  2018 faridanorlita8@gmail.com | UNISKA Banjarmasin
                </div>
        </div>
        </div>
          
</body>
</html>
<?php
mysql_free_result($mahasiswa);

mysql_free_result($kelas);

mysql_free_result($cri);
?>
