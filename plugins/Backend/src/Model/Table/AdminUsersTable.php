<?php

namespace Backend\Model\Table;

use App\Utility\Utils;
use Backend\Model\Entity\AdminUser;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class AdminUsersTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('admin_users');
        $this->addBehavior('Timestamp');
        $this->setDisplayField('email');

        $this->belongsTo('AdminRoles', [
            'foreignKey' => 'admin_role_id',
            'className' => 'Backend.AdminRoles'
        ]);
    }

    public function validationDefault(Validator $validator) {
        $validator->integer('id')->allowEmpty('id', 'create');
        $validator->email('email', false, sprintf(__('Please input %s!'), __('Email')))
                ->notEmpty('email', sprintf(__('Please input %s!'), __('Email')));
        $validator->integer('admin_role_id', sprintf(__('Please select a %s!'), __('Role')))
                ->requirePresence('admin_role_id', 'create', sprintf(__('Please select a %s!'), __('Role')))
                ->notEmpty('admin_role_id', sprintf(__('Please select a %s!'), __('Role')));

        $validator->requirePresence('password', 'create', sprintf(__('Please input %s!'), __('Password')))
                ->notEmpty('password', sprintf(__('Please input %s!'), __('Password')));
        $validator->allowEmpty('password', 'update');
        $validator->requirePresence('password_confirm', 'create', sprintf(__('Please input %s!'), __('Confirm Password')))
                ->notEmpty('password_confirm', sprintf(__('Please input %s!'), __('Confirm Password')));
        $validator->allowEmpty('password_confirm', 'update');

        return $validator;
    }

    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['email'], sprintf(__('This %s has been used!'), __('Email'))));
        $rules->add(
                function ($entity, $options) {
            if (empty($entity->password_confirm) && (empty($entity->password) || !$entity->isDirty('password'))) {
                unset($entity->password_confirm);
                unset($entity->password);
                return true;
            }
            $ret = $entity->verifyPassword($entity->password_confirm);
            unset($entity->password_confirm);
            return $ret;
        }, ['errorField' => 'password_confirm', 'message' => __('The Confirm Password does not match with the Password.')]
        );
        return $rules;
    }

    public function beforeMarshal(Event $event, $data) {
        $data['email'] = trim($data['email']);
    }

    public function getStatusList() {
        return [
            ACTIVE => [
                'label' => __('Active'),
                'iconClass' => 'success',
            ],
            INACTIVE => [
                'label' => __('Temporary Locked'),
                'iconClass' => 'danger',
            ],
            AdminUser::STATUS_SUSPEND => [
                'label' => __('Suspended'),
                'iconClass' => 'danger',
            ],
        ];
    }

}
