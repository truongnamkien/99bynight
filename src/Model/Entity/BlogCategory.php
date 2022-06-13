<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Utility\Utils;

class BlogCategory extends Entity {

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

    public function getTitle() {
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $currentLanguage = $this->MultiLanguage->getCurrentLanguage();
        $languageField = 'title_' . $currentLanguage;
        if (empty($this->$languageField)) {
            Utils::useTables($this, ['Backend.LanguageContents']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
            $this->$languageField = $this->LanguageContents->find('all', [
                        'conditions' => [
                            'target_id' => $this->id,
                            'target_type' => 'BlogCategories',
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

}
