@component('mail::message')
# Hello {{ $name }},

Your account has been created successfully.

**Email:** {{ $email }}  
**Password:** {{ $password }}
**Password:** {{ $password }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
