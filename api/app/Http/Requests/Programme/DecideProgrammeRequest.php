<?php

declare(strict_types=1);

namespace App\Http\Requests\Programme;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Approve or send back an MDA's programme (revises PRD §10). Authorization is the
 * policy's (`decide`): System Administrator only.
 *
 * A rejection MUST carry a reason. A programme sent back without one leaves the MDA
 * guessing what to change, which turns a review into a dead end.
 */
class DecideProgrammeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rejecting = $this->routeIs('programmes.reject');

        return [
            'decision_note' => [$rejecting ? 'required' : 'nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'decision_note.required' => 'Say why it is being sent back, so the MDA knows what to change.',
        ];
    }
}
