<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\DevelopmentApplicantStoreRequest;
use App\Http\Requests\DevelopmentApplicantUpdateRequest;
use App\Http\Resources\DevelopmentApplicantResource;
use App\Interfaces\DevelopmentApplicantRepositoryInterface;
use App\Models\DevelopmentApplicant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevelopmentApplicantController extends Controller
{

    private DevelopmentApplicantRepositoryInterface $developmentApplicantRepository;


    public function __construct(DevelopmentApplicantRepositoryInterface $developmentApplicantRepository) {
        $this->developmentApplicantRepository = $developmentApplicantRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $developmentApplicants = $this->developmentApplicantRepository->getAll(
                $request->search,
                $request->limit,
                true // ✔ langsung pakai boolean
            );



            return ResponseHelper::jsonResponse(true, 'Data Pendaftar Pembangunanan Berhasil Diambil', DevelopmentApplicantResource::collection($developmentApplicants));
        } catch (\Throwable $th) {
            return ResponseHelper::jsonResponse(false, $th->getMessage());
        }
    }

    public function getAllPaginated(Request $request)
    {


        $request  = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'

        ]);

        try {
            $developmentApplicants = $this->developmentApplicantRepository->getAllPaginated($request['search'] ?? null, $request['row_per_page']);

            return ResponseHelper::jsonResponse(true, 'Data Pendaftar Pembangunanan Berhasil Diambil', DevelopmentApplicantResource::collection($developmentApplicants));
        } catch (\Throwable $th) {
            return ResponseHelper::jsonResponse(false, $th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(DevelopmentApplicantStoreRequest $request)
    {
        $data = $request->validated(); // ✅ ini yang benar

        $data['status'] = $data['status'] ?? 'pending';

        try {
            $developmentApplicants = $this->developmentApplicantRepository->create($data);

            return ResponseHelper::jsonResponse(
                true,
                'Data Pendaftar Pembangunann Berhasil Dibuat',
                new DevelopmentApplicantResource($developmentApplicants),
                201
            );

        } catch (\Throwable $th) {
            return ResponseHelper::jsonResponse(false, $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $developmentApplicants = $this->developmentApplicantRepository->getById($id);

            if (!$developmentApplicants) {
                return ResponseHelper::jsonResponse(false, 'Daftar Pendaftar Pembangunan Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Pendaftar Pembangunann Berhasil Diambil',
                new DevelopmentApplicantResource($developmentApplicants),
                200
            );

        } catch (\Throwable $th) {
            return ResponseHelper::jsonResponse(false, $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */

     public function update(DevelopmentApplicantUpdateRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $developmentApplicants = $this->developmentApplicantRepository->getById($id);

            if (!$developmentApplicants) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Daftar Pendaftar Pembangunan Tidak Ditemukan',
                    null,
                    404
                );
            }

            $developmentApplicants = $this->developmentApplicantRepository->update($id, $data);

            return ResponseHelper::jsonResponse(
                true,
                'Data Pendaftar Pembangunan Berhasil Diupdate',
                new DevelopmentApplicantResource($developmentApplicants),
                200
            );

        } catch (\Throwable $th) {
            return ResponseHelper::jsonResponse(false, $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */

     public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $developmentApplicant = $this->developmentApplicantRepository->getById($id);

            if (!$developmentApplicant) {
                return ResponseHelper::jsonResponse(false, 'Data Pendaftar Pembangunann Tidak Ditemukan', null, 404);
            }

            $this->developmentApplicantRepository->delete($id);

            DB::commit(); // 🔥 INI YANG KURANG

            return ResponseHelper::jsonResponse(true, 'Data Pendaftar Pembangunan Berhasil Dihapus', null, 200);

        } catch (\Throwable $th) {
            DB::rollBack(); // 🔥 penting juga

            return ResponseHelper::jsonResponse(false, $th->getMessage());
        }
    }
}