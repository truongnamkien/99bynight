<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Utility\Utils;

class Config extends Entity {

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

    const CONFIG_KEY_PHONE = 1;
    const CONFIG_KEY_EMAIL = 2;
    const CONFIG_KEY_SYSTEM_EMAIL = 3;
    const CONFIG_KEY_SYSTEM_EMAIL_PASSWORD = 4;
    const CONFIG_KEY_KEYWORD = 5;
    const CONFIG_KEY_LOCATION = 6;
    const CONFIG_KEY_DESCRIPTION_VIETNAMESE = 7;
    const CONFIG_KEY_ADDRESS_VIETNAMESE = 8;
    const CONFIG_KEY_SOCIAL_FACEBOOK = 9;

}
