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
                        <input name="ip_addr" type="text" maxlength="15" placeholder="172.16.0.255"><br>-->
                        <label for="exten"><b>Введите требуемый номер телефона:</b>
                        <input name="exten" type="text" maxlength="4" placeholder="123">
                        </label>
                        <br>
                        <label for="vendor"><b>Выберите производителя телефона:</b><br>
                    	    <input type="radio" name="vendor" value="gs">Grandstream<br>
                            <input type="radio" name="vendor" value="yl" checked>Yealink<br>
                        </label>
                        <br>
                        <input type="submit" name="submit" value="Запросить" method="post">
                    </form>

        <?php
        //Переменные
        //Подключение основных переменных
	include ("values.php");
        $ip_addr = $_GET['ip_addr'];
        $exten = $_GET['exten'];
        $vendor = $_GET['vendor'];
	//Подключение к БД
        include ("blocks/bd.php");

	//Расширение файлов
	if ($vendor == 'gs'){
	    $filepost = ".xml";
	    }
	else if ($vendor == 'yl'){
	    $filepost = ".conf";
	}
	//Имена файлов с путями и расширениями
	$filename = $exten . $filepost;
	$fullname = $filepath . $filename;
	//Имя файла для конфига елинка
	$mac = shell_exec("/sbin/arp | grep $ip_addr | awk '{print $3}'");
	$mac2 = str_replace(":", "", $mac);
	//echo $mac2;

	if (empty($vendor)){
	    $vendor = 'yl';
            exit ("Необходимо выбрать производителя телефона");
        }


	if (empty($exten)){
            exit ("Номер телефона не может быть пустым");
//	    break;
        }
	else if (file_exists($filename)){
		echo "Файл ${filename} уже существует и будет перезаписан <br>";
	}

	$fconf = fopen($filename, 'w') or die("не удалось открыть файл");

	$query = "SELECT data FROM sip WHERE id = '${exten}' and keyword = 'secret';";
	$result = mysql_query($query, $db) or die("Query failed");
//	var_dump($query);
//	var_dump($result);
	$ext_secret = mysql_fetch_array($result)[0];
//	var_dump($ext_secret);
//	echo $exten . ':' . $ext_secret;
	mysql_free_result($result);
	mysql_close($db);
	
	if ($vendor == 'gs'){
        include ("gs_template.php");	    
	    }
	else if ($vendor == 'yl'){
        include ("yl_template.php");
	}
	
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
	
	echo '<br>  <hr align="left" width="100%" size="2" color="#ff0000" />';
/*	
	$dir = '/var/www/localhost/htdocs/pbx/genconf';
	$files1 = array_slice(scandir($dir), 3);
	$expansions = ["conf", "xml"];
//	var_dump($files1);
	$files2 = preg_grep("/xml conf/", $files1);
//	var_dump($files2);
	
	echo "<table border='1'>";

	foreach($files1 as $elem){
	var_dump($elem);
	echo "<br>";
//	var_dump($expansions);
	
	    if(preg_grep("/xml conf/", $elem)){
		var_dump($elem);
		echo "<tr> $expansions </tr><br>";
	    }
	}
	
	echo "</table>";
*/	
	?>
		</td>
	    </tr>
	</table>
    </body>
</html>