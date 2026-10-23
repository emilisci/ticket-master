<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

/**
 * Rejects text that is technically filled in but carries no meaning:
 * too short once trimmed, numbers or symbols only, or keyboard mashing like "aaaaaaa".
 */
class MeaningfulText implements ValidationRule
{
    public function __construct(
        public int $minLength = 1,
        public int $maxRepeatedCharacters = 5,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute must be text.')->translate();

            return;
        }

        $text = trim($value);

        if (mb_strlen($text) < $this->minLength) {
            $fail('The :attribute must be at least :min characters long.')->translate(['min' => $this->minLength]);

            return;
        }

        if (! preg_match('/\p{L}/u', $text)) {
            $fail('The :attribute must contain letters, not only numbers or symbols.')->translate();

            return;
        }

        if (preg_match('/([^\s\p{N}])\1{'.$this->maxRepeatedCharacters.',}/u', $text)) {
            $fail('The :attribute must not repeat the same character so many times in a row.')->translate();
        }
    }
}
