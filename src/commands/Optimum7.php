<?php

namespace YigitCukuren\Optimum7\Commands;

use Illuminate\Console\Command;
use DB;
use File;

class Optimum7 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimum7:model {table?} {directory?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create model using database table';

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
        $outputDirectory = $this->argument('directory');
        $tableName = $this->argument('table');
        if($tableName != '_all') {
            try{
                $fields = DB::select("DESCRIBE $tableName");
                $this->generateSchema($tableName,$fields,$outputDirectory);
            }catch(\Exception $e){
                if($e->getCode() == '42S02'){
                    echo "Table not found from database: \033[31m". $tableName . PHP_EOL."\033[0m";
                }
            }
        }else {
            $tables = DB::select("SHOW TABLES");
            foreach($tables as $table) {
                $tableName = current($table);
                $fields = DB::select("DESCRIBE $tableName");
                $this->generateSchema($tableName,$fields,$outputDirectory);
            }
        }
    }

    public function generateSchema($table,$fields,$output=''){
        $table = ucfirst($table);
        $php = '<?php'. "\n\n";
        $php .= 'namespace App;' . "\n\n";
        $php .= 'use Illuminate\Database\Eloquent\Model;' . "\n\n";
        $php .= 'class '.$table.' extends Model' . "\n";
        $php .= '{' . "\n";
        foreach($fields as $field){
            $fieldName = $field->Field;
            $php .= "\t" . 'protected $'.$fieldName.";\n";
        }
        $php .= "}\n";
        if(!empty($output)){
            if (!file_exists(app_path().'/'.$output.'/')) {
                mkdir(app_path().'/'.$output.'/', 0777, true);
            }
            File::put(app_path().'/'.$output.'/'.$table.'.php',$php);
        }else {
            File::put(app_path().'/'.$table.'.php',$php);
        }

    }
}
