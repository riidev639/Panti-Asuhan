<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login — Panti Asuhan</title>

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
                    rgba(13, 11, 9, 0.88)
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
            border: 1px solid rgba(212, 168, 79, 0.20);
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

    <main class="flex min-h-screen items-center justify-center px-5 py-10">

        <div class="w-full max-w-md">

            <!-- LOGO / BRAND -->
            <div class="mb-8 text-center">

                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">

                    <div class="flex h-14 w-14 items-center justify-center
                                rounded-2xl border border-gold/40
                                bg-black/40 text-2xl">
                        🏠
                    </div>

                    <div class="text-left">

                        <h1 class="text-lg font-bold tracking-[0.2em]
                                   text-goldLight">
                            PANTI ASUHAN
                        </h1>

                        <p class="text-xs text-gray-400">
                            Rumah Kenangan
                        </p>

                    </div>

                </a>

            </div>


            <!-- LOGIN CARD -->
            <div class="glass rounded-3xl p-6 shadow-2xl sm:p-8">

                <div class="text-center">

                    <div class="mx-auto mb-4 flex h-14 w-14 items-center
                                justify-center rounded-full
                                bg-gold/10 text-2xl">
                        🔐
                    </div>

                    <h2 class="text-2xl font-bold text-goldLight">
                        Login Member
                    </h2>

                    <p class="mt-2 text-sm text-gray-400">
                        Masuk menggunakan username Roblox kamu.
                    </p>

                </div>


                <div class="my-6 h-px gold-line"></div>


                <!-- ERROR -->
                @if ($errors->any())

                    <div class="mb-5 rounded-xl border border-red-500/20
                                bg-red-500/10 p-4 text-sm text-red-300">

                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach

                    </div>

                @endif


                <!-- LOGIN FORM -->
                <form action="{{ route('login.process') }}" method="POST">

                    @csrf


                    <!-- USERNAME -->
                    <div class="mb-5">

                        <label for="username"
                               class="mb-2 block text-sm font-medium
                                      text-gray-300">
                            Username Roblox
                        </label>

                        <div class="relative">

                            <span class="absolute left-4 top-1/2
                                         -translate-y-1/2 text-lg">
                                🎮
                            </span>

                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                placeholder="Masukkan username Roblox"
                                autocomplete="username"
                                required
                                class="w-full rounded-xl border
                                       border-white/10 bg-black/30
                                       py-3 pl-12 pr-4 text-sm
                                       text-white outline-none
                                       placeholder:text-gray-600
                                       focus:border-gold/60
                                       focus:ring-1 focus:ring-gold/40">

                        </div>

                    </div>


                    <!-- PASSWORD -->
                    <div class="mb-5">

                        <div class="mb-2 flex items-center justify-between">

                            <label for="password"
                                   class="block text-sm font-medium
                                          text-gray-300">
                                Password
                            </label>

                            <button type="button"
                                    onclick="document.getElementById('forgotPasswordHelp').classList.toggle('hidden')"
                                    aria-controls="forgotPasswordHelp"
                                    class="text-xs text-goldLight
                                           transition hover:text-gold">
                                Lupa Password?
                            </button>

                        </div>

                        <p id="forgotPasswordHelp"
                           class="mb-3 hidden rounded-lg border border-gold/20
                                  bg-gold/5 px-3 py-2 text-xs leading-5 text-gray-300">
                            Silakan hubungi admin Panti Asuhan untuk mengatur ulang password.
                        </p>

                        <div class="relative">

                            <span class="absolute left-4 top-1/2
                                         -translate-y-1/2 text-lg">
                                🔒
                            </span>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required
                                class="w-full rounded-xl border
                                       border-white/10 bg-black/30
                                       py-3 pl-12 pr-4 text-sm
                                       text-white outline-none
                                       placeholder:text-gray-600
                                       focus:border-gold/60
                                       focus:ring-1 focus:ring-gold/40">

                        </div>

                    </div>


                    <!-- REMEMBER -->
                    <div class="mb-6 flex items-center gap-2">

                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            class="h-4 w-4 rounded border-white/20
                                   bg-black/30 accent-[#d4a84f]">

                        <label for="remember"
                               class="text-xs text-gray-400">
                            Ingat saya
                        </label>

                    </div>


                    <!-- BUTTON -->
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-gold px-5 py-3
                               font-semibold text-black
                               transition duration-300
                               hover:scale-[1.02]
                               hover:bg-goldLight
                               active:scale-[0.98]">

                        🔐 Masuk ke Panti Asuhan

                    </button>

                </form>


                <!-- INFO -->
                <div class="mt-6 rounded-xl border border-gold/10
                            bg-gold/5 p-4 text-center">

                    <p class="text-xs leading-5 text-gray-400">
                        Website ini khusus untuk keluarga
                        <span class="text-goldLight">
                            Panti Asuhan
                        </span>.
                        Hanya member yang terdaftar yang dapat
                        mengunggah foto dan video.
                    </p>

                </div>

            </div>


            <!-- BACK -->
            <div class="mt-6 text-center">

                <a href="{{ route('home') }}"
                   class="text-sm text-gray-500 transition
                          hover:text-goldLight">

                    ← Kembali ke Beranda

                </a>

            </div>


            <p class="mt-6 text-center text-[11px] text-gray-600">
                © {{ date('Y') }} Panti Asuhan
            </p>

        </div>

    </main>

</body>
</html>
