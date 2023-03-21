<?php
class Model 
{
    protected $pdo ;
    public function __construct() {
        $this->pdo = database_connect();
    }
    public function store($table,$data)
    {
        $sql = "INSERT INTO `$table`";
        $collumn_query= '';
        $collumn_refer= '';
        $count_record = count($data);
        $count=1;
        foreach($data as $collumn => $value){
            $collumn_query .= '`'.$collumn.'`';
            $collumn_refer .= ":".$collumn;
            if($count == $count_record)
            break;
            $collumn_query .= ',';
            $collumn_refer .= ',';
            $count++;
        }
       
        $sql .= '('. $collumn_query.' ) VALUES ('. $collumn_refer.')';
        
        $stmt = $this->pdo->prepare($sql);
        foreach ($data as $collumn => $value) {
            $stmt->bindValue(':'.$collumn, $value);
        }
        try {
            $stmt->execute();
            return $this->getRecord($table,$this->pdo->lastInsertId());
             
        }catch(PDOException $e){
            echo $e->getMessage(); die();
        }
    }
    public function update($table,$data,$whereQuery=[])
    {
        $sql = "UPDATE `$table` SET ";
        $update_query= '';
        $count_record = count($data);
        $count=1;
        foreach($data as $collumn => $value){
            $update_query .= "`".$collumn."` = :".$collumn;
            if($count == $count_record)
            break;
            $update_query .= ',';
            $count++;
        }
        $sql .=  $update_query ;
        if(!empty($whereQuery)){
            $sql .= $this->whereCondition($whereQuery) ;
        }
        $stmt = $this->pdo->prepare($sql);
        foreach ($data as $collumn => $value) {
            $stmt->bindValue(':'.$collumn, $value);
        }
        try {
            return $stmt->execute();
        }catch(PDOException $e){
            echo $e->getMessage(); die();
        }
        
    }
    
    public function getRecord($table,$id)
    {
        $sql = "SELECT * FROM `$table` WHERE `id` = $id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch();
    }
    public function getWhere($table,$whereArray=[])
    {
        $sql = "SELECT * FROM `$table`";
        if(!empty($whereArray)){
            $sql .= $this->whereCondition($whereArray) ;
        }
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch();
    }
    public function getAll($table,$whereArray = [])
    {


        $query = 'SELECT * '
        . 'FROM '.$table;
        if(!empty($whereArray)){
            $query .= $this->whereCondition($whereArray) ;
        }
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll();
    }
    public function getWhereIn($table,$col,$whereInArray, $whereArray = [])
    {
        $query = 'SELECT * '
        . 'FROM '.$table;
      
        if(!empty($whereArray)){
            $query .= $this->whereCondition($whereArray) ;
            $query .= ' AND ';
        }else{
            $query .= ' WHERE ';
        }
        if(is_array($whereInArray))
        {
            $query .= '`'.$col.'` IN ('.implode(",", $whereInArray).')';
        }
        else
        {
            $query .= '`'.$col.'` IN ('.$whereInArray.')';
        }
        $query .= ' ORDER BY `id` DESC ';
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll();
    }
    public function like($table=null,$arg = [] ,$whereArray = [])
    {
        $sql = "SELECT * FROM $table WHERE (";
        $likQuery=[];
        foreach($arg as $collumn => $keyword){
            $likQuery[] = '`'.$collumn.'` LIKE "'.$keyword.'"';
        }
        $like='';
        foreach($likQuery as $key => $orQuery){
            $like .= $orQuery;
            if(isset($likQuery[$key+1]))
            {
                $like .= ' OR ';
            } 
        }
        $sql .= $like .' )';
        if(!empty($whereArray)){
            $sql .= 'AND ';
            $sql .= $this->whereCondition($whereArray , $likeQuery = true) ;
        }
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch();
    }
    public function getPanelRecord($whereArray = [])
    {
        $sql = "SELECT `panels`.`title`,`panels`.`id`,`panels`.`color`,`keywords`.`network_type`,`keywords`.`keywork`,`keywords`.`per_page_limit`,`keywords`.`page_type`,`keywords`.`is_full_text_feed`  FROM `panels` LEFT JOIN `keywords` ON `keywords`.`panel_id` = `panels`.`id`";
        $id = 0;
        if(isset($whereArray['id']))
        {
            $id = $whereArray['id'];
            unset($whereArray['id']);
        }
        if(!empty($whereArray)){
            $sql .= $this->whereCondition($whereArray) ;
        }
        if($id>0 && !empty($whereArray))
        {
            $sql .=' AND `panels`.id = '.$id;
        }elseif($id>0){
            $sql .=' WHERE `panels`.id = '.$id;
        }
        $sql .= ' ORDER BY `panels`.id DESC'; 
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    public function delete($table,$whereArray = [],$softDelete = true)
    {
        if ($softDelete) {
            $sql = "UPDATE `$table` SET `is_deleted` = 1";
        } else {
            $sql = "DELETE FROM `$table`";
        }
        if(!empty($whereArray))
        $sql .= $this->whereCondition($whereArray);
        $this->pdo->query($sql);
    }
    function countWhere($table,$whereArray=[])
    {
        $sql = "SELECT count(`id`) as `count`  FROM `$table`";
        if(!empty($whereArray))
        $sql .= $this->whereCondition($whereArray) ;
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch();
    }
    function whereCondition($whereArray = [], $likeQuery = false){
        $count_record = count($whereArray);
        $count=1;
        $whereQuery ='';
        foreach($whereArray as $col=>$val){
            if(is_numeric($val))
            $whereQuery  .= "`".$col."` = ".$val;
            else
            $whereQuery  .= "`".$col."` = '".$val."'";
            if($count == $count_record)
            break;
            $whereQuery  .= ' AND ';
            $count++;
        }
        if( $likeQuery )
        return $whereQuery ;
        return ' WHERE '. $whereQuery ;
    }

    public function getRecordWhere($table,$id)
    {
        $sql = "SELECT * FROM `$table` WHERE `Id` = $id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch();
    }
    
}
?>