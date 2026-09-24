# OpenCode CLI — All Commands, Purpose & Use Cases

## What is OpenCode

**Simple meaning:** OpenCode is a command-line AI coding tool. You run it inside a project folder, and it can read your code, answer questions, write code, and make changes — either through an interactive terminal screen (TUI) or through single commands you run like any other CLI tool.

**Analogy:** Think of OpenCode like a **junior developer who sits inside your terminal**. You can either sit with them and have a live conversation (the TUI), or just send them a task by text and walk away, checking the result later (the `run` command).

**What issue it solves:** Normally, using an AI coding assistant means copy-pasting code into a chat window. OpenCode instead lives inside your project, already has access to your files, and can be automated — used in scripts, CI pipelines, or a shared team server — not just a manual chat.

---

## Table of Contents
1. [Core ways to start OpenCode](#1-core-ways-to-start-opencode)
2. [All commands — quick reference table](#2-all-commands--quick-reference-table)
3. [Command details and use cases](#3-command-details-and-use-cases)

---

## 1. Core ways to start OpenCode

| Command | What it does |
|---|---|
| `opencode` | Opens the full-screen interactive terminal interface (TUI) for the current folder |
| `opencode run "..."` | Sends one message and prints the reply — no interactive screen, good for scripts |
| `opencode mini` | Opens a smaller, minimal interactive interface (lighter than the full TUI) |
| `opencode web` | Starts a local server and opens OpenCode in your browser |
| `opencode serve` | Starts a headless server (API access only, no visible interface) — used to power web/remote/CI use |

---

## 2. All commands — quick reference table

| Command | Purpose | Best Use Case |
|---|---|---|
| `tui` (default) | Opens the interactive chat screen | Day-to-day coding help inside a project |
| `run` | Sends a single prompt, no interactive screen | Scripts, automation, CI pipelines, quick one-off questions |
| `mini` | Lightweight interactive interface | Faster/simpler alternative to full TUI |
| `agent` | Create/list custom agents (with their own permissions & system prompt) | Building a restricted or specialized assistant (e.g., "only allowed to read files, never edit") |
| `attach` | Connects a TUI to an already-running remote OpenCode server | Using OpenCode from your laptop while it actually runs on a remote machine |
| `auth` | Manage provider login/API keys (login, list, logout) | Setting up or switching which AI provider (Anthropic, OpenAI, etc.) you use |
| `github` | Installs/runs the GitHub automation agent | Auto-reviewing pull requests inside GitHub Actions |
| `mcp` | Manage MCP (Model Context Protocol) servers — add, list, auth, logout, debug | Connecting OpenCode to external tools/data sources (e.g., a ticketing system or search tool) |
| `models` | Lists all available AI models from your configured providers | Finding the exact model name to put in your config |
| `serve` | Starts a headless API server (no UI) | Powering automation, remote access, or a shared team backend |
| `web` | Starts a server + opens a browser UI | Using OpenCode without a terminal, or from a phone/tablet on the same network |
| `session` | Manage sessions — list, delete, export, import | Reviewing past conversations, cleaning up old sessions, backing up/sharing a session |
| `stats` | Shows token usage and cost statistics | Tracking how much you're spending/using across projects |
| `export` | Exports a session as JSON (or Markdown from the TUI) | Saving a conversation outside OpenCode, sharing with a teammate |
| `import` | Imports a session from file or share URL | Restoring a session, or opening one someone shared with you |
| `acp` | Starts an Agent Client Protocol server (for editor integrations) | Connecting OpenCode into another IDE/tool that speaks ACP |
| `plugin` | Install/manage plugins | Extending OpenCode with extra tools or features |
| `pr` | Fetches and checks out a GitHub PR branch, then runs OpenCode | Quickly reviewing/working on someone else's pull request |
| `db` | Database tools for OpenCode's own internal data | Inspecting OpenCode's local storage directly |
| `debug` | Troubleshooting tools (config, paths, agents, MCP debug) | Diagnosing why something isn't working |
| `upgrade` | Updates OpenCode to latest (or a chosen) version | Keeping the tool up to date |
| `uninstall` | Removes OpenCode and related files | Cleanly removing the tool from your machine |
| `pair` | Prints a one-time sign-in link/QR code for browser or app | Logging a phone/browser into a running server quickly and securely |
| `service` | Manage the background server (start/stop/restart/status/settings) | Running OpenCode as a persistent background service instead of manually each time |
| `reload` | Reloads config without restarting the server | Applying config changes without losing the running session |
| `api` | Sends a raw request to a running OpenCode server | Scripting against OpenCode directly, similar to calling any REST API |

---

## 3. Command details and use cases

### `tui`
Starts the main interactive screen. This is what runs when you just type `opencode` with no arguments.
**Use case:** Everyday coding — asking questions, requesting changes, reviewing diffs, all in one live session.

### `run`
Sends one message and gets a printed reply, without opening the full interface.
**Use case:** Automation — e.g., in a CI pipeline: `opencode run "Review this repository for correctness and summarize any issues."` You can also attach to an already-running server with `--attach` to avoid slow startup on every call.

### `mini`
A smaller interactive interface, useful when you want interactivity but not the full-screen TUI.
**Use case:** Quick back-and-forth without the heavier TUI experience.

### `agent`
Lets you create a custom "agent" — a version of OpenCode with a fixed system prompt and a locked-down set of permissions (which tools it's allowed to use: bash, read, edit, webfetch, etc.).
**Use case:** Creating a safe "read-only reviewer" agent that can look at code and explain it, but is never allowed to edit files.

### `attach`
Connects a terminal (TUI) to an already-running OpenCode backend server elsewhere.
**Use case:** Running the actual OpenCode server on a powerful remote machine, and using your laptop just as the "screen" to interact with it.

### `auth`
Manages your login credentials for AI providers.
- `login` — add an API key for a provider
- `list` — see which providers you're logged into
- `logout` — remove saved credentials
**Use case:** Switching between two Anthropic accounts, or adding a new provider like OpenAI.

### `github`
Sets up and runs OpenCode as an automated GitHub bot.
- `install` — sets up the GitHub Actions workflow
- `run` — runs the agent (normally used inside GitHub Actions, not manually)
**Use case:** Automatic AI code review on every pull request.

### `mcp`
Manages connections to MCP servers — external tools/data OpenCode can use (similar to plugins for other tools).
- `add` — connect a new MCP server
- `list` — see connected servers and their status
- `auth` / `logout` — manage OAuth login for MCP servers that need it
- `debug` — troubleshoot a connection issue
**Use case:** Connecting OpenCode to a ticketing system, search tool, or database via MCP so it can pull live data into its answers.

### `models`
Lists every AI model available to you based on your configured providers.
**Use case:** Finding the exact `provider/model` string to put into a config file or the `--model` flag.

### `serve`
Starts OpenCode as a headless server — no visible screen, just an API.
**Use case:** Powering a web interface, a remote team server, or scripted access without needing the TUI running visibly.

### `web`
Starts a local server and automatically opens a browser-based version of OpenCode.
**Use case:** Working without a terminal window, or letting a teammate access OpenCode over the local network (`--hostname 0.0.0.0`).

### `session`
Manages your saved conversations.
- `list` — see all sessions
- `delete` — remove a session (and its child sessions)
**Use case:** Cleaning up old sessions, or checking which sessions exist before exporting one.

### `stats`
Shows how many tokens and how much cost your OpenCode usage has racked up.
**Use case:** Monitoring spend across projects or models, especially useful in a company setting with shared billing.

### `export`
Saves a session's full data as a JSON file.
**Use case:** Backing up a conversation, or sending it to a teammate outside OpenCode. The `--sanitize` flag removes sensitive data before exporting.

### `import`
Loads a session back in, either from a local JSON file or a shared OpenCode link.
**Use case:** Restoring an exported session, or opening a session a teammate shared with you.

### `acp`
Starts a server following the Agent Client Protocol, communicating over stdin/stdout.
**Use case:** Integrating OpenCode into another editor or tool that supports the ACP standard, instead of using OpenCode's own TUI.

### `plugin`
Installs and manages plugins that extend OpenCode's functionality.
**Use case:** Adding extra tools/behaviors that aren't built in by default.

### `pr`
Automatically checks out a GitHub pull request's branch and starts OpenCode in it.
**Use case:** Quickly reviewing or continuing work on a colleague's open pull request without manually finding and checking out the branch yourself.

### `db`
Low-level access to OpenCode's own internal database (its local storage).
**Use case:** Advanced troubleshooting or inspecting OpenCode's stored data directly (rarely needed for normal use).

### `debug`
A group of troubleshooting tools — checking config sources, file paths, agent setup, and MCP connection issues.
**Use case:** Figuring out why a setting isn't applying, or why a connection is failing.

### `upgrade`
Updates OpenCode itself to the newest version, or a specific version you choose.
**Use case:** Regular maintenance to get new features and fixes.

### `uninstall`
Completely removes OpenCode and its files from your system.
**Use case:** Cleanly removing the tool; flags let you keep your config or session data if you plan to reinstall later.

### `pair`
Generates a one-time, short-lived (5 minute) sign-in link and QR code to log a browser or mobile app into a running server.
**Use case:** Quickly and securely connecting a phone or second device to your running OpenCode server, without typing passwords.

### `service`
Manages OpenCode running as a persistent background service (not just a one-time terminal session).
- `start` / `stop` / `restart` / `status`
- `get` / `set` / `unset` — read or change settings like hostname, port, CORS, or environment variables for the background service
**Use case:** Running OpenCode continuously in the background (e.g., on a home server) rather than starting it manually every time.

### `reload`
Reloads OpenCode's configuration without restarting the whole server, so running sessions aren't interrupted.
**Use case:** Applying a config change (like a new MCP server) without losing an in-progress session.

### `api`
Sends a direct request to a running OpenCode server, similar to calling any REST API with curl.
**Use case:** Scripting custom automation against OpenCode's backend directly, beyond what `run` or the TUI offer.

---

*(For install instructions via npm and VS Code usage, see the separate file "OpenCode_Install_via_NPM_and_VSCode.md".)*
