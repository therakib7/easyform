<?php
class BaseModel {
    protected $table;
    protected $primaryKey = 'ID';
    protected $orderByColumn = '';
    protected $orderByDirection = 'ASC';

    public function find($id) {
        global $wpdb;
        $sql = $wpdb->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = %d", $id);
        return $wpdb->get_row($sql);
    }

    public function get($where = []) {
        global $wpdb;
        $conditions = '1=1';
        $placeholders = [];
        foreach ($where as $key => $value) {
            $conditions .= " AND {$key} = %s";
            $placeholders[] = $value;
        }
        $sql = $wpdb->prepare("SELECT * FROM {$this->table} WHERE {$conditions}", ...$placeholders);
        return $wpdb->get_results($sql);
    }

    public function getAll() {
        global $wpdb;
        $sql = "SELECT * FROM {$this->table}";
        return $wpdb->get_results($sql);
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
        $sql = "SELECT * FROM {$this->table} {$orderBy} LIMIT {$offset}, {$perPage}";
        $this->orderByColumn = '';
        $this->orderByDirection = 'ASC';
        return $wpdb->get_results($sql);
    }

    // Relationship Methods

    // One-To-One Relationship
    protected function hasOne($relatedModel, $foreignKey, $localKey) {
        global $wpdb;
        $related = new $relatedModel();
        $sql = $wpdb->prepare("SELECT * FROM {$related->table} WHERE {$foreignKey} = %s", $this->{$localKey});
        return $wpdb->get_row($sql);
    }

    // One-To-Many Relationship
    protected function hasMany($relatedModel, $foreignKey, $localKey) {
        global $wpdb;
        $related = new $relatedModel();
        $sql = $wpdb->prepare("SELECT * FROM {$related->table} WHERE {$foreignKey} = %s", $this->{$localKey});
        return $wpdb->get_results($sql);
    }

    // Belongs To (One-To-Many Inverse)
    protected function belongsTo($relatedModel, $foreignKey, $localKey) {
        global $wpdb;
        $related = new $relatedModel();
        $sql = $wpdb->prepare("SELECT * FROM {$related->table} WHERE {$related->primaryKey} = %s", $this->{$localKey});
        return $wpdb->get_row($sql);
    }

    // Has One Of Many
    protected function hasOneOfMany($relatedModel, $foreignKey, $localKey, $orderColumn) {
        global $wpdb;
        $related = new $relatedModel();
        $sql = $wpdb->prepare("SELECT * FROM {$related->table} WHERE {$foreignKey} = %s ORDER BY {$orderColumn} DESC LIMIT 1", $this->{$localKey});
        return $wpdb->get_row($sql);
    }

    // Has One Through
    protected function hasOneThrough($relatedModel, $throughModel, $firstKey, $secondKey, $localKey) {
        global $wpdb;
        $related = new $relatedModel();
        $through = new $throughModel();
        $sql = $wpdb->prepare("SELECT {$related->table}.* FROM {$related->table}
                                INNER JOIN {$through->table} ON {$through->table}.{$secondKey} = {$related->table}.{$related->primaryKey}
                                WHERE {$through->table}.{$firstKey} = %s", $this->{$localKey});
        return $wpdb->get_row($sql);
    }

    // Has Many Through
    protected function hasManyThrough($relatedModel, $throughModel, $firstKey, $secondKey, $localKey) {
        global $wpdb;
        $related = new $relatedModel();
        $through = new $throughModel();
        $sql = $wpdb->prepare("SELECT {$related->table}.* FROM {$related->table}
                                INNER JOIN {$through->table} ON {$through->table}.{$secondKey} = {$related->table}.{$related->primaryKey}
                                WHERE {$through->table}.{$firstKey} = %s", $this->{$localKey});
        return $wpdb->get_results($sql);
    }
}
