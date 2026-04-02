<?php

namespace App\Http\Controllers;

use App\Interfaces\ProfileRepositoryInterface;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Resources\ProfileResource;
use App\Http\Requests\ProfileStoreRequest;
use Illuminate\Validation\ValidationException;


class ProfileController extends Controller
{
    private ProfileRepositoryInterface $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function getProfile()
    {
        try {
            $profile = $this->profileRepository->get();
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $profile = $this->profileRepository->get($request->search, $request->limit, true);

            if (!$profile) {
                return ResponseHelper::jsonResponse(false, 'Data Profile Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data Profile Berhasil Diambil', new ProfileResource($profile), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function store(ProfileStoreRequest $request)
    {

        $request = $request->validated();
        $profile = $this->profileRepository->create($request);
        try {
            $profile = $this->profileRepository->create($request);
            return ResponseHelper::jsonResponse(true, 'Data Profile Berhasil Dibuat', new ProfileResource($profile), 201);
        } catch (\Throwable $th) {
            return ResponseHelper::jsonResponse(false, $th->getMessage(), null, 500);
        }
    }
    

}
