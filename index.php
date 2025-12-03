<?php
session_start();

// ==========================================
// BAGIAN 1: LOGIKA OOP & DATA (BACKEND)
// ==========================================

class Book {
    private $id;
    private $title;
    private $author;
    private $isBorrowed;
    private $dueDate;

    public function __construct($id, $title, $author) {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->isBorrowed = false;
        $this->dueDate = null;
    }

    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getAuthor() { return $this->author; }
    public function getIsBorrowed() { return $this->isBorrowed; }
    public function getDueDate() { return $this->dueDate; }

    public function borrow($days = 7) {
        $this->isBorrowed = true;
        $this->dueDate = date('Y-m-d', strtotime("+$days days"));
    }

    public function returnBook() {
        $this->isBorrowed = false;
        $this->dueDate = null;
    }
}

class User {
    protected $name;
    protected $nim;
    public function __construct($name, $nim) { $this->name = $name; $this->nim = $nim; }
    public function getName() { return $this->name; }
}

class Member extends User {
    private $prodi;
    private $angkatan;
    private $borrowedBooks = []; 

    public function __construct($name, $nim, $prodi, $angkatan) {
        parent::__construct($name, $nim);
        $this->prodi = $prodi;
        $this->angkatan = $angkatan;
    }

    public function getProdi() { return $this->prodi; }
    public function getAngkatan() { return $this->angkatan; }
    
    public function addBorrowedBook($bookId) {
        $this->borrowedBooks[] = [
            'id' => $bookId,
            'borrowDate' => date('Y-m-d')
        ];
    }

    public function removeBorrowedBook($bookId) {
        foreach ($this->borrowedBooks as $key => $book) {
            if ($book['id'] == $bookId) {
                unset($this->borrowedBooks[$key]);
                $this->borrowedBooks = array_values($this->borrowedBooks);
                return $book['borrowDate'];
            }
        }
        return null;
    }
    public function getHistory() { return $this->borrowedBooks; }
}

