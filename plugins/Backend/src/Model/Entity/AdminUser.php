<?php

namespace Backend\Model\Entity;

use App\Utility\Utils;
use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

class AdminUser extends Entity {

    const STATUS_SUSPEND = 2;
    const ATTEMPT_LOGIN_TIME = 5;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    /**
     * Hash password before save
     * @param string $password password
     * @return string
     */
    protected function _setPassword($password) {
        return $this->hashPassword(trim($password));
    }

    public function verifyPassword($password) {
        $hash = new DefaultPasswordHasher();
        return $hash->check($password, $this->password);
    }

    /**
     * Hash password before save
     * @param string $password password
     * @return string
     */
    public function hashPassword($password) {
        if (strlen($password)) {
            $hash = new DefaultPasswordHasher();
            return $hash->hash($password);
        }
    }

    protected function _getDisplayField() {
        return $this->_properties['email'];
    }

}
