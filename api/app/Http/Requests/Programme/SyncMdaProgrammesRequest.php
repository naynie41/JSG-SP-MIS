<?php

declare(strict_types=1);

namespace App\Http\Requests\Programme;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Set the programmes an MDA is assigned (§10, revised 2026-11-05).
 *
 * The WHOLE list, every time. An administrator works a checklist and saves it, so the
 * request carries the intended end state rather than a diff: two administrators with
 * the page open would compute diffs against different starting points and the second
 * save would undo the first silently.
 */
class SyncMdaProgrammesRequest extends FormRequest
{
    public function authorize(): bool
    {
        // The policy decides (ProgrammePolicy::assign) — the controller authorizes.
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Present but empty is meaningful and allowed: it unassigns everything,
            // which is how an administrator ends an organisation's involvement. The
            // in-use guard still refuses it while activities are running, so an empty
            // list cannot strand work. `present` rather than `required` because
            // `required` rejects [] and would make that case unexpressible.
            'programme_ids' => ['present', 'array'],
            // Archived and still-pending ids are accepted deliberately. An assignment
            // is a statement about which organisation delivers a programme, and it
            // outlives the programme's own lifecycle — unassigning everyone from a
            // programme the moment it is archived would erase who used to run it. What
            // may be USED is settled separately, by IsRunnableProgramme.
            'programme_ids.*' => ['required', 'uuid', 'distinct', 'exists:programmes,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'programme_ids.present' => 'Send the full list of programmes this organisation should be assigned.',
            'programme_ids.*.exists' => 'One of the selected programmes no longer exists.',
        ];
    }
}
