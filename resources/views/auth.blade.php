
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>My Shop | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Personal Shop Inventory System">
    <link rel="stylesheet" href="{{asset('/css/app.css')}}">
    @laravelPWA
</head>
<body class="hold-transition login-page">
<div id="app">
<login></login>
</div>
<script src="{{asset('/js/app.js')}}"></script>
</body>
</html>
