<?php

namespace App\Repositories;

use App\Interfaces\ProfileRepositoryInterface;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function get()
    {
        return Profile::with('profileImages')->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $profile = new Profile();

            $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            $profile->name = $data['name'];
            $profile->about = $data['about'] ?? null;
            $profile->headman = $data['headman'];
            $profile->people = $data['people'];
            $profile->agricultural_area = $data['agricultural_area']; // <-- fix nama kolom
            $profile->total_area = $data['total_area'];

            $profile->save();

            // Multiple images
            $images = $data['images'] ?? $data['profile_images'] ?? [];
            foreach ($images as $image) {
                $profile->profileImages()->create([
                    'image' => $image->store('assets/profiles', 'public'),
                ]);
            }

            DB::commit();

            return $profile;

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function update(array $data)
    {
        DB::beginTransaction();

        try {
            $profile = Profile::first();

            if (!$profile) {
                throw new \Exception('Profile not found');
            }

            // Update thumbnail (hapus lama dulu biar clean)
            if (isset($data['thumbnail'])) {
                if ($profile->thumbnail && Storage::disk('public')->exists($profile->thumbnail)) {
                    Storage::disk('public')->delete($profile->thumbnail);
                }

                $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            }

            $profile->name = $data['name'] ?? $profile->name;
            $profile->about = $data['about'] ?? $profile->about;
            $profile->headman = $data['headman'] ?? $profile->headman;
            $profile->people = $data['people'] ?? $profile->people;
            $profile->agricultural = $data['agricultural_area'] ?? $profile->agricultural;
            $profile->total_area = $data['total_area'] ?? $profile->total_area;

            $profile->save();

            // Tambah images baru (tidak hapus lama)
            if (!empty($data['images'])) {
                foreach ($data['images'] as $image) {
                    $profile->profileImages()->create([
                        'image' => $image->store('assets/profiles', 'public'),
                    ]);
                }
            }

            DB::commit();

            return $profile;

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}