# Laravel API

> This is a Laravel 13+ powered API boilerplate. This boilerplate includes examples of using policies, observers, gates,
> permissions, model resources, a custom `AuthorizeController` attribute, enhanced JSON error handling and more.
>
> I built this boilerplate to circumvent the need to constantly implement these features each time I build an API using
> Laravel 13. Since (at the time of creating this) there is no viable API starter-kit for Laravel that fits my needs, I
> felt it prudent to create one for my own use-cases.
>
> The sole objective is to give a balanced and configurable starting point with good out-of-the-box features, and
> examples of how to build an API architecture the "Laravel Way". This _should_ eliminate unnecessary code and allow
> the application to run as optimally as possible.
>
> All files that are unrelated to an API (unnecessary views, `package.json`, `app.ts`, `bootstrap.ts`, etc.) have been
> removed and all that is left is a robust API shell.
>
> All tests are written using PestPHP and are included in the codebase.

## Included in this repo:

- Sanctum implementation:
    - Laravel Sanctum is used to secure the API.
- Example controllers
    - with appropriate request classes and resource routes
    - included is the `AuthorizeController` attribute which allows you to run `Authorize` on all resource controller
      action methods by including a single attribute on the controller class and not one attribute per method. This is
      accomplished with a custom `AuthorizeResourceControllers` middleware implementation
    - example query filtering using `spatie/laravel-query-builder`
    - authentication controllers as well as the necessary logic to log a user in, assign the user a token and log
      a user out of one, or multiple devices
- Forgot Password / Password Reset:
    - the `ForgotPasswordController` is used to send a password reset link, and verify the password reset functionality.
    - the `ResetPasswordNotification` is used to send the user their email: allowing them to reset their password.
    - a blade view is used when sending emails using  `ResetPasswordNotification`. It is a basic,
      customizable file that uses inline CSS for styling.
- Example models:
    - relationships have been eager loaded and all the relevant policies authorize model actions using
      `spatie/laravel-permission`
    - resource classes are used to shape the model data
    - observers are used to modify model properties upon creating and updating the model
- Custom `bootstrap/app.php`:
    - `bootstrap/app.php` has been customized to allow for JSON error handling, and custom error messages returned by
      the server
- Custom config:
    - `config/auth.php` has been updated to include two custom properties for the default role of a user, and the
      default admin role
    - `config/app.php` has been updated to include a frontend url to use when performing actions that require that this
      URL is present (such as resetting passwords)
- Migrations, seeders, factories:
    - All database data for testing is included via seeders, migrations and factories
- Fully tested codebase:
    - All tests are written using `PestPHP`. The application tests can
      be [found here](https://github.com/jason-napolitano/laravel-api/tree/main/tests)

## Future implementations:

- [ ] API based OTP system

## Setup

```bash
$ cp .env.example .env
```

```bash
$ php artisan key:generate
```

```bash
$ php artisan storage:link
```

```bash
$ php artisan migrate:fresh --seed
```

### Default Admin Account

- email: `dummy@localhost`
- password: `password`

# License

MIT License

Copyright (c) 2026 Jason Napolitano

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
