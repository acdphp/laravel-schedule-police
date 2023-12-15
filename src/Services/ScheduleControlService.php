<?php

namespace Acdphp\ScheduleControl\Services;

use Acdphp\ScheduleControl\Console\Kernel as ControlKernel;
use Acdphp\ScheduleControl\Data\ExecResult;
use Acdphp\ScheduleControl\Data\ScheduledTask;
use Acdphp\ScheduleControl\Models\StoppedScheduledEvent;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class ScheduleControlService
{
    protected static ?Collection $stoppedEventsCache = null;
    protected array $config;

    public function __construct()
    {
        $this->config = config('schedule-control');
    }

    public function isConfigured(): bool
    {
        return
            is_a('\App\Console\Kernel', ControlKernel::class, true) &&
            Schema::hasTable('stopped_scheduled_events');
    }

    /**
     * @return array|ScheduledTask[]
     * @throws BindingResolutionException
     */
    public function getScheduledTasks(): array
    {
        app()->make(Kernel::class);
        $schedule = app()->make(Schedule::class);

        // Map events
        $tasks = array_map(function (Event $event) {
            return new ScheduledTask(
                key: $this->getEventKey($event),
                event: $event,
                stoppedEvent: $this->stoppedEvent($event),
            );
        }, $schedule->events());

        // Sort tasks by stopped time
        usort($tasks, static function($a, $b) {
            return $a->stoppedEvent?->created_at < $b->stoppedEvent?->created_at;
        });

        return $tasks;
    }

    public function stopScheduleByKey(string $key, string $expression): void
    {
        StoppedScheduledEvent::firstOrCreate([
            'key' => $key,
            'expression' => $expression,
        ], [
            'by' => Auth::hasUser() ? Auth::user()->{$this->config['causer_key']} : null,
        ]);
    }

    public function startScheduleByKey(string $key, string $expression): void
    {
        StoppedScheduledEvent::where('key', ['event' => $key])
            ->when(
                $this->config['separate_by_frequency'],
                fn ($q) => $q->whereJsonContains('key', ['expression' => $expression])
            )
            ->delete();
    }

    public function execCommand(string $command): ExecResult
    {
        if (Str::startsWith($command, $this->config['blacklisted_commands'])) {
            return new ExecResult(
                'Cannot run this command in dashboard because it\'s blacklisted.',
                true
            );
        }

        try {
            $exitCode = Artisan::call($command);
        } catch (Throwable $e) {
            return new ExecResult(
                $e->getMessage(),
                true
            );
        }

        return new ExecResult(
            Artisan::output(),
            $exitCode !== 0
        );
    }

    public function stoppedEvent(Event $event): ?StoppedScheduledEvent
    {
        if (! static::$stoppedEventsCache) {
            static::$stoppedEventsCache = StoppedScheduledEvent::all();
        }

        return static::$stoppedEventsCache
            ->where('key', $this->getEventKey($event))
            ->when($this->config['separate_by_frequency'], fn ($q) => $q->where('expression', $event->expression))
            ->first();
    }

    protected function getEventKey(Event $event): string
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
}
