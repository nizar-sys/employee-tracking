<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AttendanceStatus extends Enum
{
    const Holiday = 'holiday';
    const DayOff = 'day off';
    const Present = 'present';
    const Absent = 'absent';
    const Late = 'late';
    const OnLeave = 'on leave';
}
