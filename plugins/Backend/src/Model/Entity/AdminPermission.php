<?php

namespace Backend\Model\Entity;

use App\Utility\Utils;
use Cake\ORM\Entity;

class AdminPermission extends Entity {

    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

}
