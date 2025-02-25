<nav class="navbar navbar-expand-lg navbar-light bg-light my-nav">
    <a class="navbar-brand" href="#">
        <img style="width: 50px; height: 50px;" src="<?= base_url() . site_v2("SETTING_LOGO") ?>" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="<?= base_url("client-v2/home")  ?>">Home</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                    Accounts
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">LOL</a>
                    <a class="dropdown-item" href="#">Roblox</a>
                    <a class="dropdown-item" href="#">FO4</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">About Us</a>
            </li>
        </ul>
        <form class="form-inline">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
        <span class="navbar-text"><a class="btn btn-primary ml-2 text-white" href="<?= base_url("client-v2/login") ?>">Sign Up/Login</a></span>
    </div>
</nav>