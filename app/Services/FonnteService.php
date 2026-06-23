<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class FonnteService
{
    public function sendOtp(string $phoneNumber, string $otp): void
    {
        $message = "Kode OTP reset password Anda: {$otp}\nJangan berikan kode ini kepada siapa pun.\nKode berlaku selama 10 menit.";

        $this->sendMessage($phoneNumber, $message, 'Gagal mengirim OTP ke WhatsApp.');
    }

    public function sendMessage(string $phoneNumber, string $message, string $errorMessage = 'Gagal mengirim pesan WhatsApp.'): void
    {
        $token = (string) config('services.fonnte.token');

        if ($token === '') {
            throw new RuntimeException('FONNTE token belum dikonfigurasi.');
        }

        $response = Http::asMultipart()
            ->withHeaders([
                'Authorization' => $token,
            ])
            ->post('https://api.fonnte.com/send', [
                ['name' => 'target', 'contents' => $this->normalizePhoneNumber($phoneNumber)],
                ['name' => 'message', 'contents' => $message],
                ['name' => 'countryCode', 'contents' => (string) config('services.fonnte.country_code', '62')],
            ]);

        if (! $response->successful()) {
            throw new RuntimeException($errorMessage);
        }

        $payload = $response->json();

        if (is_array($payload) && array_key_exists('status', $payload) && ! $payload['status']) {
            $reason = $payload['reason'] ?? $errorMessage;
            throw new RuntimeException((string) $reason);
        }
    }

    private function normalizePhoneNumber(string $phoneNumber): string
    {
        return preg_replace('/\D+/', '', $phoneNumber) ?? $phoneNumber;
    }
}
