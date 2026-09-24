# OpenCode Web Interface

## What is it

**Simple meaning:** A way to use OpenCode inside your web browser instead of a terminal. It's the same AI coding tool, same sessions, just a different screen to interact with it.

**Analogy:** It's like the difference between using **Gmail through the desktop app vs. through your browser** — same inbox, same emails, just a different window to view it through.

**Best use case:** Working without a terminal, accessing OpenCode from a tablet/phone on the same network, or letting a non-technical teammate view a session through a simple browser link.

---

## Table of Contents
1. [Getting started](#1-getting-started)
2. [Configuration options](#2-configuration-options)
3. [Authentication](#3-authentication)
4. [Using the web interface](#4-using-the-web-interface)
5. [Config file version](#5-config-file-version)

---

## 1. Getting started

```bash
opencode web
```

This starts a local server on `127.0.0.1` with a random available port, and opens OpenCode in your default browser automatically.

**Caution:** If you don't set a password (`OPENCODE_SERVER_PASSWORD`), the server has no security. That's fine for local-only use, but you must set a password before exposing it to your network.

**Windows tip:** Run this from WSL rather than plain PowerShell for the best file access and terminal integration.

---

## 2. Configuration options

| Flag | What it does | Use Case |
|---|---|---|
| `--port 4096` | Sets a fixed port instead of a random one | Predictable URL for bookmarking or scripting |
| `--hostname 0.0.0.0` | Makes the server reachable from other devices on your network (not just your own machine) | Accessing OpenCode from your phone or another computer |
| `--mdns` | Makes the server discoverable on the local network automatically as `opencode.local` | Avoiding the need to remember an IP address |
| `--mdns-domain myproject.local` | Custom name instead of the default `opencode.local` | Running multiple OpenCode servers on the same network without name clashes |
| `--cors https://example.com` | Allows a specific external website to talk to this server | Building a custom frontend that calls into OpenCode's API |

**Example — accessible on your network:**
```bash
opencode web --hostname 0.0.0.0
```
This shows both:
```
Local access:    http://localhost:4096
Network access:  http://192.168.1.100:4096
```

---

## 3. Authentication

```bash
OPENCODE_SERVER_PASSWORD=secret opencode web
```

This turns on basic authentication (username + password) before anyone can access the server. Username defaults to `opencode`, but can be changed with `OPENCODE_SERVER_USERNAME`.

**Why it matters:** Without this, anyone who can reach the server's address/port can use your OpenCode instance and see your project — this is the difference between a private tool and an open door.

---

## 4. Using the web interface

**Sessions:** The homepage shows your active sessions and lets you start new ones — same sessions as the TUI, since they share the same underlying data.

**Server Status:** Clicking "See Servers" shows connected backend servers and whether they're online.

**Attaching a terminal to the web server:**
```bash
# Start the web server
opencode web --port 4096

# In another terminal, attach the TUI to it
opencode attach http://localhost:4096
```
This lets you use the browser and the terminal **at the same time**, sharing the exact same sessions and state — useful if you prefer typing in a terminal but want to show progress to someone on a screen.

---

## 5. Config file version

Instead of flags, you can set the same options permanently in `opencode.json`:

```json
{
  "server": {
    "port": 4096,
    "hostname": "0.0.0.0",
    "mdns": true,
    "cors": ["https://example.com"]
  }
}
```

**Note:** Command line flags always override the config file if both are set.
