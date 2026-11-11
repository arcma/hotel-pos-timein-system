<?php
	$action_td_width = 'style="width: 250px;"';
	date_default_timezone_set('Asia/Manila');

    $today = new DateTimeImmutable('today');
    $todayKey = $today->format('Y-m-d');

    $requestedDate = $_GET['date'] ?? $todayKey;
    $selectedDate = DateTimeImmutable::createFromFormat(
        '!Y-m-d',
        $requestedDate
    );

    $dateErrors = DateTimeImmutable::getLastErrors();

    if (
        !$selectedDate ||
        ($dateErrors !== false &&
            ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0)) ||
        $selectedDate->format('Y-m-d') !== $requestedDate ||
        $selectedDate > $today
    ) {
        $selectedDate = $today;
    }

    $dateKey = $selectedDate->format('Y-m-d');
    $isToday = $dateKey === $todayKey;

    function hotelDashboardEscape($value): string
    {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
    }

    $dateTitle = $isToday
    ? 'Today · ' . $selectedDate->format('l, F j, Y')
    : $selectedDate->format('l, F j, Y');
?>
<style>
	.room_content{
		border: 1px solid #c2c2c2;
		background-color: #fff;
		border-radius: 10px;
		display: inline-block;
		padding: 10px 5%;
	}
	@media screen and (max-width: 800px) {
		.room_content{
			padding: 10px;
		}
		.room_content h1{
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
	.table_grid{
	
	}
	.room_item{
		padding: 5% 10px;
		text-align: center;
		background-color: #d0e4f7;
		position: relative;
		border-radius: 20px;
		box-shadow: 5px 8px #c2c2c2;
		transition: all .3s ease-out;
		min-height: 150px;
		display: flex;
		justify-content: center;
		flex-direction: column;
	}
	.room_item:hover{
		cursor: pointer;
		background-color: #C3C9E5;
	}
	.room_item.active{
		background-color: #cdeb8b;
	}
	.room_item.active:hover{
		cursor: normal;
		background-color: inhirit;
	}
	
	.table_status{
		position: absolute;
		top: 0px;
		left: 0px;
		font-size: 12px;
		padding: 5px;
		border: 1px solid #878787;
		color: #000;
		background-color: #ccc;
		font-weight: 600;
		border-top-left-radius: 20px;
		border-bottom-right-radius: 20px;
		width: 40%;
	}
	.table_status.active{
		background-color: #F1DA36;
	}
	
	.table_number{
		font-size: 30px;
		font-weight: 600;
	}
	.table_name{
		font-size: 20px;
		line-height: 15px;
		font-weight: 500;
	}
	.table_amount{
		margin-top: 2px;
		font-size: 15px;
	}
	.table_list_count{
		position: absolute;
		bottom: 10px;
		right: 10px;
	}

    .reservation_header_panel{
        border: 1px solid red;
        display:flex;
        justify-content: space-between;

    }

	#hotel-dashboard .hotel-dashboard__arrow-btn {
		width:30px;
		padding: 0;
	}

	#hotel-dashboard .hotel-dashboard__btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		border: 1px solid #c2c2c2;
		border-radius: 3px;
		
		
		font-weight: 650;
		transition: 0.2s ease;
	}
</style>
<?php 
	// echo "<pre>";
	// print_r($tables);
	// echo "</pre>";

