<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\FamilyMemberStoreRequest;
use App\Http\Requests\FamilyMemberUpdateRequest;
use App\Http\Resources\FamilyMemberResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\FamilyMemberRepositoryInterface;
use Illuminate\Http\Request;

class FamilyMemberController extends Controller
{

    private FamilyMemberRepositoryInterface $familyMemberRepository;

    public function __construct(FamilyMemberRepositoryInterface $familyMemberRepository)
    {
        $this->familyMemberRepository = $familyMemberRepository;
    }


    public function index()
    {
        try {
            $familyMembers = $this->familyMemberRepository->getAll(
                null,
                null,
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Anggota Keluarga Berhasil Diambil',
                FamilyMemberResource::collection($familyMembers),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }


    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $familyMembers = $this->familyMemberRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Anggota Keluarga Berhasil Diambil',
                new PaginateResource($familyMembers, FamilyMemberResource::class),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FamilyMemberStoreRequest $request)
    {
        $requestData = $request->validated();

        try {
            $familyMember = $this->familyMemberRepository->create($requestData);

            return ResponseHelper::jsonResponse(
                true,
                'Data Anggota Keluarga Berhasil Ditambahkan',
                FamilyMemberResource::make($familyMember),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $familyMember = $this->familyMemberRepository->getById($id);

            return ResponseHelper::jsonResponse(
                true,
                'Data Anggota Keluarga Berhasil Diambil',
                FamilyMemberResource::make($familyMember),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FamilyMemberUpdateRequest $request, string $id)
    {
        $requestData = $request->validated();

        try {
            $familyMember = $this->familyMemberRepository->update(
                $id,
                $requestData
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Anggota Keluarga Berhasil Diperbarui',
                FamilyMemberResource::make($familyMember),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->familyMemberRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Data Anggota Keluarga Berhasil Dihapus',
                null,
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }
}
