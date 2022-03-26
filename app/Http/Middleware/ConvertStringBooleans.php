<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class ConvertStringBooleans extends TransformsRequest
{
    protected function transform($key, $value)
    {
        if ('true' === $value || 'TRUE' === $value) {
            return true;
        }

        if ('false' === $value || 'FALSE' === $value) {
            return false;
        }

        return $value;
    }
}
