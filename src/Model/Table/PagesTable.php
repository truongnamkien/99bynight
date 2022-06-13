<?php

namespace App\Model\Table;

use App\Model\Entity\Page;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Utility\Utils;

class PagesTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->setTable('pages');
        $languageList = Configure::read('LanguageList');
        foreach ($languageList as $languageCode => $languageLabel) {
            $this->hasOne('PageTitle' . $languageLabel, [
                'className' => 'Backend.LanguageContents',
                'foreignKey' => 'target_id',
                'conditions' => [
                    'PageTitle' . $languageLabel . '.language' => $languageCode,
                    'PageTitle' . $languageLabel . '.target_type' => 'Pages',
                    'PageTitle' . $languageLabel . '.field' => 'title',
                ]
            ]);
            $this->hasOne('Title' . $languageLabel, [
                'className' => 'Backend.LanguageContents',
                'foreignKey' => 'target_id',
                'conditions' => [
                    'Title' . $languageLabel . '.language' => $languageCode,
                    'Title' . $languageLabel . '.target_type' => 'Pages',
                    'Title' . $languageLabel . '.field' => 'title',
                ]
            ]);
            $this->hasOne('Content' . $languageLabel, [
                'className' => 'Backend.LanguageContents',
                'foreignKey' => 'target_id',
                'conditions' => [
                    'Content' . $languageLabel . '.language' => $languageCode,
                    'Content' . $languageLabel . '.target_type' => 'Pages',
                    'Content' . $languageLabel . '.field' => 'content',
                ]
            ]);
        }
    }

    public function validationDefault(Validator $validator) {
        $validator->integer('id')->allowEmpty('id', 'create');
        return $validator;
    }

    public function getStatusList() {
        return [
            ACTIVE => [
                'label' => __('Active'),
                'iconClass' => 'success',
            ],
            INACTIVE => [
                'label' => __('Inactive'),
                'iconClass' => 'danger',
            ],
        ];
    }

    public function getActivePages() {
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $currentLanguage = $this->MultiLanguage->getCurrentLanguage();
        $pageList = $this->find('all', [
                    'conditions' => [
                        'Pages.status' => ACTIVE,
                    ],
                    'order' => [
                        'Pages.display_order' => 'asc',
                    ],
                    'contain' => [
                        'Title' . ucfirst($currentLanguage),
                        'Content' . ucfirst($currentLanguage),
                    ],
                ])->toArray();
        if (empty($pageList)) {
            return [];
        }
        return $pageList;
    }

}
