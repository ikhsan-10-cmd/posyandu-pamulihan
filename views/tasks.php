<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
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
    <title>Manajemen Balita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        .jumbotron {
            background: linear-gradient(45deg, #007bff, #6610f2);
            color: #ffffff;
            padding: 2.5rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        .jumbotron h1 {
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            animation: slideInRight 1s ease-out;
        }
        .jumbotron p {
            font-size: 1.25rem;
            animation: slideInRight 1s ease-out 0.3s;
            animation-fill-mode: both;
        }
        .icon-container {
            font-size: 4rem;
            color: #ffffff;
            animation: fadeInUp 1.5s ease-out;
        }
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

<div class="jumbotron">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <div class="icon-container">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
            <div class="col-md-10">
                <h1 class="display-4">Tugas Admin</h1>
                <p class="lead">Sistem Informasi Pengelolaan Data Balita</p>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle dengan Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<form method="POST" class="mb-4">
    <div class="row">
        <div class="col-md-5">
            <input type="text" name="task" class="form-control" placeholder="Tugas baru" value="<?= $editingTask ? htmlspecialchars($editingTask['task']) : '' ?>" required>
        </div>
        <div class="col-md-4">
            <input type="date" name="due_date" class="form-control" value="<?= $editingTask ? $editingTask['due_date'] : '' ?>" required>
        </div>
        <div class="col-md-3">
            <?php if ($editingTask): ?>
                <input type="hidden" name="task_id" value="<?= $editingTask['id'] ?>">
                <button type="submit" name="edit_task" class="btn btn-warning">Update Tugas</button>
                <a href="tasks.php" class="btn btn-secondary">Batal</a>
            <?php else: ?>
                <button type="submit" name="add_task" class="btn btn-primary">Tambah Tugas</button>
            <?php endif; ?>
        </div>
    </div>
</form>

<table class="table">
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
                <a href="?edit=<?= $task['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                    <button type="submit" name="delete_task" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>