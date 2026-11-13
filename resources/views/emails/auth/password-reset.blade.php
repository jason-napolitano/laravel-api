<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Your Password</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body>
    <div class="flex items-center justify-center h-screen">
            <div class="space-y-2 border border-stone-300 rounded-sm shadow">
                <header class="space-y-2 px-6 py-4 border-b border-stone-300 bg-stone-50">
                    <h1 class="text-center text-2xl">
                        Reset Password
                    </h1>

                    <p class="px-2 py-1 bg-blue-200 rounded-sm text-blue-700 text-center">
                        If you did not request a password reset, you can safely ignore this email.
                    </p>
                </header>

                <article class="px-6 py-4 space-y-2 text-zinc-400 text-center">

                    <p>
                        We've received a request to reset the password associated with your account.
                    </p>

                    <p>
                        This password reset link will expire in {{ config('auth.passwords.users.expire') }} minutes.
                    </p>
                </article>

                <article class="px-4 py-2 text-center">
                    <a href="{{ $url }}" class="px-2 py-1 bg-stone-300">
                        Reset Password
                    </a>
                </article>

                <footer class="px-6 py-4 border-t border-stone-300 text-center">

                    <p class="text-zinc-400">
                        If the button above doesn't work, copy and
                        paste the following URL into your browser:
                    </p>

                    <a href="{{ $url }}" class="text-blue-400">
                        {{ $url }}
                    </a>
                </footer>
            </div></div>
    </body>
</html>
