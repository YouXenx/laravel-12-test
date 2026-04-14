<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\EventRepositoryInterface;
use Illuminate\Http\Request;

class EventController extends Controller
{

    private EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['social-assistance-list|social-assistance-create|social-assistance-edit|social-assistance-delete']), only: ['index', 'getAllPaginated', 'show']),
            new Middleware(PermissionMiddleware::using(['social-assistance-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['social-assistance-create']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['social-assistance-create']), only: ['destroy']),
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

        $events = $this->eventRepository->getAll($search, $limit, true);

        return response()->json([
            'success' => true,
            'message' => 'Data Event berhasil diambil',
            'data' => $events
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventStoreRequest $request)
    {
        $data = $request->validated();

        try {
            $event = $this->eventRepository->create($data);

            return ResponseHelper::jsonResponse(true, 'Data Event Berhasil Dibuat', new EventResource($event), 201);

        } catch (\Throwable $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
        
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $events = $this->eventRepository->getAllPaginated($request['seacrh'] ?? null, $request['row_per_page'], true);
            return ResponseHelper::jsonResponse(true, 'Data Event berhasil Diambil', PaginateResource::make($events, EventResource::class), 200);
        } catch (\Throwable $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        try {
            $event = $this->eventRepository->getById($id);

            if (!$event) {
                return ResponseHelper::jsonResponse(false, 'Data Event Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data Event Berhasil Diambil', new EventResource($event), 201);

        } catch (\Throwable $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */

     public function update(EventUpdateRequest $request, string $id)
    {
        try {
            // Ambil event dari repository
            $event = $this->eventRepository->getById($id);

            if (!$event) {
                return ResponseHelper::jsonResponse(false, 'Data Event Tidak Ditemukan', null, 404);
            }

            // Ambil semua input valid kecuali thumbnail
            $data = $request->except('thumbnail');

            // Jika ada file thumbnail baru, simpan
            if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
                $data[ 'thumbnail'] = $request->file('thumbnail')->store('assets/events', 'public');
            }

            // Update event
            $event = $this->eventRepository->update($id, $data);

            return ResponseHelper::jsonResponse(true, 'Data Event Berhasil Diupdate', new EventResource($event), 200);

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
            $event = $this->eventRepository->getById($id);

            if (!$event) {
                return ResponseHelper::jsonResponse(false, 'Data Event Tidak Ditemukan', null, 404);
            }

            $this->eventRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Data Event Berhasil Dihapus',
                null,
                200
            );

        } catch (\Throwable $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
