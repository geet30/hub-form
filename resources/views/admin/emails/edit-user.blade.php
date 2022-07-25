<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="5%">&nbsp;</td>
        <td width="90%" align="left" valign="top">
            <font style="color:#df5555; font-size:24px"><strong style="font-weight:500"><em><span style="color:#464647">Hi</span> <?php echo $data['name']; ?>,</em></strong></font><br /><hr style=" border-color:#BFBFBF" /><br />
            <font style="font-family: 'Open Sans',arial,  sans-serif; color:#3b3b3b; font-size:20px; line-height:24px">
            <strong style="margin-bottom:15px; display:inline-block; font-weight:500">Account Updated</strong>
            </font>
            <br />
            <font style="font-family: 'Open Sans', arial, sans-serif; color:#3b3b3b; font-size:13px; line-height:21px">
            Your account has been Updated in Atrum company. Below is your credential.
            </font><br>
            <font style="font-family: 'Open Sans', arial, sans-serif; color:#3b3b3b; font-size:12px; line-height:21px; font-weight: bold">
            Email -  &nbsp;<?php echo $data['email']; ?> <br>
            @if(isset($data['password']) && !empty($data['password']))
                Password - &nbsp;<?php echo $data['password']; ?> <br>
            @endif
            </font>
    <center>
        <a href="{{ route('login') }}" style=" color:#fff; font-size:18px; line-height:30px; text-decoration:none; padding:10px 15px; display:inline-block; background:#df5555; margin:15px 0;" target="_blank">Login</a>
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