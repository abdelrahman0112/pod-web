# BEST WAY: Deploy Using Git

Hostinger's File Manager is garbage. Use Git instead.

## Step-by-Step:

### 1. Connect via SSH
```bash
ssh user@your-temp-url.hostingersite.com
```

### 2. Navigate to public_html
```bash
cd ~/domains/your-temp-url.hostingersite.com/public_html
```

### 3. Clone Your Repo
```bash
git clone https://github.com/abdelrahman-hamdy/pod-web.git .
```

### 4. Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
npm install --production
npm run build
```

### 5. Run Deployment Script
```bash
chmod +x deployment/deploy.sh
./deployment/deploy.sh
```

### 6. Configure .env
```bash
cp .env.example .env
# Edit .env with your Hostinger credentials
php artisan key:generate
```

### 7. Set Permissions
```bash
chmod -R 775 storage bootstrap/cache
```

### 8. Done!
Visit your temp URL!

---

That's it. No uploads, no ZIPs, no bullshit.
