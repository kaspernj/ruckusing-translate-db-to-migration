<?php

class RuckusingTranslateDbToMigration {
  function __construct($args){
    $this->args = $args;
    $this->db = $this->args["db"];
  }
  
  function toPHPCode(){
    $code = "";
    
    foreach($this->db->tables()->getTables() as $table){
      // Table and columns.
      //if ($table->getName() != "address") continue;
      $code .= "\$table = \$this->create_table('" . $table->getName() . "', array('id' => false))\n";
      
      foreach($table->getColumns() as $column){
        $code .= "\$table->column('" . $column->getName() . "', '" . $column->getType() . "', array(";
        
        $code .= "'limit' => \"" . $column->getMaxLength() . "\",";
        $code .= "'null' => " . ($column->getNull() ? "true" : "false") . ",";
        if ($column->getUnsigned()) $code .= "'unsigned' => true,";
        
        $code .= "));\n";
      }
      
      $code .= "\$table->finish();\n";
      $code .= "\n";
      
      // Indexes.
      $indexes = $table->getIndexes();
        if (count($indexes) > 0){
        foreach($table->getIndexes() as $index){
          $code .= "\$this->add_index('" . $index->getName() . "', array('" . implode("', '", $index->getColumnNames()) . "'), array(";
          
          if ($index->getUnique()){
            $code .= "'unique' => true,";
          }
          
          $code .= "));\n";
        }
        
        $code .= "\n";
      }
      
      // Data.
      if ($table->countRows() > 0){
        $this->db->select($table->getName())->each(function($data) use($table, &$code){
          $sql = $this->db->insert($table->getName(), $data, array("return_sql" => true));
          $code .= "\$this->execute(json_decode(" . json_encode($sql) . "));\n";
        });
        
        $code .= "\n";
      }
    }
    
    return $code;
  }
}
