<?php
/**
 * FileName Model.class.php.
 * User: yangzie1192@163.com
 * Version:0.1
 * Date: 2015/12/20
 * Time: 20:28
 */
class Model
{
    //保存连接信息
    public static $link=null;
    //保存表名
    public $table=null;
    //初始化表信息
    private $opt;
    //记录发送的sql
    public static $sqls=array();
    public function __construct($table=null)
    {
        if(is_null($table)) {
            $this->table=C('DB_PREFIX') . $this->table;
        }else{
            $this->table=C('DB_PREFIX').$table;
        }
        //连接数据库
        $this->_connect();
        //初始化sql
        $this->_opt();
    }
    private function _opt()
    {
        $this->opt=array(
            'field'=>'*',
            'where'=>'',
            'group'=>'',
            'having'=>'',
            'order'=>'',
            'limit'=>''
        );
    }
    private function _connect()
    {
        if(is_null(self::$link)){
            if(empty(C('DB_DATABASE'))) halt("请先配置数据库相关选项");
            $link=new mysqli(C('DB_HOST'),C('DB_USER'),C('DB_PASSWORD'),C('DB_DATABASE'),C('DB_PORT'));
            if($link->connect_error) halt("数据库连接错误，请检查相关配置项");
            $link->set_charset(C('DB_CHARSET'));
            self::$link=$link;
        }
    }

    public function query($sql)
    {
        self::$sqls[]=$sql;
        $link=self::$link;
        $result=$link->query($sql);
        if($link->errno) halt('mysql错误:'.$link->error.'<br>SQL:'.$sql);
        $rows=array();
        while($row=$result->fetch_assoc()){
              $rows[]=$row;
        }
        //初始化
        $this->_opt();
        return $rows;
    }

    public function all()
    {
        $sql="SELECT ".$this->opt['field']." FROM ".$this->table.$this->opt['where'].$this->opt['group'].$this->opt['having'].$this->opt['order'].$this->opt['limit'];
        self::$sqls[]=$sql;
        return $this->query($sql);
    }

    public function field($field)
    {
        $this->opt['field']=$field;
        return $this;
    }

    public function where($where)
    {
        $this->opt['where']=" WHERE ". $where;
        return $this;
    }

    public function order($order)
    {
        $this->opt['order']=" ORDER BY  ". $order;
        return $this;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function  limit($limit)
    {
        $this->opt['limit']=" LIMIT ". $limit;
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function find()
    {
        $data=$this->limit(1)->all();
        $data=current($data);
        return $data;
    }
    //给find的一个别名
    public function one()
    {
        return $this->find();
    }


    public function exe($sql)
    {
        self::$sqls[]=$sql;
        $link=self::$link;
        $bool=$link->query($sql);
        $this->_opt();
        if(is_object($bool)){
            halt("请使用query()方法");
        }
        if($bool){
            return $link->insert_id?$link->insert_id:$link->affected_rows;
        }else{
            halt('mysql错误：'.$link->error.'<br/>.SQL:'.$sql);
        }
    }

    //不要删除全部数据
    public function delete()
    {
        if(empty($this->opt['where'])){
            halt('删除语句必须要where条件');
        }
        $sql="DELETE FROM ".$this->table.$this->opt['where'];
        return $this->exe($sql);
    }

    private function _safe_str($str)
    {
        if(get_magic_quotes_gpc()){
            $str=stripcslashes($str);
        }
        return self::$link->real_escape_string($str);
    }

    public function add($data=NULL)
    {
        if(empty($data)){
            $data=$_POST;
        }
        $fields='';
        $values='';
        foreach ($data as $f => $va) {
            $fields.='`'.$this->_safe_str($f).'`,';
            $values.="'".$this->_safe_str($va)."',";
        }
        $fields=trim($fields,',');
        $values=trim($values,',');
        $sql="INSERT INTO ".$this->table." (".$fields.')'.' VALUES ('.$values.')';
        return $this->exe($sql);
    }

    public function update($data=NULL)
    {
        if(empty($this->opt['where']))  halt('删除语句必须有where条件');
        if(empty($data)) $data=$_POST;
        $values='';
        foreach($data as $item => $va){
            $values.="`".$this->_safe_str($item)."`='".$this->_safe_str($va)."',";
        }
        $values=trim($values,',');

        $sql="UPDATE ".$this->table." SET".$values.$this->opt['where'];
       // p($sql);
        return $this->exe($sql);
    }






}