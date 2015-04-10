<?php
/**
 * Trident Framework - PHP MVC Framework
 *
 * The MIT License (MIT)
 * Copyright (c) 2015 Ron Dadon
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Class Trident_Request
 *
 * A wrapper class for all of the client request elements such as client information (browser, ip etc),
 * get, post, cookies and uploaded files.
 */
class Trident_Request
{

    /**
     * @var Trident_Request_Post
     */
    public $post;
    /**
     * @var Trident_Request_Get
     */
    public $get;
    /**
     * @var Trident_Request_Cookie
     */
    public $cookie;
    /**
     * @var Trident_Request_Files
     */
    public $files;
    /**
     * @var string
     */
    public $from_ip;
    /**
     * @var string
     */
    public $uri;
    /**
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $browser;
    /**
     * @var string
     */
    public $platform;
    /**
     * @var string
     */
    public $browser_version;
    /**
     * @var bool
     */
    public $https;
    /**
     * @var Trident_Configuration
     */
    private $_configuration;

    /**
     * Constructor
     *
     * Initializes the class variables.
     *
     * @param Trident_Configuration $configuration
     *
     * @throws Trident_Exception
     */
    function __construct($configuration)
    {
        $this->_configuration = $configuration;
        $this->post = new Trident_Request_Post($this->_configuration);
        $this->get = new Trident_Request_Get($this->_configuration);
        $this->cookie = new Trident_Request_Cookie($this->_configuration);
        $this->files = new Trident_Request_Files($this->_configuration);
        $this->uri = htmlspecialchars($_SERVER['REQUEST_URI']);
        $this->type = filter_var($_SERVER['REQUEST_METHOD'], FILTER_SANITIZE_STRING);
        $this->https = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || $_SERVER['SERVER_PORT'] === 443;
        $this->from_ip = $this->_parse_ip();
        $agent = $this->_parse_user_agent();
        $this->browser = $agent['browser'];
        $this->platform = $agent['platform'];
        $this->browser_version = $agent['version'];
    }

