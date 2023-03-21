<?php
class Users extends Model
{
    protected $collumn = ['ip_address','timezone'];
    protected $table = 'Users';
    public function storeData($data)
    {
        $inserted_data =array();
        foreach ($this->collumn as $col) {
        
            if(isset($data[$col]))
            $inserted_data[$col] = $data[$col];
        }
        return $this->store($this->table,$inserted_data);
    }
    public function getUser($whereArray=[])
    {
        return $this->getWhere($this->table,$whereArray);
    }
    public function getUsers()
    {
        return $this->getAll($this->table);
    }
    public function get($whereArg)
    {
        return $this->like($this->table,$whereArg);
    }
        
    /**
     * updateData
     *
     * @param  mixed $data = ['collumn'=>'value']
     * @param  mixed $condition=['collumn'=>'value']
     * @return void
     */
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