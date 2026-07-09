<?php

namespace App\Jobs;

use App\Ship\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTelegramViewJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected string $bladeView;
    protected array $data;
    protected string $token;
    protected string $chatId;

    public function __construct(string $bladeView, array $data, string $token, string $chatId)
    {
        $this->bladeView = $bladeView;
        $this->data = $data;
        $this->token = $token;
        $this->chatId = $chatId;
    }

    public function handle()
    {
        $telegram = new TelegramService($this->token, $this->chatId);
        $telegram->sendView($this->bladeView, $this->data);
    }
}
