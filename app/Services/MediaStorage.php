<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class MediaStorage
{
    public function blobEnabled(): bool
    {
        return $this->blobToken() !== '' && $this->blobStoreId() !== '';
    }

    public function publicUrl(?string $path): string
    {
        if ($path === null || $path === '') {
            return '';
        }

        if (str_starts_with($path, 'https://') || str_starts_with($path, 'http://')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }

    public function isValidBlobUpload(string $url, string $pathname, string $directory): bool
    {
        if (! $this->blobEnabled() || $url === '' || $pathname === '') {
            return false;
        }

        $parts = parse_url($url);
        $expectedHost = strtolower($this->blobStoreId().'.public.blob.vercel-storage.com');
        $actualPath = isset($parts['path']) ? rawurldecode(ltrim($parts['path'], '/')) : '';

        return ($parts['scheme'] ?? '') === 'https'
            && strtolower($parts['host'] ?? '') === $expectedHost
            && ! isset($parts['query'])
            && ! isset($parts['fragment'])
            && $actualPath === $pathname
            && str_starts_with($pathname, trim($directory, '/').'/')
            && ! str_contains($pathname, '..')
            && strlen($pathname) <= 255;
    }

    public function delete(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        if (! str_starts_with($path, 'https://') && ! str_starts_with($path, 'http://')) {
            Storage::disk('public')->delete($path);

            return;
        }

        if (! $this->isManagedBlobUrl($path)) {
            return;
        }

        $response = Http::timeout(15)
            ->retry(2, 200)
            ->withToken($this->blobToken())
            ->withHeaders([
                'x-api-version' => '12',
                'x-vercel-blob-store-id' => $this->blobStoreId(),
            ])
            ->post('https://vercel.com/api/blob/delete', [
                'urls' => [$path],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Vercel Blob gagal menghapus file: HTTP '.$response->status());
        }
    }

    public function isManagedBlobUrl(string $url): bool
    {
        if (! $this->blobEnabled()) {
            return false;
        }

        $parts = parse_url($url);

        return ($parts['scheme'] ?? '') === 'https'
            && strtolower($parts['host'] ?? '') === strtolower($this->blobStoreId().'.public.blob.vercel-storage.com');
    }

    private function blobToken(): string
    {
        return trim((string) config('services.vercel_blob.token'));
    }

    private function blobStoreId(): string
    {
        $storeId = trim((string) config('services.vercel_blob.store_id'));

        if ($storeId === '') {
            $tokenParts = explode('_', $this->blobToken());
            $storeId = $tokenParts[3] ?? '';
        }

        return str_starts_with($storeId, 'store_')
            ? substr($storeId, strlen('store_'))
            : $storeId;
    }
}
