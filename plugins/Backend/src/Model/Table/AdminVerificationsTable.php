<?php

namespace Backend\Model\Table;

use App\Utility\Utils;
use Backend\Model\Entity\AdminVerification;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class AdminVerificationsTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('admin_verifications');
        $this->setDisplayField('code');

        $this->addBehavior('Timestamp');
        $this->belongsTo('AdminUsers', [
            'className' => 'Backend.AdminUsers',
            'foreignKey' => 'admin_id',
        ]);
    }

    public function validationDefault(Validator $validator) {
        $validator->integer('id')->allowEmpty('id', 'create');
        $validator->requirePresence('admin_id');
        return $validator;
    }

    public function beforeMarshal(Event $event, $data) {
        if (isset($data['admin_id']) && empty($data['admin_id'])) {
            unset($data['admin_id']);
        }
    }

    public function generateResetCode($adminId) {
        if (empty($adminId)) {
            return false;
        }
        $code = $this->generateCode();
        $newItem = $this->newEntity([
            'admin_id' => $adminId,
            'code' => $code,
        ]);
        $this->save($newItem);
        return $newItem;
    }

    protected function generateCode() {
        do {
            $code = strtoupper(Utils::randomNumber(AdminVerification::CODE_LENGTH));
            $existed = $this->find('all', [
                        'conditions' => [
                            'code' => $code,
                        ],
                    ])->first();
        } while (!empty($existed));
        return $code;
    }

    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->existsIn(['admin_id'], 'AdminUsers'));
        return $rules;
    }

}
