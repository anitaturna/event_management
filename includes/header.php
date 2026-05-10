<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Dynamic Title -->
    <title><?php echo isset($page_title) ? $page_title . " | EventPro" : "EventPro - Premium Event Management"; ?></title>

    <!-- 1. Google Fonts: Inter for UI & Playfair Display for Headings -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- 2. Tabler Icons (Premium SVG Icons) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- 3. Global CSS Variable & Root Styles -->
    <style>
        :root {
            --primary-blue: #2563eb;
            --dark-navy: #0f172a;
            --slate-600: #475569;
            --slate-500: #64748b;
            --bg-light: #f8fafc;
            --sidebar-width: 240px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--dark-navy);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* কমন অ্যাডমিন লেআউট ফিক্স */
        .admin-shell {
            display: flex;
            min-height: 100vh;
        }

        .admin-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 40px;
            width: calc(100% - var(--sidebar-width));
            transition: all 0.3s ease;
        }

        @media (max-width: 992px) {
            .admin-content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }
        }
    </style>

    <!-- 4. Your Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo SITEURL; ?>assets/css/style.css">

</head>
<body>