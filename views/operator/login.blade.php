<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login as Technician</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:ital,wght@0,400;0,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/operator.css') }}">

    <style> 
        div.login-form{
            position: absolute;
            top: 50%;   
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="login-form text-center col-md-5 shadow overflow-hidden">
        <form action="{{ route('operator.login.post') }}" method="POST">
            @csrf
            <h4 class="text-white py-3 custom-bg">LOGIN AS TECHNICIAN</h4>
            <div class="p-4">
                <div class="mb-3">
                    <input name="operatorEmail" required type="text" class="form-control shadow-none text-center" placeholder="Technician Email">
                </div>
                <div class="mb-4">
                    <input name="serialNumber" required type="password" class="form-control shadow-none text-center" placeholder="Serial Number">
                </div>
                <button name="Login" type="submit" class="btn text-white custom-bg shadow-none">Login</button>
            </div>
        </form>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
            <strong class="me-3">{{ session('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>