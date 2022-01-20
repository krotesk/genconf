<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" >
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="height=device-height">
        <title>Модуль генерации конфигураций для Grandstream</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <style type="text/css">
	   /* скрываем чекбоксы и блоки с содержанием */
            .hide {
                display: none;
            }
            .hide + label ~ div{
                display: none;
            }
            /* оформляем текст label */
            .hide + label {
                border-bottom: 1px dotted green;
                padding: 0;
                color: green;
                cursor: pointer;
                display: inline-block;
            }
            /* вид текста label при активном переключателе */
            .hide:checked + label {
                color: red;
                border-bottom: 0;
            }
            /* когда чекбокс активен показываем блоки с содержанием  */
            .hide:checked + label + div {
                display: block;
                background: #efefef;
                -moz-box-shadow: inset 3px 3px 10px #7d8e8f;
                -webkit-box-shadow: inset 3px 3px 10px #7d8e8f;
                box-shadow: inset 3px 3px 10px #7d8e8f;
                padding: 10px;
            }
            /* demo контейнер */
            .demo {
                margin: 2% 0.3%;
            }
            input[name="email-value"] {
		display: none;
	    }
	    input[name="email-enabled"]:checked~input[name="email-value"] {
		display: inline-block;
	    }

        </style>
    </head>

    <body height="100%">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF" class="main_border">
            <tr>
                <td colspan="3" bgcolor="0066FF"><img src="blocks/head011.png" width="500" height="97"></td>
            </tr>
        </table>

        <table>
            <thead>
                <h1>Создание конфига:</h1>
            </thead>
            <tr>
                <td>
                    <form action="" method="get" name="gs_provisioning">
                        <label for="current_ip"><b>Введите текущий IP-адрес телефона:</b></label>
                        <input name="current_ip" type="text" maxlength="15" placeholder="172.16.0.255">
                        <br>

                        <div>
                            <label

                            <?php
                            include ("blocks/values.php");
                            var_dump($ip_mode);
                            if ($ip_mode = 'dhcp'){
                        	echo "hidden";
                    	    }
                    	    ?>

                            for="ip_addr"><b>Введите требуемый IP-адрес телефона:</b>
                            <input name="ip_addr" type="text" maxlength="15" placeholder="172.16.0.255">
	                    <br>(Или оставьте пустым при автоматической настройке ip-адреса)
	                    </label>
    	                    <br>
   	                </div>
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
        $ip_addr = $_GET['ip_addr'];
        $exten = $_GET['exten'];
        $vendor = $_GET['vendor'];
        $current_ip = $_GET['current_ip'];

	//Подключение к БД
        include ("blocks/bd.php");

	if ($ip_mode == 'dhcp'){
	    $ip_addr = $current_ip;
	    }

	//Расширение файлов
	if ($vendor == 'gs'){
	    $filepost = ".xml";
	    $fileprefix = "cfg";
	    }
	else if ($vendor == 'yl'){
	    $filepost = ".cfg";
	    $fileprefix = "";
	}

	//Имена файлов с путями и расширениями
	$filename = $exten . $filepost;
	$filename_mac = $fileprefix . $mac2 . $filepost;
	$fullname = $filepath . $filename;

	//Имя файла для конфига
//	var_dump($ip_addr);
	$mac = shell_exec("/sbin/arp | /bin/grep $ip_addr | /bin/awk '{print $3}'");

	//Проверка на пустой мак-адрес	
	if (empty($mac)){
	    $fullname_mac = $fullname;
	}
	else {
	$mac2 = str_replace(":", "", $mac);
	$mac3 = substr($mac2, 0, -1);
	$filename_mac = $fileprefix . $mac3 . $filepost;
	$fullname_mac = $filepath . $filename_mac;
	}
	
//	var_dump($mac3);
//	var_dump($filename);
//	var_dump($filename_mac);
	
	//Проверка на пустое поле производитель
	if (empty($vendor)){
	    $vendor = 'yl';
//          exit ("Необходимо выбрать производителя телефона");
        }

	//Проверка на пустой экстен
	if (empty($exten)){
            exit ("Номер телефона не может быть пустым");
//	    break;
        }
	else if (file_exists($filename) or file_exists($filename_mac)){
		echo "Файлы ${filename} или ${filename_mac} уже существуют и будут перезаписаны<br>";
	}

	$fconf = fopen($filename, 'w') or die("не удалось открыть файл");
	$fconf_mac = fopen($filename_mac, 'w') or die("не удалось открыть файл");

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
        include ("blocks/gs_template.php");
	    }
	else if ($vendor == 'yl'){
        include ("blocks/yl_template.php");
	}

//	var_dump($template);
//	echo $template;
	fwrite($fconf, $template);
	fclose($fconf);

	fwrite($fconf_mac, $template);
	fclose($fconf_mac);
	var_dump($fullname_mac);
//	var_dump($tftppath);
	$tftpname = $tftppath . $filename_mac;
	var_dump($tftpname);
	shell_exec("/bin/mv $fullname_mac $tftppath");
	shell_exec("sudo /bin/chown nobody.nogroup $tftpname");
	shell_exec("sudo /bin/chmod 777 $tftpname");

	if (empty($exten)){
            echo "";
        }
	else {
	    if (file_exists($filename)){
		echo "Файлы ${filename} и ${filename_mac} сохранены";
	    }
	}

	echo '<br>  <hr align="left" width="100%" size="2" color="#ff0000" />';
/*
	$dir = '/var/www/localhost/htdocs/pbx/genconf';
	$files1 = array_slice(scandir($dir), 3);
	$expansions = ["cnf", "xml"];
//	var_dump($files1);
	$files2 = preg_grep("/xml cnf/", $files1);
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
