<?php
session_start();
require_once __DIR__ . '/../config/config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Fungsi untuk mendapatkan semua tugas
function getTasks($db) {
    try {
        $stmt = $db->query("SELECT * FROM tasks ORDER BY due_date ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching all tasks: " . $e->getMessage());
        return [];
    }
}

// Fungsi untuk mendapatkan satu tugas berdasarkan ID
function getTask($db, $id) {
    $stmt = $db->prepare("SELECT * FROM tasks WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fungsi untuk menambahkan tugas baru
function addTask($db, $task, $due_date) {
    $stmt = $db->prepare("INSERT INTO tasks (task, due_date) VALUES (:task, :due_date)");
    $stmt->execute(['task' => $task, 'due_date' => $due_date]);
}

// Fungsi untuk mengedit tugas
function editTask($db, $id, $task, $due_date) {
    $stmt = $db->prepare("UPDATE tasks SET task = :task, due_date = :due_date WHERE id = :id");
    $stmt->execute(['id' => $id, 'task' => $task, 'due_date' => $due_date]);
}

// Fungsi untuk menghapus tugas
function deleteTask($db, $id) {
    $stmt = $db->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_task'])) {
        addTask($db, $_POST['task'], $_POST['due_date']);
    } elseif (isset($_POST['edit_task'])) {
        editTask($db, $_POST['task_id'], $_POST['task'], $_POST['due_date']);
    } elseif (isset($_POST['delete_task'])) {
        deleteTask($db, $_POST['task_id']);
    }
    header('Location: tasks.php');
    exit;
}

$tasks = getTasks($db);

// Jika ada permintaan edit, ambil data tugas yang akan diedit
$editingTask = null;
if (isset($_GET['edit'])) {
    $editingTask = getTask($db, $_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Tugas - Posyandu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .jumbotron {
            background: linear-gradient(135deg, #007bff, #6610f2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 3rem 1rem;
            margin-bottom: 2rem;
            border-radius: 0;
        }
        .display-4 {
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            animation: fadeInDown 1s ease-out;
        }
        .lead {
            animation: fadeInUp 1s ease-out 0.5s;
            animation-fill-mode: both;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .welcome-section {
            background: linear-gradient(135deg, #ffffff, #f3f3f3);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            transition: all 0.3s ease;
        }
        .welcome-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .welcome-text {
            animation: fadeInUp 1s ease-out;
            color: #333;
        }
        .section-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }
        .section-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .card-title {
            color: #007bff;
            font-weight: 600;
        }
        .card-text {
            color: #6c757d;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/../includes/header_dashboard.php'; ?>

  
        <div class="jumbotron text-white text-center py-5 mb-4">
            <h1 class="display-4">Manajemen Tugas Posyandu</h1>
            <p class="lead">Kelola tugas-tugas admin Posyandu dengan efisien</p>
        </div>
        
        <div class="container mt-4">
        <div class="section-card mb-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tambah Tugas Baru</h5>
                    <form method="POST" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="task" class="form-control" placeholder="Tugas baru" value="<?= $editingTask ? htmlspecialchars($editingTask['task']) : '' ?>" required>
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="due_date" class="form-control" value="<?= $editingTask ? $editingTask['due_date'] : '' ?>" required>
                            </div>
                            <div class="col-md-2">
                                <?php if ($editingTask): ?>
                                    <input type="hidden" name="task_id" value="<?= $editingTask['id'] ?>">
                                    <button type="submit" name="edit_task" class="btn btn-warning w-100">Update Tugas</button>
                                <?php else: ?>
                                    <button type="submit" name="add_task" class="btn btn-primary w-100">Tambah Tugas</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Daftar Tugas</h5>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tugas</th>
                                <th>Tenggat Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?= htmlspecialchars($task['task']) ?></td>
                                <td><?= htmlspecialchars($task['due_date']) ?></td>
                                <td>
                                    <a href="?edit=<?= $task['id'] ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                        <button type="submit" name="delete_task" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>