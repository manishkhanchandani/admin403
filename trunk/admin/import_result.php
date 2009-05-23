<?php

class queryExtractor
{
    private $fileName;
    private $fileContent;
    public $SqlQueries;
    public function __construct($fileLocation = '')
    {
        if(strlen($fileLocation) < 1)
        {
            $this->fileName = '';
        }
        else 
        {
            $this->fileName = $fileLocation;
        }
    }
    public function extractQueries()
    {
        $fileSize = 0;
        $query = '';
        $size = filesize($this->fileName);
        if(strlen($this->fileName) < 1)
        {
            return '';
        }
        else
        {
            $file = @fopen("$this->fileName", "r");//open file for reading 
            $rf = fread($file, $size); // read 32K 
            fclose( $file );

            if(strlen($rf) > 0)
            {
                $fileSize = strlen($rf);                
                for($i = 0; $i <= $fileSize; $i++)
                {
                    if(substr($rf, $i, 1) == ';')
                    {
                        $this->SqlQueries[] = $query . substr($rf, $i, 1);
                    }
                    else
                    {
                        $query .= substr($rf, $i, 1);
                    }
                }
            }
            else
            {
                $this->SqlQueries = '';
                return $this->SqlQueries;
            }
        }
    }
}

/**
* EXAMPLE
* 
* $qe = new queryExtractor("C:\Program Files\Apache Software Foundation\Apache2.2\htdocs\Tserkanos\classes\sqlFileReader\DatabaseStructure.sql");
* $qe->extractQueries();
* 
* foreach ($qe->SqlQueries as $value)
* {
*     echo($value . "<br /><br />");
* }
*/
$file = "backupsql/2008-09-07_07-09-15/admin403bnr_db.sql";
$qe = new queryExtractor($file);
$qe->extractQueries();
echo "<pre>";
print_r($qe->SqlQueries);
exit;
foreach ($qe->SqlQueries as $value)
{
echo($value . "<br /><br />");
}
?>