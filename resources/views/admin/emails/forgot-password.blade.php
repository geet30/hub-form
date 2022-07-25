<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="5%">&nbsp;</td>
        <td width="90%" align="left" valign="top">
            <font style="color:#df5555; font-size:24px"><strong style="font-weight:500"><em><span style="color:#464647">Hi</span> {{$data['name']}},</em></strong></font><br /><hr style=" border-color:#BFBFBF" /><br />
            <font style="font-family: 'Open Sans',arial,  sans-serif; color:#3b3b3b; font-size:20px; line-height:24px">
            <strong style="margin-bottom:15px; display:inline-block; font-weight:500">Forgot Password?</strong>
            </font>
            <br />
            <font style="font-family: 'Open Sans', arial, sans-serif; color:#3b3b3b; font-size:13px; line-height:21px">
                You have requested for reset password. Below is link for reset password. (If you didn't request for reset password , Please ignore this email)
            </font><br>
            <br>
            {{-- <a href="{{P2B_BASE_URL}}./reset_password/.{{$data['name']}}" style="color:#fff;font-size:18px;line-height:30px;text-decoration:none;padding:10px 15px;display:inline-block;background:#df5555;margin:15px 0" target="_blank" >Reset Password</a> --}}

    <center>
        <a href="{{ route('reset_password', ['id' => $data['id'], 'token' => $data['token']]) }}" style=" color:#fff; font-size:18px; line-height:30px; text-decoration:none; padding:10px 15px; display:inline-block; background:#df5555; margin:15px 0;" target="_blank">Reset Password</a>
    </center>
    <font style="font-family: 'Open Sans', sans-serif; color:#fff; font-size:13px; line-height:21px">
    Thanks &amp; Regards,<br />
    Atrum Coal Team</font></td>
<td width="5%">&nbsp;</td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td align="right" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
                <td align="center"><br /><font style="font-family: 'Open Sans', sans-serif; color:#3b3b3b; font-size:13px; line-height:21px"><i>Need help? Have feedback? Feel free to email us at <span style=" color:#df5555">{{!empty($data['default_mail'])? $data['default_mail'] : 'support@atrumcoal.com'}}</span></i></font><br /><br /></td>
            </tr>



        </table>
    </td>
    <td>&nbsp;</td>
</tr>
</table>