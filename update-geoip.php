<?php
/** Retrieve new GeoIP databases from MaxMind.com
 *
 * Call this page with `key` query parameter set to the value of `admin-secret` (as defined in ./src/config.php).
 * You also need the `maxmind` keys to be properly configured in config.php.
 *
 * Example : open web browser to localhost:8000/update-geoip.php?key=xxxxxxxxxx
 *
 * Define a Cron job to weekly update the databases, like :
 *
 *  `wget localhost:8000/update-geoip.php?key=xxxxxxxxxx`
 *
 */

$config = include('./src/config.php');

function download($url, $name, $untar = false) {

  global $config;
  $path = "./libs/geoip/";
  $username = $config["maxmind"]["account"];
  $password = $config["maxmind"]["password"];
  $destination = "./libs/geoip/" . $name;

  if($untar) $destination .= ".tar.gz";

  set_time_limit(3600);

  // Init authentified connection
  $fp = fopen($destination, 'w+');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSLVERSION, 3);
  curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
  curl_setopt($ch, CURLOPT_FILE, $fp);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 50);

  // Get file
  $data = curl_exec($ch);
  $error = curl_error($ch);
  curl_close($ch);

  if($untar) {
    // Decompress from .tar.gz
    $gz = new PharData($destination);
    $gz->decompress();
    $gz->extractTo($path, $gz->getBasename()."/".$name, true);

    // The .mmdb file gets extracted into a directory named after the DB version.
    // That name is non-constant across updates.
    // Move the DB to the parent folder, which has a constant name.
    rename($path.$gz->getBasename() . "/" . $name, $path.$name);

    // Delete temp files & dirs.
    // Trying to overwrite the .tar at the next update will raise an exception
    unlink(rtrim($destination, ".gz")); // delete .tar
    unlink($destination);               // delete .tar.gz
    rmdir($path.$gz->getBasename());    // delete the (now empty) extracted folder
  }
}

// TODO: get the latest release instead of a fixed version
$phar_object = "https://github.com/maxmind/GeoIP2-php/releases/download/v3.0.0/geoip2.phar";
$cities_db = "https://download.maxmind.com/geoip/databases/GeoLite2-City/download?suffix=tar.gz";
$asn_db = "https://download.maxmind.com/geoip/databases/GeoLite2-ASN/download?suffix=tar.gz";


if (!empty($_GET) && ($_GET["key"] == $config["admin-secret"]) ) {
  print("Downloading MaxMind GeoIP databases...\n");
  download($phar_object, "geoip2.phar");
  download($cities_db, "GeoLite2-City.mmdb", true);
  download($asn_db, "GeoLite2-ASN.mmdb", true);
  print("Done!");
}
else {
  http_response_code(403);
  print("The admin secret key is missing from your request.");
}
