<?php

declare(strict_types=1);

namespace App\Http\Requests\Graduation;

use App\Domain\Graduation\Enums\CriteriaLogic;
use App\Domain\Graduation\Enums\GraduationCriterionType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Admin-editable graduation criteria for a programme (FR-GRD-01). The `rules` are
 * configuration (a list of {type, threshold}) — automatic dimensions need a positive
 * threshold; the `manual` dimension is a readiness flag with no threshold.
 */
class GraduationCriteriaRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:150'],
            'logic' => ['required', Rule::enum(CriteriaLogic::class)],
            'is_active' => ['sometimes', 'boolean'],
            'rules' => ['required', 'array', 'min:1', 'max:10'],
            'rules.*.type' => ['required', Rule::enum(GraduationCriterionType::class)],
            'rules.*.threshold' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    protected function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var array<int, array<string, mixed>> $rules */
            $rules = (array) $this->input('rules', []);
            foreach ($rules as $index => $rule) {
                $type = GraduationCriterionType::tryFrom((string) ($rule['type'] ?? ''));
                if ($type?->isAutomatic() === true && (float) ($rule['threshold'] ?? 0) <= 0) {
                    $validator->errors()->add(
                        "rules.$index.threshold",
                        'An automatic criterion needs a threshold greater than zero.',
                    );
                }
            }
        });
    }
}
