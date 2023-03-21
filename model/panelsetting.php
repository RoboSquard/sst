<?php
class Panelsetting extends Model
{
    protected $collumn = ['panel_id','network_type','keywork','per_page_limit','page_type','is_full_text_feed', 'is_scheduled'];
    protected $table = 'keywords';
    public function storeData($data)
    {
        $inserted_data =array();
        foreach ($this->collumn as $col) {
        
            if(isset($data[$col]))
            $inserted_data[$col] = $data[$col];
        }
        $inserted_data['per_page_limit'] = KEYWORD_POST_LIMIT;
        return $this->store($this->table,$inserted_data);
    }
    public function getActiveAll($whereArray = [])
    {
        return $this->getAll($this->table, $whereArray);
    }

    
    public function like_search($search_arg = [])
    {
        return $this->like($this->table,$search_arg);
    }
    public function itemCount($whereArray=[])
    {
        return $this->countWhere($this->table,$whereArray);
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

    public function where($whereArray = [])
    {
        return $this->getAll($this->table, $whereArray);
    }

    public function deleteWhere($whereArray = [])
    {
        return $this->delete($this->table, $whereArray, false);
    }
}

?>
