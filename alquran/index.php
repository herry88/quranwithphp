<!DOCTYPE html>
<html >
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="google" value="notranslate">
<meta name="author" content="Herry Prasetyo">
<link rel="shortcut icon" href="allah.gif" type="image/x-icon" />
<title>Al Quran</title>
<style>
 
@font-face {
  font-family: 'Uthmani';
  src : url('http://localhost/alquran/sources/font/UthmanicHafs1 Ver09.otf') format('truetype');
}
 
h3{
  background:#222;
  color:#f9f9f9;
   padding:5px;
}
 
.arabic{
    font-family: 'Uthmani', serif;
    font-size: 28px; font-weight: normal;
    direction:rtl;
    padding : 0 5px;
    margin : 0;
}
.arabic_number {
    font-size: 28px; font-weight: normal;
}
.arabic_center{
    font-family: 'Uthmani', serif;
    font-size: 28px; font-weight: normal;
    text-align:center;
    padding : 0 5px;
    margin : 0;
}
.latin {
    font-family: serif;
    font-size: 14px; font-weight: normal;
    direction:ltr;
    padding : 0;
    margin : 0;
}
 
</style>
 </head>
<body>
<p>&copy; Herry Prasetyo 2016 <b>Hak Cipta Hanya Milik Allah SWT</b></p>
<?php
$surat = isset($_GET['surat']) ? $_GET['surat'] : 0;
$nama = isset($_GET['nama']) ? $_GET['nama'] : '';
if($surat == 0)
    show_daftar();
else
    show_quran($surat, $nama);
 
 //MENAMPILKAN LIST AL QURAN
function show_daftar(){
    mb_internal_encoding('UTF-8');
    $data = database("SELECT `index`, surat_indonesia, arti, jumlah_ayat FROM DaftarSurat");
    echo '<table>';
    echo '<tr><th>No.</th><th>Surah</th><th>Arti</th><th>Jumlah Ayat</th></tr>';
    foreach($data as $d){
        echo '<tr><td>'.$d['index'].'</td><td><a href="http://localhost/alquran/index.php?surat='.$d['index'].'&nama='.$d['surat_indonesia'].'">'.$d['surat_indonesia'].'</a></td><td>'.$d['arti'].'</td></td><td>'.$d['jumlah_ayat'].'</td></tr>';
    }
    echo '</table>';
}

function show_quran($surat, $nama=''){ 
    mb_internal_encoding('UTF-8');
    if (($surat < 1) || ($surat > 114)) exit;
    echo '<p><a href="http://localhost/alquran">Kembali ke Index</a></p>';
    echo '<h3>'.$nama.'</h3>';
    if($surat > 1) {
        echo '<p class ="arabic_center">'.mb_strtolower('بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ').'</p>';
        echo '<hr />';
    }
 
    $data = database("SELECT A.text as arabic, B.text as indonesia FROM ArabicQuran A LEFT OUTER JOIN IndonesianQuran B ON A.index=B.index WHERE A.surat = $surat");
 
    $ayat = 1;
    foreach($data as $d){
            $str = mb_strtolower($d['arabic']);
        echo '<p class ="arabic">'. $str .' ﴿'.format_arabic_number($ayat).'﴾</p>';
        echo '<p class ="latin">'.'['.$ayat.'] '.$d['indonesia'] .'</p>';
        echo '<hr />';
        $ayat++;
    }
    echo '<p><a href="http://localhost/alquran">Kembali ke Index</a></p>';
}
 //Koneksi kedalam database quran
function database($sql){
    $db = new mysqli("localhost", "root", "", "quran");
    if($db->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
    }
    $db->query("SET NAMES 'utf8'");
    $db->query('SET CHARACTER SET utf8');
 
    if(!$result = $db->query($sql)){
            die('There was an error running the query [' . $db->error . ']');
    }
       
    $return = array();
    while($row = $result->fetch_array()){
            $return[] = $row;
    }
    $result->free();
    $db->close();
    return $return;
}
 //penomoran ayat dengan arab language
function format_arabic_number($number){
    $arabic_number = array('٠','١','٢','٣','٤','٥','٦','٧','٨','٩');
    $jum_karakter = strlen($number);
    $temp = "";
    for($i = 0; $i < $jum_karakter; $i++){
        $char = substr($number, $i, 1);
        $temp .= $arabic_number[$char];
    }
    return '<span class="arabic_number">'.$temp.'</span>';
}
 
?>

<p>&copy; 2016 Herry Prasetyo <b>Hak Cipta Hanya Milik Allah SWT</b></p>
</body>
</html>