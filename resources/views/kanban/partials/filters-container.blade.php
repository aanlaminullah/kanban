<div class="filters-container">
    <div class="search-box">
        <svg class="search-icon" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd" />
        </svg>
        <input type="text" class="search-input" id="searchInput"
            placeholder="Cari task berdasarkan judul, deskripsi, atau deadline..." oninput="filterTasks()" />
    </div>

    <div class="filter-group custom-filter-group" id="userFilterGroup">
        <button type="button" class="filter-select custom-filter-toggle" id="userFilterToggle"
            onclick="toggleUserFilterDropdown()">
            Filter User <span id="selectedUserName" class="filter-selection-display"></span>
        </button>
        <div class="custom-filter-dropdown" id="userFilterDropdown" style="display: none;">
            <input type="text" id="userFilterSearchInput" class="user-search-input" placeholder="Cari user..."
                oninput="filterUserList(this.value)" />
            <div class="user-list-container" id="userListContainer">
            </div>
            <button type="button" class="btn-clear-filter" onclick="clearUserFilter()">Hapus Filter</button>
        </div>
    </div>

    <div class="filter-group">
        <select class="filter-select" id="priorityFilter" onchange="filterTasks()">
            <option value="">Semua Prioritas</option>
            <option value="high">High</option>
            <option value="medium">Medium</option>
            <option value="low">Low</option>
        </select>
    </div>

    <div class="filter-group">
        <select class="filter-select" id="bidangFilter" onchange="filterTasks()">
            <option value="">Semua Bidang</option>
            <option value="aptika">Aptika</option>
            <option value="sarkom">Sarkom</option>
            <option value="sekretariat">Sekretariat</option>
        </select>
    </div>
</div>
