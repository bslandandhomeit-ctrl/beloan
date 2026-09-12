<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 12/08/2015
 * Time: 8:22 AM
 */

namespace App\Translation;

use App\Models\Locale;
use App\Models\LocaleTitle;
use Illuminate\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;

class Manager {
    /** @var \Illuminate\Foundation\Application  */
    protected $app;
    /** @var \Illuminate\Filesystem\Filesystem  */
    protected $files;

    public function __construct(Application $app,Filesystem $files)
    {
        $this->app = $app;
        $this->files = $files;
    }
    protected function makeTree($translations)
    {
        $array = array();
        foreach($translations as $translation){
            if(!empty($translation->trans)){
                foreach($translation->trans as $trans){
                    array_set($array[$translation->short_locale][$trans->group], $trans->key, $trans->title);
                }
            }
        }
        return $array;
    }
    public function exportTranslations($group = '*',$locale = '')
    {
        if($group == '*'){
            $this->exportAllTranslations($locale);
        }else{
            $trans = Locale::with(['trans'=>function($query) use($group){
                $query->where('group',$group);
            }])->whereHas('trans',function($query) use($group){
                $query->where('group',$group);
            });
            if(!empty($locale)){
                $trans = $trans->where('id',$locale);
            }
            $trans = $trans->get();
            $tree = $this->makeTree($trans);
            foreach($tree as $locale => $groups){
                if(isset($groups[$group])){
                    $translations = $groups[$group];
                    $p = $this->app->langPath().'/'.$locale;
                    if(!$this->files->isDirectory($p)){
                        $this->files->makeDirectory($p);
                    }
                    $path = $p.'/'.$group.'.php';
                    $output = "<?php\n\nreturn ".var_export($translations, true).";\n";
                    $this->files->put($path, $output);
                }
            }
        }
    }

    public function exportAllTranslations($locale = '')
    {
        $groups = LocaleTitle::select(DB::raw('DISTINCT `group`'));
        if(!empty($locale)){
            $groups = $groups->where('locale_id','=',$locale);
        }
        $groups = $groups->get('group');
        foreach($groups as $group){
            $this->exportTranslations($group->group);
        }
    }
    public  function exportTranslationByLocale($id = 1)
    {
        $this->exportAllTranslations($id);
    }
}
