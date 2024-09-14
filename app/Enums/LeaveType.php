<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LeaveType extends Enum
{
    const Annual = 'annual';
    const Sick = 'sick';
    const Unpaid = 'unpaid';
    const Maternity = 'maternity';
    const Paternity = 'paternity';
    const Marriage = 'marriage';
    const Bereavement = 'bereavement';
    const Emergency = 'emergency';
    const Other = 'other';
}
