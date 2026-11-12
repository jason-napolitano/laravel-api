<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Your Password</title>
</head>

<body style="
    margin: 0;
    padding: 40px 15px;
    background-color: #f4f4f5;
    font-family: Arial, Helvetica, sans-serif;
    color: #18181b;
">

<table
    width="100%"
    cellpadding="0"
    cellspacing="0"
    role="presentation"
>
    <tr>
        <td align="center">

            <table
                width="100%"
                cellpadding="0"
                cellspacing="0"
                role="presentation"
                style="
                        max-width: 560px;
                        background-color: #ffffff;
                        border-radius: 8px;
                        overflow: hidden;
                    "
            >
                <tr>
                    <td style="
                            padding: 32px;
                            text-align: center;
                            background-color: #18181b;
                            color: #ffffff;
                        ">
                        <h1 style="
                                margin: 0;
                                font-size: 24px;
                            ">
                            Password Reset
                        </h1>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 32px;">

                        <p style="font-size: 16px;">
                            Hello {{ $user->name ?? 'there' }},
                        </p>

                        <p style="
                                font-size: 15px;
                                line-height: 1.7;
                                color: #52525b;
                            ">
                            We received a request to reset the password
                            associated with your account.
                        </p>

                        <p style="
                                font-size: 15px;
                                line-height: 1.7;
                                color: #52525b;
                            ">
                            Click the button below to choose a new
                            password.
                        </p>

                        <table
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            role="presentation"
                        >
                            <tr>
                                <td
                                    align="center"
                                    style="padding: 25px 0;"
                                >
                                    <a
                                        href="{{ $url }}"
                                        style="
                                                display: inline-block;
                                                padding: 14px 28px;
                                                background-color: #2563eb;
                                                color: #ffffff;
                                                text-decoration: none;
                                                border-radius: 6px;
                                                font-size: 15px;
                                                font-weight: bold;
                                            "
                                    >
                                        Reset Password
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="
                                font-size: 14px;
                                line-height: 1.7;
                                color: #52525b;
                            ">
                            This password reset link will expire in
                            {{ config('auth.passwords.users.expire') }}
                            minutes.
                        </p>

                        <p style="
                                font-size: 14px;
                                line-height: 1.7;
                                color: #52525b;
                            ">
                            If you did not request a password reset,
                            you can safely ignore this email.
                        </p>

                        <hr style="
                                margin: 30px 0;
                                border: 0;
                                border-top: 1px solid #e4e4e7;
                            ">

                        <p style="
                                font-size: 12px;
                                line-height: 1.6;
                                color: #a1a1aa;
                            ">
                            If the button above doesn't work, copy and
                            paste the following URL into your browser:
                        </p>

                        <p style="
                                font-size: 12px;
                                line-height: 1.6;
                                word-break: break-all;
                            ">
                            <a
                                href="{{ $url }}"
                                style="color: #2563eb;"
                            >
                                {{ $url }}
                            </a>
                        </p>

                    </td>
                </tr>

                <tr>
                    <td style="
                            padding: 20px;
                            text-align: center;
                            background-color: #fafafa;
                            color: #a1a1aa;
                            font-size: 12px;
                        ">
                        &copy; {{ date('Y') }}
                        {{ config('app.name') }}.
                        All rights reserved.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
