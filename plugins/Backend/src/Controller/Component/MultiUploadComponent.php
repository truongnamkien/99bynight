<?php

namespace Backend\Controller\Component;

use App\Utility\Utils;
use Cake\Controller\Component;

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class UploadFileForm {

    protected $fieldName = false;
    protected $uploadedFile = [];

    public function __construct($fieldName) {
        $this->fieldName = $fieldName;
    }

    /**
     * Save the file to the specified path
     * @return boolean true on success
     */
    public function save($path) {
        if (empty($_FILES[$this->fieldName])) {
            return false;
        }
        if (!isset($_FILES[$this->fieldName]['tmp_name'])) {
            return false;
        } else {
            foreach ($_FILES[$this->fieldName]['tmp_name'] as $index => $file) {
                $fileName = $_FILES[$this->fieldName]['name'][$index];
                if (move_uploaded_file($file, $path . $fileName)) {
                    $this->uploadedFile[] = $fileName;
                }
            }
            if (empty($this->uploadedFile)) {
                return false;
            }
        }
        return $this->uploadedFile;
    }

    public function getNameList() {
        return $_FILES[$this->fieldName]['name'];
    }

    public function getSizeList() {
        return $_FILES[$this->fieldName]['size'];
    }

}

/**
 * Handle uploaded file from user
 *
 * @author longpt
 *
 */
class MultiUploadComponent extends Component {

    private $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi', 'wmv');
    private $sizeLimit = 104857600; // 100 Mb
    private $file = null;
    private $uploadedFileList = [];
    private $destination = null;

    public function setAllowExtensions(array $allowedExtensions = array()) {
        // Only allow these specified extensions
        if (!empty($allowedExtensions)) {
            $allowedExtensions = array_map("strtolower", $allowedExtensions);
            $this->allowedExtensions = $allowedExtensions;
        }
    }

    public function setSizeLimit($sizeLimit = 52428800) {
        // File size limit
        $this->sizeLimit = $sizeLimit;
    }

    public function setup($fieldName) {
        if (!empty($_FILES[$fieldName])) {
            $this->file = new UploadFileForm($fieldName);
        } else {
            $this->file = false;
        }
    }

    /**
     * set the destination path for the uploaded file
     * @param string $path
     * @return UploadComponent
     */
    public function setDestination($path) {
        // add trailing slash if there isn't one
        $last_char = substr($path, -1);
        if ($last_char !== '/') {
            $path .= '/';
        }

        $this->destination = $path;

        return $this;
    }

    protected function setUploadedFiles($fileList) {
        $this->uploadedFileList = array_merge($this->uploadedFileList, $fileList);
    }

    protected function checkFileNotEmpty() {
        $sizeList = $this->file->getSizeList();
        $validate = true;
        foreach ($sizeList as $size) {
            $validate &= $size != 0;
        }
        return $validate;
    }

    protected function checkFileSizeLimit() {
        $sizeList = $this->file->getSizeList();
        $validate = true;
        foreach ($sizeList as $size) {
            $validate &= $size <= $this->sizeLimit;
        }
        return $validate;
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    public function handleUpload($fieldName, $fileLabel) {
        $this->setup($fieldName);
        if (!$this->file) {
            return [
                'error' => sprintf(__('%s: No files were uploaded.'), __($fileLabel)),
            ];
        }
        $fileList = $this->file->getNameList();
        if (empty($fileList)) {
            return [
                'error' => sprintf(__('%s: Cannot upload file.'), __($fileLabel)),
            ];
        }
        if (!$this->checkFileNotEmpty()) {
            return [
                'error' => sprintf(__('%s: File is empty.'), __($fileLabel)),
            ];
        }
        if (!$this->checkFileSizeLimit()) {
            return [
                'error' => sprintf(__('%s: File is too large.'), __($fileLabel)),
            ];
        }
        foreach ($fileList as $fileInfo) {
            $pathinfo = pathinfo($fileInfo);
            $filename = strtolower($pathinfo['filename']);
            $ext = $pathinfo['extension'];
            if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)) {
                $these = implode(', ', $this->allowedExtensions);
                $uploadedList = $this->getUploadedFiles();
                foreach ($uploadedList as $uploadedFile) {
                    @unlink($uploadedFile);
                }
                return [
                    'error' => sprintf(__('%s: File has an invalid extension, it should be one of %s'), __($fileLabel), $these)
                ];
            }
            // parse out class params to make the final destination string
            $destination = $this->destination . $filename . '.' . $ext;
            // create the destination unless otherwise set
            $dir = dirname($destination);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
        }
        $uploadedFiles = $this->file->save($this->destination);
        if (!empty($uploadedFiles)) {
            $this->setUploadedFiles($uploadedFiles);
        } else {
            return [
                'error' => __('Could not save uploaded file. The upload was cancelled, or server error encountered.')
            ];
        }
        return [
            'success' => true,
            'fileList' => $this->getUploadedFiles(),
        ];
    }

    /**
     * Return the uploaded file
     *
     */
    public function getUploadedFiles() {
        return $this->uploadedFileList;
    }

}
