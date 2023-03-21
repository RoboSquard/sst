<?php
class DefaultPanelSetting extends Model
{
    protected $collumn = ['user_id','group_id','panel_text_id','network_type','keywork','per_page_limit','page_type','is_full_text_feed'];
    protected $table = 'default_keyword';
    public function storeData($data)
    {
        $inserted_data =array();
        foreach ($this->collumn as $col) {
        
            if(isset($data[$col]))
            $inserted_data[$col] = $data[$col];
        }
        $inserted_data['user_id'] = login_user();
        $inserted_data['per_page_limit'] = KEYWORD_POST_LIMIT;
        return $this->store($this->table,$inserted_data);
    }
    public function getActiveAll()
    {
        return $this->getAll($this->table);
    }
    public function getWhereInKeyword($whereInArray,$whereArray)
    {
        $whereArray['user_id'] = login_user();
        return $this->getWhereIn($this->table,'panel_text_id',$whereInArray,$whereArray);
    }
    public function like_search($search_arg = [])
    {
        return $this->like($this->table,$search_arg);
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
    public function itemCount($whereArray=[])
    {
        return $this->countWhere($this->table,$whereArray);
    }
}
?>