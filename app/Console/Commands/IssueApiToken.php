<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class IssueApiToken extends Command
{
    protected $signature = 'activities:issue-token {name} {--email=admin@admin.com}';

    protected $description = 'Emite um token Sanctum com a ability activities:read';

    public function handle(): int
    {
        $user = User::where('email', $this->option('email'))->first();

        if (!$user) {
            $this->error("Usuário com e-mail {$this->option('email')} não encontrado.");
            return 1;
        }

        $token = $user->createToken($this->argument('name'), ['activities:read']);

        $this->info('Token gerado — copie agora, ele não será mostrado novamente:');
        $this->line($token->plainTextToken);

        return 0;
    }
}