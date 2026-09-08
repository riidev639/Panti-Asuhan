<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Timeline — Panti Asuhan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
        html {
            scroll-behavior: smooth;
        }

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
                    rgba(13, 11, 9, 0.65)
                ),
                url('{{ asset('images/panti-bg.png') }}');

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .glass {
            background: rgba(20, 16, 12, 0.38);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);

            border:
                1px solid
                rgba(212, 168, 79, 0.20);

            box-shadow:
                0 8px 32px
                rgba(0, 0, 0, 0.18);
        }

        .timeline-line {
            background:
                linear-gradient(
                    to bottom,
                    rgba(212, 168, 79, 0),
                    rgba(212, 168, 79, 0.5),
                    rgba(212, 168, 79, 0)
                );
        }

        .timeline-card {
            transition:
                transform 0.3s ease,
                border-color 0.3s ease,
                box-shadow 0.3s ease;
        }

        .timeline-card:hover {
            transform: translateY(-3px);
            border-color: rgba(212, 168, 79, 0.4);
            box-shadow: 0 14px 35px rgba(0, 0, 0, 0.25);
        }

        .memory-media img,
        .memory-media video {
            transition: transform 0.5s ease;
        }

        .timeline-card:hover .memory-media img,
        .timeline-card:hover .memory-media video {
            transform: scale(1.05);
        }
    </style>
</head>


