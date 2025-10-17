<?php

namespace Newnet\Cms\Builder;

interface ContentBuilderInterface
{
  public function buildAmpTag(): string;
  public function changeImgTag(): string;
  public function changeSrcImg(): string;
  public function get(): string;
}
