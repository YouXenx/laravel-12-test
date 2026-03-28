<?php

namespace App\Repositories;

use App\Interfaces\EventParticipantRepositoryInterface;
use App\Models\Event;
use App\Models\EventParticipant;
use Exception;
use Illuminate\Support\Facades\DB;

class EventParticipantRepository implements EventParticipantRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = EventParticipant::query();

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        $query->orderBy('created_at', 'desc');

        if ($limit) {
            $query->take($limit);
        }

        return $execute ? $query->get() : $query;
    }

    public function getAllPaginated(?string $search, ?int $rowPerPage)
    {
        $query = $this->getAll($search, $rowPerPage, false);

        return $query->paginate($rowPerPage);
    }

    public function getById($id)
    {
        $query = EventParticipant::where('id', $id);

        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {

            $event = Event::where('id', $data['event_id'])->first();

            if (!$event) {
                throw new \Exception('Event tidak ditemukan');
            }

            $eventParticipant = new EventParticipant();
            $eventParticipant->event_id = $data['event_id'];
            $eventParticipant->head_of_family_id = $data['head_of_family_id'];

            if (isset($data['quantity'])) {
                $eventParticipant->quantity = $data['quantity'];
            }else{
                $data['quantity'] = $eventParticipant->quantity;
            }

            $eventParticipant->total_price = $event->price * $data['quantity'];
            $eventParticipant->payment_status = 'pending';
            $eventParticipant->save();

            DB::commit();

            return $eventParticipant; // ⚠️ WAJIB return

        } catch (\Throwable $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function update($id, array $data)
    {
        $eventParticipant = EventParticipant::findOrFail($id);
        $eventParticipant->update($data);
        return $eventParticipant;
    }

    public function delete($id)
    {
        $eventParticipant = EventParticipant::findOrFail($id);
        return $eventParticipant->delete();
    }
}