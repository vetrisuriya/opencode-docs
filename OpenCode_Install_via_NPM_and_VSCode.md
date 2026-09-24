# Installing OpenCode via NPM & Using it in VS Code

*(For company laptops that block direct downloads/installers — npm install works because it just uses your existing Node.js/npm setup, not a separate installer.)*

## Table of Contents
1. [Why npm install works when direct downloads are blocked](#1-why-npm-install-works-when-direct-downloads-are-blocked)
2. [Step 1 — Check Node.js and npm are installed](#2-step-1--check-nodejs-and-npm-are-installed)
3. [Step 2 — Install OpenCode globally via npm](#3-step-2--install-opencode-globally-via-npm)
4. [Step 3 — Verify the install](#4-step-3--verify-the-install)
5. [Step 4 — Connect an AI provider](#5-step-4--connect-an-ai-provider)
6. [Step 5 — Initialize your project](#6-step-5--initialize-your-project)
7. [Using OpenCode inside VS Code](#7-using-opencode-inside-vs-code)
8. [VS Code extension (auto-installed from the terminal)](#8-vs-code-extension-auto-installed-from-the-terminal)
9. [Setting up the editor for /editor and /export](#9-setting-up-the-editor-for-editor-and-export)
10. [Basic daily usage once set up](#10-basic-daily-usage-once-set-up)
11. [Troubleshooting](#11-troubleshooting)

---

## 1. Why npm install works when direct downloads are blocked

Many companies block `.exe` installers, package managers like Chocolatey/Scoop, or direct binary downloads from the internet, but still allow **npm** — because npm just downloads packages from the npm registry, the same way it installs any coding library your team already uses.

**Analogy:** Blocking installers is like locking the **front door** of a building. npm install is like using the **staff entrance** that's already open because your company allows Node.js/npm for development work.

**Important:** This still needs your company's network to allow npm registry access (`registry.npmjs.org`). If npm itself is also blocked, you'd need IT to allow it — this method only helps when the block is specifically on installers/downloaders, not on npm itself.

---

## 2. Step 1 — Check Node.js and npm are installed

Open a terminal (in VS Code: **Terminal → New Terminal**) and run:

```bash
node -v
npm -v
```

If both show a version number, you're ready. If not, Node.js needs to be installed first — most companies already allow this since it's a standard dev tool, unlike random installers.

---

## 3. Step 2 — Install OpenCode globally via npm

```bash
npm install -g opencode-ai
```

**What this does:** Installs the OpenCode CLI globally on your machine, so you can run `opencode` from any folder in your terminal, just like `git` or `node`.

---

## 4. Step 3 — Verify the install

```bash
opencode --version
```

If it prints a version number, the install worked.

---

## 5. Step 4 — Connect an AI provider

OpenCode needs an AI provider (like Anthropic, OpenAI, etc.) connected before it can respond to anything.

```bash
opencode
/connect
```

- Select a provider (OpenCode Zen is the recommended starting option — a pre-tested list of models)
- Sign in, get your API key
- Paste the API key when prompted

This is stored locally so you don't need to log in every time.

---

## 6. Step 5 — Initialize your project

Navigate to your project folder and run:

```bash
cd /path/to/project
opencode
/init
```

**What this does:** OpenCode scans your project and creates an `AGENTS.md` file — a summary of your project's structure and coding patterns, so future sessions understand your codebase better.

**Tip:** Commit `AGENTS.md` to Git so your whole team benefits from it, not just you.

---

## 7. Using OpenCode inside VS Code

Once installed via npm, OpenCode also works as a proper VS Code extension — with shortcuts, not just a plain terminal tool.

| Shortcut (Mac) | Shortcut (Windows/Linux) | What it does |
|---|---|---|
| `Cmd+Esc` | `Ctrl+Esc` | Quick launch — opens OpenCode in a split terminal, or focuses it if already open |
| `Cmd+Shift+Esc` | `Ctrl+Shift+Esc` | Starts a brand new OpenCode session, even if one is already running |
| `Cmd+Option+K` | `Alt+Ctrl+K` | Insert a file reference into your message (e.g., `@File#L37-42`) |

**Context awareness:** OpenCode automatically knows what file/selection you currently have open in VS Code and can use that as context — you don't need to manually paste code.

---

## 8. VS Code extension (auto-installed from the terminal)

You don't need to search the Extension Marketplace manually — it installs itself automatically:

1. Open VS Code
2. Open the integrated terminal (View → Terminal)
3. Run `opencode`
4. The VS Code extension installs itself the first time you do this

**If it doesn't auto-install (manual fallback):**
- Open the Extensions panel in VS Code
- Search for "OpenCode"
- Click Install

**If it still fails:**
- Make sure you're running `opencode` from VS Code's own integrated terminal (not an external terminal)
- Confirm the `code` command works in your terminal — if not, open the Command Palette (`Cmd+Shift+P` / `Ctrl+Shift+P`) and search for **"Shell Command: Install 'code' command in PATH"**

---

## 9. Setting up the editor for /editor and /export

Some OpenCode commands (`/editor`, `/export`) open an external editor. To make this VS Code:

```bash
export EDITOR="code --wait"
```

Add this line to your shell profile file (`~/.bashrc`, `~/.zshrc`, etc.) so it applies every time, not just for one terminal session.

**Why `--wait` matters:** VS Code normally opens and returns control immediately. The `--wait` flag makes it pause until you close the tab — so OpenCode knows when you've finished editing.

---

## 10. Basic daily usage once set up

**Ask a question about your code:**
```
How is authentication handled in @packages/functions/src/api/index.ts
```
(The `@` lets you fuzzy-search and attach a file directly.)

**Plan before building (safe mode, no changes made):**
Press `Tab` to switch to Plan mode, describe what you want, review the plan, then press `Tab` again to switch to Build mode and approve the changes.

**Direct changes (no plan step) — for simple tasks:**
```
We need to add authentication to the /settings route. Take a look at how this is 
handled in /notes and implement the same logic in /settings.
```

**Undo a change:**
```
/undo
```

**Redo it:**
```
/redo
```

**Share a conversation with your team:**
```
/share
```

---

## 11. Troubleshooting

| Problem | Fix |
|---|---|
| `npm install -g` fails with permission error | Try `sudo npm install -g opencode-ai` (Mac/Linux), or configure npm to use a folder you own without sudo |
| `opencode` command not found after install | Restart the terminal, or check that npm's global bin folder is in your system PATH |
| Company blocks `registry.npmjs.org` | This method won't work — you'll need IT to allow npm registry access, or use an internal npm mirror if your company has one |
| VS Code extension doesn't appear | Confirm the `code` command works in terminal (see Step 8), then re-run `opencode` from VS Code's integrated terminal |
| Provider not responding / auth errors | Run `opencode auth list` to check login status, then `opencode auth login` again if needed |

---

*(For the full list of CLI commands and their use cases, see the separate file "OpenCode_CLI_Commands_Reference.md".)*
