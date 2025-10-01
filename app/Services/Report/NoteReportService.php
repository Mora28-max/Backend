<?php

namespace App\Services\Report;

use App\Models\Reports\Note;
use Illuminate\Http\Response;
use App\Models\Reports\Report;
use Illuminate\Support\Facades\Auth;
use App\Helpers\UploadDataToCloudinary;

class NoteReportService
{
    public function createNote(array $data): Note
    {

        $hasEvicence = !empty($data['evidence']);

        $report = Report::where('tracking_folio', $data['tracking_folio'])->first();
        if (!$report) throw new \Exception("No se encontró el reporte con el folio {$data['report_id']}");
        $evidence = $hasEvicence ? $this->uploadEvidence($data, $report->id) : null;

        return Note::create([
            'report_id' => $report->id,
            'description' => $data['description'],
            'url_evidence' => $evidence['public_id'] ?? null,
            'format_evidence' => $evidence['format_evidence'] ?? null,
            'user_id' => Auth::user()->id,
        ]);
    }

    public function uploadEvidence(array $data, string $report_id): array
    {
        $evidence = ['public_id' => null, 'format_evidence' => null,];

        $totalEvidence = Note::where('report_id', $report_id)
            ->whereNotNull('url_evidence')
            ->count() ?? 0;

        $newEvidenceName = $data['tracking_folio'] . '-' . $totalEvidence + 1;

        switch ($data['type_evidence']) {
            case 'photo':
                $evidence = [
                    'public_id' => UploadDataToCloudinary::uploadImage($newEvidenceName, $data['evidence'], 'reports'),
                    'format_evidence' => 'jpg',
                ];
                break;
            case 'video':
                $evidence = [
                    'public_id' => UploadDataToCloudinary::uploadVideo($newEvidenceName, $data['evidence'], 'reports'),
                    'format_evidence' => 'mp4',
                ];
                break;
            case 'document':
                $evidence = [
                    'public_id' => UploadDataToCloudinary::uploadDocument($newEvidenceName, $data['evidence'], 'reports/pdf'),
                    'format_evidence' => 'pdf',
                ];
                break;
            default:
                return [];
        }

        return $evidence;
    }

    public function deleteNote(Note $note): void
    {
        $deleted = $note->delete();
        if (!$deleted) throw new \Exception('Error al eliminar la nota');

        if (!empty($note->url_evidence)) {
            $extension = $note->format_evidence;
            $map = [
                'jpg' => 'image',
                'jpeg' => 'image',
                'png' => 'image',
                'mp4' => 'video',
                'pdf' => 'image',
            ];
            $resourceType = $map[strtolower($extension)] ?? 'raw';
            $removed = UploadDataToCloudinary::removeFile($note->url_evidence, $resourceType);
            if (!$removed) throw new \Exception('Error al eliminar la evidencia');
        }
    }
}
