<!-- Header -->
<?php include 'templates/header.php'; ?>

<?php
include 'koneksi.php';

// Menghitung Usia
function hitung_usia($tanggal_lahir)
{
    $lahir = new DateTime($tanggal_lahir);
    $hari_ini = new DateTime();
    return $hari_ini->diff($lahir)->y;
}

// Delete
if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE NIM='$_GET[hapus]'");
    header("Location: admin.php");
    exit;
}

// Tambah
if (isset($_POST['tambah'])) {

    mysqli_query($conn, "INSERT INTO mahasiswa 
        (NIM, NAMA, ALAMAT, `TANGGAL LAHIR`, GENDER)
        VALUES (
            '$_POST[nim]',
            '$_POST[nama]',
            '$_POST[alamat]',
            '$_POST[tanggal]',
            '$_POST[gender]'
        )");

    header("Location: admin.php");
    exit;
}

// Edit
if (isset($_POST['edit'])) {

    mysqli_query($conn, "UPDATE mahasiswa SET
        NAMA='$_POST[nama]',
        ALAMAT='$_POST[alamat]',
        `TANGGAL LAHIR`='$_POST[tanggal]',
        GENDER='$_POST[gender]'
        WHERE NIM='$_POST[nim]'");

    header("Location: admin.php");
    exit;
}


// Load Data
$result = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY NIM DESC")
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">

    <div class="page-header">
                <button class="btn-primary" onclick="openTambah()">
            + Tambah Mahasiswa
        </button>
    </div>

    <!-- Tabel -->
    <table class="table-modern">

        <thead>
            <tr>
                <th>NIM</th>
                <th>NAMA</th>
                <th>ALAMAT</th>
                <th>TANGGAL LAHIR</th>
                <th>GENDER</th>
                <th>USIA</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $row['NIM']; ?></td>
                <td><?= $row['NAMA']; ?></td>
                <td><?= $row['ALAMAT']; ?></td>
                <td><?= date('d M Y', strtotime($row['TANGGAL LAHIR'])); ?></td>
                <td><?= $row['GENDER']; ?></td>
                <td><?= hitung_usia($row['TANGGAL LAHIR']); ?> Tahun</td>

                <td class="aksi">
                    <button class="btn-edit"
                        onclick="openEdit(
                            '<?= $row['NIM']; ?>',
                            '<?= $row['NAMA']; ?>',
                            '<?= $row['ALAMAT']; ?>',
                            '<?= $row['TANGGAL LAHIR']; ?>',
                            '<?= $row['GENDER']; ?>'
                        )">
                        Edit
                    </button>

                    <a href="admin.php?hapus=<?= $row['NIM']; ?>"
                       class="btn-delete"
                       onclick="return confirm('Yakin hapus data?')">
                        Hapus
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn-secondary">← Kembali ke Home</a>
</div>


<!-- Page Tambah -->
<div class="pop" id="popTambah">
    <div class="pop-content">

        <span class="close" onclick="closePop()">×</span>

        <h3>Tambah Mahasiswa</h3>

        <form method="POST" class="form-modern">

            <input name="nim" placeholder="NIM" required>
            <input name="nama" placeholder="Nama" required>

            <textarea name="alamat" placeholder="Alamat"></textarea>

            <input type="date" name="tanggal" required>

            <select name="gender">
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>

            <button name="tambah" class="btn-primary">Simpan</button>

        </form>

    </div>
</div>


<!-- Page Edit -->
<div class="pop" id="popEdit">
    <div class="pop-content">

        <span class="close" onclick="closePop()">×</span>

        <h3>Edit Mahasiswa</h3>

        <form method="POST" class="form-modern">

            <input id="editNim" name="nim" readonly>
            <input id="editNama" name="nama" required>

            <textarea id="editAlamat" name="alamat"></textarea>

            <input id="editTanggal" type="date" name="tanggal" required>

            <select id="editGender" name="gender">
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>

            <button name="edit" class="btn-edit">Update</button>

        </form>

    </div>
</div>


<script>
function openTambah() {
    document.getElementById("popTambah").style.display = "flex";
}

function openEdit(nim, nama, alamat, tanggal, gender) {

    document.getElementById("popEdit").style.display = "flex";

    document.getElementById("editNim").value = nim;
    document.getElementById("editNama").value = nama;
    document.getElementById("editAlamat").value = alamat;
    document.getElementById("editTanggal").value = tanggal;
    document.getElementById("editGender").value = gender;
}

function closePop() {
    document.getElementById("popTambah").style.display = "none";
    document.getElementById("popEdit").style.display = "none";
}
</script>

<!-- Footer -->
<?php include 'templates/footer.php'; ?>