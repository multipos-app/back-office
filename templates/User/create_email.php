

<?php

$bodyStyle =
	 'color: #2d394f;' .
	 "font-family: sqmarket,'Helvetica Neue',sans-serif;" .
	 'font-weight: 300;' .
	 'font-size: 28px;' .
	 'line-height: 32px;' .
	 'padding: 0px 0 16px;';

$textStyle =
	 'color: #2d394f;' .
	 "font-family:'Helvetica Neue',sans-serif;" .
	 'font-weight:300;' .
	 'font-size:28px;' .
	 'line-height:32px;';

$textSmall =
	 'color: #2d394f;' .
	 'color:#2d394f;' .
	 "font-family:'Helvetica Neue',sans-serif;" .
	 'font-weight:100;' .
	 'font-size:14px;' .
	 'line-height:16px;';

$buttonStyle =
	 'text-decoration: none;' .
	 'display: inline-block;' .
	 'margin-bottom: 0;' .
	 'font-weight: 400;' .
	 'white-space: nowrap;' .
	 'vertical-align: middle;' .
	 '-ms-touch-action: manipulation;' .
	 'touch-action: manipulation;' .
	 'cursor: pointer;' .
	 'background-image: none;' .
	 'border: 1px solid transparent;' .
	 'padding: 6px 12px;' .
	 'font-size: 24px;' .
	 'line-height: 1.42857143;' .
	 'border-radius: 4px;' .
	 '-webkit-user-select: none;' .
	 '-moz-user-select: none;' .
	 '-ms-user-select: none;' .
	 'user-select: none;' .
	 'color: #fff;' .
	 'background-color: #2996CC;' .
	 'border-color: #2996CC;';

$green =
	 'color: #23B524;';

$red =
	 'color: #ed1d61;';

$textCenter =
	 'text-align:center;';

$textLarge =
	 'font-weight: 600;' .
	 'font-size: 32px;' .
	 'line-height: 42px;';

$textMedium =
	 'font-weight: 300;' .
	 'font-size: 22px;' .
	 'line-height: 28px;';

$anchorStyle =
	 'text-decoration: none;' .
	 'display: inline-block;';

?>
<html>
	 
	 <body style="<?= $bodyStyle?>">
		  
		  <table style="margin: 0px auto;table-layout:fixed;background:#ffffff" width="50%" cellpadding="0" cellspacing="0">
			   <tbody>
					 <tr>
						  <td style="text-align:center;padding:30px 0 0px" align="center">
								<h3 style="<?= $textStyle . $textLarge?>"><?= __ ('VideoRegister Password Reset') ?></h3>
						  </td>
					 </tr>
					 <tr>
						  <td style="text-align:center;padding:30px 0 0px" align="center">
								<?= __ ('Click the link below to reset your password.') ?>
						  </td>
					 </tr>
					 <tr>
						  <td style="text-align:center;padding:30px 0 0px" align="center">
								<a href="<?= $resetURL?>" style="<?= $buttonStyle ?>" target="_blank"><?= __ ('Reset Password') ?></a>
						  </td>
					 </tr>
				</tbody>
		  </table>

		  <table style="margin: 0px auto;table-layout:fixed;background:#ffffff" width="50%" cellpadding="0" cellspacing="0">
			   <tbody>
					 <tr>
						  
						  <td style="<?= $textSmall . $textCenter?>">
								<br/>
								<br/>
								<br/>
								© 2024 VideoRegister LLC<br/>
								<a href="https://videoregister.com/privacy-notice" style="<?= $textSmall . $anchorStyle ?>"><?= __ ('Privacy Notice') ?></a><br/>
						  </td>
					 </tr>
				</tbody>
		  </table>
	 </body>
</html>
