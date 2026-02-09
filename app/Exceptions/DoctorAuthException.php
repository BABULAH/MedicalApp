["INFO" - 13:12:11] Current Version: 2.0.1, Plugin Version: 2.0.1

namespace App\Exceptions;

use Exception;


class DoctorAuthException extends \Exception
{
    public static function notVerified()
    {
        return new self('DOCTOR_NOT_VERIFIED');
    }

    public static function inactive()
    {
        return new self('DOCTOR_INACTIVE');
    }

    public static function noTenant()
    {
        return new self('NO_TENANT');
    }
}
