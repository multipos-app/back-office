var controls = {

	 // payment
	 
	 'separator-payment': { desc: " --- <?= __ ('Payment') ?> <?= __('Functions') ?> --- "},
	 'CashTender': { desc: "<?= __ ('Cash Tender') ?>", method: cashTender, params: { tender_id: 1 }, prompts: [], help: 'Pay cash'},
	 'CreditTender': { desc: "<?= __ ('External Credit') ?>", method: creditTender, params: { tender_id: 2 }, prompts: [], help: 'Pay credit'},
	 'CheckTender': { desc: "<?= __ ('Check Tender') ?>", method: noParams, params: { tender_id: 3 }, prompts: [], help: 'Pay check'},
	 'AccountTender': { desc: "<?= __ ('Account Tender') ?>", method: noParams, params: { tender_id: 3 }, prompts: [], help: 'Bill to account'},
	 'GiftCardTender': { desc: "<?= __ ('Gift Card Tender') ?>", method: noParams, params: { tender_id: 4 }, prompts: [], help: 'Pay gift card'},
	 'GiftCertficateTender': { desc: "<?= __ ('Gift Certificate Tender') ?>", method: noParams, params: { tender_id: 5 }, prompts: [], help: 'Pay gift certificati'},
	 'VoucherTender': { desc: "<?= __ ('Voucher Tender') ?>", method: noParams, params: { tender_id: 5 }, prompts: [], help: 'Voucher payment'},
	 'MobileTender': { desc: "<?= __ ('Mobile Payment') ?>", method: noParams, params: { tender_id: 7 }, prompts: [], help: 'MobilePay'},
	 'CardTender': { desc: "<?= __ ('Card Payment') ?>", method: noParams, params: { tender_id: 8 }, prompts: [], help: 'Pay credit card'},
	 'SumUpTender': { desc: "<?= __ ('SumUp') ?>", method: noParams, params: { app_key: "505c1594-33a2-45d5-b802-6a1e098c7bcf"}, prompts: [], help: 'SumUp credit'},

	 // item
	 
	 'separator-item': { desc: " --- <?= __ ('Item') ?> <?= __('Functions') ?> --- "},
	 'Item': { desc: "<?= __ ("Item") ?>", method: item , params: {}, prompts: [], help: 'Item'},
	 'OpenItem': { desc: "<?= __ ("Item, prompt for amount") ?>", method: item , params: {}, prompts: [], help: 'Open item'},
	 'DepartmentItem': { desc: "<?= __ ("Department Item") ?>", method: noParams , params: {}, prompts: [], help: 'Department item'},
	 'SearchItem': { desc: "<?= __ ("Search for item") ?>", method: noParams, params: {}, prompts: [], help: 'Search items'},
	 'ExchangeItems': { desc: "<?= __ ('Exchange Items') ?>", method: noParams, params: {}, prompts: [], help: 'Mark exchange items'},
	 'Misc': { desc: "<?= __ ("Misc Item") ?>", method: noParams, params: {}, prompts: [], help: 'Misc item'},
	 'MultKey': { desc: "<?= __ ("multiplier key") ?>", method: noParams, params: {}, prompts: [], help: 'Multiplier key'},
	 'PriceCheck': { desc: "<?= __ ("Price Check") ?>", method: noParams, params: {}, prompts: [], help: 'Price check'},
	 'PriceOverride': { desc: "<?= __ ("Price Override") ?>", method: noParams, params: {}, prompts: [], help: 'Price override'},
	 'Quantity': { desc: "<?= __ ("Quantity Button") ?>", method: noParams, params: {}, prompts: [], help: 'Enter/change uantity'},

	 // manager
	 
	 'separator-manager': { desc: " --- <?= __ ('Manager') ?> <?= __('Functions') ?> --- "},
	 'XSession': { desc: "<?= __ ("X-Report") ?>", method: noParams, params: {}, prompts: [], help: 'X Repprt'},
	 'ZSession': { desc: "<?= __ ("Z-Report") ?>", method: noParams, params: {}, prompts: [], help: 'Z Report'},
	 'SessionCount': { desc: "<?= __ ("Cash Management") ?>", method: noParams, params: {}, prompts: [], help: 'Cash management'},
	 'Bank': { desc: "<?= __ ('Drop') ?>", method: noParams, params: { "type": "cash_drop" }, prompts: [], help: 'Cash drop/Open amount'},
	 'LogOut': { desc: "<?= __ ("Log Out") ?>", method: noParams, params: {}, prompts: [], help: 'POS log out'},
	 'Exit': { desc: "<?= __ ("Exit POS") ?>", method: noParams, params: {}, prompts: [], help: 'POS exit'},
	 'OpenAmount': { desc: "<?= __ ("Update Open Amount") ?>", method: noParams, params: {}, prompts: [], help: 'Enter Open Amount'},
	 'PaidIn': { desc: "<?= __ ("Cash Paid In") ?>", method: noParams, params: {}, prompts: [], help: 'Paid in'},
	 'PaidOut': { desc: "<?= __ ("Cash Paid Out") ?>", method: noParams, params: {}, prompts: [], help: 'Paid out'},
	 'Settle': { desc: "<?= __ ("settle") ?>", method: noParams, params: {}, prompts: [], help: 'Settle credit transactions'},

	 // Sale
	 
	 'separator-sale': { desc: " --- <?= __ ('Sale') ?> <?= __('Functions') ?> --- "},
	 'NextTicket': { desc: "<?= __ ("Next Transaction") ?>", method: noParams, params: {dir: 1}, prompts: [], help: 'Next transaction'},
	 'PreviousTicket': { desc: "<?= __ ("Previous Transaction") ?>", method: noParams, params: {dir: -1}, prompts: [], help: 'Previous transation'},
	 'Navigate': { desc: "<?= __ ("Jump to Menu") ?>", method: navMenus, params: {}, prompts: [], help: 'Jump to menu'},
	 'Suspend': { desc: "<?= __ ("Suspend Transaction") ?>", method: noParams, params: {}, prompts: [], help: 'Suspend transaction'},
	 'Tabs': { desc: "<?= __ ("Open Tabs") ?>", method: noParams, params: {}, prompts: [], help: 'Open tabs'},
	 'NoSale': { desc: "<?= __ ("No Sale/Open Drawer") ?>", method: noParams, params: {}, prompts: [], help: 'Open drawer/no sale'},
	 'VoidItem': { desc: "<?= __ ("Void Item") ?>", method: noParams, params: {}, prompts: [], help: 'Void item'},
	 'VoidSale': { desc: "<?= __ ("Void Sale") ?>", method: noParams, params: {}, prompts: [], help: 'Void sale'},
	 'Clear': { desc: "<?= __ ("Clear Button") ?>", method: noParams, params: {}, prompts: [], help: 'Clear/cancel operation'},
	 'CompSale': { desc: "<?= __ ("Comp Sale") ?>", method: noParams, params: {}, prompts: [], help: 'Comp sale'},
	 'NonTax': { desc: "<?= __ ("Non-Tax Sale") ?>", method: noParams, params: {}, prompts: [], help: 'Non tax sale'},
	 'PrintKitchen': { desc: "<?= __ ("Print to Kitchen") ?>", method: noParams, params: {}, prompts: [], help: 'Print kitchen receipt'},
	 'PrintReceipt': { desc: "<?= __ ("Print Receit") ?>", method: noParams, params: {}, prompts: [], help: 'Print rceipt'},
	 'PrintExchangeReceipt': { desc: "<?= __ ("Print Exchange Receipt") ?>", method: noParams, params: {}, prompts: [], help: 'Print exchange receipt'},
	 'PrintLastReceipt': { desc: "<?= __ ("Print Last Receipt") ?>", method: noParams, params: {}, prompts: [], help: 'Print last receipt'},
	 'SplitTicket': { desc: "<?= __ ("Split Ticket") ?>", method: noParams, params: {}, prompts: [], help: 'Spit transation into new trancaction'},
	 'RepeatLastOrder': { desc: "<?= __ ("Repeat Order") ?>", method: noParams, params: {}, prompts: [], help: 'Repeat last order'},
	 'ReturnSale': { desc: "<?= __ ("Return Sale") ?>", method: noParams, params: {}, prompts: [], help: 'Return sale'},
	 'ToggleClerkMode': { desc: "<?= __ ("Toggle Clerk Mode") ?>", method: noParams, params: {}, prompts: [], help: 'Enter POS clerk mode (prompt for clerk)'},

	 // bank
	 
	 'separator-verifone': { desc: " --- <?= __ ('Verifone') ?> <?= __('Functions') ?> --- "},
	 'VimTender': { desc: "<?= __ ('Verfone/Nets Credit') ?>", method: creditTender, params: { tender_id: 2 }, prompts: [], help: 'Verifone payment'},
	 'VimRefund': { desc: "<?= __ ('Verifone/Nets Refund') ?>", method: noParams, params: { tender_id: 2 }, prompts: [], help: 'Verifone refund'},
	 'VimReverse': { desc: "<?= __ ('Verifone/Nets Reverse') ?>", method: noParams, params: { tender_id: 2 }, prompts: [], help: 'Verifone reverse payment'},
	 'VimReport': { desc: "<?= __ ('Verifone/Nets Terminal Report') ?>", method: noParams, params: { }, prompts: [], help: 'Verifone terminal report'},
	 
	 // discounts
	 
	 'separator-discounts': { desc: " --- <?= __ ('Discounts') ?> <?= __('Functions') ?> --- "},
	 'ItemMarkdownAmount': { desc: "<?= __ ("Markdown Item by Amount") ?>", method: enterAmount , params: { amount: 0, type: 'currency' }, prompts: [], help: 'Markdown item by amount'},
	 'ItemMarkdownPercent': { desc: "<?= __ ("Markdown Item by Percent") ?>", method: enterPercent , params: {}, prompts: [], help: 'Markdown item by percent'},
	 'RemoveAddon': { desc: "<?= __ ("Cancel All Discounts") ?>", method: noParams, params: {}, prompts: [], help: 'Cancel all discounts'},
	 'SaleDiscountPercent': { desc: "<?= __ ("Discount All Items by Percent") ?>", method: enterPercent, params: {}, prompts: [], help: 'All items percent markdoown'},

	 // misc
	 
	 'separator-misc': { desc: " --- <?= __ ('Misc') ?> <?= __('Functions') ?> --- "},
	 'Enter': { desc: "<?= __ ("Enter Button") ?>", method: noParams, params: {}, prompts: [], help: 'Enter key'},
	 'NumKey': { desc: "<?= __ ("Number Button") ?>", method: noParams, params: {}, prompts: [], help: 'Number key'},
	 'PrintTest': { desc: "<?= __ ("print test") ?>", method: noParams, params: {}, prompts: [], help: 'Check printer'},
	 'CameraIP': { desc: "<?= __ ('Camera Setup') ?>", method: noParams, params: {}, prompts: [], help: 'Enter video camera IP address'},
    'Null': { desc: "<?=  __ ('Select Function') ?>", method: empty, params: {}, prompts: [''], help: 'No op'}
}

