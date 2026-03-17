<?php
class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = getDB();
    }

    public function all($where = '', $params = []) {
        $sql = "SELECT * FROM {$this->table}" . ($where ? " WHERE $where" : '');
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function find($id) {
        $st = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $st->execute([$id]);
        return $st->fetch();
    }

    public function findBy($col, $val) {
        // Whitelist column name to prevent SQL injection
        $col = preg_replace('/[^a-zA-Z0-9_]/', '', $col);
        $st = $this->db->prepare("SELECT * FROM {$this->table} WHERE `$col` = ? LIMIT 1");
        $st->execute([$val]);
        return $st->fetch();
    }

    public function insert($data) {
        $cols = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $st = $this->db->prepare("INSERT INTO {$this->table} ($cols) VALUES ($placeholders)");
        $st->execute(array_values($data));
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $set = implode(',', array_map(fn($k) => "$k=?", array_keys($data)));
        $st = $this->db->prepare("UPDATE {$this->table} SET $set WHERE id=?");
        $st->execute([...array_values($data), $id]);
    }

    public function delete($id) {
        $this->db->prepare("DELETE FROM {$this->table} WHERE id=?")->execute([$id]);
    }

    public function count($where = '', $params = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table}" . ($where ? " WHERE $where" : '');
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetchColumn();
    }
}
