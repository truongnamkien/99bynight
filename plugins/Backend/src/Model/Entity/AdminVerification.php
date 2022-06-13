<?php

namespace Backend\Model\Entity;

use App\Utility\Utils;
use Cake\ORM\Entity;

class AdminVerification extends Entity {

    const EXPIRED_TIME = 21600; // 6h
    const CODE_LENGTH = 20;

    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    protected function _getDisplayField() {
        return $this->_properties['code'];
    }

}
