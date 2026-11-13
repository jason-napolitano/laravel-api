<?php

namespace App\Notifications {

    use Illuminate\Notifications\Messages\MailMessage;
    use Illuminate\Notifications\Notification;
    use Illuminate\Bus\Queueable;
    use App\Models\User;

    class ResetPasswordNotification extends Notification
    {
        use Queueable;

        public function __construct(public string $token)
        {
            // ...
        }

        public function via(object $notifiable): array
        {
            return ['mail'];
        }

        public function toMail(User $notifiable): MailMessage
        {
            $url = rtrim(config('app.frontend_url'), '/') . '/reset-password?' .
                http_build_query([
                    'token' => $this->token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ]);

            return (new MailMessage())
                ->subject('Reset Your Password')
                ->view('emails.auth.password-reset', [
                    'user'  => $notifiable,
                    'url'   => $url,
                    'token' => $this->token,
                ]);
        }
    }
}
