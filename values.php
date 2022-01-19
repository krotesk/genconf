<?php
        //Глобальные параметры
        $exten = 123;
        $sip_serv = '10.10.150.250';
        $ntp_serv = 'ntp3.vniiftri.ru';
        //Каталог конфигов
        $filepath = "/var/www/localhost/htdocs/pbx/genconf/";
        $tftppath = "/tftpboot/";
        $device_password_a = "Kolodakart.20";
        $device_password_u = "Kolodakart.20";
        //Настройка ip-адресации
        $ip_mode = 'dhcp';
        // 'static' or 'dhcp'
        $ip_gate = '10.10.150.1';
        $ip_mask = '255.255.255.0';
        $ip_dns = '10.10.150.250';
        $ip_dns2 = '8.8.8.8';
?>