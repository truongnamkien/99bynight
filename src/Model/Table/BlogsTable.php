<?php

namespace App\Model\Table;

use App\Model\Entity\Blog;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Utility\Utils;

class BlogsTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('blogs');
        $this->belongsTo('BlogCategories', [
            'className' => 'BlogCategories',
            'foreignKey' => 'category_id',
        ]);
        $this->hasOne('Thumbnails', [
            'className' => 'Backend.Photos',
            'foreignKey' => 'target_id',
            'conditions' => [
                'Thumbnails.target_type' => 'Blogs',
            ]
        ]);
        $languageList = Configure::read('LanguageList');
        foreach ($languageList as $languageCode => $languageLabel) {
            $this->hasOne('BlogTitle' . $languageLabel, [
                'className' => 'Backend.LanguageContents',
                'foreignKey' => 'target_id',
                'conditions' => [
                    'BlogTitle' . $languageLabel . '.language' => $languageCode,
                    'BlogTitle' . $languageLabel . '.target_type' => 'Blogs',
                    'BlogTitle' . $languageLabel . '.field' => 'title',
                ]
            ]);
            $this->hasOne('Title' . $languageLabel, [
                'className' => 'Backend.LanguageContents',
                'foreignKey' => 'target_id',
                'conditions' => [
                    'Title' . $languageLabel . '.language' => $languageCode,
                    'Title' . $languageLabel . '.target_type' => 'Blogs',
                    'Title' . $languageLabel . '.field' => 'title',
                ]
            ]);
            $this->hasOne('Content' . $languageLabel, [
                'className' => 'Backend.LanguageContents',
                'foreignKey' => 'target_id',
                'conditions' => [
                    'Content' . $languageLabel . '.language' => $languageCode,
                    'Content' . $languageLabel . '.target_type' => 'Blogs',
                    'Content' . $languageLabel . '.field' => 'content',
                ]
            ]);
        }
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator) {
        $validator->integer('id')->allowEmpty('id', 'create');
        $validator->integer('category_id')
                ->notEmpty('category_id')
                ->requirePresence('category_id');
        $validator->notEmpty('published_date');
        return $validator;
    }

    public function beforeMarshal(Event $event, $data) {
        if (!empty($data['published_date'])) {
            list($day, $month, $year) = explode('/', $data['published_date']);
            $data['published_date'] = strtotime("{$year}/{$month}/{$day}");
        }
        if (empty($data['published_date'])) {
            $data['published_date'] = time();
        }
        $data['published_date'] = date('Y-m-d 00:00:00', $data['published_date']);
        if (isset($data['thumbnail_id']) && empty($data['thumbnail_id'])) {
            unset($data['thumbnail_id']);
        }
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
