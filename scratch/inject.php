<?php
$keg = file_get_contents('scratch/output_kegiatan.php');
$keg = preg_replace('/<\?php/', '', $keg);
$keg = preg_replace('/\?>/', '', $keg);

$blade = file_get_contents('resources/views/kegiatan/index.blade.php');
$blade = preg_replace('/@php\n\/\/ Dummy data per month.*?@endphp/s', "@php\n" . trim($keg) . "\n@endphp", $blade);

file_put_contents('resources/views/kegiatan/index.blade.php', $blade);
echo "Injected successfully.";
