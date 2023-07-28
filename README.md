# Sistem Pengukuran Efisiensi prodi Menggunakan Data Envelopment Analysis (DEA) dengan Pemodelan BCC (Banker Carnes Cooper) Input-Oriented

> Note : Perhitungan nilai rekomendasi untuk DMU (Decision Making Unit) yang belum efisien (nilai efisiensi < 1) menggunakan DEA model CCR (Charnes Cooper Rhodes) Input-Oriented

## Daftar User
| No | Username | Password | Level | Cabang prodi |
| :-: | :-: | :-: | :-: | :-: |
| 1 | superadmin    | superadmin | Superadmin | |
| 2 | manajer_pusat | pusat | Manajer Pusat | |
| 3 | murtiono | murtiono | Admin Cabang | Banyumanik |
| 4 | tiffany | tiffany | Admin Cabang | Kalipancur |
| 5 | wahyu | wahyu | Admin Cabang | Kedungmundu |
| 6 | manajer_banyumanik | banyumanik | Manajer Cabang | Banyumanik |
| 7 | manajer_kalipancur | kalipancur | Manajer Cabang | Kalipancur |
| 8 | manajer_kedungmundu | kedungmundu | Manajer Cabang | Kedungmundu |

## System Requirement Specification (SRS)
1. Autentikasi
2. CRUD Cabang
3. CRUD Pengguna
4. CRUD Variabel
5. CRUD DMU
6. Perhitungan Efisiensi dan Rekomendasi menggunakan DEA

## Update (16/08/2017)
- [x] Notifikasi pada form (Sukses / Gagal)
- [x] Jumlah Variabel dan DMU dinamis
- [x] Tabel fisik dan nilai DMU sudah responsif
- [x] Validasi form
- [x] Alert saat ingin menghapus data
- [x] Penambahan Aktor Manajer Pusat
- [x] Dashboard
- [x] Testing (100%)
- [x] Setiap akan memulai simplex dilakukan penghapusan isi tabel perhitungan_efisiensi
- [x] Urutan data pada tabel dashboard sudah singkron
- [x] Jika tabel masih kosong, muncul notifikasi
- [x] Fixing bug simplex
- [x] Modifikasi warna dashboard
- [x] Fixing bug mengelola cabang prodi
- [x] Fixing bug CCR
- [x] Penambahan tabel pengguna_khusus (superadmin, manajer pusat)
