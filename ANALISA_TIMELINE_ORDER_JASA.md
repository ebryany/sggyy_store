# Analisa Timeline Order Jasa - Flow Lengkap

## 📋 Flow Order Jasa (Detail)

### 1. **Order Creation & Task File Upload**
**Kapan:** Saat buyer checkout service
**Status:** `pending`
**Event di Timeline:**
- ✅ **Pesanan Dibuat** (Order created)
- 📎 **File Tugas Diterima** (jika buyer upload task_file saat checkout)

**Detail:**
- Buyer checkout service → Order created dengan status `pending`
- Buyer bisa upload `task_file` (opsional) saat checkout
- File disimpan di `orders/tasks/` dan path disimpan di `order.task_file_path`
- Seller bisa download task_file untuk mulai mengerjakan

---

### 2. **Payment Verification**
**Kapan:** Admin verifies payment atau payment via wallet/Xendit
**Status:** `pending` → `paid` → `processing`
**Event di Timeline:**
- ✅ **Pesanan Dibayarkan** (Payment verified, Rp X)

**Detail:**
- Payment verified → Status `paid`
- Create rekber (escrow)
- Status otomatis ke `processing` (seller langsung bisa mulai kerja)
- **TIDAK ADA STEP "accepted"** (sama seperti produk digital)

---

### 3. **Seller Mulai Mengerjakan & Update Progress**
**Kapan:** Seller mulai kerja dan update progress secara berkala
**Status:** `processing`
**Event di Timeline:**
- 📊 **Progress: 25%** (milestone pertama)
- 📊 **Progress: 50%** (milestone kedua)
- 📊 **Progress: 75%** (milestone ketiga)
- 📊 **Progress: 100%** (milestone terakhir)

**Detail:**
- Seller update progress melalui form/API
- Progress disimpan di `OrderProgressUpdate` table
- Timeline hanya menampilkan **milestone progress** (25%, 50%, 75%, 100%)
- Setiap milestone trigger notification ke buyer
- Progress update biasa (non-milestone) tidak muncul di timeline, hanya di notification

**Notification yang dikirim:**
- 25%: "🎯 Progress pesanan #XXX mencapai 25%! Pekerjaan sedang berjalan."
- 50%: "⏳ Progress pesanan #XXX sudah setengah jalan (50%)! Seller sedang bekerja keras..."
- 75%: "🚀 Progress pesanan #XXX hampir selesai (75%)! Tinggal sedikit lagi."
- 100%: "✅ Progress pesanan #XXX sudah 100%! Order akan segera diselesaikan."

---

### 4. **Seller Upload Deliverable (Progress 100%)**
**Kapan:** Seller upload hasil pekerjaan setelah progress mencapai 100%
**Status:** `processing` → `waiting_confirmation`
**Event di Timeline:**
- 📦 **File Hasil Tersedia** (Deliverable uploaded)
- ✅ **Pesanan Dikirimkan** (Status: waiting_confirmation)

**Detail:**
- Seller upload deliverable file (PDF, DOC, ZIP, dll)
- File disimpan di `orders/deliverables/`
- **Status otomatis berubah ke `waiting_confirmation`** (via `markAsWaitingConfirmation()`)
- Set `delivered_at` timestamp
- Set `auto_complete_at` = now() + 24 jam (buyer punya 24 jam untuk konfirmasi)
- Notification ke buyer: "📦 Hasil pekerjaan untuk pesanan #XXX telah diupload! Silakan review dan konfirmasi dalam 24 jam."

**Validasi:**
- Progress harus 100% (tidak wajib, tapi disarankan)
- Deliverable file wajib diupload
- Status harus `processing` atau `needs_revision`

---

### 5. **Buyer Konfirmasi Pesanan**
**Kapan:** Buyer review deliverable dan klik "Konfirmasi Produk"
**Status:** `waiting_confirmation` → `completed`
**Event di Timeline:**
- ✅ **Pesanan Selesai** (Buyer confirmed)

**Detail:**
- Buyer klik "Konfirmasi Produk" → Status `completed`
- Set `completed_at` timestamp
- **Early release rekber** (dana langsung ke seller)
- Notification ke seller: "✅ Pesanan #XXX telah dikonfirmasi buyer! Dana rekber telah dilepas."

**Auto-complete:**
- Jika buyer tidak konfirmasi dalam 24 jam → Order otomatis `completed` (via cron job)
- Rekber tetap di-release otomatis

---

### 6. **Rating (Optional)**
**Kapan:** Setelah order `completed`
**Status:** `completed`
**Event di Timeline:**
- ⭐ **Belum Dinilai** (jika belum ada rating)
- ⭐ **Sudah Dinilai** (jika sudah ada rating, menampilkan rating/5 bintang)

---

## 🎨 Timeline Jasa yang Benar (Visual)

