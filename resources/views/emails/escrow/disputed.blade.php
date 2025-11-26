<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispute Dibuat - Ebrystoree</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">⚠️ Dispute Dibuat</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <p>Halo,</p>
        
        <p>Dispute telah dibuat untuk pesanan <strong>#{{ $order->order_number }}</strong> oleh {{ $disputedBy === 'buyer' ? 'buyer' : 'seller' }}.</p>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #f5576c;">
            <h3 style="margin-top: 0;">Detail Dispute:</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 10px 0;"><strong>Pesanan:</strong> #{{ $order->order_number }}</li>
                <li style="margin: 10px 0;"><strong>Dibuat oleh:</strong> {{ $disputedBy === 'buyer' ? 'Buyer' : 'Seller' }}</li>
                <li style="margin: 10px 0;"><strong>Dibuat pada:</strong> {{ $order->escrow->disputed_at->format('d M Y, H:i') }}</li>
                @if($order->escrow->dispute_reason)
                <li style="margin: 10px 0;"><strong>Alasan:</strong> {{ Str::limit($order->escrow->dispute_reason, 200) }}</li>
                @endif
            </ul>
        </div>
        
        <p><strong>Status:</strong> Admin sedang meninjau dispute ini. Dana di escrow telah dibekukan sampai admin menyelesaikan dispute.</p>
        
        <p>Admin akan memutuskan apakah dana akan dilepas ke seller atau dikembalikan ke buyer berdasarkan hasil investigasi.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('orders.show', $order) }}" style="background: #f5576c; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Lihat Detail Pesanan
            </a>
        </div>
        
        <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;">
            Email ini dikirim otomatis oleh sistem Ebrystoree.
        </p>
    </div>
</body>
</html>

