<?php
if( empty( $_SESSION['iduser'] ) ){
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
		echo '<h2>Tentang Kami</h2><hr>';
		echo '<small>Tugas ini disusun oleh :</small><br><br>';
      echo '<div class="row">';
		echo '<div class="col-md-7"><table class="table table-bordered">';
		echo '<tr class="info"><th>Nama</th><th>NIM</th>';
				echo '<tr><td>Ali Gufron </td>';
				echo '<td>190441100140</td>';
				echo '</tr>';
				echo '<tr><td>Fiqih Afrizal </td>';
				echo '<td>190441100035</td>';
				echo '</tr>';
				echo '<tr><td>Alfi Naimah </td>';
				echo '<td>190441100030</td>';
				echo '</tr>';
				echo '<tr><td>Muhammad Alfian Harun </td>';
				echo '<td>190441100040</td>';
				echo '</tr>';
				echo '<tr><td>Mohammad Anang Makruf </td>';
				echo '<td>190441100147</td>';
				echo '</tr>';
	}

?>