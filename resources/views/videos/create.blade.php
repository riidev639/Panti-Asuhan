<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Video — Panti Asuhan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #0d0b09;
            min-height: 100vh;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            background-image:
                linear-gradient(
                    rgba(13, 11, 9, 0.45),
                    rgba(13, 11, 9, 0.60)
                ),
                url('{{ asset('images/panti-bg.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .glass {
            background: rgba(20, 16, 12, 0.35);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(212, 168, 79, 0.20);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        }

        input,
        textarea {
            outline: none;
        }
    </style>
</head>

<body class="text-white">

    <main class="flex min-h-screen items-center justify-center px-5 py-10">

        <div class="glass w-full max-w-2xl rounded-3xl p-6 md:p-8">

            <div class="mb-8 text-center">

                <p class="mb-3 text-sm uppercase tracking-[0.3em] text-gold">
                    Upload Moment
                </p>

                <h1 class="text-3xl font-black text-goldLight md:text-4xl">
                    Upload Video
                </h1>

                <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-gray-300">
                    Simpan video kenangan Panti Asuhan agar bisa dilihat lagi nanti.
                </p>

            </div>


            @if ($errors->any())
                <div class="mb-5 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-200">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="title" class="mb-2 block text-sm font-semibold text-goldLight">
                        Judul Video
                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="Contoh: Mabar Sampai Lupa Waktu"
                        class="w-full rounded-2xl border border-white/10 bg-black/35 px-4 py-3
                               text-sm text-white placeholder:text-gray-500
                               focus:border-gold/60"
                        required>
                </div>


                <div>
                    <label for="description" class="mb-2 block text-sm font-semibold text-goldLight">
                        Deskripsi
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Ceritakan sedikit tentang video ini..."
                        class="w-full resize-none rounded-2xl border border-white/10 bg-black/35 px-4 py-3
                               text-sm text-white placeholder:text-gray-500
                               focus:border-gold/60">{{ old('description') }}</textarea>
                </div>


                <div>
                    <label for="video" class="mb-2 block text-sm font-semibold text-goldLight">
                        File Video
                    </label>

                    <input
                        type="file"
                        id="video"
                        name="video"
                        accept="video/mp4,video/webm,video/quicktime,video/x-matroska"
                        class="w-full rounded-2xl border border-white/10 bg-black/35 px-4 py-3
                               text-sm text-gray-300 file:mr-4 file:rounded-xl
                               file:border-0 file:bg-gold file:px-4 file:py-2
                               file:text-sm file:font-semibold file:text-black"
                        required>

                    <p class="mt-2 text-xs text-gray-400">
                        Format: MP4, MOV, WEBM, MKV. Maksimal 30MB.
                    </p>
                </div>


                <div id="previewBox" class="hidden rounded-2xl border border-white/10 bg-black/30 p-4">

                    <p class="mb-3 text-sm font-semibold text-goldLight">
                        Preview Video
                    </p>

                    <video
                        id="videoPreview"
                        controls
                        class="w-full rounded-2xl bg-black">
                    </video>

                </div>


                <div class="flex flex-col gap-3 pt-3 sm:flex-row">

                    <a
                        href="{{ route('videos.index') }}"
                        class="rounded-xl border border-white/10 px-5 py-3 text-center
                               text-sm font-semibold text-gray-300 transition
                               hover:border-gold/40 hover:text-goldLight">
                        ← Kembali
                    </a>

                    <button
                        type="submit"
                        class="flex-1 rounded-xl bg-gold px-5 py-3
                               text-sm font-bold text-black transition
                               hover:scale-[1.02]">
                        Upload Video
                    </button>

                </div>

            </form>

        </div>

    </main>


    <script>
        const videoInput = document.getElementById('video');
        const previewBox = document.getElementById('previewBox');
        const videoPreview = document.getElementById('videoPreview');

        let previewUrl = null;

        videoInput.addEventListener('change', () => {
            const file = videoInput.files[0];

            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            }

            if (!file) {
                previewBox.classList.add('hidden');
                videoPreview.src = '';
                return;
            }

            previewUrl = URL.createObjectURL(file);

            videoPreview.src = previewUrl;
            previewBox.classList.remove('hidden');
        });

        window.addEventListener('beforeunload', () => {
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
            }
        });
    </script>

</body>
</html>
