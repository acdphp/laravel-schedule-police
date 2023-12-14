<?php

namespace Acdphp\ScheduleControl\Http\Controllers;

use Acdphp\ScheduleControl\Http\Requests\ControlRequest;
use Acdphp\ScheduleControl\Http\Requests\ExecRequest;
use Acdphp\ScheduleControl\Services\ScheduleControlService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    public function __construct(protected ScheduleControlService $service)
    {
    }

    public function index(): View
    {
        return view('schedule-control::dashboard', [
            'configured' => $this->service->isConfigured(),
            'events' => $this->service->getScheduledTasks(),
            'enableExecute' => config('schedule-control.enable_execution'),
        ]);
    }

    public function stop(ControlRequest $request): RedirectResponse
    {
        $this->service->stopScheduleByKey($request->validated('key'));

        return Redirect::back();
    }

    public function start(ControlRequest $request): RedirectResponse
    {
        $this->service->startScheduleByKey($request->validated('key'));

        return Redirect::back();
    }

    public function exec(ExecRequest $request): RedirectResponse
    {
        if (! config('schedule-control.enable_execution')) {
            abort(403);
        }

        $command = $request->validated('command');
        $output = $this->service->execCommand($command);

        return Redirect::back()->with([
            'command' => $command,
            'commandOutputIsError' => $output->isError,
            'commandOutputMessage' => $output->message,
        ]);
    }
}
