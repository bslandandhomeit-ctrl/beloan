<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 12/08/2015
 * Time: 9:29 AM
 */

namespace App\Http\Controllers;

use App\Translation\Manager;

class TranslateController extends Controller{

    /** @var \App\Translation\Manager  */
    protected $manager;

    public function __construct(Manager $manager){
        $this->manager = $manager;
    }

    public function export()
    {
        $this->manager->exportAllTranslations();
    }

    public function generateLocale($id)
    {
        $this->manager->exportTranslationByLocale($id);
        return redirect()->back();
    }
}
