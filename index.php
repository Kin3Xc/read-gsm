<?php

$myFile = "file.txt";
$fh = fopen($myFile, 'a') or die("can't open file");
fwrite($fh, '');

if($_GET['dat'] == "")
{
// no username entered
echo "You did not enter a name.";
}
else
{
$stringData = $_GET['dat'] ;
fwrite($fh, "\n");
fwrite($fh, strftime('%c'));
fwrite($fh, " :");
fwrite($fh, $stringData);
// echo "Hello, " . $_GET['dat'];
fclose($fh);
}
?>