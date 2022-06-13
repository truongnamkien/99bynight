<?php

namespace Sluggable\Utility;

use Cake\Utility\Inflector;
use Cake\Utility\Text;

class Slug
{
    /**
     * Turns a string (and optionally a dynamic, data-injected string) into a slugged value
     * @param $pattern string a simple string (e.g. 'slug me') or Text::insert-friendly string (e.g. ':id-:name')
     * @param $data mixed an Array or Entity of data to Text::insert inject into $pattern
     * @param $replacement string the character to replace non-slug-friendly characters with (default '-')
     * @return string the slugged string
     */
    public static function generate($pattern, $data = [], $replacement = '-')
    {
        # if given an Entity object, covert it to a hydrated array
        $data = ($data instanceof \Cake\ORM\Entity) ? json_decode(json_encode($data->jsonSerialize()), true) : $data;

        # build the slug
        $value = Text::insert($pattern, $data);           # inject data into pattern (if applicable)
        $value = self::replaceVietnamese($value);         # replace vietnamese
        $value = Text::slug($value, $replacement);   # slug it
        $value = strtolower($value);                      # convert to lowercase

        return $value;
    }

    public static function replaceVietnamese($value) {
        $words = [
            'à' => 'a', 'á' => 'a', 'ạ' => 'a', 'ả' => 'a', 'ã' => 'a',
            'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ậ' => 'a', 'ẩ' => 'a',
            'ẫ' => 'a', 'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ặ' => 'a',
            'ẳ' => 'a', 'ẵ' => 'a', 'è' => 'e', 'é' => 'e', 'ẹ' => 'e',
            'ẻ' => 'e', 'ẽ' => 'e', 'ê' => 'e', 'ề' => 'e', 'ế' => 'e',
            'ệ' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ì' => 'i', 'í' => 'i',
            'ị' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ò' => 'o', 'ó' => 'o',
            'ọ' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ô' => 'o', 'ồ' => 'o',
            'ố' => 'o', 'ộ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ơ' => 'o',
            'ờ' => 'o', 'ớ' => 'o', 'ợ' => 'o', 'ở' => 'o', 'ỡ' => 'o',
            'ù' => 'u', 'ú' => 'u', 'ụ' => 'u', 'ủ' => 'u', 'ũ' => 'u',
            'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ự' => 'u', 'ử' => 'u',
            'ữ' => 'u', 'ỳ' => 'y', 'ý' => 'y', 'ỵ' => 'y', 'ỷ' => 'y',
            'ỹ' => 'y', 'đ' => 'd', 'À' => 'A', 'Á' => 'A', 'Ạ' => 'A',
            'Ả' => 'A', 'Ã' => 'A', 'Â' => 'A', 'Ầ' => 'A', 'Ấ' => 'A',
            'Ậ' => 'A', 'Ẩ' => 'A', 'Ẫ' => 'A', 'Ă' => 'A', 'Ằ' => 'A',
            'Ắ' => 'A', 'Ặ' => 'A', 'Ẳ' => 'A', 'Ẵ' => 'A', 'È' => 'E',
            'É' => 'E', 'Ẹ' => 'E', 'Ẻ' => 'E', 'Ẽ' => 'E', 'Ê' => 'E',
            'Ề' => 'E', 'Ế' => 'E', 'Ệ' => 'E', 'Ể' => 'E', 'Ễ' => 'E',
            'Ì' => 'I', 'Í' => 'I', 'Ị' => 'I', 'Ỉ' => 'I', 'Ĩ' => 'I',
            'Ò' => 'O', 'Ó' => 'O', 'Ọ' => 'O', 'Ỏ' => 'O', 'Õ' => 'O',
            'Ô' => 'O', 'Ồ' => 'O', 'Ố' => 'O', 'Ộ' => 'O', 'Ổ' => 'O',
            'Ỗ' => 'O', 'Ơ' => 'O', 'Ờ' => 'O', 'Ớ' => 'O', 'Ợ' => 'O',
            'Ở' => 'O', 'Ỡ' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Ụ' => 'U',
            'Ủ' => 'U', 'Ũ' => 'U', 'Ư' => 'U', 'Ừ' => 'U', 'Ứ' => 'U',
            'Ự' => 'U', 'Ử' => 'U', 'Ữ' => 'U', 'Ỳ' => 'Y', 'Ý' => 'Y',
            'Ỵ' => 'Y', 'Ỷ' => 'Y', 'Ỹ' => 'Y', 'Đ' => 'D'
        ];
        return str_replace(
            array_keys($words),
            array_values($words),
            $value
        );
    }
}
