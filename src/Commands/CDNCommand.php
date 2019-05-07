<?php

namespace Bavix\CupKit\Commands;

use Bavix\CupKit\Client;
use Bavix\CupKit\CupKitServiceProvider;
use Illuminate\Console\Command;

class CDNCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cdn:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a view in corundum';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        /**
         * @var Client $cup
         */
        $cup = app(CupKitServiceProvider::SINGLETON_CLIENT);
        foreach (config('corundum.buckets', []) as $bucketName => $views) {
            try {
                $cup->createBucket($bucketName);
                $this->info("Bucket $bucketName create");
            } catch (\Throwable $throwable) {
                $this->warn("Bucket $bucketName exists");
            } finally {
                foreach ($views as $view) {
                    try {
                        $cup->createView($bucketName, $view);
                        $this->info("View $bucketName.{$view['name']} create");
                    } catch (\Throwable $throwable) {
                        $this->warn("View $bucketName.{$view['name']} exists");
                    }
                }
            }
        }
    }

}
