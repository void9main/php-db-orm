<?php

class db
{
    public $table="";

    public $where=[];

    public $order="";

    public $select = "";
    
    public $insert = [];

    public $update = [];

    public $delete = "";

    public $result;

    public $sql = "";

    public $mysqli;

    public $debug = false;

    public $ressolo = false;

    public function __construct($host,$user,$pass,$db, $port=3306)
    {
        $mysqli = new mysqli($host,$user,$pass,$db,$port);
        if ($mysqli->connect_error) {
            die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
        }
        $this->mysqli = $mysqli;

        $this->sql = "";
    }

    public function table($table)
    {
        $this->table = $table;

        return $this;
    }

    public function where($where)
    {
        $this->where = $where;

        return $this;
    }

    public function order($order)
    {
        $this->order = $order;

        return $this;
    }

    public function select($select)
    {
        $this->select = $select;

        return $this;
    }

    public function insert($insert)
    {
        $this->insert = $insert;

        return $this;
    }

    public function update($update)
    {
        $this->update = $update;

        return $this;
    }

    public function delete()
    {
        $this->delete = "delete";

        return $this;
    }

    public function query()
    {
        if ($this->select) {
            $this->sql = "SELECT ".$this->select." FROM `".$this->table."`";
            $this->ressolo = true;
        }
        if ($this->insert) {
            $this->sql = "INSERT INTO `" . $this->table . "` (`".implode("`,`", array_keys($this->insert))."`)VALUES('" . implode("','", array_values($this->insert))."')";
        }
        if ($this->update) {
            $this->sql = "UPDATE `" . $this->table . "` SET ".implode(",", array_map(function ($v, $k) {
                if(is_int($v)){
                    return "`".$k."`=".$v;
                }else{
                    return "`".$k."`='".$v."'";
                }
            }, $this->update, array_keys($this->update)));
        }
        if ($this->delete) {
            $this->sql = "DELETE FROM `" . $this->table . "`";
        }
        if ($this->where) {
            if (!empty($this->where)) {
                $this->sql .= " WHERE ".implode(" AND ", array_map(function ($v, $k) {
                    if (is_int($v)) {
                        return "`".$k."`=".$v;
                    }else{
                        return "`".$k."`='".$v."'";
                    }
                }, $this->where, array_keys($this->where)));
            }
        }
        if ($this->order) {
            $this->sql .= " ORDER BY `".$this->order."` DESC ";
        }

        if($this->debug){
            exit($this->sql);
        }

        $this->result = $this->mysqli->query($this->sql);

        if ($this->ressolo) {
            return $this;
        }else{
            return $this->result;
        }
    }

    public function fetch()
    {
        $data = $this->result->fetch_assoc();

        return $data;
    }

    public function fetchAll()
    {
        $data = $this->result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function debug(){
        
        $this->debug = true;

        return $this;
    }

    public function __destruct()
    {
        foreach ($this as &$value) {
            $value = null;
        }
    }
    public function __debugInfo()
    {
        return get_object_vars($this);
    }
}

/*
//增
$res = (new \db($host,$user,$pass,$db,$port))->table("list")->insert(["title"=>"测试标题", "img"=>"18"])->query();

//删
$res = (new \db($host,$user,$pass,$db,$port))->table("list")->where(["id"=>1])->delete()->query();

//改
$res = (new \db($host,$user,$pass,$db,$port))->table("list")->where(["id"=>1])->update(["title"=>"785122", "img"=>"9852"])->query();

//查
$data = (new \db($host,$user,$pass,$db,$port))->table("list")->select("*")->query()->fetch();

$data = (new \db($host,$user,$pass,$db,$port))->table("list")->select("*")->query()->fetchAll(); 
*/

$host = "localhost";
$user = "root";
$pass = "root";
$db = "";
$port = 3306;

$data = (new \db($host, $user, $pass, $db, $port))->table("list")->select("*")->where(["id"=>6])->query()->fetch(); 

var_dump($data);
