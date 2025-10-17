<?php

namespace Modules\Manage\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Manage\Models\Team;

class TeamController extends Controller
{
    public function detail($id)
    {
        $item = Team::find($id);

        return view('manage::web.team.detail', compact('item'));
    }
}
