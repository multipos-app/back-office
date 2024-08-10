<?= $this->Html->script ('https://gateway.sumup.com/gateway/ecom/card/v2/sdk.js') ?>

<fieldset class="maintenance-border">
	 <legend class="maintenance-border"><?= __ ('Add Payment Method'); ?></legend>
	 <div class="container edit">

		  <div class="row">
				<div id="sumup-card"></div>
		  </div>
	 </div>
</fieldset>

<script type="text/javascript">
    SumUpCard.mount({
        checkoutId: '2ceffb63-cbbe-4227-87cf-0409dd191a98',
        onResponse: function(type, body) {
            console.log('Type', type);
            console.log('Body', body);
        }
    });
</script>
