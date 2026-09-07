<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class PhotoController extends Controller
{
    /**
     * Menampilkan galeri foto.
     */
    public function index()
    {
        $photos = Photo::with('user')
            ->latest()
            ->get();

        return view('photos.index', compact('photos'));
    }

    /**
     * Menampilkan halaman upload foto.
     */
    public function create()
    {
        return view('photos.create');
    }

    /**
     * Menyimpan foto baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],
        ], [
            'title.required' => 'Judul foto wajib diisi.',
            'photo.required' => 'Silakan pilih foto terlebih dahulu.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format harus JPG, JPEG, PNG, atau WEBP.',
            'photo.max' => 'Ukuran foto maksimal 10 MB.',
        ]);

        try {
            $path = $request->file('photo')->store('photos', 'public');

            if ($path === false) {
                throw new RuntimeException('Foto gagal disimpan ke storage public.');
            }
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withErrors(['photo' => 'Foto gagal disimpan. Periksa izin folder storage lalu coba lagi.'])
                ->withInput();
        }

        try {
            Photo::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'path' => $path,
            ]);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($path);

            throw $exception;
        }

        return redirect()->route('photos.index')
            ->with('success', 'Foto berhasil diupload! 📸');
    }

    /**
     * Menampilkan halaman edit.
     */
    public function edit(Photo $photo)
    {
        // Hanya pemilik foto yang boleh edit.
        abort_unless($photo->user_id === Auth::id(), 403);

        return view('photos.edit', compact('photo'));
    }

    /**
     * Memperbarui foto.
     */
    public function update(Request $request, Photo $photo)
    {
        // Hanya pemilik foto yang boleh mengedit.
        abort_unless($photo->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],
        ]);

        $newPath = null;

        // Kalau user memilih foto baru, simpan dahulu agar file lama tetap aman
        // jika proses upload gagal.
        if ($request->hasFile('photo')) {
            try {
                $newPath = $request->file('photo')->store('photos', 'public');

                if ($newPath === false) {
                    throw new RuntimeException('Foto baru gagal disimpan ke storage public.');
                }
            } catch (Throwable $exception) {
                report($exception);

                return back()
                    ->withErrors(['photo' => 'Foto baru gagal disimpan. Periksa izin folder storage lalu coba lagi.'])
                    ->withInput();
            }
        }

        $oldPath = $photo->path;
        $photo->title = $validated['title'];
        $photo->description = $validated['description'] ?? null;

        if ($newPath !== null) {
            $photo->path = $newPath;
        }

        try {
            $photo->save();
        } catch (Throwable $exception) {
            if ($newPath !== null) {
                Storage::disk('public')->delete($newPath);
            }

            throw $exception;
        }

        if ($newPath !== null) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()->route('photos.index')
            ->with('success', 'Foto berhasil diperbarui! ✏️');
    }

    /**
     * Menghapus foto.
     */
    public function destroy(Photo $photo)
    {
        // Hanya pemilik foto yang boleh menghapus.
        abort_unless($photo->user_id === Auth::id(), 403);

        $path = $photo->path;
        $photo->delete();

        // Data dihapus dahulu. Jika pembersihan file gagal, galeri tetap tidak
        // menyimpan referensi menuju file yang sudah hilang.
        Storage::disk('public')->delete($path);

        return redirect()->route('photos.index')
            ->with('success', 'Foto berhasil dihapus! 🗑️');
    }
}
