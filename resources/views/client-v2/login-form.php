<div class="main main-login container-fluid mt-5">
    <form action="" class="w-50 p-4">
        <h2 class="title text-center mb-5">Login</h2>
        <div class="form-group">
            <label for="exampleInputEmail1">Username</label>
            <input type="text" class="form-control">
        </div>
        <div class="form-group">
            <div class="d-flex justify-content-between">
                <label for="exampleInputEmail1">Password</label>
                <a href="">Forgot password?</a>
            </div>
            <input type="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-3 mb-5">Login</button>
        <div class="mt-5 text-center">Don't have account? <a href="<?= base_url("client-v2/register") ?>">Sign up</a></div>
    </form>
</div>