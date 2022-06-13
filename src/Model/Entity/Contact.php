<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Contact extends Entity {

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

    const CONTACT_STATUS_NEW = 0;
    const CONTACT_STATUS_READ = 1;
    const CONTACT_STATUS_REPLIED = 2;

    const CONTACT_SPAM_TRACKING_TIME = 30;

    protected function _getDisplayField() {
        return $this->_properties['fullname'];
    }

}
