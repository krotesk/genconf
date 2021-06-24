<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" >
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="height=device-height">
        <title>Модуль генерации конфигураций для Grandstream</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <style type="text/css">
            <!--
            .style1 {color: #FFFFFF}
            -->
        </style>
    </head>
    <body height="100%">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF" class="main_border">
            <tr>
                <td colspan="3" bgcolor="0066FF"><img src="blocks/head.png" width="500" height="97"></td>
            </tr>
        </table>

        <table>
            <thead>
                <h1>Создание конфига:</h1>
            </thead>
            <tr>
                <td>
                    <form action="" method="get" name="gs_provisioning">
<!--                        <label for="ip_addr"><b>Введите IP-адрес телефона:</b></label>
                        <input name="ip_addr" type="text" maxlength="15" placeholder="172.16.0.255"><br> -->
                        <label for="exten"><b>Введите требуемый номер телефона:</b>
                        <input name="exten" type="text" maxlength="4">
                        </label>
                        <br>
                        <input type="submit" name="submit" value="Запросить" method="post">
                    </form>

        <?php
	$exten = 8000;
        $ip_addr = $_GET['ip_addr'];
        $exten = $_GET['exten'];

        include ("blocks/bd.php");

	if (empty($exten)){
            echo "Номер телефона не может быть пустым";
	    break;
        }
	else {
	    if (file_exists($filename)){
		echo "Файл ${filename} уже существует и будет перезаписан";
	    }
	}

	$filepath = "/var/www/html/gs/";
	$filename = $exten . ".xml";
	$fullname = $filepath . $filename;
//	echo $fullname;



	$fconf = fopen($fullname, 'w') or die("не удалось открыть файл");

	$query = "SELECT data FROM sip WHERE id = '${exten}' and keyword = 'secret';";
	$result = mysql_query($query, $db) or die("Query failed");
//	var_dump($query);
//	var_dump($result);
	$ext_secret = mysql_fetch_array($result)[0];
//	var_dump($ext_secret);
//	echo $exten . ':' . $ext_secret;
	mysql_free_result($result);
	mysql_close($db);
        include ("provisioning_gs.php");
//	var_dump($template);
//	echo $template;
	fwrite($fconf, $template);
	fclose($fconf);
	
	if (empty($exten)){
            echo "";
        }
	else {
	    if (file_exists($filename)){
		echo "Файл ${filename} сохранен";
	    }
	}

	?>

		</td>
	    </tr>
	</table>
