<!DOCTYPE html>
<html>
	<head>
	    <meta charset="UTF-8">
	    <title>Receipt</title>
	    <style>
	        body {
	            font-family: monospace;
	            font-size: 12px;
	            width: 58mm;
	            margin: 0;
	            padding: 5px;
	        }
	        .center { text-align: center; }
	        .right { text-align: right; }
	        hr { border: none; border-top: 1px dashed #000; }
	        table { width: 100%; }
	        td { vertical-align: top; }
	    </style>
	</head>
	<body onload="window.print()">
		<div class="center">
		    <strong>SAHIRA BUTIK HOTEL</strong><br>
		    Restaurant & Room Service<br>
		    --------------------------
		</div>

		<p>
		    Order #: <?= esc($order['order_no']) ?><br>
		    Date  : <?= date('d/m/Y H:i', strtotime($order['created_at'] ?? 'now')) ?><br>
		    Type  : <?= esc($order['order_type']) ?><br>
		    <?= $order['room_no'] ? 'Room  : '.esc($order['room_no']).'<br>' : '' ?>
		</p>
		<hr>
		<table>
		    <?php foreach ($items as $i): ?>
		    <tr>
		        <td>
		            <?= esc($i['menu_name']) ?>
		            <?= $i['variant_name'] ? '<br><small>'.$i['variant_name'].'</small>' : '' ?>
		            <?= $i['notes'] ? '<br><small>📝 '.$i['notes'].'</small>' : '' ?>
		        </td>
		    </tr>
		    <tr>
		        <td>
		            <?= $i['qty'] ?> x <?= number_format($i['price']) ?>
		            <span class="right" style="float:right">
		                <?= number_format($i['subtotal']) ?>
		            </span>
		        </td>
		    </tr>
		    <?php endforeach ?>
		</table>
		<hr>
		<table>
		    <tr>
		        <td>Total</td>
		        <td class="right"><?= number_format($order['total']) ?></td>
		    </tr>
		    <tr>
		        <td>Payment</td>
		        <td class="right"><?= esc($order['payment_method']) ?></td>
		    </tr>
		</table>
		<hr>
		<div class="center">
		    Thank you for your order<br>
		    Enjoy your meal
		</div>
	</body>
</html>
