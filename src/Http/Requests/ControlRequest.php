<?php

namespace Acdphp\ScheduleControl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ControlRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key' => ['required', 'string'],
            'expression' => ['required', 'string'],
        ];
    }
}
