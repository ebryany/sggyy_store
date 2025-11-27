# Analisa Flow Order Digital vs Jasa

## 📊 Perbandingan Flow Saat Ini

### Order Digital (Product)
```
pending → paid → processing → waiting_confirmation → completed
```

**Timeline yang ditampilkan:**
1. ✅ Pesanan Dibuat
2. ✅ Pesanan Dibayarkan (Rp X)
3. ✅ Pesanan Dikirimkan (seller kirim produk/file)
4. ✅ Pesanan Selesai (buyer konfirmasi)
5. ⭐ Belum Dinilai / Sudah Dinilai

**Detail Flow:**
- Payment verified → `paid` → Create rekber → `processing` (otomatis)
- Seller klik "Kirim Produk" → `waiting_confirmation`
- Buyer klik "Konfirmasi Produk" → `completed` + Early release rekber

---

### Order Jasa (Service) - Saat Ini
```
pending → paid → accepted → processing → waiting_confirmation → completed
```

**Timeline yang ditampilkan:**
1. ✅ Pesanan Dibuat
2. ✅ Pesanan Dibayarkan (Rp X)
3. ✅ Pesanan Dikirimkan (seller upload deliverable)
4. ✅ Pesanan Selesai (buyer konfirmasi)
5. ⭐ Belum Dinilai / Sudah Dinilai

**Detail Flow:**
- Payment verified → `paid` (seller harus accept dulu)
- Seller accept order → `accepted` → `processing` (otomatis)
- Seller upload deliverable → `waiting_confirmation`
- Buyer konfirmasi → `completed` + Early release rekber

**Masalah:**
- Ada step `accepted` yang tidak ditampilkan di timeline (internal status)
- Flow lebih kompleks dari produk digital
- Seller harus accept dulu sebelum bisa mulai kerja

---

## 🎯 Rekomendasi: Unifikasi Flow Jasa dengan Produk Digital

### Flow Jasa yang Diusulkan (Sama dengan Produk Digital)
```
pending → paid → processing → waiting_confirmation → completed
```

**Timeline yang ditampilkan (SAMA dengan Produk Digital):**
1. ✅ Pesanan Dibuat
2. ✅ Pesanan Dibayarkan (Rp X)
3. ✅ Pesanan Dikirimkan (seller upload deliverable/hasil pekerjaan)
4. ✅ Pesanan Selesai (buyer konfirmasi)
5. ⭐ Belum Dinilai / Sudah Dinilai

**Detail Flow yang Diusulkan:**
- Payment verified → `paid` → Create rekber → `processing` (otomatis, skip `accepted`)
- Seller upload deliverable → `waiting_confirmation`
- Buyer klik "Konfirmasi Produk" → `completed` + Early release rekber

**Keuntungan:**
- ✅ Flow lebih sederhana dan konsisten
- ✅ Timeline sama untuk produk dan jasa
- ✅ User experience lebih mudah dipahami
- ✅ Tidak ada step internal yang membingungkan

---

## 🔄 Perubahan yang Diperlukan

### 1. PaymentService.php
**Saat ini:**
```php
// For services, update to 'paid' status (seller needs to work on it)
$this->orderService->updateStatus($order, 'paid', 'Payment verified by admin', 'admin');
```

**Harus diubah menjadi:**
```php
// For services, sama seperti produk: paid → processing (otomatis)
$this->orderService->updateStatus($order, 'paid', 'Pembayaran diverifikasi oleh admin', 'admin');
$order = $order->fresh();

// Create rekber
if (!$order->escrow) {
    $escrowService->createEscrow($order, $payment);
}

// Update to processing (seller langsung bisa mulai kerja)
$this->orderService->updateStatus($order, 'processing', 'Order diproses, seller dapat mulai mengerjakan', 'admin');
```

### 2. OrderService.php - validateStatusTransition
**Saat ini:**
```php
'paid' => ['accepted', 'cancelled'], // Seller must accept first
```

**Harus diubah menjadi:**
```php
'paid' => ['processing', 'cancelled'], // Langsung ke processing (sama seperti produk)
```

**Hapus special case untuk service:**
- Hapus logic `paid → accepted → processing`
- Gunakan flow yang sama dengan produk: `paid → processing`

### 3. OrderService.php - acceptOrder
**Opsi A:** Hapus method `acceptOrder()` (tidak diperlukan lagi)
**Opsi B:** Keep untuk backward compatibility, tapi tidak digunakan di flow baru

### 4. TimelineService.php
**Sudah benar** - Timeline jasa sudah sama dengan produk, hanya perlu pastikan tidak ada step "accepted" yang muncul

---

## 📋 Checklist Implementasi

- [ ] Update PaymentService: Service orders langsung ke `processing` setelah `paid`
- [ ] Update validateStatusTransition: Hapus `accepted` dari flow jasa
- [ ] Update XenditService: Service orders langsung ke `processing`
- [ ] Update CheckoutService: Service orders langsung ke `processing`
- [ ] Update AdminPaymentController: Service orders langsung ke `processing`
- [ ] Test: Pastikan timeline jasa sama dengan produk digital
- [ ] Test: Pastikan seller bisa langsung upload deliverable tanpa accept dulu
- [ ] Test: Pastikan rekber dibuat saat `paid → processing`

---

## 🎨 Timeline Jasa (Setelah Perubahan)

**Visual Timeline:**
```
┌─────────────────┐
│ Pesanan Dibuat  │ ← Order created
└─────────────────┘
        ↓
┌─────────────────┐
│ Pesanan         │ ← Payment verified (Rp X)
│ Dibayarkan      │
└─────────────────┘
        ↓
┌─────────────────┐
│ Pesanan         │ ← Seller upload deliverable
│ Dikirimkan      │
└─────────────────┘
        ↓
┌─────────────────┐
│ Pesanan Selesai │ ← Buyer konfirmasi
└─────────────────┘
        ↓
┌─────────────────┐
│ Belum Dinilai   │ ← Rating (optional)
└─────────────────┘
```

**Sama persis dengan timeline produk digital!** ✅

