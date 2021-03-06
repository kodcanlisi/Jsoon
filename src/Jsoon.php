<?php

namespace Kodcanlisi\Jsoon;

use Illuminate\Support\Facades\DB;
use Kodcanlisi\Jsoon\JsoonInterface;

class Jsoon implements JsoonInterface
{

    public static $size;
    public $JSON;
    public $prop = [];
    public $settings = [
        'minAge' => 3,
        'maxAge' => 5,
        'upper' => [],
        'lower' => [],
        'db' => [
            'saveJson' => false,
            'table' => 'jsoon',
        ]
    ];
    const NAMES = ['jhon', 'jack', 'can'];
    const FIRST_NAME = ['black', 'white', 'blue', 'green', 'yellow'];
    const LAST_NAME = ['elephant', 'duck', 'cat', 'dog', 'bird'];
    const SPECIAL_CHAR = ["_", ".", ""];

    public static function size(int $size)
    {
        if ($size < 1) {
            throw new Exception("Size must at least 1.");
        }
        self::$size = $size;
        return new self;
    }


    public function config(array $arr)
    {
        $this->prop = $arr['prop'];
        //Get settings or use default settings
        foreach ($arr['settings'] as $key => $val) {
            $this->settings[$key] = $arr['settings'][$key];
        }
        return $this;
    }

    public function addProp(...$arr)
    {
        foreach ((array) $arr as $key => $value) {
            array_push($this->prop, $value);
        }
        return $this;
    }


    public function json()
    {
        $arr = $this->prop;
        for ($i = 0; $i < count($arr); $i++) {
            $propName = $arr[$i];
            self::push($propName);
        }
        ['saveJson' => $save, 'table' => $table] = $this->settings['db'];


        if ($save === true) {
            DB::table($table)->truncate();
            for ($v = 0; $v < self::$size; $v++) {
                DB::table($table)->insert($this->JSON[$v]);
            }
        }

        return $this->JSON;
    }



    //ADDING ARRAY
    public function push(string $prop)
    {
        $JSON = $this->JSON;
        for ($t = 0; $t < self::$size; $t++) {
            $temp = [];
            switch ($prop) {
                case 'id':
                    $JSON[$t][$prop] = $t;
                    break;

                case 'name':
                    $randomName = self::NAMES[array_rand(self::NAMES)];
                    $settings = (array) $this->settings;
                    if (in_array("name", $settings['upper'])) {
                        $JSON[$t][$prop] = strtoupper($randomName);
                    } else if (in_array("name", $settings['lower'])) {
                        $JSON[$t][$prop] = strtolower($randomName);
                    } else {
                        $JSON[$t][$prop] = $randomName;
                    }
                    break;

                case 'age':
                    $JSON[$t][$prop] = rand($this->settings['minAge'], $this->settings['maxAge']);
                    break;

                    //username prop
                case 'username':
                    $firstName = self::FIRST_NAME[array_rand(self::FIRST_NAME)];
                    $lastName = self::LAST_NAME[array_rand(self::LAST_NAME)];
                    $specialChar = self::SPECIAL_CHAR[array_rand(self::SPECIAL_CHAR)];
                    $JSON[$t][$prop] = $firstName . $specialChar . $lastName;
                    break;

                default:
                    break;
            }
        }
        $this->JSON = $JSON;
    }
    //ADDING ARRAY

    public static function save()
    {
        DB::table('haberlerx')->insert([
            'name' => "asd",
            'email' => '@gmail.com',
            'username' => 'password',
        ]);
        return "asd";
    }
}
