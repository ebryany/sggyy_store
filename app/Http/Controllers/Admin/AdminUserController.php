<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request): View
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $validRoles = ['admin', 'user', 'seller'];
            if (in_array($request->role, $validRoles)) {
                $query->where('role', $request->role);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $search = strip_tags($search);
            $search = mb_substr($search, 0, 100);
            
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('store_name', 'like', "%{$search}%")
                      ->orWhere('store_slug', 'like', "%{$search}%");
                });
            }
        }

        // Date range filter (registration date)
        if ($request->filled('date_from')) {
            $dateFrom = $request->date_from;
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }
        }

        if ($request->filled('date_to')) {
            $dateTo = $request->date_to;
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
                $query->whereDate('created_at', '<=', $dateTo);
            }
        }

        // Sorting
        $validSorts = ['newest', 'oldest', 'name_asc', 'name_desc', 'email_asc', 'email_desc'];
        $sort = $request->get('sort', 'newest');
        if (!in_array($sort, $validSorts)) {
            $sort = 'newest';
        }

        switch ($sort) {
            case 'newest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'email_asc':
                $query->orderBy('email', 'asc');
                break;
            case 'email_desc':
                $query->orderBy('email', 'desc');
                break;
        }

        // Per page
        $perPage = $request->get('per_page', 20);
        $validPerPage = [10, 15, 20, 30, 50];
        if (!in_array((int)$perPage, $validPerPage)) {
            $perPage = 20;
        }

        $users = $query->paginate((int)$perPage)->withQueryString();

        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'sellers' => User::where('role', 'seller')->count(),
            'users' => User::where('role', 'user')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user): View
    {
        $user->load(['products', 'services', 'ratings']);
        
        return view('admin.users.show', compact('user'));
    }
}
