<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dana Ditahan di Escrow - Ebrystoree</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">🔒 Dana Ditahan di Escrow</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <p>Halo,</p>
        
        <p>Dana untuk pesanan <strong>#<?php echo e($order->order_number); ?></strong> telah ditahan di escrow (rekber) untuk melindungi transaksi Anda.</p>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea;">
            <h3 style="margin-top: 0;">Detail Escrow:</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 10px 0;"><strong>Total Dana:</strong> Rp <?php echo e(number_format($order->escrow->amount, 0, ',', '.')); ?></li>
                <li style="margin: 10px 0;"><strong>Periode Hold:</strong> <?php echo e($holdPeriodDays); ?> hari</li>
                <li style="margin: 10px 0;"><strong>Akan dilepas pada:</strong> <?php echo e($order->escrow->hold_until->format('d M Y, H:i')); ?></li>
            </ul>
        </div>
        
        <p><strong>Apa itu Escrow?</strong></p>
        <p>Escrow adalah sistem perlindungan pembayaran. Dana ditahan sementara sampai pesanan selesai atau periode hold selesai, kemudian dana akan otomatis dilepas ke seller.</p>
        
        <p><strong>Kapan dana dilepas?</strong></p>
        <ul>
            <li>Saat Anda (buyer) mengkonfirmasi pesanan selesai (lebih cepat)</li>
            <li>Otomatis setelah periode hold selesai (<?php echo e($holdPeriodDays); ?> hari)</li>
        </ul>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="<?php echo e(route('orders.show', $order)); ?>" style="background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Lihat Detail Pesanan
            </a>
        </div>
        
        <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;">
            Email ini dikirim otomatis oleh sistem Ebrystoree. Jika Anda tidak melakukan transaksi ini, silakan hubungi support.
        </p>
    </div>
</body>
</html>

<?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/emails/escrow/created.blade.php ENDPATH**/ ?>