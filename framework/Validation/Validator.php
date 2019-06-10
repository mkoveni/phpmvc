<?php

namespace Mkoveni\Lani\Validation;

use Mkoveni\Lani\Validation\Rules\Rule;
use Mkoveni\Lani\Validation\Rules\NotBlank;
use Mkoveni\Lani\Helpers\Str;

class Validator implements ValidatorInterface
{
    protected static $defaultRules = [
        NotBlank::class
    ];

    public $registeredRules = [];

    protected $errors = [];

    /**
     * Undocumented function
     *
     * @param [type] $data
     * @param array $rules
     * @return void
     */
    public function validate($data, array $rules)
    {
        foreach($rules as $attr => $rules) {

            $value = $data[$attr];

            foreach($rules as $rule) {
                
               if($vRule = $this->getRule($rule))
               {
                    var_dump($vRule->apply($value));
               }
            }
        }
    }

    protected function addError($attribute, $message)
    {
        $this->errors[$attribute][] = $message;
    }

    public function registerRules($rules)
    {
        foreach($rules as $rule) {

            if(is_subclass_of($rule, Rule::class)) {

                $class = $rule;

                if($pos = strrpos($rule, '\\'))
                {
                    $class = substr($class, $pos+1);
                }

                $this->registeredRules[Str::snakeCase($class)] = $rule;
            }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    public static function getDefaultRules()
    {
        return static::$defaultRules;
    }

    /**
     * Undocumented function
     *
     * @param [type] $key
     * @return \Mkoveni\Lani\Validation\Rules\Rule;
     */
    protected function getRule($key) {

        if(!isset($this->registeredRules[$key]))
        {
            throw new \Mkoveni\Lani\Exceptions\InvalidRuleException(sprintf(
                'The rule %s is not a valid validation rule, please make sure that the rule is registered with the validator.', $key
            ));
        }

        return container()->get($this->registeredRules[$key]);
    }
}