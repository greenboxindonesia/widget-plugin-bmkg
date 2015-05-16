<?php
/*
Plugin Name: BMKG - Widget Cuaca Jawa Timur
* Plugin URI: https://github.com/greenboxindonesia/widget-plugin-bmkg
* Github Plugin URI: https://github.com/greenboxindonesia/widget-plugin-bmkg
* Facebook Page: http://www.fb.com/greenboxindonesia
* Description: Plugin XML Parse php Gempa Bumi Terkini dari BMKG untuk platform wordpress.
* Version: 2.0
* Author: Albert Sukmono
* Author URI: http://www.albert.sukmono.web.id/
* Copyright (c) 2015, Albert Sukmono (http://albert.sukmono.web.id/)
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

class BMKG_Widget_Cuaca extends WP_Widget {
	function BMKG_Widget_Cuaca() {
		load_plugin_textdomain( 'bmkg-widget-cuaca', false, dirname( plugin_basename( __FILE__ ) ) );
		$widget_ops = array('classname' => 'widget_bmkg-widget-cuaca', 'description' => __('Widget Informasi Prakiraan Cuaca Jawa Timur dari BMKG', 'bmkg-widget-cuaca'));
		$control_ops = array('width' => 400, 'height' => 350);
		$this->WP_Widget('bmkg-widget-cuaca', __('BMKG: Cuaca Jatim', 'bmkg-widget-cuaca'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		$text = apply_filters( 'widget_bmkg-widget-cuaca', $instance['text'], $instance );
	//Menampilkan Informasi Cuaca Jawa Timur Terkini lewat parse xml php dari bmkg.go.id
	$url = "http://data.bmkg.go.id/gempaterkini.xml"; // from http://data.bmkg.go.id/ sesuaikan dengan lokasi yang diinginkan
	$sUrl = file_get_contents($url, False);
	$xml = simplexml_load_string($sUrl);
	//End Cuaca
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }
			ob_start();
			eval('?>'.$text);
			$text = ob_get_contents();
			ob_end_clean();
			?>
			<div class="bmkg-widget-cuaca"><?php echo $instance['filter'] ? wpautop($text) : $text; ?></div>
		<?php
		echo $after_widget;
		// dibawah format untuk menampilkan data parse xml GEMPA
		echo "<table style='width:100%, border:1px solid #999;'>";
		echo "<tr>";
			echo "<td width='70'>Tanggal</td>";
			echo "<td width='110'> : " .$xml->gempa->Tanggal. "</b></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Jam</td>";
			echo "<td> : " .$xml->gempa->Jam. "</b></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Lintang</td>";
			echo "<td> : " .$xml->gempa->Lintang. "</b></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Bujur</td>";
			echo "<td> : " .$xml->gempa->Bujur. "</b></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Magnitude</td>";
			echo "<td> : " .$xml->gempa->Magnitude. "</b></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Kedalaman</td>";
			echo "<td> : " .$xml->gempa->Kedalaman. "</b></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan='2'><div align='center'>Wilayah : " .$xml->gempa->Wilayah. "</b></th>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan='2'><div align='center'>Koordinat : " .$xml->gempa->point->coordinates. "</b></th>";
		echo "</tr>";
		echo "</table>";
		//End Script GEMPA
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title = strip_tags($instance['title']);
		$text = format_to_edit($instance['text']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'bmkg-widget-cuaca'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		
<?php
	}
}

add_action('widgets_init', create_function('', 'return register_widget("BMKG_Widget_Cuaca");'));

// End All

<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Tanggal); $i++) {
    $row = $xml->Tanggal[$i];
 	echo "<div align='center'>Berlaku mulai tanggal " . $row->Mulai . " Pukul " . $row->MulaiPukul . " WIB<br/>";
	echo "Sampai dengan tanggal " . $row->Sampai . " Pukul " . $row->SampaiPukul . " WIB</div><br/>";
}
?>
<!-- css -->
<style>
.table {
  width: 100%;
  margin-bottom: 20px;
}
.table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
  border: 1px solid #ddd;
}
th {
  text-align: left;
}
.ft12 {
  font-size: 12px;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  padding: 8px;
  line-height: 1.42857143;
  vertical-align: top;
  border-top: 1px solid #ddd;
}
.table>thead>tr>th {
  vertical-align: bottom;
  border-bottom: 2px solid #ddd;
}
</style>
<!-- Warning Cuaca Propinsi -->
<table class="table table-hover table-bordered table-striped ft12">
<thead>
  <tr>
    <th>Kota</th>
    <th>Cuaca Hari Ini</th>
	<th>Cuaca Esok Hari</th>
  </tr>
</thead>
<tbody>
<!--- Cuaca Kota Pacitan ---->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "pacitan") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "pacitan") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Ponorogo ---->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "ponorogo") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "ponorogo") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Trenggalek ----->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "trenggalek") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "trenggalek") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Tulungagung ----->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "tulungagung") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "tulungagung") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!--- Cuaca Kota Blitar -->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "blitar") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "blitar") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!--- Cuaca Kota Kediri --->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "kediri") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "kediri") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!--- Cuaca Kota Kepanjen --->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "kepanjen") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "kepanjen") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!--- Cuaca Kota Lumajang -->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "lumajang") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "lumajang") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!--- Cuaca Kota Jember --->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "jember") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "jember") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!--- Cuaca Kota Banyuwangi --->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "banyuwangi") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "banyuwangi") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!--- Cuaca Kota Bondowoso --->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "bondowoso") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "bondowoso") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!--- Cuaca Kota Situbondo --->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "situbondo") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "situbondo") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!--- Cuaca Kota Probolinggo --->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "probolinggo") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "probolinggo") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!--- Cuaca Kota Pasuruan --->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "pasuruan") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "pasuruan") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!--- Cuaca Kota Sidoarjo --->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "sidoarjo") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "sidoarjo") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!--- Cuaca Kota Mojokerto ---->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "mojokerto") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "mojokerto") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!--- Cuaca Kota Jombang --->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "jombang") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "jombang") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Nganjuk ----->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "nganjuk") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "nganjuk") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Madiun ---->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "madiun") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "madiun") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!----- Cuaca Kota Magetan ---->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "magetan") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "magetan") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Ngawi --->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "ngawi") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "ngawi") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Bojonegoro ---->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "bojonegoro") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "bojonegoro") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Tuban ---->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "tuban") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "tuban") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Lamongan ----->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "lamongan") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "lamongan") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Gresik ---->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "gresik") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "gresik") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Bangkalan ---->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "bangkalan") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "bangkalan") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!----- Cuaca Kota Sampang ---->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "sampang") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "sampang") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Pamekasan ---->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "pamekasan") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "pamekasan") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Sumenep ---->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "sumenep") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "sumenep") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!--- Cuaca Kota Kediri ---->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "kediri") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "kediri") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Malang ---->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "malang") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "malang") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Batu --->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "batu") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "batu") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
<!---- Cuaca Kota Surabaya ---->
<tr>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_1.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "surabaya") {
    	echo"<td>" . strtoupper($row->Kota) . "</td>";
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
<?php
$url = "http://data.bmkg.go.id/propinsi_16_2.xml";
$sUrl = file_get_contents($url, False);
$xml = simplexml_load_string($sUrl);
for ($i=0; $i<sizeof($xml->Isi->Row); $i++) {
    $row = $xml->Isi->Row[$i];
    if(strtolower($row->Kota) == "surabaya") {
    	echo"<td align='center'>";
	echo"<img src='http://www.bmkg.go.id/ImagesStatus/" . $row->Cuaca . ".gif' alt='" . $row->Cuaca . "' /><br/>";
    	echo " " . $row->Cuaca . "<br/>";
    	echo "Suhu : " . $row->SuhuMin . " - ".$row->SuhuMax . " &deg;C<br/>";
 	echo "Kelembapan : " . $row->KelembapanMin . " - " . $row->KelembapanMax . " %<br/>";
	echo "Kecepatan Angin : " . $row->KecepatanAngin . " (km/jam)<br/>";
	echo "Arah Angin : " . $row->ArahAngin . "<br/><br/>";
	echo"</td>";
	break;
	}
}
?>
</tr>
</tbody>
</table>
