# Anjungan Pasien Mandiri
Anjungan Pasien Mandiri RS Indriati Boyolali (Adaptasi SIMRS Khanza)

Disusun oleh: Tim Sistem Informasi Anjungan Pasien Mandiri  
Tanggal penyusunan: 30 Oktober 2025

---

## Ringkasan singkat
Aplikasi desktop ini digunakan sebagai anjungan pendaftaran mandiri dan loket pendaftaran di Rumah Sakit Indriati Boyolali. Fungsi utama mencakup pengambilan nomor antrian, pendaftaran poli, check-in booking, pendaftaran pasien BPJS (pembuatan SEP), kontrol, dan aktivasi layanan Satu Sehat. Sistem terintegrasi dengan layanan eksternal seperti BPJS VClaim / Mobile JKN dan aplikasi sidik jari BPJS.

---

## Daftar Isi
1. [Persiapan dan Deployment](#persiapan-dan-deployment)  
2. [Fungsi Utama & Alur Penggunaan (Rincian Menu Halaman Utama)](#fungsi-utama--alur-penggunaan-rincian-menu-halaman-utama)  
3. [Aturan Bisnis dan Kondisi Operasional (bahasa non-teknis)](#aturan-bisnis-dan-kondisi-operasional-bahasa-non-teknis)  
4. [Cek Kunjungan Pertama SEP & Cek SKDP/SEP Kontrol (rincian lengkap)](#cek-kunjungan-pertama-sep--cek-skdpsep-kontrol-rincian-lengkap)  
5. [Autoprint / Silent Print (Cetak Otomatis)](#autoprint--silent-print-cetak-otomatis)  
6. [Pesan Kesalahan dan Penanganan](#pesan-kesalahan-dan-penanganan)  
7. [Offline & Rekonsiliasi BPJS](#offline--rekonsiliasi-bpjs)  
8. [Kepatuhan, Privasi, dan Keamanan](#kepatuhan-privasi-dan-keamanan)  
9. [Panduan Demo & Checklist Operator](#panduan-demo--checklist-operator)  
10. [Kontak Dukungan Teknis (Diskusi Repository)](#kontak-dukungan-teknis-diskusi-repository)  
11. [Penutup](#penutup)

---

## Persiapan dan Deployment

1. Tempatkan aplikasi pada folder utama SIMRS Khanza.  
2. Tambahkan konfigurasi yang diperlukan pada `database.xml`. Contoh parameter konfigurasi:
```xml
<entry key="URLFINGERPRINTBPJS">https://fp.bpjs-kesehatan.go.id/finger-rest/</entry>
<entry key="URLAPLIKASIFINGERPRINTBPJS">C:\Program Files (x86)\Aplikasi Sidik Jari BPJS Kesehatan\After.exe</entry>
<entry key="USERFINGERPRINTBPJS"></entry>
<entry key="PASSWORDFINGERPRINTBPJS"></entry>
<entry key="URLAPLIKASIFRISTA">C:\frista\frista.exe</entry>
```
3. Jalankan skrip SQL pada folder `tambahan table/` untuk menambahkan tabel atau kolom yang dibutuhkan. Ikuti urutan eksekusi jika ada file urutan.  
4. Salin library tambahan (`.jar`) dari folder `tambahan lib/` ke lokasi classpath yang sesuai bila diperlukan.  
5. Pastikan aplikasi FP BPJS v2.0 terpasang dan dapat diakses (aplikasi ini meminta login saat akan menggunakan fitur fingerprint).

---

## Fungsi Utama & Alur Penggunaan (Rincian Menu Halaman Utama)

Berikut penjelasan terperinci untuk setiap menu pada halaman utama dan apa yang terjadi ketika pengguna memilih tiap opsi.

1. ANTRIAN PASIEN
- Tujuan: Mengeluarkan nomor antrian untuk berbagai layanan.
- Pilihan antrian:
  - ADMISI (Appointment): Untuk pasien dengan booking/janji.
  - ADMISI (Walk In): Untuk pasien datang langsung tanpa booking.
  - ADMISI (Rawat Inap & IGD): Untuk pendaftaran rawat inap atau kedatangan IGD.
  - CUSTOMER SERVICE: Untuk pertanyaan umum atau bantuan administrasi.
  - FARMASI: Untuk mengambil obat / layanan farmasi.
- Alur: Pilih jenis antrian → sistem menerbitkan nomor antrian → cetak tiket antrian. Operator/pasien menyimpan nomor untuk dilayani pada loket tujuan.
- Catatan: Untuk booking yang sudah ada, gunakan menu Check-in untuk verifikasi booking terlebih dahulu.

2. PENDAFTARAN POLIKLINIK
- Tujuan: Mendaftarkan pasien baru atau pasien lama untuk pelayanan poli.
- Alur: Masukkan No RM atau NIK → sistem menampilkan data (jika ada) → lengkapi data → pilih poli & dokter → konfirmasi → cetak bukti registrasi / nomor antrian.
- Catatan: Jika pasien BPJS dan memerlukan SEP, lanjut ke menu PASIEN JKN.

3. CHECK-IN (UMUM)
- Tujuan: Konfirmasi kedatangan untuk pasien yang sudah booking.
- Alur: Masukkan No RM → verifikasi booking → jika valid, konfirmasi kedatangan → cetak bukti check-in & nomor antrian.
- Catatan: Jika booking tidak ditemukan, operator akan diinformasikan.

4. CHECK-IN (MobileJKN)
- Tujuan: Check-in untuk booking via MobileJKN.
- Alur: Masukkan No Kartu/No RM → sistem periksa MobileJKN → jika valid, proses check-in → cetak bukti.
- Catatan: Perlu koneksi internet. Jika MobileJKN tidak dapat diakses, simpan sementara.

5. PASIEN JKN
- Tujuan: Menangani pendaftaran pasien BPJS dan pembuatan SEP.
- Alur: Masukkan No JKN/No RM/NIK → tampilkan data VClaim → verifikasi → pilih poli & DPJP → isi data SEP → verifikasi biometrik bila perlu → proses pembuatan SEP → cetak bukti SEP & registrasi.
- Catatan: Jika pembuatan SEP gagal karena hak kelas, duplikasi, atau batas harian, sistem akan menampilkan pesan dan opsi penanganan.

6. SEP KONTROL
- Tujuan: Membuat SEP untuk kunjungan kontrol.
- Alur: Masukkan No Surat Kontrol atau No RM → sistem cek surat kontrol → jika valid & tanggal sesuai → buat SEP kontrol → cetak bukti.
- Catatan: Jika tanggal kontrol belum sesuai (di masa depan), proses dihentikan dan operator diberi tahu.

7. KONTROL BEDA POLI
- Tujuan: Kontrol ke poli berbeda dari rujukan sebelumnya.
- Alur: Masukkan No JKN/No RM → tampil riwayat SEP & rujukan → pilih poli baru → sistem menilai apakah rujukan masih berlaku → jika perlu, arahkan pembuatan rujukan FKTL atau opsi post‑RANAP.
- Catatan: Jika rujukan awal tidak valid untuk poli tujuan, ikuti proses rujukan ke FKTL atau post‑RANAP sesuai kebijakan.

8. AKTIVASI SATUSEHAT MOBILE
- Tujuan: Mengaktivasi akun pasien pada layanan Satu Sehat Mobile.
- Alur: Arahkan pasien/operator ke proses aktivasi → setelah aktif, konfirmasi di layar → catat ID Satu Sehat pada rekam medis.
- Catatan: Pastikan pasien menyetujui penggunaan data Satu Sehat.

---

## Aturan Bisnis dan Kondisi Operasional (bahasa non-teknis)

Berikut aturan utama yang menentukan apakah pendaftaran dilanjutkan atau ditunda/batal, dalam bahasa operasional (tanpa istilah pemrograman):

- Dokter sedang cuti → pendaftaran untuk dokter tersebut tidak dapat dilanjutkan. Solusi: pilih dokter lain atau jadwalkan ulang.  
- Duplikasi pendaftaran → jika pasien sudah terdaftar pada poli/dokter & tanggal sama, pendaftaran baru diblokir. Solusi: gunakan Check‑in atau batalkan pendaftaran ganda.  
- Biometrik wajib (pasien ≥17 tahun, layanan non‑IGD) → jika belum terverifikasi, pendaftaran tidak dilanjutkan. Solusi: lakukan verifikasi sidik jari atau prosedur alternatif.  
- Persetujuan Satu Sehat tidak ada → aktivasi Satu Sehat tidak dapat dilakukan. Solusi: minta persetujuan pasien.  
- Hak kelas peserta tidak sesuai → pendaftaran harus menyesuaikan kelas atau melakukan proses administrasi upgrade.  
- Batas SEP harian tercapai → tidak boleh membuat SEP baru untuk jenis layanan yang sama (kecuali darurat).  
- Nomor rujukan tidak sesuai/tidak ditemukan → pendaftaran rujukan tidak lanjut; solusi: periksa FKTP, minta rujukan ke FKTL, atau alur post‑RANAP.  
- Laka Lantas → keterangan kecelakaan wajib diisi sebelum pendaftaran lanjut.  
- Data wajib tidak lengkap → pendaftaran ditunda sampai data lengkap.

---

## Cek Kunjungan Pertama SEP & Cek SKDP/SEP Kontrol (rincian lengkap)

Bagian ini menjelaskan secara lengkap alur pemeriksaan dan penanganan ketika operator memilih menu terkait kunjungan pertama SEP atau SEP kontrol (SKDP).

A. Cek Kunjungan Pertama SEP (penjelasan operasional)
1. Tujuan: Menentukan apakah pasien perlu menjalani pendaftaran SEP pertama atau diarahkan ke alur lain (mis. MobileJKN).  
2. Proses pemeriksaan:
   - Sistem pertama-tama memeriksa apakah pasien sudah terdaftar pada MobileJKN. Jika ya:
     - Sistem menampilkan pesan kepada operator: "Pasien telah menggunakan Mobile JKN. Silakan cek-in menggunakan menu MobileJKN".
     - Tindakan: Operator harus menggunakan menu MobileJKN untuk proses selanjutnya (tidak lanjut di menu ini).
   - Jika pasien tidak terdaftar MobileJKN, sistem mencari data pasien secara berurutan:
     1) Cari berdasarkan nomor peserta (no_peserta) — prioritas utama.  
     2) Jika tidak ditemukan, cari berdasarkan nomor rekam medis (no_rkm_medis).  
     3) Jika masih tidak ditemukan, cari berdasarkan nomor KTP.  
   - Jika data pasien ditemukan pada salah satu kriteria di atas:
     - Sistem membuka form pendaftaran khusus untuk SEP pertama.  
     - Form pendaftaran akan memanggil/menampilkan data fingerprint bila tersedia dan meminta verifikasi biometrik bila aturan usia/layanan mengharuskan.  
3. Hasil akhir:
   - Jika semua validasi terpenuhi → lanjutkan pembuatan SEP pertama, simpan data ke database lokal, dan cetak bukti registrasi.  
   - Jika ada masalah data (tidak ditemukan / data tidak lengkap / biometrik belum dilakukan) → sistem menampilkan pesan dan menahan proses sampai diselesaikan.

B. Cek SKDP / SEP Kontrol (penjelasan operasional)
1. Tujuan: Memproses kunjungan kontrol berdasarkan surat kontrol (SKDP) yang telah diterbitkan sebelumnya.  
2. Proses pemeriksaan:
   - Sistem memeriksa tabel surat kontrol (`bridging_surat_kontrol_bpjs`) untuk menemukan entri berdasarkan nomor surat yang dimasukkan.  
   - Jika surat kontrol ditemukan:
     - Sistem membaca tanggal rencana kontrol dari data surat kontrol.  
     - Jika tanggal rencana kontrol masih di masa depan (belum tiba waktunya):
       - Sistem menampilkan pesan: "Jadwal kontrol tidak boleh maju".
       - Tindakan: Operator tidak dapat melanjutkan pendaftaran untuk kunjungan kontrol ini; minta pasien kembali pada tanggal yang tercantum.  
     - Jika tanggal rencana telah tiba (atau tanggal memungkinkan):
       - Sistem memeriksa apakah pasien sudah menggunakan MobileJKN; jika pasien menggunakan MobileJKN, arahkan ke menu MobileJKN.  
       - Jika tidak ada konflik, aplikasi akan membuka form registrasi kontrol (fungsi tampilan pendaftaran kontrol) sehingga operator dapat melanjutkan proses pembuatan SEP kontrol dan mencetak bukti.  
   - Jika surat kontrol tidak ditemukan:
     - Sistem menampilkan pesan: "Data surat kontrol tidak ditemukan!".  
     - Tindakan: Operator dapat memeriksa nomor surat, hubungi admin/pihak terkait, atau bantu pasien dengan alur pendaftaran lain (mis. pembuatan SEP baru bila memenuhi syarat).  
3. Hasil akhir:
   - Kontrol valid → lanjutkan pembuatan SEP kontrol dan cetak bukti.  
   - Kontrol belum sesuai waktu atau data tidak ditemukan → operasi dihentikan sampai masalah diselesaikan.

Catatan tambahan:
- Semua pesan yang muncul bersifat informatif dan harus disampaikan ke pasien dengan bahasa sederhana. Petugas dianjurkan mencatat alasan penundaan jika pendaftaran ditunda.

---

## Autoprint / Silent Print (Cetak Otomatis)

A. Fungsi Autoprint / Silent Print
- Sistem mendukung fitur cetak otomatis (autoprint / silent print) pada beberapa alur:
  - Cetak tiket antrian.
  - Cetak bukti registrasi.
  - Cetak bukti SEP / surat rujukan.
- Autoprint berarti setelah proses selesai (mis. SEP dibuat atau pendaftaran terkonfirmasi), sistem secara otomatis mengirim dokumen ke printer yang telah dikonfigurasi tanpa menampilkan dialog print ke operator.

B. Konfigurasi dan perilaku
- Pengaturan printer (nama printer, mode autoprint on/off, dan tray) disimpan pada konfigurasi aplikasi atau file konfigurasi yang hanya boleh diakses oleh admin.
- Jika autoprint aktif:
  - Sistem akan langsung mencetak dokumen yang relevan pada printer default yang dikonfigurasi.
  - Jika printer tidak tersedia, sistem akan menampilkan pesan: "Printer tidak tersedia. Silakan periksa koneksi printer atau cetak secara manual."
- Jika autoprint tidak aktif:
  - Operator akan melihat dialog print untuk memilih printer atau opsi cetak ulang.

C. Implementasi teknis singkat (untuk tim IT/developer)
- Dalam implementasi Java, mekanisme "silent print" dapat diarahkan ke printer yang dipilih (selected printer) atau menggunakan printer default dari sistem operasi.
- Pada kode aplikasi ini, terdapat akomodasi untuk kedua pendekatan tersebut:
  - Jika konfigurasi menyertakan nama printer tertentu, sistem mengirim cetakan langsung ke printer yang dipilih tanpa menampilkan dialog.  
  - Jika tidak ada nama printer yang dikonfigurasi, sistem akan menggunakan printer default OS untuk melakukan cetak otomatis.
- Pastikan nama printer yang dikonfigurasi cocok dengan nama printer yang terdaftar di sistem operasi atau jaringan, agar cetak otomatis berjalan tanpa intervensi.

D. Catatan implementasi dan operasional kecil
- Sistem menyediakan opsi untuk reprint/cetak ulang pada form hasil pendaftaran sehingga operator dapat mencetak ulang jika perlu.
- Periksa kompatibilitas driver printer (thermal/ESC-POS vs Windows driver) saat men-deploy kiosk/printer.

---

## Pesan Kesalahan dan Penanganan

Beberapa contoh pesan pengguna dan langkah lanjutan yang direkomendasikan:

- "Dokter sedang cuti untuk poli ini. Pilih dokter lain." → Ganti dokter atau jadwalkan ulang.  
- "Pasien sudah terdaftar pada tanggal dan poli ini." → Periksa antrian, gunakan Check‑in.  
- "Verifikasi sidik jari belum dilakukan. Silakan lakukan verifikasi." → Jalankan aplikasi fingerprint dan verifikasi.  
- "Batas pembuatan SEP harian telah tercapai." → Verifikasi SEP sebelumnya; bila darurat, gunakan jalur darurat.  
- "Nomor rujukan tidak valid." → Hubungi FKTP asal atau buat rujukan baru ke FKTL sesuai prosedur.  
- "Keterangan kecelakaan belum diisi." → Isi keterangan lengkap; lanjutkan pendaftaran.  
- "Koneksi ke layanan BPJS gagal." → Simpan pendaftaran lokal, beri tahu pasien, lakukan rekonsiliasi saat layanan pulih.  
- "Jadwal kontrol tidak boleh maju." → Informasikan pasien mengenai tanggal rencana kontrol dan minta untuk kembali pada tanggal tersebut.

Pesan tersebut sebaiknya ditampilkan dengan bahasa sederhana kepada pasien dan instruksi singkat untuk operator.

---

## Offline & Rekonsiliasi BPJS

Jika layanan BPJS atau MobileJKN tidak dapat diakses:
1. Tampilkan informasi singkat kepada operator/pasien: "Layanan BPJS saat ini tidak tersedia. Pendaftaran akan disimpan sementara untuk direkonsiliasi."  
2. Simpan data pendaftaran lokal dengan status menunggu rekonsiliasi.  
3. Cetak bukti pendaftaran lokal dengan keterangan bahwa SEP belum dibuat.  
4. Jalankan proses rekonsiliasi setelah layanan pulih: kirim ulang pendaftaran yang tertunda, catat hasil dan perbarui status.  
5. Catat log rekonsiliasi: waktu, operator, alasan kegagalan, dan hasil setelah pengiriman ulang.

---

## Kepatuhan, Privasi, dan Keamanan

- Hanya operator terotorisasi yang boleh mengakses modul SEP dan fitur biometrik.  
- Semua transmisi data ke layanan eksternal harus melalui jaringan aman (VPN atau jaringan terisolasi RS).  
- Simpan kredensial dan file konfigurasi pada lokasi yang aman dan berizin.  
- Jaga kerahasiaan data pasien (PHI); ikuti kebijakan privasi rumah sakit dan peraturan yang berlaku.

---

## Panduan Demo & Checklist Operator

Sebelum presentasi / pelatihan:
- Pastikan koneksi database dan akun testing BPJS (jika tersedia).  
- Perangkat fingerprint dan aplikasi After.exe siap dan terhubung.  
- Printer terpasang dan dapat mencetak tiket.  
- Siapkan skenario data: Walk-in, BPJS reguler, SEP kontrol, rujukan FKTP gagal → FKTL, post-RANAP, kasus Laka Lantas.

Demo singkat:
1. Tampilkan Halaman Utama → pilih ANTRIAN PASIEN → ambil nomor & cetak tiket.  
2. Pendaftaran Walk-in: cari pasien / buat baru → pilih poli → cetak bukti.  
3. Pendaftaran BPJS: masukkan nomor kartu test → verifikasi → buat SEP → cetak bukti (perhatikan autoprint).  
4. Simulasi rujukan gagal: gunakan nomor rujukan tidak valid → tunjukkan opsi pindah ke FKTL atau post-RANAP.  
5. Simulasi layanan BPJS down: tampilkan penyimpanan lokal dan proses rekonsiliasi.

---

## Kontak Dukungan Teknis (Diskusi Repository)

Untuk pertanyaan operasional, pelaporan bug, permintaan fitur, atau berdiskusi mengenai alur kerja:
- Silakan buat topik di tab "Discussions" pada repository ini: https://github.com/RS-INDRIATI/anjunganmandiriSEP/discussions  
  - Gunakan kategori yang sesuai (General, Q&A, Bug reports, Features).  
  - Cantumkan langkah reproduksi, screenshot jika perlu, dan log kesalahan bila tersedia.  
- Untuk kebutuhan darurat atau informasi internal rumah sakit, hubungi Admin Sistem Informasi RS Indriati (cantumkan kontak internal di sini).

Kami menganjurkan penggunaan Discussions untuk:
- Mengumpulkan masukan pengguna/operator.  
- Mendokumentasikan masalah umum dan solusi.  
- Memudahkan tim pengembang merespons dan menindaklanjuti.

---

## Penutup

Dokumentasi ini dirancang agar operator dan staf rumah sakit memahami alur kerja setiap menu, kondisi yang menyebabkan pendaftaran ditahan, dan langkah-langkah penanganan yang dapat dilakukan. Dokumen juga memuat panduan saat integrasi eksternal (BPJS/MobileJKN) bermasalah dan alur rujukan lanjutan seperti perpindahan ke FKTL atau proses post-RANAP.
