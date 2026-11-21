<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramAreaPositionData extends TelegramObjectData
{
    public function xPercentage(): ?float
    {
        return $this->float('x_percentage');
    }

    public function yPercentage(): ?float
    {
        return $this->float('y_percentage');
    }

    public function widthPercentage(): ?float
    {
        return $this->float('width_percentage');
    }

    public function heightPercentage(): ?float
    {
        return $this->float('height_percentage');
    }

    public function rotationAngle(): ?float
    {
        return $this->float('rotation_angle');
    }

    public function cornerRadiusPercentage(): ?float
    {
        return $this->float('corner_radius_percentage');
    }

    private function float(string $key): ?float
    {
        $value = $this->get($key);

        return is_float($value) || is_int($value) ? (float) $value : null;
    }
}
