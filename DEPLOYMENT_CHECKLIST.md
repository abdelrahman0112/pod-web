# Production Deployment Checklist

## ⚠️ MANUAL STEPS REQUIRED:

### 1. Get Your Hostinger Credentials
- Log in to Hostinger Control Panel
- Get your temporary subdomain URL
- Create a database
- Get database credentials (DB_NAME, DB_USER, DB_PASSWORD)

### 2. Upload Files to Hostinger
Choose ONE method:

**Option A - File Manager:**
1. Go to Hostinger panel → File Manager
2. Navigate to `public_html`
3. Upload project ZIP
4. Extract files
5. Move ALL files from project folder to `public_html` root

**Option B - Git (Recommended):**
```bash
ssh user@your-temp-url.hostingersite.com
cd ~/domains/your-temp-url.hostingersite.com/public_html
git clone https://github.com/abdelrahman-hamdy/pod-web.git .
```

**Option C - FTP:**
- Use FileZilla or WinSCP
- Upload all files to `public_html`

### 3. Configure .env File
```bash
# On server, copy example file
cp .env.example .env

# Edit .env with these values:
```

**REQUIRED Changes:**
- `APP_KEY` - Run: `php artisan key:generate`
- `APP_URL` - Your temp URL from Hostinger
- `DB_DATABASE` - Your Hostinger database name
- `DB_USERNAME` - Your Hostinger database user
- `DB_PASSWORD` - Your Hostinger database password
- `MAIL_USERNAME` - Your email (e.g., noreply@yourdomain.com)
- `MAIL_PASSWORD` - Your email password

### 4. Run Deployment Commands
```bash
cd ~/domains/your-temp-url.hostingersite.com/public_html

# Or use the automated script:
chmod +x deployment/deploy.sh
./deployment/deploy.sh
```

### 5. Set Permissions
```bash
chmod -R 775 storage bootstrap/cache
chmod -R 755 .
```

### 6. Fix .htaccess
```bash
cp deployment/.htaccess.root .htaccess
```

### 7. Import Database
```bash
# Option A: Via phpMyAdmin in Hostinger panel
# Option B: Via SSH
mysql -u your_db_user -p your_db_name < your_backup.sql
```

---

## What's Already Configured:
✅ Production .env.example with all correct settings
✅ Deployment scripts ready
✅ Security configurations
✅ No extra files

Just follow the steps above!
