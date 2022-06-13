<?php

namespace App\Model\Table;

use App\Model\Entity\Contact;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ContactsTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('contacts');
        $this->setDisplayField('fullname');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator) {
        $validator->integer('id')->allowEmpty('id', 'create');
        $validator->email('email', false, sprintf(__('Please input %s!'), __('Email')))
                ->notEmpty('email', sprintf(__('Please input %s!'), __('Email')));

        $validator->requirePresence('fullname', 'create', sprintf(__('Please input %s!'), __('Fullname')))
                ->notEmpty('fullname', sprintf(__('Please input %s!'), __('Fullname')));

        $validator->requirePresence('phone', 'create', sprintf(__('Please input %s!'), __('Phone')))
                ->notEmpty('phone', sprintf(__('Please input %s!'), __('Phone')));

        $validator->requirePresence('message', 'create', sprintf(__('Please input %s!'), __('Message')))
                ->notEmpty('message', sprintf(__('Please input %s!'), __('Message')));
        $validator->requirePresence('ip_address')->notEmpty('ip_address');
        return $validator;
    }

    public function beforeMarshal(Event $event, $data) {
        if (empty($data['fullname'])) {
            unset($data['fullname']);
        }
        if (empty($data['email'])) {
            unset($data['email']);
        }
        if (empty($data['message'])) {
            unset($data['message']);
        }
    }

    public function buildRules(RulesChecker $rules) {
        $rules->add(function ($entity, $options) {
            $checkingTime = time() - Contact::CONTACT_SPAM_TRACKING_TIME;
            $lastSubmit = $this->find('all', [
                        'conditions' => [
                            'ip_address' => $entity->ip_address,
                            'created > ' => date('Y-m-d H:i:s', $checkingTime),
                        ],
                    ])->first();
            return empty($lastSubmit);
        }, ['errorField' => 'created', 'message' => __('You have submitted too recently. Please try again later!')]
        );
        return $rules;
    }

    public function getStatusList() {
        $statusList = [
            Contact::CONTACT_STATUS_NEW => __('New'),
            Contact::CONTACT_STATUS_READ => __('Read'),
            Contact::CONTACT_STATUS_REPLIED => __('Replied'),
        ];
        return $statusList;
    }

}
