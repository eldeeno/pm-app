@component('mail::message')
    # Your Account Login Details

    Hello {{ $user->name }},

    An account has been created for you on {{ config('app.name') }}. Below are your login details:

    - Email: {{ $user->email }}
    - Temporary Password: {{ $temporaryPassword }}

    Please use the temporary password to log in and reset your password immediately.

    @component('mail::button', ['url' => $resetPasswordUrl])
        Reset Password
    @endcomponent

    If you did not request this account, please contact us immediately.

    Thanks,<br>
    {{ config('app.name') }}

@endcomponent
