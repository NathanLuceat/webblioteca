<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservaSala extends Model
{
    protected $table = 'reservas_salas';

    protected $fillable = ['usuario_id', 'sala_id', 'data', 'hora_inicio', 'hora_fim'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }

    public static function blocosDisponiveis(): array
    {
        $blocos = [];
        for ($hora = 6; $hora < 18; $hora++) {
            $inicio = sprintf('%02d:00', $hora);
            $fim = sprintf('%02d:00', $hora + 1);
            $blocos[$inicio . '-' . $fim] = [$inicio, $fim];
        }
        return $blocos;
    }

    public static function diasPermitidos(): array
    {
        return [
            now()->toDateString() => 'Hoje (' . now()->format('d/m') . ')',
            now()->addDay()->toDateString() => 'Amanhã (' . now()->addDay()->format('d/m') . ')',
            now()->addDays(2)->toDateString() => 'Depois de amanhã (' . now()->addDays(2)->format('d/m') . ')',
        ];
    }
}