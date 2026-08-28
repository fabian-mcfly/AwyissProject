# Awyiss Project

Starter project for [Awyiss CMS](https://github.com/fabian-mcfly/Awyiss), built on top of the CakePHP framework.

![Awyiss CMS](logo-awyiss.png)

![Awyiss Version](https://img.shields.io/badge/Awyiss%20Version-0.3.0%20%22Interface%22-63D1A5?style=for-the-badge&labelColor=131A21)\
![Project Version](https://img.shields.io/badge/Project%20Version-1.0.1-63D1A5?style=for-the-badge&labelColor=131A21)\
![PHP](https://img.shields.io/badge/PHP-8.4+-63D1A5?style=for-the-badge&labelColor=131A21)\
![License](https://img.shields.io/badge/License-MIT-63D1A5?style=for-the-badge&labelColor=131A21)

## Requirements

- PHP 8.4 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Web server (Apache or Nginx) with URL rewriting enabled and rights to create symlinks
- Shell access with PHP CLI
- Cronjob support (often & long-running)
- Imagick or GD PHP extension (for image manipulation)
- FileInfo PHP extension
- Intl PHP extension

## Installation

> [!TIP]
> It's recommended to run the command as the web server user to ensure proper file permissions,
> for example using `sudo runuser -u <username> -- <command>` on Debian-based systems.

Create a new project with Composer:

```bash
composer create-project awyiss/project your-project-name
```

This installs Awyiss along with its dependencies and then automatically runs the Awyiss installer, which will ask you for:

1. **Customer name** – unique identifier for your project, used as the default namespace for plugins and themes
2. **Database credentials** – leave empty to skip database setup
3. **Admin username**
4. **Admin password** – leave empty to generate a random one
5. **Environment** (`development` by default) – sets `CONFIG_ENV` in your `.env` file

> [!IMPORTANT]
> Passwords are visible when typed in the terminal. Don't use sensitive passwords if others can see your terminal.

Forgot your admin password? Reset it with:

```bash
bin/cake awyiss reset_password
```

## Configure your Web Server

Point your web server to the `webroot` directory and set up the cronjobs:

```bash
*/10 * * * * cd /var/www/ && bin/cake queue run -q -g general >> /var/www/logs/cron.log 2>&1
*/1 * * * * cd /var/www/ && bin/cake media convert_files --include-avif --include-webp -q
```

> [!IMPORTANT]
> Only include `--include-avif` / `--include-webp` if your server can handle these formats. Check with:
> ```bash
> bin/cake media detect_available_commands
> ```

Once configured, access the Backend at `http://your-domain.com/backend` with the credentials from the installer.

## Documentation

More detailed documentation is available in the [official documentation](https://docs.awyiss.2f.media).

## License

**Awyiss** is licensed under the MIT License.\
See the [LICENSE](LICENSE) file for details.

## Support

For support inquiries, please contact [awyiss@2f.media](mailto:awyiss@2f.media).

---

© 2025-2026 Awyiss CMS. All rights reserved.