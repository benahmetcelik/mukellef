<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $class = $this->argument('class');
        // Define the path where the service files will be created
        $servicePath = app_path("Services/{$class}Service.php");
        $interfacePath = app_path("Interfaces/I{$class}Service.php");
        $controllerPath = app_path("Http/Controllers/{$class}Controller.php");
        // Generate the interface and class stubs
        $interfaceStub = file_get_contents(base_path('stubs/service.interface.stub'));
        $classStub = file_get_contents(base_path('stubs/service.class.stub'));
        $controllerStub = file_get_contents(base_path('stubs/controller.service.stub'));

        // Replace placeholders with the actual name
        $interfaceContent = str_replace('{{ class }}', $class, $interfaceStub);
        $classContent = str_replace('{{ class }}', $class.'Service', $classStub);
        $controllerContent = str_replace('{{ class }}', $class, $controllerStub);
        $controllerContent = str_replace('{{ classLoverCase }}', Str::lower($class), $controllerContent);

        // Write the files
        File::put($interfacePath, $interfaceContent);
        File::put($servicePath, $classContent);
        File::put($controllerPath, $controllerContent);

        // Output success message
        $this->info("Service and interface created successfully.");
    }
}
