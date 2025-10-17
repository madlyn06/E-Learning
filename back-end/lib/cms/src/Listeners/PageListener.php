<?php

namespace Newnet\Cms\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Newnet\Cms\Exceptions\RevalidateException;

class PageListener implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Create the event listener.
   *
   * @return void
   */
  public string $type;

  public function __construct(string $type)
  {
    $this->type = $type;
  }

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle()
  {
    $url = config('app.front_end_url').'/api/revalidate';
    switch ($this->type) {
      case 'created':
      case 'updated':
      case 'deleted':
        $url .= '?type=page';
        break;
      default:
        throw new RevalidateException('Type not supported in revalidate page');
        break;
    }
    $response = Http::get($url);
    return $response->body();
  }
}
