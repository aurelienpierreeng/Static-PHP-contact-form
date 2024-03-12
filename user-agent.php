<?php
/**
 * Return a JSON response containing user agent info, including geographic location.
 */

$config = include('./src/config.php');
require_once("./src/utils.php");

require_once("./libs/geoip/geoip2.phar");
use GeoIp2\Database\Reader;
$GeoIPCity = new Reader("./libs/geoip/GeoLite2-City.mmdb");
$GeoIPASN = new Reader("./libs/geoip/GeoLite2-ASN.mmdb");

// Build response headers
if(!authorize($config)) return;
header('Content-Type: application/json; charset=utf-8');

// Build JSON response
$origin_ip = get_ip($config);

try {
    $city_record = $GeoIPCity->city($origin_ip);
    $asn_record = $GeoIPASN->asn($origin_ip);
    $whois = $city_record->location;
    $isp = $asn_record->autonomousSystemOrganization;
    $country = $city_record->country->name;
}
catch (Exception $e) {
    // Not much to do... IP is most likely not in DB.
}

$response = array(
    "ip"         => get_ip($config),
    "origin"     => get_origin(),
    "user-agent" => $_SERVER['HTTP_USER_AGENT'],
    "lang"       => get_lang(),
    "whois"      => $whois,
    "isp"        => $isp,
    "country"    => $country,
    "browser"    => getBrowser(),
    "OS"         => getOS(),
    "port"       => $_SERVER['SERVER_PORT'],
    "host"       => $_SERVER['HTTP_HOST'],
    "secret"     => $config['user-secret'],
);

echo json_encode($response);
