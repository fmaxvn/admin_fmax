<style>
    .apps-market__image {
        width: 100px;
        height: 100px;
    }

    a {
        text-decoration: none;
        color: #000;
    }
</style>
<h2>Danh sách tài khoản</h2>

<div class="d-flex justify-content-end mb-4">
    <a id="exportLink" href="/admin/payment-shipping/export" class="btn btn-success">Xuất file</a>
</div>
<div class="container-fluid px-0">
    <!-- AG data table -->
    <div class="box-table dragon"
        style="width: auto; height: 100%; overflow-x: auto; cursor: grab; cursor : -o-grab; cursor : -moz-grab; cursor : -webkit-grab;">
        <?php include('components/table.phtml') ?>
    </div>
    <!--End AG data table -->
</div>

<script>
    function updateExportLink() {
        const searchParam = new URLSearchParams(window.location.search);
        let exportUrl = "/admin/payment-shipping/export";

        if (searchParam.toString()) {
            exportUrl += "?" + searchParam.toString();
        }

        document.getElementById("exportLink").setAttribute("href", exportUrl);
    }

    // ✅ Cập nhật đường dẫn ngay khi trang load để giữ đúng tham số hiện tại
    document.addEventListener("DOMContentLoaded", updateExportLink);
</script>