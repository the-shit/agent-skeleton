# Agent Skeleton

Laravel 13 template for Odin AI agents. Clone this to build a new agent in minutes.

## Stack

- **Laravel 13** — full web app (not Laravel Zero)
- **Laravel AI** — agent framework with tool calling
- **Prism** — multi-provider AI (OpenRouter default)
- **Horizon** — queue visibility and workers
- **Verbs** — event sourcing for agent actions
- **FrankenPHP** — HTTP server (Octane mode)

## Architecture

```
External World
    ↓ (direct Slack webhooks)
SlackController (sync, <3s response)
    ↓ (dispatch to queue)
Horizon Workers
    ↓
YourAgent (Laravel AI + tools)
    ↓
Slack / GitHub / APIs

Bifrost (optional, async events)
    ↓ Redis pub/sub
ListenCommand → dispatch jobs
```

## Create a New Agent

```bash
gh repo create the-shit/your-agent --template the-shit/agent-skeleton
cd your-agent
cp .env.example .env
# Fill in your keys
php artisan key:generate
php artisan migrate
```

## Customize

1. **`app/Agent/YourAgent.php`** — extend `BaseAgent`, define persona + tools
2. **`app/Commands/ListenCommand.php`** — override `channels()` and `route()`
3. **`app/Http/Controllers/SlackController.php`** — wire up slash commands
4. **`bootstrap/app.php`** — add scheduled tasks
5. **`deploy/agent.container`** — update for your agent name

## Deploy (Quadlet)

```bash
cp deploy/agent.container ~/.config/containers/systemd/your-agent.container
# Edit the container file with correct image and env paths
systemctl --user daemon-reload
systemctl --user enable --now your-agent
```

## Key Patterns

- **Conversation memory**: `ConversationMemory::push/history` — Redis-backed, survives restarts
- **Event sourcing**: Verbs events for every significant agent action
- **Own database**: each agent has isolated storage (SQLite default, MySQL optional)
- **Direct Slack**: slash commands hit the agent directly — no Bifrost dependency for sync responses
