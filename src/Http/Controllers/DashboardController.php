<?php

namespace Acdphp\SchedulePolice\Http\Controllers;

use Acdphp\SchedulePolice\Http\Requests\ControlRequest;
use Acdphp\SchedulePolice\Http\Requests\ExecRequest;
use Acdphp\SchedulePolice\Services\SchedulePoliceService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    public function __construct(protected SchedulePoliceService $service)
    {
    }

    /**
     * @throws BindingResolutionException
     */
    public function index(): View
    {
        if ($this->service->isConfigured()) {
            return view('schedule-police::dashboard', [
                'events' => $this->service->getScheduledEvents(),
                'enableExecute' => config('schedule-police.enable_execution'),
            ]);
        }

        return view('schedule-police::unconfigured');
    }

    public function stop(ControlRequest $request): RedirectResponse
    {
        $this->service->stopSchedule(...$request->validated());

        return Redirect::route('schedule-police.index');
    }

    public function start(ControlRequest $request): RedirectResponse
    {
        $this->service->startSchedule(...$request->validated());

        return Redirect::route('schedule-police.index');
    }

    public function exec(ExecRequest $request): RedirectResponse
    {
        if (! config('schedule-police.enable_execution')) {
            abort(403);
        }

        $command = $request->validated('command');
        $output = $this->service->execCommand($command);

        return Redirect::route('schedule-police.index')
            ->withFragment('#v-execute')
            ->with([
                'command' => $command,
                'commandOutputIsError' => $output->isError,
                'commandOutputMessage' => $output->message,
            ]);
    }
}
