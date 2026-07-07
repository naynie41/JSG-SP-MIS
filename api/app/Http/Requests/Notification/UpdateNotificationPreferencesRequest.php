<?php

declare(strict_types=1);

namespace App\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Update the caller's notification channel preferences (PRD FR-NOT-02). In-app is
 * always on; only the outbound channel toggles are editable.
 */
class UpdateNotificationPreferencesRequest extends FormRequest
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
        return [
            'email_enabled' => ['required', 'boolean'],
        ];
    }
}
