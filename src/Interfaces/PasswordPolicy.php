<?php

namespace JalalLinuX\Thingsboard\Interfaces;

class PasswordPolicy
{
    public bool $allowWhitespaces;
    public ?int $minimumDigits;
    public ?int $minimumLength;
    public ?int $minimumLowercaseLetters;
    public ?int $minimumSpecialCharacters;
    public ?int $minimumUppercaseLetters;
    public ?int $passwordExpirationPeriodDays;
    public ?int $passwordReuseFrequencyDays;

    public function __construct(bool $allowWhitespaces = false, int $minimumDigits = null, int $minimumLength = null, int $minimumLowercaseLetters = null, int $minimumSpecialCharacters = null, int $minimumUppercaseLetters = null, int $passwordExpirationPeriodDays = null, int $passwordReuseFrequencyDays = null)
    {
        $this->allowWhitespaces = $allowWhitespaces;
        $this->minimumDigits = $minimumDigits;
        $this->minimumLength = $minimumLength;
        $this->minimumLowercaseLetters = $minimumLowercaseLetters;
        $this->minimumSpecialCharacters = $minimumSpecialCharacters;
        $this->minimumUppercaseLetters = $minimumUppercaseLetters;
        $this->passwordExpirationPeriodDays = $passwordExpirationPeriodDays;
        $this->passwordReuseFrequencyDays = $passwordReuseFrequencyDays;
    }

    public static function fromArray(array $policies): PasswordPolicy
    {
        return new self(
            @$policies['allowWhitespaces'] ?? false,
            @$policies['minimumDigits'] ?? null,
            @$policies['minimumLength'] ?? null,
            @$policies['minimumLowercaseLetters'] ?? null,
            @$policies['minimumSpecialCharacters'] ?? null,
            @$policies['minimumUppercaseLetters'] ?? null,
            @$policies['passwordExpirationPeriodDays'] ?? null,
            @$policies['passwordReuseFrequencyDays'] ?? null,
        );
    }
}
