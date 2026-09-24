# Running WordPress Locally via CLI (No XAMPP/WAMP)

## What is this about

**Simple meaning:** You can run a full WordPress site on your own computer — for testing, theme/plugin development, or learning — entirely from the terminal, without installing bulky all-in-one software like XAMPP or WAMP.

**Analogy:** XAMPP/WAMP is like buying a **pre-built kitchen set** (stove, sink, fridge all bundled together, take it or leave it). Running things via CLI tools is like **picking exactly the appliances you need** — lighter, faster, and easier to throw away and rebuild when you're done.

**Why it matters:** Five different tools solve this problem, each with a different tradeoff between speed, realism (does it use real MySQL/PHP or not), and setup effort. This file covers all five so you can pick the right one for the job.

---

## Table of Contents
1. [Quick comparison of all 5 methods](#1-quick-comparison-of-all-5-methods)
2. [Method 1 — DDEV (Docker-based developer CLI)](#2-method-1--ddev-docker-based-developer-cli)
3. [Method 2 — Docker CLI only (no extra tool)](#3-method-2--docker-cli-only-no-extra-tool)
4. [Method 3 — PHP built-in server (bare-metal)](#4-method-3--php-built-in-server-bare-metal)
5. [Method 4 — WordPress Playground CLI (npm, zero-dependency)](#5-method-4--wordpress-playground-cli-npm-zero-dependency)
6. [Method 5 — wp-env (npm, Docker-backed, official)](#6-method-5--wp-env-npm-docker-backed-official)
7. [Fixing "command not found" after npm global install](#7-fixing-command-not-found-after-npm-global-install)
8. [Pros & cons — all methods side by side](#8-pros--cons--all-methods-side-by-side)
9. [Best use case for each method](#9-best-use-case-for-each-method)

---

## 1. Quick comparison of all 5 methods

| Method | Needs Docker? | Needs PHP/MySQL installed? | Real MySQL DB? | Setup effort |
|---|---|---|---|---|
| DDEV | Yes | No | Yes (real, in a container) | Low — one tool handles everything |
| Docker CLI only | Yes | No | Yes (real, in a container) | Medium — you configure containers manually |
| PHP built-in server | No | Yes, both must already be on your system | Yes (your real local MySQL) | Medium — manual setup, no containers |
| WP Playground CLI (npm) | No | No | No — simulated in WebAssembly, not real MySQL | Very low — one command |
| wp-env (npm) | Yes | No | Yes (real, in a container) | Low — one command, but Docker required |

---

## 2. Method 1 — DDEV (Docker-based developer CLI)

**What it is:** A developer-focused tool that manages PHP, MySQL, and routing automatically, using Docker behind the scenes — without you having to write raw Docker commands yourself.

**Steps:**

```bash
# 1. Install DDEV and Docker Desktop
brew install ddev/ddev/ddev              # macOS (Homebrew)
winget install drud.ddev                 # Windows (PowerShell/WSL2)

# 2. Create and configure a project folder
mkdir my-wp-site && cd my-wp-site
ddev config --project-type=wordpress

# 3. Start the local environment
ddev start

# 4. Download and install WordPress using WP-CLI (built into DDEV)
ddev wp core download
ddev wp core install --url=https://ddev.site --title="My Local Site" \
  --admin_user=admin --admin_password=password --admin_email=admin@example.com

# 5. Open your site in the browser automatically
ddev launch
```

---

## 3. Method 2 — Docker CLI only (no extra tool)

**What it is:** Running WordPress and MySQL as two separate Docker containers directly, without any extra wrapper tool like DDEV.

**Steps:**

```bash
# 1. Create a shared network so the two containers can talk to each other
docker network create wp-network

# 2. Start a MySQL database container
docker run -d --name wp-db --network wp-network \
  -e MYSQL_ROOT_PASSWORD=secret_root \
  -e MYSQL_DATABASE=wordpress_db \
  -e MYSQL_USER=wp_user \
  -e MYSQL_PASSWORD=wp_pass \
  mysql:8.0

# 3. Start the WordPress container, connected to that database
docker run -d --name my-wordpress --network wp-network -p 8080:80 \
  -e WORDPRESS_DB_HOST=wp-db \
  -e WORDPRESS_DB_USER=wp_user \
  -e WORDPRESS_DB_PASSWORD=wp_pass \
  -e WORDPRESS_DB_NAME=wordpress_db \
  wordpress:latest

# 4. Open http://localhost:8080 in your browser
```

**Architecture (how the two containers connect):**

```
┌────────────────────┐         wp-network          ┌────────────────────┐
│   WordPress         │ ───────────────────────────▶│   MySQL Container   │
│   Container          │   talks to "wp-db" by name  │   (name: wp-db)      │
│   (port 8080 → 80)   │◀───────────────────────────│                       │
└────────────────────┘                              └────────────────────┘
        ▲
        │ localhost:8080
        │
   Your Browser
```

---

## 4. Method 3 — PHP built-in server (bare-metal)

**What it is:** Skips Docker entirely. Uses PHP and MySQL already installed directly on your system, plus PHP's own lightweight built-in web server — no Apache or Nginx needed.

**Steps:**

```bash
# 1. Download and extract WordPress
curl -O https://wordpress.org
tar -xzvf latest.tar.gz
cd wordpress

# 2. Create the database (log into your local MySQL)
mysql -u root -p
CREATE DATABASE wordpress_local;
EXIT;

# 3. Install WP-CLI (a command-line tool for managing WordPress)
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp

# 4. Generate config and install WordPress via WP-CLI
wp config create --dbname=wordpress_local --dbuser=root --dbpass=YOUR_PASSWORD
wp core install --url=http://localhost:8000 --title="Local WP" \
  --admin_user=admin --admin_password=adminpass --admin_email=info@example.com

# 5. Start PHP's built-in server (keep this terminal window open)
php -S localhost:8000
```

**Note:** This is the only method here that requires PHP and MySQL to already be manually installed on your machine beforehand — every other method provides them for you inside a container or WebAssembly.

---

## 5. Method 4 — WordPress Playground CLI (npm, zero-dependency)

**What it is:** The fastest method. Runs WordPress **entirely inside Node.js**, by compiling PHP into WebAssembly (`php-wasm`). No MySQL, Docker, Apache, or PHP needs to be installed on your system at all.

**Analogy:** Instead of building a real kitchen, this method runs a **realistic simulation** of one inside your computer's memory — good enough to cook in, but nothing is "really" installed anywhere.

**Steps:**

```bash
# 1. Install the Playground CLI globally
npm install -g @wp-playground/cli

# 2. Go to your project folder (can be empty, or a theme/plugin folder)
cd path/to/your/project

# 3. Start the environment
wp-playground server start

# 4. The terminal shows a local URL (e.g. http://localhost:8881)
#    and opens it automatically in your browser
```

**Note:** The older tool `@wp-now/wp-now` is deprecated — `@wp-playground/cli` replaced it officially.

---

## 6. Method 5 — wp-env (npm, Docker-backed, official)

**What it is:** The official WordPress core team's tool for local development, built on top of real Docker containers with a real MySQL database — used when you need production-accurate behavior, not a simulation.

**Prerequisite:** Docker Desktop must be installed and running.

**Steps:**

```bash
# 1. Install wp-env globally
npm install -g @wordpress/env

# 2. Go to your plugin/theme folder
cd my-wp-plugin

# 3. Start the environment
wp-env start
```

This automatically downloads the needed Docker containers, sets up WordPress and the database, and links your current folder directly into the container's `wp-content` directory — so edits to your plugin/theme files show up live.

**Login:** `http://localhost:8888` — username: `admin`, password: `password`

---

## 7. Fixing "command not found" after npm global install

**Why this happens:** `npm install -g` places the tool in a global folder. If your terminal's `PATH` doesn't include that folder, it won't recognize the command afterward, even though the install succeeded.

**Analogy:** Installing the package is like putting a new tool in a **storage room**. `PATH` is the **list of rooms your terminal checks** — if that room isn't on the list, the terminal says it can't find the tool, even though it's sitting right there.

### Quick fix — use `npx` (no setup needed)

`npx` downloads and runs a package on the fly, bypassing PATH issues entirely:

```bash
npx @wp-playground/cli start
npx @wordpress/env start
```

### Permanent fix — add npm's global folder to PATH

**Step 1 — find where npm stores global files:**
```bash
npm config get prefix
```
- Windows usually: `C:\Users\YOUR_USERNAME\AppData\Roaming\npm`
- Mac/Linux usually: `/usr/local` or `~/.npm-global`

**Step 2 — add it to PATH:**

*Windows:*
1. Search "Environment Variables" in the Start menu
2. Click **Environment Variables...**
3. Under "User variables", select **Path**, click **Edit**
4. Click **New**, paste the path from Step 1
5. Click OK, close all windows, restart your terminal

*Mac/Linux (Zsh/Bash):*
```bash
# Open your shell profile
nano ~/.zshrc      # or ~/.bashrc

# Add this line at the bottom
export PATH="$(npm config get prefix)/bin:$PATH"

# Apply the change
source ~/.zshrc
```

---

## 8. Pros & cons — all methods side by side

| Method | Pros | Cons |
|---|---|---|
| **DDEV** | Very easy once installed, handles PHP/MySQL/routing for you, good for teams | Requires installing DDEV itself plus Docker |
| **Docker CLI only** | No extra tool needed beyond Docker, full manual control | More manual steps, you manage networking yourself |
| **PHP built-in server** | No Docker at all, very lightweight | Requires PHP and MySQL already installed and configured correctly on your machine |
| **WP Playground CLI (npm)** | Fastest possible setup, zero dependencies, great for quick tests | Not a real MySQL database — not fully production-accurate |
| **wp-env (npm)** | Official WordPress tool, real database, auto-links your plugin/theme folder | Requires Docker Desktop running in the background |

---

## 9. Best use case for each method

- **DDEV** — daily development across multiple projects, when you want one consistent tool managing everything
- **Docker CLI only** — when you want full manual control and don't want to install any extra CLI tool beyond Docker itself
- **PHP built-in server** — quick bare-metal testing on a machine that already has PHP/MySQL set up, without touching Docker at all
- **WP Playground CLI (npm)** — the fastest way to try an idea, test a plugin quickly, or demo something without needing accuracy to a real production database
- **wp-env (npm)** — building a real plugin or theme that needs to behave exactly like it would on a live WordPress site, with an actual MySQL database
