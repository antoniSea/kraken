<?php

namespace App;

use App\Models\PointsHistory;
use Illuminate\Support\Facades\Auth;

trait HasPoints
{
    public function addPoints(int $points, ?string $reason = null, ?int $adminId = null): void
    {
        $pointsBefore = $this->points;
        $this->increment('points', $points);
        $this->refresh();
        
        PointsHistory::create([
            'user_id' => $this->id,
            'points_change' => $points,
            'points_before' => $pointsBefore,
            'points_after' => $this->points,
            'reason' => $reason ?? 'Punkty dodane przez administratora',
            'admin_id' => $adminId ?? Auth::id(),
        ]);
    }

    public function subtractPoints(int $points, ?string $reason = null, ?int $adminId = null): void
    {
        $pointsBefore = $this->points;
        $this->decrement('points', $points);
        $this->refresh();
        
        PointsHistory::create([
            'user_id' => $this->id,
            'points_change' => -$points,
            'points_before' => $pointsBefore,
            'points_after' => $this->points,
            'reason' => $reason ?? 'Punkty odjęte przez administratora',
            'admin_id' => $adminId ?? Auth::id(),
        ]);
    }

    public function setPoints(int $points, ?string $reason = null, ?int $adminId = null): void
    {
        $pointsBefore = $this->points;
        $this->update(['points' => $points]);
        
        PointsHistory::create([
            'user_id' => $this->id,
            'points_change' => $points - $pointsBefore,
            'points_before' => $pointsBefore,
            'points_after' => $points,
            'reason' => $reason ?? 'Punkty ustawione przez administratora',
            'admin_id' => $adminId ?? Auth::id(),
        ]);
    }

    public function resetPoints(?string $reason = null, ?int $adminId = null): void
    {
        $this->setPoints(0, $reason ?? 'Reset punktów', $adminId);
    }
}
