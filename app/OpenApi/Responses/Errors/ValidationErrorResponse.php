<?php

namespace App\OpenApi\Responses\Errors;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class ValidationErrorResponse extends ResponseFactory implements Reusable
{
    public function build(): Response
    {
        $response = Schema::object()->properties(
            Schema::string('message')
                ->nullable(false)
                ->example('The given data was invalid.'),
            Schema::object('errors')
                ->nullable(false)
                ->additionalProperties(
                    Schema::array()->items(Schema::string())
                )
                ->example(['field' => ['Something is wrong with this field!']])
        )->required('message', 'errors');

        return Response::unprocessableEntity('ValidationError')
            ->description('Validation errors')
            ->content(
                MediaType::json()->schema($response)
            );
    }
}
