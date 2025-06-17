    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Counselor extends Model
    {
        protected $table = 'counselors';
        protected $fillable = ['lname', 'fname', 'mname', 'email', 'contact_num', 'c_level', 'c_image'];

    }
