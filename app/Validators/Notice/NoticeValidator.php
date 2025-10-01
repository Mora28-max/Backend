<?php

namespace App\Validators\Notice;

use App\Models\Catalogs\ProcessStatus;
use App\Models\Notice\Notice;

class NoticeValidator
{
    public static function ensureNoActiveNotice(int $customer_id): void
    {
        $notice_active = Notice::where('customer_id', $customer_id)
            ->where('process_status_id', 1)
            ->exists();
        if ($notice_active)
            throw new \Exception('El usuario tiene una notificación activa.');
    }
    public static function ensureNotClosed(string $process_status): void
    {
        if ($process_status === 'Cerrado' || $process_status === 'Terminado')
            throw new \Exception('La notificación no puede ser actualizado porque está cerrada o terminada.');
    }
    public static function ensureNotCanceled(string $process_status): void
    {
        if ($process_status === 'Cancelado')
            throw new \Exception('La notificación no puede ser actualizada porque está cancelada.');
    }
    public static function ensureNotFinished(string $process_status): void
    {
        $process_status = ProcessStatus::findOrFail($process_status);
        if ($process_status->name === "Terminado")
            throw new \Exception('Una notificación terminada no puede ser eliminada.');
    }
}
