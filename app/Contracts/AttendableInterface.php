<?php

namespace App\Contracts;

interface AttendableInterface
{
    public function getAttendancePayload(): array;
    public function getAttendanceEntityType(): string;
}