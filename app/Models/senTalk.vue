namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SenTalk extends Model
{
    protected $fillable = [
        'pdf_path', 'title', 'creator',
        'number_views', 'number_likes', 'number_downloads'
    ];
}
