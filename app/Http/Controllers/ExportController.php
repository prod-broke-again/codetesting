<?php

namespace App\Http\Controllers;

use App\Services\SnippetExportService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function __construct(
        private SnippetExportService $exportService
    ) {}

    /**
     * Экспортировать сниппеты пользователя в JSON формате
     */
    public function exportJson(Request $request): JsonResponse
    {
        try {
            $this->requireAuth();
            $user = $request->user();
            
            $data = $this->exportService->exportToJson($user);
            
            return $this->successResponse(
                data: $data,
                message: 'Данные успешно экспортированы'
            );
        } catch (Exception $e) {
            return $this->handleException(
                exception: $e,
                context: 'Export JSON failed',
                extraData: ['user_id' => $request->user()?->id]
            );
        }
    }

    /**
     * Экспортировать сниппеты пользователя в ZIP архиве
     */
    public function exportZip(Request $request): StreamedResponse|JsonResponse
    {
        try {
            $this->requireAuth();
            $user = $request->user();
            
            $zipFilePath = $this->exportService->exportToZip($user);

            if (!$zipFilePath) {
                return $this->errorResponse(
                    message: 'Не удалось создать архив или у вас нет сниппетов для экспорта',
                    status: 400
                );
            }

            return response()->streamDownload(function () use ($zipFilePath) {
                readfile($zipFilePath);
            }, basename($zipFilePath), [
                'Content-Type' => 'application/zip',
            ])->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return $this->handleException(
                exception: $e,
                context: 'Export ZIP failed',
                extraData: ['user_id' => $request->user()?->id]
            );
        }
    }
}
