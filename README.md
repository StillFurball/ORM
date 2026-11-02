# JobPilot Assistant - Relasi Antar Model & Eager Loading

Tugas PBKK Pertemuan 9, dikaitkan dengan proyek kelompok **JobPilot** (asisten pencari kerja berbasis AI). Skema ini mencatat sesi obrolan kandidat dengan asisten JobPilot, isi pesannya, dan log pemanggilan alat (tool calling) oleh agent-agent JobPilot:

| Agent JobPilot | Alat | Fungsi |
|---|---|---|
| `JobDiscoveryAgent` | `search_jobs` | Mencari lowongan dari Jobicy, Arbeitnow, JSearch, dll. |
| `JobMatchingAgent` | `score_match` | Menilai kecocokan lowongan dengan profil kandidat (LLM `qwen3:8b`) |
| `CvTailoringAgent` | `tailor_cv` | Menyesuaikan CV untuk lowongan tertentu |
| `EmailApplicationAgent` | `draft_email` | Menyusun draf email lamaran (safe mode: tidak terkirim tanpa persetujuan) |

## ERD

```mermaid
erDiagram
    users ||--o{ chat_sessions : "hasMany"
    chat_sessions ||--o{ chat_messages : "hasMany"
    chat_messages ||--o{ tool_execution_logs : "hasMany"
    job_discovery_agents ||--o{ tool_execution_logs : "morphMany (loggable)"
    job_matching_agents ||--o{ tool_execution_logs : "morphMany (loggable)"
    cv_tailoring_agents ||--o{ tool_execution_logs : "morphMany (loggable)"
    email_application_agents ||--o{ tool_execution_logs : "morphMany (loggable)"

    users {
        bigint id PK
        string name
        string email UK
        string password
        timestamps created_at
    }
    chat_sessions {
        bigint id PK
        bigint user_id FK "cascade"
        string title "mis. Lowongan Backend Laravel di Indonesia"
        timestamps created_at
    }
    chat_messages {
        bigint id PK
        bigint chat_session_id FK "cascade"
        string role "user | assistant"
        text content
        timestamps created_at
    }
    tool_execution_logs {
        bigint id PK
        bigint chat_message_id FK "cascade"
        bigint loggable_id "morphs"
        string loggable_type "morphs"
        string tool_name "search_jobs | score_match | tailor_cv | draft_email"
        json input
        json output "nullable"
        string status
        int duration_ms "nullable"
        timestamps created_at
    }
    job_discovery_agents {
        bigint id PK
        string name
        string source "jobicy | arbeitnow | jsearch ..."
    }
    job_matching_agents {
        bigint id PK
        string name
        string llm_model
    }
    cv_tailoring_agents {
        bigint id PK
        string name
        string llm_model
    }
    email_application_agents {
        bigint id PK
        string name
        boolean safe_mode
    }
```

## Relasi Model

| Model | Relasi | Tipe |
|---|---|---|
| `User` | `chatSessions()` | `hasMany(ChatSession)` |
| `ChatSession` | `user()` / `messages()` | `belongsTo(User)` / `hasMany(ChatMessage)` |
| `ChatMessage` | `chatSession()` / `toolExecutionLogs()` | `belongsTo(ChatSession)` / `hasMany(ToolExecutionLog)` |
| `ToolExecutionLog` | `chatMessage()` / `loggable()` | `belongsTo(ChatMessage)` / `morphTo()` |
| `JobDiscoveryAgent`, `JobMatchingAgent`, `CvTailoringAgent`, `EmailApplicationAgent` | `logs()` | `morphMany(ToolExecutionLog, 'loggable')` via trait `HasToolExecutionLogs` |

- Semua foreign key memakai `foreignId()->constrained()->cascadeOnDelete()`: menghapus user ikut menghapus sesi, pesan, dan log.
- Kolom morph (`loggable_id`, `loggable_type`) tidak bisa diberi foreign key, jadi cascade saat agent dihapus dilakukan oleh trait `HasToolExecutionLogs` (event `deleting`).
- Setiap tabel agent punya kolom khasnya sendiri (`source`, `llm_model`, `safe_mode`); inilah alasan log memakai relasi polymorphic, bukan satu tabel agent.

## Challenge A+

1. **Polymorphic logging**: `tool_execution_logs` memakai `$table->morphs('loggable')`, sehingga satu tabel log dipakai bersama oleh keempat agent JobPilot. Log tetap `belongsTo` pesan asisten yang memicunya.
2. **Optimasi kueri riwayat obrolan** di `ChatSessionController@show`:

   ```php
   $chatSession->load([
       'messages' => fn ($query) => $query->oldest(),
       'messages.toolExecutionLogs.loggable',
   ]);
   ```

   Jumlah query tetap konstan berapa pun banyaknya pesan dan log: pesan, log, lalu 1 query per tipe agent.
   Halaman daftar (`index`) memakai `withCount('messages')` sehingga jumlah pesan dihitung di database tanpa memuat isinya.

Bukti bebas N+1 (Laravel Debugbar, `/chat-sessions/1`): **10 statement** total. Semua relasi dimuat dengan satu query `WHERE ... IN (...)` per tabel; tidak ada query berulang per pesan atau per log.

![Debugbar: 10 query untuk halaman riwayat obrolan JobPilot](docs/debugbar-chat-session.jpg)

| # | Query | Asal |
|---|---|---|
| 1-3 | session, user login, `chat_sessions` (route binding) | middleware & routing |
| 4 | `chat_messages WHERE chat_session_id IN (1)` | eager `messages` |
| 5 | `tool_execution_logs WHERE chat_message_id IN (1,2,3,4)` | eager `messages.toolExecutionLogs` |
| 6-9 | `job_discovery_agents` / `job_matching_agents` / `cv_tailoring_agents` / `email_application_agents WHERE id IN (...)` | eager `loggable` (1 query per tipe agent) |
| 10 | update session | middleware |

Pengaman tambahan:
- `Model::preventLazyLoading()` aktif di luar production (`AppServiceProvider`), jadi lazy loading langsung melempar exception.
- Test `halaman riwayat obrolan bebas N+1 query` membandingkan jumlah query untuk sesi dengan 1 pesan vs 10 pesan dan memastikan keduanya sama.

## Autentikasi (Laravel Breeze)

- `User` berasal dari Breeze (Blade + Pest). Halaman `/chat-sessions` berada di dalam middleware `auth`.
- `ChatSessionPolicy@view` memastikan user hanya bisa membuka sesi miliknya sendiri (403 untuk sesi orang lain).
- Akun demo dari seeder: `test@example.com` / `password`.

## Menjalankan

```bash
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed   # 5 user, 15 sesi, 60 pesan, 60 log, 4 agent JobPilot
php artisan serve                  # login test@example.com / password, buka /chat-sessions
php artisan test --compact
```

Setiap sesi hasil seeder mengikuti alur JobPilot: kandidat meminta lowongan, asisten memanggil `search_jobs` dan `score_match`, kandidat meminta CV dan email, lalu asisten memanggil `tailor_cv` dan `draft_email`.
