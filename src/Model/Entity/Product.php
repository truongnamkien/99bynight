<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Utility\Utils;

class Product extends Entity {

    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    protected function _getDisplayField() {
        return $this->getTitle();
    }

    protected function _setDisplayField($name) {
        $this->cache_name = $name;
        return $this->cache_name;
    }

    public function getTitle($languageCode = false, $currentLanguage = false) {
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        if ($currentLanguage === false) {
            $currentLanguage = $this->MultiLanguage->getCurrentLanguage();
        }
        if ($languageCode === false) {
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        $languageField = 'title_' . $currentLanguage;
        if (empty($this->$languageField)) {
            Utils::useTables($this, ['Backend.LanguageContents']);
            $this->$languageField = $this->LanguageContents->find('all', [
                        'conditions' => [
                            'target_id' => $this->id,
                            'target_type' => 'Products',
                            'language' => $languageCode,
                            'field' => 'title',
                        ],
                    ])->first();
        }
        unset($this->MultiLanguage);
        unset($this->LanguageContents);
        if (!empty($this->$languageField)) {
            return $this->$languageField->content;
        }
        return '';
    }

    public function getDescription() {
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $currentLanguage = $this->MultiLanguage->getCurrentLanguage();
        $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        $languageField = 'description_' . $currentLanguage;
        if (empty($this->$languageField)) {
            Utils::useTables($this, ['Backend.LanguageContents']);
            $this->$languageField = $this->LanguageContents->find('all', [
                        'conditions' => [
                            'target_id' => $this->id,
                            'target_type' => 'Products',
                            'language' => $languageCode,
                            'field' => 'description',
                        ],
                    ])->first();
        }
        unset($this->MultiLanguage);
        unset($this->LanguageContents);
        if (!empty($this->$languageField)) {
            return $this->$languageField->content;
        }
        return '';
    }

}
