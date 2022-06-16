<?php

namespace App\Model\Table;

use App\Model\Entity\ProductCategory;
use App\Utility\Utils;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ProductCategoriesTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->setTable('product_categories');

        $this->hasMany('Products', [
            'className' => 'Products',
            'foreignKey' => 'category_id',
        ]);
        $languageList = Configure::read('LanguageList');
        foreach ($languageList as $languageCode => $languageLabel) {
            $this->hasOne('Title' . $languageLabel, [
                'className' => 'Backend.LanguageContents',
                'foreignKey' => 'target_id',
                'conditions' => [
                    'Title' . $languageLabel . '.language' => $languageCode,
                    'Title' . $languageLabel . '.target_type' => 'ProductCategories',
                    'Title' . $languageLabel . '.field' => 'title',
                ]
            ]);
            $this->hasOne('CategoryTitle' . $languageLabel, [
                'className' => 'Backend.LanguageContents',
                'foreignKey' => 'target_id',
                'conditions' => [
                    'CategoryTitle' . $languageLabel . '.language' => $languageCode,
                    'CategoryTitle' . $languageLabel . '.target_type' => 'ProductCategories',
                    'CategoryTitle' . $languageLabel . '.field' => 'title',
                ]
            ]);
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

    public function delete(EntityInterface $entity, $options = array()) {
        Utils::useTables($this, [
            'Products',
        ]);
        $this->Products->updateAll([
            'category_id' => 0,
                ], [
            'category_id' => $entity->id,
        ]);
        return parent::delete($entity, $options);
    }

}
