<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <?php if ($this->order['result'] == "vobdelavi"): ?>
    <meta http-equiv="refresh" content="1; url=http://<?php echo str_replace('www2', 'www', $_SERVER['HTTP_HOST']); ?>/moneta/nakup.php/ConfirmationID/<?php echo $this->order['id'] ?>">
    <?php elseif ($this->order['result'] == "potrjeno"): ?>
    <meta http-equiv="refresh" content="0; url=http://<?php echo str_replace('www2', 'www', $_SERVER['HTTP_HOST']); ?>/nakup/korak4moneta-success/id/<?php echo $this->order['id'] ?>">
    <?php endif; ?>
    <meta name='Price' content='<?php echo $this->price_total ?>'>
    <meta name='Quantity' content='1'>
    <meta name='VATRate' content='20'>
    <meta name='Description' content='Kupon'>
    <meta name='Currency' content='EUR'>
</head>
<body>
<p>Status nakupa: <?php echo $this->status ?></p>
</body>
</html>

<!--  -->