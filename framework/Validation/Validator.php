<?php

namespace Mkoveni\Lani\Validation;

use Mkoveni\Lani\Validation\Rules\NotBlank;

class Validator implements ValidatorInterface
{
    protected $registeredRules = [
        NotBlank::class
    ];

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
                
            }
        }
    }

    protected function addError($attribute, $message)
    {
        $this->errors[$attribute][] = $message;
    }

    public function addRule($rule)
    {
        array_push($this->registeredRules, $rule);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }
}