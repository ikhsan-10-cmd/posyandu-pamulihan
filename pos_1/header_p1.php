<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Posyandu Desa Pamulihan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .dashboard-container {
            flex: 1;
            padding: 2rem 0;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        .card-body {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        .card-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .btn-primary, .btn-success {
            transition: all 0.3s ease;
        }
        .btn-primary:hover, .btn-success:hover {
            transform: scale(1.05);
        }
        .header {
            background-color: #4e73df;
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 50px 50px;
        }
    </style>
</head>
<body>