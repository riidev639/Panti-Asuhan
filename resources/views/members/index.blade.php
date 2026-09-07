<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Members — Panti Asuhan</title>

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

        .member-card {
            transition:
                transform 0.3s ease,
                border-color 0.3s ease,
                box-shadow 0.3s ease;
        }

        .member-card:hover {
            transform: translateY(-6px);
            border-color: rgba(212, 168, 79, 0.45);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
        }

        .avatar {
            transition: transform 0.3s ease;
        }

        .member-card:hover .avatar {
            transform: scale(1.08);
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

                <!-- LOGO -->
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


                <!-- DESKTOP MENU -->
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
                       class="text-sm text-gray-300 transition hover:text-goldLight">
                        Video
                    </a>

                    <a href="{{ route('members.index') }}"
                       class="text-sm font-semibold text-goldLight">
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


                <!-- MOBILE BUTTON -->
                <button
                    id="menuButton"
                    class="rounded-xl border border-gold/30
                           px-3 py-2 text-xl md:hidden">
                    ☰
                </button>

            </div>


            <!-- MOBILE MENU -->
            <div id="mobileMenu" class="hidden pt-4 md:hidden">

                <div class="flex flex-col gap-2 border-t border-white/10 pt-3">

                    <a href="{{ route('home') }}"
                       class="rounded-lg px-3 py-3 text-sm hover:bg-white/5">
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
                       class="rounded-lg bg-gold/10 px-3 py-3
                              text-sm text-goldLight">
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



    <!-- =====================================================
         MAIN
    ====================================================== -->
    <main class="px-5 pb-20 pt-32">

        <div class="mx-auto max-w-7xl">

            <!-- HEADER -->
            <div class="mb-12 text-center">

                <p class="mb-3 text-sm uppercase tracking-[0.3em] text-gold">
                    Our Family
                </p>

                <h2 class="text-4xl font-black tracking-wide
                           text-goldLight sm:text-5xl md:text-6xl">
                    MEMBERS
                </h2>

                <div class="mx-auto my-5 h-px w-40
                            bg-gradient-to-r from-transparent
                            via-gold to-transparent">
                </div>

                <p class="mx-auto max-w-xl text-sm leading-7 text-gray-300
                          md:text-base">
                    Orang-orang yang membuat
                    Panti Asuhan tidak pernah
                    benar-benar sepi.
                </p>

            </div>



            <!-- =====================================================
                 MEMBER GRID
                 BAPAK + MAMAK DI ATAS CENTER
                 ANAK 1 - 4 DI BAWAH
            ====================================================== -->

            <!-- ORANG TUA -->
            <div class="mx-auto mb-8 flex max-w-6xl
                        flex-wrap justify-center gap-5">

                <!-- BAPAK -->
                <div class="member-card glass w-full rounded-2xl p-6
                            text-center sm:w-[300px]">

                    <div class="avatar mx-auto flex h-24 w-24
                                items-center justify-center rounded-full
                                border border-gold/30 bg-gold/10 text-4xl">
                        👑
                    </div>

                    <div class="mt-5">
                        <h3 class="text-xl font-bold text-goldLight">
                            Bapak
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Kepala Keluarga
                        </p>
                    </div>

                    <div class="my-5 h-px bg-white/10"></div>

                    <div class="flex justify-center gap-2">
                        <span class="rounded-full border border-gold/20
                                     bg-gold/10 px-3 py-1 text-[10px]
                                     text-goldLight">
                            👑 1ridescent2
                        </span>
                    </div>

                </div>


                <!-- MAMAK 1 -->
                <div class="member-card glass w-full rounded-2xl p-6
                            text-center sm:w-[300px]">

                    <div class="avatar mx-auto flex h-24 w-24
                                items-center justify-center rounded-full
                                border border-gold/30 bg-gold/10 text-4xl">
                        ❤️
                    </div>

                    <div class="mt-5">
                        <h3 class="text-xl font-bold text-goldLight">
                            Mamak 1
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Mamak
                        </p>
                    </div>

                    <div class="my-5 h-px bg-white/10"></div>

                    <div class="flex justify-center">
                        <span class="rounded-full border border-gold/20
                                     bg-gold/10 px-3 py-1 text-[10px]
                                     text-goldLight">
                            ❤️ nisrr_322
                        </span>
                    </div>

                </div>


                <!-- MAMAK 2 -->
                <div class="member-card glass w-full rounded-2xl p-6
                            text-center sm:w-[300px]">

                    <div class="avatar mx-auto flex h-24 w-24
                                items-center justify-center rounded-full
                                border border-gold/30 bg-gold/10 text-4xl">
                        ❤️
                    </div>

                    <div class="mt-5">
                        <h3 class="text-xl font-bold text-goldLight">
                            Mamak 2
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Mamak
                        </p>
                    </div>

                    <div class="my-5 h-px bg-white/10"></div>

                    <div class="flex justify-center">
                        <span class="rounded-full border border-gold/20
                                     bg-gold/10 px-3 py-1 text-[10px]
                                     text-goldLight">
                            ❤️ ddaeisy
                        </span>
                    </div>

                </div>

            </div>



            <!-- ANAK-ANAK -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

                <!-- ANAK 1 -->
                <div class="member-card glass rounded-2xl p-6 text-center">

                    <div class="avatar mx-auto flex h-24 w-24
                                items-center justify-center rounded-full
                                border border-gold/30 bg-gold/10 text-4xl">
                        👦
                    </div>

                    <div class="mt-5">
                        <h3 class="text-xl font-bold text-goldLight">
                            Anak 1
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Anak
                        </p>
                    </div>

                    <div class="my-5 h-px bg-white/10"></div>

                    <div class="flex justify-center">
                        <span class="rounded-full border border-gold/20
                                     bg-gold/10 px-3 py-1 text-[10px]
                                     text-goldLight">
                            🎮 RiiAja639
                        </span>
                    </div>

                </div>


                <!-- ANAK 2 -->
                <div class="member-card glass rounded-2xl p-6 text-center">

                    <div class="avatar mx-auto flex h-24 w-24
                                items-center justify-center rounded-full
                                border border-gold/30 bg-gold/10 text-4xl">
                        👧
                    </div>

                    <div class="mt-5">
                        <h3 class="text-xl font-bold text-goldLight">
                            Anak 2
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Anak
                        </p>
                    </div>

                    <div class="my-5 h-px bg-white/10"></div>

                    <div class="flex justify-center">
                        <span class="rounded-full border border-gold/20
                                     bg-gold/10 px-3 py-1 text-[10px]
                                     text-goldLight">
                            🎮 ForgerssLoid
                        </span>
                    </div>

                </div>


                <!-- ANAK 3 -->
                <div class="member-card glass rounded-2xl p-6 text-center">

                    <div class="avatar mx-auto flex h-24 w-24
                                items-center justify-center rounded-full
                                border border-gold/30 bg-gold/10 text-4xl">
                        👧
                    </div>

                    <div class="mt-5">
                        <h3 class="text-xl font-bold text-goldLight">
                            Anak 3
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Anak
                        </p>
                    </div>

                    <div class="my-5 h-px bg-white/10"></div>

                    <div class="flex justify-center">
                        <span class="rounded-full border border-gold/20
                                     bg-gold/10 px-3 py-1 text-[10px]
                                     text-goldLight">
                            🎮 kiki_qwq09
                        </span>
                    </div>

                </div>


                <!-- ANAK 4 -->
                <div class="member-card glass rounded-2xl p-6 text-center">

                    <div class="avatar mx-auto flex h-24 w-24
                                items-center justify-center rounded-full
                                border border-gold/30 bg-gold/10 text-4xl">
                        👧
                    </div>

                    <div class="mt-5">
                        <h3 class="text-xl font-bold text-goldLight">
                            Anak 4
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Anak
                        </p>
                    </div>

                    <div class="my-5 h-px bg-white/10"></div>

                    <div class="flex justify-center">
                        <span class="rounded-full border border-gold/20
                                     bg-gold/10 px-3 py-1 text-[10px]
                                     text-goldLight">
                            🎮 bata_gor78
                        </span>
                    </div>

                </div>

            </div>



            <!-- INFO -->
            <div class="glass mx-auto mt-12 max-w-3xl rounded-3xl
                        p-6 text-center md:p-8">

                <div class="text-3xl">
                    🏠
                </div>

                <h3 class="mt-3 text-xl font-bold text-goldLight">
                    Satu Grup, Satu Keluarga
                </h3>

                <p class="mx-auto mt-3 max-w-2xl text-sm leading-7
                          text-gray-400">
                    Panti Asuhan terbentuk dari
                    pertemanan yang awalnya hanya
                    berasal dari Mabar.
                    Dari panggilan Bapak, Mamak,
                    sampai anak-anak yang semakin
                    lama semakin banyak.
                </p>

            </div>

        </div>

    </main>



    <!-- =====================================================
         FOOTER
    ====================================================== -->
    <footer class="border-t border-white/10 px-5 py-10">

        <div class="mx-auto max-w-7xl text-center">

            <h4 class="font-bold tracking-[0.2em] text-goldLight">
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
    </script>

</body>
</html>
