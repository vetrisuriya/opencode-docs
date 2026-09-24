# OpenCode IDE Integration (VS Code, Cursor, Windsurf, VSCodium)

## What is it

**Simple meaning:** A built-in extension that connects OpenCode directly to your code editor, so you get shortcuts, automatic context (what file/code you're looking at), and quick file references — instead of using a plain standalone terminal.

**Analogy:** Using OpenCode without the IDE integration is like giving directions **over the phone**. Using it with the IDE extension is like sitting **next to someone pointing at the map together** — it can already see what you're looking at.

**Works with:** VS Code and popular forks — Cursor, Windsurf, VSCodium.

---

## Table of Contents
1. [Usage shortcuts](#1-usage-shortcuts)
2. [Installation](#2-installation)
3. [Manual install](#3-manual-install)
4. [Troubleshooting](#4-troubleshooting)

---

## 1. Usage shortcuts

| Shortcut (Mac) | Shortcut (Win/Linux) | What it does |
|---|---|---|
| `Cmd+Esc` | `Ctrl+Esc` | Quick Launch — opens OpenCode in a split terminal, or focuses an already-open session |
| `Cmd+Shift+Esc` | `Ctrl+Shift+Esc` | New Session — starts a fresh OpenCode session even if one is already running (also available via the OpenCode button in the UI) |
| `Cmd+Option+K` | `Alt+Ctrl+K` | Insert a file reference into your message, e.g. `@File#L37-42` |

**Context Awareness:** Whatever file or code selection you currently have open in the editor is automatically shared with OpenCode — you don't need to copy-paste it.

---

## 2. Installation

The extension installs itself automatically the first time you run OpenCode from the editor's own terminal:

1. Open VS Code (or Cursor, Windsurf, VSCodium)
2. Open the integrated terminal
3. Run `opencode` — the extension installs on its own

If you want `/editor` or `/export` to open your own IDE instead of a plain text editor, set:
```bash
export EDITOR="code --wait"
```

---

## 3. Manual install

If the automatic install doesn't happen, search **"OpenCode"** in the editor's Extension Marketplace and click Install.

---

## 4. Troubleshooting

If the extension fails to install automatically, check the following in order:

- Make sure you're running `opencode` inside the editor's **own integrated terminal** (not an external terminal window)
- Confirm the editor's command-line tool is available in your terminal:

| Editor | Command to check |
|---|---|
| VS Code | `code` |
| Cursor | `cursor` |
| Windsurf | `windsurf` |
| VSCodium | `codium` |

- If the command isn't found, open the Command Palette (`Cmd+Shift+P` / `Ctrl+Shift+P`) and search for **"Shell Command: Install 'code' command in PATH"** (or the equivalent phrase for your editor)

**Note:** Some GUI editors need the `--wait` flag to work correctly with `/editor` and `/export` — without it, OpenCode won't know when you've finished editing.
