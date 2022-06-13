<?php

namespace App\Utility;

use Backend\Controller\CoreController;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use InvalidArgumentException;

class Utils {

    public static function checkIPInWhiteList($IPList = []) {
        if (empty($IPList)) {
            return false;
        }
        $userIP = self::getUserIP();
        foreach ($IPList as $ip) {
            if (strcmp($userIP, $ip['ip']) == 0) {
                return true;
            }
        }
        return false;
    }

    public static function getUserIP() {
        $ip = null;

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * remove leading, trailing
     * and "more than one" space in between words
     *
     * @param string $string The string used to trim
     * @return string
     */
    public static function trimSpace($string) {
        $pat[0] = "/^\s+/u";
        $pat[1] = "/\s{2,}/u";
        $pat[2] = "/\s+\$/u";
        $rep[0] = "";
        $rep[1] = " ";
        $rep[2] = "";
        $str = preg_replace($pat, $rep, $string);
        return $str;
    }

    /**
     * clean free text
     * @param string $str The string used to clean up
     * @return string
     */
    public static function cleanupFreeText($str) {
        // remove html tags
        // trim spaces
        return Utils::trimSpace(strip_tags($str));
    }

    /**
     * Load instance of table
     * @param object $obj instance of Controller/Component
     * @param array $tableNames list of tables in format array('table1', 'table2')
     * @throws InvalidArgumentException
     * @throws \Cake\ORM\Exception\MissingTableClassException
     * @return void
     */
    public static function useTables($obj, $tableNames = []) {
        if (!is_array($tableNames) || empty($tableNames)) {
            throw new InvalidArgumentException(__('Param $tableNames must be array and not empty.'));
        }

        foreach ($tableNames as $tableName) {
            $controller = CoreController::$_instance;
            if (empty($controller)) {
                $controller = new CoreController();
                $controller->initialize();
            }
            list(, $prop) = pluginSplit($tableName);
            $obj->$prop = $controller->loadTable($tableName);
        }
    }

    /**
     * Load instance of component
     * @param object $obj instance of Controller/Component
     * @param array $componentNames list of components in format array('component1', 'component2')
     * @throws InvalidArgumentException
     * @throws MissingComponentException
     * @return void
     */
    public static function useComponents($obj, $componentNames = []) {
        if (!is_array($componentNames) || empty($componentNames)) {
            throw new InvalidArgumentException(__('Param $componentNames must be array and not empty.'));
        }
        Utils::cacheExistedComponents($obj);
        foreach ($componentNames as $componentName) {
            $controller = CoreController::$_instance;
            if (empty($controller)) {
                $controller = new CoreController();
                $controller->initialize();
            }
            list(, $prop) = pluginSplit($componentName);
            $obj->$prop = $controller->loadComponent($componentName);
        }
    }

    /**
     * cache existed components
     * @param object $obj instance of Component
     * @return void
     */
    public static function cacheExistedComponents($obj) {
        if (!isset($obj->hasLoadComponents) && isset($obj->components) && is_array($obj->components) && !empty($obj->components)) {
            foreach ($obj->components as $key => $val) {
                $componentName = ($val == null ? $key : $val);
                if (is_string($componentName) && !empty($componentName) && isset($obj->$componentName) && !isset(CoreController::$_globalObjects['components'][$componentName])) {
                    CoreController::$_globalObjects['components'][$componentName] = $obj->$componentName;
                }
            }
            $obj->hasLoadComponents = true;
        }
    }

    /**
     * convert sql date to date
     * @param string $sqlDate The string which need to convert
     * @param string $format Format string
     * @param string $default Default string need to convert
     * @return string a formatted date string
     */
    public static function sqlDateToDate($sqlDate, $format = 'Y-m-d', $default = '') {
        if (empty($sqlDate)) {
            return $default;
        }

        list ($year, $month, $day) = explode('-', $sqlDate);
        return date($format, mktime(0, 0, 0, (int) $month, (int) $day, (int) $year));
    }

    /**
     * convert string to date time
     * @param string $s The string which need to format
     * @param string $format Format string
     * @return string a formatted date string
     */
    public static function convertToDate($s, $format = 'Y-m-d') {
        return date($format, strtotime($s));
    }

    /**
     * convert string to number
     * @param string $s The number being formatted.
     * @param string $decimals Sets the number of decimal points.
     * @return string a formatted date string
     */
    public static function stringToNumber($s, $decimals = 2) {
        return number_format((float) $s, $decimals, '.', '');
    }

    public static function sanitizeText($str, $allow = array('&', ';', '#', '%', '(', ')', '/', '?', '!')) {
        $str = self::stripScripts($str);
        $str = strip_tags($str);
        $allow = array_merge($allow, array(' ', '-', '_', '.', '@', '$', "'", ',', ':'));

        // Allow utf8
        $utf8Array = array('À', 'Á', 'Ã', 'Ả', 'Ạ', 'Â', 'Ấ', 'Ầ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ẳ', 'Ẵ', 'Ặ', 'È', 'É', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ', 'Ì', 'Í', 'Ị', 'Ĩ', 'Ỉ', 'Ð', 'Ò', 'Ó', 'Ọ', 'Ô', 'Ồ', 'Ố', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ờ', 'Ớ', 'Ở', 'Ỡ', 'Ợ', 'Ù', 'Ú', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ừ', 'Ứ', 'Ử', 'Ữ', 'Ự', 'Ỳ', 'Ý', 'Ỷ', 'Ỹ', 'Ỵ', 'à', 'á', 'ã', 'ả', 'ạ', 'â', 'ấ', 'ầ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ẳ', 'ẵ', 'ặ', 'è', 'é', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ', 'ì', 'í', 'ị', 'ĩ', 'ỉ', 'đ', 'ò', 'ó', 'ọ', 'ô', 'ồ', 'ố', 'ổ', 'ỗ', 'ộ', 'ơ', 'ờ', 'ớ', 'ở', 'ỡ', 'ợ', 'ù', 'ú', 'ủ', 'ũ', 'ụ', 'ư', 'ừ', 'ứ', 'ử', 'ữ', 'ự', 'ỳ', 'ý', 'ỷ', 'ỹ', 'ỵ');
        $allow = $allow + $utf8Array;

        $str = self::paranoid($str, $allow);
        return $str;
    }

    /**
     * Removes any non-alphanumeric characters.
     *
     * @param string $string String to sanitize
     * @param array $allowed An array of additional characters that are not to be removed.
     * @return string Sanitized string
     */
    public static function paranoid($string, $allowed = array()) {
        $allow = null;
        if (!empty($allowed)) {
            foreach ($allowed as $value) {
                $allow .= "\\$value";
            }
        }

        if (!is_array($string)) {
            return preg_replace("#/[^{$allow}a-zA-Z0-9]/#i", '', $string);
        }

        $cleaned = array();
        foreach ($string as $key => $clean) {
            $cleaned[$key] = preg_replace("#/[^{$allow}a-zA-Z0-9]/#i", '', $clean);
        }

        return $cleaned;
    }

    public static function encode($string, $options = array()) {
        static $defaultCharset = false;
        if ($defaultCharset === false) {
            $defaultCharset = 'UTF-8';
        }
        $defaults = array(
            'charset' => $defaultCharset,
            'quotes' => ENT_QUOTES,
            'double' => true
        );

        $options += $defaults;

        return htmlspecialchars($string, $options['quotes'], $options['charset'], $options['double']);
    }

    /**
     * Strips extra whitespace from output
     *
     * @param string $str String to sanitize
     * @return string whitespace sanitized string
     */
    public static function stripWhitespace($str) {
        return preg_replace('/\s{2,}/u', ' ', preg_replace('/[\n\r\t]+/', '', $str));
    }

    /**
     * Strips image tags from output
     *
     * @param string $str String to sanitize
     * @return string Sting with images stripped.
     */
    public static function stripImages($str) {
        $preg = array(
            '/(<a[^>]*>)(<img[^>]+alt=")([^"]*)("[^>]*>)(<\/a>)/i' => '$1$3$5<br />',
            '/(<img[^>]+alt=")([^"]*)("[^>]*>)/i' => '$2<br />',
            '/<img[^>]*>/i' => ''
        );

        return preg_replace(array_keys($preg), array_values($preg), $str);
    }

    /**
     * Strips scripts and stylesheets from output
     *
     * @param string $str String to sanitize
     * @return string String with <link>, <img>, <script>, <style> elements and html comments removed.
     */
    public static function stripScripts($str) {
        $regex = '/(<link[^>]+rel="[^"]*stylesheet"[^>]*>|' .
                '<img[^>]*>|style="[^"]*")|' .
                '<script[^>]*>.*?<\/script>|' .
                '<style[^>]*>.*?<\/style>|' .
                '<!--.*?-->/is';
        return preg_replace($regex, '', $str);
    }

    /**
     * Strips extra whitespace, images, scripts and stylesheets from output
     *
     * @param string $str String to sanitize
     * @return string sanitized string
     */
    public static function stripAll($str) {
        return self::stripScripts(
                        self::stripImages(
                                self::stripWhitespace($str)
                        )
        );
    }

    /**
     * Strips the specified tags from output. First parameter is string from
     * where to remove tags. All subsequent parameters are tags.
     *
     * Ex.`$clean = Sanitize::stripTags($dirty, 'b', 'p', 'div');`
     *
     * Will remove all `<b>`, `<p>`, and `<div>` tags from the $dirty string.
     *
     * @param string $str String to sanitize.
     * @return string sanitized String
     */
    public static function stripTags($str) {
        $params = func_get_args();

        for ($i = 1, $count = count($params); $i < $count; $i++) {
            $str = preg_replace('/<' . $params[$i] . '\b[^>]*>/i', '', $str);
            $str = preg_replace('/<\/' . $params[$i] . '[^>]*>/i', '', $str);
        }
        return $str;
    }

    /**
     * Sanitizes given array or value for safe input. Use the options to specify
     * the connection to use, and what filters should be applied (with a boolean
     * value). Valid filters:
     *
     * - odd_spaces - removes any non space whitespace characters
     * - dollar - Escape `$` with `\$`
     * - carriage - Remove `\r`
     * - encode - allow utf8
     * - text - sanitize text
     * - backslash -
     * - remove_html - Strip HTML with strip_tags. `encode` must be true for this option to work.
     *
     * @param string|array $data Data to sanitize
     * @param string|array $options If string, DB connection being used, otherwise set of options
     * @return mixed Sanitized data
     */
    public static function clean($data, $options = array()) {
        if (empty($data)) {
            return $data;
        }

        if (!is_array($options)) {
            $options = array('connection' => $options);
        }

        $options += array(
            'odd_spaces' => true,
            'encode' => true,
            'dollar' => true,
            'carriage' => true,
            'text' => true,
            'backslash' => true
        );

        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $data[$key] = self::clean($val, $options);
            }
            return $data;
        }

