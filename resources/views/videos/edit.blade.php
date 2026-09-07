<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Video — Panti Asuhan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #0d0b09;
        }

        .hero-bg {
            background-image:
                linear-gradient(
                    rgba(13, 11, 9, 0.65),
                    rgba(13, 11, 9, 0.92)
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
        <nav class="glass mx-3 mt-3 rounded-2xl px-4 py-3 md:mx-auto md:max-w-5xl md:px-6">
            <div class="flex items-center justify-between">

                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-gold/40 bg-black/40">
                        🎥
                    </div>

                    <div>
                        <h1 class="text-sm font-bold tracking-[0.2em] text-goldLight">
                            EDIT VIDEO
                        </h1>
                        <p class="hidden text-[10px] text-gray-400 sm:block">
                            Ubah kenangan video
                        </p>
                    </div>
                </a>

                <div class="flex items-center gap-3">
                    <a href="{{ route('videos.index') }}"
                       class="rounded-xl border border-white/10 px-4 py-2 text-sm text-gray-300 transition hover:border-gold/40 hover:text-goldLight">
                        Kembali
                    </a>
                </div>

            </div>
        </nav>
    </header>


    <!-- CONTENT -->
    <main class="min-h-screen px-5 pb-16 pt-32">

        <div class="mx-auto max-w-5xl">

            <section class="mb-8">
                <p class="mb-2 text-sm uppercase tracking-[0.25em] text-gold">
                    Edit Video
                </p>

                <h2 class="text-3xl font-bold sm:text-4xl">
                    Ubah Video Kenangan 🎬
                </h2>

                <p class="mt-3 max-w-2xl text-sm leading-6 text-gray-400">
                    Kamu bisa mengubah judul, deskripsi, atau mengganti file video.
                    Kalau tidak ingin mengganti video, kosongkan bagian file video.
                </p>
            </section>


            <!-- ERROR MESSAGE -->
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-400/30 bg-red-500/10 p-4 text-sm text-red-200">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <!-- FORM -->
            <section class="glass rounded-3xl p-6 sm:p-8">

                <form action="{{ route('videos.update', $video->id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="space-y-6">

                    @csrf
                    @method('PUT')

                    <!-- JUDUL -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-goldLight">
                            Judul Video
                        </label>

                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', $video->title) }}"
                            required
                            class="w-full rounded-2xl border border-white/10 bg-black/40 px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-600 focus:border-gold/50"
                            placeholder="Contoh: Momen Mabar Bareng">
                    </div>


                    <!-- DESKRIPSI -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-goldLight">
                            Deskripsi
                        </label>

                        <textarea
                            name="description"
                            rows="5"
                            class="w-full rounded-2xl border border-white/10 bg-black/40 px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-600 focus:border-gold/50"
                            placeholder="Ceritakan sedikit tentang video ini...">{{ old('description', $video->description) }}</textarea>
                    </div>


                    <!-- VIDEO LAMA -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-goldLight">
                            Video Saat Ini
                        </label>

                        <video
                            src="{{ asset('storage/' . $video->path) }}"
                            controls
                            class="w-full rounded-2xl border border-white/10 bg-black">
                        </video>
                    </div>


                    <!-- GANTI VIDEO -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-goldLight">
                            Ganti Video
                        </label>

                        <input
                            type="file"
                            name="video"
                            accept="video/mp4,video/quicktime,video/webm,video/x-matroska"
                            class="w-full rounded-2xl border border-white/10 bg-black/40 px-4 py-3 text-sm text-gray-300 file:mr-4 file:rounded-xl file:border-0 file:bg-gold file:px-4 file:py-2 file:text-sm file:font-semibold file:text-black hover:file:bg-goldLight">

                        <p class="mt-2 text-xs text-gray-500">
                            Kosongkan kalau tidak ingin mengganti video. Format: MP4, MOV, WEBM, MKV. Maksimal 30MB.
                        </p>
                    </div>


                    <!-- PREVIEW VIDEO BARU -->
                    <div id="previewBox" class="hidden">
                        <label class="mb-2 block text-sm font-semibold text-goldLight">
                            Preview Video Baru
                        </label>

                        <video
                            id="videoPreview"
                            controls
                            class="w-full rounded-2xl border border-white/10 bg-black">
                        </video>
                    </div>


                    <!-- BUTTON -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">

                        <a href="{{ route('videos.index') }}"
                           class="rounded-2xl border border-white/10 px-6 py-3 text-center text-sm text-gray-300 transition hover:border-gold/40 hover:text-goldLight">
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="rounded-2xl bg-gold px-6 py-3 text-sm font-bold text-black transition hover:bg-goldLight">
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </section>

        </div>

    </main>


    <script>
        const videoInput = document.querySelector('input[name="video"]');
        const previewBox = document.getElementById('previewBox');
        const videoPreview = document.getElementById('videoPreview');

        let previewUrl = null;

        videoInput.addEventListener('change', function () {
            const file = this.files[0];

            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            }

            if (file) {
                previewUrl = URL.createObjectURL(file);

                videoPreview.src = previewUrl;
                previewBox.classList.remove('hidden');
            } else {
                videoPreview.src = '';
                previewBox.classList.add('hidden');
            }
        });

        window.addEventListener('beforeunload', () => {
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
            }
        });
    </script>

</body>
</html>
