<?php

namespace App\Http\Controllers;

use App\Services\SnippetExportService;
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
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Необходима аутентификация'
            ], 401);
        }

        $data = $this->exportService->exportToJson($user);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Экспортировать сниппеты пользователя в ZIP архиве
     */
    public function exportZip(Request $request): StreamedResponse|JsonResponse
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Необходима аутентификация'
            ], 401);
        }

        $zipFilePath = $this->exportService->exportToZip($user);

        if (!$zipFilePath) {
            return response()->json([
                'success' => false,
                'message' => 'Не удалось создать архив или у вас нет сниппетов для экспорта'
            ], 400);
        }

        return response()->streamDownload(function () use ($zipFilePath) {
            readfile($zipFilePath);
        }, basename($zipFilePath), [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }
}
