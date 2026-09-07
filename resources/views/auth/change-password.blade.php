<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password — Panti Asuhan</title>

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
                    rgba(13, 11, 9, 0.55),
                    rgba(13, 11, 9, 0.75)
                ),
                url('{{ asset('images/panti-bg.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .glass {
            background: rgba(20, 16, 12, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(212, 168, 79, 0.20);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.20);
        }
    </style>
</head>

<body class="text-white">

    <main class="flex min-h-screen items-center justify-center px-5 py-10">

        <div class="glass w-full max-w-xl rounded-3xl p-6 md:p-8">

            <div class="mb-8 text-center">

                <p class="mb-3 text-sm uppercase tracking-[0.3em] text-gold">
                    Member Security
                </p>

                <h1 class="text-3xl font-black text-goldLight md:text-4xl">
                    Ganti Password
                </h1>

                <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-gray-300">
                    Gunakan password yang mudah kamu ingat, tapi jangan terlalu gampang ditebak.
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


            <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="mb-2 block text-sm font-semibold text-goldLight">
                        Password Lama
                    </label>

                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        placeholder="Masukkan password lama"
                        class="w-full rounded-2xl border border-white/10 bg-black/35 px-4 py-3
                               text-sm text-white placeholder:text-gray-500
                               focus:border-gold/60 focus:outline-none"
                        required
                    >
                </div>


                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-goldLight">
                        Password Baru
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimal 8 karakter"
                        class="w-full rounded-2xl border border-white/10 bg-black/35 px-4 py-3
                               text-sm text-white placeholder:text-gray-500
                               focus:border-gold/60 focus:outline-none"
                        required
                    >
                </div>


                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-goldLight">
                        Konfirmasi Password Baru
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi password baru"
                        class="w-full rounded-2xl border border-white/10 bg-black/35 px-4 py-3
                               text-sm text-white placeholder:text-gray-500
                               focus:border-gold/60 focus:outline-none"
                        required
                    >
                </div>


                <div class="flex flex-col gap-3 pt-3 sm:flex-row">

                    <a
                        href="{{ route('dashboard') }}"
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
                        Simpan Password
                    </button>

                </div>

            </form>

        </div>

    </main>

</body>
</html>
