<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark m-3">
  <!-- Container wrapper -->
  <div class="container-fluid">
    <!-- Toggle button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
      aria-label="Toggle navigation">
      <i class="fas fa-bars text-light"></i>
    </button>

    <!-- Collapsible wrapper -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left links -->
      <ul class="navbar-nav me-auto d-flex flex-row mt-3 mt-lg-0">
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link active" aria-current="page" href="index.php">
            <div>
              <i class="fas fa-home fa-lg mb-1"></i>
            </div>
            Trang chủ
          </a>
        </li>
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="about.php">
            <div>
              <i class="fas fa-info-circle fa-lg mb-1"></i>
            </div>
            Giới thiệu
          </a>
        </li>

        <!-- Dropdown for Categories -->
        <li class="nav-item dropdown text-center mx-2 mx-lg-1">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <div>
              <i class="fas fa-list fa-lg mb-1"></i>
            </div>
            Category
          </a>
          <!-- Dropdown menu -->
          <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
            <form action="index.php" method="get">
              <?php
              $sql = "SELECT * FROM category";
              $result = mysqli_query($conn, $sql);
              while ($row = mysqli_fetch_array($result)) {
                  if (!isset($_SESSION['user'])) {
                      // Nếu người dùng chưa đăng nhập, chuyển hướng đến trang login
                      echo '<li><a class="dropdown-item" href="login.php?redirect=' . urlencode("index.php?category=" . $row['id']) . '">' . $row['name'] . '</a></li>';
                  } else {
                      // Nếu đã đăng nhập, cho phép chọn phân loại
                      echo '<li><button class="dropdown-item" type="submit" name="category" value="' . $row['id'] . '">' . $row['name'] . '</button></li>';
                  }
              }
              ?>
            </form>
          </ul>
        </li>

        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="cart.php">
            <div>
              <i class="fas fa-shopping-cart fa-lg mb-1"></i>
            </div>
            Giỏ hàng
          </a>
        </li>

        <!-- Đơn hàng -->
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="myaccount.php">
            <div>
              <i class="fas fa-box fa-lg mb-1"></i>
            </div>
            Đơn hàng
          </a>
        </li>

        <?php
        if (isset($_SESSION['user']) && $_SESSION['user'] == 'thanhkingchess') {
            echo '
            <li class="nav-item text-center mx-2 mx-lg-1">
              <a class="nav-link" href="add-product.php">
                <div>
                  <i class="fas fa-plus-circle fa-lg mb-1"></i>
                </div>
                Thêm sản phẩm
              </a>
            </li>';
        }
        ?>
      </ul>
      <!-- Left links -->

      <!-- Right links -->
      <ul class="navbar-nav ms-auto d-flex flex-row mt-3 mt-lg-0">
        <!-- Form tìm kiếm -->
        <li class="nav-item text-center mx-2 mx-lg-1">
          <form class="d-flex" action="yourpage.php" method="get">
            <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm sản phẩm..." aria-label="Search">
            <button class="btn btn-outline-light" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </form>
        </li>

        <?php
        if (isset($_SESSION['user']) && $_SESSION['user'] != "") {
            echo '
            <li class="nav-item text-center mx-2 mx-lg-1">
              <a class="nav-link" href="profile.php">
                <div>
                  <i class="fas fa-user fa-lg mb-1"></i>
                </div>
                ' . $_SESSION['user'] . '
              </a>
            </li>
            <li class="nav-item text-center mx-2 mx-lg-1">
              <a class="nav-link" href="logout.php">
                <div>
                  <i class="fas fa-sign-out-alt fa-lg mb-1"></i>
                </div>
                Đăng xuất
              </a>
            </li>';
        } else {
            echo '
            <li class="nav-item text-center mx-2 mx-lg-1">
              <a class="nav-link" href="register.php">
                <div>
                  <i class="fas fa-user-plus fa-lg mb-1"></i>
                </div>
                Đăng ký
              </a>
            </li>
            <li class="nav-item text-center mx-2 mx-lg-1">
              <a class="nav-link" href="login.php">
                <div>
                  <i class="fas fa-sign-in-alt fa-lg mb-1"></i>
                </div>
                Đăng nhập
              </a>
            </li>';
        }
        ?>
      </ul>
      <!-- Right links -->
    </div>
    <!-- Collapsible wrapper -->
  </div>
  <!-- Container wrapper -->
</nav>
<!-- Navbar -->
