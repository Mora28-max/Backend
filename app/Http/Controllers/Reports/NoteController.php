<?php

namespace App\Http\Controllers\Reports;

use App\Models\Reports\Note;
use Illuminate\Http\Request;
use App\Models\Reports\Report;
use App\Http\Controllers\Controller;
use App\Services\Report\NoteReportService;
use App\Http\Resources\Report\NoteResource;
use App\Http\Resources\Report\NoteCollection;
use App\Http\Requests\Report\StoreNoteRequest;
use App\Http\Requests\Report\UpdateNoteRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\Report\UpdateReportImageRequest;

class NoteController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected NoteReportService $note_report_service
    ) {}

    public function index(Request $request)
    {
        if (!$request->report_id)
            return response(['message' => 'El id del reporte es obligatorio.'], 400);
        $this->authorize('viewAny', Note::class);
        $notes = Note::where('report_id', $request->report_id)->get();
        return new NoteCollection($notes);
    }

    public function store(StoreNoteRequest $request)
    {
        try {
            $this->authorize('create', Note::class);
            $note = $this->note_report_service->createNote($request->validated());
            return response([
                'message' => 'Nota creada exitosamente.',
                'note' => $note
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al crear la nota.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function show(int $note_id)
    {
        $note = Note::findOrFail($note_id);
        $this->authorize('view', $note);
        return new NoteResource($note);
    }

    public function update(UpdateNoteRequest $request, Note $note)
    {
        try {
            $this->authorize('update', $note);
            $note->update($request->validated());
            return response([
                'messages' => 'Nota actualizada exitosamente.',
                'note' => new NoteResource($note->fresh()),
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al actualizar la nota.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $note = Note::findOrFail($id);
            $this->authorize('delete', $note);
            $this->note_report_service->deleteNote($note);
            return response(['message' => 'Nota eliminada exitosamente.']);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al eliminar la nota.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function addImagesForPdf(UpdateReportImageRequest $request, int $report_id)
    {
        try {
            $report = Report::findOrFail($report_id);
            $this->authorize('update', $report);
            $report->update($request->validated());
            return response(['message' => 'Imágenes para PDF agregadas exitosamente.']);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al agregar imágenes para PDF.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
