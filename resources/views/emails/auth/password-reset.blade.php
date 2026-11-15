<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Your Password</title>
    </head>

    <body style="margin: 0; padding: 40px 15px; background-color: #f5f5f4; font-family: Arial, Helvetica, sans-serif; color: #27272a;">

        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="
                                   max-width: 600px;
                                   background-color: #ffffff;
                                   border: 1px solid #d6d3d1;
                                   border-radius: 4px;
                                   box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                               ">

                        <!-- Header -->
                        <tr>
                            <td align="center" style="padding: 24px;background-color: #fafaf9;border-bottom: 1px solid #d6d3d1;">
                                <h1 style="margin: 0;font-size: 24px;font-weight: normal;line-height: 1.4;color: #292524;">
                                    Hey there, {{ $user->name }}!
                                </h1>
                            </td>
                        </tr>

                        <!-- Message -->
                        <tr>
                            <td align="center" style="padding: 24px;color: #a1a1aa;font-size: 15px;line-height: 1.6;">
                                <p style="margin: 0 0 12px;">
                                    We've received a request to reset the password associated with your account.
                                </p>

                                <p style="margin: 0;">
                                    This password reset link will expire in {{ config('auth.passwords.users.expire') }} minutes.
                                </p>
                            </td>
                        </tr>

                        <!-- Reset Button -->
                        <tr>
                            <td align="center" style="padding: 10px 24px 24px;">
                                <a href="{{ $url }}" style="display: inline-block;padding: 10px 20px;background-color: #d6d3d1;color: #292524;font-size: 14px;text-decoration: none;border-radius: 3px;">
                                    Reset Password
                                </a>
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td align="center" style="padding: 24px;border-top: 1px solid #d6d3d1;">
                                <p style="margin: 0 0 12px;color: #a1a1aa;font-size: 14px;line-height: 1.5;">
                                    If the button above doesn't work, copy and paste the following URL into your browser:
                                </p>

                                <p style="margin: 0;color: #60a5fa;font-size: 12px;line-height: 1.5;word-break: break-all;">
                                    <span style="color: #60a5fa; text-decoration: none;">
                                        {{ $url }}
                                    </span>
                                </p>
                            </td>
                        </tr>
                    </table>

                    <!-- Disclaimer -->
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px;">
                        <tr>
                            <td align="center" style="padding: 16px 10px;color: #a8a29e;font-size: 12px;line-height: 1.5;">
                                You can ignore this message if you did not request a password reset.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>

    </body>
</html>
