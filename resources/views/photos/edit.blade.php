<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Foto — Panti Asuhan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #0d0b09;
        }

        .hero-bg {
            background-image:
                linear-gradient(
                    rgba(13, 11, 9, 0.68),
                    rgba(13, 11, 9, 0.94)
                ),
                url('{{ asset('images/panti-bg.png') }}');

            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .glass {
            background: rgba(20, 16, 12, 0.68);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(212, 168, 79, 0.18);
        }
    </style>
</head>

<body class="hero-bg min-h-screen text-white">

    <!-- NAVBAR -->
    <header class="fixed left-0 right-0 top-0 z-50">

        <nav class="glass mx-3 mt-3 rounded-2xl px-4 py-3
                    md:mx-auto md:max-w-7xl md:px-6">

            <div class="flex items-center justify-between">

                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center
                                rounded-xl border border-gold/40 bg-black/40">
                        🏠
                    </div>

                    <div>
                        <h1 class="text-sm font-bold tracking-[0.2em]
                                   text-goldLight">
                            PANTI ASUHAN
                        </h1>

                        <p class="hidden text-[10px] text-gray-400 sm:block">
                            Rumah Kenangan
                        </p>
                    </div>

                </a>

                    <a href="{{ route('photos.index') }}"
                   class="rounded-xl border border-white/10 px-3 py-2
                          text-sm text-gray-300 transition
                          hover:border-gold/40 hover:text-goldLight">
                    ← Galeri Foto
                </a>

            </div>

        </nav>

    </header>


    <!-- CONTENT -->
    <main class="min-h-screen px-5 pb-16 pt-32">

        <div class="mx-auto max-w-3xl">

            <div class="mb-8">

                <p class="mb-2 text-sm uppercase tracking-[0.25em] text-gold">
                    Memories
                </p>

                <h2 class="text-3xl font-bold md:text-4xl">
                    Edit Foto ✏️
                </h2>

                <p class="mt-3 text-sm text-gray-400">
                    Perbaiki informasi atau ganti foto kenangan kamu.
                </p>

            </div>


            <div class="glass rounded-3xl p-6 shadow-2xl md:p-8">

                <!-- ERROR -->
                @if ($errors->any())

                    <div class="mb-6 rounded-2xl border border-red-500/20
                                bg-red-500/10 p-4">

                        <p class="mb-2 text-sm font-semibold text-red-300">
                            Ada yang perlu diperbaiki:
                        </p>

                        <ul class="list-inside list-disc space-y-1
                                   text-xs text-red-300">

                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>

                @endif


                <form
                    action="{{ route('photos.update', $photo) }}"
                    method="POST"
                    enctype="multipart/form-data"
                    data-blob-upload="photo"
                    data-blob-enabled="{{ config('services.vercel_blob.token') && config('services.vercel_blob.store_id') ? '1' : '0' }}">

                    @csrf
                    @method('PUT')


                    <!-- FOTO SEKARANG -->
                    <div class="mb-6">

                        <label class="mb-2 block text-sm font-medium
                                      text-gray-300">
                            Foto Saat Ini
                        </label>

                        <div class="overflow-hidden rounded-2xl
                                    border border-white/10 bg-black/30">

                            <img
                                src="{{ $photo->url }}"
                                alt="{{ $photo->title }}"
                                class="max-h-[400px] w-full object-contain">

                        </div>

                    </div>


                    <!-- JUDUL -->
                    <div class="mb-6">

                        <label for="title"
                               class="mb-2 block text-sm font-medium
                                      text-gray-300">
                            Judul Foto
                        </label>

                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title', $photo->title) }}"
                            required
                            class="w-full rounded-xl border
                                   border-white/10 bg-black/30
                                   px-4 py-3 text-sm text-white
                                   outline-none placeholder:text-gray-600
                                   focus:border-gold/60
                                   focus:ring-1 focus:ring-gold/30">

                    </div>


                    <!-- DESKRIPSI -->
                    <div class="mb-6">

                        <label for="description"
                               class="mb-2 block text-sm font-medium
                                      text-gray-300">
                            Cerita / Deskripsi
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="w-full resize-none rounded-xl border
                                   border-white/10 bg-black/30
                                   px-4 py-3 text-sm text-white
                                   outline-none
                                   focus:border-gold/60
                                   focus:ring-1 focus:ring-gold/30">{{ old('description', $photo->description) }}</textarea>

                    </div>


                    <!-- GANTI FOTO -->
                    <div class="mb-6">

                        <label for="photo"
                               class="mb-2 block text-sm font-medium
                                      text-gray-300">
                            Ganti Foto
                            <span class="text-xs text-gray-600">
                                (opsional)
                            </span>
                        </label>

                        <input
                            type="file"
                            id="photo"
                            name="photo"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="block w-full cursor-pointer rounded-xl
                                   border border-white/10 bg-black/30
                                   text-sm text-gray-400
                                   file:mr-4 file:border-0
                                   file:bg-gold file:px-4 file:py-3
                                   file:font-semibold file:text-black
                                   hover:file:bg-goldLight">

                        <p class="mt-2 text-xs text-gray-500">
                            JPG, JPEG, PNG atau WEBP — maksimal 10 MB.
                        </p>

                    </div>


                    <!-- BUTTON -->
                    <p data-upload-progress class="mb-3 hidden text-sm text-goldLight" aria-live="polite"></p>
                    <p data-upload-error class="mb-3 hidden rounded-xl border border-red-500/20 bg-red-500/10 p-3 text-sm text-red-300" role="alert"></p>

                    <div class="flex flex-col gap-3 sm:flex-row">

                        <a
                            href="{{ route('photos.index') }}"
                            class="flex-1 rounded-xl border
                                   border-white/10 px-5 py-3 text-center
                                   text-sm font-semibold text-gray-300
                                   transition hover:bg-white/5">
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="flex-1 rounded-xl bg-gold px-5 py-3
                                   font-semibold text-black transition
                                   hover:bg-goldLight">
                            ✏️ Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </main>

</body>
</html>
