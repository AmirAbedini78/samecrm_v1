<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' || app()->getLocale() == 'fa' || app()->getLocale() == 'ur' || app()->getLocale() == 'he' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() == 'ar' || app()->getLocale() == 'fa' || app()->getLocale() == 'ur' || app()->getLocale() == 'he' ? 'rtl-layout' : 'ltr-layout' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RTL Test Page</title>
    <link href="public/vendor/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/rtl.css" rel="stylesheet">
    <style>
        .test-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .test-section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 3px;
        }
        .test-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .test-content {
            margin: 10px 0;
        }
        .icon-test {
            margin: 5px;
            padding: 5px;
            border: 1px solid #ccc;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>RTL Test Page</h1>
        <p>Current Language: {{ app()->getLocale() }}</p>
        <p>Direction: {{ app()->getLocale() == 'ar' || app()->getLocale() == 'fa' || app()->getLocale() == 'ur' || app()->getLocale() == 'he' ? 'RTL' : 'LTR' }}</p>
        
        <div class="test-section">
            <div class="test-title">Text Alignment Test</div>
            <div class="test-content">
                <p class="text-left">Left aligned text</p>
                <p class="text-right">Right aligned text</p>
                <p class="text-center">Center aligned text</p>
            </div>
        </div>
        
        <div class="test-section">
            <div class="test-title">Form Elements Test</div>
            <div class="test-content">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" class="form-control" placeholder="Enter your name">
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" class="form-control" placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label>Message:</label>
                    <textarea class="form-control" rows="3" placeholder="Enter your message"></textarea>
                </div>
            </div>
        </div>
        
        <div class="test-section">
            <div class="test-title">Icons Test</div>
            <div class="test-content">
                <div class="icon-test">
                    <i class="fa fa-chevron-left"></i> Left Arrow
                </div>
                <div class="icon-test">
                    <i class="fa fa-chevron-right"></i> Right Arrow
                </div>
                <div class="icon-test">
                    <i class="fa fa-arrow-left"></i> Left Arrow
                </div>
                <div class="icon-test">
                    <i class="fa fa-arrow-right"></i> Right Arrow
                </div>
            </div>
        </div>
        
        <div class="test-section">
            <div class="test-title">Table Test</div>
            <div class="test-content">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>John Doe</td>
                            <td>john@example.com</td>
                            <td>123-456-7890</td>
                        </tr>
                        <tr>
                            <td>Jane Smith</td>
                            <td>jane@example.com</td>
                            <td>098-765-4321</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="test-section">
            <div class="test-title">Buttons Test</div>
            <div class="test-content">
                <button class="btn btn-primary">Primary Button</button>
                <button class="btn btn-secondary">Secondary Button</button>
                <button class="btn btn-success">Success Button</button>
                <button class="btn btn-danger">Danger Button</button>
            </div>
        </div>
        
        <div class="test-section">
            <div class="test-title">Navigation Test</div>
            <div class="test-content">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="navbar-nav">
                        <a class="nav-link" href="#">Home</a>
                        <a class="nav-link" href="#">About</a>
                        <a class="nav-link" href="#">Contact</a>
                    </div>
                </nav>
            </div>
        </div>
        
        <div class="test-section">
            <div class="test-title">Card Test</div>
            <div class="test-content">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Card Title</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">This is a card with some content to test RTL layout.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="test-section">
            <div class="test-title">List Test</div>
            <div class="test-content">
                <ul>
                    <li>First item</li>
                    <li>Second item</li>
                    <li>Third item</li>
                </ul>
                <ol>
                    <li>First ordered item</li>
                    <li>Second ordered item</li>
                    <li>Third ordered item</li>
                </ol>
            </div>
        </div>
        
        <div class="test-section">
            <div class="test-title">Language Switch Test</div>
            <div class="test-content">
                <a href="?lang=en" class="btn btn-sm btn-outline-primary">English</a>
                <a href="?lang=ar" class="btn btn-sm btn-outline-primary">العربية</a>
                <a href="?lang=fa" class="btn btn-sm btn-outline-primary">فارسی</a>
                <a href="?lang=ur" class="btn btn-sm btn-outline-primary">اردو</a>
                <a href="?lang=he" class="btn btn-sm btn-outline-primary">עברית</a>
            </div>
        </div>
        
        <div class="test-section">
            <div class="test-title">RTL Components Test</div>
            <div class="test-content">
                <div class="card rtl-card">
                    <div class="card-header">
                        <h5 class="card-title">RTL Card Test</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">This is a test card for RTL layout.</p>
                        <a href="#" class="btn btn-primary">RTL Button</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="test-section">
            <div class="test-title">RTL Form Test</div>
            <div class="test-content">
                <form class="rtl-form">
                    <div class="form-group">
                        <label for="rtl-name">Name:</label>
                        <input type="text" class="form-control" id="rtl-name" placeholder="Enter your name">
                    </div>
                    <div class="form-group">
                        <label for="rtl-email">Email:</label>
                        <input type="email" class="form-control" id="rtl-email" placeholder="Enter your email">
                    </div>
                    <div class="form-group">
                        <label for="rtl-message">Message:</label>
                        <textarea class="form-control" id="rtl-message" rows="3" placeholder="Enter your message"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="public/js/rtl.js"></script>
</body>
</html>
