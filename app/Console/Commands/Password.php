<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Password extends Command
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
     * hash string
     *
     * @return string
     */
    public function hash(string $password) : string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $password = $this->secret("Set content password");
        $hashed = $this->hash($password);

        $this->setEnvValue($hashed);
    }
    
    private function setEnvValue(?string $newValue)
    {
        $passKey = \App\Literal::passwordKey();
        
        $path = base_path(".env");
        
        if (file_exists($path)) {

            $content = file_get_contents($path);
                        
            $newPair = "{$passKey}=$newValue";

            // strpos can return 0 as position or false, if substr doesn't exist,
            // use === to check result
            if (strpos($content, $passKey) === false) {
                
                $content = "{$content}\n{$newPair}";

            }
            else {
                
                $oldValue = env($passKey);
                $oldPair = "{$passKey}=$oldValue";
                $content = str_replace($oldPair, $newPair, $content);
                
            }

            file_put_contents($path, $content);
        }
        else {
            
            $this->error("can't find .env file!");
            
        }
    }
}
