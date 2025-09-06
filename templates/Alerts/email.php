
<?php

// $this->debug ($merchant);

if (count ($merchant ['totals']) == 0) {
	 
	 $this->debug ('email, no totals... ');

	 return;
}

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
	 'font-size: 30px;' .
	 'line-height: 38px;';

$textMedium =
	 'font-weight: 300;' .
	 'font-size: 22px;' .
	 'line-height: 28px;';

$anchorStyle =
	 'text-decoration: none;' .
	 'display: inline-block;' .

	 $title = 'VideoRegister Alert Report for ' . $merchant ['alert_date'] . '';

?>
<html>
	 
	 <body style="<?= $bodyStyle?>">

		  <table style="margin: 0px auto;table-layout:fixed;background:#ffffff" width="50%" cellpadding="0" cellspacing="0">
			   <tbody>
					 <tr>
						  <td style="text-align:center;padding:30px 0 0px" align="center">
								<h3 style="<?= $textStyle . $textLarge?>"><?= $title ?></h3>
						  </td>
					 </tr>
					 <tr>
						  <td style="text-align:center;padding:30px 0 0px" align="center">
								<h4 style="<?= $textStyle . $textLarge?>"><?= $merchant ['merchant_name'] ?></h4>
						  </td>
					 </tr>
					 <tr>
						  <td style="text-align:center;padding:30px 0 0px" align="center">
								<div style="<?= $textStyle . $textMedium ?>"><?= __ ('Below is a summary of transactions that exceeded typical Refund, Discount and/or No Sales values. Log into your <a href="https://myvideoregister.com" style="text-decoration:none;color:#2d394f;font-weight:600;" target="_blank">MyVideoregister</a> account for details and transaction video.') ?></div>
						  </td>
					 </tr>
					 <tr>
						  <td style="text-align:center;padding:30px 0 0px" align="center">
								<a href="https://myvideoregister.com" style="<?= $buttonStyle ?>" target="_blank"><?= __ ('MyVideoRegister Dashboard') ?></a>
						  </td>
					 </tr>
				</tbody>
		  </table>

        <table cellpadding="10" cellspacing="10" style="margin: 0px auto;width:50%">
				<tbody>
					 <tr>
						  <td style="<?= $textStyle . $textLarge?>" align="center" colspan="3">
								<?= __ ('Totals and Alerts') ?>
						  </td>
					 </tr>
					 <tr>
						  <td style="<?= $textStyle ?>" align="left">
								<?= __ ('TOTAL SALES') ?>
						  </td>
						  <td></td>
						  <td style="<?= $textStyle . $green ?>" align="right">
								<?= sprintf ("%.2f", $merchant ['totals'] [0] ['amount']) ?>
						  </td>
					 </tr>
					 <tr>
						  <td style="<?= $textStyle ?>" align="left">
								<?= __ ('TOTAL TRANSACTIONS') ?>
						  </td>
						  <td></td>
						  <td style="<?= $textStyle . $green ?>" align="right">
								<?= sprintf ("%d", $merchant ['totals'] [0] ['quantity']) ?>
						  </td>
					 </tr>
					 
					 <tr style="margin-top:25px;">
						  <td style="<?= $textStyle ?>" align="left"><?= __ ('Alert') ?></td>
						  <td style="<?= $textStyle ?>" align="right"><?= __ ('Average Value') ?></td>
						  <td style="<?= $textStyle ?>" align="right"><?= __('Alert Value') ?></td>
					 </tr>
					 <?php

					 $title = '';
					 $amount = 0;
					 
					 foreach ($merchant ['alerts'] as $alert) {
						  
						  $this->debug ($alert);
						  
						  $title = $alert ['alert_text'];

						  if ($alert ['exception']) {
								
								$amount = sprintf ("%.2f", floatval ($alert ['exception'] ['amount']));
					 ?>
						  
						  <tr>
								<td style="<?= $textStyle ?>" align="left">
									 <?= $title ?>
								</td>
								<td style="<?= $textStyle ?>" align="right"><?= $alert ['exception'] ['median'] ?></td>
								<td style="<?= $textStyle . $red ?>" align="right">
									 <?= $amount ?>
								</td>
						  </tr>
					 <?php
					 }
					 }
					 ?>
					 <tr>
						  <td style="<?= $textSmall . $textCenter?>" colspan="3">
								<br/><br/><?= __ ('To adjust alert values log into your <a href="https://myvideoregister.com" style="text-decoration:none;color:#2d394f;font-weight:600;" target="_blank">MyVideoRegister</a> account, go to the alerts page and select the settings icon in the upper right corner.') ?><br/><br/><br/>
								© 2024 VideoRegister LLC<br/>
								<a href="#" style="<?= $textSmall . $anchorStyle ?>">Privace Notice</a><br/>
								<a href="#" style="<?= $textSmall . $anchorStyle ?>">Unsubscribe</a>
						  </td>
					 </tr>
				</tbody>
				
		  </table>
	 </body>
</html>

<script>

 var expanded = false;
 function show (id) {

	  var elements = document.querySelectorAll (".visible");
	  for (var i = 0; i < elements.length; i++) {
			
			elements [i].classList.remove ('visible')
			elements [i].classList.add ('invisible')
	  }
	  
	  console.log ('show... ' + id);
	  
	  document.getElementById (id).classList.remove ('invisible')
	  document.getElementById (id).classList.add ('visible');
 }
 
</script>
