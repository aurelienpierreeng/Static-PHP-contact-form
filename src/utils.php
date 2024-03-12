<?php

function get_host($url)
{
    return parse_url($url)['host'];
}

function get_origin()
{
    if(isset($_SERVER['HTTP_ORIGIN']))
    {
        return get_host($_SERVER['HTTP_ORIGIN']);
    }
    else if(isset($_SERVER['HTTP_REFERER']))
    {
        return get_host($_SERVER['HTTP_REFERER']);
    }

    return "";
}

function authorize($config)
{
  $origin = get_origin();
  header("Vary: Origin");

  if(!empty($origin) && in_array($origin, $config['origins']) && !empty($_SERVER['HTTP_USER_AGENT']))
  {
      header("Access-Control-Allow-Origin: https://" . $origin . "");
      return True;
  }
  else
  {
      // No user-agent or direct access to PHP: block bots
      http_response_code(403);
      echo "Unauthorized";
      return False;
  }
}

function getOS()
{
    // https://stackoverflow.com/questions/18070154/get-operating-system-info-with-php
    $os_array = [
        'windows nt 10'                              => 'Windows 10',
        'windows nt 6.3'                             => 'Windows 8.1',
        'windows nt 6.2'                             => 'Windows 8',
        'windows nt 6.1|windows nt 7.0'              => 'Windows 7',
        'windows nt 6.0'                             => 'Windows Vista',
        'windows nt 5.2'                             => 'Windows Server 2003/XP x64',
        'windows nt 5.1'                             => 'Windows XP',
        'windows xp'                                 => 'Windows XP',
        'windows nt 5.0|windows nt5.1|windows 2000'  => 'Windows 2000',
        'windows me'                                 => 'Windows ME',
        'windows nt 4.0|winnt4.0'                    => 'Windows NT',
        'windows ce'                                 => 'Windows CE',
        'windows 98|win98'                           => 'Windows 98',
        'windows 95|win95'                           => 'Windows 95',
        'win16'                                      => 'Windows 3.11',
        'mac os x 10.1[^0-9]'                        => 'Mac OS X Puma',
        'macintosh|mac os x'                         => 'Mac OS X',
        'mac_powerpc'                                => 'Mac OS 9',
        'ubuntu'                                     => 'Linux - Ubuntu',
        'iphone'                                     => 'iPhone',
        'ipod'                                       => 'iPod',
        'ipad'                                       => 'iPad',
        'android'                                    => 'Android',
        'blackberry'                                 => 'BlackBerry',
        'webos'                                      => 'Mobile',
        'linux'                                      => 'Linux',

        '(media center pc).([0-9]{1,2}\.[0-9]{1,2})'     => 'Windows Media Center',
        '(win)([0-9]{1,2}\.[0-9x]{1,2})'                 => 'Windows',
        '(win)([0-9]{2})'                                => 'Windows',
        '(windows)([0-9x]{2})'                           => 'Windows',

        // Doesn't seem like these are necessary...not totally sure though..
        //'(winnt)([0-9]{1,2}\.[0-9]{1,2}){0,1}'=>'Windows NT',
        //'(windows nt)(([0-9]{1,2}\.[0-9]{1,2}){0,1})'=>'Windows NT', // fix by bg

        'Win 9x 4.90'                                   => 'Windows ME',
        '(windows)([0-9]{1,2}\.[0-9]{1,2})'             => 'Windows',
        'win32'                                         => 'Windows',
        '(java)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2})'    => 'Java',
        '(Solaris)([0-9]{1,2}\.[0-9x]{1,2}){0,1}'       => 'Solaris',
        'dos x86'                                       => 'DOS',
        'Mac OS X'                                      => 'Mac OS X',
        'Mac_PowerPC'                                   => 'Macintosh PowerPC',
        '(mac|Macintosh)'                               => 'Mac OS',
        '(sunos)([0-9]{1,2}\.[0-9]{1,2}){0,1}'          => 'SunOS',
        '(beos)([0-9]{1,2}\.[0-9]{1,2}){0,1}'           => 'BeOS',
        '(risc os)([0-9]{1,2}\.[0-9]{1,2})'             => 'RISC OS',
        'unix'                                          => 'Unix',
        'os/2'                                          => 'OS/2',
        'freebsd'                                       => 'FreeBSD',
        'openbsd'                                       => 'OpenBSD',
        'netbsd'                                        => 'NetBSD',
        'irix'                                          => 'IRIX',
        'plan9'                                         => 'Plan9',
        'osf'                                           => 'OSF',
        'aix'                                           => 'AIX',
        'GNU Hurd'                                      => 'GNU Hurd',
        '(fedora)'                                      => 'Linux - Fedora',
        '(kubuntu)'                                     => 'Linux - Kubuntu',
        '(ubuntu)'                                      => 'Linux - Ubuntu',
        '(debian)'                                      => 'Linux - Debian',
        '(CentOS)'                                      => 'Linux - CentOS',
        '(Mandriva).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)' => 'Linux - Mandriva',
        '(SUSE).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)' => 'Linux - SUSE',
        '(Dropline)'                                    => 'Linux - Slackware (Dropline GNOME)',
        '(ASPLinux)'                                    => 'Linux - ASPLinux',
        '(Red Hat)'                                     => 'Linux - Red Hat',
        // Loads of Linux machines will be detected as unix.
        // Actually, all of the linux machines I've checked have the 'X11' in the User Agent.
        //'X11'=>'Unix',
        '(linux)'                                       => 'Linux',
        '(amigaos)([0-9]{1,2}\.[0-9]{1,2})'             => 'AmigaOS',
        'amiga-aweb'                                    => 'AmigaOS',
        'amiga'                                         => 'Amiga',
        'AvantGo'                                       => 'PalmOS',
        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1}-([0-9]{1,2}) i([0-9]{1})86){1}'=>'Linux',
        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1} i([0-9]{1}86)){1}'=>'Linux',
        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1})'=>'Linux',
        '[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}'            => 'Linux',
        '(webtv)/([0-9]{1,2}\.[0-9]{1,2})'              => 'WebTV',
        'Dreamcast'                                     => 'Dreamcast OS',
        'GetRight'                                      => 'Windows',
        'go!zilla'                                      => 'Windows',
        'gozilla'                                       => 'Windows',
        'gulliver'                                      => 'Windows',
        'ia archiver'                                   => 'Windows',
        'NetPositive'                                   => 'Windows',
        'mass downloader'                               => 'Windows',
        'microsoft'                                     => 'Windows',
        'offline explorer'                              => 'Windows',
        'teleport'                                      => 'Windows',
        'web downloader'                                => 'Windows',
        'webcapture'                                    => 'Windows',
        'webcollage'                                    => 'Windows',
        'webcopier'                                     => 'Windows',
        'webstripper'                                   => 'Windows',
        'webzip'                                        => 'Windows',
        'wget'                                          => 'Windows',
        'Java'                                          => 'Unknown',
        'flashget'                                      => 'Windows',

        // delete next line if the script show not the right OS
        //'(PHP)/([0-9]{1,2}.[0-9]{1,2})'=>'PHP',
        'MS FrontPage'                                  => 'Windows',
        '(msproxy)/([0-9]{1,2}.[0-9]{1,2})'             => 'Windows',
        '(msie)([0-9]{1,2}.[0-9]{1,2})'                 => 'Windows',
        'libwww-perl'                                   => 'Unix',
        'UP.Browser'                                    => 'Windows CE',
        'NetAnts'                                       => 'Windows',
    ];

    // https://github.com/ahmad-sa3d/php-useragent/blob/master/core/user_agent.php
    $arch_regex = '/\b(x86_64|x86-64|Win64|WOW64|x64|ia64|amd64|ppc64|sparc64|IRIX64)\b/ix';
    $arch = preg_match($arch_regex, $_SERVER['HTTP_USER_AGENT']) ? '64' : '32';

    foreach ($os_array as $regex => $value) {
        if (preg_match('{\b(' . $regex . ')\b}i', $_SERVER['HTTP_USER_AGENT'])) {
            return $value . ' x' . $arch;
        }
    }

    return 'Unknown';
}


