<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panti Asuhan — Memories</title>

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

        .hero-bg {
            background-image:
                linear-gradient(
                    to bottom,
                    rgba(13, 11, 9, 0.45),
                    rgba(13, 11, 9, 0.85)
                ),
                url('{{ asset('images/panti-bg.png') }}');

            background-size: cover;
            background-position: center;
            background-attachment: fixed;
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

        .gold-line {
            background: linear-gradient(
                90deg,
                transparent,
                #d4a84f,
                transparent
            );
        }

        .memory-card {
            transition:
                transform 0.3s ease,
                border-color 0.3s ease,
                box-shadow 0.3s ease;
        }

        .memory-card:hover {
            transform: translateY(-5px);
            border-color: rgba(212, 168, 79, 0.45);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
        }

        .memory-card img,
        .memory-card video {
            transition: transform 0.5s ease;
        }

        .memory-card:hover img,
        .memory-card:hover video {
            transform: scale(1.05);
        }
    </style>
</head>

<body class="text-white">

    <!-- ================= NAVBAR ================= -->
    <header class="fixed left-0 right-0 top-0 z-50">

        <nav class="glass mx-3 mt-3 rounded-2xl px-4 py-3
                    md:mx-auto md:max-w-7xl md:px-6">

            <div class="flex items-center justify-between">

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center
                                rounded-xl border border-gold/40
                                bg-black/40 text-xl">
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
                       class="text-sm font-semibold text-goldLight">
                        Beranda
                    </a>

                    <a href="{{ route('photos.index') }}"
                       class="text-sm text-gray-300 transition hover:text-goldLight">
                        Foto
                    </a>

                    <a href="{{ route('videos.index') }}"
                       class="text-sm text-gray-300 transition hover:text-goldLight">
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

                    <a href="#tentang"
                       class="text-sm text-gray-300 transition hover:text-goldLight">
                        Tentang
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

                            <button
                                type="submit"
                                class="rounded-xl border border-red-400/40
                                       px-4 py-2 text-sm text-red-200 transition
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


                <!-- Mobile Menu Button -->
                <button
                    id="menuButton"
                    class="rounded-xl border border-gold/30
                           px-3 py-2 text-xl md:hidden">
                    ☰
                </button>

            </div>


            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden pt-4 md:hidden">

                <div class="flex flex-col gap-2 border-t border-white/10 pt-3">

                    <a href="{{ route('home') }}"
                       class="rounded-lg bg-gold/10 px-3 py-3 text-sm text-goldLight">
                        🏠 Beranda
                    </a>

                    <a href="{{ route('photos.index') }}"
                       class="rounded-lg px-3 py-3 text-sm hover:bg-white/5">
                        📸 Galeri Foto
                    </a>

                    <a href="{{ route('videos.index') }}"
                       class="rounded-lg px-3 py-3 text-sm hover:bg-white/5">
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

                    <a href="#tentang"
                       class="rounded-lg px-3 py-3 text-sm hover:bg-white/5">
                        ℹ️ Tentang
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="rounded-lg border border-gold/40 px-3 py-3
                                  text-sm text-goldLight">
                            📂 Dashboard
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button
                                type="submit"
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


    <!-- ================= HERO ================= -->
    <main>

        <section id="beranda"
                 class="hero-bg flex min-h-screen items-center
                        justify-center px-5 pt-24">

            <div class="mx-auto max-w-4xl text-center">

                <div class="mb-6 inline-flex items-center gap-2 rounded-full
                            border border-gold/30 bg-black/30 px-4 py-2
                            text-xs text-goldLight backdrop-blur-md">
                    🎮 Sebuah keluarga yang terbentuk dari Mabar
                </div>

                <h2 class="text-5xl font-black tracking-[0.12em]
                           text-goldLight drop-shadow-2xl
                           sm:text-6xl md:text-8xl">
                    PANTI ASUHAN
                </h2>

                <div class="mx-auto my-6 h-px w-48 gold-line"></div>

                <p class="mx-auto max-w-2xl text-base leading-7
                          text-gray-200 md:text-lg md:leading-8">
                    Tempat menyimpan cerita, tawa, kekacauan,
                    dan momen-momen kecil yang terjadi
                    selama perjalanan kita bersama.
                </p>

                <div class="mt-8 flex flex-col justify-center gap-3
                            sm:flex-row">

                    <a href="#foto"
                       class="rounded-xl bg-gold px-6 py-3 font-semibold
                              text-black transition hover:scale-105">
                        📸 Lihat Kenangan
                    </a>

                    <a href="#tentang"
                       class="rounded-xl border border-white/20
                              bg-black/30 px-6 py-3 font-semibold
                              text-white backdrop-blur-md transition
                              hover:border-gold/50">
                        Tentang Kami
                    </a>

                </div>

            </div>

        </section>


        <!-- ================= STATISTIK ================= -->
        <section class="relative z-10 -mt-10 px-5">

            <div class="glass mx-auto grid max-w-5xl grid-cols-2
                        overflow-hidden rounded-2xl md:grid-cols-4">

                <div class="p-5 text-center">
                    <div class="text-2xl">📸</div>

                    <p class="mt-2 text-2xl font-bold text-goldLight">
                        {{ $photoCount ?? 0 }}
                    </p>

                    <p class="text-xs text-gray-400">Foto</p>
                </div>

                <div class="border-white/10 p-5 text-center
                            max-md:border-l md:border-l">
                    <div class="text-2xl">🎥</div>

                    <p class="mt-2 text-2xl font-bold text-goldLight">
                        {{ $videoCount ?? 0 }}
                    </p>

                    <p class="text-xs text-gray-400">Video</p>
                </div>

                <div class="border-t border-white/10 p-5 text-center
                            md:border-l md:border-t-0">
                    <div class="text-2xl">👥</div>

                    <p class="mt-2 text-2xl font-bold text-goldLight">
                        {{ $memberCount ?? 0 }}
                    </p>

                    <p class="text-xs text-gray-400">Members</p>
                </div>

                <div class="border-t border-white/10 p-5 text-center
                            md:border-l md:border-t-0">
                    <div class="text-2xl">❤️</div>

                    <p class="mt-2 text-2xl font-bold text-goldLight">
                        {{ $memoryCount ?? 0 }}
                    </p>

                    <p class="text-xs text-gray-400">Kenangan</p>
                </div>

            </div>

        </section>


        <!-- ================= FOTO ================= -->
        <section id="foto" class="px-5 py-24">

            <div class="mx-auto max-w-7xl">

                <div class="mb-10 flex items-end justify-between gap-5">

                    <div>
                        <p class="mb-2 text-sm uppercase tracking-[0.25em]
                                  text-gold">
                            Memories
                        </p>

                        <h3 class="text-3xl font-bold md:text-4xl">
                            Galeri Foto Terbaru
                        </h3>

                        <p class="mt-2 text-sm text-gray-400">
                            Momen-momen yang pernah kita abadikan.
                        </p>
                    </div>

                    <a href="{{ route('photos.index') }}"
                       class="hidden text-sm text-goldLight hover:text-gold
                              sm:block">
                        Lihat Semua →
                    </a>

                </div>


                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2
                            lg:grid-cols-3">

                    @forelse ($latestPhotos ?? [] as $photo)

                        <a href="{{ route('photos.index') }}"
                           class="memory-card group overflow-hidden rounded-2xl
                                  border border-white/10 bg-brown">

                            <div class="aspect-[4/3] overflow-hidden">

                                <img
                                    src="{{ $photo->url }}"
                                    alt="{{ $photo->title ?? 'Foto Kenangan' }}"
                                    class="h-full w-full object-cover">

                            </div>

                            <div class="p-4">

                                <h4 class="font-semibold">
                                    {{ $photo->title ?? 'Foto Kenangan' }}
                                </h4>

                                <p class="mt-1 text-xs text-gray-500">
                                    👤 {{ $photo->user?->username ?? 'Unknown' }}
                                    •
                                    {{ $photo->created_at?->format('d M Y') }}
                                </p>

                            </div>

                        </a>

                    @empty

                        <div class="col-span-full">

                            <div class="glass rounded-2xl p-10 text-center">

                                <div class="text-5xl">📷</div>

                                <h4 class="mt-4 text-xl font-semibold">
                                    Belum ada foto
                                </h4>

                                <p class="mt-2 text-sm text-gray-400">
                                    Foto yang diupload nanti akan tampil di sini.
                                </p>

                                @auth
                                    <a href="{{ route('photos.create') }}"
                                       class="mt-5 inline-block rounded-xl
                                              bg-gold px-5 py-3 text-sm
                                              font-semibold text-black">
                                        Upload Foto
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
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

            </div>

        </section>


        <!-- ================= VIDEO ================= -->
        <section id="video" class="px-5 py-24">

            <div class="mx-auto max-w-7xl">

                <div class="mb-10 flex items-end justify-between gap-5">

                    <div>
                        <p class="mb-2 text-sm uppercase tracking-[0.25em]
                                  text-gold">
                            Moments
                        </p>

                        <h3 class="text-3xl font-bold md:text-4xl">
                            Galeri Video Terbaru
                        </h3>

                        <p class="mt-2 text-sm text-gray-400">
                            Karena beberapa kenangan lebih seru kalau bergerak. 🎥
                        </p>
                    </div>

                    <a href="{{ route('videos.index') }}"
                       class="hidden text-sm text-goldLight hover:text-gold
                              sm:block">
                        Lihat Semua →
                    </a>

                </div>


                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    @forelse ($latestVideos ?? [] as $video)

                        <div
                            onclick="openVideoPlayer({{ $loop->index }})"
                            class="memory-card glass group cursor-pointer overflow-hidden
                                   rounded-2xl p-5">

                            <div class="relative aspect-video overflow-hidden rounded-xl bg-black/40">

                                <video
                                    src="{{ $video->url }}"
                                    muted
                                    playsinline
                                    preload="metadata"
                                    class="h-full w-full object-cover">
                                </video>

                                <div class="absolute inset-0 bg-black/35"></div>

                                <div class="absolute left-1/2 top-1/2
                                            flex h-16 w-16 -translate-x-1/2
                                            -translate-y-1/2 items-center
                                            justify-center rounded-full
                                            bg-black/70 text-2xl
                                            shadow-xl backdrop-blur-md">
                                    ▶
                                </div>

                            </div>

                            <h4 class="mt-4 font-semibold">
                                {{ $video->title ?? 'Video Kenangan' }}
                            </h4>

                            <p class="mt-1 text-xs text-gray-500">
                                👤 {{ $video->user?->username ?? 'Unknown' }}
                                •
                                {{ $video->created_at?->format('d M Y') }}
                            </p>

                        </div>

                    @empty

                        <div class="col-span-full">

                            <div class="glass rounded-2xl p-10 text-center">

                                <div class="text-5xl">🎥</div>

                                <h4 class="mt-4 text-xl font-semibold">
                                    Belum ada video
                                </h4>

                                <p class="mt-2 text-sm text-gray-400">
                                    Video yang diupload nanti akan tampil di sini.
                                </p>

                                @auth
                                    <a href="{{ route('videos.create') }}"
                                       class="mt-5 inline-block rounded-xl
                                              bg-gold px-5 py-3 text-sm
                                              font-semibold text-black">
                                        Upload Video
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
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

            </div>

        </section>


        <!-- ================= MEMBERS ================= -->
        <section id="members" class="px-5 py-24">

            <div class="mx-auto max-w-7xl">

                <div class="mb-12 text-center">

                    <p class="mb-2 text-sm uppercase tracking-[0.25em]
                              text-gold">
                        Our Family
                    </p>

                    <h3 class="text-3xl font-bold md:text-4xl">
                        Members Panti Asuhan
                    </h3>

                    <p class="mx-auto mt-3 max-w-2xl text-sm leading-6 text-gray-400">
                        Keluarga kecil yang terbentuk dari mabar, canda, dan kenangan bersama.
                    </p>

                    <div class="mx-auto mt-6 h-px w-40 gold-line"></div>

                </div>


                <!-- BARIS ATAS: BAPAK & MAMAK -->
                <div class="mx-auto flex max-w-6xl flex-col items-center justify-center gap-5 md:flex-row">

                    <!-- BAPAK -->
                    <div class="glass w-full rounded-3xl p-6 text-center transition
                                hover:-translate-y-1 hover:border-gold/40
                                sm:w-[300px]">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center
                                    rounded-full border border-gold/30 bg-gold/10 text-4xl">
                            👑
                        </div>

                        <p class="mt-5 text-xs uppercase tracking-[0.25em] text-gold">
                            Bapak
                        </p>

                        <h4 class="mt-2 text-xl font-bold text-goldLight">
                            1ridescent2
                        </h4>

                        <p class="mt-2 text-sm text-gray-400">
                            Pemimpin keluarga Panti Asuhan.
                        </p>

                    </div>


                    <!-- MAMAK 1 -->
                    <div class="glass w-full rounded-3xl p-6 text-center transition
                                hover:-translate-y-1 hover:border-gold/40
                                sm:w-[300px]">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center
                                    rounded-full border border-gold/30 bg-gold/10 text-4xl">
                            ❤️
                        </div>

                        <p class="mt-5 text-xs uppercase tracking-[0.25em] text-gold">
                            Mamak 1
                        </p>

                        <h4 class="mt-2 text-xl font-bold text-goldLight">
                            nisrr_322
                        </h4>

                        <p class="mt-2 text-sm text-gray-400">
                            Penjaga suasana dan kehangatan keluarga.
                        </p>

                    </div>


                    <!-- MAMAK 2 -->
                    <div class="glass w-full rounded-3xl p-6 text-center transition
                                hover:-translate-y-1 hover:border-gold/40
                                sm:w-[300px]">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center
                                    rounded-full border border-gold/30 bg-gold/10 text-4xl">
                            💐
                        </div>

                        <p class="mt-5 text-xs uppercase tracking-[0.25em] text-gold">
                            Mamak 2
                        </p>

                        <h4 class="mt-2 text-xl font-bold text-goldLight">
                            ddaeisy
                        </h4>

                        <p class="mt-2 text-sm text-gray-400">
                            Bagian penting dari cerita Panti Asuhan.
                        </p>

                    </div>

                </div>


                <!-- BARIS BAWAH: ANAK-ANAK -->
                <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

                    <!-- ANAK 1 -->
                    <div class="glass rounded-3xl p-6 text-center transition
                                hover:-translate-y-1 hover:border-gold/40">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center
                                    rounded-full border border-gold/30 bg-gold/10 text-4xl">
                            🎮
                        </div>

                        <p class="mt-5 text-xs uppercase tracking-[0.25em] text-gold">
                            Anak 1
                        </p>

                        <h4 class="mt-2 text-xl font-bold text-goldLight">
                            RiiAja639
                        </h4>

                        <p class="mt-2 text-sm text-gray-400">
                            Anak Panti Asuhan.
                        </p>

                    </div>


                    <!-- ANAK 2 -->
                    <div class="glass rounded-3xl p-6 text-center transition
                                hover:-translate-y-1 hover:border-gold/40">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center
                                    rounded-full border border-gold/30 bg-gold/10 text-4xl">
                            🕹️
                        </div>

                        <p class="mt-5 text-xs uppercase tracking-[0.25em] text-gold">
                            Anak 2
                        </p>

                        <h4 class="mt-2 text-xl font-bold text-goldLight">
                            ForgerssLoid
                        </h4>

                        <p class="mt-2 text-sm text-gray-400">
                            Anak Panti Asuhan.
                        </p>

                    </div>


                    <!-- ANAK 3 -->
                    <div class="glass rounded-3xl p-6 text-center transition
                                hover:-translate-y-1 hover:border-gold/40">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center
                                    rounded-full border border-gold/30 bg-gold/10 text-4xl">
                            🌙
                        </div>

                        <p class="mt-5 text-xs uppercase tracking-[0.25em] text-gold">
                            Anak 3
                        </p>

                        <h4 class="mt-2 text-xl font-bold text-goldLight">
                            kiki_qwq09
                        </h4>

                        <p class="mt-2 text-sm text-gray-400">
                            Anak Panti Asuhan.
                        </p>

                    </div>


                    <!-- ANAK 4 -->
                    <div class="glass rounded-3xl p-6 text-center transition
                                hover:-translate-y-1 hover:border-gold/40">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center
                                    rounded-full border border-gold/30 bg-gold/10 text-4xl">
                            ⭐
                        </div>

                        <p class="mt-5 text-xs uppercase tracking-[0.25em] text-gold">
                            Anak 4
                        </p>

                        <h4 class="mt-2 text-xl font-bold text-goldLight">
                            bata_gor78
                        </h4>

                        <p class="mt-2 text-sm text-gray-400">
                            Anak Panti Asuhan.
                        </p>

                    </div>

                </div>


                <div class="mt-8 text-center">

                    <a href="{{ route('members.index') }}"
                       class="inline-flex items-center justify-center rounded-xl
                              border border-gold/40 px-6 py-3 text-sm
                              font-semibold text-goldLight transition
                              hover:bg-gold hover:text-black">
                        Lihat Halaman Members →
                    </a>

                </div>

            </div>

        </section>


        <!-- ================= TENTANG ================= -->
        <section id="tentang" class="px-5 py-24">

            <div class="glass mx-auto max-w-4xl rounded-3xl p-8
                        text-center md:p-12">

                <p class="mb-3 text-sm uppercase tracking-[0.25em]
                          text-gold">
                    Tentang Kami
                </p>

                <h3 class="text-3xl font-bold md:text-4xl">
                    Dari Mabar, Menjadi Keluarga.
                </h3>

                <div class="mx-auto my-6 h-px w-32 gold-line"></div>

                <p class="leading-8 text-gray-300">
                    Panti Asuhan adalah tempat kami menyimpan
                    berbagai cerita dan kenangan yang tercipta
                    selama bermain bersama.
                    Bukan tentang siapa yang paling jago,
                    tetapi tentang siapa yang masih bertahan
                    ketika Mabar sudah terlalu kacau.
                </p>

            </div>

        </section>

    </main>


    <!-- ================= VIDEO PLAYER ================= -->
    <div
        id="videoPlayer"
        class="fixed inset-0 z-[100] hidden items-center
               justify-center bg-black/85 p-4 backdrop-blur-md">

        <button
            onclick="closeVideoPlayer()"
            class="absolute right-5 top-5 z-[110]
                   flex h-11 w-11 items-center justify-center
                   rounded-full border border-white/20
                   bg-black/60 text-2xl
                   transition hover:bg-gold hover:text-black">
            ×
        </button>

        <div class="w-full max-w-5xl">

            <video
                id="mainVideo"
                controls
                playsinline
                class="max-h-[75vh] w-full rounded-2xl
                       bg-black shadow-2xl">
            </video>

            <div
                class="mt-4 rounded-2xl border border-white/10
                       bg-black/60 p-4 text-center
                       backdrop-blur-xl">

                <h3 id="videoTitle" class="font-semibold text-goldLight">
                    Judul Video
                </h3>

                <p id="videoDescription" class="mt-1 text-sm text-gray-400">
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


    <!-- ================= FOOTER ================= -->
    <footer class="border-t border-white/10 px-5 py-10">

        <div class="mx-auto max-w-7xl text-center">

            <h4 class="font-bold tracking-[0.2em] text-goldLight">
                PANTI ASUHAN
            </h4>

            <p class="mt-2 text-xs text-gray-500">
                Rumah kecil untuk menyimpan kenangan besar.
            </p>

            <p class="mt-6 text-[11px] text-gray-600">
                © {{ date('Y') }} Panti Asuhan. All memories reserved.
            </p>

        </div>

    </footer>


    <!-- ================= JAVASCRIPT ================= -->
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


        const latestVideos = [
            @foreach ($latestVideos ?? [] as $video)
                {
                    video: @json($video->url),
                    title: @json($video->title ?? 'Video Kenangan'),
                    description: @json($video->description ?? 'Tidak ada deskripsi.'),
                    uploader: @json($video->user?->username ?? 'Unknown'),
                    date: @json($video->created_at?->format('d M Y'))
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
            if (!latestVideos.length) {
                return;
            }

            const video = latestVideos[index];

            videoTitle.textContent = video.title;
            videoDescription.textContent = video.description;
            videoUploader.textContent = '👤 ' + video.uploader;
            videoDate.textContent = '📅 ' + video.date;

            mainVideo.src = video.video;
            mainVideo.muted = false;
            mainVideo.volume = 1;
            mainVideo.load();

            videoPlayer.classList.remove('hidden');
            videoPlayer.classList.add('flex');

            document.body.style.overflow = 'hidden';

            mainVideo.play().catch(() => {});
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
