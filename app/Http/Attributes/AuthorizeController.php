<?php

namespace App\Http\Attributes {

    use Attribute;

    #[Attribute(Attribute::TARGET_CLASS)]
    class AuthorizeController
    {
        public function __construct(public string $model, public string $parameter)
        {
        }
    }
}
