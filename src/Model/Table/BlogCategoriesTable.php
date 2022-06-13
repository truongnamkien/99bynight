<?php

namespace App\Model\Table;

use App\Model\Entity\BlogCategory;
use App\Utility\Utils;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class BlogCategoriesTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->setTable('blog_categories');

        $this->hasMany('Blogs', [
            'className' => 'Blogs',
            'foreignKey' => 'category_id',
        ]);
        $languageList = Configure::read('LanguageList');
        foreach ($languageList as $languageCode => $languageLabel) {
            $this->hasOne('Title' . $languageLabel, [
                'className' => 'Backend.LanguageContents',
                'foreignKey' => 'target_id',
                'conditions' => [
                    'Title' . $languageLabel . '.language' => $languageCode,
                    'Title' . $languageLabel . '.target_type' => 'BlogCategories',
                    'Title' . $languageLabel . '.field' => 'title',
                ]
            ]);
            $this->hasOne('CategoryTitle' . $languageLabel, [
                'className' => 'Backend.LanguageContents',
                'foreignKey' => 'target_id',
                'conditions' => [
                    'CategoryTitle' . $languageLabel . '.language' => $languageCode,
                    'CategoryTitle' . $languageLabel . '.target_type' => 'BlogCategories',
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
            'Blogs',
        ]);
        $this->Blogs->updateAll([
            'category_id' => 0,
                ], [
            'category_id' => $entity->id,
        ]);
        return parent::delete($entity, $options);
    }

}
