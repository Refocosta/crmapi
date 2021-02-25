<?php namespace Core;
use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;
trait Validator
{
    public static $errors = false;

    public static function validateRequest(array $request, array $rules)
    {
        foreach ($rules as $field => $rule) {
            try {
                $rule->assert($request[$field]);
            } catch (NestedValidationException $e) {
                self::$errors = true;
            }
        }
    }

    public static function failded()
    {
        return self::$errors;
    }

}