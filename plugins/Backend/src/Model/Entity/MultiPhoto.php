<?php

namespace Backend\Model\Entity;

use App\Utility\Utils;
use Cake\ORM\Entity;

class MultiPhoto extends Entity {

    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

}
