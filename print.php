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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_dtmhs = 10;
$pageNum_dtmhs = 0;
if (isset($_GET['pageNum_dtmhs'])) {
  $pageNum_dtmhs = $_GET['pageNum_dtmhs'];
}
$startRow_dtmhs = $pageNum_dtmhs * $maxRows_dtmhs;

mysql_select_db($database_koneksi, $koneksi);
$query_dtmhs = "SELECT * FROM tbl_mhs ORDER BY nama ASC";
$query_limit_dtmhs = sprintf("%s LIMIT %d, %d", $query_dtmhs, $startRow_dtmhs, $maxRows_dtmhs);
$dtmhs = mysql_query($query_limit_dtmhs, $koneksi) or die(mysql_error());
$row_dtmhs = mysql_fetch_assoc($dtmhs);

if (isset($_GET['totalRows_dtmhs'])) {
  $totalRows_dtmhs = $_GET['totalRows_dtmhs'];
} else {
  $all_dtmhs = mysql_query($query_dtmhs);
  $totalRows_dtmhs = mysql_num_rows($all_dtmhs);
}
$totalPages_dtmhs = ceil($totalRows_dtmhs/$maxRows_dtmhs)-1;

$queryString_dtmhs = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_dtmhs") == false && 
        stristr($param, "totalRows_dtmhs") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_dtmhs = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_dtmhs = sprintf("&totalRows_dtmhs=%d%s", $totalRows_dtmhs, $queryString_dtmhs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print Data Mahasiswa</title>
<style type ="text/css">
	@media print{
	input.noPrint{display: none;}
	}
body {
	background-color: #FFF;
}
body,td,th {
	color: #000;
}
a:link {
	color: #000;
}
a:visited {
	color: #000;
}
a:active {
	color: #000;
}
</style>
</head>

<body>
<h1 align="center"><a href="home.php">Laporan Data Mahasiswa</a></h1>
<p align="center">&nbsp;</p>
<div align="center">
  <table width="80%" border="1" cellpadding="0" cellspacing="0">
    <tr>
      <th height="30"><div align="center">No</div></th>
      <th height="30"><div align="center">Nama</div></th>
      <th height="30"><div align="center">NPM</div></th>
      <th height="30"><div align="center">Kelas</div></th>
      <th height="30"><div align="center">Jumlah</div></th>
    </tr>
    <?php $no=1; do { ?>
      <tr>
        <td width="40" height="30"><div align="center"><?php echo $no; ?></div></td>
        <td width="40" height="30"><div align="center"><?php echo $row_dtmhs['nama']; ?></div></td>
        <td width="40" height="30"><div align="center"><?php echo $row_dtmhs['npm']; ?></div></td>
        <td width="40" height="30"><div align="center"><?php echo $row_dtmhs['kelas']; ?></div></td>
        <td width="40" height="30"><div align="right"><?php echo 'Rp. ' . number_format($row_dtmhs['jumlah'], 0, ',', '.') .',-'; ?>
        </div></td>
        
      </tr>
      <?php $no++; } while ($row_dtmhs = mysql_fetch_assoc($dtmhs)); ?>
  </table>
</div>
<p align="center"><input class="noPrint" type="button" value="Print Laporan" onclick="window.print()">
<table border="0" align="center">
  <tr>
    <td><?php if ($pageNum_dtmhs > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_dtmhs=%d%s", $currentPage, 0, $queryString_dtmhs); ?>">First</a>
      <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_dtmhs > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_dtmhs=%d%s", $currentPage, max(0, $pageNum_dtmhs - 1), $queryString_dtmhs); ?>">Previous</a>
      <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_dtmhs < $totalPages_dtmhs) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_dtmhs=%d%s", $currentPage, min($totalPages_dtmhs, $pageNum_dtmhs + 1), $queryString_dtmhs); ?>">Next</a>
      <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_dtmhs < $totalPages_dtmhs) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_dtmhs=%d%s", $currentPage, $totalPages_dtmhs, $queryString_dtmhs); ?>">Last</a>
      <?php } // Show if not last page ?></td>
  </tr>
</table>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
<div align="center"></div>
</body>
</html>
<?php
mysql_free_result($dtmhs);
?>
