<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\DevelopmentStoreRequest;
use App\Http\Requests\DevelopmentUpdateRequest;
use App\Http\Resources\DevelopmentResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\DevelopmentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class DevelopmentController extends Controller implements HasMiddleware
{

    private DevelopmentRepositoryInterface $developmentRepository;

    public function __construct(DevelopmentRepositoryInterface $developmentRepository) {
        $this->developmentRepository = $developmentRepository;
    }

    /**
     * Display a listing of the resource.
     */

     public static function middleware()
     {
         return [
             new Middleware(PermissionMiddleware::using(['development-list|development-create|development-edit|development-delete']), only: ['index', 'getAllPaginated', 'show']),
             new Middleware(PermissionMiddleware::using(['development-create']), only: ['store']),
             new Middleware(PermissionMiddleware::using(['development-create']), only: ['update']),
             new Middleware(PermissionMiddleware::using(['development-create']), only: ['destroy']),
         ];
     }

    public function index(Request $request)
    {
        $search = $request->query('search'); // bisa null atau string
        $limit = $request->query('limit');

        // amanin limit
        $limit = is_numeric($limit) ? (int)$limit : null;

        // amanin search
        $search = $search && trim($search) !== '' ? $search : null;

        $developments = $this->developmentRepository->getAll($search, $limit, true);

        return response()->json([
            'success' => true,
            'message' => 'Data Pembangunan berhasil diambil',
            'data' => $developments
        ]);
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $developments = $this->developmentRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );
            return ResponseHelper::jsonResponse(true, 'Data Pembangunan berhasil Diambil', PaginateResource::make($developments, DevelopmentResource::class), 200);
        } catch (\Throwable $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(DevelopmentStoreRequest $request)
    {
        try {
            $development = $this->developmentRepository->create($request->validated());

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembangunan Berhasil Ditambahkan',
                new DevelopmentResource($development),
                201
            );
        } catch (\Throwable $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $development = $this->developmentRepository->getById($id);

            if (!$development) {
                return ResponseHelper::jsonResponse(true, 'Data Pembangunan Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembangunan Berhasil Diambil',
                new DevelopmentResource($development),
                201
            );
        } catch (\Throwable $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DevelopmentUpdateRequest $request, string $id)
    {
        try {
            $development = $this->developmentRepository->getById($id);

            if (!$development) {
                return ResponseHelper::jsonResponse(true, 'Data Pembangunan Tidak Ditemukan', null, 404);
            }

            $development = $this->developmentRepository->update($id, $request->validated());

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembangunan Berhasil Diambil',
                new DevelopmentResource($development),
                201
            );
        } catch (\Throwable $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $development = $this->developmentRepository->getById($id);

            if (!$development) {
                return ResponseHelper::jsonResponse(true, 'Data Pembangunan Tidak Ditemukan', null, 404);
            }

            $development = $this->developmentRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembangunan Berhasil Dihapus',
                null,
                200
            );
        } catch (\Throwable $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
