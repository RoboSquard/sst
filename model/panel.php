<?php
class Panel extends Model
{
    protected $collumn = ['title','user_id','group_id','color','panel_type','is_deleted'];
    protected $table = 'panels';
    public function storeData($data)
    {
        $inserted_data =array();
        foreach ($this->collumn as $col) {
        
            if(isset($data[$col]))
            $inserted_data[$col] = $data[$col];
        }
        $inserted_data['user_id'] = login_user();
        $inserted_data['color'] = THEME_COLOR;
        return $this->store($this->table,$inserted_data);
    }
    public function getActiveAllPanel($whereArray = [])
    {
        $whereArray['user_id'] = login_user();
        $whereArray['is_deleted'] = 0;
        return $this->getAll($this->table, $whereArray);
    }
    public function getGroupPanel( $whereArray)
    {
        $whereArray['user_id'] = login_user();
        return $this->getPanelRecord($whereArray);
    }
    
    public function getPenel($whereArray)
    {
        $whereArray['user_id'] = login_user();
        return $this->getWhere($this->table,$whereArray);
    }

    public function like_search($search_arg = [],$whereArray = [])
    {
        $whereArray['user_id'] = login_user();
        return $this->like($this->table,$search_arg,$whereArray );
    }

    public function getAllPanels()
    {
        return $this->getAll($this->table);
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
        $condition['user_id'] = login_user();
        return $this->update($this->table,$updated_data,$condition);
    }
}

?>