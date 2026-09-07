<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Foto — Panti Asuhan</title>

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

        .photo-card {
            transition:
                transform 0.3s ease,
                border-color 0.3s ease,
                box-shadow 0.3s ease;
        }

        .photo-card:hover {
            transform: translateY(-5px);
            border-color: rgba(212, 168, 79, 0.45);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
        }

        .photo-card img {
            transition: transform 0.5s ease;
        }

        .photo-card:hover img {
            transform: scale(1.06);
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

    <!-- ================= NAVBAR ================= -->
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


                <!-- Desktop Navigation -->
                <div class="hidden items-center gap-6 md:flex">

                    <a href="{{ route('home') }}"
                       class="text-sm text-gray-300 transition hover:text-goldLight">
                        Beranda
                    </a>

                    <a href="{{ route('photos.index') }}"
                       class="text-sm font-semibold text-goldLight">
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


            <!-- Mobile Navigation -->
            <div id="mobileMenu" class="hidden pt-4 md:hidden">

                <div class="flex flex-col gap-2
                            border-t border-white/10 pt-3">

                    <a href="{{ route('home') }}"
                       class="rounded-lg px-3 py-3 text-sm hover:bg-white/5">
                        🏠 Beranda
                    </a>

                    <a href="{{ route('photos.index') }}"
                       class="rounded-lg bg-gold/10 px-3 py-3
                              text-sm text-goldLight">
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

                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="rounded-lg border border-gold/40
                                  px-3 py-3 text-sm text-goldLight">
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
                           class="rounded-lg border border-gold/40
                                  px-3 py-3 text-sm text-goldLight">
                            🔐 Login
                        </a>
                    @endauth

                </div>

            </div>

        </nav>

    </header>


    <!-- ================= CONTENT ================= -->
    <main class="px-5 pb-20 pt-32">

        <div class="mx-auto max-w-7xl">

            <!-- Header -->
            <div class="mb-10 text-center">

                <p class="mb-3 text-sm uppercase tracking-[0.3em]
                          text-gold">
                    Our Memories
                </p>

                <h2 class="text-4xl font-black tracking-wide
                           text-goldLight sm:text-5xl md:text-6xl">
                    GALERI FOTO
                </h2>

                <div class="mx-auto my-5 h-px w-40
                            bg-gradient-to-r from-transparent
                            via-gold to-transparent">
                </div>

                <p class="mx-auto max-w-xl text-sm leading-7 text-gray-300
                          md:text-base">
                    Kumpulan momen yang pernah kita abadikan
                    selama perjalanan Panti Asuhan.
                </p>

                <div class="mt-6">
                    @auth
                        <a href="{{ route('photos.create') }}"
                           class="inline-flex items-center gap-2 rounded-xl
                                  bg-gold px-5 py-3 text-sm font-semibold
                                  text-black transition hover:scale-105">
                            ➕ Upload Foto
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


            <!-- Filter -->
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


            <!-- ================= PHOTO GRID ================= -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                @forelse ($photos ?? [] as $photo)

                    <div class="photo-card glass group overflow-hidden rounded-2xl">

                        <!-- FOTO -->
                        <button
                            type="button"
                            onclick="openLightbox({{ $loop->index }})"
                            class="relative block aspect-[4/3] w-full overflow-hidden text-left">

                            <img
                                src="{{ asset('storage/' . $photo->path) }}"
                                alt="{{ $photo->title ?? 'Foto Kenangan' }}"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                            >

                            <div class="absolute inset-0 bg-gradient-to-t
                                        from-black/70 via-transparent to-transparent">
                            </div>

                        </button>


                        <!-- INFORMASI -->
                        <div class="p-4">

                            <h3 class="text-lg font-semibold">
                                {{ $photo->title ?? 'Foto Kenangan' }}
                            </h3>

                            @if ($photo->description)
                                <p class="mt-1 text-sm text-gray-400">
                                    {{ $photo->description }}
                                </p>
                            @else
                                <p class="mt-1 text-sm text-gray-500">
                                    Tidak ada deskripsi
                                </p>
                            @endif


                            <div class="mt-4 flex items-center justify-between gap-3
                                        border-t border-white/10 pt-3">

                                <span class="truncate text-[11px] text-gray-400">
                                    👤 {{ optional($photo->user)->username ?? 'Unknown' }}
                                </span>

                                <span class="shrink-0 text-[11px] text-gray-500">
                                    🕐 {{ optional($photo->created_at)->format('d M Y H:i') }}
                                </span>

                            </div>


                            <!-- ================= AKSI ================= -->
                            @auth
                                @if ($photo->user_id === auth()->id())

                                    <div class="mt-4 flex gap-2">

                                        <!-- EDIT -->
                                        <a
                                            href="{{ route('photos.edit', $photo) }}"
                                            class="flex-1 rounded-xl border border-gold/40
                                                   px-3 py-2 text-center text-sm
                                                   text-goldLight transition
                                                   hover:bg-gold hover:text-black">
                                            ✏️ Edit
                                        </a>


                                        <!-- HAPUS -->
                                        <form
                                            action="{{ route('photos.destroy', $photo) }}"
                                            method="POST"
                                            class="flex-1"
                                            onsubmit="return confirm('Yakin ingin menghapus foto ini?')">

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
                                📸
                            </div>

                            <h3 class="mt-4 text-xl font-semibold">
                                Belum ada foto
                            </h3>

                            <p class="mt-2 text-sm text-gray-400">
                                Belum ada kenangan yang diupload.
                            </p>

                            @auth
                                <a
                                    href="{{ route('photos.create') }}"
                                    class="mt-5 inline-block rounded-xl
                                           bg-gold px-5 py-3 text-sm
                                           font-semibold text-black">
                                    Upload Foto
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


            <!-- Empty / Future Upload -->
            <div class="mt-10 text-center">

                <div class="glass mx-auto max-w-xl rounded-2xl p-6">

                    <div class="text-3xl">
                        📸
                    </div>

                    <h3 class="mt-3 font-semibold">
                        Lebih banyak kenangan akan datang
                    </h3>

                    <p class="mt-2 text-sm text-gray-400">
                        @auth
                            Kamu bisa menambahkan foto kenangan baru melalui tombol upload di atas.
                        @else
                            Hanya anggota Panti Asuhan yang bisa login dan upload foto kenangan.
                        @endauth
                    </p>

                </div>

            </div>

        </div>

    </main>


    <!-- =====================================================
         PHOTO LIGHTBOX
    ====================================================== -->
    <div
        id="lightbox"
        class="fixed inset-0 z-[100] hidden items-center
               justify-center bg-black/80 p-4 backdrop-blur-md">

        <!-- Tombol Close -->
        <button
            onclick="closeLightbox()"
            class="absolute right-5 top-5 z-[110]
                   flex h-11 w-11 items-center justify-center
                   rounded-full border border-white/20
                   bg-black/50 text-2xl text-white
                   transition hover:bg-gold hover:text-black">
            ×
        </button>


        <!-- Tombol Previous -->
        <button
            onclick="previousPhoto()"
            class="absolute left-3 top-1/2 z-[110]
                   flex h-11 w-11 -translate-y-1/2
                   items-center justify-center rounded-full
                   border border-white/20
                   bg-black/50 text-xl text-white
                   transition hover:bg-gold hover:text-black
                   sm:left-6">
            ‹
        </button>


        <!-- Konten -->
        <div
            class="relative flex max-h-[90vh] max-w-6xl
                   flex-col items-center">

            <img
                id="lightboxImage"
                src=""
                alt="Preview Foto"
                class="max-h-[75vh] max-w-full rounded-2xl
                       object-contain shadow-2xl"
            >


            <!-- Informasi Foto -->
            <div
                class="mt-4 w-full max-w-2xl rounded-2xl
                       border border-white/10 bg-black/60
                       p-4 text-center backdrop-blur-xl">

                <h3
                    id="lightboxTitle"
                    class="font-semibold text-goldLight">
                    Foto Kenangan
                </h3>

                <p
                    id="lightboxDescription"
                    class="mt-1 text-xs text-gray-400">
                    Deskripsi foto
                </p>

                <div
                    class="mt-3 flex flex-wrap justify-center
                           gap-4 text-[11px] text-gray-400">

                    <span id="lightboxUploader">
                        👤 Unknown
                    </span>

                    <span id="lightboxDate">
                        📅 -
                    </span>

                    <span id="lightboxTime">
                        🕐 -
                    </span>

                </div>

            </div>

        </div>


        <!-- Tombol Next -->
        <button
            onclick="nextPhoto()"
            class="absolute right-3 top-1/2 z-[110]
                   flex h-11 w-11 -translate-y-1/2
                   items-center justify-center rounded-full
                   border border-white/20
                   bg-black/50 text-xl text-white
                   transition hover:bg-gold hover:text-black
                   sm:right-6">
            ›
        </button>

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
        const menuButton =
            document.getElementById('menuButton');

        const mobileMenu =
            document.getElementById('mobileMenu');

        menuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        document.querySelectorAll('#mobileMenu a')
            .forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                });
            });


        /* =====================================================
           PHOTO LIGHTBOX
        ===================================================== */

        const photos = [
            @foreach ($photos ?? [] as $photo)
                {
                    image: @json(asset('storage/' . $photo->path)),
                    title: @json($photo->title ?? 'Foto Kenangan'),
                    description: @json($photo->description ?? 'Tidak ada deskripsi.'),
                    uploader: @json(optional($photo->user)->username ?? 'Unknown'),
                    date: @json(optional($photo->created_at)->format('d M Y')),
                    time: @json(optional($photo->created_at)->format('H:i'))
                },
            @endforeach
        ];


        let currentPhoto = 0;

        const lightbox =
            document.getElementById('lightbox');

        const lightboxImage =
            document.getElementById('lightboxImage');

        const lightboxTitle =
            document.getElementById('lightboxTitle');

        const lightboxDescription =
            document.getElementById('lightboxDescription');

        const lightboxUploader =
            document.getElementById('lightboxUploader');

        const lightboxDate =
            document.getElementById('lightboxDate');

        const lightboxTime =
            document.getElementById('lightboxTime');


        function openLightbox(index) {
            if (!photos.length) {
                return;
            }

            currentPhoto = index;
            showPhoto();

            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');

            document.body.style.overflow = 'hidden';
        }


        function closeLightbox() {
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');

            document.body.style.overflow = '';
        }


        function showPhoto() {
            const photo = photos[currentPhoto];

            lightboxImage.src = photo.image;
            lightboxTitle.textContent = photo.title;
            lightboxDescription.textContent = photo.description;
            lightboxUploader.textContent = '👤 ' + photo.uploader;
            lightboxDate.textContent = '📅 ' + photo.date;
            lightboxTime.textContent = '🕐 ' + photo.time;
        }


        function nextPhoto() {
            currentPhoto++;

            if (currentPhoto >= photos.length) {
                currentPhoto = 0;
            }

            showPhoto();
        }


        function previousPhoto() {
            currentPhoto--;

            if (currentPhoto < 0) {
                currentPhoto = photos.length - 1;
            }

            showPhoto();
        }


        document.addEventListener('keydown', (event) => {
            if (lightbox.classList.contains('hidden')) {
                return;
            }

            if (event.key === 'Escape') {
                closeLightbox();
            }

            if (event.key === 'ArrowRight') {
                nextPhoto();
            }

            if (event.key === 'ArrowLeft') {
                previousPhoto();
            }
        });


        lightbox.addEventListener('click', (event) => {
            if (event.target === lightbox) {
                closeLightbox();
            }
        });
    </script>

</body>
</html>
