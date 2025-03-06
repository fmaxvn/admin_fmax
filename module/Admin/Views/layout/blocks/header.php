<style>
/* Header */
header {
    background: #007bff;
    color: white;
    padding: 10px 15px;
    font-size: 14px;
}

.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

/* User Menu */
.user-menu {
    display: flex;
    align-items: center;
    cursor: pointer;
    position: relative;
    font-size: 14px;
}

.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    margin-right: 8px;
    float: left; /* ✅ Căn trái avatar */
}

/* User Info */
.user-info {
    float: left; /* ✅ Căn trái thông tin */
    line-height: 35px; /* ✅ Căn giữa theo chiều cao avatar */
}

/* Dropdown Menu */
.dropdown-menu {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    background: white;
    color: black;
    padding: 8px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    width: 160px;
    font-size: 13px;
}

.dropdown-menu a {
    display: block;
    padding: 5px 8px;
    text-decoration: none;
    color: black;
}

.dropdown-menu a:hover {
    background: #ddd;
}

.dropdown-menu .logout {
    color: red;
}

/* Responsive */
@media (max-width: 768px) {
    header {
        font-size: 12px;
        padding: 8px;
    }

    .user-menu {
        font-size: 12px;
    }

    .dropdown-menu {
        width: 140px;
        font-size: 12px;
    }
}

</style>
<header>
    <div class="header-container">
        <strong>Quản lý Fmax</strong>

        <div class="user-menu" onclick="toggleMenu()">
            <img src="<?= URL_ASSETS ?>/images/images.png" alt="Avatar" class="user-avatar">
            <span> Phạm Hồng Hóa</span>
            ▼
        </div>
        
        <div id="userDropdown" class="dropdown-menu">
            <a href="<?= BASE_URL ?>/admin/user/profile">👤 Thay đổi thông tin</a>
            <a href="<?= BASE_URL ?>/admin/user/change_password">🔑 Đổi mật khẩu</a>
            <a href="<?= BASE_URL ?>/admin/auth/logout" class="logout">🚪 Đăng xuất</a>
        </div>
    </div>
</header>
<script>
    function toggleMenu() {
        var menu = document.getElementById("userDropdown");
        menu.style.display = (menu.style.display === "block") ? "none" : "block";
    }
    
    // Đóng menu khi click ra ngoài
    document.addEventListener("click", function (event) {
        var menu = document.getElementById("userDropdown");
        var userMenu = document.querySelector(".user-menu");
    
        if (!userMenu.contains(event.target) && !menu.contains(event.target)) {
            menu.style.display = "none";
        }
    });
</script>