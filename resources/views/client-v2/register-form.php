<div class="main main-login container-fluid mt-5">
    <form action="" class="w-50 p-4">
        <h2 class="title text-center mb-5">Register</h2>
        <div class="form-group">
            <label for="exampleInputEmail1">Username</label>
            <input type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Password</label>
            <input type="password" class="form-control">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Password verify</label>
            <input type="password" class="form-control">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Email</label>
            <input type="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Name</label>
            <input type="text" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-3 mb-5">Register</button>
        <div class="mt-5 text-center">Have account? <a href="<?= base_url("client-v2/login") ?>">Login</a></div>
    </form>
</div>