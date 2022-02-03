<?php

namespace App\OpenApi\Responses;

use App\OpenApi\Schemas\ProfileResponseSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class CustomerProfileResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()
            ->content(MediaType::json()->schema(ProfileResponseSchema::ref()))
            ->description('Customer Profile');
    }
}
