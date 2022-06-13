<?php

namespace Backend\Model\Table;

use App\Utility\Utils;
use Backend\Model\Entity\MultiPhoto;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class MultiPhotosTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('multi_photos');
        $this->setPrimaryKey('id');
    }

    public function validationDefault(Validator $validator) {
        $validator->integer('id')->allowEmpty('id', 'create');

        $validator->requirePresence('path', 'create')->notEmpty('path');
        $validator->requirePresence('target_id', 'create')->notEmpty('target_id');
        $validator->requirePresence('target_type', 'create')->notEmpty('target_type');
        return $validator;
    }

}
