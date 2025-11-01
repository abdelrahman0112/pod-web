<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Convert /storage/avatars/filename.jpg to avatars/filename.jpg for FileUpload component
        // Filament FileUpload expects paths relative to the disk root
        if (isset($data['avatar']) && $data['avatar']) {
            $avatar = $data['avatar'];

            // If it's an external URL (OAuth), keep it as-is
            if (str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://')) {
                return $data;
            }

            // Convert /storage/avatars/filename.jpg to avatars/filename.jpg
            if (str_starts_with($avatar, '/storage/avatars/')) {
                $filename = basename($avatar);
                $relativePath = 'avatars/'.$filename;

                // Only set if file exists - use relative path, Filament will generate URLs correctly
                if (Storage::disk('public')->exists($relativePath)) {
                    // Use relative path - Filament FileUpload will handle URL generation
                    $data['avatar'] = $relativePath;
                } else {
                    // File doesn't exist - clear it to avoid fetch errors
                    $data['avatar'] = null;
                }
            }
            // If it already starts with avatars/, convert to full URL
            elseif (str_starts_with($avatar, 'avatars/')) {
                if (Storage::disk('public')->exists($avatar)) {
                    // Generate full URL
                    $data['avatar'] = Storage::disk('public')->url($avatar);
                } else {
                    // File doesn't exist - clear it
                    $data['avatar'] = null;
                }
            }
            // Otherwise, prepend avatars/ and convert to full URL
            else {
                $relativePath = 'avatars/'.$avatar;
                if (Storage::disk('public')->exists($relativePath)) {
                    // Generate full URL
                    $data['avatar'] = Storage::disk('public')->url($relativePath);
                } else {
                    // File doesn't exist - clear it
                    $data['avatar'] = null;
                }
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Prevent role changes unless user is superadmin
        if (isset($data['role']) && auth()->user()?->role !== 'superadmin') {
            // Restore original role if user is not superadmin
            $data['role'] = $this->record->role;
        }

        // Convert full URLs or relative paths back to /storage/avatars/filename.jpg for database
        if (isset($data['avatar']) && $data['avatar']) {
            $avatar = $data['avatar'];

            // If it's an external URL (OAuth), keep it as-is
            if (str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://')) {
                // Check if it's our own domain's storage URL - convert back to relative
                $storageUrl = Storage::disk('public')->url('');
                if (str_starts_with($avatar, $storageUrl)) {
                    // Extract relative path from full URL
                    $relativePath = str_replace($storageUrl, '', $avatar);
                    // Convert to /storage/ format
                    $data['avatar'] = '/storage/'.$relativePath;
                }
                // Otherwise keep external URL as-is (OAuth avatars)
            }
            // If it's already in /storage/ format, keep it
            elseif (str_starts_with($avatar, '/storage/')) {
                // Already in correct format
            }
            // If it's in avatars/ format, convert to /storage/avatars/
            elseif (str_starts_with($avatar, 'avatars/')) {
                $data['avatar'] = '/storage/'.$avatar;
            }
            // Otherwise, assume it's just a filename
            else {
                $data['avatar'] = '/storage/avatars/'.$avatar;
            }
        }

        return $data;
    }
}
