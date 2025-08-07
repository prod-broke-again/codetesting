<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

readonly class ApiResponse
{
    public function __construct(
        public bool $success,
        public string $message,
        public array|JsonResource|null $data = null,
        public array|null $errors = null,
        public int $status = 200
    ) {}

    public static function success(
        string $message = 'Success',
        array|JsonResource|null $data = null,
        int $status = 200
    ): self {
        return new self(
            success: true,
            message: $message,
            data: $data,
            status: $status
        );
    }

    public static function error(
        string $message = 'Error',
        array|null $errors = null,
        int $status = 400
    ): self {
        return new self(
            success: false,
            message: $message,
            errors: $errors,
            status: $status
        );
    }

    public function toResponse(Request $request): JsonResponse
    {
        $response = [
            'success' => $this->success,
            'message' => $this->message,
        ];

        if ($this->data !== null) {
            $response['data'] = $this->data instanceof JsonResource 
                ? $this->data->resolve($request)
                : $this->data;
        }

        if ($this->errors !== null) {
            $response['errors'] = $this->errors;
        }

        return response()->json($response, $this->status);
    }

    public function toArray(Request $request): array
    {
        $array = [
            'success' => $this->success,
            'message' => $this->message,
        ];

        if ($this->data !== null) {
            $array['data'] = $this->data instanceof JsonResource 
                ? $this->data->resolve($request)
                : $this->data;
        }

        if ($this->errors !== null) {
            $array['errors'] = $this->errors;
        }

        return $array;
    }
} 