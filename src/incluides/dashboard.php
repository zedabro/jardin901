<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$servername = "bvz7k1ksxz11rnbj0lj8-mysql.services.clever-cloud.com";
$username = "uhtj264qooomsf9c";
$password = "npnnZIsTN3dTbpbujEWD";
$database = "bvz7k1ksxz11rnbj0lj8";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT nombre, jerarquia_id FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($nombre_usuario, $jerarquia_id);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Error al preparar la consulta: " . $conn->error);
}
?>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
:root {
  --white-color: #fff;
  --blue-color: #4070f4;
  --grey-color: #707070;
  --grey-color-light: #aaa;
}
body {
  background-color: #e7f2fd;
  transition: all 0.5s ease;
  overflow-x: hidden;

}
body.dark {
  background-color: #333;
}
body.dark {
  --white-color: #333;
  --blue-color: #fff;
  --grey-color: #f2f2f2;
  --grey-color-light: #aaa;
}

/* navbar */
.navbar {
  position: fixed;
  top: 0;
  width: 100%;
  left: 0;
  background-color: var(--white-color);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 15px 30px;
  z-index: 1000;
  box-shadow: 0 0 2px var(--grey-color-light);
}
.logo_item {
  display: flex;
  align-items: center;
  column-gap: 10px;
  font-size: 22px;
  font-weight: 500;
  color: var(--blue-color);
}
.navbar img {
  width: 35px;
  height: 35px;
  object-fit: cover;
  border-radius: 50%;
}
.navbar_content {
  display: flex;
  align-items: center;
  column-gap: 25px;
}
.navbar_content i {
  cursor: pointer;
  font-size: 20px;
  color: var(--grey-color);
}

/* sidebar */
.sidebar {
  background-color: var(--white-color);
  width: 260px;
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  padding: 80px 20px;
  z-index: 100;
  overflow-y: scroll;
  box-shadow: 0 0 1px var(--grey-color-light);
  transition: all 0.5s ease;
}
.sidebar.close {
  padding: 60px 0;
  width: 80px;
}
.sidebar::-webkit-scrollbar {
  display: none;
}
.menu_content {
  position: relative;
}
.menu_title {
  margin: 15px 0;
  padding: 0 20px;
  font-size: 18px;
}
.sidebar.close .menu_title {
  padding: 6px 30px;
}
.menu_title::before {
  color: var(--grey-color);
  white-space: nowrap;
}
.menu_dahsboard::before {
  content: "Dashboard";
}
.menu_editor::before {
  content: "Editor";
}
.menu_setting::before {
  content: "Setting";
}
.sidebar.close .menu_title::before {
  content: "";
  position: absolute;
  height: 2px;
  width: 18px;
  border-radius: 12px;
  background: var(--grey-color-light);
}
.menu_items {
  padding: 0;
  list-style: none;
}
.navlink_icon {
  position: relative;
  font-size: 22px;
  min-width: 50px;
  line-height: 40px;
  display: inline-block;
  text-align: center;
  border-radius: 6px;
}
.navlink_icon::before {
  content: "";
  position: absolute;
  height: 100%;
  width: calc(100% + 100px);
  left: -20px;
}
.navlink_icon:hover {
  background: var(--blue-color);
}
.sidebar .nav_link {
  display: flex;
  align-items: center;
  width: 100%;
  padding: 4px 15px;
  border-radius: 8px;
  text-decoration: none;
  color: var(--grey-color);
  white-space: nowrap;
}
.sidebar.close .navlink {
  display: none;
}
.nav_link:hover {
  color: var(--white-color);
  background: var(--blue-color);
}
.sidebar.close .nav_link:hover {
  background: var(--white-color);
}
.submenu_item {
  cursor: pointer;
}
.submenu {
  display: none;
}
.submenu_item .arrow-left {
  position: absolute;
  right: 10px;
  display: inline-block;
  margin-right: auto;
}
.sidebar.close .submenu {
  display: none;
}
.show_submenu ~ .submenu {
  display: block;
}
.show_submenu .arrow-left {
  transform: rotate(90deg);
}
.submenu .sublink {
  padding: 15px 15px 15px 52px;
}
.bottom_content {
  position: fixed;
  bottom: 60px;
  left: 0;
  width: 260px;
  cursor: pointer;
  transition: all 0.5s ease;
}
.bottom {
  position: absolute;
  display: flex;
  align-items: center;
  left: 0;
  justify-content: space-around;
  padding: 18px 0;
  text-align: center;
  width: 100%;
  color: var(--grey-color);
  border-top: 1px solid var(--grey-color-light);
  background-color: var(--white-color);
}
.bottom i {
  font-size: 20px;
}
.bottom span {
  font-size: 18px;
}
.sidebar.close .bottom_content {
  width: 50px;
  left: 15px;
}
.sidebar.close .bottom span {
  display: none;
}
.sidebar.hoverable .collapse_sidebar {
  display: none;
}
#sidebarOpen {
  display: none;
}
#content{
            padding-left:6%;
        }
