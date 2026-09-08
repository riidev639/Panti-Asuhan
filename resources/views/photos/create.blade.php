<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Upload Foto — Panti Asuhan</title>

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

        .gold-line {
            background: linear-gradient(
                90deg,
                transparent,
                #d4a84f,
                transparent
            );
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

                <div class="flex items-center gap-3">

                    <span class="hidden text-sm text-gray-400 sm:block">
                        {{ Auth::user()->username }}
                    </span>

                <a href="{{ route('dashboard') }}"
                       class="rounded-xl border border-white/10 px-3 py-2
                              text-sm text-gray-300 transition
                              hover:border-gold/40 hover:text-goldLight">
                        ← Dashboard
                    </a>

                </div>

            </div>

        </nav>

    </header>


    <!-- CONTENT -->
    <main class="min-h-screen px-5 pb-16 pt-32">

        <div class="mx-auto max-w-3xl">

            <!-- HEADER -->
            <div class="mb-8">

                <p class="mb-2 text-sm uppercase tracking-[0.25em] text-gold">
                    Memories
                </p>

                <h2 class="text-3xl font-bold md:text-4xl">
                    Upload Foto 📸
                </h2>

                <p class="mt-3 text-sm leading-6 text-gray-400">
                    Simpan momen Mabar dan kenangan bersama keluarga
                    Panti Asuhan.
                </p>

            </div>


            <!-- FORM CARD -->
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


                <!-- FORM -->
                <form
                    action="{{ route('photos.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    data-blob-upload="photo"
                    data-blob-enabled="{{ config('services.vercel_blob.token') && config('services.vercel_blob.store_id') ? '1' : '0' }}">

                    @csrf


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
                            value="{{ old('title') }}"
                            placeholder="Contoh: Mabar sampai pagi 😂"
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
                            <span class="text-xs text-gray-600">
                                (opsional)
                            </span>
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Ceritakan sedikit tentang momen ini..."
                            class="w-full resize-none rounded-xl border
                                   border-white/10 bg-black/30
                                   px-4 py-3 text-sm text-white
                                   outline-none placeholder:text-gray-600
                                   focus:border-gold/60
                                   focus:ring-1 focus:ring-gold/30">{{ old('description') }}</textarea>

                    </div>


                    <!-- FILE -->
                    <div class="mb-6">

                        <label for="photo"
                               class="mb-2 block text-sm font-medium
                                      text-gray-300">
                            Pilih Foto
                        </label>

                        <label
                            for="photo"
                            class="group flex cursor-pointer flex-col
                                   items-center justify-center rounded-2xl
                                   border border-dashed border-gold/30
                                   bg-black/20 px-5 py-10 text-center
                                   transition hover:border-gold/60
                                   hover:bg-gold/5">

                            <div class="mb-4 flex h-16 w-16 items-center
                                        justify-center rounded-2xl
                                        bg-gold/10 text-3xl
                                        transition group-hover:scale-110">
                                📸
                            </div>

                            <p class="font-semibold text-gray-200">
                                Klik untuk memilih foto
                            </p>

                            <p class="mt-2 text-xs text-gray-500">
                                JPG, JPEG, PNG atau WEBP — maksimal 10 MB
                            </p>

                            <p id="fileName"
                               class="mt-3 hidden text-sm text-goldLight">
                            </p>

                        </label>

                        <input
                            type="file"
                            id="photo"
                            name="photo"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="hidden"
                            required>

                    </div>


                    <!-- PREVIEW -->
                    <div id="previewContainer" class="mb-6 hidden">

                        <p class="mb-2 text-sm font-medium text-gray-300">
                            Preview
                        </p>

                        <div class="overflow-hidden rounded-2xl
                                    border border-white/10 bg-black/30">

                            <img
                                id="preview"
                                src=""
                                alt="Preview foto"
                                class="max-h-[400px] w-full object-contain">

                        </div>

                    </div>


                    <!-- INFO -->
                    <div class="mb-6 rounded-2xl border border-gold/10
                                bg-gold/5 p-4">

                        <div class="flex gap-3">

                            <div class="text-xl">
                                👤
                            </div>

                            <div>

                                <p class="text-sm font-semibold
                                          text-goldLight">
                                    Foto akan tercatat atas nama
                                </p>

                                <p class="mt-1 text-sm text-gray-300">
                                    {{ Auth::user()->username }}
                                </p>

                                <p class="mt-1 text-xs leading-5 text-gray-500">
                                    Tanggal dan waktu upload akan dicatat
                                    otomatis oleh sistem.
                                </p>

                            </div>

                        </div>

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
                                   transition hover:border-white/20
                                   hover:bg-white/5">
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="flex-1 rounded-xl bg-gold px-5 py-3
                                   font-semibold text-black transition
                                   hover:scale-[1.02]
                                   hover:bg-goldLight
                                   active:scale-[0.98]">
                            📸 Upload Foto
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </main>


    <!-- FOOTER -->
    <footer class="border-t border-white/10 px-5 py-8">

        <p class="text-center text-[11px] text-gray-600">
            © {{ date('Y') }} Panti Asuhan — Rumah Kenangan
        </p>

    </footer>


    <!-- JAVASCRIPT PREVIEW -->
    <script>

        const photoInput = document.getElementById('photo');
        const preview = document.getElementById('preview');
        const previewContainer =
            document.getElementById('previewContainer');
        const fileName =
            document.getElementById('fileName');

        photoInput.addEventListener('change', function () {

            const file = this.files[0];

            if (!file) {
                previewContainer.classList.add('hidden');
                fileName.classList.add('hidden');
                return;
            }

            fileName.textContent = file.name;
            fileName.classList.remove('hidden');

            const reader = new FileReader();

            reader.onload = function (event) {

                preview.src = event.target.result;

                previewContainer.classList.remove('hidden');

            };

            reader.readAsDataURL(file);

        });

    </script>

</body>
</html>
