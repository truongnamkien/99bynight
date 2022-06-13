<?php

namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\View;

class MinifyHelper extends Helper {

    public $helpers = ['Url'];
    public static $jsHtml = '';
    public static $jsBottomHtml = '';
    public static $cssHtml = '';

    const EXT_JS = 'js';
    const EXT_CSS = 'css';

    public function script($assets, $isBottom = false) {
        $cf = Configure::read('Minify');
        $useMinFiles = !empty($cf['min']) && $cf['min'];
        $assets = $this->_path($assets, self::EXT_JS, $useMinFiles);
        foreach ($assets as $file) {
            if ($isBottom) {
                self::$jsBottomHtml .= '<script src="' . $this->Url->build($file, true) . '"></script>';
            } else {
                self::$jsHtml .= '<script src="' . $this->Url->build($file, true) . '"></script>';
            }
        }
    }

    public function css($assets) {
        $cf = Configure::read('Minify');
        $useMinFiles = !empty($cf['min']) && $cf['min'];
        $assets = $this->_path($assets, self::EXT_CSS, $useMinFiles);
        foreach ($assets as $file) {
            self::$cssHtml .= '<link rel="stylesheet" href="' . $this->Url->build($file, true) . '"/>';
        }
    }

    protected function _path($assets, $ext, $useMinFiles) {
        if (!is_array($assets)) {
            $assets = [$assets];
        }
        $ver = Configure::read('Minify.ver');
        if (!empty($ver)) {
            $ver = '?v=' . $ver;
        }
        $ret = [];
        foreach ($assets as $asset) {
            if (strpos($asset, '.' . $ext) !== false) {
                $asset = str_replace('.' . $ext, '', $asset);
            }
            $file = str_replace('.min', '', $asset);
            if ($useMinFiles) {
                $fileList = glob(WWW_ROOT . $file . '.min.' . $ext);
                if (count($fileList) > 0) {
                    $file .= '.min';
                }
            }
            $file = $file . '.' . $ext;
            $ret[] = $file . $ver;
        }
        return $ret;
    }

    public function fetchCss() {
        return self::$cssHtml;
    }

    public function fetchJs() {
        return self::$jsHtml;
    }

    public function fetchBottomJs() {
        return self::$jsBottomHtml;
    }

}
