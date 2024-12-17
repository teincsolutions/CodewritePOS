<head>
    <style>
        .notification {
            background-color: #0073e6;
            color: #fff;
            padding: 15px;
            text-align: center;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .notification h2 {
            margin: 0 0 10px 0;
            font-size: 20px;
        }

        .notification p {
            margin: 0;
            font-size: 14px;
        }

        .update-details {
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .update-details h3 {
            color: #333;
        }

        .update-details ul {
            line-height: 1.6;
        }
    </style>
</head>

<body>

    <!-- Top Notification Bar -->
    <div class="notification">
        <h2>🚀 New Update Available for CodewritePOS!</h2>
        <p>Search results in the POS section now appear at the top of the list.</p>
    </div>

    <!-- Update Details Section -->
    <div class="update-details">
        <h3>What's New?</h3>
        <ol>
            <li><b>Improved POS search functionality:</b> When you add a search result, it will now appear at the top of the list for easier access.</li>
            <li>Enhanced user experience and faster response times.</li>
            <li>Minor bug fixes and optimizations.</li>
        </ol>
    </div>
</body>

</html>