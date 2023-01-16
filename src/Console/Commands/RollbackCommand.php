<?php

declare(strict_types=1);

namespace AFZidan\Subscriptions\Console\Commands;

use Illuminate\Console\Command;

class RollbackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'afzidan:rollback:subscriptions {--f|force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback AFZidan Subscriptions Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert($this->description);

        $path = config('afzidan.subscriptions.autoload_migrations') ?
            'vendor/afzidan/laravel-subscriptions/database/migrations' :
            'database/migrations/afzidan/laravel-subscriptions';

        if (file_exists($path)) {
            $this->call('migrate:reset', [
                '--path' => $path,
                '--force' => $this->option('force'),
            ]);
        } else {
            $this->warn('No migrations found! Consider publish them first: <fg=green>php artisan afzidan:publish:subscriptions</>');
        }

        $this->line('');
    }
}
