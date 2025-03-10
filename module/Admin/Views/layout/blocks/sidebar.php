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
        <li>
            <a href="<?= BASE_URL ?>/admin/dashboard">
                <i class="me-2 ph-bold ph-house"></i> <span>Dashboard</span>
            </a>
        </li>

        <li class="has-submenu">
            <a href="#" class="d-flex justify-content-between align-items-center">
                <span><i class="me-2 ph-bold ph-globe"></i> Quản lý Website </span><span class="arrow">▼</span>
            </a>
            <ul class="submenu">
                <li><a href="<?= BASE_URL ?>/admin/website-management/index"><i class="me-2 ph-bold ph-monitor"></i> Quản lý Domain</a></li>
                <li><a href="<?= BASE_URL ?>/admin/domain-extentions/index"><i class="me-2 ph-bold ph-globe-hemisphere-west"></i> Quản lý Domain mở rộng</a></li>
                <li><a href="<?= BASE_URL ?>/admin/apps-market/index"><i class="me-2 ph-bold ph-wrench"></i> Quản lý Tiện ích & Nền tảng</a></li>
            </ul>
        </li>

        <li class="has-submenu">
            <a href="#" class="d-flex justify-content-between align-items-center">
                <span><i class="me-2 ph-bold ph-package"></i> Quản lý Đơn hàng & Thanh toán</span> <span class="arrow">▼</span>
            </a>
            <ul class="submenu">
                <li><a href="<?= BASE_URL ?>/admin/order/index"><i class="me-2 ph-bold ph-shopping-cart"></i> Quản lý Đơn hàng</a></li>
                <li><a href="<?= BASE_URL ?>/admin/payment-shipping/index"><i class="me-2 ph-bold ph-truck"></i> Quản lý Thanh toán & Shipping</a></li>
            </ul>
        </li>

        <li class="has-submenu">
            <a href="#" class="d-flex justify-content-between align-items-center">
                <span><i class="me-2 ph-bold ph-users"></i> Quản lý Người dùng</span> <span class="arrow">▼</span>
            </a>
            <ul class="submenu">
                <li><a href="<?= BASE_URL ?>/admin/member/index"><i class="me-2 ph-bold ph-user"></i> Quản lý Member</a></li>
                <li><a href="<?= BASE_URL ?>/admin/user/index"><i class="me-2 ph-bold ph-user-circle"></i> Quản lý user</a></li>
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