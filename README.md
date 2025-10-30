<div align="center">
  <img height="150" src="https://camo.githubusercontent.com/62da68eb62b1e5f175f7d1f0191dd89a653d7908feb22d37d4a0ab07365d6791/68747470733a2f2f6d656469612e67697068792e636f6d2f6d656469612f4d3967624264396e6244724f5475314d71782f67697068792e676966"  />
</div>

###

<div align="center">
  <a href="https://id.linkedin.com/in/abdul-rokhim-661523110" target="_blank">
    <img src="https://img.shields.io/static/v1?message=LinkedIn&logo=linkedin&label=&color=0077B5&logoColor=white&labelColor=&style=for-the-badge" height="25" alt="linkedin logo"  />
  </a>
</div>

###

<div align="center">
  <img src="https://visitor-badge.laobi.icu/badge?page_id=abdulrokhimrepo.abdulrokhimrepo&"  />
</div>

###

<h1 align="center">hey there 👋</h1>

###


# AnjunganmandiriSEP

Anjungan Pasien Mandiri RS Indriati Boyolali (Adaptasi SIMRS Khanza)


## Authors

- [@abdulrokhimrepo](https://www.github.com/abdulrokhimrepo)


## Tech Stack

Java


## Deployment

Silahkan lakukan deploy, dan tempatkan pada folder utama SIMRS Khanza.
Tambahkan konfigurasi berikut pada database.xml
Tambahkan table pada database dengan file SQL di folder tambahan Table.
Tambahkan lib yg ada pada folder tambahan lib
Anjungan ini menggunakan versi aplikasi FP BPJS v 2.0(harus login setiap akan rekan FP)

```bash
  <entry key="URLFINGERPRINTBPJS">https://fp.bpjs-kesehatan.go.id/finger-rest/</entry>
  <entry key="URLAPLIKASIFINGERPRINTBPJS">C:\Program Files (x86)\Aplikasi Sidik Jari BPJS Kesehatan\After.exe</entry>
  <entry key="USERFINGERPRINTBPJS"></entry>
  <entry key="PASSWORDFINGERPRINTBPJS"></entry>
  <entry key="URLAPLIKASIFRISTA">C:\frista\frista.exe</entry>
```



<h3 align="left">🛠 Language and tools</h3>

###

<div align="left">
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg" height="40" alt="java logo"  />
</div>

###

<h3 align="left">🔥   My Stats :</h3>

###

<div align="center">
  <img src="https://github-readme-stats.vercel.app/api/top-langs?username=abdulrokhimrepo&locale=en&hide_title=false&layout=compact&card_width=320&langs_count=5&theme=dracula&hide_border=false&order=2" height="150" alt="languages graph"  />
</div>

<<<<<<< HEAD
###
=======
###
>>>>>>> 0cdbcd62424f96a28edbf16970022011629d84c1


# PANDUAN PENGGUNAAN ANJUNGAN PASIEN MANDIRI
## RUMAH SAKIT INDRIATI BOYOLALI

---

**Disusun oleh:** Sistem Informasi Anjungan Pasien Mandiri  
**Tanggal:** 30 Oktober 2025

---

## DAFTAR ISI

I. [Pendahuluan](#pendahuluan)  
II. [Fungsi Utama Anjungan Pasien Mandiri](#fungsi-utama-anjungan-pasien-mandiri)  
III. [Panduan Penggunaan Fitur](#panduan-penggunaan-fitur)  
IV. [Prosedur Pendaftaran Lengkap BPJS](#prosedur-pendaftaran-lengkap-bpjs)  
V. [Validasi dan Pemeriksaan Sistem](#validasi-dan-pemeriksaan-sistem)  
VI. [Penting Diperhatikan](#penting-diperhatikan)  
VII. [Kesimpulan](#kesimpulan)  

---

## PENDAHULUAN

Anjungan Pasien Mandiri merupakan sistem pelayanan otomatis yang dirancang untuk mempermudah dan mempercepat proses pendaftaran pasien di Rumah Sakit Indriati Boyolali. Sistem ini mampu menghubungkan dengan sistem BPJS dan Mobile JKN untuk memberikan pelayanan terbaik kepada pasien.

Sistem ini terintegrasi dengan:
- Sistem BPJS VClaim dan Mobile JKN
- Aplikasi Fingerprint Sidik Jari BPJS
- Sistem Antrean Online
- Sistem Satu Sehat

---

## FUNGSI UTAMA ANJUNGAN PASIEN MANDIRI

Sistem ini menyediakan 8 fungsi utama berdasarkan tampilan utama:

1. **ANTRIAN PASIEN** - Mengambil nomor antrian pendaftaran
2. **PENDAFTARAN POLIKLINIK** - Mendaftarkan pasien baru
3. **CHECK-IN (UMUM)** - Check-in pasien yang telah booking
4. **CHECK-IN (MobileJKN)** - Check-in via aplikasi Mobile JKN
5. **PASIEN JKN** - Pelayanan pasien BPJS
6. **SEP KONTROL** - Pembuatan SEP untuk kontrol
7. **KONTROL BEDA POLI** - Kontrol ke poli berbeda
8. **AKTIVASI SATUSEHAT MOBILE** - Aktivasi layanan Satu Sehat

---

## PANDUAN PENGGUNAAN FITUR

### A. ANTRIAN PASIEN
**Fungsi:** Mengambil nomor antrian pendaftaran secara mandiri

**Langkah-langkah penggunaan:**
1. Pilih tombol **"ANTRIAN PASIEN"** dari halaman utama
2. Pilih jenis antrian yang diinginkan:
   - **ADMISI (Appointment):** Pendaftaran pasien dengan booking
   - **ADMISI (Walk In):** Pendaftaran pasien umum
   - **ADMISI (Rawat Inap & IGD):** Pendaftaran rawat inap dan IGD
   - **CUSTOMER SERVICE:** Pelayanan informasi
   - **FARMASI:** Pelayanan obat
3. Sistem akan mengeluarkan nomor antrian
4. Simpan nomor antrian untuk diproses di loket terkait

### B. PENDAFTARAN POLIKLINIK
**Fungsi:** Mendaftarkan pasien baru secara mandiri

**Langkah-langkah penggunaan:**
1. Pilih tombol **"PENDAFTARAN POLIKLINIK"** dari halaman utama
2. Masukkan **Nomor Rekam Medis (No RM)** atau **Nomor KTP** pasien
   - Gunakan tombol angka untuk memasukkan nomor
   - Gunakan tombol `←` untuk menghapus satu karakter
   - Gunakan tombol **C** untuk menghapus semua karakter
3. Klik tombol centang (✓) untuk melanjutkan
4. Sistem akan menampilkan data pasien
5. Ikuti instruksi pendaftaran yang muncul di layar

### C. CHECK-IN (UMUM)
**Fungsi:** Check-in pasien yang telah melakukan booking

**Langkah-langkah penggunaan:**
1. Pilih tombol **"Check-in (UMUM)"** dari halaman utama
2. Masukkan **Nomor Rekam Medis (No RM)** pasien
   - Gunakan tombol angka untuk memasukkan nomor
   - Gunakan tombol `←` untuk menghapus satu karakter
   - Gunakan tombol **C** untuk menghapus semua karakter
3. Klik tombol centang (✓) untuk melanjutkan
4. Sistem akan memverifikasi booking dan menampilkan data
5. Jika valid, pasien akan terdaftar dan menerima bukti registrasi

### D. CHECK-IN (MobileJKN)
**Fungsi:** Check-in pasien yang menggunakan aplikasi Mobile JKN

**Langkah-langkah penggunaan:**
1. Pilih tombol **"Check-in (MobileJKN)"** dari halaman utama
2. Masukkan **Nomor Kartu** atau **Nomor Rekam Medis**
3. Klik tombol centang (✓) untuk melanjutkan
4. Sistem akan memeriksa data booking dari Mobile JKN
5. Jika valid, pasien akan terdaftar dan menerima bukti registrasi

### E. PASIEN JKN
**Fungsi:** Pendaftaran dan pelayanan pasien BPJS

**Langkah-langkah penggunaan:**
1. Pilih tombol **"PASIEN JKN"** dari halaman utama
2. Masukkan **No JKN/No RM/NIK** pasien
   - Bisa menggunakan nomor kartu BPJS
   - Bisa menggunakan nomor rekam medis
   - Bisa menggunakan nomor KTP
3. Klik tombol centang (✓) untuk melanjutkan
4. Sistem akan menampilkan data elegibilitas pasien
5. Verifikasi data dan lengkapi jika diperlukan
6. Klik tombol **"Konfirmasi"** untuk membuat SEP

### F. SEP KONTROL
**Fungsi:** Membuat Surat Eligibilitas Peserta untuk kontrol

**Langkah-langkah penggunaan:**
1. Pilih tombol **"SEP KONTROL"** dari halaman utama
2. Masukkan **nomor surat kontrol BPJS**
3. Klik tombol centang (✓) untuk melanjutkan
4. Sistem akan memverifikasi data kontrol
5. Jika valid, akan ditampilkan data pelayanan kontrol
6. Proses pendaftaran kontrol akan dilakukan otomatis

### G. KONTROL BEDA POLI
**Fungsi:** Pendaftaran kontrol ke poli berbeda

**Langkah-langkah penggunaan:**
1. Pilih tombol **"KONTROL BEDA POLI"** dari halaman utama
2. Masukkan **No JKN/No RM/NIK** pasien
3. Klik tombol centang (✓) untuk melanjutkan
4. Sistem akan mengecek data pasien dan SEP sebelumnya
5. Lakukan pilihan poli tujuan kontrol
6. Proses pendaftaran kontrol beda poli akan dilakukan

### H. AKTIVASI SATUSEHAT MOBILE
**Fungsi:** Aktivasi fitur Satu Sehat Mobile

**Langkah-langkah penggunaan:**
1. Pilih tombol **"AKTIVASI SATUSEHAT MOBILE"** dari halaman utama
2. Sistem akan mengarahkan ke aplikasi Satu Sehat
3. Ikuti instruksi aktivasi di aplikasi
4. Konfirmasi aktivasi di layar anjungan

---

## PROSEDUR PENDAFTARAN LENGKAP BPJS

**Langkah-langkah pendaftaran BPJS secara menyeluruh:**

1. Masukkan **Nomor Kartu BPJS** pada menu **"PASIEN JKN"**
2. Sistem akan memanggil data dari **VClaim**
3. Verifikasi **data pasien** yang ditampilkan
4. Pilih **poli tujuan** pemeriksaan
5. Pilih **dokter DPJP**
6. Isi data **SEP** sesuai kebutuhan:
   - Tanggal SEP
   - Tanggal Rujukan
   - No Rujukan
   - PPK Rujukan
   - Diagnosa Awal
7. Lakukan **verifikasi sidik jari** atau pengenalan wajah
8. Sistem akan membuat **SEP** dan memberikan bukti registrasi

---

## VALIDASI DAN PEMERIKSAAN SISTEM

### A. VALIDASI BIOMETRIK

Sistem Anjungan Pasien Mandiri dilengkapi dengan fitur validasi biometrik yang terdiri dari:

1. **Verifikasi Sidik Jari**
   - Digunakan untuk pasien usia di atas 17 tahun
   - Dilakukan melalui aplikasi sidik jari BPJS
   - Validasi dilakukan secara real-time dengan server BPJS

2. **Pengenalan Wajah (Facial Recognition)**
   - Alternatif verifikasi bagi pasien yang tidak bisa sidik jari
   - Memanfaatkan kamera untuk melakukan pengenalan wajah
   - Terintegrasi dengan sistem BPJS

**Proses Validasi Biometrik:**
- Sistem secara otomatis memeriksa apakah pasien sudah melakukan perekaman biometrik
- Jika belum, pasien akan diminta melakukan perekaman
- Jika sudah, sistem akan memverifikasi kesesuaian biometrik
- Hasil validasi akan memengaruhi proses pembuatan SEP

### B. VALIDASI DOKTER CUTI

Sistem melakukan pemeriksaan otomatis terhadap status dokter:
```
if (Valid.ValidasiDOkterCuti(kodedokterreg, kodepolireg) == true) {
    // Tampilkan pesan bahwa dokter sedang cuti
}
```

### C. VALIDASI REGISTRASI BERULANG

Sistem mencegah pendaftaran ganda dengan validasi:
```
if (Valid.ValidasiRegistrasi(kodepolireg, kodedokterreg, TNoRM.getText(), 
     Valid.SetTgl(TanggalSEP.getSelectedItem() + ""), Kdpnj.getText()) == true) {
    // Tampilkan pesan bahwa pasien sudah terdaftar
}
```

### D. VALIDASI USIA DAN BIOMETRIK

Untuk pasien dewasa (≥17 tahun), sistem mewajibkan validasi biometrik:
```
if (statusfinger == false && 
    Sequel.cariInteger("select timestampdiff(year, '" + TglLahir.getText() + "', CURRENT_DATE())") >= 17 && 
    JenisPelayanan.getSelectedIndex() != 0 && 
    !KdPoli.getText().equals("IGD")) {
    // Tampilkan pesan bahwa fingerprint belum dilakukan
}
```

### E. VALIDASI SATU SEHAT

Sistem memeriksa persetujuan penggunaan data untuk platform Satu Sehat:
```
public boolean GeneralConsentSatuSehat(String NoRMPasien) {
    int cariflaging = Sequel.cariInteger(
        "select count(flagging_pasien_satusehat.no_rkm_medis) from flagging_pasien_satusehat " +
        "where flagging_pasien_satusehat.no_rkm_medis='" + NoRMPasien + "'");
    
    if (cariflaging > 0) {
        return true; // Sudah memberikan persetujuan
    } else {
        return false; // Belum memberikan persetujuan
    }
}
```

### F. VALIDASI HAK KELAS

Sistem memeriksa hak kelas peserta BPJS:
```
if (response.path("peserta").path("hakKelas").path("kode").asText().equals("1")) {
    // Kelas 1
} else if (response.path("peserta").path("hakKelas").path("kode").asText().equals("2")) {
    // Kelas 2
} else if (response.path("peserta").path("hakKelas").path("kode").asText().equals("3")) {
    // Kelas 3
}
```

### G. VALIDASI JENIS PELAYANAN

Sistem membedakan antara rawat inap dan rawat jalan:
```
if (JenisPelayanan.getSelectedIndex() == 0) {
    // Rawat Inap
} else if (JenisPelayanan.getSelectedIndex() == 1) {
    // Rawat Jalan
}
```

### H. VALIDASI BATAS SEP

Sistem membatasi jumlah SEP yang dapat dibuat dalam sehari:
```
if (Sequel.cariInteger(
    "select count(bridging_sep.no_kartu) from bridging_sep " +
    "where bridging_sep.no_kartu='" + no_peserta + "' and " +
    "bridging_sep.jnspelayanan='" + JenisPelayanan.getSelectedItem().toString().substring(0, 1) + "' and " +
    "bridging_sep.tglsep like '%" + Valid.SetTgl(TanggalSEP.getSelectedItem() + "") + "%' and " +
    "bridging_sep.nmpolitujuan not like '%darurat%'") >= 1) {
    // Batas SEP harian telah tercapai
}
```

### I. VALIDASI NOMOR RUJUKAN

Sistem memvalidasi format dan keberadaan nomor rujukan:
```
if (TujuanKunjungan.getSelectedItem().toString().equals("0. Normal") && 
    FlagProsedur.getSelectedItem().toString().equals("") && 
    Penunjang.getSelectedItem().toString().equals("") && 
    AsesmenPoli.getSelectedItem().toString().equals("")) {
    // Validasi rujukan normal
}
```

### J. VALIDASI LAPORAN KECELAKAAN

Untuk kasus kecelakaan lalu lintas:
```
if ((LakaLantas.getSelectedIndex() == 1) && Keterangan.getText().equals("")) {
    // Keterangan kecelakaan harus diisi
}
```

---

## PENTING DIPERHATIKAN

1. Pastikan **kartu BPJS aktif** dan tidak dalam status ditangguhkan
2. Baca dengan **teliti data** yang ditampilkan sebelum konfirmasi
3. **Simpan bukti registrasi** dan nomor antrian untuk proses selanjutnya
4. Hubungi **petugas** jika terjadi kendala teknis
5. Gunakan sistem dengan **jujur** dan tidak menyalahgunakannya
6. **Validasi biometrik** wajib dilakukan untuk pasien dewasa
7. Perhatikan **batas waktu pendaftaran** yang berlaku
8. **Perbarui data** jika terdapat ketidaksesuaian

---

## KESIMPULAN

Anjungan Pasien Mandiri merupakan inovasi pelayanan rumah sakit yang bertujuan untuk memberikan kemudahan, efisiensi waktu, dan kenyamanan kepada pasien. Sistem ini dilengkapi dengan berbagai validasi otomatis untuk memastikan keakuratan data dan mencegah penyalahgunaan.

Dengan mengikuti panduan ini, diharapkan setiap pasien dapat menggunakan layanan dengan baik dan memperoleh pelayanan kesehatan secara optimal. Untuk informasi lebih lanjut atau bantuan teknis, silakan menghubungi **Customer Service** kami.

---
