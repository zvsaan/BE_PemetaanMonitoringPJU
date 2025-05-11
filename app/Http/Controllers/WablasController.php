<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WablasService;

class WablasController extends Controller
{
    protected $wablas;

    public function __construct(WablasService $wablas)
    {
        $this->wablas = $wablas;
    }

    public function kirimGambarWablas(Request $request)
    {
        $groupId = $request->input('group_id');
        $imageUrl = $request->input('image');
        $caption = $request->input('caption', '');

        return $this->wablas->kirimGambarWablas($groupId, $imageUrl, $caption);
    }
}
