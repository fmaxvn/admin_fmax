<style>
    /* Sidebar Styles */
    .sidebar {
        width: 300px;
        background: #ffffff;
        /* Nền trắng */
        color: #2c3e50;
        /* Màu chữ tối */
        padding: 15px;
        font-family: Arial, sans-serif;
        border-right: 1px solid #ddd;
        /* Thêm viền nhẹ bên phải */
    }

    .sidebar h3 {
        text-align: center;
        padding: 10px;
        margin-bottom: 10px;
        color: #333;
        /* Màu tiêu đề */
        border-bottom: 2px solid #ddd;
        /* Viền dưới nhẹ */
    }

    .menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .menu li {
        position: relative;
    }

    /* Màu chữ tối hơn để dễ đọc */
    .menu li a {
        display: block;
        padding: 12px;
        color: #2c3e50;
        text-decoration: none;
        font-size: 14px;
        border-radius: 5px;
        transition: background 0.3s ease-in-out;
    }

    /* .menu li a:hover {
        background: #f2f2f2;
    } */

    /* Submenu Styles */
    .has-submenu>a {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .has-submenu .arrow {
        font-size: 12px;
        transition: transform 0.3s ease-in-out;
    }

    .submenu {
        display: none;
        list-style: none;
        padding: 0;
        margin: 0;
        background: #f9f9f9;
        /* Nền xám nhạt */
        /* border-left: 3px solid #1abc9c; */
        /* Viền màu xanh nổi bật */
    }

    .submenu li a {
        padding-left: 30px;
        font-size: 13px;
    }

    /* Active State */
    .has-submenu.active>a {
        background: #1abc9c;
        color: #ffffff;
    }

    .has-submenu.active .arrow {
        transform: rotate(180deg);
    }

    /* Định dạng mũi tên màu tối để phù hợp nền sáng */
    .has-submenu .arrow {
        color: #2c3e50;
    }

    /* Hiệu ứng khi menu đang được chọn */
    .menu li a.current {
        border-bottom: 2px solid #1abc9c;
        /* color: #ffffff; */
        font-weight: bold;
        border-radius: 0;
    }

    /* Khi menu cấp 1 đang mở, giữ màu cho menu con */
    .has-submenu.active>a {
        border-bottom: 2px solid #1abc9c;
        border-radius: 0;
    }
</style>

<aside class="sidebar">
    <h3>Quản lý Fmax</h3>
    <ul class="menu">
        <li><a href="<?= BASE_URL ?>/admin/dashboard">🏠 Dashboard</a></li>

        <li class="has-submenu">
            <a href="#" class="d-flex justify-content-between">🌐 Quản lý Website <span class="arrow">▼</span></a>
            <ul class="submenu">
                <li><a href="<?= BASE_URL ?>/admin/website-management/index">🖥️ Quản lý Website</a></li>
                <li><a href="<?= BASE_URL ?>/admin/domain-extentions/index">🌍 Quản lý Domain mở rộng</a></li>
                <li><a href="<?= BASE_URL ?>/admin/platform-utilities/index">🛠️ Quản lý Tiện ích & Nền tảng</a></li>
                <li><a href="<?= BASE_URL ?>/admin/installed-utilities/index">🔌 Quản lý Tiện ích đã cài đặt</a></li>
            </ul>
        </li>

        <li class="has-submenu">
            <a href="#" class="d-flex justify-content-between">📦 Quản lý Đơn hàng & Thanh toán <span class="arrow">▼</span></a>
            <ul class="submenu">
                <li><a href="<?= BASE_URL ?>/admin/order/index">🛒 Quản lý Đơn hàng</a></li>
                <li><a href="<?= BASE_URL ?>/admin/payment-shipping/index">🚚 Quản lý Thanh toán & Shipping</a></li>
            </ul>
        </li>

        <li class="has-submenu">
            <a href="#" class="d-flex justify-content-between">👥 Quản lý Người dùng <span class="arrow">▼</span></a>
            <ul class="submenu">
                <li><a href="<?= BASE_URL ?>/admin/member/index">👤 Quản lý Member</a></li>
                <li><a href="<?= BASE_URL ?>/admin/user/index">👤 Quản lý user</a></li>
            </ul>
        </li>
    </ul>
</aside>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let submenuItems = document.querySelectorAll(".has-submenu > a");
        let currentUrl = window.location.href;

        submenuItems.forEach(item => {
            item.addEventListener("click", function(e) {
                e.preventDefault(); // Ngăn chặn reload khi click menu cấp 1

                let parent = this.parentElement;
                let submenu = parent.querySelector(".submenu");

                parent.classList.toggle("active");

                if (submenu.style.display === "block") {
                    submenu.style.display = "none";
                } else {
                    submenu.style.display = "block";
                }
            });
        });

        // Kiểm tra URL hiện tại và giữ trạng thái active cho menu
        let menuLinks = document.querySelectorAll(".menu a");
        menuLinks.forEach(link => {
            if (link.href === currentUrl) {
                link.classList.add("current");

                // Mở menu cha nếu là menu con
                let parentSubmenu = link.closest(".submenu");
                if (parentSubmenu) {
                    parentSubmenu.style.display = "block";
                    parentSubmenu.parentElement.classList.add("active");
                }
            }
        });
    });
</script>