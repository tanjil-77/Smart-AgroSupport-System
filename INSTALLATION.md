# Smart AgroSupport System - Installation Guide

## Quick Setup Instructions

### Step 1: Install XAMPP
1. Download XAMPP from https://www.apachefriends.org/
2. Install XAMPP (PHP 7.4 or higher)
3. Start Apache and MySQL from XAMPP Control Panel

### Step 2: Setup Project
1. Copy this folder to: `C:\xampp\htdocs\`
2. Your path should be: `C:\xampp\htdocs\Smart AgroSupport System\`

### Step 3: Create Database
1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click "New" to create a database
3. Name it: `smart_agrosupport`
4. Click on the database
5. Click "SQL" tab
6. Open file: `config/database.sql`
7. Copy all SQL code and paste in the SQL tab
8. Click "Go" to execute

### Step 4: Verify Configuration
Open `config/config.php` and check:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Empty for XAMPP default
define('DB_NAME', 'smart_agrosupport');
```

### Step 5: Access Website
Open browser and visit:
```
http://localhost/Smart%20AgroSupport%20System/
```

## Default Login Credentials

### Admin Access:
- Email: `admin@agrosupport.com`
- Password: `admin123`

### Test the System:
1. Create a farmer account (Register as Farmer)
2. Login and add a crop post
3. Create a buyer account (Register as Buyer)
4. Login as buyer and view marketplace
5. Login as admin to manage prices

## Troubleshooting

### Error: "Connection failed"
- Make sure MySQL is running in XAMPP
- Check database name is `smart_agrosupport`
- Verify database credentials in config.php

### Error: "Page not found"
- Make sure Apache is running
- Check the folder is in htdocs
- Try: `http://localhost/Smart%20AgroSupport%20System/index.php`

### Error: "Access Denied"
- Default MySQL user: `root`
- Default password: empty (no password)

## Features Included

✅ User Authentication (Login/Register)
✅ Admin Dashboard with Statistics
✅ Farmer Dashboard (Post Crops)
✅ Buyer Marketplace (Browse & Contact)
✅ Crop Price Management
✅ Weather Advisory System
✅ Responsive Design
✅ Bangla Language Support

## Next Steps

1. **Add Crop Prices**: Login as admin → Go to "Crop Prices" → Add daily prices
2. **Test Farmer Flow**: Register as farmer → Add crop post → View in marketplace
3. **Test Buyer Flow**: Register as buyer → Browse crops → Contact farmer
4. **Customize**: Edit colors, add logo, modify content as needed

## File Structure

```
Smart AgroSupport System/
├── config/           # Configuration files
├── admin/            # Admin panel files
├── farmer/           # Farmer dashboard files
├── buyer/            # Buyer marketplace files
├── auth/             # Login/Register files
├── assets/           # CSS, JS, images
└── index.php         # Home page
```

## Database Tables

- `users` - All users (Admin, Farmer, Buyer)
- `crops` - Crop master data
- `crop_posts` - Farmer's crop listings
- `crop_prices` - Daily crop prices
- `weather_advisory` - Weather tips
- `notifications` - Alert system
- `crop_calendar` - Planting calendar
- `knowledge_base` - Farming knowledge

## Support

For any issues:
1. Check XAMPP Control Panel (Apache & MySQL running?)
2. Check browser console for errors (F12)
3. Check database is created properly
4. Verify PHP version: 7.4 or higher

---

**You're all set! Start using Smart AgroSupport System 🌾**