```
┌─────────────────────┐
│ Pesanan Dibuat      │ ← Order created
│ 26 Nov 2025, 10:00  │
└─────────────────────┘
        ↓
┌─────────────────────┐
│ File Tugas Diterima │ ← Buyer upload task_file (optional)
│ 26 Nov 2025, 10:00  │
└─────────────────────┘
        ↓
┌─────────────────────┐
│ Pesanan Dibayarkan  │ ← Payment verified
│ Rp 500.000          │
│ 26 Nov 2025, 11:00  │
└─────────────────────┘
        ↓
┌─────────────────────┐
│ Progress: 25%       │ ← Seller update progress (milestone)
│ 26 Nov 2025, 14:00  │
└─────────────────────┘
        ↓
┌─────────────────────┐
│ Progress: 50%       │ ← Seller update progress (milestone)
│ 27 Nov 2025, 10:00  │
└─────────────────────┘
        ↓
┌─────────────────────┐
│ Progress: 75%       │ ← Seller update progress (milestone)
│ 27 Nov 2025, 16:00  │
└─────────────────────┘
        ↓
┌─────────────────────┐
│ Progress: 100%      │ ← Seller update progress (milestone)
│ 28 Nov 2025, 10:00  │
└─────────────────────┘
        ↓
┌─────────────────────┐
│ File Hasil Tersedia │ ← Seller upload deliverable
│ 28 Nov 2025, 11:00  │
└─────────────────────┘
        ↓
┌─────────────────────┐
│ Pesanan Dikirimkan │ ← Status: waiting_confirmation
│ 28 Nov 2025, 11:00  │
└─────────────────────┘
        ↓
┌─────────────────────┐
│ Pesanan Selesai     │ ← Buyer konfirmasi (atau auto-complete 24 jam)
│ 28 Nov 2025, 12:00  │
└─────────────────────┘
        ↓
┌─────────────────────┐
│ Belum Dinilai       │ ← Rating (optional)
│ 28 Nov 2025, 12:00  │
└─────────────────────┘
```

---

## 🔄 Status Transitions untuk Jasa

```
pending → paid → processing → waiting_confirmation → completed
```

**Detail Transitions:**
1. `pending` → `paid`: Payment verified
2. `paid` → `processing`: Otomatis setelah rekber dibuat (sama seperti produk)
3. `processing` → `waiting_confirmation`: Seller upload deliverable (progress 100%)
4. `waiting_confirmation` → `completed`: Buyer konfirmasi (atau auto-complete 24 jam)

**Special Cases:**
- `processing` → `needs_revision`: Buyer request revision
- `needs_revision` → `processing`: Seller upload revision
- `waiting_confirmation` → `needs_revision`: Buyer request revision setelah deliverable
- `waiting_confirmation` → `disputed`: Buyer buka dispute

---

## 📊 Event yang Muncul di Timeline

### ✅ **Selalu Muncul:**
1. Pesanan Dibuat
2. Pesanan Dibayarkan
3. Pesanan Dikirimkan (saat `waiting_confirmation`)
4. Pesanan Selesai (saat `completed`)
5. Belum Dinilai / Sudah Dinilai

### 📎 **Kondisional (jika ada):**
- File Tugas Diterima (jika buyer upload task_file)
- Progress: 25% / 50% / 75% / 100% (jika seller update progress milestone)
- File Hasil Tersedia (jika seller upload deliverable)
- Revisi Diminta (jika buyer request revision)

---

## 🎯 Rekomendasi Implementasi

### 1. **TimelineService.php - Update untuk Jasa**

**Saat ini timeline jasa sudah benar**, tapi perlu pastikan:
- ✅ Progress milestones (25%, 50%, 75%, 100%) muncul di timeline
- ✅ "File Tugas Diterima" muncul jika ada `task_file_path`
- ✅ "File Hasil Tersedia" muncul jika ada `deliverable_path`
- ✅ "Pesanan Dikirimkan" muncul saat status `waiting_confirmation`
- ✅ Timeline diurutkan berdasarkan waktu (oldest first)

### 2. **OrderService.php - Pastikan Flow Konsisten**

**Saat ini flow jasa:**
- ✅ `paid` → `processing` (otomatis, sama seperti produk)
- ✅ `processing` → `waiting_confirmation` (saat deliverable diupload)
- ✅ `waiting_confirmation` → `completed` (buyer konfirmasi)

**Tidak perlu step `accepted`** - langsung ke `processing` seperti produk digital.

### 3. **Timeline Display - Urutan yang Benar**

**Urutan timeline jasa:**
1. Pesanan Dibuat
2. File Tugas Diterima (jika ada)
3. Pesanan Dibayarkan
4. Progress: 25% (jika ada milestone)
5. Progress: 50% (jika ada milestone)
6. Progress: 75% (jika ada milestone)
7. Progress: 100% (jika ada milestone)
8. File Hasil Tersedia (jika ada deliverable)
9. Pesanan Dikirimkan (status: waiting_confirmation)
10. Pesanan Selesai (status: completed)
11. Belum Dinilai / Sudah Dinilai

---

## ✅ Checklist Implementasi

- [x] Timeline menampilkan "File Tugas Diterima" jika ada task_file
- [x] Timeline menampilkan progress milestones (25%, 50%, 75%, 100%)
- [x] Timeline menampilkan "File Hasil Tersedia" jika ada deliverable
- [x] Timeline menampilkan "Pesanan Dikirimkan" saat waiting_confirmation
- [x] Status otomatis ke waiting_confirmation saat deliverable diupload
- [x] Status otomatis ke completed saat buyer konfirmasi (atau 24 jam)
- [ ] Pastikan timeline diurutkan berdasarkan waktu (oldest first)
- [ ] Pastikan tidak ada duplikasi event di timeline

---

## 🚨 Catatan Penting

1. **Progress Update:**
   - Hanya milestone (25%, 50%, 75%, 100%) yang muncul di timeline
   - Progress update biasa (misal 30%, 45%) hanya muncul di notification, tidak di timeline

2. **Deliverable Upload:**
   - Deliverable bisa diupload kapan saja saat status `processing`
   - Tapi status baru berubah ke `waiting_confirmation` setelah deliverable diupload
   - Progress 100% tidak wajib, tapi disarankan

3. **Auto-complete:**
   - Jika buyer tidak konfirmasi dalam 24 jam → Order otomatis `completed`
   - Rekber tetap di-release otomatis

4. **Revisi:**
   - Buyer bisa request revisi saat `waiting_confirmation`
   - Status berubah ke `needs_revision`
   - Seller upload revision → Status kembali ke `processing` atau `waiting_confirmation`

