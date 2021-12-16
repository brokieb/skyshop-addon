<?php
$ch = curl_init($_POST['attach']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization:bearer ".$_POST['token']
]);
$mainResult = curl_exec($ch);
curl_close($ch);
echo $path = 'download/'.$_POST['file'];
$file = fopen($path,'w+');
readfile($mainResult);
fputs($file,$mainResult);
fclose($file);
?>

<script>
window.location.assign('<?=$path?>');
</script>
