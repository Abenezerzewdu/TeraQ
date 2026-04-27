# TeraQ — Smart Queue Management System

TeraQ is a real-time queue management platform that lets businesses create and manage customer queues, while customers join and track their position live — with SMS notifications when it's their turn.

Home page
<img width="960" height="451" alt="Image" src="https://github.com/user-attachments/assets/22e87cc2-6322-4e2f-abc9-e4c9c7a6b7a7" />

Businesses explore page

<img width="625" height="448" alt="Image" src="https://github.com/user-attachments/assets/80bd0a0a-6973-4b01-b134-c7610f22d6d7" />

Queue joining page

<img width="405" height="443" alt="Image" src="https://github.com/user-attachments/assets/ed457450-4ee8-4e83-926b-fc945feb2e76" />

sample video

<iframe width="560" height="315" src="https://www.youtube.com/embed/HkdAHXoRtos?si=bpqDr9c2kaLa6gxv" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12 (PHP 8.2+) |
| Frontend | Vue 3 + Inertia.js |
| Styling | Tailwind CSS v3 |
| Real-time | Laravel Reverb (WebSockets) |
| SMS | TextBee API |
| Auth & Roles | Laravel Breeze + Spatie Permissions |
| Build | Vite |
| Database | SQLite (dev) / MySQL (prod) |

---

## Features

- Business registration with hero image and logo upload
- Multiple queues per business
- Real-time queue updates via WebSockets (Laravel Reverb)
- SMS notifications on join, turn start, and next-in-line reminder
- Guest joining via device ID (no account required)
- Owner dashboard to advance, remove, and manage queue entries
- Role-based access control via Spatie Laravel Permission

---

## Getting Started

### Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- SQLite (default) or MySQL

### Installation

```bash
# 1. Clone the repo
git clone https://github.com/your-org/teraq.git
cd teraq

# 2. Install dependencies
composer install
npm install

# 3. Set up environment
cp .env.example .env
php artisan key:generate

# 4. Run migrations and seeders
php artisan migrate --seed

# 5. Link storage for image uploads
php artisan storage:link

# 6. Build frontend assets
npm run build
```

Or use the one-command setup:

```bash
composer run setup
```

### Running in Development

```bash
composer run dev
```

This starts all required processes concurrently:
- `php artisan serve` — Laravel dev server
- `npm run dev` — Vite HMR
- `php artisan queue:listen` — Job queue worker
- `php artisan pail` — Log viewer

---

## Environment Variables

Copy `.env.example` to `.env` and configure:

```env
APP_NAME=TeraQ
APP_URL=http://localhost:8000

# Database (SQLite default, switch to mysql for production)
DB_CONNECTION=sqlite

# Broadcasting (set to reverb for real-time)
BROADCAST_CONNECTION=reverb

# Queue (database driver works out of the box)
QUEUE_CONNECTION=database

# Reverb WebSocket server
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080

# TextBee SMS (https://textbee.dev)
TEXTBEE_API_KEY=your-api-key
TEXTBEE_DEVICE_ID=your-device-id
TEXTBEE_BASE_URL=https://api.textbee.dev
```

---

## Project Structure

```
app/
├── Http/Controllers/
│   ├── BusinessController.php   # Business CRUD + image upload
│   └── QueueController.php      # Queue management + join/leave
├── Services/
│   ├── QueueService.php         # Core queue logic (join, leave, advance)
│   └── NotificationService.php  # SMS via TextBee
├── Models/
│   ├── Business.php
│   ├── Queue.php
│   └── QueueEntry.php
├── Events/                      # Broadcast events (Reverb)
│   ├── UserJoinedQueue.php
│   ├── UserLeftQueue.php
│   ├── UserServingStarted.php
│   └── UserRemoved.php
└── Listeners/
    └── QueueNotificationHandler.php  # Sends SMS on queue events

resources/js/
├── Pages/
│   ├── Dashboard.vue
│   ├── Business/         # Create, Edit, Show, Index
│   └── Queue/            # Show (live queue view)
└── Components/           # Shared UI components
```

---

## Key Concepts

### Queue Entry Statuses

| Status | Meaning |
|---|---|
| `waiting` | In line, has a position number |
| `serving` | Currently being served (position = 0) |
| `done` | Service completed |
| `left` | User voluntarily left |
| `removed` | Removed by business owner |

### Real-time Flow

1. User joins → `UserJoinedQueue` event broadcasts on `queue.{id}` channel
2. Owner clicks "Next" → `UserServingStarted` broadcasts, SMS sent to current + next person
3. User leaves → `UserLeftQueue` broadcasts, positions compact automatically

### Image Uploads

Images are stored on the `public` disk under `storage/app/public/businesses/`. Run `php artisan storage:link` once to make them accessible at `/storage/businesses/...`.

---

## Seeding

```bash
# Seed roles (admin, business_owner, customer)
php artisan db:seed --class=RoleSeeder

# Seed sample queues
php artisan db:seed --class=QueueSeeder

# Or run all seeders
php artisan db:seed
```

---

## Production Checklist

- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Switch `DB_CONNECTION` to `mysql` and configure credentials
- [ ] Set `BROADCAST_CONNECTION=reverb` and configure Reverb keys
- [ ] Set `QUEUE_CONNECTION=database` and run `php artisan queue:work` as a daemon
- [ ] Run `php artisan storage:link`
- [ ] Run `npm run build`
- [ ] Configure a process manager (Supervisor) for queue workers and Reverb

---

## License

MIT
