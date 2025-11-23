<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class JokiService
{
    public function create(array $data, ?UploadedFile $image = null): Service
    {
        $data['user_id'] = auth()->id();
        $data['status'] = 'active';

        // Generate slug if not provided
        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = Service::generateSlug($data['title']);
        }

        if ($image) {
            $data['image'] = $this->storeImage($image);
        }

        return Service::create($data);
    }

    public function update(Service $service, array $data, ?UploadedFile $image = null): Service
    {
        // Regenerate slug if title changed
        if (isset($data['title']) && $data['title'] !== $service->title) {
            $data['slug'] = Service::generateSlug($data['title'], $service->id);
        }

        if ($image) {
            $disk = config('filesystems.default');
            if ($service->image) {
                Storage::disk($disk)->delete($service->image);
            }
            $data['image'] = $this->storeImage($image);
        }

        $service->update($data);
        return $service->fresh();
    }

    public function delete(Service $service): bool
    {
        if ($service->image) {
            $disk = config('filesystems.default');
            Storage::disk($disk)->delete($service->image);
        }

        return $service->delete();
    }

    public function markCompleted(Service $service): Service
    {
        $service->increment('completed_count');
        return $service->fresh();
    }

    private function storeImage(UploadedFile $file): string
    {
        $disk = config('filesystems.default');
        return $file->store('services/images', $disk);
    }
}





