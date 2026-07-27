<?php

namespace TallStackUi\Http\AsyncUpload;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AsyncUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'chunk' => ['required', 'file'],
            'chunk_index' => ['required', 'integer', 'min:0'],
            'total_chunks' => ['required', 'integer', 'min:1', 'max:20000'],
            'chunk_size' => ['required', 'integer', 'min:1'],
            'total_size' => ['required', 'integer', 'min:1'],
            'session_id' => ['required', 'string', 'uuid'],
            'real_name' => ['required', 'string', 'max:512'],
            'mime' => ['required', 'string', 'max:255'],
            'client_id' => ['required', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        // The lt: rule misbehaves when the compared field is itself missing,
        // and FormRequest::after() is Laravel 11.4+ while we support 10.
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            if ((int) $this->input('chunk_index') >= (int) $this->input('total_chunks')) {
                $validator->errors()->add('chunk_index', 'The chunk index must be lower than the total of chunks.');
            }
        });
    }
}
