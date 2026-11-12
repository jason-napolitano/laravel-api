<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Your Password</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="m-0 bg-zinc-100 px-4 py-10 font-sans text-zinc-900">
        <table class="w-full" role="presentation">
            <tr>
                <td>
                    <table class="w-full max-w-[560px] overflow-hidden rounded-lg bg-white" role="presentation">
                        <tr>
                            <td class="bg-zinc-900 px-8 py-8 text-center text-white">
                                <h1 class="m-0 text-2xl font-bold">
                                    Password Reset
                                </h1>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-8">
                                <p class="text-base">
                                    Hello {{ $user->name ?? 'there' }},
                                </p>
                                <p class="text-[15px] leading-7 text-zinc-600">
                                    We received a request to reset the password
                                    associated with your account.
                                </p>
                                <p class="text-[15px] leading-7 text-zinc-600">
                                    Click the button below to choose a new
                                    password.
                                </p>

                                <table class="w-full" role="presentation">
                                    <tr>
                                        <td class="py-6">
                                            <a href="{{ $url }}" class="inline-block rounded-md bg-blue-600 px-7 py-3.5 text-[15px] font-bold text-white no-underline">
                                                Reset Password
                                            </a>
                                        </td>
                                    </tr>
                                </table>

                                <p class="text-sm leading-7 text-zinc-600">
                                    This password reset link will expire in
                                    {{ config('auth.passwords.users.expire') }}
                                    minutes.
                                </p>

                                <p class="text-sm leading-7 text-zinc-600">
                                    If you did not request a password reset,
                                    you can safely ignore this email.
                                </p>

                                <hr class="my-8 border-0 border-t border-solid border-zinc-200">

                                <p class="text-xs leading-6 text-zinc-400">
                                    If the button above doesn't work, copy and
                                    paste the following URL into your browser:
                                </p>

                                <p class="break-all text-xs leading-6">
                                    <a href="{{ $url }}" class="text-blue-600">
                                        {{ $url }}
                                    </a>
                                </p>

                            </td>
                        </tr>

                        <tr>
                            <td class="bg-zinc-50 px-5 py-5 text-center text-xs text-zinc-400">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