// DATA SEEDING 100 buku + penulis
if (!isset($_SESSION['books'])) {
    $libraryData = [
        ["Laskar Pelangi", "Andrea Hirata"], ["Bumi Manusia", "Pramoedya Ananta Toer"], ["Anak Semua Bangsa", "Pramoedya Ananta Toer"], 
        ["Jejak Langkah", "Pramoedya Ananta Toer"], ["Rumah Kaca", "Pramoedya Ananta Toer"], ["Cantik Itu Luka", "Eka Kurniawan"], 
        ["Lelaki Harimau", "Eka Kurniawan"], ["Laut Bercerita", "Leila S. Chudori"], ["Pulang", "Leila S. Chudori"], 
        ["Saman", "Ayu Utami"], ["Larung", "Ayu Utami"], ["Ronggeng Dukuh Paruk", "Ahmad Tohari"], ["Bekisar Merah", "Ahmad Tohari"],
        ["Tenggelamnya Kapal Van der Wijck", "Buya Hamka"], ["Di Bawah Lindungan Ka'bah", "Buya Hamka"], ["Salah Asuhan", "Abdoel Moeis"], 
        ["Sitti Nurbaya", "Marah Rusli"], ["Belenggu", "Armijn Pane"], ["Atheis", "Achdiat K. Mihardja"], ["Senja di Jakarta", "Mochtar Lubis"],
        ["Jalan Tak Ada Ujung", "Mochtar Lubis"], ["Harimau! Harimau!", "Mochtar Lubis"], ["Robohnya Surau Kami", "A.A. Navis"], 
        ["Para Priyayi", "Umar Kayam"], ["Burung-Burung Manyar", "Y.B. Mangunwijaya"], ["Olenka", "Budi Darma"], ["Hujan Bulan Juni", "Sapardi Djoko Damono"],
        ["Perahu Kertas", "Dee Lestari"], ["Filosofi Kopi", "Dee Lestari"], ["Rectoverso", "Dee Lestari"], ["Supernova: Ksatria, Puteri...", "Dee Lestari"],
        ["Supernova: Akar", "Dee Lestari"], ["Supernova: Petir", "Dee Lestari"], ["Aroma Karsa", "Dee Lestari"], ["Rapijali", "Dee Lestari"],
        ["Bumi", "Tere Liye"], ["Bulan", "Tere Liye"], ["Matahari", "Tere Liye"], ["Bintang", "Tere Liye"], ["Ceros dan Batozar", "Tere Liye"],
        ["Komet", "Tere Liye"], ["Komet Minor", "Tere Liye"], ["Selena", "Tere Liye"], ["Nebula", "Tere Liye"], ["Si Putih", "Tere Liye"],
        ["Lumpu", "Tere Liye"], ["Bibi Gill", "Tere Liye"], ["Sagaras", "Tere Liye"], ["Hujan", "Tere Liye"], ["Pulang", "Tere Liye"],
        ["Pergi", "Tere Liye"], ["Rindu", "Tere Liye"], ["Tentang Kamu", "Tere Liye"], ["Negeri 5 Menara", "A. Fuadi"], ["Ranah 3 Warna", "A. Fuadi"],
        ["Rantau 1 Muara", "A. Fuadi"], ["Dilan 1990", "Pidi Baiq"], ["Dilan 1991", "Pidi Baiq"], ["Milea", "Pidi Baiq"], ["Ancika", "Pidi Baiq"],
        ["Orang-Orang Biasa", "Andrea Hirata"], ["Sirkus Pohon", "Andrea Hirata"], ["Guru Aini", "Andrea Hirata"], ["Ayah", "Andrea Hirata"],
        ["Edensor", "Andrea Hirata"], ["Maryamah Karpov", "Andrea Hirata"], ["Padang Bulan", "Andrea Hirata"], ["Cinta di Dalam Gelas", "Andrea Hirata"],
        ["Entrok", "Okky Madasari"], ["Kerumunan Terakhir", "Okky Madasari"], ["Pasung Jiwa", "Okky Madasari"], ["Bilangan Fu", "Ayu Utami"],
        ["Cerita dari Blora", "Pramoedya Ananta Toer"], ["Gadis Kretek", "Ratih Kumala"], ["Amba", "Laksmi Pamuntjak"], ["Aruna dan Lidahnya", "Laksmi Pamuntjak"],
        ["Harry Potter and the Sorcerer's Stone", "J.K. Rowling"], ["Harry Potter and the Chamber of Secrets", "J.K. Rowling"], ["Harry Potter and the Prisoner of Azkaban", "J.K. Rowling"],
        ["The Lord of the Rings: Fellowship", "J.R.R. Tolkien"], ["The Hobbit", "J.R.R. Tolkien"], ["To Kill a Mockingbird", "Harper Lee"], 
        ["1984", "George Orwell"], ["Animal Farm", "George Orwell"], ["The Great Gatsby", "F. Scott Fitzgerald"], ["Pride and Prejudice", "Jane Austen"],
        ["Sense and Sensibility", "Jane Austen"], ["Jane Eyre", "Charlotte Bronte"], ["Wuthering Heights", "Emily Bronte"], ["Little Women", "Louisa May Alcott"],
        ["The Catcher in the Rye", "J.D. Salinger"], ["Fahrenheit 451", "Ray Bradbury"], ["Brave New World", "Aldous Huxley"], ["Moby Dick", "Herman Melville"],
        ["War and Peace", "Leo Tolstoy"], ["Anna Karenina", "Leo Tolstoy"], ["Crime and Punishment", "Fyodor Dostoevsky"], ["The Alchemist", "Paulo Coelho"],
        ["The Da Vinci Code", "Dan Brown"], ["Angels & Demons", "Dan Brown"], ["Inferno", "Dan Brown"], ["Origin", "Dan Brown"], ["Sherlock Holmes: A Study in Scarlet", "Arthur Conan Doyle"]
    ];
    $bookObjects = [];
    foreach ($libraryData as $index => $data) {
        $bookObjects[$index] = new Book($index, $data[0], $data[1]);
    }
    $_SESSION['books'] = serialize($bookObjects);
}

