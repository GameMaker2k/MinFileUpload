<?php
@ob_start("ob_gzhandler");
@ini_set("html_errors", false);
@ini_set("track_errors", false);
@ini_set("display_errors", false);
@ini_set("report_memleaks", false);
@ini_set("display_startup_errors", false);
@ini_set("docref_ext", "");
@ini_set("docref_root", "http://php.net/");
if (!defined("E_DEPRECATED")) {
    define("E_DEPRECATED", 0);
}
@error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
@ini_set("ignore_user_abort", 1);
@set_time_limit(30);
@ignore_user_abort(true);
@ini_set('zend.ze1_compatibility_mode', 0);
@ini_set("date.timezone", "UTC");
@date_default_timezone_set("UTC");
@clearstatcache();
if (!isset($_GET['act'])) {
    $_GET['act'] = null;
}
if (!isset($_GET['filename'])) {
    $_GET['filename'] = null;
}
if ($_GET['act'] == "delete") {
    unlink("./uploads/".$_GET['filename']);
}
function _format_bytes($a_bytes)
{
    if ($a_bytes < 1024) {
        return $a_bytes .' B';
    } elseif ($a_bytes < 1048576) {
        return round($a_bytes / 1024, 2) .' KiB';
    } elseif ($a_bytes < 1073741824) {
        return round($a_bytes / 1048576, 2) . ' MiB';
    } elseif ($a_bytes < 1099511627776) {
        return round($a_bytes / 1073741824, 2) . ' GiB';
    } elseif ($a_bytes < 1125899906842624) {
        return round($a_bytes / 1099511627776, 2) .' TiB';
    } elseif ($a_bytes < 1152921504606846976) {
        return round($a_bytes / 1125899906842624, 2) .' PiB';
    } elseif ($a_bytes < 1180591620717411303424) {
        return round($a_bytes / 1152921504606846976, 2) .' EiB';
    } elseif ($a_bytes < 1208925819614629174706176) {
        return round($a_bytes / 1180591620717411303424, 2) .' ZiB';
    } else {
        return round($a_bytes / 1208925819614629174706176, 2) .' YiB';
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title> MinUpload ( Minimalist File Upload ) </title>
  <meta name="generator" content="MinUpload" />
  <meta name="author" content="Kazuki Przyborowski" />
  <meta name="keywords" content="MinUpload,Minimalist File Upload,Minimalist,File Upload,File,Upload" />
  <meta name="description" content="MinUpload ( Minimalist File Upload ) by Kazuki Przyborowski" />
 </head>

 <body>

<form name="file_upload" id="file_upload" action="index.php" method="post" enctype="multipart/form-data">
<input type="submit" name="submit" value="Submit" /> <button type="button" onclick="var input = document.createElement('input'); input.type = 'file'; input.name = 'file[]'; input.id = 'file[]'; document.getElementById('file_upload').appendChild(input); var brline = document.createElement('br'); document.getElementById('file_upload').appendChild(brline);">Add More</button><br />
<label for="file">Filename:</label><br />
<input type="file" name="file[]" id="file[]" /><br />
</form>
 
<?php
$i = 0;
$max = count($_FILES['file']['error']) - 1;
while ($i <= $max) {
    if ($_FILES['file']['error'][$i] === 0) {
        echo "<hr />";
        echo $_FILES['file']['tmp_name'][$i]." => ".getcwd().DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.$_FILES['file']['name'][$i]."<br />\n";
        echo "<a href=\"http://".$_SERVER['HTTP_HOST']."/~cooldude2k/minupload/uploads/".$_FILES['file']['name'][$i]."\" title=\"".$_FILES['file']['name'][$i]."\">http://".$_SERVER['HTTP_HOST']."/~cooldude2k/minupload/uploads/".$_FILES['file']['name'][$i]."</a><br />\n";
        echo "\n<pre>";
        var_dump($_FILES['file']['name'][$i]);
        echo "\n";
        var_dump($_FILES['file']['type'][$i]);
        echo "\n";
        var_dump($_FILES['file']['tmp_name'][$i]);
        echo "\n";
        var_dump($_FILES['file']['error'][$i]);
        echo "\n";
        var_dump($_FILES['file']['size'][$i]);
        echo "</pre>\n";
        move_uploaded_file($_FILES['file']['tmp_name'][$i], getcwd().DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.$_FILES['file']['name'][$i]);
        @clearstatcache();
    }
    ++$i;
}
chdir("./uploads/");
var_dump(count(glob("*")));
if (count(glob("*")) > 0) {
    echo "\n<hr />\n";
}
echo "\n<pre>";
foreach (glob("*") as $filename) {
    echo "[<a href=\"http://".$_SERVER['HTTP_HOST']."/~cooldude2k/minupload/index.php?act=delete&amp;filename=".urlencode($filename)."\">Delete</a>] <a href=\"http://".$_SERVER['HTTP_HOST']."/~cooldude2k/minupload/uploads/".$filename."\" title=\"".$filename."\">".$filename."</a>\n";
    ?>[<a href="javascript:<?php echo urlencode("alert('".gmdate("F d Y H:i:s", filectime($filename))."');"); ?>">INFO:CTIME</a>] <a href="javascript:<?php echo urlencode("alert('".gmdate("F d Y H:i:s", filectime($filename))."');"); ?>"><?php echo gmdate("F d Y H:i:s", filectime($filename)); ?></a><?php echo "\n";
    ?>[<a href="javascript:<?php echo urlencode("alert('".gmdate("F d Y H:i:s", fileatime($filename))."');"); ?>">INFO:ATIME</a>] <a href="javascript:<?php echo urlencode("alert('".gmdate("F d Y H:i:s", fileatime($filename))."');"); ?>"><?php echo gmdate("F d Y H:i:s", fileatime($filename)); ?></a><?php echo "\n";
    ?>[<a href="javascript:<?php echo urlencode("alert('".filesize($filename)." Bytes => "._format_bytes(filesize($filename))."');"); ?>">INFO:SIZE</a>] <a href="javascript:<?php echo urlencode("alert('".filesize($filename)." Bytes => "._format_bytes(filesize($filename))."');"); ?>"><?php echo filesize($filename)." Bytes =&gt; "._format_bytes(filesize($filename)); ?></a><?php echo "\n";
    if ($_GET['act'] == "debug" || $_GET['act'] == "info") {
        ?>[<a href="javascript:<?php echo urlencode("alert('".hash_file('crc32', $filename, false)."');");
        ?>">INFO:CRC</a>] <a href="javascript:<?php echo urlencode("alert('".hash_file('crc32', $filename, false)."');"); ?>"><?php echo hash_file('crc32', $filename, false); ?></a><?php echo "\n";
        ?>[<a href="javascript:<?php echo urlencode("alert('".hash_file('crc32b', $filename, false)."');"); ?>">INFO:CRCB</a>] <a href="javascript:<?php echo urlencode("alert('".hash_file('crc32b', $filename, false)."');"); ?>"><?php echo hash_file('crc32b', $filename, false); ?></a><?php echo "\n";
        ?>[<a href="javascript:<?php echo urlencode("alert('".hash_file("md2", $filename)."');"); ?>">INFO:MD2</a>] <a href="javascript:<?php echo urlencode("alert('".hash_file("md2", $filename)."');"); ?>"><?php echo hash_file("md2", $filename); ?></a><?php echo "\n";
        ?>[<a href="javascript:<?php echo urlencode("alert('".hash_file("md4", $filename)."');"); ?>">INFO:MD4</a>] <a href="javascript:<?php echo urlencode("alert('".hash_file("md4", $filename)."');"); ?>"><?php echo hash_file("md4", $filename); ?></a><?php echo "\n";
        ?>[<a href="javascript:<?php echo urlencode("alert('".hash_file("md5", $filename)."');"); ?>">INFO:MD5</a>] <a href="javascript:<?php echo urlencode("alert('".hash_file("md5", $filename)."');"); ?>"><?php echo hash_file("md5", $filename); ?></a><?php echo "\n";
        ?>[<a href="javascript:<?php echo urlencode("alert('".hash_file("sha1", $filename)."');"); ?>">INFO:SHA1</a>] <a href="javascript:<?php echo urlencode("alert('".hash_file("sha1", $filename)."');"); ?>"><?php echo hash_file("sha1", $filename); ?></a><?php echo "\n";
        ?>[<a href="javascript:<?php echo urlencode("alert('".hash_file("sha224", $filename)."');"); ?>">INFO:SHA224</a>] <a href="javascript:<?php echo urlencode("alert('".hash_file("sha224", $filename)."');"); ?>"><?php echo hash_file("sha224", $filename); ?></a><?php echo "\n";
        ?>[<a href="javascript:<?php echo urlencode("alert('".hash_file("sha256", $filename)."');"); ?>">INFO:SHA256</a>] <a href="javascript:<?php echo urlencode("alert('".hash_file("sha256", $filename)."');"); ?>"><?php echo hash_file("sha256", $filename); ?></a><?php echo "\n";
        ?>[<a href="javascript:<?php echo urlencode("alert('".hash_file("sha384", $filename)."');"); ?>">INFO:SHA384</a>] <a href="javascript:<?php echo urlencode("alert('".hash_file("sha384", $filename)."');"); ?>"><?php echo hash_file("sha384", $filename); ?></a><?php echo "\n";
        ?>[<a href="javascript:<?php echo urlencode("alert('".hash_file("sha512", $filename)."');"); ?>">INFO:SHA512</a>] <a href="javascript:<?php echo urlencode("alert('".hash_file("sha512", $filename)."');"); ?>"><?php echo hash_file("sha512", $filename); ?></a><?php } echo "\n<hr />\n"; ?>
<?php }
echo "</pre>\n";
?>
 </body>
</html>