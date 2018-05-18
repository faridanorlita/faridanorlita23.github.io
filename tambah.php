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
$query_mahasiswa = "SELECT * FROM tbl_mhs ORDER BY id_mhs DESC";
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
$query_mahasiswa = "SELECT * FROM tbl_mhs ORDER BY id_mhs DESC";
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
 					
                        <a href="home.php" ><i class="fa fa-desktop"></i>HOME<span class="badge"></span></a>
                    </li>
                    <li>
                        <a href="cari.php?id="><i class="fa fa-table"></i>SEARCH <span class="badge"></span></a>
                    </li>
                    
                    <li>
                    <li class="active-link">
                    <a href="tambah.php?id="><i class="fa fa-edit"></i>INSERT <span class="badge"></span></a>
                        
                    </li>  
                    <li>
                        <a href="print.php?id="><i class="fa fa-table"></i>REPORT</a>
                    </li>
                    
                     <li>
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
                     <h2>TAMBAH DATA MAHASISWA</h2>   
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
                  
                 <h1 align="center"><h1 align="center">&nbsp;</h1>
               <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td width="86" align="right" nowrap="nowrap">Nama :</td>
      <td width="543"><span id="sprytextfield1">
      <input type="text" name="nama" value="" size="32" />
      <span class="textfieldRequiredMsg">Nama Harus Diisi!</span><span class="textfieldInvalidFormatMsg">Format Salah!</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">NPM  :</td>
      <td><span id="sprytextfield2">
      <input type="text" name="npm" value="" size="32" />
      <span class="textfieldRequiredMsg">NPM Harus Diisi!</span><span class="textfieldInvalidFormatMsg">NPM Harus Angka!</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Kelas:</td>
      <td><select name="kelas">
        <?php 
do {  
?>
        <option value="<?php echo $row_kelas['kelas']?>" ><?php echo $row_kelas['kelas']?></option>
        <?php
} while ($row_kelas = mysql_fetch_assoc($kelas));
?>
    </select>        <span id="spryselect1"><span id="spryselect2"></tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Jenis Kelamin:</td>
      <td valign="baseline"><table>
        <tr>
          <td width="104">
          <input name="jk" type="radio" value="Laki-Laki" checked="checked" />
            Laki-Laki</td>
          <td width="106"><input type="radio" name="jk" value="Perempuan" />
            Perempuan
            </td>
        </tr>
      </table></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Telepon:</td>
      <td><span id="sprytextfield3">
      <input type="text" name="telp" value="" size="32" />
      <span class="textfieldRequiredMsg">Telepon Harus Diisi!</span><span class="textfieldInvalidFormatMsg">Telepon Harus Angka!</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Email:</td>
      <td><span id="sprytextfield4">
      <input type="text" name="email" value="" size="32" />
      <span class="textfieldRequiredMsg">Email Harus Diisi!</span><span class="textfieldInvalidFormatMsg">Format Salah!</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Alamat:</td>
      <td><span id="sprytextarea1">
        <textarea name="alamat" cols="35" rows="5"></textarea>
      <span class="textareaRequiredMsg">Alamat Harus Diisi!</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top"><label for="point">Point :</label></td>
      <td><span id="sprytextfield5">
      <input type="text" name="point" value="" size="32" />
      <span class="textfieldRequiredMsg">Point Harus Diisi!</span><span class="textfieldInvalidFormatMsg">Format Harus Angka!</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top"><label for="kali">Perkalian Point :</label></td>
      <td><span id="sprytextfield6">
      <input type="text" name="kali" value="" size="32" />
      <span class="textfieldRequiredMsg">Perkalian Point Harus Diisi!</span><span class="textfieldInvalidFormatMsg">Format Harus Angka!</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Simpan Data" />
      <input type="reset" name="reset" id="reset" value="Batal" /></td>
    </tr>
  </table>
  <input type="hidden" name="jumlah" value="" />
  <input type="hidden" name="admin" value="<?php echo $_SESSION['nm_admin']; ?>" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>   

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<h2 align="center">&nbsp;</h2>
<p>&nbsp;</p>
              
                 <!-- /. ROW  -->           
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
    <div class="footer">
      
    
             <div class="row">
                <div class="col-lg-12" >
                   &copy;  2018 faridanorlita8@gmail.com | Sistem Informasi UNISKA Banjarmasin
                </div>
        </div>
        </div>
          


<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "custom", {validateOn:["blur", "change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "real", {validateOn:["change"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "real", {validateOn:["change"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "email", {validateOn:["change"]});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {validateOn:["change"]});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "real", {validateOn:["change"]});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "real", {validateOn:["change"]});
</script>

</body>
</html>
<?php
mysql_free_result($mahasiswa);

mysql_free_result($kelas);

mysql_free_result($cri);
?>