// DATA PRODI  (S1 & Vokasi - Sorted)
$prodiList = [
    "D4 - Akuntansi Perpajakan",
    "D4 - Bahasa Asing Terapan",
    "D4 - Informasi dan Hubungan Masyarakat",
    "D4 - Manajemen dan Administrasi Logistik",
    "D4 - Perencanaan Tata Ruang dan Pertanahan",
    "D4 - Rekayasa Perancangan Mekanik",
    "D4 - Teknik Infrastruktur Sipil dan Perancangan Arsitektur",
    "D4 - Teknik Listrik Industri",
    "D4 - Teknologi Rekayasa Kimia Industri",
    "D4 - Teknologi Rekayasa Konstruksi Perkapalan",
    "D4 - Teknologi Rekayasa Otomasi",
    "S1 - Administrasi Bisnis",
    "S1 - Administrasi Publik",
    "S1 - Agribisnis",
    "S1 - Agroekoteknologi",
    "S1 - Akuntansi",
    "S1 - Arsitektur",
    "S1 - Bahasa dan Kebudayaan Jepang",
    "S1 - Biologi",
    "S1 - Bioteknologi",
    "S1 - Ekonomi",
    "S1 - Farmasi",
    "S1 - Fisika",
    "S1 - Geodesi",
    "S1 - Geologi",
    "S1 - Gizi",
    "S1 - Hubungan Internasional",
    "S1 - Hukum",
    "S1 - Ilmu Kelautan",
    "S1 - Ilmu Komunikasi",
    "S1 - Ilmu Pemerintahan",
    "S1 - Ilmu Perpustakaan",
    "S1 - Informatika",
    "S1 - Kedokteran",
    "S1 - Kedokteran Gigi",
    "S1 - Keperawatan",
    "S1 - Kesehatan Masyarakat",
    "S1 - Keselamatan dan Kesehatan Kerja",
    "S1 - Kimia",
    "S1 - Manajemen",
    "S1 - Manajemen Sumberdaya Perairan",
    "S1 - Matematika",
    "S1 - Oseanografi",
    "S1 - Perencanaan Wilayah dan Kota",
    "S1 - Peternakan",
    "S1 - Psikologi",
    "S1 - Sastra Indonesia",
    "S1 - Sastra Inggris",
    "S1 - Sejarah",
    "S1 - Statistika",
    "S1 - Teknik Elektro",
    "S1 - Teknik Geodesi",
    "S1 - Teknik Geologi",
    "S1 - Teknik Industri",
    "S1 - Teknik Kimia",
    "S1 - Teknik Komputer",
    "S1 - Teknik Lingkungan",
    "S1 - Teknik Mesin",
    "S1 - Teknik Perkapalan",
    "S1 - Teknik Sipil",
    "S1 - Teknologi Hasil Perikanan",
    "S1 - Teknologi Pangan"
];

// LOGIC HANDLERS
$message = ""; $messageType = ""; 

