<!DOCTYPE html>
<html>
	<head>
	    <title>Print Order <?= esc($order['order_no']) ?></title>
	    <style>
	        body {
	            font-family: monospace;
	            font-size: 12px;
	        }
	        .center { text-align:center }
	        .right { text-align:right }
	        hr { border-top:1px dashed #000 }
	    </style>
	</head>
	
	<body onload="window.print()">
		<div class="center">
		    <strong>SAHIRA BUTIK HOTEL</strong><br>
		    Restaurant<br>
		    =========================
		</div>

		<br>
		Order : <?= esc($order['order_no']) ?><br>
		Room  : <?= esc($order['room_no'] ?: '-') ?><br>
		Guest : <?= esc($order['guest_name'] ?: '-') ?><br>
		Date  : <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
		<br>

		<hr>

		<?php foreach ($items as $i): ?>
		<?= esc($i['menu_name']) ?><br>
		<?php if ($i['variant_name']): ?>
		  - <?= esc($i['variant_name']) ?><br>
		<?php endif ?>
		<?= $i['qty'] ?> x <?= number_format($i['price']) ?>
		<span class="right">
		    <?= number_format($i['subtotal']) ?>
		</span><br>
		<?php endforeach ?>

		<hr>

		<strong>Total :</strong>
		<span class="right">
		    Rp <?= number_format($order['total']) ?>
		</span>

		<hr>

		Payment : <?= esc($order['payment_method']) ?><br>
		Status  : <?= esc($order['payment_status']) ?>

		<br><br>
		<div class="center">
		    Terima Kasih
		</div>
	</body>
</html>
