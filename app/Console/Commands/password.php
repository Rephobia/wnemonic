<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class password extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate password to manage content';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $password = $this->secret("Set content password");
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $this->setEnvValue($hashed);
    }
    
    private function setEnvValue(?string $newValue)
    {
        $path = base_path(".env");
        
        if (file_exists($path)) {

            $content = file_get_contents($path);
                        
            $newPair = "{$this->passKey}=$newValue";

            // strpos can return 0 as position or false, if substr doesn't exist,
            // use === to check result
            if (strpos($content, $this->passKey) === false) {
                
                $content = "{$content}\n{$newPair}";

            }
            else {
                
                $oldValue = env($this->passKey);
                $oldPair = "{$this->passKey}=$oldValue";
                $content = str_replace($oldPair, $newPair, $content);
                
            }

            file_put_contents($path, $content);
        }
        else {
            
            $this->error("can't find .env file!");
            
        }
    }
    
    private string $passKey = "CONTENT_PASSWORD";
}
