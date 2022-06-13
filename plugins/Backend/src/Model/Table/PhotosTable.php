<?php

namespace Backend\Model\Table;

use App\Utility\Utils;
use Backend\Model\Entity\Photo;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class PhotosTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('photos');
        $this->setDisplayField('path');
    }

    public function validationDefault(Validator $validator) {
        $validator->integer('id')->allowEmpty('id', 'create');

        $validator->requirePresence('path', 'create')
                ->notEmpty('path');
        $validator->integer('target_id');
        return $validator;
    }

    public function updateSinglePhoto($targetId, $targetType, $field, $path) {
        $record = $this->getSinglePhoto($targetId, $targetType, $field);
        if (empty($record)) {
            $record = $this->newEntity([
                'target_id' => $targetId,
                'target_type' => $targetType,
                'field' => $field,
            ]);
        }
        $record->path = $path;
        $this->save($record);
        return $record;
    }

    public function getSinglePhoto($targetId, $targetType, $field) {
        $keyFields = [
            'target_id' => $targetId,
            'target_type' => $targetType,
            'field' => $field,
        ];
        $record = $this->find('all', [
                    'conditions' => $keyFields,
                ])->first();
        return $record;
    }

    public function updateListPhoto($targetId, $targetType, $field, $photoPathList) {
        if (is_string($photoPathList)) {
            $photoPathList = [$photoPathList];
        }
        foreach ($photoPathList as $photoPath) {
            $record = $this->newEntity([
                'target_id' => $targetId,
                'target_type' => $targetType,
                'field' => $field,
                'path' => $photoPath,
            ]);
            $this->save($record);
        }
        return $this->getListPhoto($targetId, $targetType, $field);
    }

    public function getListPhoto($targetId, $targetType, $field) {
        $keyFields = [
            'target_id' => $targetId,
            'target_type' => $targetType,
            'field' => $field,
        ];
        $record = $this->find('all', [
                    'conditions' => $keyFields,
                ])->toArray();
        return $record;
    }

    public function delete(EntityInterface $entity, $options = array()) {
        @unlink(WWW_ROOT . $entity->path);
        return parent::delete($entity, $options);
    }

    public function deleteAll($conditions) {
        $photoList = $this->find('all', [
                    'conditions' => $conditions,
                ])->toArray();
        foreach ($photoList as $entity) {
            @unlink(WWW_ROOT . $entity->path);
        }
        return parent::deleteAll($conditions);
    }

}
