<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Kanban Board Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }

        .dashboard-container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: linear-gradient(145deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .user-profile {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 24px;
            font-weight: bold;
        }

        .user-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .user-role {
            font-size: 12px;
            opacity: 0.8;
        }

        .sidebar-menu {
            flex: 1;
            padding: 20px 0;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            color: white;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.2);
            border-right: 3px solid white;
        }

        .menu-icon {
            width: 20px;
            height: 20px;
            margin-right: 12px;
        }

        .logout-btn {
            margin: 20px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .header {
            background: white;
            padding: 20px 30px;
            border-bottom: 1px solid #e1e5e9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #2d3748;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .add-task-btn {
            background: #4299e1;
            color: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(66, 153, 225, 0.3);
        }

        .add-task-btn:hover {
            background: #3182ce;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(66, 153, 225, 0.4);
        }

        .add-task-btn svg {
            width: 20px;
            height: 20px;
        }

        /* Filter & Search Styles */
        .filters-container {
            background: white;
            padding: 16px 30px;
            border-bottom: 1px solid #e1e5e9;
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 8px 12px 8px 40px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
        }

        .search-input:focus {
            outline: none;
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #a0aec0;
        }

        .filter-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .filter-select {
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
        }

        .filter-btn {
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #4a5568;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
        }

        .filter-btn.active {
            background: #4299e1;
            color: white;
            border-color: #4299e1;
        }

        .filter-btn:hover {
            background: #f7fafc;
        }

        .filter-btn.active:hover {
            background: #3182ce;
        }

        /* Kanban Board */
        .kanban-board {
            flex: 1;
            padding: 20px 30px;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .columns-container {
            display: flex;
            gap: 20px;
            height: 100%;
            min-width: 1200px;
        }

        .column {
            flex: 1;
            min-width: 300px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
        }

        .column-header {
            padding: 16px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task-count {
            background: rgba(255, 255, 255, 0.3);
            color: inherit;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .product-backlog .column-header {
            background: #e2e8f0;
            color: #4a5568;
        }

        .in-progress .column-header {
            background: #bee3f8;
            color: #2b6cb0;
        }

        .pending .column-header {
            background: #faf089;
            color: #b7791f;
        }

        .done .column-header {
            background: #c6f6d5;
            color: #2f855a;
        }

        .overdue .column-header {
            background: #fed7d7;
            color: #c53030;
        }

        .tasks-container {
            flex: 1;
            padding: 16px;
            padding-top: 8px;
            overflow-y: auto;
            min-height: 200px;
        }

        .task-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 12px;
            cursor: grab;
            transition: all 0.2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .task-card:active {
            cursor: grabbing;
        }

        .task-card.dragging {
            opacity: 0.5;
            transform: rotate(5deg);
        }

        .task-card.overdue-task {
            border-left: 4px solid #c53030;
            background: #fed7d7;
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .task-title {
            font-weight: 600;
            font-size: 14px;
            color: #2d3748;
            flex: 1;
        }

        .task-priority {
            margin-left: 8px;
            display: flex;
            align-items: center;
        }

        .priority-high {
            color: #e53e3e;
        }

        .priority-medium {
            color: #dd6b20;
        }

        .priority-low {
            color: #38a169;
        }

        .task-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }

        .task-deadline {
            display: flex;
            align-items: center;
            font-size: 12px;
            color: #718096;
        }

        .task-deadline svg {
            width: 12px;
            height: 12px;
            margin-right: 4px;
        }

        .task-labels {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }

        .task-label {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .label-urgent {
            background: #fed7d7;
            color: #c53030;
        }

        .label-bug {
            background: #fbb6ce;
            color: #b83280;
        }

        .label-improvement {
            background: #c6f6d5;
            color: #2f855a;
        }

        .label-personal {
            background: #bee3f8;
            color: #2b6cb0;
        }

        .label-feature {
            background: #e9d8fd;
            color: #805ad5;
        }

        .task-description {
            font-size: 12px;
            color: #4a5568;
            line-height: 1.4;
            margin-bottom: 12px;
        }

        .subtasks-container {
            margin-bottom: 12px;
        }

        .subtask-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: #718096;
            margin-bottom: 4px;
        }

        .subtask-checkbox {
            width: 12px;
            height: 12px;
        }

        .subtask-text.completed {
            text-decoration: line-through;
            color: #a0aec0;
        }

        .task-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .subtask-progress {
            font-size: 10px;
            color: #718096;
        }

        .task-buttons {
            display: flex;
            gap: 4px;
        }

        .edit-btn {
            background: #bee3f8;
            color: #2b6cb0;
            border: none;
            padding: 4px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .edit-btn:hover {
            background: #90cdf4;
        }

        .delete-btn {
            background: #fed7d7;
            color: #c53030;
            border: none;
            padding: 4px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .delete-btn:hover {
            background: #feb2b2;
        }

        .edit-btn svg,
        .delete-btn svg {
            width: 12px;
            height: 12px;
        }

        .drop-zone {
            background: rgba(66, 153, 225, 0.1);
            border: 2px dashed #4299e1;
            border-radius: 6px;
        }

        .empty-state {
            text-align: center;
            color: #a0aec0;
            font-style: italic;
            font-size: 14px;
            padding: 20px;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal {
            background: white;
            border-radius: 8px;
            padding: 24px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
        }

        .modal-subtitle {
            font-size: 12px;
            color: #a0aec0;
            font-weight: normal;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: #a0aec0;
            cursor: pointer;
            padding: 4px;
            line-height: 1;
        }

        .close-btn:hover {
            color: #4a5568;
        }

        .form-row {
            display: flex;
            gap: 16px;
        }

        .form-group {
            margin-bottom: 16px;
            flex: 1;
        }

        .form-group.full-width {
            flex: none;
            width: 100%;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #4a5568;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            background: white;
            cursor: pointer;
        }

        .labels-container {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .label-checkbox {
            display: none;
        }

        .label-option {
            padding: 6px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
            font-weight: 500;
        }

        .label-option.urgent {
            border-color: #fed7d7;
            color: #c53030;
        }

        .label-option.bug {
            border-color: #fbb6ce;
            color: #b83280;
        }

        .label-option.improvement {
            border-color: #c6f6d5;
            color: #2f855a;
        }

        .label-option.personal {
            border-color: #bee3f8;
            color: #2b6cb0;
        }

        .label-option.feature {
            border-color: #e9d8fd;
            color: #805ad5;
        }

        .label-checkbox:checked+.label-option.urgent {
            background: #fed7d7;
        }

        .label-checkbox:checked+.label-option.bug {
            background: #fbb6ce;
        }

        .label-checkbox:checked+.label-option.improvement {
            background: #c6f6d5;
        }

        .label-checkbox:checked+.label-option.personal {
            background: #bee3f8;
        }

        .label-checkbox:checked+.label-option.feature {
            background: #e9d8fd;
        }

        .subtasks-section {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
        }

        .subtask-input-container {
            display: flex;
            gap: 8px;
            margin-bottom: 8px;
        }

        .subtask-input {
            flex: 1;
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            font-size: 12px;
        }

        .add-subtask-btn {
            padding: 6px 12px;
            background: #4299e1;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
        }

        .add-subtask-btn:hover {
            background: #3182ce;
        }

        .subtask-list {
            max-height: 120px;
            overflow-y: auto;
        }

        .subtask-edit-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .subtask-edit-item:last-child {
            border-bottom: none;
        }

        .subtask-edit-text {
            flex: 1;
            font-size: 12px;
            color: #4a5568;
        }

        .remove-subtask-btn {
            background: #fed7d7;
            color: #c53030;
            border: none;
            padding: 2px 4px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 10px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .btn-secondary {
            padding: 10px 20px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #4a5568;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
        }

        .btn-primary {
            padding: 10px 20px;
            border: none;
            background: #4299e1;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #3182ce;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
            }

            .filters-container {
                flex-direction: column;
                gap: 8px;
                align-items: stretch;
            }

            .columns-container {
                flex-direction: column;
                min-width: auto;
            }

            .column {
                min-width: auto;
            }

            .form-row {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="user-profile">
                <div class="user-avatar">JD</div>
                <div class="user-name">John Doe</div>
                <div class="user-role">Project Manager</div>
            </div>

            <div class="sidebar-menu">
                <a href="#" class="menu-item">
                    <svg class="menu-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    Dashboard
                </a>

                <a href="#" class="menu-item active">
                    <svg class="menu-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                    </svg>
                    Kanban Board
                </a>

                <a href="#" class="menu-item">
                    <svg class="menu-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                            clip-rule="evenodd" />
                    </svg>
                    Calendar
                </a>

                <a href="#" class="menu-item">
                    <svg class="menu-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L3 7v11a1 1 0 001 1h3v-5a1 1 0 011-1h4a1 1 0 011 1v5h3a1 1 0 001-1V7l-7-5z" />
                    </svg>
                    Projects
                </a>

                <a href="#" class="menu-item">
                    <svg class="menu-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                            clip-rule="evenodd" />
                    </svg>
                    Team
                </a>

                <a href="#" class="menu-item">
                    <svg class="menu-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                            clip-rule="evenodd" />
                    </svg>
                    Settings
                </a>
            </div>

            <button class="logout-btn" onclick="logout()">
                <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                        clip-rule="evenodd" />
                </svg>
                Logout
            </button>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <h1>Enhanced Kanban Board</h1>
                <div class="header-actions">
                    <button class="add-task-btn" onclick="openAddTaskModal()" title="Tambah Task Baru (Shift+N)">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Filters & Search -->
            <div class="filters-container">
                <div class="search-box">
                    <svg class="search-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                    <input type="text" class="search-input" id="searchInput"
                        placeholder="Cari task berdasarkan judul, deskripsi, atau deadline..." oninput="filterTasks()">
                </div>

                
            </div>

            <!-- Kanban Board -->
            <div class="kanban-board">
                <div class="columns-container">
                    <!-- Product Backlog Column -->
                    <div class="column product-backlog" ondrop="handleDrop(event, 'product-backlog')"
                        ondragover="handleDragOver(event)">
                        <div class="column-header">
                            <span>Product Backlog</span>
                            <span class="task-count" id="product-backlog-count">0</span>
                        </div>
                        <div class="tasks-container" id="product-backlog-tasks">
                            <!-- Tasks will be inserted here -->
                        </div>
                    </div>

                    <!-- In Progress Column -->
                    <div class="column in-progress" ondrop="handleDrop(event, 'in-progress')"
                        ondragover="handleDragOver(event)">
                        <div class="column-header">
                            <span>In Progress</span>
                            <span class="task-count" id="in-progress-count">0</span>
                        </div>
                        <div class="tasks-container" id="in-progress-tasks">
                            <div class="empty-state">Belum ada tugas</div>
                        </div>
                    </div>

                    <!-- Pending Column -->
                    <div class="column pending" ondrop="handleDrop(event, 'pending')"
                        ondragover="handleDragOver(event)">
                        <div class="column-header">
                            <span>Pending</span>
                            <span class="task-count" id="pending-count">0</span>
                        </div>
                        <div class="tasks-container" id="pending-tasks">
                            <div class="empty-state">Belum ada tugas</div>
                        </div>
                    </div>

                    <!-- Done Column -->
                    <div class="column done" ondrop="handleDrop(event, 'done')" ondragover="handleDragOver(event)">
                        <div class="column-header">
                            <span>Done</span>
                            <span class="task-count" id="done-count">0</span>
                        </div>
                        <div class="tasks-container" id="done-tasks">
                            <div class="empty-state">Belum ada tugas</div>
                        </div>
                    </div>

                    <!-- Overdue Column -->
                    <div class="column overdue" ondrop="handleDrop(event, 'overdue')"
                        ondragover="handleDragOver(event)">
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
        </div>
    </div>

    <!-- Add/Edit Task Modal -->
    <div class="modal-overlay" id="taskModal">
        <div class="modal">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title" id="modalTitle">Tambah Task Baru</h3>
                    <div class="modal-subtitle">Shortcut: Shift+N</div>
                </div>
                <button class="close-btn" onclick="closeTaskModal()">&times;</button>
            </div>

            <form id="taskForm">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="taskTitle">Judul Task</label>
                        <input type="text" id="taskTitle" class="form-input" placeholder="Masukkan judul task..."
                            required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="taskPriority">Prioritas</label>
                        <select id="taskPriority" class="form-select" required>
                            <option value="">Pilih Prioritas</option>
                            <option value="high">High 🔥</option>
                            <option value="medium">Medium ⬆️</option>
                            <option value="low">Low ➡️</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="taskDeadline" class="form-label">Deadline</label>
                        <input type="text" id="taskDeadline" class="form-input" required>
                    </div>

                    
                </div>

                <div class="form-group full-width">
                    <label class="form-label" for="taskDescription">Deskripsi</label>
                    <textarea id="taskDescription" class="form-input form-textarea" placeholder="Masukkan deskripsi task..." required></textarea>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Label</label>
                    <div class="labels-container">
                        <input type="checkbox" id="label-urgent" class="label-checkbox" value="urgent">
                        <label for="label-urgent" class="label-option urgent">Urgent</label>

                        <input type="checkbox" id="label-bug" class="label-checkbox" value="bug">
                        <label for="label-bug" class="label-option bug">Bug</label>

                        <input type="checkbox" id="label-improvement" class="label-checkbox" value="improvement">
                        <label for="label-improvement" class="label-option improvement">Improvement</label>

                        <input type="checkbox" id="label-personal" class="label-checkbox" value="personal">
                        <label for="label-personal" class="label-option personal">Personal</label>

                        <input type="checkbox" id="label-feature" class="label-checkbox" value="feature">
                        <label for="label-feature" class="label-option feature">Feature</label>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Subtasks</label>
                    <div class="subtasks-section">
                        <div class="subtask-input-container">
                            <input type="text" id="subtaskInput" class="subtask-input"
                                placeholder="Tambah subtask...">
                            <button type="button" class="add-subtask-btn" onclick="addSubtask()">Tambah</button>
                        </div>
                        <div class="subtask-list" id="subtaskList">
                            <!-- Subtasks will be inserted here -->
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeTaskModal()">Batal</button>
                    <button type="submit" class="btn-primary" id="submitBtn">Tambah Task</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Enhanced tasks data with new fields
        let tasks = [{
                id: 1,
                title: 'Implementasi Sistem Login',
                deadline: '2024-09-20',
                description: 'Membuat sistem autentikasi pengguna dengan JWT token dan validasi form',
                column: 'product-backlog',
                priority: 'high',
                labels: ['urgent', 'feature'],
                subtasks: [{
                        id: 1,
                        text: 'Setup JWT library',
                        completed: false,
        createdBy: 'john'
                    },
                    {
                        id: 2,
                        text: 'Buat login form',
                        completed: true
                    },
                    {
                        id: 3,
                        text: 'Validasi input',
                        completed: false,
        createdBy: 'john'
                    }
                ]
            },
            {
                id: 2,
                title: 'Design UI Dashboard',
                deadline: '2024-10-20',
                description: 'Merancang interface dashboard utama dengan komponen yang responsif',
                column: 'product-backlog',
                priority: 'medium',
                labels: ['improvement'],
                subtasks: [{
                        id: 1,
                        text: 'Wireframe',
                        completed: true
                    },
                    {
                        id: 2,
                        text: 'Mockup design',
                        completed: false,
        createdBy: 'john'
                    }
                ]
            },
            {
                id: 3,
                title: 'Fix Bug Authentication',
                deadline: '2024-09-15',
                description: 'Mengatasi masalah token expiry yang tidak ter-handle dengan baik',
                column: 'in-progress',
                priority: 'high',
                labels: ['urgent', 'bug'],
                subtasks: [{
                        id: 1,
                        text: 'Reproduce bug',
                        completed: true
                    },
                    {
                        id: 2,
                        text: 'Fix implementation',
                        completed: false,
        createdBy: 'john'
                    },
                    {
                        id: 3,
                        text: 'Testing',
                        completed: false,
        createdBy: 'john'
                    }
                ]
            },
            {
                id: 4,
                title: 'Database Schema',
                deadline: '2024-10-12',
                description: 'Membuat struktur database untuk aplikasi dengan relasi yang tepat',
                column: 'product-backlog',
                priority: 'medium',
                labels: ['feature'],
                subtasks: [{
                        id: 1,
                        text: 'ERD Design',
                        completed: false,
        createdBy: 'john'
                    },
                    {
                        id: 2,
                        text: 'Migration scripts',
                        completed: false,
        createdBy: 'john'
                    }
                ]
            },
            {
                id: 5,
                title: 'Personal Learning: React Hooks',
                deadline: '2024-10-25',
                description: 'Mempelajari advanced React Hooks untuk project mendatang',
                column: 'product-backlog',
                priority: 'low',
                labels: ['personal', 'improvement'],
                subtasks: [{
                        id: 1,
                        text: 'useCallback & useMemo',
                        completed: false,
        createdBy: 'john'
                    },
                    {
                        id: 2,
                        text: 'Custom hooks',
                        completed: false,
        createdBy: 'john'
                    },
                    {
                        id: 3,
                        text: 'Practice project',
                        completed: false,
        createdBy: 'john'
                    }
                ]
            }
        ];

        let draggedTask = null;
        let nextTaskId = 6;
        let nextSubtaskId = 10;
        let editingTaskId = null;
        let currentSubtasks = [];
        let filteredTasks = [...tasks];

        // Priority icons
        const priorityIcons = {
            high: '🔥',
            medium: '⬆️',
            low: '➡️'
        };

        // Format date function
        function formatDate(dateString) {
            const options = {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }

        // Check and update overdue tasks
        function updateOverdueTasks() {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            tasks.forEach(task => {
                const taskDeadline = new Date(task.deadline);
                taskDeadline.setHours(0, 0, 0, 0);

                if (taskDeadline < today && task.column !== 'done' && task.column !== 'overdue') {
                    task.column = 'overdue';
                }
            });
        }

        // Filter tasks based on search and filters
        function filterTasks() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const columnFilter = document.getElementById('columnFilter').value;
            const priorityFilter = document.getElementById('priorityFilter').value;
            const overdueFilter = document.getElementById('overdueFilter').classList.contains('active');

            filteredTasks = tasks.filter(task => {
                // Search filter
                const matchesSearch = !searchTerm ||
                    task.title.toLowerCase().includes(searchTerm) ||
                    task.description.toLowerCase().includes(searchTerm) ||
                    formatDate(task.deadline).toLowerCase().includes(searchTerm);

                // Column filter
                const matchesColumn = !columnFilter || task.column === columnFilter;

                // Priority filter
                const matchesPriority = !priorityFilter || task.priority === priorityFilter;

                // Overdue filter
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const taskDeadline = new Date(task.deadline);
                taskDeadline.setHours(0, 0, 0, 0);
                const isOverdue = taskDeadline < today && task.column !== 'done';
                const matchesOverdue = !overdueFilter || isOverdue;

                return matchesSearch && matchesColumn && matchesPriority && matchesOverdue;
            });

            renderTasks();
        }

        // Toggle overdue filter
        function toggleOverdueFilter() {
            const btn = document.getElementById('overdueFilter');
            btn.classList.toggle('active');
            filterTasks();
        }

        // Create task card HTML
        function createTaskCard(task) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const taskDeadline = new Date(task.deadline);
            taskDeadline.setHours(0, 0, 0, 0);

            const isOverdue = taskDeadline < today && task.column !== 'done';
            const overdueClass = isOverdue ? 'overdue-task' : '';

            // Calculate subtask progress
            const completedSubtasks = task.subtasks?.filter(st => st.completed).length || 0;
            const totalSubtasks = task.subtasks?.length || 0;

            // Generate labels HTML
            const labelsHTML = task.labels?.map(label =>
                `<span class="task-label label-${label}">${label}</span>`
            ).join('') || '';

            // Generate subtasks HTML
            const subtasksHTML = task.subtasks?.slice(0, 3).map(subtask =>
                `<div class="subtask-item">
                    <input type="checkbox" class="subtask-checkbox" ${subtask.completed ? 'checked' : ''} 
                           onchange="toggleSubtask(${task.id}, ${subtask.id})">
                    <span class="subtask-text ${subtask.completed ? 'completed' : ''}">${subtask.text}</span>
                </div>`
            ).join('') || '';

            return `
                <div class="task-card ${overdueClass}" draggable="true" data-task-id="${task.id}" 
                     ondragstart="handleDragStart(event, ${task.id})">
                    <div class="task-header">
                        <div class="task-title">${task.title}</div>
                        <div class="task-priority priority-${task.priority}">
                            ${priorityIcons[task.priority] || ''}
                        </div>
                    </div>
                    
                    <div class="task-meta">
                        <div class="task-deadline">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            ${formatDate(task.deadline)}
                            ${isOverdue && task.column !== 'overdue' ? '<span style="color: #c53030; font-weight: bold; margin-left: 4px;">OVERDUE!</span>' : ''}
                        </div>
                        
                        <div class="task-labels">
                            ${labelsHTML}
                        </div>
                    </div>
                    
                    <div class="task-description">${task.description}</div>
                    
                    ${totalSubtasks > 0 ? `
                                                                        <div class="subtasks-container">
                                                                            ${subtasksHTML}
                                                                            ${totalSubtasks > 3 ? `<div class="subtask-item" style="font-style: italic;">...dan ${totalSubtasks - 3} lainnya</div>` : ''}
                                                                        </div>
                                                                    ` : ''}
                    
                    <div class="task-actions">
                        <div class="subtask-progress">
                            ${totalSubtasks > 0 ? `${completedSubtasks}/${totalSubtasks} subtasks` : ''}
                        </div>
                        <div class="task-buttons">
                            <button class="edit-btn" onclick="openEditTaskModal(${task.id})" title="Edit Task">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                            </button>
                            <button class="delete-btn" onclick="deleteTask(${task.id})" title="Hapus Task">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Render tasks in columns
        function renderTasks() {
            updateOverdueTasks();

            const columns = ['product-backlog', 'in-progress', 'pending', 'done', 'overdue'];

            columns.forEach(columnId => {
                const container = document.getElementById(columnId + '-tasks');
                const countElement = document.getElementById(columnId + '-count');
                const columnTasks = filteredTasks.filter(task => task.column === columnId);

                // Update task count
                countElement.textContent = columnTasks.length;

                if (columnTasks.length === 0) {
                    container.innerHTML = '<div class="empty-state">Belum ada tugas</div>';
                } else {
                    container.innerHTML = columnTasks.map(task => createTaskCard(task)).join('');
                }
            });
        }

        // Toggle subtask completion
        function toggleSubtask(taskId, subtaskId) {
            const task = tasks.find(t => t.id === taskId);
            if (task) {
                const subtask = task.subtasks.find(st => st.id === subtaskId);
                if (subtask) {
                    subtask.completed = !subtask.completed;
                    renderTasks();
                }
            }
        }

        // Drag and drop functions
        function handleDragStart(event, taskId) {
            draggedTask = tasks.find(task => task.id === taskId);
            event.target.classList.add('dragging');
            event.dataTransfer.effectAllowed = 'move';
        }

        function handleDragOver(event) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';

            const column = event.currentTarget;
            if (!column.classList.contains('drop-zone')) {
                column.classList.add('drop-zone');
            }
        }

        function handleDrop(event, columnId) {
    if (columnId === 'pending' && draggedTask.column !== 'pending') {
        pendingTaskId = draggedTask.id;
        openPendingModal();
        return;
    }
    if (columnId === 'done' && draggedTask.column !== 'done') {
        draggedTask.completedAt = new Date();
    }
            event.preventDefault();

            const columns = document.querySelectorAll('.column');
            columns.forEach(col => col.classList.remove('drop-zone'));

            if (draggedTask && draggedTask.column !== columnId) {
                const taskIndex = tasks.findIndex(task => task.id === draggedTask.id);
                if (taskIndex !== -1) {
                    tasks[taskIndex].column = columnId;
                    filterTasks(); // Use filterTasks instead of renderTasks to maintain filters
                }
            }

            draggedTask = null;

            const draggingElements = document.querySelectorAll('.dragging');
            draggingElements.forEach(el => el.classList.remove('dragging'));
        }

        // Delete task function
        function deleteTask(taskId) {
            if (confirm('Apakah Anda yakin ingin menghapus tugas ini?')) {
                tasks = tasks.filter(task => task.id !== taskId);
                filterTasks();
            }
        }

        // Modal functions
        function openAddTaskModal() {
            editingTaskId = null;
            currentSubtasks = [];

            document.getElementById('modalTitle').textContent = 'Tambah Task Baru';
            document.getElementById('submitBtn').textContent = 'Tambah Task';
            document.getElementById('taskModal').classList.add('active');
            document.body.style.overflow = 'hidden';

            // Reset form
            document.getElementById('taskForm').reset();
            updateSubtasksList();

            setTimeout(() => {
                document.getElementById('taskTitle').focus();
            }, 100);
        }

        function openEditTaskModal(taskId) {
            editingTaskId = taskId;
            const task = tasks.find(t => t.id === taskId);

            if (!task) return;

            document.getElementById('modalTitle').textContent = 'Edit Task';
            document.getElementById('submitBtn').textContent = 'Update Task';
            document.getElementById('taskModal').classList.add('active');
            document.body.style.overflow = 'hidden';

            // Fill form with task data
            document.getElementById('taskTitle').value = task.title;
            document.getElementById('taskPriority').value = task.priority;
            document.getElementById('taskDeadline').value = task.deadline;
            document.getElementById('taskColumn').value = task.column;
            document.getElementById('taskDescription').value = task.description;

            // Set labels
            const labelCheckboxes = document.querySelectorAll('.label-checkbox');
            labelCheckboxes.forEach(checkbox => {
                checkbox.checked = task.labels?.includes(checkbox.value) || false;
            });

            // Set subtasks
            currentSubtasks = [...(task.subtasks || [])];
            updateSubtasksList();

            setTimeout(() => {
                document.getElementById('taskTitle').focus();
            }, 100);
        }

        function closeTaskModal() {
            document.getElementById('taskModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            document.getElementById('taskForm').reset();
            currentSubtasks = [];
            editingTaskId = null;
        }

        // Subtask management
        function addSubtask() {
            const input = document.getElementById('subtaskInput');
            const text = input.value.trim();

            if (text) {
                currentSubtasks.push({
                    id: nextSubtaskId++,
                    text: text,
                    completed: false,
        createdBy: 'john'
                });
                input.value = '';
                updateSubtasksList();
            }
        }

        function removeSubtask(subtaskId) {
            currentSubtasks = currentSubtasks.filter(st => st.id !== subtaskId);
            updateSubtasksList();
        }

        function updateSubtasksList() {
            const container = document.getElementById('subtaskList');
            if (currentSubtasks.length === 0) {
                container.innerHTML =
                    '<div style="text-align: center; color: #a0aec0; font-style: italic; padding: 8px;">Belum ada subtask</div>';
            } else {
                container.innerHTML = currentSubtasks.map(subtask => `
                    <div class="subtask-edit-item">
                        <input type="checkbox" ${subtask.completed ? 'checked' : ''} 
                               onchange="currentSubtasks.find(st => st.id === ${subtask.id}).completed = this.checked">
                        <span class="subtask-edit-text">${subtask.text}</span>
                        <button type="button" class="remove-subtask-btn" onclick="removeSubtask(${subtask.id})">×</button>
                    </div>
                `).join('');
            }
        }

        // Add/Update task function
        function handleTaskSubmit(event) {
            event.preventDefault();

            const title = document.getElementById('taskTitle').value.trim();
            const priority = document.getElementById('taskPriority').value;
            const deadline = document.getElementById('taskDeadline').value;
            const column = document.getElementById('taskColumn').value;
            const description = document.getElementById('taskDescription').value.trim();

            // Get selected labels
            const labels = Array.from(document.querySelectorAll('.label-checkbox:checked')).map(cb => cb.value);

            if (title && priority && deadline && description) {
                if (editingTaskId) {
                    // Update existing task
                    const taskIndex = tasks.findIndex(t => t.id === editingTaskId);
                    if (taskIndex !== -1) {
                        tasks[taskIndex] = {
                            ...tasks[taskIndex],
                            title,
                            priority,
                            deadline,
                            column,
                            description,
                            labels,
                            subtasks: [...currentSubtasks]
                        };
                    }
                    alert('Task berhasil diupdate!');
                } else {
                    // Add new task
                    const newTask = {
                        id: nextTaskId++,
                        title,
                        priority,
                        deadline,
                        column,
                        description,
                        labels,
                        subtasks: [...currentSubtasks]
                    };

                    tasks.push(newTask);
                    alert('Task berhasil ditambahkan!');
                }

                filterTasks();
                closeTaskModal();
            }
        }

        // Logout function
        function logout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                alert('Anda telah berhasil logout!');
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const columns = document.querySelectorAll('.column');
            columns.forEach(column => {
                column.addEventListener('dragleave', function(e) {
                    if (!column.contains(e.relatedTarget)) {
                        column.classList.remove('drop-zone');
                    }
                });
            });

            // Form submit listener
            document.getElementById('taskForm').addEventListener('submit', handleTaskSubmit);

            // Close modal when clicking outside
            document.getElementById('taskModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeTaskModal();
                }
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && document.getElementById('taskModal').classList.contains(
                        'active')) {
                    closeTaskModal();
                }

                if (e.shiftKey && e.key.toLowerCase() === 'n' && !document.getElementById('taskModal')
                    .classList.contains('active')) {
                    e.preventDefault();
                    openAddTaskModal();
                }
            });

            // Subtask input enter key
            document.getElementById('subtaskInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addSubtask();
                }
            });

            // Initial render and periodic check for overdue tasks
            filterTasks();

            // Check for overdue tasks every minute
            setInterval(() => {
                updateOverdueTasks();
                filterTasks();
            }, 60000);
        });

        // Remove drop-zone class when drag ends
        document.addEventListener('dragend', function() {
            const columns = document.querySelectorAll('.column');
            columns.forEach(col => col.classList.remove('drop-zone'));

            const draggingElements = document.querySelectorAll('.dragging');
            draggingElements.forEach(el => el.classList.remove('dragging'));
        });
    </script>

    <!-- Tambahkan script flatpickr di bawah sebelum penutup body -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#taskDeadline", {
            dateFormat: "d-m-Y",
            allowInput: true,
        });

        // Fungsi format tanggal untuk tampilan kartu task
        function formatDate(dateString) {
            const date = new Date(dateString);
            const day = ("0" + date.getDate()).slice(-2);
            const month = ("0" + (date.getMonth() + 1)).slice(-2);
            const year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }

        // Saat membuka edit modal, ubah format tanggal ke dd-mm-yyyy
        function openEditTaskModal(taskId) {
            const task = tasks.find(t => t.id === taskId);
            if (!task) return;

            document.getElementById("taskDeadline").value = formatDate(task.deadline);
            // kode lain untuk mengisi form ...
        }

        // Saat simpan task, konversi tanggal dd-mm-yyyy ke format ISO yyyy-mm-dd untuk penyimpanan
        function convertToISODate(dateStr) {
            const parts = dateStr.split("-");
            return `${parts[2]}-${parts[1]}-${parts[0]}`;
        }

        // Pada fungsi handleTaskSubmit, konversi tanggal sebelum disimpan ke model
        function handleTaskSubmit(event) {
            event.preventDefault();
            const deadlineInput = document.getElementById("taskDeadline").value;
            const deadlineISO = convertToISODate(deadlineInput);

            // gunakan deadlineISO untuk disimpan ke model tasks
        }
    </script>
</body>

</html>
