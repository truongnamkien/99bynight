<?php

namespace App\Model\Table;

use App\Model\Entity\Product;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Utility\Utils;

class ProductsTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('products');
        $this->belongsTo('ProductCategories', [
            'className' => 'ProductCategories',
            'foreignKey' => 'category_id',
        ]);
        $languageList = Configure::read('LanguageList');
        foreach ($languageList as $languageCode => $languageLabel) {
            $this->hasOne('ProductTitle' . $languageLabel, [
                'className' => 'Backend.LanguageContents',
                'foreignKey' => 'target_id',
                'conditions' => [
                    'ProductTitle' . $languageLabel . '.language' => $languageCode,
                    'ProductTitle' . $languageLabel . '.target_type' => 'Products',
                    'ProductTitle' . $languageLabel . '.field' => 'title',
                ]
            ]);
            $this->hasOne('Title' . $languageLabel, [
                'className' => 'Backend.LanguageContents',
                'foreignKey' => 'target_id',
                'conditions' => [
                    'Title' . $languageLabel . '.language' => $languageCode,
                    'Title' . $languageLabel . '.target_type' => 'Products',
                    'Title' . $languageLabel . '.field' => 'title',
                ]
            ]);
        }
    }

    public function validationDefault(Validator $validator) {
        $validator->integer('id')->allowEmpty('id', 'create');
        $validator->integer('category_id')
                ->notEmpty('category_id')
                ->requirePresence('category_id');
        return $validator;
    }

    public function beforeMarshal(Event $event, $data) {
        if (isset($data['category_id']) && empty($data['category_id'])) {
            unset($data['category_id']);
        }
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

}
