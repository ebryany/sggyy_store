<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponse
{
    /**
     * Success response with data
     */
    protected function success($data = null, string $message = null, int $code = 200): JsonResponse
    {
        $response = [
            'data' => $data,
            'meta' => [
                'message' => $message,
            ],
            'errors' => null,
        ];

        // Remove meta.message if null
        if ($message === null) {
            unset($response['meta']['message']);
        }

        // If meta is empty, set to null
        if (empty($response['meta'])) {
            $response['meta'] = null;
        }

        return response()->json($response, $code);
    }

    /**
     * Success response with resource collection (includes pagination)
     */
    protected function successCollection($collection, string $message = null): JsonResponse
    {
        // Extract pagination data if present
        $meta = [
            'message' => $message,
        ];

        if (method_exists($collection, 'resource') && $collection->resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $paginator = $collection->resource;
            $meta['pagination'] = [
                'total' => $paginator->total(),
                'count' => $paginator->count(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'total_pages' => $paginator->lastPage(),
                'has_more_pages' => $paginator->hasMorePages(),
            ];
        }

        // Remove message if null
        if ($message === null) {
            unset($meta['message']);
        }

        $response = [
            'data' => $collection->resource instanceof \Illuminate\Pagination\LengthAwarePaginator 
                ? $collection->collection 
                : $collection,
            'meta' => $meta,
            'errors' => null,
        ];

        return response()->json($response, 200);
    }

    /**
     * Error response
     */
    protected function error(string $message, array $fields = [], string $code = 'ERROR', int $httpCode = 400): JsonResponse
    {
        $errors = [
            'code' => $code,
            'message' => $message,
        ];

        if (!empty($fields)) {
            $errors['fields'] = $fields;
        }

        return response()->json([
            'data' => null,
            'meta' => null,
            'errors' => $errors,
        ], $httpCode);
    }

    /**
     * Validation error response
     */
    protected function validationError(array $errors): JsonResponse
    {
        return $this->error(
            'Validation failed',
            $errors,
            'VALIDATION_ERROR',
            422
        );
    }

    /**
     * Not found error
     */
    protected function notFound(string $resource = 'Resource'): JsonResponse
    {
        return $this->error(
            "$resource not found",
            [],
            'NOT_FOUND',
            404
        );
    }

    /**
     * Unauthorized error
     */
    protected function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->error(
            $message,
            [],
            'UNAUTHORIZED',
            401
        );
    }

    /**
     * Forbidden error
     */
    protected function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->error(
            $message,
            [],
            'FORBIDDEN',
            403
        );
    }

    /**
     * Created response
     */
    protected function created($data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    /**
     * No content response
     */
    protected function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }
}

