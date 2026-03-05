<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recent Notices</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Full page coverage */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #e0e0e0;
        }
        
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
            padding: 0px;
            box-sizing: 100%;
        }
        
        .notices-panel {
            background-color: #e0e0e0;
            border-radius: 10px;
            width: 100%;
            height: 100%;
            max-width: 100%;
            padding: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: static;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        h1 {
            font-size: 24px;
            color: #333;
            margin: 0;
        }
        
        .controls {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .add-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .add-btn:hover {
            background-color: #3e8e41;
        }
        
        .search-container {
            display: flex;
            align-items: center;
        }
        
        .search-container input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
            border-right: none;
        }
        
        .search-btn {
            background-color: white;
            border: 1px solid #ddd;
            border-left: none;
            padding: 8px 12px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        
        tbody tr {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        tbody tr:hover {
            background-color: #f5f5f5;
        }
        
        .delete-btn {
            background: none;
            border: none;
            color: #5c85d6;
            cursor: pointer;
            font-size: 18px;
        }
        
        .delete-btn:hover {
            color: #f44336;
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: #fefefe;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .modal-title {
            font-size: 20px;
            font-weight: bold;
        }
        
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: #333;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .notice-details {
            padding: 15px 0;
        }
        
        .notice-content {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .notice-meta {
            color: #666;
            font-size: 14px;
        }
        
        .attachment-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #0066cc;
            text-decoration: none;
            margin-top: 10px;
        }
        
        /* Responsive styles */
        @media screen and (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .controls {
                width: 100%;
                justify-content: space-between;
            }
            
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 8px 10px;
            }
            
            .notices-panel {
                padding: 15px;
            }
            
            .search-container input {
                width: 120px;
            }
        }

        /* Add highlight for selected row and hover */
        .selected-row {
            background-color: #d0eaff !important;
        }
        tbody tr:hover {
            background-color: #e6f7ff !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="notices-panel" aria-label="Notices Panel">
            <div class="header">
                <h1>Recent notices</h1>
                <div class="controls">
                    <button class="add-btn" id="addNoticeBtn" title="Add a new notice" aria-label="Add notice">
                        <i class="fas fa-plus"></i> Add notice
                    </button>
                    <div class="search-container">
                        <input type="text" id="searchInput" placeholder="Search by title, owner, or content" aria-label="Search notices">
                        <button class="search-btn" id="searchBtn" title="Search notices" aria-label="Search">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <table id="noticesTable" aria-label="Notices Table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>To</th>
                        <th>From</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- JavaScript will populate this -->
                </tbody>
            </table>
            <div id="noNoticesMsg" style="display:none;text-align:center;color:#888;margin-top:20px;">
                No notices found.
            </div>
        </div>
    </div>
    
    <!-- Add Notice Modal -->
    <div id="addNoticeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Add New Notice</div>
                <span class="close">&times;</span>
            </div>
            <form id="noticeForm">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" required>
                </div>
                <div class="form-group">
                    <label for="content">Description</label>
                    <textarea id="content" rows="5" required></textarea>
                </div>
                <div>
                    
                </div>                
                <div class="form-group">
                    <label for="date">Date and Time</label>
                    <input type="datetime-local" id="date" required>
                </div>
                <div class="form-group">
                    <label for="to">To</label>
                    <select id="owner" required>
                        <option value="" disabled selected>Select recipient</option>
                        <option value="Staff">Staff</option>
                        <option value="Student">Student</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="attachment">Attachment (optional)</label>
                    <input type="file" id="attachment" name="attachment">
                </div>
                <button type="submit" class="submit-btn">Add Notice</button>
                <button type="button" class="submit-btn" id="cancelAddNoticeBtn" style="background-color:#aaa;margin-left:10px;">Cancel</button>
            </form>
        </div>
    </div>
    
    <!-- Notice Details Modal -->
    <div id="noticeDetailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="detailsTitle">Notice Details</div>
                <span class="close">&times;</span>
            </div>
            <div class="notice-details" id="noticeDetails">
                <!-- JavaScript will populate this -->
            </div>
            <button type="button" class="submit-btn" id="cancelDetailsBtn" style="background-color:#aaa;margin-top:10px;">Cancel</button>
        </div>
    </div>

    <script>
        // Sample notices data with content and attachments
        //const notices = [
        //    {
       //         title: "Exams",
        //        date: "11-03-2025 10:00am",
       //         owner: "Dean of Students",
        //        content: "Final exams for all students will be held from March 15th to March 30th, 2025. Please ensure that you check your exam schedule and arrive at least 15 minutes before the start time. Bring your student ID and necessary stationery.",
       //         attachment: "exam_schedule.pdf"
       //     },
       //     {
       //         title: "Handover",
       //         date: "11-03-2025 10:30am",
       //         owner: "Dean of students",
       //         content: "All outgoing student leaders are requested to prepare their handover reports and submit them by March 10th, 2025. The official handover ceremony will take place on March 11th at the Main Hall.",
       //         attachment: "handover_template.docx"
       //     }
       // ];
        
        // DOM elements
        const noticesTable = document.getElementById('noticesTable');
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        const addNoticeBtn = document.getElementById('addNoticeBtn');
        const addNoticeModal = document.getElementById('addNoticeModal');
        const noticeDetailsModal = document.getElementById('noticeDetailsModal');
        const noticeForm = document.getElementById('noticeForm');
        const closeButtons = document.querySelectorAll('.close');
        
        // Render notices table with "No notices" message
        function renderNotices(noticesList) {
            const tbody = noticesTable.querySelector('tbody');
            tbody.innerHTML = '';
            const noNoticesMsg = document.getElementById('noNoticesMsg');
            if (noticesList.length === 0) {
                noNoticesMsg.style.display = 'block';
                return;
            } else {
                noNoticesMsg.style.display = 'none';
            }
            noticesList.forEach((notice, index) => {
                const tr = document.createElement('tr');
                tr.dataset.index = index;
                tr.tabIndex = 0;
                tr.setAttribute('aria-label', `Notice: ${notice.title}`);
                tr.innerHTML = `
                    <td>${notice.title}</td>
                    <td>${notice.date}</td>
                    <td>${notice.owner}</td>
                    <td><button class="delete-btn" title="Delete this notice" aria-label="Delete"><i class="fas fa-trash"></i></button></td>
                `;
                tbody.appendChild(tr);
            });
        }
        
        // Show notice details
        function showNoticeDetails(index) {
            const notice = notices[index];
            const detailsTitle = document.getElementById('detailsTitle');
            const noticeDetails = document.getElementById('noticeDetails');
            
            detailsTitle.textContent = notice.title;
            
            let attachmentHtml = '';
            if (notice.attachment) {
                attachmentHtml = `
                    <div>
                        <a href="#" class="attachment-link" title="Download attachment" aria-label="Download attachment: ${notice.attachment}">
                            <i class="fas fa-download"></i> ${notice.attachment}
                        </a>
                    </div>
                `;
            }
            
            noticeDetails.innerHTML = `
                <div class="notice-content">${notice.content}</div>
                <div class="notice-meta">
                    <div><strong>Date:</strong> ${notice.date}</div>
                    <div><strong>Owner:</strong> ${notice.owner}</div>
                    ${attachmentHtml}
                </div>
            `;
            
            noticeDetailsModal.style.display = 'flex';
            // Focus for accessibility
            noticeDetailsModal.querySelector('.close').focus();
        }
        
        // Delete notice
        function deleteNotice(index) {
            if (confirm('Are you sure you want to delete this notice?')) {
                notices.splice(index, 1);
                renderNotices(notices);
            }
        }
        
        // Search notices
        function searchNotices() {
            const searchTerm = searchInput.value.toLowerCase();
            const filteredNotices = notices.filter(notice => 
                notice.title.toLowerCase().includes(searchTerm) || 
                notice.owner.toLowerCase().includes(searchTerm) ||
                notice.content.toLowerCase().includes(searchTerm)
            );
            renderNotices(filteredNotices);
        }
        
        // Format date for new notices
        function formatDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            
            return `${day}-${month}-${year} ${hours}:${minutes}am`;
        }
        
        // Event Listeners
        
        // Table row click (show details)
        noticesTable.addEventListener('click', function(e) {
            // If the click is on the delete button, handle delete instead
            if (e.target.closest('.delete-btn')) {
                const row = e.target.closest('tr');
                const index = row.dataset.index;
                deleteNotice(index);
                return; // Prevent showing details
            }
            
            // Otherwise show notice details
            const row = e.target.closest('tr');
            if (row) {
                const index = row.dataset.index;
                showNoticeDetails(index);
                // Highlight selected row
                Array.from(noticesTable.querySelectorAll('tbody tr')).forEach(r => r.classList.remove('selected-row'));
                row.classList.add('selected-row');
            }
        });

        // Keyboard accessibility for table rows
        noticesTable.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                const row = e.target.closest('tr');
                if (row && row.dataset.index) {
                    showNoticeDetails(row.dataset.index);
                }
            }
        });

        // Open add notice modal
        addNoticeBtn.addEventListener('click', function() {
            addNoticeModal.style.display = 'flex';
            addNoticeBtn.disabled = true;
            // Focus first input for accessibility
            setTimeout(() => document.getElementById('title').focus(), 100);
        });

        // Close modals
        closeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.modal').style.display = 'none';
                addNoticeBtn.disabled = false;
            });
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
                addNoticeBtn.disabled = false;
            }
        });

        // Close modal on Escape key
        window.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal').forEach(modal => {
                    if (modal.style.display === 'flex') {
                        modal.style.display = 'none';
                        addNoticeBtn.disabled = false;
                    }
                });
            }
        });

        // Add new notice
        noticeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const title = document.getElementById('title').value;
            const content = document.getElementById('content').value;
            const dateInput = document.getElementById('date').value;
            const owner = document.getElementById('owner').value;
            const attachment = document.getElementById('attachment').files[0];
            
            // Add new notice to array
            notices.push({
                title: title,
                date: formatDate(dateInput),
                owner: owner,
                content: content,
                attachment: attachment ? attachment.name : null
            });
            
            // Render updated notices
            renderNotices(notices);
            
            // Reset form and close modal
            noticeForm.reset();
            addNoticeModal.style.display = 'none';
            addNoticeBtn.disabled = false;
        });

        // Search on button click
        searchBtn.addEventListener('click', searchNotices);
        
        // Search on enter key
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchNotices();
            }
        });
        
        // Cancel buttons
        document.getElementById('cancelAddNoticeBtn').addEventListener('click', function() {
            addNoticeModal.style.display = 'none';
            addNoticeBtn.disabled = false;
        });
        document.getElementById('cancelDetailsBtn').addEventListener('click', function() {
            noticeDetailsModal.style.display = 'none';
        });
        
        // Initial render
        renderNotices(notices);
    </script>
</body>
</html>