if (isset($_POST['login'])) {
    $nama = $_POST['nama']; $nim = $_POST['nim']; $prodi = $_POST['prodi']; $angkatan = $_POST['angkatan'];
    if (!is_string($nama) || trim($nama) == "" || $prodi == "" || $angkatan == "") { 
        $message = "Semua field harus diisi dengan benar!"; $messageType = "error"; 
    }
    elseif (!ctype_digit($nim)) { $message = "NIM harus berupa angka!"; $messageType = "error"; }
    else {
        $member = new Member($nama, $nim, $prodi, $angkatan);
        $_SESSION['user'] = serialize($member);
        header("Location: index.php"); exit();
    }
}
if (isset($_GET['action']) && $_GET['action'] == 'logout') { session_destroy(); header("Location: index.php"); exit(); }
if (isset($_SESSION['user'])) {
    $currentUser = unserialize($_SESSION['user']);
    $books = unserialize($_SESSION['books']);

    if (isset($_POST['borrow_id'])) {
        $id = $_POST['borrow_id'];
        if (count($currentUser->getHistory()) >= 3) { $message = "Batas maksimal 3 buku!"; $messageType = "error"; }
        else {
            if (!$books[$id]->getIsBorrowed()) {
                $books[$id]->borrow(); $currentUser->addBorrowedBook($id);
                $_SESSION['books'] = serialize($books); $_SESSION['user'] = serialize($currentUser);
                $message = "Buku berhasil dipinjam!"; $messageType = "success";
            }
        }
    }
    if (isset($_POST['return_id'])) {
        $id = $_POST['return_id'];
        $borrowDate = $currentUser->removeBorrowedBook($id);
        if ($borrowDate) {
            $books[$id]->returnBook();
            $selisih = (new DateTime())->diff(new DateTime($borrowDate))->days;
            $denda = ($selisih > 7) ? ($selisih - 7) * 1000 : 0;
            $message = $denda > 0 ? "Buku kembali. Denda: Rp " . number_format($denda) : "Buku kembali tepat waktu.";
            $messageType = $denda > 0 ? "warning" : "success";
            $_SESSION['books'] = serialize($books); $_SESSION['user'] = serialize($currentUser);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DipbookSpace - UNDIP Library</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #001242; /* Biru Tua  */
            --secondary: #00509D; /* Biru Laut  */
            --accent: #FFD166; /* Kuning Keemasan/Aksen */
            --dark: #001242;
            --light: #f3f4f6;
            --white: #ffffff;
            --shadow: 0 10px 30px -10px rgba(0, 18, 66, 0.2);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        /* --- SCROLLBAR CUSTOMIZATION (Tipis & Samar) --- */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(0, 18, 66, 0.3); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(0, 18, 66, 0.5); }

        body {
            background-color: #FAFAFA;
            /* Pattern dot background (Menggunakan warna Biru UNDIP) */
            background-image: radial-gradient(var(--secondary) 0.8px, transparent 0.8px), radial-gradient(var(--secondary) 0.8px, #FAFAFA 0.8px);
            background-size: 30px 30px;
            background-position: 0 0, 15px 15px;
            color: #333;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 0;
        }

        /* --- NOTIFICATIONS --- */
        .notification {
            position: fixed; top: 20px; right: 20px; padding: 15px 25px; border-radius: 12px;
            color: white; font-weight: 500; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            animation: slideIn 0.5s ease;
        }
        .notif-success { background: linear-gradient(135deg, #10B981, #059669); }
        .notif-error { background: linear-gradient(135deg, #EF4444, #DC2626); }
        .notif-warning { background: linear-gradient(135deg, #F59E0B, #D97706); }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* --- LOGIN WRAPPER --- */
        .login-wrapper { width: 100%; max-width: 450px; padding: 20px; margin: auto; }
        .card {
            background: var(--white); border-radius: 24px; padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05); border: 1px solid rgba(0, 18, 66, 0.1); /* Border disesuaikan */
            position: relative; overflow: hidden;
        }
        .card::before {
            content: ''; position: absolute; top: -50px; left: -50px; width: 150px; height: 150px;
            background: linear-gradient(135deg, var(--primary), var(--secondary)); filter: blur(60px); opacity: 0.1; border-radius: 50%;
        }

        h1.logo { font-size: 24px; font-weight: 700; color: var(--dark); margin-bottom: 10px; display: flex; align-items: center; gap: 10px; }
        .logo-img { height: 45px; width: auto; }

        .subtitle { color: #666; font-size: 14px; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--dark); margin-bottom: 8px; }
        .form-control {
            width: 100%; padding: 12px 15px; border: 2px solid #E5E7EB; /* Border lebih soft */ border-radius: 12px;
            font-size: 14px; transition: 0.3s; background: #F9FAFB;
        }
        .form-control:focus { border-color: var(--secondary); background: white; outline: none; box-shadow: 0 0 0 3px rgba(0, 80, 157, 0.2); }
        
        .btn-primary {
            width: 100%; padding: 14px; border: none; border-radius: 12px;
            /* Gradient Biru UNDIP */
            background: linear-gradient(135deg, var(--primary), var(--secondary)); 
            color: white; font-weight: 600; font-size: 15px; cursor: pointer; transition: 0.3s;
            box-shadow: 0 10px 20px rgba(0, 18, 66, 0.3);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 15px 25px rgba(0, 18, 66, 0.4); }

        /* --- DASHBOARD --- */
        .dashboard-container {
            width: 95%; max-width: 1200px; height: 90vh; background: var(--white);
            border-radius: 30px; box-shadow: 0 25px 50px rgba(0,0,0,0.08);
            display: flex; overflow: hidden; position: relative;
        }
        .sidebar { 
            width: 280px; background: #F8FAFC; padding: 30px; display: flex; flex-direction: column; 
            border-right: 1px solid #E5E7EB; /* Border lebih tegas */
            overflow-y: auto; 
        }
        .user-profile-mini { margin-bottom: 40px; padding: 15px; background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); text-align: center; }
        .avatar {
            width: 60px; height: 60px; 
            /* Gradien biru  */
            background: linear-gradient(to right, #00509D, #001242); 
            border-radius: 50%;
            margin: 0 auto 10px; display: flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: bold; color: white;
        }
        .nav-menu { list-style: none; }
        .nav-item { margin-bottom: 8px; }
        .nav-link { 
            display: block; padding: 12px 15px; border-radius: 12px; text-decoration: none; 
            color: #4B5563; font-weight: 500; transition: 0.2s; 
        }
        .nav-link:hover { 
            background: rgba(0, 80, 157, 0.1); 
            color: var(--secondary); 
            /* Tambah border kiri sebagai penanda hover */
            border-left: 3px solid var(--secondary);
            padding-left: 12px;
        }
        .nav-link.active { 
            background: rgba(0, 18, 66, 0.1); 
            color: var(--primary); 
            font-weight: 600; 
            border-left: 3px solid var(--primary); 
            padding-left: 12px;
        }
        .logout-btn { margin-top: auto; color: #EF4444; }

        .main-content { flex: 1; padding: 40px; overflow-y: auto; background-color: #FDFDFD; } /* Background sedikit beda dari sidebar */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
        .header h2 { font-size: 28px; color: var(--dark); }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card { 
            background: white; padding: 25px; border-radius: 20px; border: 1px solid #eee; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.02); 
            transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }

        .stat-title { color: #888; font-size: 13px; margin-bottom: 5px; }
        .stat-value { font-size: 24px; font-weight: 700; color: var(--dark); }

        .search-box { 
            width: 100%; margin-bottom: 25px; padding: 15px 20px; border-radius: 15px; 
            border: 1px solid #E5E7EB; font-family: inherit; transition: 0.3s;
            background: #fff;
        }
        .search-box:focus { border-color: var(--accent); }

        .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 25px; }
        .book-card {
            background: white; border: 1px solid #f0f0f0; border-radius: 16px; padding: 20px; position: relative;
            transition: 0.3s; display: flex; flex-direction: column; justify-content: space-between;
        }
        .book-card:hover { box-shadow: 0 10px 30px rgba(0,0,0,0.08); border-color: var(--accent); } /* Aksen Kuning pada hover */

        .book-tag {
            position: absolute; top: 15px; right: 15px; font-size: 10px; padding: 4px 8px; border-radius: 20px;
            font-weight: 700; text-transform: uppercase;
        }
        .tag-avail { background: #DCFCE7; color: #166534; }
        .tag-borrowed { background: #FEE2E2; color: #991B1B; }
        .book-title { font-weight: 600; font-size: 15px; color: var(--dark); margin-bottom: 5px; line-height: 1.4; }
        .book-author { font-size: 12px; color: #888; margin-bottom: 15px; }
        
        .btn-borrow, .btn-return { width: 100%; padding: 10px; border-radius: 10px; border: none; font-weight: 600; cursor: pointer; transition: 0.2s; font-size: 13px; }
        .btn-borrow { background: var(--dark); color: white; }
        .btn-borrow:hover { background: var(--secondary); }
        .btn-borrow:disabled { background: #ccc; cursor: not-allowed; }
        .btn-return { background: #FFF1F2; color: #BE123C; border: 1px solid #FECDD3; }
        .btn-return:hover { background: #FECDD3; }

        .history-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .history-table th { text-align: left; padding: 15px; color: #888; font-size: 13px; border-bottom: 1px solid #eee; }
        .history-table td { padding: 15px; border-bottom: 1px solid #f9f9f9; font-size: 14px; }
    </style>
</head>
<body>
    <?php if ($message != ""): ?>
        <div class="notification notif-<?= $messageType ?>"><?= $message ?></div>
        <script>setTimeout(() => { document.querySelector('.notification').style.display = 'none'; }, 4000);</script>
    <?php endif; ?>

    <?php if (!isset($_SESSION['user'])): ?>
    <div class="login-wrapper">
        <div class="card">
            <h1 class="logo">
                <img src="https://upload.wikimedia.org/wikipedia/id/2/2d/Undip.png" alt="UNDIP" class="logo-img">
                DipbookSpace.
            </h1>
            <p class="subtitle">Masuk untuk mengakses perpustakaan digital Universitas Diponegoro.</p>
            <form method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama" 
                           pattern="[A-Za-z\s]+" 
                           title="Nama hanya boleh mengandung huruf dan spasi."
                           required>
                </div>
                <div class="form-group">
                    <label>NIM (Hanya Angka)</label>
                    <input type="text" name="nim" class="form-control" placeholder="Cth: 24060120..." required>
                </div>
                <div class="form-group">
                    <label>Program Studi </label>
                    <select name="prodi" class="form-control" required>
                        <option value="" disabled selected>--- Pilih Program Studi ---</option> 
                        <?php foreach($prodiList as $p): ?>
                            <option value="<?= $p ?>"><?= $p ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Angkatan</label>
                    <select name="angkatan" class="form-control" required>
                        <option value="" disabled selected>--- Pilih Angkatan ---</option>
                        <?php for($i=2020; $i<=2025; $i++) echo "<option value='$i'>$i</option>"; ?>
                    </select>
                </div>
                <button type="submit" name="login" class="btn-primary">Sign In to Dashboard</button>
            </form>
        </div>
    </div>

    <?php else: 
        $user = unserialize($_SESSION['user']);
        $allBooks = unserialize($_SESSION['books']);
        $history = $user->getHistory();
    ?>
    <div class="dashboard-container">
        <div class="sidebar">
            <h1 class="logo" style="font-size: 20px;">
                <img src="https://upload.wikimedia.org/wikipedia/id/2/2d/Undip.png" alt="UNDIP" style="height:30px; width:auto;">
                DipbookSpace.
            </h1><br>
            <div class="user-profile-mini">
                <div class="avatar"><?= strtoupper(substr($user->getName(), 0, 1)) ?></div>
                <h3 style="font-size: 14px;"><?= htmlspecialchars($user->getName()) ?></h3>
                <p style="font-size: 11px; color: #888;"><?= htmlspecialchars($user->getProdi()) ?></p>
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><a href="?page=books" class="nav-link <?= (!isset($_GET['page']) || $_GET['page']=='books') ? 'active' : '' ?>">📚 Peminjaman Buku</a></li>
                <li class="nav-item"><a href="?page=history" class="nav-link <?= (isset($_GET['page']) && $_GET['page']=='history') ? 'active' : '' ?>">⏱️ Riwayat & Kembalikan</a></li>
                <li class="nav-item"><a href="?page=profile" class="nav-link <?= (isset($_GET['page']) && $_GET['page']=='profile') ? 'active' : '' ?>">👤 Profil Anggota</a></li>
                <li class="nav-item"><a href="?action=logout" class="nav-link logout-btn">🚪 Sign Out</a></li>
            </ul>
        </div>

        <div class="main-content">
            <?php if (!isset($_GET['page']) || $_GET['page'] == 'books'): ?>
                <div class="header">
                    <div><h2>Katalog Buku</h2><p style="color:#666">Jelajahi koleksi sastra terbaik kami.</p></div>
                    <div class="stat-card" style="padding: 10px 20px;">
                        <span class="stat-title">Kuota Pinjam</span><div class="stat-value" style="font-size:18px; color:var(--primary);"><?= count($history) ?> / 3</div>
                    </div>
                </div>
                <input type="text" id="searchBook" class="search-box" placeholder="Cari judul buku atau penulis..." onkeyup="filterBooks()">
                <div class="book-grid" id="bookContainer">
                    <?php foreach($allBooks as $book): ?>
                    <div class="book-card search-item">
                        <span class="book-tag <?= $book->getIsBorrowed() ? 'tag-borrowed' : 'tag-avail' ?>"><?= $book->getIsBorrowed() ? 'Dipinjam' : 'Tersedia' ?></span>
                        <div><div class="book-title"><?= $book->getTitle() ?></div><div class="book-author">Oleh <?= $book->getAuthor() ?></div></div>
                        <?php if(!$book->getIsBorrowed()): ?>
                            <form method="POST"><input type="hidden" name="borrow_id" value="<?= $book->getId() ?>"><button type="submit" class="btn-borrow">Pinjam Buku</button></form>
                        <?php else: ?><button disabled class="btn-borrow" style="opacity: 0.5;">Sedang Kosong</button><?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

            <?php elseif ($_GET['page'] == 'history'): ?>
                <div class="header"><h2>Buku yang Dipinjam</h2></div>
                <div class="stats-grid">
                    <div class="stat-card"><div class="stat-title">Sedang Dipinjam</div><div class="stat-value"><?= count($history) ?> Buku</div></div>
                    <div class="stat-card"><div class="stat-title">Batas Waktu</div><div class="stat-value">7 Hari</div></div>
                    <div class="stat-card"><div class="stat-title">Denda Keterlambatan</div><div class="stat-value" style="color: #EF4444;">Rp 1.000/hari</div></div>
                </div>
                <?php if(empty($history)): ?>
                    <div style="text-align:center; padding: 50px; color: #888;"><p>Tidak ada buku yang sedang dipinjam.</p></div>
                <?php else: ?>
                    <div class="book-grid">
                        <?php foreach($history as $record): 
                            $b = $allBooks[$record['id']];
                            $dueDate = date('Y-m-d', strtotime($record['borrowDate']. ' + 7 days'));
                            $today = date('Y-m-d');
                            $isLate = $today > $dueDate;
                        ?>
                        <div class="book-card" style="border-color: <?= $isLate ? '#EF4444' : '#eee' ?>">
                            <?php if($isLate): ?><span class="book-tag" style="background:red; color:white;">JATUH TEMPO</span><?php endif; ?>
                            <div><div class="book-title"><?= $b->getTitle() ?></div><div class="book-author">Dipinjam: <?= $record['borrowDate'] ?></div>
                            <div class="book-author" style="color: <?= $isLate ? 'red' : 'green' ?>">Kembali maks: <?= $dueDate ?></div></div>
                            <form method="POST"><input type="hidden" name="return_id" value="<?= $b->getId() ?>"><button type="submit" class="btn-return">Kembalikan Buku</button></form>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            <?php elseif ($_GET['page'] == 'profile'): ?>
                <div class="header"><h2>Profil Anggota</h2></div>
                <div class="card" style="max-width: 600px;">
                    <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 30px;">
                        <div class="avatar" style="width: 80px; height: 80px; font-size: 32px;"><?= strtoupper(substr($user->getName(), 0, 1)) ?></div>
                        <div><h3 style="font-size: 20px;"><?= $user->getName() ?></h3><span style="background: #EEF2FF; color: var(--primary); padding: 5px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">Mahasiswa Aktif</span></div>
                    </div>
                    <table class="history-table">
                        <tr><th width="150">Program Studi</th><td><?= $user->getProdi() ?></td></tr>
                        <tr><th>Angkatan</th><td><?= $user->getAngkatan() ?></td></tr>
                        <tr><th>Status</th><td>Aktif</td></tr>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function filterBooks() {
            let input = document.getElementById('searchBook');
            let filter = input.value.toUpperCase();
            let cards = document.getElementsByClassName('search-item');
            for (let i = 0; i < cards.length; i++) {
                let title = cards[i].querySelector('.book-title').innerText;
                let author = cards[i].querySelector('.book-author').innerText;
                if (title.toUpperCase().indexOf(filter) > -1 || author.toUpperCase().indexOf(filter) > -1) {
                    cards[i].style.display = "";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }
    </script>
    <?php endif; ?>
</body>
</html>