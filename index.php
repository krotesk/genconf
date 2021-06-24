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
	$exten = 123;
        $ip_addr = $_GET['ip_addr'];
        $exten = $_GET['exten'];
        $vendor = $_GET['vendor'];
	$sip_serv = '10.10.150.250';
	$ntp_serv = 'ntp3.vniiftri.ru';
	//Подключение к БД
        include ("blocks/bd.php");
	//Каталог конфигов
	$filepath = "/var/www/html/gs/";
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
	
	if (empty($vendor)){
	    $vendor = 'yl';
            exit ("Необходимо выбрать производителя телефона");
        }


	if (empty($exten)){
            exit ("Номер телефона не может быть пустым");
        }
	else if (file_exists($filename)){
		echo "Файл ${filename} уже существует и будет перезаписан <br>";
	}

	$fconf = fopen($filename, 'w') or die("не удалось открыть файл");

	$query = "SELECT data FROM sip WHERE id = '${exten}' and keyword = 'secret';";
	$result = mysql_query($query, $db) or die("Query failed");
	$ext_secret = mysql_fetch_array($result)[0];
	mysql_free_result($result);
	mysql_close($db);
	
	if ($vendor == 'gs'){
        include ("gs_template.php");	    
	    }
	else if ($vendor == 'yl'){
        include ("yl_template.php");
	}
	
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
	?>
		</td>
	    </tr>
	</table>
    </body>
</html>