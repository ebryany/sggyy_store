# 📊 LAPORAN KEUANGAN EBRYSTOREE

## 🎯 OVERVIEW

Dokumentasi ini menjelaskan sistem perhitungan keuangan untuk platform Ebrystoree, termasuk fee platform, biaya payment gateway (Veripay), dan biaya payout.

---

## 💰 STRUKTUR BIAYA

### 1. **Fee Platform (Platform Fee)**
- **Nilai**: Rp 4.000 (fixed per transaksi)
- **Deskripsi**: Biaya tetap yang dikenakan untuk setiap transaksi sebagai fee platform
- **Penerima**: Platform Ebrystoree

### 2. **Biaya Payment Gateway - QRIS (Veripay)**
- **Nilai**: 5% dari total transaksi
- **Deskripsi**: Biaya yang dikenakan oleh Veripay untuk setiap transaksi QRIS
- **Penerima**: Veripay (Payment Gateway)
- **Formula**: `Biaya QRIS = Total Transaksi × 5%`

### 3. **Biaya Payout (Withdraw)**
- **Nilai**: Rp 2.500 + 1% dari jumlah payout
- **Deskripsi**: Biaya yang dikenakan oleh Veripay untuk setiap penarikan dana (payout)
- **Penerima**: Veripay (Payment Gateway)
- **Formula**: `Biaya Payout = Rp 2.500 + (Jumlah Payout × 1%)`

---

## 📈 KALKULASI PENGHASILAN PLATFORM

### **Contoh Perhitungan Transaksi**

#### **Skenario 1: Transaksi Rp 100.000**

```
Total Transaksi (Buyer Bayar)    : Rp 100.000
─────────────────────────────────────────────
Biaya QRIS (5%)                   : Rp   5.000
Fee Platform                      : Rp   4.000
─────────────────────────────────────────────
Dana Masuk ke Platform            : Rp  91.000
Seller Earning (Dana ke Seller)   : Rp  96.000
─────────────────────────────────────────────
Penghasilan Platform (Net)         : Rp   4.000
```

**Rincian:**
- Buyer membayar: **Rp 100.000**
- Biaya QRIS (5%): **Rp 5.000** → Dibayar ke Veripay
- Fee Platform: **Rp 4.000** → Penghasilan platform
- Seller Earning: **Rp 96.000** → Dana yang akan diterima seller

**Catatan**: Seller earning dihitung dari total transaksi dikurangi fee platform, bukan dikurangi biaya QRIS (karena biaya QRIS dibebankan ke buyer).

---

#### **Skenario 2: Transaksi Rp 500.000**

```
Total Transaksi (Buyer Bayar)    : Rp 500.000
─────────────────────────────────────────────
Biaya QRIS (5%)                   : Rp  25.000
Fee Platform                      : Rp   4.000
─────────────────────────────────────────────
Dana Masuk ke Platform            : Rp 471.000
Seller Earning (Dana ke Seller)   : Rp 496.000
─────────────────────────────────────────────
Penghasilan Platform (Net)         : Rp   4.000
```

---

#### **Skenario 3: Transaksi Rp 1.000.000**

```
Total Transaksi (Buyer Bayar)    : Rp 1.000.000
─────────────────────────────────────────────
Biaya QRIS (5%)                   : Rp    50.000
Fee Platform                      : Rp     4.000
─────────────────────────────────────────────
Dana Masuk ke Platform            : Rp   946.000
Seller Earning (Dana ke Seller)   : Rp   996.000
─────────────────────────────────────────────
Penghasilan Platform (Net)         : Rp     4.000
```

---

## 💸 KALKULASI BIAYA PAYOUT

### **Contoh Perhitungan Payout**

#### **Skenario 1: Seller Withdraw Rp 100.000**

```
Jumlah Payout                     : Rp 100.000
─────────────────────────────────────────────
Biaya Payout (2.500 + 1%)         : Rp   3.500
─────────────────────────────────────────────
Dana yang Diterima Seller         : Rp  96.500
```

**Rincian:**
- Biaya Payout: Rp 2.500 + (Rp 100.000 × 1%) = Rp 2.500 + Rp 1.000 = **Rp 3.500**
- Dana yang diterima seller: Rp 100.000 - Rp 3.500 = **Rp 96.500**

---

#### **Skenario 2: Seller Withdraw Rp 500.000**

```
Jumlah Payout                     : Rp 500.000
─────────────────────────────────────────────
Biaya Payout (2.500 + 1%)         : Rp   7.500
─────────────────────────────────────────────
Dana yang Diterima Seller         : Rp 492.500
```

**Rincian:**
- Biaya Payout: Rp 2.500 + (Rp 500.000 × 1%) = Rp 2.500 + Rp 5.000 = **Rp 7.500**
- Dana yang diterima seller: Rp 500.000 - Rp 7.500 = **Rp 492.500**

---

#### **Skenario 3: Seller Withdraw Rp 1.000.000**

```
Jumlah Payout                     : Rp 1.000.000
─────────────────────────────────────────────
Biaya Payout (2.500 + 1%)         : Rp    12.500
─────────────────────────────────────────────
Dana yang Diterima Seller         : Rp   987.500
```

