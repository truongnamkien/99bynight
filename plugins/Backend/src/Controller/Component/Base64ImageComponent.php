<?php

namespace Backend\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use App\Utility\Utils;

class Base64ImageComponent extends Component {

    public function uploadBase64($photoData, $folderPath = false, $type = false) {
        if (empty($photoData) || (!empty($type) && !in_array($type, ['png', 'jpg']))) {
            return '';
        }
        $photoData = trim(str_replace('\/', '/', $photoData));
        $photoData = trim(str_replace('\r', '', $photoData));
        $photoData = trim(str_replace('\n', '', $photoData));
        $photoData = explode(';', $photoData);
        if (empty($photoData) || empty($photoData[1])) {
            return '';
        }
        if (empty($type)) {
            if (strpos($photoData[0], 'jpeg') !== false) {
                $type = 'jpg';
            } else if (strpos($photoData[0], 'jpg') !== false) {
                $type = 'jpg';
            } else if (strpos($photoData[0], 'png') !== false) {
                $type = 'png';
            } else {
                return false;
            }
        }
        $photoData = explode(',', $photoData[1]);
        if (empty($photoData) || empty($photoData[1])) {
            return '';
        }
        $photoData = $photoData[1];
        $photoData = base64_decode($photoData);
        if (empty($folderPath)) {
            $photoPath = Configure::read('uploadFolder');
            if (empty($photoPath)) {
                $photoPath = WWW_ROOT . 'uploads/photo/';
            }
            if (strpos($photoPath, WWW_ROOT) === false) {
                $photoPath = WWW_ROOT . $photoPath;
            }
        } else {
            $photoPath = $folderPath;
        }
        $dir = $photoPath;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
            chmod($dir, 0777);
        }
        $filename = time() . '_' . uniqid() . '.' . $type;
        file_put_contents($photoPath . $filename, $photoData);
        if ($type == 'png') {
            $photo = imagecreatefrompng($photoPath . $filename);
        } elseif ($type == 'jpg') {
            $photo = imagecreatefromjpeg($photoPath . $filename);
        }
        if (!$photo) {
            return false;
        }
        return $photoPath . $filename;
    }

}
