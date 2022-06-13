<?php

namespace Backend\Model\Table;

use App\Utility\Utils;
use Backend\Model\Entity\AdminPermission;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class AdminPermissionsTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('admin_permissions');
        $this->setDisplayField('id');
        $this->belongsTo('AdminRoles', [
            'className' => 'Backend.AdminRoles',
            'foreignKey' => 'admin_role_id',
        ]);
    }

    public function validationDefault(Validator $validator) {
        $validator->integer('id')->allowEmpty('id', 'create');
        $validator->requirePresence('admin_role_id');
        return $validator;
    }

    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->existsIn(['admin_role_id'], 'AdminRoles'));
        return $rules;
    }

}
