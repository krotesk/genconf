<?php
$template = <<<EOD
#!version:1.0.0.1

### This file is the exported MAC-all.cfg.
### For security, the following parameters with password haven't been display in this file.
voice.handfree.tone_vol = 9
voice.ring_vol = 7
### Time Settings
local_time.time_zone = +5
local_time.time_zone_name = Russia(Chelyabinsk)
local_time.summer_time = 0
local_time.ntp_server1 = ${sip_serv}
local_time.ntp_server2 = ${ntp_serv}
local_time.date_format = 6
local_time.manual_ntp_srv_prior = 1
### Config Account 1
account.1.enable = 1
account.1.codec.pcmu.priority = 1
account.1.codec.pcma.priority = 2
account.1.codec.g722.priority = 3
account.1.label = ${exten}
account.1.display_name = ${exten}
account.1.user_name = ${exten}
account.1.auth_name = ${exten}
account.1.password = ${ext_secret}
account.1.sip_server.1.address = ${sip_serv}
account.1.unregister_on_reboot = 1
###  Static Configuration  ###
static.auto_provision.dhcp_option.enable = 0
static.auto_provision.pnp_enable = 0
static.auto_provision.power_on = 0
### Network
static.auto_provision.attempt_expired_time = 300
static.auto_provision.dhcp_option.enable = 0
static.auto_provision.pnp_enable = 0
static.auto_provision.server.url = ${sip_serv}
static.network.dhcp_host_name = tlf_${exten}
voice_mail.number.1 = *97
features.relog_offtime = 15
sip.listen_port = 9950
lang.gui = Russian
lang.wui = Russian
#### по умолчанию rtp-порты 11780-12780
### User and Admin Login
security.user_password = admin:${device_admpass}
security.user_password = user:${device_userpass}
EOD;
?>