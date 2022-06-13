<?php

namespace Backend\Model\Entity;

use App\Utility\Utils;
use Cake\ORM\Entity;

class AdminRole extends Entity {

    const ROLE_SUPER_ADMIN_ID = 1;
    const ROLE_SUPER_ADMIN_NAME = 'Super Admin';

    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    protected function _getDisplayField() {
        return $this->_properties['name'];
    }

}
