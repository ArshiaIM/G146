<?php

namespace App\Models;

use App\Traits\RequiresApproval;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use RequiresApproval;
}
