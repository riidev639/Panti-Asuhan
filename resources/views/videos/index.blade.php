<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Video — Panti Asuhan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #0d0b09;
            min-height: 100vh;
            color: white;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;

            background-image:
                linear-gradient(
                    rgba(13, 11, 9, 0.45),
                    rgba(13, 11, 9, 0.55)
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

        .video-card {
            cursor: pointer;
            transition:
                transform 0.3s ease,
                border-color 0.3s ease,
                box-shadow 0.3s ease;
        }

        .video-card:hover {
            transform: translateY(-5px);
            border-color: rgba(212, 168, 79, 0.45);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
        }

        .video-thumbnail video {
            transition: transform 0.5s ease;
        }

        .video-card:hover .video-thumbnail video {
            transform: scale(1.05);
        }

        .play-button {
            transition:
                transform 0.3s ease,
                background 0.3s ease;
        }

        .video-card:hover .play-button {
            transform: scale(1.12);
            background: rgba(212, 168, 79, 0.95);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(212, 168, 79, 0.5);
            border-radius: 999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(212, 168, 79, 0.8);
        }
    </style>
</head>


<body class="text-white">

    <!-- =====================================================
         NAVBAR
    ====================================================== -->
    <header class="fixed left-0 right-0 top-0 z-50">

        <nav class="glass mx-3 mt-3 rounded-2xl px-4 py-3
                    md:mx-auto md:max-w-7xl md:px-6">

            <div class="flex items-center justify-between">

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center
                                rounded-xl border border-gold/40
                                bg-black/30 text-xl">
                        🏠
                    </div>

                    <div>
                        <h1 class="text-sm font-bold tracking-[0.2em]
                                   text-goldLight md:text-base">
                            PANTI ASUHAN
                        </h1>

                        <p class="hidden text-[10px] text-gray-400 sm:block">
                            Rumah Kenangan
                        </p>
                    </div>

                </a>


                <!-- Desktop Menu -->
                <div class="hidden items-center gap-6 md:flex">

                    <a href="{{ route('home') }}"
                       class="text-sm text-gray-300 transition hover:text-goldLight">
                        Beranda
                    </a>

                    <a href="{{ route('photos.index') }}"
                       class="text-sm text-gray-300 transition hover:text-goldLight">
                        Foto
                    </a>

                    <a href="{{ route('videos.index') }}"
                       class="text-sm font-semibold text-goldLight">
                        Video
                    </a>

                    <a href="{{ route('members.index') }}"
                       class="text-sm text-gray-300 transition hover:text-goldLight">
                        Members
                    </a>

                    <a href="{{ route('timeline.index') }}"
                       class="text-sm text-gray-300 transition hover:text-goldLight">
                        Timeline
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="rounded-xl border border-gold/50 px-4 py-2
                                  text-sm text-goldLight transition
                                  hover:bg-gold hover:text-black">
                            Dashboard
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="rounded-xl border border-red-400/40 px-4 py-2
                                           text-sm text-red-200 transition
                                           hover:bg-red-500/15">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="rounded-xl border border-gold/50 px-4 py-2
                                  text-sm text-goldLight transition
                                  hover:bg-gold hover:text-black">
                            Login
                        </a>
                    @endauth

                </div>


                <!-- Mobile Button -->
                <button
                    id="menuButton"
                    class="rounded-xl border border-gold/30
                           px-3 py-2 text-xl md:hidden">
                    ☰
                </button>

            </div>


            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden pt-4 md:hidden">

                <div class="flex flex-col gap-2
                            border-t border-white/10 pt-3">

                    <a href="{{ route('home') }}"
                       class="rounded-lg px-3 py-3 text-sm hover:bg-white/5">
                        🏠 Beranda
                    </a>

                    <a href="{{ route('photos.index') }}"
                       class="rounded-lg px-3 py-3 text-sm hover:bg-white/5">
                        📸 Galeri Foto
                    </a>

                    <a href="{{ route('videos.index') }}"
                       class="rounded-lg bg-gold/10 px-3 py-3
                              text-sm text-goldLight">
                        🎥 Galeri Video
                    </a>

                    <a href="{{ route('members.index') }}"
                       class="rounded-lg px-3 py-3 text-sm hover:bg-white/5">
                        👥 Members
                    </a>

                    <a href="{{ route('timeline.index') }}"
                       class="rounded-lg px-3 py-3 text-sm hover:bg-white/5">
                        📖 Timeline
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="rounded-lg border border-gold/40 px-3 py-3
                                  text-sm text-goldLight">
                            📂 Dashboard
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full rounded-lg border border-red-400/40
                                           px-3 py-3 text-left text-sm text-red-200">
                                🚪 Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="rounded-lg border border-gold/40 px-3 py-3
                                  text-sm text-goldLight">
                            🔐 Login
                        </a>
                    @endauth

                </div>

            </div>

        </nav>

    </header>



    <!-- =====================================================
         MAIN
    ====================================================== -->
    <main class="px-5 pb-20 pt-32">

        <div class="mx-auto max-w-7xl">


            <!-- PAGE HEADER -->
            <div class="mb-10 text-center">

                <p class="mb-3 text-sm uppercase tracking-[0.3em] text-gold">
                    Our Moments
                </p>

                <h2 class="text-4xl font-black tracking-wide
                           text-goldLight sm:text-5xl md:text-6xl">
                    GALERI VIDEO
                </h2>

                <div class="mx-auto my-5 h-px w-40
                            bg-gradient-to-r from-transparent
                            via-gold to-transparent">
                </div>

                <p class="mx-auto max-w-xl text-sm leading-7 text-gray-300
                          md:text-base">
                    Kumpulan video dan momen random yang
                    pernah terjadi selama kita bermain bersama.
                </p>

                <div class="mt-6">
                    @auth
                        <a href="{{ route('videos.create') }}"
                           class="inline-flex items-center gap-2 rounded-xl
                                  bg-gold px-5 py-3 text-sm font-semibold
                                  text-black transition hover:scale-105">
                            ➕ Upload Video
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 rounded-xl
                                  border border-gold/50 bg-black/35
                                  px-5 py-3 text-sm font-semibold
                                  text-goldLight backdrop-blur-md transition
                                  hover:bg-gold hover:text-black">
                            🔐 Login untuk Upload
                        </a>
                    @endauth
                </div>

            </div>



            <!-- FILTER -->
            <div class="mb-10 flex flex-wrap justify-center gap-2">

                <button
                    class="rounded-full border border-gold
                           bg-gold px-5 py-2 text-sm font-semibold
                           text-black">
                    Semua
                </button>

                <button
                    class="rounded-full border border-white/10
                           bg-white/5 px-5 py-2 text-sm text-gray-300
                           transition hover:border-gold/50
                           hover:text-goldLight">
                    Mabar
                </button>

                <button
                    class="rounded-full border border-white/10
                           bg-white/5 px-5 py-2 text-sm text-gray-300
                           transition hover:border-gold/50
                           hover:text-goldLight">
                    Random
                </button>

                <button
                    class="rounded-full border border-white/10
                           bg-white/5 px-5 py-2 text-sm text-gray-300
                           transition hover:border-gold/50
                           hover:text-goldLight">
                    Event
                </button>

            </div>



            <!-- =================================================
                 VIDEO GRID
            ================================================== -->
            <div class="grid grid-cols-1 gap-6
                        md:grid-cols-2
                        xl:grid-cols-3">

                @forelse ($videos ?? [] as $video)

                    <div class="video-card glass overflow-hidden rounded-2xl"
                         onclick="openVideoPlayer({{ $loop->index }})">

                        <!-- Thumbnail -->
                        <div class="video-thumbnail relative aspect-video overflow-hidden">

                            <video
                                src="{{ asset('storage/' . $video->path) }}"
                                muted
                                playsinline
                                preload="metadata"
                                class="h-full w-full object-cover">
                            </video>

                            <!-- Dark Overlay -->
                            <div class="absolute inset-0 bg-black/35"></div>


                            <!-- Play Button -->
                            <button
                                type="button"
                                class="play-button absolute left-1/2 top-1/2
                                       flex h-16 w-16 -translate-x-1/2
                                       -translate-y-1/2 items-center
                                       justify-center rounded-full
                                       bg-black/70 text-2xl
                                       shadow-xl backdrop-blur-md">
                                ▶
                            </button>


                            <!-- Category -->
                            <span
                                class="absolute bottom-3 left-3
                                       rounded-full bg-black/60
                                       px-3 py-1 text-[10px]
                                       text-goldLight backdrop-blur-md">
                                VIDEO
                            </span>

                        </div>


                        <!-- Info -->
                        <div class="p-5">

                            <h3 class="font-semibold">
                                {{ $video->title }}
                            </h3>

                            @if ($video->description)
                                <p class="mt-2 line-clamp-2 text-sm leading-6 text-gray-400">
                                    {{ $video->description }}
                                </p>
                            @else
                                <p class="mt-2 text-sm leading-6 text-gray-500">
                                    Tidak ada deskripsi
                                </p>
                            @endif


                            <div class="mt-4 flex items-center justify-between gap-3
                                        border-t border-white/10 pt-3">

                                <span class="truncate text-[11px] text-gray-400">
                                    👤 {{ $video->user?->username ?? 'Unknown' }}
                                </span>

                                <span class="shrink-0 text-[11px] text-gray-500">
                                    {{ $video->created_at?->format('d M Y') ?? '-' }}
                                </span>

                            </div>


                            @auth
                                @if ($video->user_id === auth()->id())

                                    <div class="mt-4 flex gap-2" onclick="event.stopPropagation()">

                                        <a
                                            href="{{ route('videos.edit', $video) }}"
                                            class="flex-1 rounded-xl border border-gold/40
                                                   px-3 py-2 text-center text-sm
                                                   text-goldLight transition
                                                   hover:bg-gold hover:text-black">
                                            ✏️ Edit
                                        </a>

                                        <form
                                            action="{{ route('videos.destroy', $video) }}"
                                            method="POST"
                                            class="flex-1"
                                            onsubmit="return confirm('Yakin ingin menghapus video ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="w-full rounded-xl border
                                                       border-red-500/30 px-3 py-2
                                                       text-sm text-red-300 transition
                                                       hover:bg-red-500/10">
                                                🗑️ Hapus
                                            </button>

                                        </form>

                                    </div>

                                @endif
                            @endauth

                        </div>

                    </div>

                @empty

                    <div class="col-span-full">

                        <div class="glass rounded-2xl p-10 text-center">

                            <div class="text-5xl">
                                🎥
                            </div>

                            <h3 class="mt-4 text-xl font-semibold">
                                Belum ada video
                            </h3>

                            <p class="mt-2 text-sm text-gray-400">
                                Belum ada video kenangan yang diupload.
                            </p>

                            @auth
                                <a
                                    href="{{ route('videos.create') }}"
                                    class="mt-5 inline-block rounded-xl
                                           bg-gold px-5 py-3 text-sm
                                           font-semibold text-black">
                                    Upload Video
                                </a>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="mt-5 inline-block rounded-xl
                                           border border-gold/40 px-5 py-3
                                           text-sm font-semibold text-goldLight
                                           transition hover:bg-gold hover:text-black">
                                    Login untuk Upload
                                </a>
                            @endauth

                        </div>

                    </div>

                @endforelse

            </div>



            <!-- FUTURE UPLOAD -->
            <div class="mt-10 text-center">

                <div class="glass mx-auto max-w-xl rounded-2xl p-6">

                    <div class="text-3xl">
                        🎥
                    </div>

                    <h3 class="mt-3 font-semibold">
                        Lebih banyak video akan datang
                    </h3>

                    <p class="mt-2 text-sm text-gray-400">
                        @auth
                            Kamu bisa menambahkan video kenangan baru melalui tombol upload di atas.
                        @else
                            Hanya anggota Panti Asuhan yang bisa login dan upload video kenangan.
                        @endauth
                    </p>

                </div>

            </div>

        </div>

    </main>


    <!-- =====================================================
         VIDEO PLAYER
    ====================================================== -->
    <div
        id="videoPlayer"
        class="fixed inset-0 z-[100] hidden items-center
               justify-center bg-black/85 p-4 backdrop-blur-md"
    >

        <!-- Close -->
        <button
            onclick="closeVideoPlayer()"
            class="absolute right-5 top-5 z-[110]
                   flex h-11 w-11 items-center justify-center
                   rounded-full border border-white/20
                   bg-black/60 text-2xl
                   transition hover:bg-gold hover:text-black">
            ×
        </button>


        <!-- Video -->
        <div class="w-full max-w-5xl">

            <video
                id="mainVideo"
                controls
                playsinline
                class="max-h-[75vh] w-full rounded-2xl
                       bg-black shadow-2xl">
            </video>


            <!-- Video Info -->
            <div
                class="mt-4 rounded-2xl border border-white/10
                       bg-black/60 p-4 text-center
                       backdrop-blur-xl">

                <h3
                    id="videoTitle"
                    class="font-semibold text-goldLight">
                    Judul Video
                </h3>

                <p
                    id="videoDescription"
                    class="mt-1 text-sm text-gray-400">
                    Deskripsi video
                </p>

                <div
                    class="mt-3 flex flex-wrap justify-center
                           gap-4 text-[11px] text-gray-400">

                    <span id="videoUploader">
                        👤 Unknown
                    </span>

                    <span id="videoDate">
                        📅 -
                    </span>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         FOOTER
    ====================================================== -->
    <footer class="border-t border-white/10 px-5 py-10">

        <div class="mx-auto max-w-7xl text-center">

            <h4 class="font-bold tracking-[0.2em] text-goldLight">
                PANTI ASUHAN
            </h4>

            <p class="mt-2 text-xs text-gray-500">
                Rumah kecil untuk menyimpan kenangan besar.
            </p>

            <p class="mt-6 text-[11px] text-gray-600">
                © {{ date('Y') }} Panti Asuhan.
                All memories reserved.
            </p>

        </div>

    </footer>



    <!-- =====================================================
         JAVASCRIPT
    ====================================================== -->
    <script>
        const menuButton = document.getElementById('menuButton');
        const mobileMenu = document.getElementById('mobileMenu');

        menuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });


        /* =====================================================
           VIDEO PLAYER
        ===================================================== */

        const videos = [
            @foreach ($videos ?? [] as $video)
                {
                    video: @json(asset('storage/' . $video->path)),
                    title: @json($video->title),
                    description: @json($video->description ?? 'Tidak ada deskripsi.'),
                    uploader: @json($video->user?->username ?? 'Unknown'),
                    date: @json($video->created_at?->format('d M Y') ?? '-'),
                    type: @json($video->mime_type ?? 'video/mp4')
                },
            @endforeach
        ];

        const videoPlayer = document.getElementById('videoPlayer');
        const mainVideo = document.getElementById('mainVideo');
        const videoTitle = document.getElementById('videoTitle');
        const videoDescription = document.getElementById('videoDescription');
        const videoUploader = document.getElementById('videoUploader');
        const videoDate = document.getElementById('videoDate');

        function openVideoPlayer(index) {
            if (!videos.length) {
                return;
            }

            const video = videos[index];

            videoTitle.textContent = video.title;
            videoDescription.textContent = video.description;
            videoUploader.textContent = '👤 ' + video.uploader;
            videoDate.textContent = '📅 ' + video.date;

            mainVideo.src = video.video;
            mainVideo.load();

            videoPlayer.classList.remove('hidden');
            videoPlayer.classList.add('flex');

            document.body.style.overflow = 'hidden';

            mainVideo.play().catch(() => {
                // Kalau browser menahan autoplay, user tinggal klik play manual.
            });
        }

        function closeVideoPlayer() {
            mainVideo.pause();
            mainVideo.src = '';

            videoPlayer.classList.add('hidden');
            videoPlayer.classList.remove('flex');

            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                if (!videoPlayer.classList.contains('hidden')) {
                    closeVideoPlayer();
                }
            }
        });

        videoPlayer.addEventListener('click', (event) => {
            if (event.target === videoPlayer) {
                closeVideoPlayer();
            }
        });
    </script>

</body>
</html>
