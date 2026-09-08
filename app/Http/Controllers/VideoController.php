<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Services\MediaStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with('user')
            ->latest()
            ->get();

        return view('videos.index', compact('videos'));
    }

    public function create()
    {
        return view('videos.create');
    }

    public function store(Request $request, MediaStorage $mediaStorage)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'video' => ['nullable', 'required_without:blob_url', 'file', 'mimes:mp4,mov,webm,mkv', 'max:30720'],
            'blob_url' => ['nullable', 'required_without:video', 'url:https', 'max:2048'],
            'blob_pathname' => ['nullable', 'required_with:blob_url', 'string', 'max:255'],
            'blob_mime_type' => ['nullable', 'required_with:blob_url', 'in:video/mp4,video/quicktime,video/webm,video/x-matroska'],
            'blob_size' => ['nullable', 'required_with:blob_url', 'integer', 'min:1', 'max:31457280'],
            'blob_original_name' => ['nullable', 'required_with:blob_url', 'string', 'max:255'],
        ], [
            'title.required' => 'Judul video wajib diisi.',
            'video.required' => 'File video wajib dipilih.',
            'video.mimes' => 'Format video harus mp4, mov, webm, atau mkv.',
            'video.max' => 'Ukuran video maksimal 30MB.',
        ]);

        $file = $request->file('video');

        if ($request->filled('blob_url')) {
            if (! $mediaStorage->isValidBlobUpload(
                $validated['blob_url'],
                $validated['blob_pathname'],
                'videos',
            )) {
                throw ValidationException::withMessages([
                    'video' => 'Hasil upload video tidak valid. Silakan pilih file dan coba lagi.',
                ]);
            }

            $path = $validated['blob_url'];
            $originalName = $validated['blob_original_name'];
            $mimeType = $validated['blob_mime_type'];
            $size = (int) $validated['blob_size'];
        } else {
            try {
                $path = $file->store('videos', 'public');

                if ($path === false) {
                    throw new RuntimeException('Video gagal disimpan ke storage public.');
                }
            } catch (Throwable $exception) {
                report($exception);

                return back()
                    ->withErrors(['video' => 'Video gagal disimpan. Periksa izin folder storage lalu coba lagi.'])
                    ->withInput();
            }

            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $size = $file->getSize();
        }

        try {
            Video::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'path' => $path,
                'original_name' => $originalName,
                'mime_type' => $mimeType,
                'size' => $size,
            ]);
        } catch (Throwable $exception) {
            $this->cleanupFile($mediaStorage, $path);

            throw $exception;
        }

        return redirect()
            ->route('videos.index')
            ->with('success', 'Video berhasil diupload.');
    }

    public function edit(Video $video)
    {
        if ($video->user_id !== Auth::id()) {
            abort(403);
        }

        return view('videos.edit', compact('video'));
    }

    public function update(Request $request, Video $video, MediaStorage $mediaStorage)
    {
        if ($video->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'video' => ['nullable', 'file', 'mimes:mp4,mov,webm,mkv', 'max:30720'],
            'blob_url' => ['nullable', 'url:https', 'max:2048'],
            'blob_pathname' => ['nullable', 'required_with:blob_url', 'string', 'max:255'],
            'blob_mime_type' => ['nullable', 'required_with:blob_url', 'in:video/mp4,video/quicktime,video/webm,video/x-matroska'],
            'blob_size' => ['nullable', 'required_with:blob_url', 'integer', 'min:1', 'max:31457280'],
            'blob_original_name' => ['nullable', 'required_with:blob_url', 'string', 'max:255'],
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ];

        $newPath = null;

        if ($request->filled('blob_url')) {
            if (! $mediaStorage->isValidBlobUpload(
                $validated['blob_url'],
                $validated['blob_pathname'],
                'videos',
            )) {
                throw ValidationException::withMessages([
                    'video' => 'Hasil upload video baru tidak valid. Silakan coba lagi.',
                ]);
            }

            $newPath = $validated['blob_url'];
            $data['path'] = $newPath;
            $data['original_name'] = $validated['blob_original_name'];
            $data['mime_type'] = $validated['blob_mime_type'];
            $data['size'] = (int) $validated['blob_size'];
        } elseif ($request->hasFile('video')) {
            $file = $request->file('video');

            try {
                $newPath = $file->store('videos', 'public');

                if ($newPath === false) {
                    throw new RuntimeException('Video baru gagal disimpan ke storage public.');
                }
            } catch (Throwable $exception) {
                report($exception);

                return back()
                    ->withErrors(['video' => 'Video baru gagal disimpan. Periksa izin folder storage lalu coba lagi.'])
                    ->withInput();
            }

            $data['path'] = $newPath;
            $data['original_name'] = $file->getClientOriginalName();
            $data['mime_type'] = $file->getMimeType();
            $data['size'] = $file->getSize();
        }

        $oldPath = $video->path;

        try {
            $video->update($data);
        } catch (Throwable $exception) {
            if ($newPath !== null) {
                $this->cleanupFile($mediaStorage, $newPath);
            }

            throw $exception;
        }

        if ($newPath !== null) {
            $this->cleanupFile($mediaStorage, $oldPath);
        }

        return redirect()
            ->route('videos.index')
            ->with('success', 'Video berhasil diperbarui.');
    }

    public function destroy(Video $video, MediaStorage $mediaStorage)
    {
        if ($video->user_id !== Auth::id()) {
            abort(403);
        }

        $path = $video->path;
        $video->delete();
        $this->cleanupFile($mediaStorage, $path);

        return redirect()
            ->route('videos.index')
            ->with('success', 'Video berhasil dihapus.');
    }

    private function cleanupFile(MediaStorage $mediaStorage, ?string $path): void
    {
        try {
            $mediaStorage->delete($path);
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
