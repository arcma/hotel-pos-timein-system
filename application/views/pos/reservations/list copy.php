
<?php
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

$dateTitle = $isToday
    ? 'Today · ' . $selectedDate->format('l, F j, Y')
    : $selectedDate->format('l, F j, Y');

/*
|--------------------------------------------------------------------------
| Demo data
|--------------------------------------------------------------------------
| Replace these arrays and the occupancy logic with database queries
| when integrating the dashboard with your hotel booking system.
*/

//echo print_r($rooms);
$xrooms = [
    ['101', 'Standard'],
    ['102', 'Standard'],
    ['103', 'Standard'],
    ['104', 'Standard'],
    ['105', 'Standard'],
    ['201', 'Deluxe'],
    ['202', 'Deluxe'],
    ['203', 'Deluxe'],
    ['204', 'Deluxe'],
    ['301', 'Suite'],
    ['302', 'Suite'],
    ['303', 'Suite'],
];

$guests = [
    'M. Santos',
    'A. Reyes',
    'J. Cruz',
    'L. Garcia',
    'R. Mendoza',
    'K. Dela Cruz',
    'S. Flores',
    'T. Ramos',
    'P. Torres',
    'D. Navarro',
];



$occupiedNumbers = [];


$roomData = [];

foreach ($rooms as $room) {
    $number = $room->rmTitle;
    $type = 'n/a';

    $occupied = false;//in_array($number, $occupiedNumbers, true);

    $roomData[] = [
        'id' => $room->rmID,
        'title' => $room->rmTitle,
        'type' => false,
        'occupied' => $occupied,
        'guest' => false,
        'action' => $isToday? 0 : 1, // replace 1 with reservation id
    ];
}




$totalRooms = count($roomData);

$occupiedRooms = count(array_filter(
    $roomData,
    fn($room) => $room['occupied']
));

$availableRooms = $totalRooms - $occupiedRooms;

function hotelDashboardEscape($value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}
?>


<link rel="stylesheet" href="<?= base_url('assets/css/reservation.css') ?>" />

    <style>
        /* Dashboard styles are scoped to #hotel-dashboard */

        .table_content{
		border: 1px solid #c2c2c2;
		background-color: #fff;
		border-radius: 10px;
		display: inline-block;
		padding: 10px 5%;
	}

        
    </style>

<div class="table_content container-fluid" >
<main id="hotel-dashboard" class="hotel-dashboard">
    <div class="hotel-dashboard__container">

        <!-- Date navigation -->
        <form
            method="GET"
            id="hotel-dashboard-date-form"
            class="hotel-dashboard__datebar"
        >
            <div>
                <div class="hotel-dashboard__eyebrow">
                    Daily overview
                </div>

                <div
                    id="hotel-dashboard-date-title"
                    class="hotel-dashboard__date-title"
                >
                    <?= hotelDashboardEscape($dateTitle) ?>
                </div>
            </div>

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

        <!-- Summary cards -->
        <section
            class="hotel-dashboard__stats"
            aria-label="Room summary"
        >
            <article class="hotel-dashboard__stat-card">
                <div class="hotel-dashboard__stat-heading">
                    <span>Total Rooms</span>
                    <span class="hotel-dashboard__stat-icon" aria-hidden="true">▦</span>
                </div>

                <div class="hotel-dashboard__stat-number">
                    <?= $totalRooms ?>
                </div>

                <div class="hotel-dashboard__stat-description">
                    Rooms in property
                </div>
            </article>

            <article class="hotel-dashboard__stat-card xhotel-dashboard__stat-card--available">
                <div class="hotel-dashboard__stat-heading">
                    <span>Available</span>
                    <span class="hotel-dashboard__stat-icon" aria-hidden="true">✓</span>
                </div>

                <div class="hotel-dashboard__stat-number">
                    <?= $availableRooms ?>
                </div>

                <div class="hotel-dashboard__stat-description">
                    Ready for booking
                </div>
            </article>

            <article class="hotel-dashboard__stat-card xhotel-dashboard__stat-card--occupied">
                <div class="hotel-dashboard__stat-heading">
                    <span>Occupied</span>
                    <span class="hotel-dashboard__stat-icon" aria-hidden="true">▣</span>
                </div>

                <div class="hotel-dashboard__stat-number">
                    <?= $occupiedRooms ?>
                </div>

                <div class="hotel-dashboard__stat-description">
                    Currently occupied
                </div>
            </article>
        </section>

        <!-- Room status -->
        <section class="hotel-dashboard__room-panel">
            <div class="hotel-dashboard__panel-header">
                <div>
                    <h2 class="hotel-dashboard__section-title">
                        Room Status
                    </h2>

                    <p class="hotel-dashboard__muted">
                        Room-by-room overview for
                        <?= hotelDashboardEscape(
                            $selectedDate->format('M j, Y')
                        ) ?>
                    </p>
                </div>

                <span class="hotel-dashboard__room-count">
                    <?= $totalRooms ?> Rooms
                </span>
            </div>

            <div class="hotel-dashboard__room-panel-body">
                <div class="hotel-dashboard__room-grid">

                    <?php foreach ($roomData as $room): ?>
                        <a class="hotel-dashboard__room-card" href="/pos/reservations/book/<?=$room['id']?>/<?=$room['action']?>">

                            <div class="hotel-dashboard__room-top">
                                <div>
                                    <div class="hotel-dashboard__room-number">
                                        <?= hotelDashboardEscape($room['title']) ?>
                                    </div>

                                    <!-- Uncomment to display room type.
                                    <div class="hotel-dashboard__room-type">
                                        <?= hotelDashboardEscape($room['type']) ?>
                                    </div>
                                    -->
                                </div>

                                <div
                                    class="hotel-dashboard__room-symbol"
                                    aria-hidden="true"
                                >⌂</div>
                            </div>

                            <span class="hotel-dashboard__status <?= $room['occupied']
                                ? 'hotel-dashboard__status--occupied'
                                : 'hotel-dashboard__status--available' ?>">
                                <?= $room['occupied'] ? 'Occupied' : 'Available' ?>
                            </span>

                            <div class="hotel-dashboard__guest">
                                <?php if ($room['occupied']): ?>
                                    Guest:
                                    <?= hotelDashboardEscape($room['guest']) ?>
                                <?php else: ?>
                                    No current guest
                                <?php endif; ?>
                            </div>

                        </a>
                    <?php endforeach; ?>

                </div>
            </div>
        </section>

        <footer class="hotel-dashboard__footer">
            Hotel Management Dashboard · Demo version using sample data
        </footer>

    </div>
</main>
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

