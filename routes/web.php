<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\VideoController;
use App\Models\Photo;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\Route;

// ================= PUBLIC =================

Route::get('/', function () {
    $latestPhotos = Photo::with('user')
        ->latest()
        ->take(3)
        ->get();

    $latestVideos = Video::with('user')
        ->latest()
        ->take(2)
        ->get();

    $photoCount = Photo::count();
    $videoCount = Video::count();
    $memberCount = User::count();
    $memoryCount = $photoCount + $videoCount;

    return view('welcome', compact(
        'latestPhotos',
        'latestVideos',
        'photoCount',
        'videoCount',
        'memberCount',
        'memoryCount'
    ));
})->name('home');

Route::get('/foto', [PhotoController::class, 'index'])
    ->name('photos.index');

Route::get('/video', [VideoController::class, 'index'])
    ->name('videos.index');

Route::get('/members', function () {
    return view('members.index');
})->name('members.index');

Route::get('/timeline', function () {
    $photos = Photo::with('user')
        ->latest()
        ->get()
        ->map(function ($photo) {
            return [
                'type' => 'photo',
                'title' => $photo->title,
                'description' => $photo->description,
                'path' => $photo->path,
                'username' => $photo->user?->username ?? 'Unknown',
                'created_at' => $photo->created_at,
            ];
        });

    $videos = Video::with('user')
        ->latest()
        ->get()
        ->map(function ($video) {
            return [
                'type' => 'video',
                'title' => $video->title,
                'description' => $video->description,
                'path' => $video->path,
                'username' => $video->user?->username ?? 'Unknown',
                'created_at' => $video->created_at,
            ];
        });

    $memories = $photos
        ->concat($videos)
        ->sortByDesc('created_at')
        ->values();

    return view('timeline.index', compact('memories'));
})->name('timeline.index');

// ================= AUTH =================

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:6,1')
        ->name('login.process');
});

// ================= MEMBER AREA =================

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ================= FOTO =================
    // Upload foto hanya untuk member login
    Route::get('/foto/upload', [PhotoController::class, 'create'])
        ->name('photos.create');

    Route::post('/foto/upload', [PhotoController::class, 'store'])
        ->name('photos.store');

    // Edit dan hapus hanya untuk pemilik foto
    Route::get('/foto/{photo}/edit', [PhotoController::class, 'edit'])
        ->name('photos.edit');

    Route::put('/foto/{photo}', [PhotoController::class, 'update'])
        ->name('photos.update');

    Route::delete('/foto/{photo}', [PhotoController::class, 'destroy'])
        ->name('photos.destroy');

    // ================= VIDEO =================
    // Upload video hanya untuk member login
    Route::get('/video/upload', [VideoController::class, 'create'])
        ->name('videos.create');

    Route::post('/video/upload', [VideoController::class, 'store'])
        ->name('videos.store');

    // Edit dan hapus hanya untuk pemilik video
    Route::get('/video/{video}/edit', [VideoController::class, 'edit'])
        ->name('videos.edit');

    Route::put('/video/{video}', [VideoController::class, 'update'])
        ->name('videos.update');

    Route::delete('/video/{video}', [VideoController::class, 'destroy'])
        ->name('videos.destroy');

    // ================= LOGOUT =================
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // ================= GANTI PASSWORD =================
    Route::get('/ganti-password', [AuthController::class, 'showChangePassword'])
        ->name('password.change');

    Route::put('/ganti-password', [AuthController::class, 'updatePassword'])
        ->name('password.update');
});
