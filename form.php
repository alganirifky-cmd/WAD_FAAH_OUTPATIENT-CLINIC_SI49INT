<?php
require 'koneksi.php';

$id    = $_GET['id'] ?? null;
$error = '';

$d = [
    'kode_dokter' => '', 'nama_lengkap' => '', 'gelar' => '',
    'jenis_kelamin' => '', 'sip' => '', 'spesialisasi' => '',
    'hari_praktik' => '', 'email' => '', 'telepon' => '', 'status' => 'Aktif'
];


if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM dokter WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        die("Data tidak ditemukan. <a href='index.php'>Kembali</a>");
    }
    $d = $row;
}
$hariDipilih = $d['hari_praktik'] ? explode(',', $d['hari_praktik']) : [];

// Proses simpan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hariDipilih = $_POST['hari_praktik'] ?? [];

    $d = [
        'kode_dokter'   => trim($_POST['kode_dokter']),
        'nama_lengkap'  => trim($_POST['nama_lengkap']),
        'gelar'         => trim($_POST['gelar']),
        'jenis_kelamin' => $_POST['jenis_kelamin'] ?? '',
        'sip'           => trim($_POST['sip']),
        'spesialisasi'  => $_POST['spesialisasi'],
        'hari_praktik'  => implode(',', $hariDipilih),
        'email'         => trim($_POST['email']),
        'telepon'       => trim($_POST['telepon']),
        'status'        => $_POST['status'],
    ];

    try {
        if ($id) {
            $sql = "UPDATE dokter SET kode_dokter=:kode_dokter, nama_lengkap=:nama_lengkap,
                    gelar=:gelar, jenis_kelamin=:jenis_kelamin, sip=:sip,
                    spesialisasi=:spesialisasi, hari_praktik=:hari_praktik,
                    email=:email, telepon=:telepon, status=:status WHERE id=:id";
            $d['id'] = $id;
            $pesan = 'Data dokter berhasil diperbarui.';
        } else {
            $sql = "INSERT INTO dokter (kode_dokter, nama_lengkap, gelar, jenis_kelamin, sip,
                    spesialisasi, hari_praktik, email, telepon, status)
                    VALUES (:kode_dokter, :nama_lengkap, :gelar, :jenis_kelamin, :sip,
                    :spesialisasi, :hari_praktik, :email, :telepon, :status)";
            $pesan = 'Data dokter berhasil ditambahkan.';
        }
        $pdo->prepare($sql)->execute($d);
        header("Location: index.php?pesan=" . urlencode($pesan));
        exit;
    } catch (PDOException $e) {
        $error = ($e->getCode() == 23000)
            ? 'Kode dokter sudah dipakai, gunakan kode lain.'
            : 'Gagal menyimpan: ' . $e->getMessage();
    }
}

$daftarSpesialisasi = ['Spesialis Penyakit Dalam', 'Spesialis Bedah Syaraf', 'Spesialis Anak', 'Spesialis Kulit'];
$daftarHari         = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$daftarStatus       = ['Aktif', 'Non-Aktif', 'Cuti'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pengisian Form Data Dokter</title>
    <link rel="stylesheet" href="tampilan.css">
</head>
<body>

    <div class="navbar">
        <div class="logo">Klinik Sehat</div>
        <div class="nav-links">
            <a href="#">Home</a>
            <a href="index.php" class="active">Data Dokter</a>
            <a href="#">Data Pasien</a>
            <a href="#">Jadwal Praktik</a>
        </div>
    </div>

    <div class="container">
        <h1><?= $id ? 'Edit' : 'Form' ?> Data Dokter</h1>
        <p>Isi data master dokter, nomor SIP (Surat Izin Praktik), spesialisasi, dan kontak.</p>

        <?php if ($error): ?>
            <div class="alert error"><?= h($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST">

            <h3>1. Identitas Dokter</h3>

            <p>
                Kode Dokter *<br>
                <input type="text" name="kode_dokter" placeholder="Dr.001"
                       value="<?= h($d['kode_dokter']) ?>" required>
            </p>

            <p>
                Nama Lengkap *<br>
                <input type="text" name="nama_lengkap" placeholder="Nama tanpa gelar"
                       value="<?= h($d['nama_lengkap']) ?>" required>
            </p>

            <p>
                Gelar<br>
                <input type="text" name="gelar" placeholder="Gelar (opsional)"
                       value="<?= h($d['gelar']) ?>">
            </p>

            <p>
                Jenis Kelamin *<br>
                <input type="radio" id="jk_l" name="jenis_kelamin" value="Laki-laki"
                       <?= $d['jenis_kelamin'] === 'Laki-laki' ? 'checked' : '' ?> required>
                <label for="jk_l">Laki-laki</label>
                <input type="radio" id="jk_p" name="jenis_kelamin" value="Perempuan"
                       <?= $d['jenis_kelamin'] === 'Perempuan' ? 'checked' : '' ?>>
                <label for="jk_p">Perempuan</label>
            </p>

            <hr>

            <h3>2. Izin Praktik &amp; Spesialisasi</h3>

            <p>
                No. SIP *<br>
                <input type="text" name="sip" placeholder="503/SIP/001/DPMPTSP/2026"
                       value="<?= h($d['sip']) ?>" required>
            </p>

            <p>
                Spesialisasi *<br>
                <select name="spesialisasi" required>
                    <option value="">-- Pilih Spesialisasi --</option>
                    <?php foreach ($daftarSpesialisasi as $s): ?>
                        <option value="<?= h($s) ?>" <?= $d['spesialisasi'] === $s ? 'selected' : '' ?>>
                            <?= h($s) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>

            <p>
                Hari Praktik<br>
                <?php foreach ($daftarHari as $hari): ?>
                    <input type="checkbox" name="hari_praktik[]" value="<?= $hari ?>"
                           <?= in_array($hari, $hariDipilih) ? 'checked' : '' ?>> <?= $hari ?>
                <?php endforeach; ?>
            </p>

            <hr>

            <h3>3. Kontak dan Status</h3>

            <p>
                Email<br>
                <input type="email" name="email" placeholder="dokter@klinik.id"
                       value="<?= h($d['email']) ?>">
            </p>

            <p>
                No. Telepon / HP *<br>
                <input type="text" name="telepon" placeholder="081234567890"
                       value="<?= h($d['telepon']) ?>" required>
            </p>

            <p>
                Status *<br>
                <select name="status" required>
                    <?php foreach ($daftarStatus as $st): ?>
                        <option value="<?= $st ?>" <?= $d['status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
                    <?php endforeach; ?>
                </select>
            </p>

            <p>
                <a href="index.php" class="btn abu">Batal</a>
                <button type="reset">Reset</button>
                <button type="submit">Simpan Dokter</button>
            </p>

        </form>
    </div>

</body>
</html>