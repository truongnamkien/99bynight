<?php

namespace Backend\Model\Table;

use App\Utility\Utils;
use Backend\Model\Entity\AdminPermission;
use Backend\Model\Entity\AdminRole;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class AdminRolesTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('admin_roles');
        $this->addBehavior('Timestamp');
        $this->setDisplayField('name');

        $this->hasMany('AdminUsers', [
            'foreignKey' => 'admin_role_id',
            'className' => 'Backend.AdminUsers'
        ]);
    }

    public function validationDefault(Validator $validator) {
        $validator->integer('id')
                ->allowEmpty('id', 'create');
        $validator->requirePresence('name', 'create', sprintf(__('Please input %s!'), __('Role Name')))
                ->notEmpty('name', sprintf(__('Please input %s!'), __('Role Name')));
        return $validator;
    }

    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['name'], sprintf(__('This %s has been used!'), __('Role Name'))));
        return $rules;
    }

    public function beforeMarshal(Event $event, $data) {
        $data['name'] = trim($data['name']);
    }

    public function delete(EntityInterface $entity, $options = array()) {
        if ($entity->id == AdminRole::ROLE_SUPER_ADMIN_ID) {
            return false;
        }
        Utils::useTables($this, [
            'Backend.AdminPermissions',
            'Backend.AdminUsers',
        ]);
        $this->AdminUsers->updateAll([
            'admin_role_id' => 0,
                ], [
            'admin_role_id' => $entity->id,
        ]);
        $this->AdminPermissions->deleteAll([
            'AdminPermissions.admin_role_id' => $entity->id,
        ]);

        return parent::delete($entity, $options);
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
