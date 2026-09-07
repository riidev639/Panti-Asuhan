<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'video' => ['required', 'file', 'mimes:mp4,mov,webm,mkv', 'max:30720'],
        ], [
            'title.required' => 'Judul video wajib diisi.',
            'video.required' => 'File video wajib dipilih.',
            'video.mimes' => 'Format video harus mp4, mov, webm, atau mkv.',
            'video.max' => 'Ukuran video maksimal 30MB.',
        ]);

        $file = $request->file('video');

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

        try {
            Video::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($path);

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

    public function update(Request $request, Video $video)
    {
        if ($video->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'video' => ['nullable', 'file', 'mimes:mp4,mov,webm,mkv', 'max:30720'],
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ];

        $newPath = null;

        if ($request->hasFile('video')) {
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
                Storage::disk('public')->delete($newPath);
            }

            throw $exception;
        }

        if ($newPath !== null) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()
            ->route('videos.index')
            ->with('success', 'Video berhasil diperbarui.');
    }

    public function destroy(Video $video)
    {
        if ($video->user_id !== Auth::id()) {
            abort(403);
        }

        $path = $video->path;
        $video->delete();
        Storage::disk('public')->delete($path);

        return redirect()
            ->route('videos.index')
            ->with('success', 'Video berhasil dihapus.');
    }
}
