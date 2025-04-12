<?php

namespace App\Livewire\Settings;

use App\Models\Session;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Sessions extends Component {

    public $sessions;
    public $headers = [
        'Usuario',
        'Dispositivo',
        'Estado',
        'Tiempo',
        'Ubicación',
        'Cerrar Sesión'
    ];

    public $users;

    public function mount()
    {
        // Obtener las sesiones activas
        $this->sessions = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->select('sessions.*', 'users.name as user_name')
            ->get();
        $this->users = User::all();
    }

    public function render()
    {
        return view('livewire.settings.sessions', [
            'sessions' => $this->sessions,
            'users' => $this->users,
        ])->layout('layouts.settings.user-management');
    }

    public function getDeviceType($userAgent)
    {
        if (str_contains(strtolower($userAgent), 'windows')) {
            return 'Windows';
        } elseif (str_contains(strtolower($userAgent), 'macintosh')) {
            return 'Mac';
        }
        return 'Otro';
    }

    public function getBrowserType($userAgent)
    {
        if (str_contains($userAgent, 'Chrome')) {
            return 'Chrome';
        } elseif (str_contains($userAgent, 'Firefox')) {
            return 'Firefox';
        } elseif (str_contains($userAgent, 'Safari')) {
            return 'Safari';
        }
        return 'Otro';
    }

    public function closeSession($sessionId)
    {
        try {
            DB::table('sessions')
                ->where('id', $sessionId)
                ->delete();


            $this->dispatch('open-modal', 'modal-session-closed');

            // Recargar las sesiones
            $this->sessions = DB::table('sessions')
                ->join('users', 'sessions.user_id', '=', 'users.id')
                ->select('sessions.*', 'users.name as user_name')
                ->get();

        } catch (\Exception $e) {
            session()->flash('error', 'No se pudo cerrar la sesión');
        }
    }

    public function formatLastActivity($timestamp)
    {
        if (!$timestamp) return 'Desconocido';

        $carbon = \Carbon\Carbon::createFromTimestamp($timestamp);

        if ($carbon->isToday()) {
            return $carbon->diffForHumans();
        }

        if ($carbon->isLastWeek()) {
            return $carbon->isoFormat('dddd [a las] HH:mm');
        }

        return $carbon->isoFormat('DD/MM/YYYY HH:mm');
    }


    public function closeModal()
    {
        $this->dispatch('close-modal', 'modal-session-closed');
    }
}
