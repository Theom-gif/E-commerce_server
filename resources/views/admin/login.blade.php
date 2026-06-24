<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form action="/admin/login" method="POST" class="card">
      @csrf
      <h1>Admin Login</h1>
      <p>Sign in to your account</p>

      @if($errors->any())
        <div class="alert error">{{ $errors->first('email') }}</div>
      @endif

      <label>Email</label>
      <input type="email" name="email" value="{{ old('email') }}" required autofocus>

      <label>Password</label>
      <input type="password" name="password" required>

      <button type="submit" class="btn btn-primary">Login</button>
    </form>
  </div>
</body>
</html>
