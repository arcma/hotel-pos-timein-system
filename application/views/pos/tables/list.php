<?php
	$action_td_width = 'style="width: 260px;"';
?>
<style>
	.table_content{
		border: 1px solid #c2c2c2;
		background-color: #fff;
		border-radius: 10px;
		display: inline-block;
		padding: 10px 5%;
	}
	@media screen and (max-width: 800px) {
		.table_content{
			padding: 10px 5px;
		}
		.table_content h1{
			font-size: 25px;
		}
	}
	.disabled_td td{
		background-color: #deefff;
	}
	.disabled_td td:not(.no_blur) {
		opacity: .6;
	}
	.list_back{
		display: flex;
		justify-content: left;
	}
</style>
<div class="table_content container-fluid" >
	<h1 class="mt-3">Tables List</h1>
	
	<?php if (isset($_SESSION['error'])){ ?>
		<div class="container alert alert-danger mt-3">
			<?= $_SESSION['error'] ?>
		</div>
	<?php }else if (isset($_SESSION['success'])){ ?>
		<div class="container alert alert-success mt-3">
			<?= $_SESSION['success'] ?>
		</div>
	<?php } ?>
	<div class="list_back">
		<a href="<?=base_url('pos/tables/add')?>" class="btn btn-primary btn-sm">
			<i class="fa-solid fa-plus"></i>
			Add Table
		</a>
	</div>
    <?php
        $tableCategories = array(1 => 'Restaurant', 2 => 'Cafe Shop', 3 => 'Hotel');
        foreach ($tableCategories as $categoryId => $categoryTitle):
            $categoryTables = array_filter($tables, function($row) use ($categoryId) { return (int)$row->tbCategory === $categoryId; });
    ?>
        <h2 class="mt-4"><?=html_escape($categoryTitle)?></h2>
        <hr>
        <div class="table-responsive">
        <table class="table table-striped mt-3">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Table Name</th>
              <th scope="col" class="text-center">Sort order</th>
              <th scope="col">Status</th>
              <th scope="col">Date last modified</th>
              <th scope="col">Date created</th>
              <th scope="col" <?=$action_td_width?>>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $counter = 0; foreach ($categoryTables as $table): $counter++; ?>
                <tr <?php if($table->tbStatus != "1") echo "class='disabled_td'"; ?>>
                  <td scope="row"><?=$counter?></td>
                  <td><?=html_escape($table->tbName)?></td>
                  <td class="text-center"><?=$table->tbSortOrder?></td>
                  <td><?=$table->tbStatus == "1" ? "Active" : "Disabled"?></td>
                  <td><?=$table->tbDate_modified?></td>
                  <td><?=$table->tbDate_created?></td>
                  <td class="no_blur" <?=$action_td_width?>>
                    <a href="<?=base_url('pos/tables/edit/').$table->tbID?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
                    <?php if($table->tbStatus == "1"): ?>
                        <a href="<?=base_url('pos/tables/disable/').$table->tbID?>" class="btn btn-warning btn-sm" onclick="return confirm('Confirm disable of <?=html_escape($table->tbName)?>?')"><i class="fas fa-toggle-off"></i> Disable</a>
                    <?php else: ?>
                        <a href="<?=base_url('pos/tables/enable/').$table->tbID?>" class="btn btn-success btn-sm"><i class="fas fa-toggle-on"></i> Enable</a>
                    <?php endif; ?>
                    <?php if (empty($table->tbRoomID)): ?>
                    <a href="<?=base_url('pos/tables/delete/').$table->tbID?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirm deletion of <?=html_escape($table->tbName)?>?')"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>
                    <?php endif; ?>
                  </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$categoryTables): ?><tr><td colspan="7" class="text-center text-muted">No tables in this category.</td></tr><?php endif; ?>
          </tbody>
        </table>
        </div>
    <?php endforeach; ?>
</div>