<?PHP 
namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class MyModels extends Model {


    public $sessionData = false;
    public $lang_id = false;

  function __construct()
  {
      //parent::__construct();
      $this->sessionData = session('users');
      $this->lang_id = $this->sessionData['lang_id'];
  }

  protected function Check_validator($data, $rules) {

      $validator = Validator::make($data, $rules);

      if ($validator->fails()) {

          return $validator->errors();//collect()->collapse()->all();
      }

      return true;

    }
}

?>
