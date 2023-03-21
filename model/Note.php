<?php
class Note extends Model
{
    protected $collumn = ['user_id','panel_id','post_title', 'note'];
    protected $table = 'notes';

    public function storeData($data)
    {
        $inserted_data =array();
        foreach ($this->collumn as $col) {
        
            if(isset($data[$col]))
            $inserted_data[$col] = $data[$col];
        }
        $inserted_data['user_id'] = login_user();
        
        return $this->store($this->table,$inserted_data);
    }
    public function getById($id)
    {
        $where = [ 'id'=>$id ];
        return $this->getWhere($this->table,$where);
    }
    public function getActiveAll()
    {
        return $this->getAll($this->table,[ 'user_id' => login_user()]);
    }
    public function getNote($search_arg = [])
    {
        $search_arg['post_title'] = str_replace("'", "''", $search_arg['post_title']);
        $search_arg['user_id'] = login_user();
        return $this->getWhere($this->table, $search_arg);
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
    public function deleteNote($id)
    {
        $this->delete($this->table,['id'=>$id], false);
        return $this->getWhere($this->table,['id'=>$id]);
    }
}


