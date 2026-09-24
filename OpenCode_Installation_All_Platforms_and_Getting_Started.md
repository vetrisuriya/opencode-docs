# OpenCode — Installation (All Platforms) & Getting Started

*(For the npm-only install method for restricted company laptops, see "OpenCode_Install_via_NPM_and_VSCode.md". This file covers every install method in general.)*

## Table of Contents
1. [Installing on Arch Linux](#1-installing-on-arch-linux)
2. [Installing on Windows](#2-installing-on-windows)
3. [Configure — connecting an AI provider](#3-configure--connecting-an-ai-provider)
4. [Initialize a project](#4-initialize-a-project)
5. [Usage — day to day workflow](#5-usage--day-to-day-workflow)
6. [Undo / Redo](#6-undo--redo)
7. [Sharing a conversation](#7-sharing-a-conversation)

---

## 1. Installing on Arch Linux

```bash
sudo pacman -S opencode           # Stable release
paru -S opencode-bin              # Latest version from AUR
```

**Use case:** `pacman` gives the stable, tested release. `paru` (AUR) gives the newest version faster, at the cost of being slightly less tested.

---

## 2. Installing on Windows

**Recommended: use WSL.** Running OpenCode through Windows Subsystem for Linux gives better performance and full feature compatibility compared to running it directly on Windows.

| Method | Command | Notes |
|---|---|---|
| Chocolatey | `choco install opencode` | Common Windows package manager |
| Scoop | `scoop install opencode` | Lightweight alternative to Chocolatey |
| npm | `npm install -g opencode-ai` | Works if Node.js/npm is already set up — often the option that survives strict IT restrictions |
| Mise | `mise use -g github:anomalyco/opencode` | For teams already using Mise to manage dev tool versions |
| Docker | `docker run -it --rm ghcr.io/anomalyco/opencode` | No install at all — runs in a disposable container, good for trying it out without touching your system |

**Note:** Bun-based install on Windows is still in progress. You can also download the binary directly from the project's Releases page if none of the package managers are usable.

---

## 3. Configure — connecting an AI provider

OpenCode needs at least one AI provider's API key before it can do anything.

```bash
opencode
/connect
```

- Pick a provider (OpenCode Zen is recommended if you're new — a pre-tested, curated model list)
- Sign in, add billing details, copy your API key
- Paste the API key when OpenCode asks for it

This is saved locally, so you won't need to repeat this every session.

---

## 4. Initialize a project

```bash
cd /path/to/project
opencode
/init
```

**What this does:** Scans your project and creates an `AGENTS.md` file describing your project's structure and coding patterns, so OpenCode (and anyone on your team using it) understands the codebase better in future sessions.

**Tip:** Commit `AGENTS.md` to Git so the whole team shares the same context.

---

## 5. Usage — day to day workflow

**Ask questions about the codebase:**
```
How is authentication handled in @packages/functions/src/api/index.ts
```
Use `@` to fuzzy-search and attach any file directly into your message.

**Add a feature — plan first (recommended for bigger changes):**

1. Press `Tab` to switch to **Plan mode** — OpenCode can only suggest an approach, not make changes yet
2. Describe the feature in detail, like explaining it to a junior developer on your team
3. Review the plan, give feedback, or attach a reference image if needed (drag-and-drop an image into the terminal)
4. Once happy, press `Tab` again to switch to **Build mode**, then approve the changes

**Make direct changes (for simple, well-understood tasks):**
```
We need to add authentication to the /settings route. Take a look at how this is
handled in the /notes route in @packages/functions/src/notes.ts and implement
the same logic in @packages/functions/src/settings.ts
```
**Tip:** The more detail and examples you give, the more accurate the result — vague instructions lead to guessed changes.

---

## 6. Undo / Redo

If a change wasn't what you wanted:
```
/undo
```
This removes the AI's last response and reverts any file changes it made (using Git behind the scenes — your project must be a Git repo for this to work). You can run `/undo` multiple times to step back further.

To bring the change back:
```
/redo
```

---

## 7. Sharing a conversation

```
/share
```
Creates a shareable link to the current conversation and copies it to your clipboard automatically. Conversations are **not** shared by default — you must run this command yourself.

**Best use case:** Showing a teammate exactly how a change was made, or getting review on an AI-suggested plan before building it.
