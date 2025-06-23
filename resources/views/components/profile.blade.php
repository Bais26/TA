<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description"
        content="OneUI - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">
    <meta property="og:title" content="OneUI - Bootstrap 5 Admin Template &amp; UI Framework">
    <meta property="og:site_name" content="OneUI">
    <meta property="og:description"
        content="OneUI - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
    <!-- END Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" id="css-main" href="assets/css/oneui.min.css">
</head>

@extends('layout')

@section('content')
    @include('components.navbar')

    <main id="main-container">
        <div class="content content-boxed">

            <!-- User Profile -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">User Profile</h3>
                </div>
                <div class="block-content">
                    <form action="#" method="POST" enctype="multipart/form-data" onsubmit="return false;">
                        <div class="row push">
                            <!-- Avatar Column -->
                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    <img class="img-avatar img-avatar-thumb img-thumbnail"
                                        style="width: 150px; height: 150px;" src="assets/media/avatars/avatar13.jpg"
                                        alt="User Avatar">
                                </div>
                                <div class="mb-4">
                                    <label for="one-profile-edit-avatar" class="form-label">Change Avatar</label>
                                    <input class="form-control" type="file" id="one-profile-edit-avatar" name="avatar">
                                </div>
                            </div>

                            <!-- Profile Form Column -->
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label class="form-label" for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="Enter your username" value= {{Auth::user()->username}}>
                                </div>
                               
                                <div class="mb-4">
                                    <label class="form-label" for="email">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter your email address" value={{Auth::user()->email}}>
                                </div>
                                <div class="mb-4">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Update Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END User Profile -->

            <!-- Change Password -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Change Password</h3>
                </div>
                <div class="block-content">
                    <form action="#" method="POST" onsubmit="return false;">
                        <div class="row push">
                            <div class="col-md-4">
                                <p class="fs-sm text-muted">
                                    For better security, change your password periodically.
                                </p>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label class="form-label" for="current-password">Current Password</label>
                                    <input type="password" class="form-control" id="current-password"
                                        name="current-password" placeholder="Enter current password">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="new-password">New Password</label>
                                    <input type="password" class="form-control" id="new-password" name="new-password"
                                        placeholder="Enter new password">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="confirm-password">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm-password"
                                        name="confirm-password" placeholder="Confirm new password">
                                </div>
                                <div class="mb-4">
                                    <button type="submit" class="btn btn-secondary w-100">
                                        Change Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END Change Password -->

        </div>
    </main>
@endsection

</html>