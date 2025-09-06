<?php

/**
 *
 * Format the email for a free trial initiated via videoregister.com
 * currently not used.
 *
 **/

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
								<h3 style="<?= $textStyle . $textLarge?>"><?= __ ('Welcome to VideoRegister') ?></h3>
								<h5 style="<?= $textStyle . $textLarge?>"><?= __ ('Time to complete your VideoRegister Setup') ?></h5>
								<h5 style="<?= $textStyle . $textLarge?>"><?= __ ('We are grateful you chose VideoRegister!') ?></h5>
						  </td>
					 </tr>
					 <tr>
						  <td style="text-align:center;padding:30px 0 0px" align="center">
								<div style="<?= $textStyle . $textMedium ?>"><?= __ ('Please click the "Get Started" button below to complete the setup of your VideoRegister transaction video surveillance service. You will need your <b><strong><i>Square ID and password</i></strong></b> to complete your setup. The button below can only be used once; it is important that you complete the setup process once the button is clicked. The setup process should take less than 5 minutes.') ?></div>
								<br/>
								<br/>
								<div style="<?= $textStyle . $textMedium ?>"><?= __ ('As a reminder, your subscription requires a VideoRegister-certified security camera. Here is a link to order your <a href="https://www.amazon.com/dp/B0145OQXCK?th=1&_encoding=UTF8&tag=videoregist0a-20&linkCode=ur2&linkId=f53ccda6425bcb021ebcc0dace5b51ef&camp=1789&creative=9325" target="_blank">Amcrest IP2M-84 from Amazon</a> if you have not yet done so.') ?></div>
						  </td>
					 </tr>
					 <tr>
						  <td style="text-align:center;padding:30px 0 0px" align="center">
								<a href="<?= $link ?>" style="<?= $buttonStyle ?>" target="_blank"><?= __ ('Get Started') ?></a>
						  </td>
					 </tr>
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
