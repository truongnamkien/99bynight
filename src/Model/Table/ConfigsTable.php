<?php

namespace App\Model\Table;

use App\Model\Entity\Config;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Validation\Validation;
use App\Utility\Utils;

/**
 * Configs Model
 *
 * @property \Cake\ORM\Association\HasMany $AdminUsers
 */
class ConfigsTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('configs');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator->integer('id')->allowEmpty('id', 'create');

        $validator->requirePresence('field')->notEmpty('field');
        $validator->requirePresence('content')->notEmpty('content');

        return $validator;
    }

    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique([
                    'field'
        ]));
        $rules->add(function ($entity, $options) {
            if (!empty($entity->content) && ($entity->field == Config::CONFIG_KEY_EMAIL || $entity->field == Config::CONFIG_KEY_SYSTEM_EMAIL)) {
                return Validation::email($entity->content);
            }
            return true;
        }, ['errorField' => 'content', 'message' => __('The value must be a valid email.')]
        );
        $rules->add(function ($entity, $options) {
            if (!empty($entity->content) && $entity->field == Config::CONFIG_KEY_SOCIAL_FACEBOOK) {
                $validate = Validation::url($entity->content);
                if ($validate) {
                    $validate = strpos($entity->content, 'facebook.com') !== false;
                }
                return $validate;
            }
            return true;
        }, ['errorField' => 'content', 'message' => __('The value must be a valid Facebook link.')]
        );
        return $rules;
    }

}
