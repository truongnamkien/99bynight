<?php

namespace Backend\Controller\Component;

use App\Utility\Utils;
use Cake\Controller\Component;
use Cake\Core\Configure;

include APP . '/Utility/PHPExcel/IOFactory.php';

use PHPExcel_IOFactory;

class CsvComponent extends Component {

    public function write($fileName = null, $dataRows = [], $fields = []) {
        $fileHandler = fopen($fileName, "w");
        fputs($fileHandler, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

        fputcsv($fileHandler, $fields);

        foreach ($dataRows as $dataRow) {
            $row = [];

            foreach ($dataRow as $field => $value) {
                $row[$field] = $value;
            }

            fputcsv($fileHandler, $row);
        }

        fclose($fileHandler);
    }

    public function import($importFile = null) {
        $data = [];
        if (!empty($importFile)) {
            $objPHPExcel = PHPExcel_IOFactory::load($importFile);
            $data = $objPHPExcel->getActiveSheet()->toArray();
        }

        return $data;
    }

}
