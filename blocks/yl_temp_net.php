<?php
$network_template = <<<EOD
### Network Settings ###
static.network.internet_port.gateway = ${sip_serv}
static.network.internet_port.ip = ${ip_addr2}
static.network.internet_port.mask = 255.255.255.0
static.network.internet_port.type = 2
static.network.primary_dns = ${sip_serv}
static.network.secondary_dns = 8.8.8.8
EOD;
?>