@media screen and (max-width: 768px) {
  #sidebarOpen {
    font-size: 25px;
    display: block;
    margin-right: 10px;
    cursor: pointer;
    color: var(--grey-color);
  }
  .sidebar.close {
    left: -100%;
  }
  .sidebar.close .bottom_content {
    left: -100%;
  }
  #content{
            padding-left:0%;
    }
}
    </style>
  </head>
  <body>
    <nav class="navbar">
      <div class="logo_item">
        <i class="bx bx-menu" id="sidebarOpen"></i>
        <span>Mi jardin</span>
      </div>
    </nav>

    <nav class="sidebar close">
      <div class="menu_content">
        <ul class="menu_items">
          <div class="menu_title menu_dashboard"></div>
          <?php if ($jerarquia_id == 1): ?>
          <li class="item">
            <a href="../src/index.php" class="nav_link">
              <span class="navlink_icon">
                <i class="bx bx-home"></i>
                <!-- <i class="bx bx-tachometer"></i> -->
              </span>
              <span class="navlink">Inicio</span>
            </a>
          </li>
          <li class="item">
            <a href="../src/pedagogico.php" class="nav_link">
              <span class="navlink_icon">
                <i class="bx bx-upload"></i>
              </span>
              <span class="navlink">Cargar Contenido</span>
            </a>
          </li>
          <li class="item">
            <a href="../src/vercontenido.php" class="nav_link">
              <span class="navlink_icon">
                <i class="bx bx-folder-open"></i>
              </span>
              <span class="navlink">Cont.Pedagogico</span>
            </a>
          </li>
          <li class="item">
            <a href="../src/crear_actividad.php" class="nav_link">
              <span class="navlink_icon">
                <i class="bx bx-book"></i>
              </span>
              <span class="navlink">Noticias</span>
            </a>
          </li>
          <li class="item">
            <a href="../src/calendario.php" class="nav_link">
              <span class="navlink_icon">
                <i class="bx bx-calendar"></i>
              </span>
              <span class="navlink">Calendario</span>
            </a>
          </li>
          <li class="item">
            <a href="../src/salas.php" class="nav_link">
              <span class="navlink_icon">
                <i class="bx bx-door-open"></i>
              </span>
              <span class="navlink">Salas</span>
            </a>
          </li>
          <li class="item">
            <a href="../src/usuarios.php" class="nav_link">
              <span class="navlink_icon">
                <i class="bx bx-user-plus"></i>
              </span>
              <span class="navlink">Usuarios</span>
            </a>
          </li>
          <?php elseif ($jerarquia_id == 3): ?>
          <li class="item">
            <a href="../src/index.php" class="nav_link">
              <span class="navlink_icon">
                <i class="bx bx-home"></i>
              </span>
              <span class="navlink">Panel</span>
            </a>
          </li>
          <li class="item">
            <a href="../src/salamaestro.php" class="nav_link">
              <span class="navlink_icon">
                <i class="bx bx-door-open"></i>
              </span>
              <span class="navlink">Salas</span>
            </a>
          </li>
          <li class="item">
            <a href="../src/vercontenido.php" class="nav_link">
              <span class="navlink_icon">
                <i class="bx bx-book"></i>
              </span>
              <span class="navlink">Cont.Pedagogico</span>
            </a>
          </li>
          <?php endif; ?>
          
          <li class="item">
            <a href="../src/scriptsphp/logout.php" class="nav_link">
              <span class="navlink_icon">
                <i class="bx bx-log-out"></i>
              </span>
              <span class="navlink">Cerrar sesion</span>
            </a>
          </li>
        </ul>
        <div class="bottom_content">
          <div class="bottom expand_sidebar">
            <span>Mantener desplegado</span>
            <i class='bx bx-log-in'></i>
          </div>
          <div class="bottom collapse_sidebar">
            <span>Cerrar menu</span>
            <i class='bx bx-log-out'></i>
          </div>
        </div>
      </div>
    </nav>
    <script>
  const body = document.querySelector("body");
  const darkLight = document.querySelector("#darkLight");
  const sidebar = document.querySelector(".sidebar");
  const submenuItems = document.querySelectorAll(".submenu_item");
  const sidebarOpen = document.querySelector("#sidebarOpen");
  const sidebarClose = document.querySelector(".collapse_sidebar");
  const sidebarExpand = document.querySelector(".expand_sidebar");

  sidebarOpen.addEventListener("click", () => sidebar.classList.toggle("close"));

  sidebarClose.addEventListener("click", () => {
    sidebar.classList.add("close", "hoverable");
  });
  sidebarExpand.addEventListener("click", () => {
    sidebar.classList.remove("close", "hoverable");
  });

  sidebar.addEventListener("mouseenter", () => {
    if (sidebar.classList.contains("hoverable") && !isTouchDevice()) {
      sidebar.classList.remove("close");
    }
  });
  sidebar.addEventListener("mouseleave", () => {
    if (sidebar.classList.contains("hoverable") && !isTouchDevice()) {
      sidebar.classList.add("close");
    }
  });

  darkLight.addEventListener("click", () => {
    body.classList.toggle("dark");
    if (body.classList.contains("dark")) {
      darkLight.classList.replace("bx-sun", "bx-moon");
    } else {
      darkLight.classList.replace("bx-moon", "bx-sun");
    }
  });

  submenuItems.forEach((item) => {
    item.addEventListener("click", () => {
      item.classList.toggle("show_submenu");
      submenuItems.forEach((item2) => {
        if (item !== item2) {
          item2.classList.remove("show_submenu");
        }
      });
    });
  });

  const adjustSidebar = () => {
    if (window.innerWidth < 768) {
      sidebar.classList.add("close");
    } else {
      sidebar.classList.remove("close");
    }
  };

  window.addEventListener("resize", adjustSidebar);
  adjustSidebar();

  function isTouchDevice() {
    return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
  }
</script>
  </body>
</html>