**Rincian:**
- Biaya Payout: Rp 2.500 + (Rp 1.000.000 × 1%) = Rp 2.500 + Rp 10.000 = **Rp 12.500**
- Dana yang diterima seller: Rp 1.000.000 - Rp 12.500 = **Rp 987.500**

---

## 📊 FORMULA UMUM

### **Perhitungan Transaksi**

```php
// Input
$totalTransaction = 100000; // Total yang dibayar buyer

// Biaya QRIS (5%)
$qrisFee = $totalTransaction * 0.05;

// Fee Platform (Fixed)
$platformFee = 4000;

// Seller Earning
$sellerEarning = $totalTransaction - $platformFee;

// Dana Masuk ke Platform (setelah biaya QRIS)
$platformRevenue = $totalTransaction - $qrisFee;

// Penghasilan Platform (Net)
$platformNetIncome = $platformFee; // Karena biaya QRIS sudah dikurangi dari dana masuk
```

### **Perhitungan Payout**

```php
// Input
$payoutAmount = 100000; // Jumlah yang akan di-withdraw

// Biaya Payout (2.500 + 1%)
$payoutFee = 2500 + ($payoutAmount * 0.01);

// Dana yang Diterima Seller
$sellerReceived = $payoutAmount - $payoutFee;
```

---

## 🔄 FLOW KEUANGAN LENGKAP

### **1. Transaksi Order**

```
Buyer Membayar: Rp 100.000
    ↓
Veripay Menerima: Rp 100.000
    ↓
Biaya QRIS (5%): Rp 5.000 → Dibayar ke Veripay
    ↓
Dana Masuk Platform: Rp 95.000
    ↓
Fee Platform: Rp 4.000 → Penghasilan Platform
    ↓
Seller Earning: Rp 96.000 → Tersedia untuk withdrawal
```

### **2. Seller Withdraw**

```
Seller Request Withdraw: Rp 96.000
    ↓
Biaya Payout: Rp 2.500 + (Rp 96.000 × 1%) = Rp 3.460
    ↓
Dana yang Diterima Seller: Rp 96.000 - Rp 3.460 = Rp 92.540
```

---

## 📋 LAPORAN KEUANGAN BULANAN

### **Contoh Laporan (Asumsi 100 Transaksi @ Rp 100.000)**

```
═══════════════════════════════════════════════════
           LAPORAN KEUANGAN BULANAN
═══════════════════════════════════════════════════

📊 TRANSAKSI
─────────────────────────────────────────────────
Total Transaksi                    : 100 transaksi
Total Nilai Transaksi               : Rp 10.000.000
Total Biaya QRIS (5%)               : Rp    500.000
─────────────────────────────────────────────────
Dana Masuk Platform                 : Rp  9.500.000

💰 PENGHASILAN PLATFORM
─────────────────────────────────────────────────
Total Fee Platform (100 × 4.000)   : Rp    400.000
─────────────────────────────────────────────────
Penghasilan Platform (Gross)        : Rp    400.000

💸 PAYOUT SELLER
─────────────────────────────────────────────────
Total Payout Request                : Rp  9.600.000
Total Biaya Payout                   : Rp    121.000
─────────────────────────────────────────────────
Dana yang Diterima Seller            : Rp  9.479.000

📈 RINGKASAN
─────────────────────────────────────────────────
Penghasilan Platform (Net)          : Rp    400.000
Total Biaya Payment Gateway          : Rp    500.000
Total Biaya Payout                   : Rp    121.000
─────────────────────────────────────────────────
Total Biaya Operasional              : Rp    621.000
```

---

## ⚙️ KONFIGURASI DI SYSTEM

### **Settings yang Perlu Dikonfigurasi:**

1. **Platform Fee (Fixed)**
   - Key: `platform_fee_fixed`
   - Value: `4000`
   - Unit: Rupiah (IDR)

2. **Biaya QRIS (Percentage)**
   - Key: `payment_gateway_qris_fee_percent`
   - Value: `5`
   - Unit: Persen (%)

3. **Biaya Payout (Fixed + Percentage)**
   - Key: `payout_fee_fixed`
   - Value: `2500`
   - Unit: Rupiah (IDR)
   - Key: `payout_fee_percent`
   - Value: `1`
   - Unit: Persen (%)

---

## 🔍 CATATAN PENTING

1. **Biaya QRIS dibebankan ke Buyer**: Biaya QRIS (5%) sudah termasuk dalam total yang dibayar buyer, sehingga tidak mengurangi seller earning.

2. **Fee Platform Fixed**: Fee platform selalu Rp 4.000 per transaksi, tidak peduli nilai transaksi.

3. **Biaya Payout**: Dibebankan saat seller melakukan withdraw, bukan saat transaksi.

4. **Seller Earning**: Dihitung dari total transaksi dikurangi fee platform saja, tidak dikurangi biaya QRIS.

5. **Penghasilan Platform**: Adalah fee platform (Rp 4.000 per transaksi), karena biaya QRIS sudah dikurangi dari dana masuk.

---

## 📝 IMPLEMENTASI

Lihat file:
- `app/Services/FinancialReportService.php` - Service untuk menghitung laporan keuangan
- `app/Http/Controllers/Admin/FinancialReportController.php` - Controller untuk menampilkan laporan
- `resources/views/admin/financial-report/index.blade.php` - View untuk laporan keuangan

---

**Last Updated**: 2025-01-12
**Version**: 1.0

