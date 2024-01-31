<?php
if( empty( $_SESSION['iduser'] ) ){
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
   if( isset( $_REQUEST['sub'] )){
      $sub = $_REQUEST['sub'];
      
      include "laporan_tagihan.php";
   } else {
   
      if(isset($_REQUEST['submit'])){
         $submit = $_REQUEST['submit'];
         $bulan = $_REQUEST['bln'];
         $idkelas = $_REQUEST['kls'];
         
         
         $q = "SELECT a.bulan, b.kelas, a.nis, c.nama_siswa, a.jumlah, a.tgl_bayar FROM pembayaran a, kelas b, siswa c WHERE a.nis = c.nis and a.idkelas = b.idkelas and a.bulan = '$bulan' AND a.idkelas = '$idkelas'";
         
         echo '<h2>Rekap Pembayaran <small> Bulan '.$bulan.'</small></h2><hr>';
         
      } else {
         $tgl = date("Y/m/d");
         $q = "SELECT a.bulan, b.kelas, a.nis, c.nama_siswa, a.jumlah, a.tgl_bayar FROM pembayaran a, kelas b, siswa c WHERE tgl_bayar='$tgl' and a.nis = c.nis and a.idkelas = b.idkelas";
         echo '<h2>Rekap Pembayaran <small>'.$tgl.'</small></h2><hr>';
      }
      echo '<a class="noprint pull-right btn btn-default" onclick="fnCetak()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Cetak</a>';
         
      $sql = mysqli_query($koneksi,$q);
      
      echo '<div class="row">';
      echo '<div class="col-md-7">';
?>
<div class="well well-sm noprint">
<form class="form-inline" role="form" method="post" action="">
<div class="form-group">
    <label class="sr-only" for="bln">Bulan</label>
	<select name="bln" id="bln" class="form-control">
	<?php
		for($i=1;$i<=12;$i++){
			$b = date('F',mktime(0,0,0,$i,10));
			echo '<option value="'.$b.'">'.$b.'</option>';
		}
	?>
	</select>
  </div>
	<div class="form-group">
		<label for="kls" class="sr-only">Kelas</label>
		<div class="col-sm-4">
			<select name="kls" class="form-control">
			<?php
			$qprodi = mysqli_query($koneksi,"SELECT * FROM kelas ORDER BY idkelas");
			while(list($idprodi,$prodi)=mysqli_fetch_array($qprodi)){
				echo '<option value="'.$idprodi.'">'.$prodi.'</option>';
			}
			?>
			</select>
		</div>
	</div>
  <button type="submit" name="submit" class="btn btn-default">Tampilkan</button>
</form>
</div>
<?php 
      echo '<table class="table table-bordered">';
      echo '<tr class="info"><th>#</th><th>Bulan</th><th>Kelas</th><th>NIS</th><th>Nama</th><th>Jumlah</th><th>Tanggal Bayar</th></tr>';
      
      $total = 0;
      $no=1;
      while(list($bln,$kls,$nis,$nm,$jml,$tgl) = mysqli_fetch_array($sql)){
         echo '<tr><td>'.$no.'</td><td>'.$bln.'</td><td>'.$kls.'</td><td>'.$nis.'</td><td>'.$nm.'</td><td>'.$tgl.'</td><td><span class="pull-right">'.$jml.'</span></td></tr>';
         $total += $jml;
         $no++;
      }
      
      echo '</table>';
      echo '</div></div>';
   }
}
?>