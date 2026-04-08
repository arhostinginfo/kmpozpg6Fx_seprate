<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login — ग्रामपंचायत</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #0D3D47 0%, #0F5C7B 55%, #00BFC5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        /* Top GP brand badge */
        .gp-brand {
            text-align: center;
            margin-bottom: 24px;
        }
        .gp-brand img {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.3);
            object-fit: cover;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        .gp-brand h2 {
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            margin: 12px 0 2px;
            letter-spacing: -0.3px;
        }
        .gp-brand p {
            color: rgba(255,255,255,0.65);
            font-size: 13px;
            margin: 0;
        }

        /* Card */
        .login-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            padding: 36px 32px 28px;
        }

        .login-card h5 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #0D3D47;
            letter-spacing: -0.025em;
            margin-bottom: 6px;
        }
        .login-card .subtitle {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 28px;
        }

        /* Form */
        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 6px;
        }
        .form-control {
            border-radius: 8px;
            border: 1.5px solid #d1d8e0;
            font-size: 14px;
            padding: 10px 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
            color: #333;
        }
        .form-control:focus {
            border-color: #00BFC5;
            box-shadow: 0 0 0 3px rgba(0,191,197,0.15);
            outline: none;
        }
        .input-group .form-control {
            border-right: none;
        }
        .input-group-text {
            background: #fff;
            border: 1.5px solid #d1d8e0;
            border-left: none;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            color: #6c757d;
            transition: color 0.2s;
        }
        .input-group-text:hover { color: #1a73e8; }
        .input-group:focus-within .form-control,
        .input-group:focus-within .input-group-text {
            border-color: #1a73e8;
        }

        .btn-login {
            background: linear-gradient(135deg, #0F5C7B, #00BFC5);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            padding: 12px;
            width: 100%;
            transition: opacity 0.2s, box-shadow 0.2s;
            letter-spacing: 0.3px;
        }
        .btn-login:hover {
            opacity: 0.9;
            box-shadow: 0 6px 20px rgba(0,191,197,0.4);
            color: #fff;
        }

        .alert-danger {
            background: #fdecea;
            color: #c0392b;
            border: none;
            border-left: 4px solid #dc3545;
            border-radius: 8px;
            font-size: 13px;
            padding: 10px 14px;
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: rgba(255,255,255,0.5);
            font-size: 12px;
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <div class="gp-brand">
        <img src="{{ asset('asset/default.jpg') }}" alt="GP Logo">
        <h2>ग्रामपंचायत</h2>
        <p>Administration Panel</p>
    </div>

    <div class="login-card">
        <h5>Welcome Back</h5>
        <p class="subtitle">Sign in to your admin account</p>

        @if (session('error'))
            <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif

        <form id="loginform" method="POST" action="{{ route('superlogin') }}">
            @csrf

            <div class="mb-3">
                <label for="superemail" class="form-label">Username / Email</label>
                <input type="text" class="form-control" id="superemail" name="superemail"
                    placeholder="Enter username or email" value="{{ old('superemail') }}" autocomplete="username">
            </div>

            <div class="mb-4">
                <label for="superpassword" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="superpassword" name="superpassword"
                        placeholder="Enter password" autocomplete="current-password">
                    <span class="input-group-text" id="togglePassword">
                        <i class="mdi mdi-eye" id="toggleIcon"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="mdi mdi-login me-1"></i> Sign In
            </button>
        </form>
    </div>

    <div class="login-footer">
        &copy; {{ date('Y') }} ग्रामपंचायत Admin Panel
    </div>

</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const pwd = document.getElementById('superpassword');
        const icon = document.getElementById('toggleIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.replace('mdi-eye', 'mdi-eye-off');
        } else {
            pwd.type = 'password';
            icon.classList.replace('mdi-eye-off', 'mdi-eye');
        }
    });
</script>

</body>
</html>
