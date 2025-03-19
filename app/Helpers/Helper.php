<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

/**
 * Kullanicinin kullandigi isletim sistemi bilgisini alir.
 *
 * @since 2.0
 */

function getOS()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $os_platform = "Unknown Operating System";
    $os_array = array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );
    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $os_platform = $value;
        }
    }
    return $os_platform;
}

/**
 * Kullanicinin kullandigi internet tarayici bilgisini alir.
 *
 * @since 2.0
 */
function getBrowser()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser = "Unknown browser";
    $browser_array = array(
        '/msie/i' => 'Internet Explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/netscape/i' => 'Netscape',
        '/maxthon/i' => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i' => 'Handheld Browser'
    );

    foreach ($browser_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $browser = $value;
        }
    }
    return $browser;
}

function getIP()
{
    $ip_address = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP']; // Get the shared IP Address
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //Check if the proxy is used for IP/IPs
        // Split if multiple IP addresses exist and get the last IP address
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
            $multiple_ips = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip_address = trim(current($multiple_ips));
        } else {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    } else if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED'];
    } else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (!empty($_SERVER['HTTP_FORWARDED'])) {
        $ip_address = $_SERVER['HTTP_FORWARDED'];
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    return $ip_address;
}

function getMenu()
{
    $permission = Auth::user();
    //$modules = Module::allEnabled();

    $json = file_get_contents(dirname(__FILE__, 3) . '/modules_statuses.json');
    $modules = json_decode($json, true);

    foreach ($modules as $key => $module) {
        $name = strtolower($key);
        if (config($name . ".application") == true && $module == true) {
            if ((int)$permission->can(config($name . ".role")) == true) {
                $nameMenu = strtolower(config($name . ".name"));
                if (Lang::has('modules.modules.' . $nameMenu . '')) {
                    $nameModule = trans('modules.modules.' . $nameMenu . '');
                } else {
                    $nameModule = config($name . ".name");
                }
                if (config($name . ".sub-menu") == "") {
                    echo '<li class="nav-item">
						<a href="' . route(config($name . ".route-name")) . '" class="group">
							<div class="flex items-center">
								' . config($name . ".icon") . '
								<span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">' . $nameModule . '</span>
							</div>
						</a>
					</li>';
                } else {
                    echo '<li class="menu nav-item">
						<button type="button" class="nav-link group" :class="{\'active\' : activeDropdown === \'' . $nameMenu . '\'}" @click="activeDropdown === \'' . $nameMenu . '\' ? activeDropdown = null : activeDropdown = \'' . $nameMenu . '\'">
							<div class="flex items-center">
								' . config($name . ".icon") . '
								<span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">' . $nameModule . '</span>
							</div>
							<div class="rtl:rotate-180" :class="{\'!rotate-90\' : activeDropdown === \'' . $nameMenu . '\'}">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</div>
						</button>
						<ul x-cloak x-show="activeDropdown === \'' . $nameMenu . '\'" x-collapse class="sub-menu text-gray-500">';
                    foreach (config($name . ".sub-menu") as $submenu) {
                        echo '<li>
							    <a href="' . route($submenu['route-name']) . '">' . $submenu['name'] . '</a>
							  </li>';
                    }
                    echo '</ul>
					</li>';
                }
            }
        }
    }
}

/**
 * Display a listing of the resource.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
function responseResult($data, $errors, $msg, $code = 200)
{
    // https://gist.github.com/jeffochoa/a162fc4381d69a2d862dafa61cda0798
    $statuses = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',            // RFC2518
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',          // RFC4918
        208 => 'Already Reported',      // RFC5842
        226 => 'IM Used',               // RFC3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',    // RFC7238
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',                                               // RFC2324
        421 => 'Misdirected Request',                                         // RFC7540
        422 => 'Unprocessable Entity',                                        // RFC4918
        423 => 'Locked',                                                      // RFC4918
        424 => 'Failed Dependency',                                           // RFC4918
        425 => 'Reserved for WebDAV advanced collections expired proposal',   // RFC2817
        426 => 'Upgrade Required',                                            // RFC2817
        428 => 'Precondition Required',                                       // RFC6585
        429 => 'Too Many Requests',                                           // RFC6585
        431 => 'Request Header Fields Too Large',                             // RFC6585
        451 => 'Unavailable For Legal Reasons',                               // RFC7725
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',                                     // RFC2295
        507 => 'Insufficient Storage',                                        // RFC4918
        508 => 'Loop Detected',                                               // RFC5842
        510 => 'Not Extended',                                                // RFC2774
        511 => 'Network Authentication Required',                             // RFC6585
    ];

    $status = $statuses[$code] ?? $statuses[500];
    $response = [
        'status' => $status,
        'data' => $data,
        'message' => $msg,
        'code' => $code
    ];
    if (!empty($errors)) {
        $response += [
            'errors' => $errors,
        ];
    }
    return response()->json($response, $code);
}

function timeAgo($time)
{
    // Calculate difference between current
    // time and given timestamp in seconds

    $timeAgo = "";

    $time   = strtotime($time);

    $diff     = time() - $time;

    // Time difference in seconds
    $sec     = $diff;

    // Convert time difference in minutes
    $min     = round($diff / 60);

    // Convert time difference in hours
    $hrs     = round($diff / 3600);

    // Convert time difference in days
    $days     = round($diff / 86400);

    // Convert time difference in weeks
    $weeks     = round($diff / 604800);

    // Convert time difference in months
    $mnths     = round($diff / 2600640);

    // Convert time difference in years
    $yrs     = round($diff / 31207680);

    // Check for seconds
    if ($sec <= 60) {
        $timeAgo = "$sec " . trans('seconds_ago');
    }
    // Check for minutes
    else if ($min <= 60) {
        if ($min == 1) {
            $timeAgo =  trans('timeago.one_minute_ago');
        } else {
            $timeAgo =  "$min " . trans('timeago.minutes_ago');
        }
    }
    // Check for hours
    else if ($hrs <= 24) {
        if ($hrs == 1) {
            $timeAgo =  trans('timeago.an_hour_ago');
        } else {
            $timeAgo =  "$hrs " . trans('timeago.hours_ago');
        }
    }
    // Check for days
    else if ($days <= 7) {
        if ($days == 1) {
            $timeAgo =  trans('timeago.yesterday');
        } else {
            $timeAgo =  "$days " . trans('timeago.days_ago');
        }
    }
    // Check for weeks
    else if ($weeks <= 4.3) {
        if ($weeks == 1) {
            $timeAgo =  trans('timeago.a_week_ago');
        } else {
            $timeAgo =  "$weeks " . trans('timeago.weeks_ago');
        }
    }
    // Check for months
    else if ($mnths <= 12) {
        if ($mnths == 1) {
            $timeAgo =  trans('timeago.a_month_ago');
        } else {
            $timeAgo =  "$mnths " . trans('timeago.months_ago');
        }
    }
    // Check for years
    else {
        if ($yrs == 1) {
            $timeAgo =  trans('timeago.one_year_ago');
        } else {
            $timeAgo =  "$yrs " . trans('timeago.years_ago');
        }
    }

    return $timeAgo;
}
