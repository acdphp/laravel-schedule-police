<?php

namespace Acdphp\SchedulePolice\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExecRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'command' => ['required', 'string'],
        ];
    }
}
