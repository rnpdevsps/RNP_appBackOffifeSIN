<?php

namespace App\Http\Controllers;

use App\Models\TimerSession;
use App\Models\TimerSessionBreak;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FormTimerController extends Controller
{
    public function start(Request $request)
    {
        $currTime     = Carbon::now();
        $userId       = $request->form_type === 'public' ? null : auth()->user()->id ?? null;
        $timerSession = TimerSession::updateOrCreate(
            [
                'form_value_id' => $request->form_value_id ?? null,
                'form_id'    	=> $request->form_id,
                'user_id'       => $userId,
            ],
            [
                'start_time' => $currTime,
                'stop_time'  => null,
                'status'     => 'running',
            ]
        );
        if ($timerSession->wasRecentlyCreated === false) {
            TimerSessionBreak::where('timer_session_id', $timerSession->id)->delete();
        }
        return response()->json(['message' => 'Timer started']);
    }

    public function stop(Request $request)
    {
        $currTime       = Carbon::now();
        $timerSession   = TimerSession::find($request->session_id);
        if ($timerSession && ($timerSession->status === 'running' || $timerSession->status === 'paused')) {
            $timerSession->stop_time        = $currTime;
            $timerSession->form_value_id    = $request->form_value_id;
            $timerSession->status           = 'stopped';
            $timerSession->save();
            $break = TimerSessionBreak::where('timer_session_id', $timerSession->id)
                ->whereNull('break_end_time')
                ->latest()
                ->first();
            if ($break) {
                $break->break_end_time = $currTime;
                $break->save();
            }
            return response()->json(['message' => 'Timer stopped']);
        }
        return response()->json(['message' => 'No active running session found'], 404);
    }

    public function pause(Request $request)
    {
        $currTime = Carbon::now();
        $timerSession = TimerSession::find($request->session_id);
        if ($timerSession && $timerSession->status === 'running') {
            $timerSession->status = 'paused';
            $timerSession->save();
            TimerSessionBreak::create([
                'timer_session_id' => $timerSession->id,
                'break_start_time' => $currTime,
            ]);
            return response()->json(['message' => 'Timer paused']);
        }elseif ($timerSession && $timerSession->status != 'stopped') {
            return response()->json(['message' => 'Unable to pause. Session not running'], 400);
        }
    }

    public function resume(Request $request)
    {
        $currTime = Carbon::now();
        $timerSession = TimerSession::find($request->session_id);
        if ($timerSession && $timerSession->status === 'paused') {
            $timerSession->status = 'running';
            $timerSession->save();
            $break = TimerSessionBreak::where('timer_session_id', $timerSession->id)
                ->whereNull('break_end_time')
                ->latest()
                ->first();
            if ($break) {
                $break->break_end_time = $currTime;
                $break->save();
            }
            return response()->json(['message' => 'Timer resumed']);
        }elseif ($timerSession && $timerSession->status != 'stopped') {
            return response()->json(['message' => 'Unable to resume. Session not paused'], 400);
        }
    }

    public function loadState(Request $request)
    {
        $userId       = $request->form_type === 'public' ? null : auth()->user()->id ?? null;
        $timerSession = TimerSession::where('user_id', $userId)
            ->where('form_id', $request->form_id)
            ->where('form_value_id', $request->form_value_id ?? null)
            ->latest()
            ->first();
        if ($timerSession) {
            $elapsedTime = Self::calculateElapsedTime($timerSession);
            return response()->json([
                'session_id' => $timerSession->id,
                'elapsed_time' => $elapsedTime,
                'status' => $timerSession->status,
            ]);
        }
        return response()->json([
            'session_id' => null,
            'elapsed_time' => 0,
            'status' => 'stopped',
        ]);
    }

    protected function calculateElapsedTime($timerSession)
    {
        if ($timerSession->status === 'running') {
            $duartionDate = Carbon::now();
        } else {
            $duartionDate = Carbon::parse($timerSession->updated_at);
        }
        $totalTime = Carbon::parse($timerSession->start_time)->diffInSeconds($duartionDate);
        $breaks = TimerSessionBreak::where('timer_session_id', $timerSession->id)
            ->whereNotNull('break_end_time')
            ->get();
        $totalBreakTime = 0;
        foreach ($breaks as $break) {
            $totalBreakTime += Carbon::parse($break->break_start_time)->diffInSeconds(Carbon::parse($break->break_end_time));
        }
        return $totalTime - $totalBreakTime;
    }
}
