<?php

namespace App\Repositories;

use App\Interfaces\EventRepositoryInterface;
use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\DB;

class EventRepository implements EventRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = Event::query();

        if ($search) {
            $query->search($search);
        }

        $query->orderBy('created_at', 'desc');

        if (is_numeric($limit)) {
            $query->limit($limit);
        }

        return $execute ? $query->get() : $query;
    }

    public function getAllPaginated(?string $search, ?int $rowPerPage)
    {
        $query = Event::query();

        if ($search) {
            $query->search($search);
        }

        return $query->orderBy('created_at', 'desc')->paginate($rowPerPage ?? 10);
    }

    public function getById(string $id)
    {
        $query = Event::where('id', $id);

        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $event = new Event();

            $event->thumbnail = $data['thumbnail']->store('assets/events', 'public');
            $event->name = $data['name'];
            $event->description = $data['description'];
            $event->price = $data['price'] ?? 0;
            $event->date = $data['date'];
            $event->time = $data['time'];
            $event->is_active = $data['is_active'] ?? true;

            // 🔥 INI YANG KURANG
            $event->save();

            DB::commit();

            return $event;

        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th; // biar error ke controller
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $event = Event::find($id);

            if (!isset($data['thumbnail'])) {
                $event->thumbnail = $data['thumbnail']->store('assets/events', 'public');
            }

            $event->thumbnail = $data['thumbnail']->store('assets/events', 'public');
            $event->name = $data['name'];
            $event->description = $data['description'];
            $event->price = $data['price'] ?? 0;
            $event->date = $data['date'];
            $event->time = $data['time'];
            $event->is_active = $data['is_active'] ?? true;

            // 🔥 INI YANG KURANG
            $event->save();

            DB::commit();

            return $event;

        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th; // biar error ke controller
        }
    }

    public function delete($id)
    {
        $event = Event::find($id);
        
        if ($event) {
            $event->delete();
        }

        return $event;
    }
}