    /**
     * Parses the client ip from the global server array
     *
     * @return string
     */
    private function _parse_ip()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }
        else
        {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else
            {
                if (isset($_SERVER['HTTP_X_FORWARDED']))
                {
                    $ip_address = $_SERVER['HTTP_X_FORWARDED'];
                }
                else
                {
                    if (isset($_SERVER['HTTP_FORWARDED_FOR']))
                    {
                        $ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
                    }
                    else
                    {
                        if (isset($_SERVER['HTTP_FORWARDED']))
                        {
                            $ip_address = $_SERVER['HTTP_FORWARDED'];
                        }
                        else
                        {
                            if (isset($_SERVER['REMOTE_ADDR']))
                            {
                                $ip_address = $_SERVER['REMOTE_ADDR'];
                            }
                            else
                            {
                                $ip_address = 'n/a';
                            }
                        }
                    }
                }
            }
        }
        return $ip_address;
    }

    /**
     * Parses a user agent string into its important parts
     *
     * @author Jesse G. Donat <donatj@gmail.com>
     * @link   https://github.com/donatj/PhpUserAgent
     * @link   http://donatstudios.com/PHP-Parser-HTTP_USER_AGENT
     *
     * @param string|null $user_agent User agent string to parse or null. Uses $_SERVER['HTTP_USER_AGENT'] on NULL
     *
     * @throws Trident_Exception
     * @return string[] an array with browser, version and platform keys
     */
    private function _parse_user_agent($user_agent = null)
    {
        if (is_null($user_agent))
        {
            if (isset($_SERVER['HTTP_USER_AGENT']))
            {
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
            }
            else
            {
                throw new Trident_Exception("Can't parse user agent. No user agent string is given", TRIDENT_ERROR_URI_PARSE_NA);

            }
        }

        $platform = null;
        $browser = null;
        $version = null;

        $empty = ['platform' => $platform, 'browser' => $browser, 'version' => $version];

        if (!$user_agent)
        {
            return $empty;
        }

        if (preg_match('/\((.*?)\)/im', $user_agent, $parent_matches))
        {

            preg_match_all('/(?P<platform>BB\d+;|Android|CrOS|Tizen|iPhone|iPad|Linux|Macintosh|Windows(\ Phone)?|Silk|linux-gnu|BlackBerry|PlayBook|(New\ )?Nintendo\ (WiiU?|3DS)|Xbox(\ One)?)
				(?:\ [^;]*)?
				(?:;|$)/imx', $parent_matches[1], $result, PREG_PATTERN_ORDER);

            $priority = ['Android', 'Xbox One', 'Xbox', 'Tizen'];
            $result['platform'] = array_unique($result['platform']);
            if (count($result['platform']) > 1)
            {
                if ($keys = array_intersect($priority, $result['platform']))
                {
                    $platform = reset($keys);
                }
                else
                {
                    $platform = $result['platform'][0];
                }
            }
            elseif (isset($result['platform'][0]))
            {
                $platform = $result['platform'][0];
            }
        }

        if ($platform == 'linux-gnu')
        {
            $platform = 'Linux';
        }
        elseif ($platform == 'CrOS')
        {
            $platform = 'Chrome OS';
        }

        preg_match_all('%(?P<browser>Camino|Kindle(\ Fire\ Build)?|Firefox|Iceweasel|Safari|MSIE|Trident|AppleWebKit|TizenBrowser|Chrome|
			Vivaldi|IEMobile|Opera|OPR|Silk|Midori|Edge|CriOS|
			Baiduspider|Googlebot|YandexBot|bingbot|Lynx|Version|Wget|curl|
			NintendoBrowser|PLAYSTATION\ (\d|Vita)+)
			(?:\)?;?)
			(?:(?:[:/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix',
            $user_agent, $result, PREG_PATTERN_ORDER);

        // If nothing matched, return null (to avoid undefined index errors)
        if (!isset($result['browser'][0]) || !isset($result['version'][0]))
        {
            if (!$platform && preg_match('%^(?!Mozilla)(?P<browser>[A-Z0-9\-]+)(/(?P<version>[0-9A-Z.]+))?([;| ]\ ?.*)?$%ix', $user_agent, $result)
            )
            {
                return ['platform' => null, 'browser' => $result['browser'], 'version' => isset($result['version']) ? $result['version'] ?: null : null];
            }

            return $empty;
        }

        if (preg_match('/rv:(?P<version>[0-9A-Z.]+)/si', $user_agent, $rv_result))
        {
            $rv_result = $rv_result['version'];
        }

        $browser = $result['browser'][0];
        $version = $result['version'][0];

        $find = function ($search, &$key) use ($result)
        {
            $x_key = array_search(strtolower($search), array_map('strtolower', $result['browser']));
            if ($x_key !== false)
            {
                $key = $x_key;

                return true;
            }

            return false;
        };

        $key = 0;
        $e_key = 0;
        if ($browser == 'Iceweasel')
        {
            $browser = 'Firefox';
        }
        elseif ($find('Playstation Vita', $key))
        {
            $platform = 'PlayStation Vita';
            $browser = 'Browser';
        }
        elseif ($find('Kindle Fire Build', $key) || $find('Silk', $key))
        {
            $browser = $result['browser'][$key] == 'Silk' ? 'Silk' : 'Kindle';
            $platform = 'Kindle Fire';
            if (!($version = $result['version'][$key]) || !is_numeric($version[0]))
            {
                $version = $result['version'][array_search('Version', $result['browser'])];
            }
        }
        elseif ($find('NintendoBrowser', $key) || $platform == 'Nintendo 3DS')
        {
            $browser = 'NintendoBrowser';
            $version = $result['version'][$key];
        }
        elseif ($find('Kindle', $key))
        {
            $browser = $result['browser'][$key];
            $platform = 'Kindle';
            $version = $result['version'][$key];
        }
        elseif ($find('OPR', $key))
        {
            $browser = 'Opera Next';
            $version = $result['version'][$key];
        }
        elseif ($find('Opera', $key))
        {
            $browser = 'Opera';
            $find('Version', $key);
            $version = $result['version'][$key];
        }
        elseif ($find('Midori', $key))
        {
            $browser = 'Midori';
            $version = $result['version'][$key];
        }
        elseif ($browser == 'MSIE' || ($rv_result && $find('Trident', $key)) || $find('Edge', $e_key))
        {
            $browser = 'MSIE';
            if ($find('IEMobile', $key))
            {
                $browser = 'IEMobile';
                $version = $result['version'][$key];
            }
            elseif ($e_key)
            {
                $version = $result['version'][$e_key];
            }
            else
            {
                $version = $rv_result ?: $result['version'][$key];
            }
        }
        elseif ($find('Vivaldi', $key))
        {
            $browser = 'Vivaldi';
            $version = $result['version'][$key];
        }
        elseif ($find('Chrome', $key) || $find('CriOS', $key))
        {
            $browser = 'Chrome';
            $version = $result['version'][$key];
        }
        elseif ($browser == 'AppleWebKit')
        {
            if (($platform == 'Android' && !($key = 0)))
            {
                $browser = 'Android Browser';
            }
            elseif (strpos($platform, 'BB') === 0)
            {
                $browser = 'BlackBerry Browser';
                $platform = 'BlackBerry';
            }
            elseif ($platform == 'BlackBerry' || $platform == 'PlayBook')
            {
                $browser = 'BlackBerry Browser';
            }
            elseif ($find('Safari', $key))
            {
                $browser = 'Safari';
            }
            elseif ($find('TizenBrowser', $key))
            {
                $browser = 'TizenBrowser';
            }

            $find('Version', $key);

            $version = $result['version'][$key];
        }
        elseif ($key = preg_grep('/playstation \d/i', array_map('strtolower', $result['browser'])))
        {
            $key = reset($key);

            $platform = 'PlayStation ' . preg_replace('/[^\d]/i', '', $key);
            $browser = 'NetFront';
        }

        return ['platform' => $platform ?: null, 'browser' => $browser ?: null, 'version' => $version ?: null];
    }
}