function getBrowser()
{
    $browser = "Unknown";

    $browser_array = array(
        '/msie/i'      => 'Internet Explorer',
        '/firefox/i'   => 'Firefox',
        '/safari/i'    => 'Safari',
        '/chrome/i'    => 'Chrome',
        '/edge/i'      => 'Edge',
        '/opera/i'     => 'Opera',
        '/netscape/i'  => 'Netscape',
        '/maxthon/i'   => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i'    => 'Handheld Browser'
    );

    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $_SERVER['HTTP_USER_AGENT']))
            $browser = $value;

    return $browser;
}


function get_ip($config)
{
    $ip_address = '';
    if (str_contains($_SERVER['HTTP_HOST'], 'localhost'))
    {
        // In case the page is called from localhost, there is no public IP.
        // Fetch it from config.
        $ip_address = $config['localhost_ip'];
    }
    else if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
        $ip_address = $_SERVER['HTTP_CLIENT_IP']; // Get the shared IP Address
    }
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        // Check if a proxy is used for IP/IPs
        // Split if multiple IP addresses exist and get the last IP address
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false)
        {
            $multiple_ips = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip_address = trim(current($multiple_ips));
        } else
        {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    }
    else if (!empty($_SERVER['HTTP_X_FORWARDED']))
    {
        $ip_address = $_SERVER['HTTP_X_FORWARDED'];
    }
    else if (!empty($_SERVER['HTTP_FORWARDED_FOR']))
    {
        $ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
    }
    else if (!empty($_SERVER['HTTP_FORWARDED']))
    {
        $ip_address = $_SERVER['HTTP_FORWARDED'];
    }
    else
    {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }

    return $ip_address;
}

function get_lang()
{
    return explode(";", $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];
}
