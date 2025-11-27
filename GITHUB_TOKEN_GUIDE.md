# GitHub Token untuk Composer - Panduan Lengkap

## 🔑 Apa itu GitHub Token?

GitHub Token (Personal Access Token) adalah token autentikasi yang digunakan Composer untuk mengakses GitHub API saat download packages dari GitHub.

## ❓ Kapan Token Diperlukan?

Token GitHub **TIDAK SELALU DIPERLUKAN**. Composer meminta token jika:

1. **Rate Limit Terlampaui**: GitHub membatasi 60 request/jam untuk unauthenticated requests
2. **Private Repositories**: Jika project menggunakan private GitHub repositories
3. **Large Downloads**: Untuk download package besar dari GitHub

## ✅ Apakah Wajib?

**TIDAK WAJIB** untuk project ini karena:
- ✅ Package yang digunakan sudah tersedia di Packagist (public repository)
- ✅ AWS SDK dan Flysystem tersedia di Packagist, bukan langsung dari GitHub
- ✅ Composer akan otomatis fallback ke Packagist jika GitHub rate limit

## 🚀 Cara Skip Token (Recommended)

Jika muncul prompt token, Anda bisa:

### Opsi 1: Tekan Enter (Skip)
```
Token (hidden): [Tekan Enter saja, kosongkan]
```

Composer akan tetap bekerja, hanya mungkin lebih lambat jika rate limit tercapai.

### Opsi 2: Set di Environment Variable
```bash
# Windows PowerShell
$env:COMPOSER_AUTH='{"github-oauth":{"github.com":"YOUR_TOKEN"}}'

# Atau set permanent di system environment variables
```

### Opsi 3: Set di composer.json (Tidak Recommended)
```json
{
    "config": {
        "github-oauth": {
            "github.com": "YOUR_TOKEN"
        }
    }
}
```

## 🔐 Cara Mendapatkan Token (Jika Diperlukan)

Jika Anda tetap ingin membuat token (opsional):

### Step 1: Buat Personal Access Token
1. Login ke GitHub: https://github.com
2. Klik **Profile** → **Settings**
3. Scroll ke bawah → **Developer settings**
4. Klik **Personal access tokens** → **Tokens (classic)**
5. Klik **Generate new token** → **Generate new token (classic)**
6. Beri nama: `Composer Token`
7. Pilih scope: **Hanya centang `repo`** (untuk private repos) atau **tidak perlu centang apa-apa** (untuk public repos)
8. Klik **Generate token**
9. **COPY TOKEN** (hanya muncul sekali!)

### Step 2: Gunakan Token
Saat Composer prompt token, paste token yang sudah dicopy.

## ⚠️ Keamanan

**PENTING:**
- ❌ **JANGAN commit token ke Git**
- ❌ **JANGAN share token di public**
- ✅ **Gunakan environment variable** untuk production
- ✅ **Token hanya untuk development** jika benar-benar diperlukan

## 💡 Rekomendasi untuk Project Ini

**Untuk project Ebrystoree, Anda TIDAK PERLU token** karena:
1. ✅ Semua package tersedia di Packagist
2. ✅ Tidak ada private repositories
3. ✅ Composer akan otomatis handle rate limit

**Langkah yang Disarankan:**
1. **Skip token** (tekan Enter saat prompt)
2. **Tunggu beberapa saat** jika rate limit tercapai
3. **Atau install package secara manual** jika perlu:
   ```bash
   composer require aws/aws-sdk-php --no-interaction
   ```

## 🔧 Troubleshooting

### Jika Composer Lambat
- **Solusi 1**: Tunggu beberapa menit (rate limit reset setiap jam)
- **Solusi 2**: Buat token GitHub (opsional)
- **Solusi 3**: Gunakan Packagist mirror

### Jika Install Gagal
- **Solusi 1**: Clear composer cache: `composer clear-cache`
- **Solusi 2**: Install ulang: `composer install --no-cache`
- **Solusi 3**: Install package spesifik: `composer require aws/aws-sdk-php --no-interaction`

---

## 📝 Summary

**Untuk project ini:**
- ✅ **Token TIDAK WAJIB** - Skip saja dengan tekan Enter
- ✅ **Composer akan tetap bekerja** tanpa token
- ✅ **Package akan terdownload** dari Packagist
- ⚠️ **Token hanya diperlukan** jika ada private repos atau rate limit sering tercapai

