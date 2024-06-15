
<table class="tb-head">
	<tr>
		<td></td>
		<td><p style="text-align:center;font-size: 22px;width: 40vw" class="bg-red"><?= $data['title']; ?></p></td>
		<td></td>
	</tr>
	<tr class="tr-title">
		<td><p style="text-align: left;">Rekap <?= $data['tipe_rekap']; ?></p></td>
		<td></td>
		<td></td>
		<td><p style="text-align:right;margin-left: 72px;"><?= $data['time'] ?> </p></td>
	</tr>
</table>
<table class="tb-content">
	<thead>
		<tr class="tr-head">
			<th>Kustomer</th>
			<th>Nominal</th>
			<th>Tanggal Transaksi</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach($transaksi as $val): ?>
			<tr class="tr-body">
				<td><?= $val->customer_name; ?></td>
				<td>Rp. <?= number_format($val->total_amount,0,'.','.');; ?></td>
				<td><?= get_waktu($val->created_at, 'd-m-Y'); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<table class="tb-foot">
	<tr>
		<td>Waktu</td>
		<td>Total Jumlah Transaksi</td>
		<td>Total Nominal Transaksi</td>
	</tr>
	<tr>
		<td><?= $data['time']; ?></td>
		<td><?= $data['transaction_count']; ?></td>
		<td><?= 'Rp. '.number_format($data['total_amount'],0,'.','.'); ?></td>
	</tr>
</table>



<style type="text/css">
	table.tb-foot{
		margin-top: 40px;
		width: 100% !important;
		border:1.5px dashed gray;
	}
	table.tb-foot tr td{
		padding: 6px 0px;
		text-align: center;
	}
	table.tb-foot tr:nth-of-type(1) td{
		background-color: #ccc;
	}
	table.tb-head,
	table.tb-content{
		width: 100% !important;
	}
	.tb-head tr.tr-title td{
		font-weight: bolder;
	}
	.tb-content thead th{
		border-top: 2px solid #000;
		color:#fff;
		background-color: #5b5b5b;
	}
	.tb-content thead th,
	.tb-content tr.tr-body td{
		text-align: left;
		padding: 8px 4px;
		border-bottom:1px solid grey;
	}
	.tb-content tbody tr:nth-child(even) td{
		background-color: #f2f2f2!important;
	}
	.bg-red{
		border-radius: 18px;
		display: block;
		padding: 5px 8px;
		color: #fff;
		background: rgba(220,63,79,1);
	}
</style>