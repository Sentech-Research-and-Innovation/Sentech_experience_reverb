<template>
<div class="sentalk-container">
    <div class="sentalk-header">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search by name">
        </div>
        <div class="action-buttons">
            <button class="btn btn-upload">
                <i class="fas fa-upload"></i> Upload
            </button>
            <button class="btn btn-download">
                <i class="fas fa-download"></i> Download
            </button>
        </div>
    </div>

    <div class="edition-card">
        <div class="edition-title">SenTalk August Edition 2025</div>
        <div class="edition-meta">
            <span class="creator">By Machabal</span>
            <span class="date">on 18 Aug 2025 7:14 AM</span>
            <span class="stats">
                - 109 views - 23 downloads - 3 likes
            </span>
            <a href="#" class="view-new-tab">view on a new tap</a>
        </div>

        <div class="pdf-preview">
            <div class="pdf-header">
                <h2>AUGUST 2025 EDITION</h2>
                <h3>SENTALK</h3>
            </div>
            <div class="pdf-content">
                <!-- PDF will be displayed here -->
                <div class="pdf-placeholder">
                    <i class="fas fa-file-pdf"></i>
                    <p>PDF Preview</p>
                </div>
            </div>
        </div>
    </div>

    <div class="pagination">
        <button class="pagination-btn">Previous</button>
        <button class="pagination-btn active">1</button>
        <button class="pagination-btn">2</button>
        <button class="pagination-btn">3</button>
        <button class="pagination-btn">4</button>
        <button class="pagination-btn">5</button>
        <button class="pagination-btn">6</button>
        <button class="pagination-btn">7</button>
        <button class="pagination-btn">8</button>
        <button class="pagination-btn">9</button>
        <button class="pagination-btn">10</button>
        <span class="pagination-ellipsis">...</span>
        <button class="pagination-btn">19</button>
        <button class="pagination-btn">20</button>
        <button class="pagination-btn">Next</button>
    </div>
</div>
<\template>
<style>
    .sentalk-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        max-width: 1000px;
        margin: 20px auto;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .sentalk-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .search-box {
        display: flex;
        align-items: center;
        background: #f5f7f9;
        border-radius: 4px;
        padding: 8px 12px;
        flex: 1;
        max-width: 300px;
    }

    .search-box i {
        color: #7a7a7a;
        margin-right: 8px;
    }

    .search-box input {
        border: none;
        background: transparent;
        outline: none;
        width: 100%;
        font-size: 14px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-upload {
        background: #4CAF50;
        color: white;
    }

    .btn-upload:hover {
        background: #3d8b40;
    }

    .btn-download {
        background: #2196F3;
        color: white;
    }

    .btn-download:hover {
        background: #0b7dda;
    }

    .edition-card {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .edition-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #2c3e50;
    }

    .edition-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 14px;
        color: #7a7a7a;
        margin-bottom: 20px;
        align-items: center;
    }

    .view-new-tab {
        color: #2196F3;
        text-decoration: none;
    }

    .view-new-tab:hover {
        text-decoration: underline;
    }

    .pdf-preview {
        background: white;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .pdf-header {
        background: #2c3e50;
        color: white;
        padding: 15px;
        text-align: center;
    }

    .pdf-header h2 {
        font-size: 18px;
        margin: 0 0 5px 0;
        font-weight: 600;
    }

    .pdf-header h3 {
        font-size: 14px;
        margin: 0;
        font-weight: 400;
        letter-spacing: 1px;
    }

    .pdf-content {
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f2f5;
    }

    .pdf-placeholder {
        text-align: center;
        color: #7a7a7a;
    }

    .pdf-placeholder i {
        font-size: 48px;
        color: #e74c3c;
        margin-bottom: 10px;
    }

    .pdf-placeholder p {
        margin: 0;
        font-size: 14px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 20px;
    }

    .pagination-btn {
        padding: 6px 12px;
        border: 1px solid #ddd;
        background: white;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
    }

    .pagination-btn:hover {
        background: #f5f5f5;
    }

    .pagination-btn.active {
        background: #2196F3;
        color: white;
        border-color: #2196F3;
    }

    .pagination-ellipsis {
        padding: 6px 4px;
    }

    @media (max-width: 768px) {
        .sentalk-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .search-box {
            max-width: 100%;
            width: 100%;
        }
        
        .action-buttons {
            width: 100%;
            justify-content: space-between;
        }
        
        .btn {
            flex: 1;
            justify-content: center;
        }
        
        .edition-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
    }
</style>
