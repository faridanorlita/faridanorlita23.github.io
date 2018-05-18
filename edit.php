<?php require_once('Connections/koneksi.php'); ?>
<?php require_once('akses.php'); ?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tbl_mhs SET nama=%s, npm=%s, kelas=%s, jk=%s, telp=%s, email=%s, alamat=%s, point=%s, kali=%s, jumlah=%s, admin=%s WHERE id_mhs=%s",
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
					   GetSQLValueString($_POST['admin'], "text"),
                       GetSQLValueString($_POST['id_mhs'], "int"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($updateSQL, $koneksi) or die(mysql_error());

 if($updateSQL) {
	  echo "<script type='text/javascript'>alert('Data Berhasil Diedit!');location.href=\"alldata.php?farida=\";</script>";
 }
}

$colname_mahasiswa = "-1";
if (isset($_GET['farida'])) {
  $colname_mahasiswa = $_GET['farida'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_mahasiswa = sprintf("SELECT * FROM tbl_mhs WHERE id_mhs = %s", GetSQLValueString($colname_mahasiswa, "int"));
$mahasiswa = mysql_query($query_mahasiswa, $koneksi) or die(mysql_error());
$row_mahasiswa = mysql_fetch_assoc($mahasiswa);
$totalRows_mahasiswa = mysql_num_rows($mahasiswa);

mysql_select_db($database_koneksi, $koneksi);
$query_kelas = "SELECT * FROM tbl_kls";
$kelas = mysql_query($query_kelas, $koneksi) or die(mysql_error());
$row_kelas = mysql_fetch_assoc($kelas);
$totalRows_kelas = mysql_num_rows($kelas);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Data Mahasiswa</title>
<script src="js/SpryValidationTextField.js" type="text/javascript"></script>
<script src="js/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="js/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="js/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
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
                     <h2>EDIT DATA MAHASISWA</h2>   
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
                  
                  <h1 align="center"><h1 align="center">&nbsp;</h1>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <div align="center">
    <table align="center">
      <tr valign="baseline">
        <td width="48" align="right" nowrap="nowrap">Id_mhs:</td>
        <td width="567"><?php echo $row_mahasiswa['id_mhs']; ?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Nama :</td>
        <td><span id="sprytextfield1">
          <input type="text" name="nama" value="<?php echo htmlentities($row_mahasiswa['nama'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
        <span class="textfieldRequiredMsg">Nama Harus Diisi!</span><span class="textfieldInvalidFormatMsg">Format Salah</span></span></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">NPM :</td>
        <td><span id="sprytextfield2">
          <input type="text" name="npm" value="<?php echo htmlentities($row_mahasiswa['npm'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
        <span class="textfieldRequiredMsg">NPM Harus Diisi!</span><span class="textfieldInvalidFormatMsg">NPM Harus Angka!</span></span></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Kelas :</td>
        <td><select name="kelas">
          <?php 
do {  
?>
          <option value="<?php echo $row_kelas['kelas']?>" <?php if (!(strcmp($row_kelas['kelas'], htmlentities($row_mahasiswa['kelas'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_kelas['kelas']?></option>
          <?php
} while ($row_kelas = mysql_fetch_assoc($kelas));
?>
        </select></td>
      </tr>
      <tr> </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Jenis Kelamin:</td>
        <td valign="baseline"><table width="230">
          <tr>
            <td width="287"><input type="radio" name="jk" value="Laki-Laki" <?php if (!(strcmp(htmlentities($row_mahasiswa['jk'], ENT_COMPAT, 'utf-8'),"Laki-Laki"))) {echo "checked=\"checked\"";} ?> />
              Laki-Laki
              <input type="radio" name="jk" value="Perempuan" <?php if (!(strcmp(htmlentities($row_mahasiswa['jk'], ENT_COMPAT, 'utf-8'),"Perempuan"))) {echo "checked=\"checked\"";} ?> />Perempuan</td>
            </tr>
        </table></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Telpon :</td>
        <td><span id="sprytextfield3">
        <input type="text" name="telp" value="<?php echo htmlentities($row_mahasiswa['telp'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
        <span class="textfieldRequiredMsg">Format Salah</span><span class="textfieldInvalidFormatMsg">Telpon Harus Diisi</span></span></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Email:</td>
        <td><span id="sprytextfield4">
          <input type="text" name="email" value="<?php echo htmlentities($row_mahasiswa['email'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
        <span class="textfieldRequiredMsg">Email Harus Diisi!</span><span class="textfieldInvalidFormatMsg">Format Salah!</span></span></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right" valign="top">Alamat :</td>
        <td>
          <textarea name="alamat" cols="50" rows="5" required="required" ><?php echo htmlentities($row_mahasiswa['alamat'], ENT_COMPAT, 'utf-8'); ?>
      </textarea>
        </td> 
        <label for="1"></label></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right" valign="top"><label for="point">Point  :</label></td>
        <td><span id="sprytextfield5">
        <input type="text" name="point" value="<?php echo htmlentities($row_mahasiswa['point'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
        <span class="textfieldRequiredMsg">Point Harus Diisi!</span><span class="textfieldInvalidFormatMsg">Format Harus Angka!</span></span></td>   
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right" valign="top"><label for="kali">Perkalian Point :</label></td>
        <td><span id="sprytextfield6">
        <input type="text" name="kali" value="<?php echo htmlentities($row_mahasiswa['kali'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
        <span class="textfieldRequiredMsg">Perkalian Point Harus Diisi!</span><span class="textfieldInvalidFormatMsg">Format Harus Angka!</span></span></td>
      </tr>
	  <tr valign="baseline">
        <td nowrap="nowrap" align="right" valign="top"><label for="kali">Jumlah :</label></td>
        <td>
          <input type="text" name="jumlah" value="<?php echo htmlentities($row_mahasiswa['jumlah'], ENT_COMPAT, 'utf-8'); ?>" size="32" readonly />
        </td>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Edit Data" /><input type="button" value="Batal" onclick="window.location='cari.php'"/>
        <input type="reset" name="reset" id="reset" value="Reset" /></td>
      </tr>
    </table>
    <input type="hidden" name="admin" value="<?php echo $_SESSION['nm_admin']; ?>" />
    <input type="hidden" name="MM_update" value="form1" />
    <input type="hidden" name="id_mhs" value="<?php echo $row_mahasiswa['id_mhs']; ?>" />
  </div>
</form>
<p>&nbsp;</p>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "custom", {validateOn:["change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "real", {validateOn:["change"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "email", {validateOn:["change"]});
</script>


              
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
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "real", {validateOn:["change"]});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "real", {validateOn:["change"]});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "real", {validateOn:["change"]});
</script>
</body>
</html>
<?php
mysql_free_result($mahasiswa);

mysql_free_result($kelas);


?>
