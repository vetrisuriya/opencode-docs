# OpenCode TUI — Usage, Slash Commands & Configuration

## What is the TUI

**Simple meaning:** TUI stands for **Terminal User Interface** — a full-screen interactive chat screen that runs inside your terminal. It's the default screen you see when you type `opencode` with no extra arguments.

**Analogy:** If `opencode run` is like sending a **text message** and waiting for one reply, the TUI is like a **live phone call** — an ongoing back-and-forth conversation in one continuous session.

---

## Table of Contents
1. [Starting the TUI](#1-starting-the-tui)
2. [Referencing files with @](#2-referencing-files-with-)
3. [Running shell commands with !](#3-running-shell-commands-with-)
4. [All slash commands — list and use cases](#4-all-slash-commands--list-and-use-cases)
5. [Editor setup for /editor and /export](#5-editor-setup-for-editor-and-export)
6. [TUI configuration (tui.json)](#6-tui-configuration-tuijson)
7. [Attention (notifications & sounds)](#7-attention-notifications--sounds)
8. [Customization via command palette](#8-customization-via-command-palette)

---

## 1. Starting the TUI

```bash
opencode                    # start in current folder
opencode /path/to/project   # start in a specific folder
```

Once inside, just type a message and press enter — no special syntax needed for normal questions.

---

## 2. Referencing files with @

Typing `@` inside a message does a fuzzy search of files in your project and attaches the file's content to the conversation automatically.

```
How is auth handled in @packages/functions/src/api/index.ts?
```

**Why it matters:** You don't need to copy-paste code manually — OpenCode reads the exact file for you.

If you've configured named references (aliases) in your project, typing `@alias` adds that whole reference, and `@alias/` lets you browse files inside it.

---

## 3. Running shell commands with !

Starting a message with `!` runs it as a real shell command, and the output is added to the conversation as a result OpenCode can read.

```
!ls -la
```

**Use case:** Letting OpenCode "see" the result of a command (like a test run or a directory listing) without you having to describe it manually.

---

## 4. All slash commands — list and use cases

| Command | Purpose | Use Case |
|---|---|---|
| `/help` | Shows the help dialog | Quick reminder of what's available |
| `/connect` | Add/configure a provider's API key | First-time setup or adding a new provider |
| `/compact` (alias `/summarize`) | Compacts/summarizes the current session | Long sessions that are getting too big for context |
| `/details` | Toggle tool execution details on/off | Seeing (or hiding) exactly what OpenCode is doing behind the scenes |
| `/editor` | Opens an external editor to compose a message | Writing a long, complex prompt more comfortably than a terminal input line |
| `/exit` (aliases `/quit`, `/q`) | Exit OpenCode | Ending the session |
| `/export` | Export the conversation to Markdown and open it | Saving/sharing a conversation as a readable document |
| `/init` | Guided setup to create/update `AGENTS.md` | First-time project setup so OpenCode understands your codebase |
| `/models` | List available models | Checking or switching which AI model you're using |
| `/new` (alias `/clear`) | Start a new session | Beginning a fresh conversation without old context |
| `/redo` | Redo a previously undone message (and its file changes) | Restoring a change you undid by mistake |
| `/sessions` (aliases `/resume`, `/continue`) | List and switch between sessions | Going back to an earlier conversation |
| `/share` | Share the current session as a link | Sending a conversation to a teammate |
| `/themes` | List available color themes | Changing how the TUI looks |
| `/thinking` | Toggle visibility of the model's reasoning/thinking blocks | Seeing the model's reasoning process (for models that support it) |
| `/undo` | Undo the last message and its file changes | Reverting an AI change you didn't want |
| `/unshare` | Stop sharing the current session | Making a previously shared session private again |

**Note:** `/undo` and `/redo` work using Git behind the scenes, so your project folder needs to be a Git repository for these to work.

---

## 5. Editor setup for /editor and /export

Both `/editor` and `/export` open whatever editor is set in your `EDITOR` environment variable.

```bash
export EDITOR="code --wait"     # VS Code
export EDITOR=vim               # Vim
export EDITOR=nano              # Nano
```

Add this line to your shell profile (`~/.bashrc`, `~/.zshrc`) to make it permanent.

**Why `--wait` matters for GUI editors:** GUI editors like VS Code normally open and immediately hand control back to the terminal. `--wait` makes the editor block until you close the file, so OpenCode knows you're done writing.

---

## 6. TUI configuration (tui.json)

A separate config file just for how the TUI behaves (different from `opencode.json`, which controls server/runtime behavior).

```json
{
  "theme": "opencode",
  "leader_timeout": 2000,
  "keybinds": {
    "leader": "ctrl+x",
    "command_list": "ctrl+p"
  },
  "scroll_speed": 3,
  "diff_style": "auto",
  "cursor": { "style": "block", "blinking": true },
  "mouse": true
}
```

**Key options explained simply:**

| Option | What it controls |
|---|---|
| `theme` | Visual color theme |
| `keybinds` | Custom keyboard shortcuts — you only need to list the ones you want to change, the rest stay default |
| `leader_timeout` | How long OpenCode waits after you press the "leader" key before it expects the next key |
| `diff_style` | How code changes (diffs) are displayed — `auto` adapts to your terminal width, `stacked` always uses one column |
| `cursor` | Terminal cursor look and blink behavior |
| `mouse` | Turn mouse support on/off inside the TUI |
| `scroll_speed` / `scroll_acceleration` | Controls how fast/naturally scrolling feels |

---

## 7. Attention (notifications & sounds)

A feature that alerts you when OpenCode needs something — a question answered, a permission approved, an error, or a finished task.

```json
{
  "attention": {
    "enabled": true,
    "notifications": true,
    "sound": true,
    "volume": 0.4
  }
}
```

**Best use case:** Long-running tasks — you can switch to another window and still get notified when OpenCode needs your input or finishes.

**Note:** Disabled by default. Desktop notifications only fire when the terminal window isn't focused (so it doesn't interrupt you while you're actively watching it).

---

## 8. Customization via command palette

Press `ctrl+p` to open the command palette, where you can change TUI display settings that are saved automatically and remembered across restarts — for example, toggling whether your username shows next to your chat messages.
