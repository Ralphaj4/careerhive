<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body data-page="jobs">
    <?php include('navbar.php') ?>
    <h2 style="margin-top: 20px; margin-left:10px;">Available Jobs</h2>
    <div class="jobs-container">
        <!-- Jobs section (left) -->
        <div class="jobs-wrapper" id="jobs-container">
            <div class="spinner-container">
                <div class="spinner"></div>
            </div>
        </div>
        
        <!-- Vertical line -->
        <div class="vertical-line"></div>
        
        <!-- Pages section (right) -->
        <div class="pages-wrapper" id="pages-container">
            <div class="spinner-container">
                <div class="spinner"></div>
            </div>
        </div>
    </div>

    <!-- Modal or Apply Form (optional) -->
    <div id="apply-form-container" style="display:none;">
        <!-- This container can hold a global form for applications if needed -->
    </div>

</body>

<script src="scripts.js"></script>
</html>
