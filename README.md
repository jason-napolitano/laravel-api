# Laravel API

> A Laravel 13+ powered API boilerplate. This boilerplate was built to give a good starting off point for RESTful
> API's using Laravel 13+. It includes examples of using policies, observers, gates, permissions, model resources,
> a custom `AuthorizeResource` controller attribute, enhanced JSON error handling and more.
>
> All files that are unrelated to an API (views, `package.json`, `app.ts`, `bootstrap.ts`, etc.) have been removed and
> all that is left is a robust API shell. All tests are written using PestPHP and are included in the codebase.
>
> I built this boilerplate to circumvent the need to constantly implement these features each time I build an API using
> Laravel 13. Since (at the time of creating this) there is no viable API starter-kit for Laravel that fits my needs, I
> felt it prudent to create one for my own use-cases.
>
> The sole objective is to give a balanced and configurable starting point with good out-of-the-box features, and
> examples of how to build an API architecture the "Laravel Way". This _should_ eliminate unnecessary code and allow 
> the application to run as optimally as possible.

## Included in this repo:

- Example controllers
    - with appropriate request classes and resource routes. Also included is the `AuthorizeResource` attribute which
      allows you to run `Authorize` on all controller action methods by including a single attribute in the controller
      and not one attribute per method
    - example query filtering using `spatie/laravel-query-builder`
    - authentication controllers which are protected by `sanctum` as well as the necessary logic to log a user in,
      assign the user a token and log a user out of one, or multiple devices
- Example models:
    - with appropriate relationship eager loaded and all policies written and authorized using
      `spatie/laravel-permission`
    - resource classes are used to shape the model data
    - observers to allow for updating model fields upon creating and updating a model
- Custom `bootstrap/app.php`:
    - `bootstrap/app.php` has been customized to allow for JSON error handling, and custom error messages returned by
      the server
- Custom config:
    - `config/auth.php` has been updated to include two custom properties for the default role of a user, and the
      default admin role
- Migrations, seeders, factories:
    - All database data for testing is included via seeders, migrations and factories
- Fully tested codebase:
    - All tests are written using `PestPHP`. All of the tests can
      be [found here](https://github.com/jason-napolitano/laravel-api/tree/main/tests)

## Coming soon:

- Forgot password functionality
- API based OTP system

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
