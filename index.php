<?php
// Inisialisasi variabel untuk menampung pesan status dan data
$pesan_sukses = "";
$pesan_error  = "";
$data_dokter  = null;

// 1. MEMERIKSA APAKAH FORM DIKIRIM VIA METODE POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. MENGAMBIL DAN MENSTERILKAN DATA INPUT (SANITASI)
    $kode_dokter   = htmlspecialchars(trim($_POST['kode_dokter'] ?? ''));
    $nama_lengkap  = htmlspecialchars(trim($_POST['nama_lengkap'] ?? ''));
    $gelar         = htmlspecialchars(trim($_POST['gelar'] ?? ''));
    $jenis_kelamin = htmlspecialchars(trim($_POST['jenis_kelamin'] ?? ''));
    $sip           = htmlspecialchars(trim($_POST['sip'] ?? ''));
    $spesialisasi  = htmlspecialchars(trim($_POST['spesialisasi'] ?? ''));
    $email         = htmlspecialchars(trim($_POST['email'] ?? ''));
    $telepon       = htmlspecialchars(trim($_POST['telepon'] ?? ''));

    // Memproses masukan checkbox (Hari Praktik)
    $hari_praktik = isset($_POST['hari_praktik']) ? $_POST['hari_praktik'] : [];
    if (is_array($hari_praktik)) {
        $hari_praktik_str = implode(", ", array_map('htmlspecialchars', $hari_praktik));
    } else {
        $hari_praktik_str = htmlspecialchars($hari_praktik);
    }

    // 3. VALIDASI TINGKAT SERVER (SERVER-SIDE VALIDATION)
    if (empty($kode_dokter) || empty($nama_lengkap) || empty($jenis_kelamin) || empty($sip) || empty($spesialisasi) || empty($telepon)) {
        $pesan_error = "Harap isi semua kolom wajib yang bertanda bintang (*)!";
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $pesan_error = "Format alamat email tidak valid!";
    } else {
        // 4. JIKA VALIDASI LOLOS (SIMULASI PENYIMPANAN KE DATABASE)
        $pesan_sukses = "Data Dokter berhasil diproses dan disimpan!";
        $data_dokter = [
            'Kode Dokter'   => $kode_dokter,
            'Nama Lengkap'  => $nama_lengkap . ($gelar ? ", " . $gelar : ""),
            'Jenis Kelamin' => $jenis_kelamin,
            'No. SIP'       => $sip,
            'Spesialisasi'  => $spesialisasi,
            'Hari Praktik'  => !empty($hari_praktik_str) ? $hari_praktik_str : "Tidak ada hari dipilih",
            'Email'         => !empty($email) ? $email : "-",
            'No. Telepon'   => $telepon
        ];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pengisian Form Data Dokter</title>
    <link rel="stylesheet" href="tampilan.css">
    <style>
        .alert-success { background-color: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; }
        .alert-danger { background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; }
        .hasil-box { background: #eef2ff; padding: 15px; border-radius: 4px; border: 1px solid #1d52d8; margin-top: 20px; }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">Klinik Sehat informasi sistem</div>
        <div class="nav-links">
            <a href="#" class="active">Home</a>
            <a href="#">Data Dokter</a>
            <a href="#">Data Pasien</a>
            <a href="#">Jadwal Praktik</a>
        </div>
    </div>

    <div class="container">
        <h1>Form Data Dokter</h1>
        <p>Isi data master dokter, nomor SIP (Surat Izin Praktik), spesialisasi, dan kontak.</p>

        <!-- Menampilkan Pesan Notifikasi -->
        <?php if (!empty($pesan_error)): ?>
            <div class="alert-danger"><?= $pesan_error; ?></div>
        <?php endif; ?>

        <?php if (!empty($pesan_sukses)): ?>
            <div class="alert-success"><?= $pesan_sukses; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            
            <h3>1. Identitas Dokter</h3>

            <p>
                Kode Dokter *<br>
                <input type="text" name="kode_dokter" placeholder="Dr.001" value="<?= htmlspecialchars($_POST['kode_dokter'] ?? ''); ?>" required>
            </p>

            <p>
                Nama Lengkap *<br>
                <input type="text" name="nama_lengkap" placeholder="Nama tanpa gelar" value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? ''); ?>" required>
            </p>

            <p>
                Gelar<br>
                <input type="text" name="gelar" placeholder="Gelar" value="<?= htmlspecialchars($_POST['gelar'] ?? ''); ?>">
            </p>

            <p>
                Jenis Kelamin *<br>
                <input type="radio" id="jk_l" name="jenis_kelamin" value="Laki-laki" <?= (($_POST['jenis_kelamin'] ?? '') == 'Laki-laki') ? 'checked' : ''; ?> required>
                <label for="jk_l">Laki-laki</label>
                <input type="radio" id="jk_p" name="jenis_kelamin" value="Perempuan" <?= (($_POST['jenis_kelamin'] ?? '') == 'Perempuan') ? 'checked' : ''; ?>>
                <label for="jk_p">Perempuan</label>
            </p>

            <hr>

            <h3>2. Izin Praktik & Spesialisasi</h3>

            <p>
                No. SIP *<br>
                <input type="text" name="sip" placeholder="No. SIP" value="<?= htmlspecialchars($_POST['sip'] ?? ''); ?>" required>
            </p>

            <p>
                Spesialisasi *<br>
                <select name="spesialisasi" required>
                    <option value="">-- Pilih Spesialisasi --</option>
                    <option value="Spesialis Anak" <?= (($_POST['spesialisasi'] ?? '') == 'Spesialis Anak') ? 'selected' : ''; ?>>Spesialis Anak</option>
                    <option value="Spesialis Kulit" <?= (($_POST['spesialisasi'] ?? '') == 'Spesialis Kulit') ? 'selected' : ''; ?>>Spesialis Kulit</option>
                </select>
            </p>

            <p>
                Hari Praktik<br>
                <!-- Diberi akhiran [] agar bisa menampung banyak pilihan array -->
                <?php $hari_terpilih = $_POST['hari_praktik'] ?? []; ?>
                <input type="checkbox" name="hari_praktik[]" value="Senin" <?= in_array("Senin", (array)$hari_terpilih) ? 'checked' : ''; ?>> Senin
                <input type="checkbox" name="hari_praktik[]" value="Selasa" <?= in_array("Selasa", (array)$hari_terpilih) ? 'checked' : ''; ?>> Selasa
                <input type="checkbox" name="hari_praktik[]" value="Rabu" <?= in_array("Rabu", (array)$hari_terpilih) ? 'checked' : ''; ?>> Rabu
                <input type="checkbox" name="hari_praktik[]" value="Kamis" <?= in_array("Kamis", (array)$hari_terpilih) ? 'checked' : ''; ?>> Kamis
                <input type="checkbox" name="hari_praktik[]" value="Jumat" <?= in_array("Jumat", (array)$hari_terpilih) ? 'checked' : ''; ?>> Jumat
                <input type="checkbox" name="hari_praktik[]" value="Sabtu" <?= in_array("Sabtu", (array)$hari_terpilih) ? 'checked' : ''; ?>> Sabtu
            </p>

            <hr>

            <h3>3. Kontak dan Status</h3>

            <p>
                Email<br>
                <input type="email" name="email" placeholder="name@gmail.com" value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>">
            </p>

            <p>
                No. Telepon / HP *<br>
                <input type="text" name="telepon" placeholder="081234567890" value="<?= htmlspecialchars($_POST['telepon'] ?? ''); ?>" required>
            </p>

            <p>
                <button type="reset" class="btn-reset">Reset</button>
                <button type="submit" class="btn-simpan">Simpan Dokter</button>
            </p>

        </form>

        <!-- Menampilkan Output Hasil Pengiriman Data -->
        <?php if ($data_dokter): ?>
            <div class="hasil-box">
                <h3>Hasil Data yang Diterima Server:</h3>
                <ul>
                    <?php foreach ($data_dokter as $label => $nilai): ?>
                        <li><strong><?= $label; ?>:</strong> <?= $nilai; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>