@component('mail::message')

<table style="border:0 none;border-spacing:0;font-family:Arial,sans-serif;color:#000;font-weight:normal;text-align:left;width:100%" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td colspan="2">
            <table style="border:0 none;border-spacing:0;font-family:Arial,sans-serif;color:#000;font-weight:normal;text-align:left;font-weight:100;width:100%" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="font-size:24px;color:#333;line-height:100%;padding:0px 0 0;font-weight:bold">Dear {{ ucwords($user->name) }},</td>
                </tr>

            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-family:Arial,sans-serif;padding:20px 0 0 0">
            <p style="font-family:Arial,sans-serif;margin:0;padding:0;line-height:24px">Welcome to {{ config('app.name') }}!! We are glad you are here.</p>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-family:Arial,sans-serif;padding:20px 0 0 0">
            <p style="font-family:Arial,sans-serif;margin:0;padding:0;line-height:24px">Your account is now active and ready to use.</p>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-family:Arial,sans-serif;padding:20px 0 0 0">
            <p style="font-family:Arial,sans-serif;margin:0;padding:0;line-height:24px">Here are your login credentials.</p>
            <br/>
            <p style="font-family:Arial,sans-serif;margin:0;padding:0;line-height:24px"><strong>Email :</strong> {{ $user->email  }}</p>
            <p style="font-family:Arial,sans-serif;margin:0;padding:0;line-height:24px"><strong>Password :</strong> {{ Session::has('user_password') ? Session::get('user_password') : ''  }}</p>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-family:Arial,sans-serif;padding:20px 0 0 0">
            <p style="font-family:Arial,sans-serif;margin:0;padding:0;line-height:24px">Let’s get started!</p>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-family:Arial,sans-serif;padding:40px 0 0 0">
            <p style="font-family:Arial,sans-serif;margin:0;padding:0;line-height:24px;color:#949494">Thank you.<br> Team {{ config('app.name') }}</p>
        </td>
    </tr>
</table>
@endcomponent
