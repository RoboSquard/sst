<?php

class Cache extends Model

{

    protected $collumn = ['ip','slug','page'];

    protected $table = 'cache_storage';



    public function storeData($data)

    {

        $inserted_data =array();

        foreach ($this->collumn as $col) {
            if(isset($data[$col]))
            $inserted_data[$col] = $data[$col];
          
        }
        return $this->store($this->table,$inserted_data);
    }
    public function getWhereCount($whereArray)
    {
        return $this->countWhere($this->table,$whereArray);
    }
    public function updateData($data,$condition=[])

    {

        $updated_data =array();

        foreach ($this->collumn as $col) {

        

            if(isset($data[$col]))

            $updated_data[$col] = $data[$col];

        }

        return $this->update($this->table,$updated_data,$condition);

    }

}



?>



