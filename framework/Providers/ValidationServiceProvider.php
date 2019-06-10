<?php

namespace Mkoveni\Lani\Providers;

use Mkoveni\Lani\Validation\ValidatorInterface;
use Mkoveni\Lani\Validation\Validator;

class ValidationServiceProvider extends AbstractServiceProvider
{
    public function register()
    {
        $c = $this->getContainer();

        $c->share(ValidatorInterface::class, function(){
            
            $rules = array_merge(Validator::getDefaultRules(),config('validation.custom_rules', []));

            $validator = new Validator;

            $validator->registerRules($rules);

            return $validator;
        });
    }
}

