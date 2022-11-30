<?php

namespace App\Services;

abstract class ServiceAbstract
{
    protected function returnSuccessResponse(array $result): array
    {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    protected function returnCreatedResponse(?array $result): array
    {
        $response['status_code_header'] = 'HTTP/1.1 201 OK';
        $response['body'] = $result ? json_encode($result) : null;
        return $response;
    }
}