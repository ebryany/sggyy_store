<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dana Dikembalikan - Ebrystoree</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">💰 Dana Dikembalikan</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <p>Halo,</p>
        
        @if(auth()->check() && auth()->id() === $order->user_id)
        <p>Dana untuk pesanan <strong>#{{ $order->order_number }}</strong> telah dikembalikan ke wallet Anda!</p>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #fa709a;">
            <h3 style="margin-top: 0;">Detail Refund:</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 10px 0;"><strong>Total Refund:</strong> Rp {{ number_format($amount, 0, ',', '.') }}</li>
                <li style="margin: 10px 0;"><strong>Status:</strong> Dana telah ditambahkan ke wallet Anda</li>
            </ul>
        </div>
        
        <p>Anda dapat menggunakan dana ini untuk transaksi berikutnya atau melakukan withdrawal.</p>
        @else
        <p>Dispute untuk pesanan <strong>#{{ $order->order_number }}</strong> telah diselesaikan dengan refund ke buyer.</p>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #fa709a;">
            <h3 style="margin-top: 0;">Detail:</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 10px 0;"><strong>Total Refund:</strong> Rp {{ number_format($amount, 0, ',', '.') }}</li>
                <li style="margin: 10px 0;"><strong>Status:</strong> Dana dikembalikan ke buyer</li>
            </ul>
        </div>
        @endif
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('orders.show', $order) }}" style="background: #fa709a; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Lihat Detail Pesanan
            </a>
        </div>
        
        <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;">
            Email ini dikirim otomatis oleh sistem Ebrystoree.
        </p>
    </div>
</body>
</html>