<body class="text-white">

    <!-- =====================================================
         NAVBAR
    ====================================================== -->
    <header class="fixed left-0 right-0 top-0 z-50">

        <nav
            class="glass mx-3 mt-3 rounded-2xl
                   px-4 py-3
                   md:mx-auto md:max-w-7xl md:px-6"
        >

            <div class="flex items-center justify-between">

                <!-- LOGO -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10
                               items-center justify-center
                               rounded-xl
                               border border-gold/40
                               bg-black/30
                               text-xl"
                    >
                        🏠
                    </div>

                    <div>
                        <h1
                            class="text-sm font-bold
                                   tracking-[0.2em]
                                   text-goldLight
                                   md:text-base"
                        >
                            PANTI ASUHAN
                        </h1>

                        <p
                            class="hidden text-[10px]
                                   text-gray-400 sm:block"
                        >
                            Rumah Kenangan
                        </p>
                    </div>

                </a>


                <!-- DESKTOP MENU -->
                <div
                    class="hidden items-center
                           gap-6 md:flex"
                >

                    <a
                            href="{{ route('home') }}"
                        class="text-sm text-gray-300
                               transition
                               hover:text-goldLight"
                    >
                        Beranda
                    </a>

                    <a
                            href="{{ route('photos.index') }}"
                        class="text-sm text-gray-300
                               transition
                               hover:text-goldLight"
                    >
                        Foto
                    </a>

                    <a
                            href="{{ route('videos.index') }}"
                        class="text-sm text-gray-300
                               transition
                               hover:text-goldLight"
                    >
                        Video
                    </a>

                    <a
                            href="{{ route('members.index') }}"
                        class="text-sm text-gray-300
                               transition
                               hover:text-goldLight"
                    >
                        Members
                    </a>

                    <a
                            href="{{ route('timeline.index') }}"
                        class="text-sm font-semibold
                               text-goldLight"
                    >
                        Timeline
                    </a>

                    @auth
                        <a
                            href="{{ route('dashboard') }}"
                            class="rounded-xl
                                   border border-gold/50
                                   px-4 py-2
                                   text-sm text-goldLight
                                   transition
                                   hover:bg-gold
                                   hover:text-black"
                        >
                            Dashboard
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button
                                type="submit"
                                class="rounded-xl
                                       border border-red-400/40
                                       px-4 py-2
                                       text-sm text-red-200
                                       transition
                                       hover:bg-red-500/15"
                            >
                                Logout
                            </button>
                        </form>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="rounded-xl
                                   border border-gold/50
                                   px-4 py-2
                                   text-sm text-goldLight
                                   transition
                                   hover:bg-gold
                                   hover:text-black"
                        >
                            Login
                        </a>
                    @endauth

                </div>


                <!-- MOBILE BUTTON -->
                <button
                    id="menuButton"
                    class="rounded-xl
                           border border-gold/30
                           px-3 py-2
                           text-xl md:hidden"
                >
                    ☰
                </button>

            </div>


            <!-- MOBILE MENU -->
            <div
                id="mobileMenu"
                class="hidden pt-4 md:hidden"
            >

                <div
                    class="flex flex-col gap-2
                           border-t border-white/10
                           pt-3"
                >

                    <a
                            href="{{ route('home') }}"
                        class="rounded-lg px-3 py-3
                               text-sm hover:bg-white/5"
                    >
                        🏠 Beranda
                    </a>

                    <a
                            href="{{ route('photos.index') }}"
                        class="rounded-lg px-3 py-3
                               text-sm hover:bg-white/5"
                    >
                        📸 Galeri Foto
                    </a>

                    <a
                            href="{{ route('videos.index') }}"
                        class="rounded-lg px-3 py-3
                               text-sm hover:bg-white/5"
                    >
                        🎥 Galeri Video
                    </a>

                    <a
                            href="{{ route('members.index') }}"
                        class="rounded-lg px-3 py-3
                               text-sm hover:bg-white/5"
                    >
                        👥 Members
                    </a>

                    <a
                            href="{{ route('timeline.index') }}"
                        class="rounded-lg
                               bg-gold/10
                               px-3 py-3
                               text-sm
                               text-goldLight"
                    >
                        📖 Timeline
                    </a>

                    @auth
                        <a
                            href="{{ route('dashboard') }}"
                            class="rounded-lg
                                   border border-gold/40
                                   px-3 py-3
                                   text-sm
                                   text-goldLight"
                        >
                            📂 Dashboard
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button
                                type="submit"
                                class="w-full rounded-lg
                                       border border-red-400/40
                                       px-3 py-3
                                       text-left text-sm
                                       text-red-200"
                            >
                                🚪 Logout
                            </button>
                        </form>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="rounded-lg
                                   border border-gold/40
                                   px-3 py-3
                                   text-sm
                                   text-goldLight"
                        >
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
    <main class="px-5 pb-24 pt-32">

        <div class="mx-auto max-w-4xl">

            <!-- HEADER -->
            <div class="mb-14 text-center">

                <p
                    class="mb-3 text-sm uppercase
                           tracking-[0.3em]
                           text-gold"
                >
                    Our Story
                </p>

                <h2
                    class="text-4xl font-black
                           tracking-wide
                           text-goldLight
                           sm:text-5xl md:text-6xl"
                >
                    TIMELINE
                </h2>

                <div
                    class="mx-auto my-5 h-px w-40
                           bg-gradient-to-r
                           from-transparent
                           via-gold
                           to-transparent"
                ></div>

                <p
                    class="mx-auto max-w-xl
                           text-sm leading-7
                           text-gray-300
                           md:text-base"
                >
                    Jejak kecil dari berbagai
                    kenangan yang pernah tercipta
                    di Panti Asuhan.
                </p>

            </div>



            <!-- TIMELINE -->
            <div class="relative">

                <!-- GARIS -->
                <div
                    class="timeline-line absolute
                           bottom-0 left-5 top-0
                           w-px
                           md:left-1/2
                           md:-translate-x-1/2"
                ></div>


                @php
                    $videoIndex = 0;
                @endphp


                @forelse ($memories ?? [] as $memory)

                    @php
                        $type = $memory['type'] ?? 'moment';
                        $isPhoto = $type === 'photo';
                        $isVideo = $type === 'video';

                        $createdAt = $memory['created_at'] ?? now();

                        $dateText = $createdAt instanceof \Carbon\CarbonInterface
                            ? $createdAt->format('d M Y')
                            : \Carbon\Carbon::parse($createdAt)->format('d M Y');

                        $timeText = $createdAt instanceof \Carbon\CarbonInterface
                            ? $createdAt->format('H:i')
                            : \Carbon\Carbon::parse($createdAt)->format('H:i');
                    @endphp


                    <div
                        class="relative mb-12
                               pl-14
                               md:grid md:grid-cols-2
                               md:gap-10 md:pl-0"
                    >

                        <!-- DOT -->
                        <div
                            class="absolute left-[9px]
                                   top-6 z-10
                                   flex h-6 w-6
                                   items-center justify-center
                                   rounded-full
                                   border border-gold
                                   bg-dark
                                   text-xs
                                   md:left-1/2
                                   md:-translate-x-1/2"
                        >
                            @if ($isPhoto)
                                📸
                            @elseif ($isVideo)
                                🎥
                            @else
                                🎮
                            @endif
                        </div>


                        <!-- CARD -->
                        <div
                            class="timeline-card glass
                                   rounded-2xl p-5
                                   {{ $loop->odd ? 'md:col-start-1' : 'md:col-start-2' }}"
                        >

                            <div
                                class="mb-4 flex items-center
                                       justify-between gap-3"
                            >

                                <div>
                                    <p class="text-xs text-gold">
                                        {{ $dateText }}
                                    </p>

                                    <p class="mt-1 text-[11px] text-gray-500">
                                        {{ $timeText }} WIB
                                    </p>
                                </div>


                                <span
                                    class="rounded-full
                                           border border-gold/20
                                           bg-gold/10
                                           px-3 py-1
                                           text-[10px]
                                           text-goldLight"
                                >
                                    {{ strtoupper($type) }}
                                </span>

                            </div>


                            @if ($isPhoto)

                                <a
                                    href="{{ $memory['url'] }}"
                                    target="_blank"
                                    class="memory-media block aspect-video
                                           overflow-hidden rounded-xl
                                           bg-black/30"
                                >
                                    <img
                                        src="{{ $memory['url'] }}"
                                        class="h-full w-full object-cover"
                                        alt="{{ $memory['title'] ?? 'Foto Kenangan' }}"
                                    >
                                </a>

                            @elseif ($isVideo)

                                <button
                                    type="button"
                                    onclick="openTimelineVideo({{ $videoIndex }})"
                                    class="memory-media relative block aspect-video
                                           w-full overflow-hidden rounded-xl
                                           bg-black/40 text-left"
                                >

                                    <video
                                        src="{{ $memory['url'] }}"
                                        muted
                                        playsinline
                                        preload="metadata"
                                        class="h-full w-full object-cover"
                                    ></video>

                                    <div class="absolute inset-0 bg-black/35"></div>

                                    <div
                                        class="absolute left-1/2 top-1/2
                                               flex h-16 w-16
                                               -translate-x-1/2
                                               -translate-y-1/2
                                               items-center justify-center
                                               rounded-full
                                               bg-gold
                                               text-2xl
                                               text-black
                                               shadow-xl"
                                    >
                                        ▶
                                    </div>

                                </button>

                                @php
                                    $videoIndex++;
                                @endphp

                            @else

                                <div
                                    class="flex aspect-video
                                           items-center justify-center
                                           rounded-xl
                                           bg-black/20
                                           text-5xl"
                                >
                                    🎮
                                </div>

                            @endif


                            <div class="mt-4">

                                <h3 class="font-bold text-goldLight">
                                    {{ $memory['title'] ?? 'Kenangan Panti Asuhan' }}
                                </h3>

                                <p
                                    class="mt-2 text-sm
                                           leading-6
                                           text-gray-400"
                                >
                                    {{ $memory['description'] ?? 'Tidak ada deskripsi untuk kenangan ini.' }}
                                </p>

                                <p class="mt-4 text-xs text-gray-500">
                                    👤 Uploaded by
                                    <span class="text-goldLight">
                                        {{ $memory['username'] ?? 'Unknown' }}
                                    </span>
                                </p>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="relative pl-14 md:pl-0">

                        <div
                            class="absolute left-[9px]
                                   top-6 z-10
                                   flex h-6 w-6
                                   items-center justify-center
                                   rounded-full
                                   border border-gold
                                   bg-dark
                                   text-xs
                                   md:left-1/2
                                   md:-translate-x-1/2"
                        >
                            🏠
                        </div>

                        <div
                            class="timeline-card glass
                                   rounded-2xl p-8
                                   text-center
                                   md:mx-auto md:max-w-lg"
                        >

                            <div class="text-5xl">
                                📖
                            </div>

                            <h3 class="mt-4 text-xl font-bold text-goldLight">
                                Timeline masih kosong
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-gray-400">
                                Foto dan video yang diupload nanti
                                akan otomatis muncul di timeline ini.
                            </p>

                            @auth
                                <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:justify-center">

                                    <a
                                        href="{{ route('photos.create') }}"
                                        class="rounded-xl bg-gold
                                               px-5 py-3
                                               text-sm font-semibold
                                               text-black"
                                    >
                                        Upload Foto
                                    </a>

                                    <a
                                        href="{{ route('videos.create') }}"
                                        class="rounded-xl
                                               border border-gold/40
                                               px-5 py-3
                                               text-sm font-semibold
                                               text-goldLight
                                               transition
                                               hover:bg-gold
                                               hover:text-black"
                                    >
                                        Upload Video
                                    </a>

                                </div>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="mt-5 inline-block rounded-xl
                                           border border-gold/40
                                           px-5 py-3
                                           text-sm font-semibold
                                           text-goldLight
                                           transition
                                           hover:bg-gold
                                           hover:text-black"
                                >
                                    Login untuk Upload
                                </a>
                            @endauth

                        </div>

                    </div>

                @endforelse

            </div>



            <!-- END -->
            <div class="mt-16 text-center">

                <div
                    class="inline-flex
                           items-center gap-2
                           rounded-full
                           border border-white/10
                           bg-black/20
                           px-5 py-3
                           text-xs text-gray-500"
                >
                    🏠
                    <span>
                        Ini baru permulaan...
                    </span>
                </div>

            </div>

        </div>

    </main>



    <!-- =====================================================
         VIDEO PLAYER MODAL
    ====================================================== -->
    <div
        id="videoPlayer"
        class="fixed inset-0 z-[100] hidden
               items-center justify-center
               bg-black/85 p-4
               backdrop-blur-md"
    >

        <button
            onclick="closeTimelineVideo()"
            class="absolute right-5 top-5 z-[110]
                   flex h-11 w-11
                   items-center justify-center
                   rounded-full
                   border border-white/20
                   bg-black/60
                   text-2xl
                   transition
                   hover:bg-gold
                   hover:text-black"
        >
            ×
        </button>

        <div class="w-full max-w-5xl">

            <video
                id="mainVideo"
                controls
                playsinline
                class="max-h-[75vh] w-full
                       rounded-2xl bg-black
                       shadow-2xl"
            ></video>

            <div
                class="mt-4 rounded-2xl
                       border border-white/10
                       bg-black/60 p-4
                       text-center
                       backdrop-blur-xl"
            >

                <h3
                    id="videoTitle"
                    class="font-semibold text-goldLight"
                >
                    Judul Video
                </h3>

                <p
                    id="videoDescription"
                    class="mt-1 text-sm text-gray-400"
                >
                    Deskripsi video
                </p>

                <div
                    class="mt-3 flex flex-wrap
                           justify-center gap-4
                           text-[11px] text-gray-400"
                >

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

            <h4
                class="font-bold
                       tracking-[0.2em]
                       text-goldLight"
            >
                PANTI ASUHAN
            </h4>

            <p class="mt-2 text-xs text-gray-500">
                Rumah kecil untuk menyimpan
                kenangan besar.
            </p>

            <p class="mt-6 text-[11px] text-gray-600">
                © {{ date('Y') }}
                Panti Asuhan.
                All memories reserved.
            </p>

        </div>

    </footer>



    <!-- =====================================================
         JAVASCRIPT
    ====================================================== -->
    <script>
        const menuButton =
            document.getElementById('menuButton');

        const mobileMenu =
            document.getElementById('mobileMenu');

        menuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        document
            .querySelectorAll('#mobileMenu a')
            .forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                });
            });


        const timelineVideos = [
            @foreach (collect($memories ?? [])->where('type', 'video')->values() as $memory)
                {
                    video: @json($memory['url']),
                    title: @json($memory['title'] ?? 'Video Kenangan'),
                    description: @json($memory['description'] ?? 'Tidak ada deskripsi.'),
                    uploader: @json($memory['username'] ?? 'Unknown'),
                    date: @json(
                        ($memory['created_at'] instanceof \Carbon\CarbonInterface)
                            ? $memory['created_at']->format('d M Y')
                            : \Carbon\Carbon::parse($memory['created_at'])->format('d M Y')
                    )
                },
            @endforeach
        ];

        const videoPlayer =
            document.getElementById('videoPlayer');

        const mainVideo =
            document.getElementById('mainVideo');

        const videoTitle =
            document.getElementById('videoTitle');

        const videoDescription =
            document.getElementById('videoDescription');

        const videoUploader =
            document.getElementById('videoUploader');

        const videoDate =
            document.getElementById('videoDate');


        function openTimelineVideo(index) {
            if (!timelineVideos.length) {
                return;
            }

            const video = timelineVideos[index];

            videoTitle.textContent =
                video.title;

            videoDescription.textContent =
                video.description;

            videoUploader.textContent =
                '👤 ' + video.uploader;

            videoDate.textContent =
                '📅 ' + video.date;

            mainVideo.src =
                video.video;

            mainVideo.load();

            videoPlayer.classList.remove('hidden');
            videoPlayer.classList.add('flex');

            document.body.style.overflow =
                'hidden';

            mainVideo.play().catch(() => {});
        }


        function closeTimelineVideo() {
            mainVideo.pause();
            mainVideo.src = '';

            videoPlayer.classList.add('hidden');
            videoPlayer.classList.remove('flex');

            document.body.style.overflow = '';
        }


        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                if (!videoPlayer.classList.contains('hidden')) {
                    closeTimelineVideo();
                }
            }
        });


        videoPlayer.addEventListener('click', (event) => {
            if (event.target === videoPlayer) {
                closeTimelineVideo();
            }
        });
    </script>

</body>
</html>
