<?php

namespace Backend\Model\Entity;

use App\Utility\Utils;
use Cake\ORM\Entity;

class Photo extends Entity {

    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

}
