<?php
class dCURLClass {
    public static $timeout = 10; //seconds
    public static $followRedirects = true; //seconds
    public static $maxRedirects = 5; //seconds
    public static $includeHeaders = false; //seconds
    public static $failOnError = false;
    public static $additHeaders = array(
        //'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13',
        //'Accept: text/html,application/xhtml+xml,application/xml;text/xml;q=0.9,*/*;q=0.8',
        //'Keep-Alive: 115',
        //'Connection: keep-alive',
        //'Referer: https://shop.esetnod32.ru/',
    ); //array('Content-type: aaa', 'Content-length: 546')
    public static $debug = false;

    public static function get($path, $data = array(), $port = 0) {
        $http = curl_init($path);

        if ($http) {
            if ((int)$port > 0) {
                curl_setopt($http, CURLOPT_PORT, (int)$port);
            }

            if (is_array($data)) {
                $data = self::arrayToParams($data);
                $delimiter = (strpos($path, '?') !== false) ? '&' : '?';
                curl_setopt($http, CURLOPT_URL, $path.$delimiter.$data);
            }

            curl_setopt($http, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($http, CURLOPT_TIMEOUT, self::$timeout);
            curl_setopt($http, CURLOPT_FOLLOWLOCATION, self::$followRedirects);
            curl_setopt($http, CURLOPT_MAXREDIRS, self::$maxRedirects);
            curl_setopt($http, CURLOPT_HEADER, self::$includeHeaders);
            curl_setopt($http, CURLOPT_FAILONERROR, self::$failOnError);

            //curl_setopt($http, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            //curl_setopt($http, CURLOPT_USERPWD, 'login:pass');

            /* Remote certification */
            curl_setopt($http, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($http, CURLOPT_SSL_VERIFYHOST, 2);
            //curl_setopt($http, CURLOPT_CAINFO, '/etc/apache2/ssl/mrsoft_chain.crt');

            /* Local certification */
            //curl_setopt($http, CURLOPT_SSLCERT, '/etc/apache2/ssl/mrsoft_server.crt');
            //curl_setopt($http, CURLOPT_SSLKEY, '/etc/apache2/ssl/mrsoft_server.key');

            if (is_array(self::$additHeaders)) {
                curl_setopt($http, CURLOPT_HTTPHEADER, self::$additHeaders);
            }

            $resp = curl_exec($http);

            curl_close($http);

            return $resp;
        }

        return false;
    }

    public static function post($path, $data = '', $port = 0) {
        $http = curl_init($path);

        if ($http) {
            if ((int)$port > 0) {
                curl_setopt($http, CURLOPT_PORT, (int)$port);
            } elseif (preg_match('/^https:\/\//i', $path)) {
                curl_setopt($http, CURLOPT_PORT, 443);
            }

            curl_setopt($http, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($http, CURLOPT_TIMEOUT, self::$timeout);
            curl_setopt($http, CURLOPT_FOLLOWLOCATION, self::$followRedirects);
            curl_setopt($http, CURLOPT_MAXREDIRS, self::$maxRedirects);
            curl_setopt($http, CURLOPT_HEADER, self::$includeHeaders);
            curl_setopt($http, CURLOPT_FAILONERROR, self::$failOnError);

            curl_setopt($http, CURLOPT_POST, true);
            $data = self::arrayToParams($data);
            curl_setopt($http, CURLOPT_POSTFIELDS, $data);

            /* Remote certification */
            //curl_setopt($http, CURLOPT_SSL_VERIFYPEER, true);
            //curl_setopt($http, CURLOPT_SSL_VERIFYHOST, 2);
            //curl_setopt($http, CURLOPT_CAINFO, '/usr/share/ca-certificates/mrsoft/trusted.crt');

            /* Local certification */
            //curl_setopt($http, CURLOPT_SSLCERT, '/usr/share/ca-certificates/mrsoft/shop.crt');
            //curl_setopt($http, CURLOPT_SSLKEY, '/usr/share/ca-certificates/mrsoft/shop.key');

            /* Debug */
            if (self::$debug) {
                curl_setopt($http, CURLOPT_VERBOSE, true);
                curl_setopt($http, CURLINFO_HEADER_OUT, true);
                curl_setopt($http, CURLOPT_HEADER, true);
            }

            //curl_setopt($http, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            //curl_setopt($http, CURLOPT_USERPWD, 'usr:pwd');

            if (is_array(self::$additHeaders)) {
                curl_setopt($http, CURLOPT_HTTPHEADER, self::$additHeaders);
            }

            $resp = curl_exec($http);

            if (self::$debug) {
                var_dump(curl_getinfo($http));
            }

            curl_close($http);
            return $resp;
        }

        return false;
    }

    public static function arrayToParams($data) {
        if (is_array($data)) {
            $str = '';

            foreach ($data as $k => $v) {
                //TODO: escape
                //TODO: arrays
                $str .= $k.'='.$v.'&';
            }

            $data = $str;
        }

        return $data;
    }
}