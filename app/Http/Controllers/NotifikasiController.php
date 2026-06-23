<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Log;
use Throwable;

class NotifikasiController extends Controller
{
    public function __construct(
        private readonly FonnteService $fonnteService,
    ) {}

    public function sendWhatsApp(
        User $recipient,
        string $message,
        string $errorContext = 'pengaduan'
    ): void {
        if (! $recipient->no_hp) {
            return;
        }

        try {
            $this->fonnteService->sendMessage($recipient->no_hp, $message);
        } catch (Throwable $e) {
            Log::warning("Gagal kirim notifikasi WhatsApp {$errorContext}.", [
                'user_id' => $recipient->user_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function sendToRoleInCabang(
        int $cabangId,
        string $role,
        string $message
    ): void {
        $users = User::role($role)
            ->where('is_active', true)
            ->where('cabang_id', $cabangId)
            ->get();

        foreach ($users as $user) {
            $this->sendWhatsApp($user, $message, $role);
        }
    }
}
