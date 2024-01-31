<?php
session_start();
include "koneksi.php";
if( empty( $_SESSION['iduser'] ) ){
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
   $nis = $_REQUEST['nis'];
   if(isset($_REQUEST['submit'])){
      //cetak nota pembayaran sesuai NIS dan BULAN
      $submit = $_REQUEST['submit'];
      $kls = $_REQUEST['kls'];
      $bln = $_REQUEST['bln'];
      
      //print: $nis, $nama, $kls, $bln, $tgl_bayar, $jml
      $sql = mysqli_query($koneksi, "SELECT s.nama_siswa,p.tgl_bayar,p.jumlah FROM siswa s INNER JOIN pembayaran p ON s.nis = p.nis AND p.nis='$nis'");
      list($nama,$tgl_bayar,$jml) = mysqli_fetch_array($sql);
      
      $printTestText = "NIS   : ".$nis."\n<br>";
      $printTestText .= "NAMA  : ".$nama."\n<br>";
      $printTestText .= "KELAS : ".$kls."\n\n<br><br>";
      $printTestText .= "========================================\n<br>";
      $printTestText .= str_pad($tgl_bayar,20);
		$printTestText .= str_pad($bln,3);
		$printTestText .= str_pad($jml,10," ",STR_PAD_LEFT)."\n<br>";
      $printTestText .= "========================================\n<br>";
      $printTestText .= "\n<br>";
      $printTestText .= "\n<br>";
      $printTestText .= str_pad("--= TERIMA KASIH =--",40," ",STR_PAD_BOTH)."\n<br>";
      $printTestText .= "\n";
      
      echo $printTestText;
     
      
     
   } else {
      //cetak seluruh pembayaran sesuai NIS
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>phpBayar</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

	<style type="text/css">
	body {
	  min-height: 200px;
	  padding-top: 50px;
	}
   @media print {
      .noprint {
         display: none;
      }
   }
	</style>
  </head>

  <body>
    <div class="container">
<?php
   echo '<h3>Bukti Pembayaran SPP</h3>';
   $qsiswa = mysqli_query($koneksi,"SELECT * FROM siswa WHERE nis='$nis'");
   list($nis,$nama,$idprodi) = mysqli_fetch_array($qsiswa);
   
   echo '<div class="row">';
   echo '<div class="col-sm-6"><table class="table table-bordered">';
   echo '<tr><td colspan="2">Nomor Induk</td><td colspan="3">'.$nis.'</td></tr>';
   echo '<tr><td colspan="2">Nama Siswa</td><td colspan="3">'.$nama.'</td></tr>';
   echo '<tr class="info"><th width="50">#</th><th width="100">Kelas</th><th>Bulan</th><th>Tanggal Bayar</th><th>Jumlah</th>';
   echo '</tr>';
   
   //tampilkan histori pembayaran, jika ada
   $qbayar = mysqli_query($koneksi,"SELECT kelas,bulan,tgl_bayar,jumlah FROM pembayaran WHERE nis='$nis' ORDER BY tgl_bayar DESC");
   if(mysqli_num_rows($qbayar) > 0){
      $no = 1;
      while(list($kelas,$bulan,$tgl,$jumlah) = mysqli_fetch_array($qbayar)){
         echo '<tr><td>'.$no.'</td>';
         echo '<td>'.$kelas.'</td>';
         echo '<td>'.$bulan.'</td>';
         echo '<td>'.$tgl.'</td>';
         echo '<td>'.$jumlah.'</td>';
         echo '</tr>';
         
         $no++;
      }
   } else {
      echo '<tr><td colspan="6"><em>Belum ada data!</em></td></tr>';
   }
   echo '</table></div></div>';
?>

   <a class="noprint btn btn-default" onclick="fnCetak()">Cetak</a>

   </div> <!-- /container -->


    <!-- Bootstrap core JavaScript, Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	 <script type="text/javascript">
		$(".force-logout").alert().delay(3000).slideUp('slow', function(){
			window.location = "./logout.php";
		});
      function fnCetak() {
         window.print();
      }
	 </script>
  </body>
</html>
<?php
   }
}
?>