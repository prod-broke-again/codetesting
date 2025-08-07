<?php

namespace App\Http\Controllers;

use App\Services\SnippetExportService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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
        $user = $this->getAuthenticatedUser($request);
        
        if (!$user) {
            return $this->unauthorizedResponse();
        }

        try {
            $data = $this->exportService->exportToJson($user);
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при экспорте данных: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Экспортировать сниппеты пользователя в ZIP архиве
     */
    public function exportZip(Request $request): StreamedResponse|JsonResponse
    {
        $user = $this->getAuthenticatedUser($request);
        
        if (!$user) {
            return $this->unauthorizedResponse();
        }

        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при создании архива: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Получить аутентифицированного пользователя
     */
    private function getAuthenticatedUser(Request $request)
    {
        return $request->user();
    }

    /**
     * Ответ для неаутентифицированных пользователей
     */
    private function unauthorizedResponse(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Необходима аутентификация'
        ], 401);
    }
}
