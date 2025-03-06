<style>
/* ====== Global Styles ====== */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1100px;
}

/* ====== Card Styles ====== */
.card {
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

.card-body {
    text-align: center;
    padding: 25px;
}

.card-title {
    font-size: 20px;
    font-weight: bold;
}

.card-text {
    font-size: 28px;
    font-weight: bold;
}

/* ====== Logout Button ====== */
.logout-btn {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    background-color: #dc3545;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    transition: background 0.3s ease;
}

.logout-btn:hover {
    background-color: #c82333;
}
</style>

<div class="container mt-5">

    <h2 class="text-center">Dashboard Quản trị</h2>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tổng số thành viên</h5>
                    <p class="card-text fs-3">
                        <?php echo isset($data["total_users"]) ? $data["total_users"] : 0; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tổng số đơn hàng</h5>
                    <p class="card-text fs-3">
                        <?php echo isset($data["total_orders"]) ? $data["total_orders"] : 0; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tổng doanh thu</h5>
                    <p class="card-text fs-3">
                        <?php echo number_format($data["total_revenue"] ?? 0, 0, ',', '.'); ?> VNĐ
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
