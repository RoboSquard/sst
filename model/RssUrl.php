<?php

class RssUrl extends Model
{
    protected $collumn = ['panel_id','url'];
    protected $table = 'rss_urls';

    public function storeData($data)
    {
        $inserted_data =array();
        foreach ($this->collumn as $col) {
        
            if(isset($data[$col]))
            $inserted_data[$col] = $data[$col];
        }
        return $this->store($this->table,$inserted_data);
    }
    public function getById($id)
    {
        $where = [ 'id'=>$id ];
        return $this->getWhere($this->table,$where);
    }
    public function getActiveAll($panel_id = null)
    {
        return $this->getAll($this->table,[ 'panel_id' => $panel_id]);
    }
    public function getRssUrl($search_arg = [])
    {
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
    public function deleteRssUrl($id)
    {
        $this->delete($this->table,['id'=>$id], false);
        return $this->getWhere($this->table,['id'=>$id]);
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