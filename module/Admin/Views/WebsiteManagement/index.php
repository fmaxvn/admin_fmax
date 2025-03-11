<h2>Danh sách website</h2>

<div class="d-flex justify-content-between mb-4">
    <!-- <div>
        <form method="GET" action="/admin/website-management/index" class="d-flex">
            <input type="text" class="form-control me-2" placeholder="Tìm kiếm domain..." name="search" value="<?= htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <select name="sort" class="form-select me-2" style="width: 150px;">
                <option value="desc" <?= (($_GET['sort'] ?? 'desc') == 'desc') ? 'selected' : '' ?>>Mới nhất</option>
                <option value="asc" <?= (($_GET['sort'] ?? '') == 'asc') ? 'selected' : '' ?>>Cũ nhất</option>
            </select>
            <button type="submit" class="btn btn-primary">Tìm</button>
        </form>
    </div> -->
</div>
<div class="container-fluid px-0">
    <!-- AG data table -->
    <div class="box-table dragon"
        style="width: auto; height: 100%; overflow-x: auto; cursor: grab; cursor : -o-grab; cursor : -moz-grab; cursor : -webkit-grab;">
        <?php include('components/table.phtml') ?>
    </div>
    <!--End AG data table -->
</div>


<!-- Phân trang -->
<?php //echo $pagination; 
?>