<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escrow Dilepas - Ebrystoree</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">✅ Escrow Dilepas</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <p>Halo,</p>
        
        <p>Escrow untuk pesanan <strong>#<?php echo e($order->order_number); ?></strong> telah dilepas!</p>
        
        <?php
            $releaseTypeLabels = [
                'early' => 'dilepas lebih awal saat buyer konfirmasi selesai',
                'auto' => 'dilepas otomatis setelah periode hold selesai',
                'manual' => 'dilepas secara manual oleh admin',
            ];
        ?>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #11998e;">
            <h3 style="margin-top: 0;">Detail Release:</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 10px 0;"><strong>Total Dana:</strong> Rp <?php echo e(number_format($order->escrow->amount, 0, ',', '.')); ?></li>
                <li style="margin: 10px 0;"><strong>Tipe Release:</strong> <?php echo e($releaseTypeLabels[$releaseType] ?? 'Dilepas'); ?></li>
                <li style="margin: 10px 0;"><strong>Dilepas pada:</strong> <?php echo e($order->escrow->released_at->format('d M Y, H:i')); ?></li>
            </ul>
        </div>
        
        <?php if(auth()->check() && (auth()->id() === ($order->product?->user_id ?? $order->service?->user_id))): ?>
        <p><strong>Untuk Seller:</strong> Dana sebesar <strong>Rp <?php echo e(number_format($order->escrow->seller_earning ?? $order->escrow->amount, 0, ',', '.')); ?></strong> telah tersedia untuk withdrawal di dashboard seller Anda.</p>
        <?php else: ?>
        <p><strong>Untuk Buyer:</strong> Dana telah dikirim ke seller sesuai dengan ketentuan escrow.</p>
        <?php endif; ?>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="<?php echo e(route('orders.show', $order)); ?>" style="background: #11998e; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Lihat Detail Pesanan
            </a>
        </div>
        
        <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;">
            Email ini dikirim otomatis oleh sistem Ebrystoree.
        </p>
    </div>
</body>
</html>

<?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/emails/escrow/released.blade.php ENDPATH**/ ?>