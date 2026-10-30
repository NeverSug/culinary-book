<?php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class SmartCaptchaRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (app()->environment('local', 'testing')) {
            return;
        }

        $response = Http::asForm()->post('https://smartcaptcha.cloud.yandex.ru/validate', [
            'secret' => config('services.smartcaptcha.server_key'),
            'token'  => $value,
            'ip'     => request()->ip(),
        ])->json();

        if (!isset($response['status']) || $response['status'] !== 'ok') {
            $fail('Не удалось подтвердить, что вы не робот. Попробуйте ещё раз.');
        }
    }
}
