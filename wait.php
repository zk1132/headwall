<?
    /*
     * wait.php
     *
     *  Copyright (C) 2013-2014 by Erland Hedman <erland@hedmanshome.se>
     *
     * This program is free software; you can redistribute it and/or
     * modify it under the terms of the GNU General Public License
     * as published by the Free Software Foundation; either version
     * 2 of the License, or (at your option) any later version.
     */

    error_reporting(0);
    $nosess=1;
    include 'cgi-bin/common.php';

    do_hostopts();
    @system("leases " .g_lan()." ".g_domain().">/tmp/reslist &");
	@system("alleases ".g_domain().">/tmp/dmlist &");
    @system("topspammers > /tmp/spamlist &");

    //echo "<pre>"; print_r($_POST); echo "</pre>";
    //echo "<pre>"; print_r($_GET); echo "</pre>";
    touch("/tmp/keep_wd");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="/css/bridge.css">
        <title><? p_title(); ?></title>
        <script>

<? include 'inc/general.js.php' ?>

window.location.hash="no-back-button";
window.location.hash="Again-No-back-button";//again because google chrome don't insert first hash into history
window.onhashchange=function(){window.location.hash="no-back-button";}

function get_burn_time(size)
{
	var burn_time, countdown;
	var bsize = parseInt(size,[10]);

	burn_time = parseInt((bsize+63)/64,[10]) * 2102;
	burn_time = parseInt((burn_time+999)/1000,[10]);
    countdown=<? echo $_GET[seconds]?$_GET[seconds]:60 ?>

	return countdown;
}

var countdown = get_burn_time(64);
function initPage()
{
    <?
        if ($_GET[reboot] == "y")
        system("init 6&");
    ?>
	
    nev();
}

function nev()
{
	countdown--;
	document.form.WaitInfo.value=countdown;
	if(countdown < 1 ) {
        <? 
            if (isset($_GET[loc])) {
                if (isset($_GET[ip]))
                    $url="http://".$_GET[ip];
                else $url="";
                echo "top.location.href='".$url.$_GET[loc]."'";
            } else
                echo 'history.go(-1);';
            echo "\n";
        ?>
    }
	else setTimeout('nev()',1000);
}
        </script>
    </head>
    <body onload="initPage();" style="width:600px;"><script>onbody();</script>
    <form name="form" id="form" style="height:0">
        <table id="topContainer">
            <tr>
	            <td class="laCN">Project Page&nbsp;:&nbsp;<a href="<? p_productHome(); ?>" target=_blank><? p_serverName(); ?></a></td>
	            <td class="raCN">Version&nbsp;:&nbsp;<? p_firmware("-ro");?>&nbsp;</td>
            </tr>
        </table>
        <table id="topTable">
            <tr>
	            <td id="topBarLeft"><a id="logo" href="<? p_productHome(); ?>"></a></td>	            
	            <td id="topBarRight"></td>
            </tr>
        </table>
        <table id="mainContentTable">
            <tr style="vertical-align: top;">
	            <td style="padding: 4% 8% 5% 8%">
		            <div class="actionBox" style="background-color: #DEDEDE">
			            <h2 class="actionHeader">Wait</h2>
			            <table>
			                <tr>
				                <td class="laCB" style="text-align: center; height:80px;">
				                    <? if ($_GET[reboot] == "y") { ?>
			                        The device is rebooting...<br><br>
                                    Please <b style="color:red">DO NOT POWER OFF</b> the device.<br><br>
                                    <? } else { ?>
                                    The new settings have been saved ...<br><br>
                                    Please <b style="color:red">DO NOT POWER OFF</b> the device.<br><br>
                                    <? } ?>
                                    And please wait for
                                    <input type="Text" readonly name="WaitInfo" size="2" style="border-width:0; background-color:#DFDFDF; color:#FF3030; text-align:center">
                                    seconds...
                                </td>
			                </tr>
			            </table>
		            </div>
                </td>
            </tr>
            <tr>
	            <td colspan="3" id="footer">
                    Copyright &copy; <? p_copyRight(); ?>
                </td>                   
            </tr>
        </table>
    </form>
    </body>
</html>
