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
            <p style="font-family:Arial,sans-serif;margin:0;padding:0;line-height:24px">Thank you for your interest to sign up with the {{ config('app.name') }}.</p>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-family:Arial,sans-serif;padding:20px 0 0 0">
            <p style="font-family:Arial,sans-serif;margin:0;padding:0;line-height:24px">Please click on the following link to verify your account and login again. </p>
        </td>
    </tr>
    <tr>
        <td style="width:250px;font-family:Arial,sans-serif;padding:20px 0 0 0">
            <p style="font-family:Arial,sans-serif;margin:0;padding:0"><a href="{{ URL::to('email-verification') }}/{{ $user->verify_token }}" style="color:#ffffff;border:10px solid #171d8f;background:#171d8f;display:block;font-size:17px;width:200px;text-align:center;border-radius:4px" title="Login" target="_blank">Verify</a></p>
        </td>

    </tr>
    <tr>
        <td colspan="2" style="font-family:Arial,sans-serif;padding:40px 0 0 0">
            <p style="font-family:Arial,sans-serif;margin:0;padding:0;line-height:24px;color:#949494">Thank you.<br> Team {{ config('app.name') }}</p>
        </td>
    </tr>
</table>
@endcomponent