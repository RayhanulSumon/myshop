<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Personal Shop Inventory System">

    <!--  CSS -->
    <link rel="stylesheet" href="{{asset('/css/app.css')}}">
    <meta name="csrf-token" content="{{csrf_token()}}">

    <title>My Shop</title>
    @laravelPWA

</head>
<body>
<div class="wrapper" id="app">

    <div class="container-fluid fixed-top bg-dark pt-3" id="topbar">
        <span class="h5">@auth{{Auth::user()->shop_name}}@endauth</span>
        <span class="float-right">
            <div class="dropdown">
                <div class="btn-group">
                  <button class="btn btn-dark btn-sm " type="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                        <i class="fas fa-angle-down"></i>
                 </button>
                  <div class="dropdown-menu" style="background-color:#f4f4f4">
                      @can('isAdmin')
                      <router-link to="/edit-profile" class="dropdown-item" style="background-color:#008891;color:white">
                        <i class="fas fa-edit" ></i>   Edit Profile
                      </router-link>
                     <a class="dropdown-item" style="background-color:#d68060;color:white" href="#">
                         <i class="fas fa-users-cog"></i>  Seller Manage</a>
                      <div class="dropdown-divider"></div>
                      @endcan
                     <a href="{{route('logout')}}" class="dropdown-item" style="background-color:#ec4646;color:white">
                       <i class="fas fa-sign-out-alt"></i>   Logout
                     </a>
                  </div>
                </div>
           </div>
        </span>
    </div>

    <div class="container-fluid mt-4 mb-5">
        <router-view></router-view>
        <!-- set progressbar -->
        <vue-progress-bar></vue-progress-bar>
    </div><!-- /.container-fluid -->

    <nav class="navbar fixed-bottom navbar-dark bg-dark">
        <form class="form-inline">

            <router-link to="/products" class="nav-link btn btn-outline-success">
                <i class="fas fa-tasks"></i>  PRODUCTS
            </router-link>

            <router-link to="/sell-report" class="nav-link btn btn-outline-success">
                <i class="fas fa-cart-plus"></i>  HISTORY
            </router-link>
        </form>
    </nav>

</div>
 @auth
     <script>
         window.user = @json(auth()->user());
     </script>

 @endauth
<!-- JavaScript -->
<script src="{{asset('/js/app.js')}}"></script>
</body>

</html>