?>
<div class="room_content container-fluid" >
	<h1 class="mt-2">Reservations</h1>
	
	<?php if (isset($_SESSION['error'])){ ?>
		<div class="container alert alert-danger mt-3">
			<?= $_SESSION['error'] ?>
		</div>
	<?php }else if (isset($_SESSION['success'])){ ?>
		<div class="container alert alert-success mt-3">
			<?= $_SESSION['success'] ?>
		</div>
	<?php } ?>
	
	<?php 
		// echo "<pre>";
		// print_r($cart[3]);
		// echo "</pre>";
	?>
	
	
	<div class="table_grid row mt-2" id="hotel-dashboard">

        <div class="reservation_header_panel">
        <!-- Date navigation -->
        
            <div>
                <div
                    id="hotel-dashboard-date-title"
                    class="hotel-dashboard__date-title"
                >
                    <?= hotelDashboardEscape($dateTitle) ?>
                </div>
            </div>
            <form
            method="GET"
            id="hotel-dashboard-date-form"
            class="hotel-dashboard__datebar"
        >
            <div class="hotel-dashboard__date-controls">
                
                <button
                    type="button"
                    id="hotel-dashboard-prev-btn"
                    class="hotel-dashboard__btn hotel-dashboard__arrow-btn"
                    aria-label="Previous day"
                >‹</button>

                <input
                    type="date"
                    name="date"
                    id="hotel-dashboard-date-picker"
                    class="hotel-dashboard__date-picker"
                    value="<?= hotelDashboardEscape($dateKey) ?>"
                    max="<?= hotelDashboardEscape($todayKey) ?>"
                    aria-label="Select date"
                >

                <button
                    type="button"
                    id="hotel-dashboard-next-btn"
                    class="hotel-dashboard__btn hotel-dashboard__arrow-btn"
                    aria-label="Next day"
                    <?= $isToday ? 'disabled' : '' ?>
                >›</button>

           
            </div>
             </form>
</div>

		<?php
			foreach($rooms as $room){
                $reservation = isset($room->reservation) ? $room->reservation : null;
                $stay = isset($room->active_stay) ? $room->active_stay : null;
				$active = ($stay || $reservation) ? "active" : "";
				$active_text = $stay ? "Checked In" : ($reservation ? "Reserved" : "Vacant");
                $book = (!$isToday || $reservation || $stay) ? 'booked' : 'book';
                $roomUrl = base_url('pos/reservations/'.$book.'/'.$room->rmID.'?date='.rawurlencode($dateKey));
		?>	
			<div class="col-sm-6 col-md-4 col-lg-3">
				<a href="<?= $roomUrl ?>">
				<div class="room_item mt-4 <?=$active?>">
					<div class="table_status <?=$active?>"><?=$active_text?></div>
					<div class="table_number"><?=$room->rmTitle?></div>
					<div class="table_amount">&#8369; <?=$room->rmPrice?></div>
					<div class="table_list_count">
						<i class="fa-solid fa-person">&nbsp;&nbsp;0/<?=$room->rmCapacity?></i>
					</div>
				</div>
				</a>
			</div>
		<?php } ?>
	</div>
	
</div>

<script>
(() => {
    "use strict";

    const root = document.getElementById("hotel-dashboard");

    if (!root) return;

    const form = root.querySelector("#hotel-dashboard-date-form");
    const picker = root.querySelector("#hotel-dashboard-date-picker");
    const prevBtn = root.querySelector("#hotel-dashboard-prev-btn");
    const nextBtn = root.querySelector("#hotel-dashboard-next-btn");

    function changeDate(days) {
        if (!picker.value) return;

        // Use local date components to avoid UTC timezone shifts.
        const [year, month, day] = picker.value.split("-").map(Number);
        const date = new Date(year, month - 1, day, 12);

        date.setDate(date.getDate() + days);

        const newDate = [
            date.getFullYear(),
            String(date.getMonth() + 1).padStart(2, "0"),
            String(date.getDate()).padStart(2, "0")
        ].join("-");

        if (newDate > picker.max) return;

        picker.value = newDate;
        form.requestSubmit();
    }

    prevBtn.addEventListener("click", () => {
        console.log('dayuyu');
        changeDate(-1);
    });

    nextBtn.addEventListener("click", () => {
        changeDate(1);
    });

    picker.addEventListener("change", () => {
        if (picker.value && picker.value <= picker.max) {
            form.requestSubmit();
        }
    });
})();
</script>