<?php

namespace Modules\StaticBlock\Services;

use Modules\StaticBlock\Models\StaticBlock;
use Modules\StaticBlock\Repositories\StaticBlockRepository;

class StaticBlockService
{
    protected $staticBlock;

    public function __construct(StaticBlockRepository $staticBlock)
    {
        $this->staticBlock = $staticBlock;
    }

    public function render($key, $view = null)
    {
        // dd($key);
        if (is_numeric($key)) {
            $block = $this->staticBlock->getById($key);
        } elseif (is_string($key)){
            $block = $this->staticBlock->findBySlug($key);
        } elseif ($key instanceof StaticBlock) {
            $block = $key;
        }
        // dd($block);
        if (empty($key)) {
            return view('staticblock::static-render.not-found')->with([
                'blockId' => $key,
            ]);
        }

        if (!$view || !view()->exists($view)) {
            if (!$view || !view()->exists($view = "static-block::static-render.{$view}")) {
                $view = 'staticblock::static-render.default';
            }
        }

        return view($view)->with([
            'item' => $block,
        ]);
    }
}
