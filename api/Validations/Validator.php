<?php


namespace Movies\Validations;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class Validator {
    private static $errors = [];
    public static function validate($request, array $rules) {
        foreach ($rules as $field => $rule) {
            $param = $request->getAttribute($field) ?? $request->getParam($field);
            try {
              $rule->setName(ucfirst($field))->assert($param);
            } catch (ValidationException $e) {
                self::$errors[$field] = $e->getMessages();
            }
        }
        return empty(self::$errors);
    }

    public static function validateUser($request) {
        $rules = [
            'name' => v::notEmpty(),
            'username' => v::noWhitespace()->notEmpty()->alnum(),
            'password' => v::noWhitespace()->notEmpty(),
            //'apikey' => v::notEmpty(),
        ];

        return self::validate($request, $rules);
    }
    public static function getErrors() {
        return self::$errors;
    }
    }