<?php

namespace App\Http\Controllers\Notices;

use Illuminate\Http\Request;
use App\Models\Notice\Notice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Notice\NoticeService;
use App\Http\Resources\Notice\NoticeResource;
use App\Http\Resources\Notice\NoticeCollection;
use App\Http\Requests\Notice\StoreNoticeRequest;
use App\Http\Requests\Notice\UpdateNoticeRequest;
use App\Http\Requests\Notice\NotComplianceNoticeRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NoticeController extends Controller
{

    use AuthorizesRequests;

    public function __construct(protected NoticeService $notice_service) {}

    private function noticeRelations()
    {
        return [
            'processStatus:id,name',
            'noticeType:id,name',
            'customer:id,first_name,last_name',
            'user:id,firstname,lastname'
        ];
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Notice::class);
        $process_id = $request->process_id ?? 1;
        $customer_id = $request->customer_id ?? null;

        if ($customer_id) {
            $notices = Notice::with($this->noticeRelations())
                ->where('customer_id', $customer_id)
                ->orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString();
        } else {
            if (Auth::user()->hasRole('Admin|General Administrator|Developer')) {
                $notices = Notice::with($this->noticeRelations())
                    ->where('process_status_id', $process_id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15)
                    ->withQueryString();
            } else {
                $notices = Notice::with($this->noticeRelations())
                    ->where('process_status_id', $process_id)
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->paginate(15)
                    ->withQueryString();
            }
        }

        return new NoticeCollection($notices);
    }

    public function store(StoreNoticeRequest $request)
    {
        try {
            $this->authorize('create', Notice::class);
            $notice = $this->notice_service->createNotice($request->validated());
            $notice = $notice->load($this->noticeRelations());
            return response([
                'message' => 'La notifición ha sido creada correctamente.',
                'notice_id' => new NoticeResource($notice)
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al crear la notificación.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function show(Notice $notice)
    {
        $this->authorize('view', $notice);
        return new NoticeResource($notice->load($this->noticeRelations()));
    }

    public function update(UpdateNoticeRequest $request, Notice $notice)
    {
        try {
            $this->authorize('update', $notice);
            $notice = $this->notice_service->updateNotice($notice,  $request->validated());
            $notice = $notice->load($this->noticeRelations());
            return response([
                'message' => 'La notificación ha sido actualizada correctamente.',
                'data' => new NoticeResource($notice)
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al actualizar la notificación.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Notice $notice)
    {
        try {
            $this->authorize('delete', $notice);
            $this->notice_service->deleteNotice($notice);
            return response(['message' => 'La notificación ha sido eliminada correctamente.']);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al eliminar la notificación.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function nonCompliance(Notice $notice, NotComplianceNoticeRequest $request)
    {
        try {
            $this->authorize('nonCompliance', $notice);
            $message = $this->notice_service->generateReportForNoncompliance($notice, $request->validated());
            return response([
                'message' => $message[0],
                'report_id' => $message[1]
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al generar el reporte de incumplimiento.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
