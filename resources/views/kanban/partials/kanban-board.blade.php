<div class="kanban-board">
    <div class="columns-container">
        <div class="column product-backlog" ondrop="handleDrop(event, 'product-backlog')"
            ondragover="handleDragOver(event)">
            <div class="column-header">
                <span>Product Backlog</span>
                <span class="task-count" id="product-backlog-count">0</span>
            </div>
            <div class="tasks-container" id="product-backlog-tasks"></div>
        </div>

        <div class="column in-progress" ondrop="handleDrop(event, 'in-progress')" ondragover="handleDragOver(event)">
            <div class="column-header">
                <span>In Progress</span>
                <span class="task-count" id="in-progress-count">0</span>
            </div>
            <div class="tasks-container" id="in-progress-tasks">
                <div class="empty-state">Belum ada tugas</div>
            </div>
        </div>

        <div class="column pending" ondrop="handleDrop(event, 'pending')" ondragover="handleDragOver(event)">
            <div class="column-header">
                <span>Pending</span>
                <span class="task-count" id="pending-count">0</span>
            </div>
            <div class="tasks-container" id="pending-tasks">
                <div class="empty-state">Belum ada tugas</div>
            </div>
        </div>

        <div class="column done" ondrop="handleDrop(event, 'done')" ondragover="handleDragOver(event)">
            <div class="column-header">
                <span>Done</span>
                <span class="task-count" id="done-count">0</span>
            </div>
            <div class="tasks-container" id="done-tasks">
                <div class="empty-state">Belum ada tugas</div>
            </div>
        </div>

        <div class="column overdue" ondrop="handleDrop(event, 'overdue')" ondragover="handleDragOver(event)">
            <div class="column-header">
                <span>Overdue</span>
                <span class="task-count" id="overdue-count">0</span>
            </div>
            <div class="tasks-container" id="overdue-tasks">
                <div class="empty-state">Belum ada tugas</div>
            </div>
        </div>
    </div>
</div>