        if ($options['odd_spaces']) {
            $data = str_replace(chr(0xCA), '', $data);
        }
        if ($options['carriage']) {
            $data = str_replace("\r", "", $data);
        }
        if ($options['text']) {
            $data = self::sanitizeText($data);
        }
        if ($options['encode']) {
            $data = self::encode($data);
        }
        if ($options['dollar']) {
            $data = str_replace("\\\$", "$", $data);
        }
        if ($options['backslash']) {
            $data = preg_replace("/\\\(?!&amp;#|\?#)/", "\\", $data);
        }
        return $data;
    }

    public static function stripUnicode($str) {
        if (!$str) {
            return false;
        }
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'A' => 'À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ',
            'd' => 'đ',
            'D' => 'Đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'E' => 'È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'I' => 'Ì|Í|Ị|Ỉ|Ĩ',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'O' => 'Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'U' => 'Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'Y' => 'Ỳ|Ý|Ỵ|Ỷ|Ỹ'
        );
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return $str;
    }

    public static function shortDescription($str, $len = 536) {
        $detail = trim(html_entity_decode(strip_tags($str)));
        $splitedStr = UnicodeLib::substr($detail, 0, $len);

        return mb_strlen($str) > $len ? $splitedStr . '...' : $splitedStr;
    }

    public static function trimPhoneNumber($str, $length = -4, $character = "*") {
        return str_pad(substr($str, $length), strlen($str), $character, STR_PAD_LEFT);
    }

    public static function diffTimePast(FrozenTime $time) {
        if (empty($time)) {
            return '';
        }
        $diff = $time->diff(new \DateTime);
        $year = $diff->y;
        if ($year > 0) {
            return $year . ' ' . __($year == 1 ? 'year' : 'years') . ' ' . __('ago');
        }
        $month = $diff->m;
        if ($month > 0) {
            return $month . ' ' . __($month == 1 ? 'month' : 'months') . ' ' . __('ago');
        }
        $day = $diff->d;
        if ($day > 0) {
            return $day . ' ' . __($day == 1 ? 'day' : 'days') . ' ' . __('ago');
        }
        $hour = $diff->h;
        if ($hour > 0) {
            return $hour . ' ' . __($hour == 1 ? 'hour' : 'hours') . ' ' . __('ago');
        }
        $min = $diff->i;
        if ($min > 0) {
            return $min . ' ' . __($min == 1 ? 'minute' : 'minutes') . ' ' . __('ago');
        }
        $second = $diff->s;
        if ($second > 0) {
            return $second . ' ' . __($second == 1 ? 'second' : 'seconds') . ' ' . __('ago');
        }
        return __('just now');
    }

    public static function diffTimeFuture(FrozenTime $time) {
        if (empty($time)) {
            return '';
        }
        $diff = $time->diff(new \DateTime);
        $year = $diff->y;
        if ($year > 0) {
            return __('Time left') . ': ' . $year . ' ' . __($year == 1 ? 'year' : 'years');
        }
        $month = $diff->m;
        if ($month > 0) {
            return __('Time left') . ': ' . $month . ' ' . __($month == 1 ? 'month' : 'months');
        }
        $day = $diff->d;
        if ($day > 0) {
            return __('Time left') . ': ' . $day . ' ' . __($day == 1 ? 'day' : 'days');
        }
        $hour = $diff->h;
        if ($hour > 0) {
            return __('Time left') . ': ' . $hour . ' ' . __($hour == 1 ? 'hour' : 'hours');
        }
        $min = $diff->i;
        if ($min == 0) {
            $day = 1;
        }
        return __('Time left') . ': ' . $min . ' ' . __($min == 1 ? 'minute' : 'minutes');
    }

    public static function randomString($length, $keyspace = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[rand(0, $max)];
        }
        return $str;
    }

    public static function randomNumber($length, $keyspace = '0123456789') {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[rand(0, $max)];
        }
        return $str;
    }

    public static function recurseCopy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    self::recurseCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public static function getVideoInfoFromString($string) {
        if (empty($string)) {
            return [];
        }
        $matches = [];
        // First implement just detect youtube link
        preg_match("/(?:http(?:s)?:\/\/)?(?:www\.|m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'\s>]{11})/i", $string, $matches);
        if (sizeof($matches) > 0) {
            if (isset($matches[1])) {
                $videoId = $matches[1];
                $videoSource = 'youtube';
                if ($this->isValidYoutubeVideo($videoId)) {
                    return [
                        'videoId' => $videoId,
                        'videoSource' => $videoSource,
                        'videoLink' => $matches[0],
                        'videoThumbnail' => '',
                    ];
                }
            }
        }

        // detect vine video
        $matches = array();
        preg_match("/(?:http(?:s)?:\/\/)?(?:www\.)?vine\.co\/v\/([^\?&\"'\s>]{11})/i", $string, $matches);
        if (sizeof($matches) > 0) {
            if (isset($matches[1])) {
                $videoId = $matches[1];
                $videoSource = 'vine';
                // check this video is valid
                $videoThumbnail = $this->isValidVineVideo($video_id);
                if ($videoThumbnail) {
                    return [
                        'videoId' => $videoId,
                        'videoSource' => $videoSource,
                        'videoLink' => $matches[0],
                        'videoThumbnail' => $videoThumbnail,
                    ];
                }
            }
        }
        return [];
    }

    private function isValidYoutubeVideo($videoId) {
        if (empty($videoId)) {
            return false;
        }
        $apiUrl = 'https://www.googleapis.com/youtube/v3/videos?id=' . $videoId . '&key=' . GCM_GOOGLE_API_KEY . '&part=status';
        $data = @file_get_contents($apiUrl);
        if ($data) {
            if ($oData = json_decode($data)) {
                if ($pageInfo = $oData->pageInfo) {
                    if ($totalResults = $pageInfo->totalResults) {
                        if ($totalResults > 0) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    private function isValidVineVideo($videoId) {
        if (empty($videoId)) {
            return false;
        }
        $matches = [];
        $vine = @file_get_contents(VINE_VIDEO_URL . "$videoId");
        preg_match('/property="og:image" content="(.*?)"/', $vine, $matches);
        if (isset($matches[1])) {
            $thumb = explode('?', $matches[1]);
            if (!empty($thumb[0])) {
                return $thumb[0];
            }
        }
        return false;
    }

    public static function convertTimeZone($format, $date, $timezone, $toTimezone) {
        $time = strtotime($date);
        $timezoneSections = explode(':', $timezone);
        $toTimezoneSections = explode(':', $toTimezone);
        if (count($timezoneSections) != 2 || count($toTimezoneSections) != 2) {
            return false;
        }
        $offset = (($toTimezoneSections[0] - $timezoneSections[0]) * 60 + ($toTimezoneSections[1] - $timezoneSections[1])) * 60;
        $newDate = date($format, $time + $offset);
        return $newDate;
    }

}
