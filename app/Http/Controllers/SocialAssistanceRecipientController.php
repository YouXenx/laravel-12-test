<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\SocialAssistanceRecipientRepositoryInterface;
use App\Helpers\ResponseHelper;
use App\Http\Resources\SocialAssistanceRecipientResource;
use App\Http\Requests\SocialAssistanceRecipientStoreRequest;
use App\Http\Requests\SocialAssistanceRecipientUpdateRequest;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\Response;

class SocialAssistanceRecipientController extends Controller
{
    
    private SocialAssistanceRecipientRepositoryInterface $socialAssistanceRecipientRepository;
    public function __construct(SocialAssistanceRecipientRepositoryInterface $socialAssistanceRecipientRepository)
    {
        $this->socialAssistanceRecipientRepository = $socialAssistanceRecipientRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['-recipient-list|-recipient-create|-recipient-edit|-recipient-delete']), only: ['index', 'getAllPaginated', 'show']),
            new Middleware(PermissionMiddleware::using(['-recipient-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['-recipient-create']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['-recipient-create']), only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        try {
            $socialAssistanceRecipients = $this->socialAssistanceRecipientRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            // pastikan ini collection
            $socialAssistanceRecipients->load([
                'socialAssistance',
                'headOfFamily'
            ]);

            return ResponseHelper::jsonResponse(
                true,
                'Data Bantuan Sosial Penerima Berhasil Diambil',
                SocialAssistanceRecipientResource::collection($socialAssistanceRecipients),
                200
            );

        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function getAllPaginated(Request $request)
    {
        try {

            $limit = $request->limit ?? 10;

            $socialAssistanceRecipients = $this->socialAssistanceRecipientRepository->getAllPaginated(
                $request->search,
                $limit
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Bantuan Sosial Penerima Berhasil Diambil',
                SocialAssistanceRecipientResource::collection($socialAssistanceRecipients),
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
    public function store(SocialAssistanceRecipientStoreRequest $request)
    {
        $data = $request->validated(); // gunakan $data
        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->create($data);
            $socialAssistanceRecipient->load([
                'socialAssistance', // optional select
                'headOfFamily'
            ]);
            
            return ResponseHelper::jsonResponse(true, 'Data Bantuan Sosial Penerima Berhasil Ditambahkan', SocialAssistanceRecipientResource::make($socialAssistanceRecipient), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getById($id);

            if (!$socialAssistanceRecipient) {
                return ResponseHelper::jsonResponse(false, 'Data Penerima Bantuan Social Tidak Ditemukan', 'null', 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data Penerima Bantuan Social Berhasil Ditemukan', new SocialAssistanceRecipientResource($socialAssistanceRecipient));
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */

     public function update(SocialAssistanceRecipientUpdateRequest $request, string $id)
{
    $data = $request->validated();

    try {
        $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getById($id);

        if (!$socialAssistanceRecipient) {
            return ResponseHelper::jsonResponse(
                false,
                'Data penerima Bantuan Sosial Tidak Ditemukan',
                null,
                404
            );
        }

        // update data
        $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->update($id, $data);

        // load relasi setelah update
        $socialAssistanceRecipient->load([
            'socialAssistance',
            'headOfFamily'
        ]);

        return ResponseHelper::jsonResponse(
            true,
            'Data Bantuan Sosial Penerima Berhasil Diupdate',
            SocialAssistanceRecipientResource::make($socialAssistanceRecipient),
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
        $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getById($id);

        if (!$socialAssistanceRecipient) {
            return ResponseHelper::jsonResponse(
                false,
                'Data penerima Bantuan Sosial Tidak Ditemukan',
                null,
                404
            );
        }

        // jalankan delete TANPA overwrite
        $this->socialAssistanceRecipientRepository->delete($id);

        return ResponseHelper::jsonResponse(
            true,
            'Data Bantuan Sosial Penerima Berhasil Dihapus',
            SocialAssistanceRecipientResource::make($socialAssistanceRecipient),
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
