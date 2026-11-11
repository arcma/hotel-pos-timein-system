<style>
	.table_content{
		border: 1px solid #c2c2c2;
		background-color: #fff;
		border-radius: 10px;
		display: inline-block;
		padding: 10px 5%;
	}
	.list_back{
		display: flex;
		justify-content: right;
	}
</style>
<div class="table_content container-fluid" >
	<h1 class="mt-3">Edit Table</h1>

	<div class="list_back mt-3">
		<a href="<?=base_url('pos/tables')?>" class="btn btn-primary btn-sm">
			<i class="fa-solid fa-less-than"></i>&nbsp;
			Back to List
		</a>
	</div>
	
	<form class="mt-3" action="<?=base_url('pos/tables/do_edit/').$table->tbID?>" method="POST">
<div class="mb-3">
            <label for="input_category" class="form-label">Table Category</label>
            <?php if (!empty($table->tbRoomID)): ?>
                <div class="form-control bg-light" aria-readonly="true">Hotel (managed through Rooms)</div>
                <small class="text-muted">This table is linked to a room. Update its name and category from the Rooms page.</small>
            <?php else: ?>
                <select class="form-select" id="input_category" name="category" required>
                    <option value="1" <?php if ((int)$table->tbCategory === 1) echo 'selected'; ?>>Restaurant</option>
                    <option value="2" <?php if ((int)$table->tbCategory === 2) echo 'selected'; ?>>Cafe Shop</option>
                </select>
                <?php if ((int)$table->tbCategory === 3): ?>
                    <small class="text-danger">This is an unlinked legacy Hotel table. Please contact an administrator to link it to a room before editing its category.</small>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="mb-3">
			<label for="input_name" class="form-label">Name</label>
			<input type="text" class="form-control" id="input_name" name="name" placeholder="Enter table name" value="<?=html_escape($table->tbName)?>" <?=(!empty($table->tbRoomID) || (int)$table->tbCategory === 3) ? 'readonly' : ''?> required>
		</div>
		<div class="mb-3">
			<label for="input_sort_order" class="form-label">Sort order</label>
			<input type="number" class="form-control" id="input_sort_order" name="order" placeholder="Enter sort order" value="<?=$table->tbSortOrder?>" required>
		</div>

		
		<button type="submit" class="btn btn-primary">Update</button>
	</form>
</div>
<script>
(function(){
    var category = document.getElementById('input_category');
    var name = document.getElementById('input_name');
    if (!category || category.disabled) return;
    var initialName = name.value;
    var submit = document.querySelector('form button[type="submit"]');
    category.addEventListener('change', function(){
        var hotel = category.value === '3';
        name.readOnly = hotel;
        if (hotel) name.value = '';
        else if (name.value === '') name.value = initialName;
        if (submit) submit.disabled = hotel;
    });
})();
</script>