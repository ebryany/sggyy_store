<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait HasQueryFilters
{
    /**
     * Apply standard filters to query builder
     * 
     * Supports:
     * - q (search)
     * - category
     * - tag
     * - price_min, price_max
     * - sort (latest|popular|price_asc|price_desc)
     * - page, per_page
     * - status (for orders/payments/earnings)
     */
    protected function applyFilters(Builder $query, Request $request, array $searchFields = []): Builder
    {
        // Search query (q parameter)
        if ($request->filled('q') && !empty($searchFields)) {
            $searchTerm = $request->input('q');
            $query->where(function ($q) use ($searchFields, $searchTerm) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'LIKE', "%{$searchTerm}%");
                }
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Tag filter (for products/services with tags relationship)
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->input('tag'));
            });
        }

        // Price range filter
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        // Status filter (for orders, payments, earnings)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                // Assuming sold_count or views_count exists
                if (in_array('sold_count', $query->getModel()->getFillable())) {
                    $query->orderBy('sold_count', 'desc');
                } elseif (in_array('views_count', $query->getModel()->getFillable())) {
                    $query->orderBy('views_count', 'desc');
                } else {
                    $query->latest();
                }
                break;
            default:
                $query->latest();
        }

        return $query;
    }

    /**
     * Get pagination parameters from request
     */
    protected function getPaginationParams(Request $request): array
    {
        $perPage = min((int) $request->input('per_page', 15), 100); // Max 100 items per page
        $page = max((int) $request->input('page', 1), 1);

        return [
            'per_page' => $perPage,
            'page' => $page,
        ];
    }

    /**
     * Apply pagination to query
     */
    protected function paginate(Builder $query, Request $request)
    {
        $params = $this->getPaginationParams($request);
        return $query->paginate($params['per_page'], ['*'], 'page', $params['page']);
    }
}

