<?php

namespace Acdphp\ScheduleControl\Services;

use Acdphp\ScheduleControl\Console\Kernel;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ScheduleControlService
{
    protected CONST DB_TABLE = 'stopped_scheduled_events';
    protected static ?Collection $stoppedEventsCache = null;
    protected array $config;

    public function __construct()
    {
        $this->config = config('schedule-control');
    }

    public function isConfigured(): bool
    {
        return
            is_a('\App\Console\Kernel', Kernel::class, true) &&
            Schema::hasTable(self::DB_TABLE);
    }

    public function getScheduledTasks(): array
    {
        app()->make(Kernel::class);
        $schedule = app()->make(Schedule::class);

        return array_map(function (Event $event) {
            $key = $this->getEventKey($event);

            return (object) [
                'key' => $key,
                'name' => $this->getEventCommand($event),
                'description' => $event->description,
                'expression' => $event->expression,
                'stopped' => $this->eventStoppedAt($event)?->format('Y-m-d H:i:sO'),
                'is_console' => $this->isEventConsoleCommand($event),
            ];
        }, $schedule->events());
    }

    public function stopScheduleByKey(string $key): void
    {
        $this->table()
            ->insert([
                'key' => $key,
                'created_at' => now(),
                'by' => Auth::hasUser() ? Auth::user()->{$this->config['causer_key']} : null,
            ]);
    }

    public function startScheduleByKey(string $key): void
    {
        $key = json_decode($key);

        $this->table()
            ->whereJsonContains('key', ['event' => $key->event])
            ->when($this->config['separate_by_frequency'], fn ($q) => $q->whereJsonContains('key', ['freq' => $key->freq]))
            ->delete();
    }

    public function execCommand(string $command): string
    {
        if (Str::startsWith($command, $this->config['blacklist_commands'])) {
            return 'Error: Cannot run this command in dashboard because it\'s blacklisted. Update the blacklist in the config.';
        }

        try {
            Artisan::call($command);
        } catch (\Throwable $e) {
            return 'Error: ' . $e->getMessage();
        }

        return Artisan::output();
    }

    public function eventStoppedAt(Event $event): ?Carbon
    {
        $stoppedEvents = $this->getStoppedEvents();
        $subjectEvent = json_decode($this->getEventKey($event), false, 512, JSON_THROW_ON_ERROR);

        $stoppedEvent = $stoppedEvents->firstWhere(function ($stoppedEvent) use ($subjectEvent) {
            $sEvent = json_decode($stoppedEvent->key, false, 512, JSON_THROW_ON_ERROR);

            return
                $sEvent->event === $subjectEvent->event &&
                (! $this->config['separate_by_frequency'] || $sEvent->freq === $subjectEvent->freq);
        });

        return $stoppedEvent ? Carbon::parse($stoppedEvent->created_at) : null;
    }

    public function getEventKey(Event $event): string
    {
        return json_encode([
            'event' => $this->getEventCommand($event),
            'freq' => $event->expression,
        ], JSON_THROW_ON_ERROR);
    }

    protected function getEventCommand(Event $event): string
    {
        return Str::of($event->command)
            ->after('artisan\'')
            ->whenEmpty(fn () => Str::of($event->description))
            ->trim();
    }

    protected function isEventConsoleCommand(Event $event): bool
    {
        return preg_match("/^'.*php.*' 'artisan' /", $event->command);
    }

    protected function getStoppedEvents(): Collection
    {
        if (! static::$stoppedEventsCache) {
            static::$stoppedEventsCache = $this->table()->get();
        }

        return static::$stoppedEventsCache;
    }

    protected function table(): Builder
    {
        return DB::table(self::DB_TABLE);
    }
}
