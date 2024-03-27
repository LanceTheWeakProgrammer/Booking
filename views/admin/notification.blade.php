<div class="dropdown position-relative"> 
    <button class="btn btn-outline-none border-0 shadow-none dropdown-toggle mt-1 me-2" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell-fill text-white fs-5"></i>
        <span id="unreadNotificationCount" class="badge bg-secondary" style="font-size: 11px; margin-left: -10px;"></span> 
    </button>
    <ul class="my-notification-dropdown dropdown-menu dropdown-menu-end border-0 shadow" aria-labelledby="notificationDropdown" style="height: 450px; overflow-y: scroll">
        <li class="d-flex justify-content-between align-items-center px-3 py-2">
            <h6 class="dropdown-header mb-0 fs-5">Notifications</h6>
            <button class="btn btn-sm btn-white me-2 border-0 mark-all-read" style="font-style: italic;"><i class="bi bi-check"></i> Mark All as Read</button>
        </li>
        <li id="notificationList">
            
        </li>
    </ul>
</div>

<style>
    .dropdown-toggle::after {
        display: none !important;
    }
</style>
