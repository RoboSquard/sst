<?php
class Group extends Model
{
    protected $collumn = ['name','user_id','is_deleted'];
    protected $table = 'groups';

    public function storeData($data)
    {
        $inserted_data =array();
        foreach ($this->collumn as $col) {
        
            if(isset($data[$col]))
            $inserted_data[$col] = $data[$col];
        }
        $inserted_data['user_id'] = login_user();
        $inserted_data['is_deleted'] = 0;
        
        return $this->store($this->table,$inserted_data);
    }
    public function getById($id)
    {
        $where = [ 'id'=>$id, 'is_deleted'=>0 ];
        return $this->getWhere($this->table,$where);
    }
    public function getActiveAll()
    {
        return $this->getAll($this->table,[ 'user_id' => login_user(),'is_deleted' => 0 ]);
    }
    public function getGroup($search_arg = [])
    {
        return $this->getWhere($this->table,$search_arg,[ 'user_id' => login_user() ,'is_deleted' => 0]);
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
    public function deleteGroup($id)
    {
        $this->delete($this->table,['id'=>$id]);
        return $this->getWhere($this->table,['id'=>$id,'is_deleted'=>0]);
    }
    function itemCount(){
        $whereArray = [];
        $whereArray['user_id'] = login_user();
        $whereArray['is_deleted'] = 0;
        return $this->countWhere($this->table, $whereArray);
    }
}

?>

