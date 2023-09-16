

<?php

class BaseModel {
	
    protected $table;
    protected $primaryKey = 'ID';
    protected $orderByColumn = '';
    protected $orderByDirection = 'ASC';

    public function find($id) {
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = {$id}");
    }

    public function get($where = []) {
        global $wpdb;
        $conditions = '1=1';
        foreach ($where as $key => $value) {
            $conditions .= " AND {$key} = '{$value}'";
        }
        return $wpdb->get_results("SELECT * FROM {$this->table} WHERE {$conditions}");
    }

    public function getAll() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$this->table}");
    }

    public function insert($data) {
        global $wpdb;
        $wpdb->insert($this->table, $data);
        return $wpdb->insert_id;
    }

    public function update($id, $data) {
        global $wpdb;
        return $wpdb->update($this->table, $data, [$this->primaryKey => $id]);
    }

    public function delete($id) {
        global $wpdb;
        return $wpdb->delete($this->table, [$this->primaryKey => $id]);
    }

    public function orderBy($column, $direction = 'ASC') {
        $this->orderByColumn = $column;
        $this->orderByDirection = $direction;
        return $this;
    }

    public function paginate($perPage = 10, $currentPage = 1) {
        global $wpdb;
        $offset = ($currentPage - 1) * $perPage;

        $orderBy = '';
        if ($this->orderByColumn) {
            $orderBy = "ORDER BY {$this->orderByColumn} {$this->orderByDirection}";
        }

        $rows = $wpdb->get_results("SELECT * FROM {$this->table} {$orderBy} LIMIT {$offset}, {$perPage}");

        // Reset order by for the next query
        $this->orderByColumn = '';
        $this->orderByDirection = 'ASC';

        return $rows;
    }

    // One-To-One Relationship
    protected function hasOne($relatedModel, $foreignKey, $localKey) {
        global $wpdb;
        $related = new $relatedModel();
        return $wpdb->get_row("SELECT * FROM {$related->table} WHERE {$foreignKey} = {$this->{$localKey}}");
    }

    // One-To-Many Relationship
    protected function hasMany($relatedModel, $foreignKey, $localKey) {
        global $wpdb;
        $related = new $relatedModel();
        return $wpdb->get_results("SELECT * FROM {$related->table} WHERE {$foreignKey} = {$this->{$localKey}}");
    }

    // Belongs To (One-To-Many Inverse)
    protected function belongsTo($relatedModel, $foreignKey, $localKey) {
        global $wpdb;
        $related = new $relatedModel();
        return $wpdb->get_row("SELECT * FROM {$related->table} WHERE {$related->primaryKey} = {$this->{$localKey}}");
    }

    // Has One Of Many
    protected function hasOneOfMany($relatedModel, $foreignKey, $localKey, $orderColumn) {
        global $wpdb;
        $related = new $relatedModel();
        return $wpdb->get_row("SELECT * FROM {$related->table} WHERE {$foreignKey} = {$this->{$localKey}} ORDER BY {$orderColumn} DESC LIMIT 1");
    }

    // Has One Through
    protected function hasOneThrough($relatedModel, $throughModel, $firstKey, $secondKey, $localKey) {
        global $wpdb;
        $related = new $relatedModel();
        $through = new $throughModel();
        return $wpdb->get_row("SELECT {$related->table}.* FROM {$related->table}
                                INNER JOIN {$through->table} ON {$through->table}.{$secondKey} = {$related->table}.{$related->primaryKey}
                                WHERE {$through->table}.{$firstKey} = {$this->{$localKey}}");
    }

    // Has Many Through
    protected function hasManyThrough($relatedModel, $throughModel, $firstKey, $secondKey, $localKey) {
        global $wpdb;
        $related = new $relatedModel();
        $through = new $throughModel();
        return $wpdb->get_results("SELECT {$related->table}.* FROM {$related->table}
                                   INNER JOIN {$through->table} ON {$through->table}.{$secondKey} = {$related->table}.{$related->primaryKey}
                                   WHERE {$through->table}.{$firstKey} = {$this->{$localKey}}");
    }
}
