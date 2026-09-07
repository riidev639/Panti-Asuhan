<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Panti Asuhan</title>

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

        <nav class="glass mx-3 mt-3 rounded-2xl px-4 py-3
                    md:mx-auto md:max-w-7xl md:px-6">

            <div class="flex items-center justify-between">

                <!-- LOGO -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center
                                rounded-xl border border-gold/40
                                bg-black/40">
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


                <!-- DESKTOP -->
                <div class="hidden items-center gap-4 md:flex">

                    <a href="{{ route('home') }}"
                       class="rounded-xl border border-white/10 px-4 py-2
                              text-sm text-gray-300 transition
                              hover:border-gold/40 hover:text-goldLight">
                        Beranda
                    </a>

                    <span class="text-sm text-gray-400">
                        Halo,
                    </span>

                    <span class="font-semibold text-goldLight">
                        {{ Auth::user()->username }}
                    </span>

                    <form action="{{ route('logout') }}" method="POST">

                        @csrf

                        <button
                            type="submit"
                            class="rounded-xl border border-red-500/30
                                   px-4 py-2 text-sm text-red-300
                                   transition hover:bg-red-500/10">
                            Logout
                        </button>

                    </form>

                </div>

            </div>

        </nav>

    </header>


    <!-- CONTENT -->
    <main class="min-h-screen px-5 pb-16 pt-32">

        <div class="mx-auto max-w-7xl">

            <!-- WELCOME -->
            <section class="mb-10">

                <p class="mb-2 text-sm uppercase tracking-[0.25em] text-gold">
                    Member Area
                </p>

                <h2 class="text-3xl font-bold sm:text-4xl md:text-5xl">
                    Selamat datang,
                    <span class="text-goldLight">
                        {{ Auth::user()->username }}
                    </span>
                    👋
                </h2>

                <p class="mt-3 max-w-2xl text-sm leading-6 text-gray-400">
                    Ini adalah tempat khusus member Panti Asuhan.
                    Dari sini kamu bisa mengunggah foto dan video
                    kenangan bersama.
                </p>

            </section>


            <!-- SUCCESS MESSAGE -->
            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-green-400/30
                            bg-green-500/10 p-4 text-sm text-green-200">
                    {{ session('success') }}
                </div>
            @endif


            <!-- QUICK ACTION -->
            <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

                <!-- UPLOAD FOTO -->
                <a href="{{ route('photos.create') }}"
                   class="glass group rounded-2xl p-6 transition
                          duration-300 hover:-translate-y-1
                          hover:border-gold/40">

                    <div class="mb-5 flex h-14 w-14 items-center
                                justify-center rounded-2xl
                                bg-gold/10 text-2xl">
                        📸
                    </div>

                    <h3 class="text-xl font-bold">
                        Upload Foto
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-gray-400">
                        Simpan foto momen Mabar dan kenangan
                        bersama keluarga Panti Asuhan.
                    </p>

                    <p class="mt-5 text-sm font-semibold text-goldLight">
                        Upload sekarang →
                    </p>

                </a>


                <!-- UPLOAD VIDEO -->
                <a href="{{ route('videos.create') }}"
                   class="glass group rounded-2xl p-6 transition
                          duration-300 hover:-translate-y-1
                          hover:border-gold/40">

                    <div class="mb-5 flex h-14 w-14 items-center
                                justify-center rounded-2xl
                                bg-gold/10 text-2xl">
                        🎥
                    </div>

                    <h3 class="text-xl font-bold">
                        Upload Video
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-gray-400">
                        Upload video lucu, random, atau momen
                        penting selama bermain bersama.
                    </p>

                    <p class="mt-5 text-sm font-semibold text-goldLight">
                        Upload sekarang →
                    </p>

                </a>


                <!-- GALERI -->
                <a href="{{ route('photos.index') }}"
                   class="glass group rounded-2xl p-6 transition
                          duration-300 hover:-translate-y-1
                          hover:border-gold/40">

                    <div class="mb-5 flex h-14 w-14 items-center
                                justify-center rounded-2xl
                                bg-gold/10 text-2xl">
                        🖼️
                    </div>

                    <h3 class="text-xl font-bold">
                        Lihat Galeri
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-gray-400">
                        Lihat kembali foto dan kenangan yang
                        sudah tersimpan.
                    </p>

                    <p class="mt-5 text-sm font-semibold text-goldLight">
                        Buka galeri →
                    </p>

                </a>


                <!-- GANTI PASSWORD -->
                <a href="{{ route('password.change') }}"
                   class="glass group rounded-2xl p-6 transition
                          duration-300 hover:-translate-y-1
                          hover:border-gold/40">

                    <div class="mb-5 flex h-14 w-14 items-center
                                justify-center rounded-2xl
                                bg-gold/10 text-2xl">
                        🔑
                    </div>

                    <h3 class="text-xl font-bold">
                        Ganti Password
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-gray-400">
                        Ubah password akun kamu agar tetap aman.
                    </p>

                    <p class="mt-5 text-sm font-semibold text-goldLight">
                        Ganti sekarang →
                    </p>

                </a>

            </section>


            <!-- INFO -->
            <section class="mt-8">

                <div class="glass rounded-2xl p-6">

                    <div class="flex flex-col gap-4 sm:flex-row
                                sm:items-center sm:justify-between">

                        <div>

                            <p class="text-xs uppercase tracking-[0.2em]
                                      text-gold">
                                Akun Kamu
                            </p>

                            <h3 class="mt-2 text-lg font-semibold">
                                🎮 {{ Auth::user()->username }}
                            </h3>

                        </div>

                        <div class="text-sm text-gray-400">

                            Bergabung:
                            <span class="text-gray-300">
                                {{ Auth::user()->created_at?->format('d M Y') }}
                            </span>

                        </div>

                    </div>

                </div>

            </section>

        </div>

    </main>


    <!-- FOOTER -->
    <footer class="border-t border-white/10 px-5 py-8">

        <div class="mx-auto max-w-7xl text-center">

            <p class="text-[11px] text-gray-600">
                © {{ date('Y') }} Panti Asuhan — Rumah Kenangan
            </p>

        </div>

    </footer>

</body>
</html>
