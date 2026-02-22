<?php
include 'koneksi.php';

// search
$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search) {
    $query = "SELECT * FROM mahasiswa 
              WHERE NIM LIKE '%$search%' 
              OR NAMA LIKE '%$search%' 
              ORDER BY NIM DESC";
} else {
    $query = "SELECT * FROM mahasiswa ORDER BY NIM DESC";
}

$result = mysqli_query($conn, $query);


// Hitung Masing-Masing
$lakiQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM mahasiswa WHERE GENDER='L'");
$lakiData = mysqli_fetch_assoc($lakiQuery);
$laki = $lakiData['total'];

$perempuanQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM mahasiswa WHERE GENDER='P'");
$perempuanData = mysqli_fetch_assoc($perempuanQuery);
$perempuan = $perempuanData['total'];
?>

psr


<!-- Header -->
<?php include 'templates/header.php'; ?>


<!-- Konten -->
<div class="top-bar">
    <div class="total-box">
        Total Mahasiswa
        <span><?= $laki + $perempuan; ?></span>
    </div>

    <div class="search-box">
        <form method="GET">
            <input 
                type="text" 
                name="search" 
                placeholder="Cari mahasiswa..."
                value="<?= $search; ?>"
            >
            <button class="stat-box" type="submit">
                🔍
            </button>
        </form>
    </div>
</div>

<table class="table-modern">

    <thead>
        <tr>
            <th>NIM</th>
            <th>NAMA</th>
            <th>ALAMAT</th>
            <th>TANGGAL LAHIR</th>
            <th>GENDER</th>
            <th>USIA</th>
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
            <td>            
                <?php $lahir = new DateTime($row['TANGGAL LAHIR']);
                $today = new DateTime();
                $usia = $today->diff($lahir)->y;                
                echo $usia . " Tahun";
                ?> 
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>

</table>


<!-- Statistik -->
<div class="chart-wrapper">
    <div class="chart-container">
        <h3>Statistik Jenis Kelamin</h3>
        <canvas id="genderChart"></canvas>
    </div>
</div>

<script>
const ctx = document.getElementById('genderChart');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Laki-Laki', 'Perempuan'],
        datasets: [{
            data: [<?= $laki ?>, <?= $perempuan ?>],
            backgroundColor: [
                '#7b2ff7',   // laki
                '#ff4ecd'    // perempuan
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<?php
$laki = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM mahasiswa WHERE GENDER='L'"))['total'];
$perempuan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM mahasiswa WHERE GENDER='P'"))['total'];
?>

<!-- footer -->
<?php include 'templates/footer.php'; ?>