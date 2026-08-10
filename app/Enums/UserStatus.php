<?php

namespace App\Enums;

enum UserStatus: string {
    case ACTIVE   = 'active';
    case INACTIVE = 'deactive';
    case BANNED   = 'yes';
    case UNBANNED = 'no';
}
