<?php

namespace App\Model\Table;

use App\Model\Entity\Banner;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class BannersTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('banners');
        $languageList = Configure::read('LanguageList');
        foreach ($languageList as $languageCode => $languageLabel) {
            $this->hasOne('BannerTitle' . $languageLabel, [
                'className' => 'Backend.LanguageContents',
                'foreignKey' => 'target_id',
                'conditions' => [
                    'BannerTitle' . $languageLabel . '.language' => $languageCode,
                    'BannerTitle' . $languageLabel . '.target_type' => 'Banners',
                    'BannerTitle' . $languageLabel . '.field' => 'title',
                ]
            ]);
            $this->hasOne('Brief' . $languageLabel, [
                'className' => 'Backend.LanguageContents',
                'foreignKey' => 'target_id',
                'conditions' => [
                    'Brief' . $languageLabel . '.language' => $languageCode,
                    'Brief' . $languageLabel . '.target_type' => 'Banners',
                    'Brief' . $languageLabel . '.field' => 'brief',
                ]
            ]);
        }
        $this->hasOne('Photos', [
            'className' => 'Backend.Photos',
            'foreignKey' => 'target_id',
            'conditions' => [
                'Photos.target_type' => 'Banners',
            ]
        ]);
    }

    public function validationDefault(Validator $validator) {
        $validator->integer('id')->allowEmpty('id', 'create');
        $validator->integer('picture_id')->notEmpty('picture_id');
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

    public function getDefaultPositionList() {
        return [
            Banner::BANNER_POSITION_HOME => [
                'label' => __('Home'),
                'iconClass' => 'success',
            ],
        ];
    }

    public function getPositionList(Entity $currentBanner = null) {
        $defaultPositions = $this->getDefaultPositionList();
        $bannerList = $this->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'position'
                ])->toArray();
        $checkingList = [
            Banner::BANNER_POSITION_HOME => true,
        ];
        foreach ($bannerList as $position) {
            if (!isset($checkingList[$position]) && (empty($currentBanner) || $position !== $currentBanner->position)) {
                unset($defaultPositions[$position]);
            }
        }
        return $defaultPositions;
    }

}
