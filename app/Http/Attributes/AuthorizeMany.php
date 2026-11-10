<?php

namespace App\Http\Attributes {

    use Attribute;

    #[Attribute(Attribute::TARGET_CLASS)]
	class AuthorizeMany
	{
        public function __construct(public string $model, public string $parameter) {}
    }
}
