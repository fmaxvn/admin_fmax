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
    <a href="/admin/apps-market/add" class="btn btn-success">Thêm mới</a>
</div>
<div class="container-fluid px-0">
    <!-- AG data table -->
    <div class="box-table dragon"
        style="width: auto; height: 100%; overflow-x: auto; cursor: grab; cursor : -o-grab; cursor : -moz-grab; cursor : -webkit-grab;">
        <?php include('components/table.phtml') ?>
    </div>
    <!--End AG data table -->
</div>